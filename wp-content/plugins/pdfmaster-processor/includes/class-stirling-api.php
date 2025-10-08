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
     * @param string $operation compress|merge
     * @return string|WP_Error Absolute path to processed file
     */
    public function process(array $files, string $operation = 'compress'): string|WP_Error
    {
        if ($files === []) {
            return new WP_Error('no_files', __('No files provided for processing', 'pdfmaster-processor'));
        }

        $operation = $operation === 'merge' ? 'merge' : 'compress';

        if ($operation === 'compress') {
            return $this->compress_pdf($files[0]);
        }

        return $this->merge_pdfs($files);
    }

    private function compress_pdf(string $file_path): string|WP_Error
    {
        if (! file_exists($file_path)) {
            return new WP_Error('file_not_found', __('File does not exist', 'pdfmaster-processor'));
        }

        // Correct endpoint per Stirling PDF OpenAPI (/v1/api-docs): /api/v1/misc/compress-pdf
        $url = $this->get_endpoint() . '/api/v1/misc/compress-pdf';

        $boundary = wp_generate_password(24, false);
        $body = $this->build_multipart_body([
            'fileInput' => [
                'filename' => basename($file_path),
                'content'  => file_get_contents($file_path),
                'mime'     => 'application/pdf',
            ],
        ], [
            // Align with OptimizePdfRequest schema
            'optimizeLevel'      => '5',
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

        $filename = uniqid('pdf_' . $suffix . '_') . '.pdf';
        $path = $processed_dir . $filename;
        if (file_put_contents($path, $content) === false) {
            return new WP_Error('save_failed', __('Failed to save processed file', 'pdfmaster-processor'));
        }

        return $path;
    }
}
