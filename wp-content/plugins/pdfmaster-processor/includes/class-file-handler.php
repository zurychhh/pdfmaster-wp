<?php
/**
 * File handling utilities for PDFMaster processor.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class FileHandler
{
    private string $upload_dir;

    public function __construct()
    {
        $wp_upload_dir = wp_upload_dir();
        $this->upload_dir = trailingslashit($wp_upload_dir['basedir']) . 'pdfmaster/';
    }

    /**
     * Validate uploaded file payload.
     */
    public function validate(array $file): bool|WP_Error
    {
        if (empty($file['name']) || empty($file['tmp_name'])) {
            return new WP_Error('pdfm_invalid_upload', __('Invalid upload payload.', 'pdfmaster-processor'));
        }

        $extension = strtolower((string) pathinfo((string) $file['name'], PATHINFO_EXTENSION));
        if (! in_array($extension, ['pdf'], true)) {
            return new WP_Error('pdfm_unsupported_type', __('Only PDF files are accepted.', 'pdfmaster-processor'));
        }

        if (! isset($file['size']) || (int) $file['size'] <= 0) {
            return new WP_Error('pdfm_invalid_size', __('Uploaded file is empty.', 'pdfmaster-processor'));
        }

        return true;
    }

    /**
     * Persist file to plugin-specific storage.
     */
    public function persist(array $file): string|WP_Error
    {
        wp_mkdir_p($this->upload_dir);

        $destination = $this->upload_dir . wp_unique_filename($this->upload_dir, (string) $file['name']);

        if (! move_uploaded_file((string) $file['tmp_name'], $destination)) {
            return new WP_Error('pdfm_upload_failure', __('Unable to store uploaded file.', 'pdfmaster-processor'));
        }

        return $destination;
    }

    /**
     * Retrieve all stored files.
     *
     * @return array<int, string>
     */
    public function list_stored_files(): array
    {
        if (! is_dir($this->upload_dir)) {
            return [];
        }

        $files = array_diff(scandir($this->upload_dir, SCANDIR_SORT_ASCENDING) ?: [], ['.', '..']);

        return array_map(static fn (string $file): string => $this->upload_dir . $file, $files);
    }

    /**
     * Remove a stored file.
     */
    public function remove(string $path): bool
    {
        return is_file($path) ? unlink($path) : false;
    }
}
