<?php
/**
 * Stirling PDF API integration.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class StirlingApi
{
    private function get_endpoint(): string
    {
        $endpoint = (string) get_option('pdfm_stirling_endpoint', 'http://localhost:8080');
        return rtrim($endpoint, '/');
    }

    private function get_timeout(): int
    {
        return (int) get_option('pdfm_stirling_timeout', 30);
    }

    /**
     * @param array<int,string> $files
     * @param string $operation compress|merge|split
     * @param int $level Optimize level for compression (1-9), only used for compress
     * @param string $pages Page ranges for split (e.g., "1-5" or "1,3,5-7")
     * @return string|WP_Error Absolute path to processed file
     */
    public function process(array $files, string $operation = 'compress', int $level = 5, string $pages = '', string $format = 'jpg'): string|WP_Error
    {
        if ($files === []) {
            return new WP_Error('no_files', __('No files provided for processing', 'pdfmaster-processor'));
        }

        $operation = in_array($operation, ['compress', 'merge', 'split', 'img-to-pdf', 'pdf-to-img'], true) ? $operation : 'compress';

        if ($operation === 'compress') {
            $level = max(1, min(9, $level));
            return $this->compress_pdf($files[0], $level);
        }

        if ($operation === 'merge') {
            return $this->merge_pdfs($files);
        }

        if ($operation === 'split') {
            return $this->split_pdf($files[0], $pages);
        }

        if ($operation === 'img-to-pdf') {
            return $this->images_to_pdf($files);
        }

        if ($operation === 'pdf-to-img') {
            return $this->pdf_to_images($files[0], $format);
        }

        return new WP_Error('invalid_operation', __('Invalid operation', 'pdfmaster-processor'));
    }

    private function compress_pdf(string $file_path, int $level = 5): string|WP_Error
    {
        if (! file_exists($file_path)) {
            return new WP_Error('file_not_found', __('File does not exist', 'pdfmaster-processor'));
        }

        $url = $this->get_endpoint() . '/api/v1/misc/compress-pdf';

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body([
            'fileInput' => [
                'filename' => basename($file_path),
                'content'  => file_get_contents($file_path),
                'mime'     => 'application/pdf',
            ],
        ], [
            // Per Swagger OptimizePdfRequest (required fields)
            'optimizeLevel'      => (string) $level,
            'expectedOutputSize' => '25KB',
            'linearize'          => 'false',
            'normalize'          => 'false',
            'grayscale'          => 'false',
        ], $boundary);

        $response = wp_remote_post($url, [
            'timeout' => $this->get_timeout(),
            'headers' => [ 'Content-Type' => 'multipart/form-data; boundary=' . $boundary ],
            'body'    => $body,
        ]);

        return $this->handle_response($response, 'compressed');
    }

    /**
     * @param array<int,string> $file_paths
     */
    private function merge_pdfs(array $file_paths): string|WP_Error
    {
        if (count($file_paths) < 2) {
            return new WP_Error('insufficient_files', __('Merge requires at least 2 files', 'pdfmaster-processor'));
        }

        $url = $this->get_endpoint() . '/api/v1/general/merge-pdfs';

        $files = [];
        foreach ($file_paths as $index => $path) {
            if (! file_exists($path)) {
                return new WP_Error('file_not_found', sprintf(__('File not found: %s', 'pdfmaster-processor'), $path));
            }
            $files["fileInput[$index]"] = [
                'filename' => basename($path),
                'content'  => file_get_contents($path),
                'mime'     => 'application/pdf',
            ];
        }

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body($files, [], $boundary);

        $response = wp_remote_post($url, [
            'timeout' => $this->get_timeout(),
            'headers' => [ 'Content-Type' => 'multipart/form-data; boundary=' . $boundary ],
            'body'    => $body,
        ]);

        return $this->handle_response($response, 'merged');
    }

    private function split_pdf(string $file_path, string $pages): string|WP_Error
    {
        if (! file_exists($file_path)) {
            return new WP_Error('file_not_found', __('File does not exist', 'pdfmaster-processor'));
        }

        if ($pages === '') {
            return new WP_Error('no_pages', __('Please specify page numbers', 'pdfmaster-processor'));
        }

        $url = $this->get_endpoint() . '/api/v1/general/rearrange-pages';

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body([
            'fileInput' => [
                'filename' => basename($file_path),
                'content'  => file_get_contents($file_path),
                'mime'     => 'application/pdf',
            ],
        ], [
            'pageNumbers' => $pages,
        ], $boundary);

        $response = wp_remote_post($url, [
            'timeout' => $this->get_timeout(),
            'headers' => [ 'Content-Type' => 'multipart/form-data; boundary=' . $boundary ],
            'body'    => $body,
        ]);

        return $this->handle_response($response, 'split');
    }

    /**
     * @param array<string,array{filename:string,content:string,mime:string}> $files
     * @param array<string,string> $fields
     */
    private function build_multipart_body(array $files, array $fields, string $boundary): string
    {
        $body = '';

        foreach ($fields as $name => $value) {
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Disposition: form-data; name=\"{$name}\"\r\n\r\n";
            $body .= $value . "\r\n";
        }

        foreach ($files as $name => $file) {
            $body .= "--{$boundary}\r\n";
            $body .= "Content-Disposition: form-data; name=\"{$name}\"; filename=\"{$file['filename']}\"\r\n";
            $body .= "Content-Type: {$file['mime']}\r\n\r\n";
            $body .= $file['content'] . "\r\n";
        }

        $body .= "--{$boundary}--\r\n";
        return $body;
    }

    private function handle_response($response, string $suffix): string|WP_Error
    {
        if (is_wp_error($response)) {
            return new WP_Error('api_error', 'Stirling PDF API error: ' . $response->get_error_message());
        }

        $code = (int) wp_remote_retrieve_response_code($response);
        if ($code !== 200) {
            return new WP_Error('api_error', 'API returned HTTP ' . $code . ': ' . wp_remote_retrieve_body($response));
        }

        $content = (string) wp_remote_retrieve_body($response);
        if ($content === '') {
            return new WP_Error('empty_response', __('API returned empty body', 'pdfmaster-processor'));
        }

        $upload_dir = wp_upload_dir();
        $processed_dir = trailingslashit($upload_dir['basedir']) . 'pdfmaster/processed/';
        if (! file_exists($processed_dir)) {
            wp_mkdir_p($processed_dir);
        }

        // Detect file type from content
        $extension = '.pdf';
        if ($suffix === 'pdf_to_images') {
            // Check if content is ZIP
            if (substr($content, 0, 2) === 'PK') { // ZIP magic bytes
                $extension = '.zip';
            }
        }

        $filename = uniqid('pdf_' . $suffix . '_') . $extension;
        $path = $processed_dir . $filename;
        if (file_put_contents($path, $content) === false) {
            return new WP_Error('save_failed', __('Failed to save processed file', 'pdfmaster-processor'));
        }

        return $path;
    }

    /**
     * @param array<int,string> $image_paths
     */
    private function images_to_pdf(array $image_paths): string|WP_Error
    {
        if (count($image_paths) === 0) {
            return new WP_Error('no_images', __('No images provided', 'pdfmaster-processor'));
        }

        $url = $this->get_endpoint() . '/api/v1/convert/img/pdf';

        $files = [];
        foreach ($image_paths as $index => $path) {
            if (! file_exists($path)) {
                return new WP_Error('file_not_found', sprintf(__('File not found: %s', 'pdfmaster-processor'), $path));
            }

            // Validate image type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? finfo_file($finfo, $path) : '';
            if ($finfo) {
                finfo_close($finfo);
            }

            if (! in_array($mime, ['image/jpeg', 'image/png', 'image/bmp'], true)) {
                return new WP_Error('invalid_image', __('Only JPG, PNG, and BMP images supported', 'pdfmaster-processor'));
            }

            $files["fileInput[$index]"] = [
                'filename' => basename($path),
                'content'  => file_get_contents($path),
                'mime'     => $mime,
            ];
        }

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body($files, [
            'fitOption' => 'fitDocumentToImage',
            'colorType' => 'color',
        ], $boundary);

        $response = wp_remote_post($url, [
            'timeout' => $this->get_timeout(),
            'headers' => [ 'Content-Type' => 'multipart/form-data; boundary=' . $boundary ],
            'body'    => $body,
        ]);

        return $this->handle_response($response, 'images_to_pdf');
    }

    private function pdf_to_images(string $pdf_path, string $format = 'jpg'): string|WP_Error
    {
        if (! file_exists($pdf_path)) {
            return new WP_Error('file_not_found', __('PDF file not found', 'pdfmaster-processor'));
        }

        $format = in_array($format, ['jpg', 'png'], true) ? $format : 'jpg';
        $url = $this->get_endpoint() . '/api/v1/convert/pdf/img';

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body([
            'fileInput' => [
                'filename' => basename($pdf_path),
                'content'  => file_get_contents($pdf_path),
                'mime'     => 'application/pdf',
            ],
        ], [
            'imageFormat' => $format,
            'singleOrMultiple' => 'multiple',
            'colorType' => 'color',
            'dpi' => '300',
        ], $boundary);

        $response = wp_remote_post($url, [
            'timeout' => $this->get_timeout(),
            'headers' => [ 'Content-Type' => 'multipart/form-data; boundary=' . $boundary ],
            'body'    => $body,
        ]);

        return $this->handle_response($response, 'pdf_to_images');
    }
}
