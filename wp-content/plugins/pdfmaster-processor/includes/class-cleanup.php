<?php
/**
 * Cleanup service for expiring processed files.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

if (! defined('ABSPATH')) {
    exit;
}

class Cleanup
{
    private int $expiration;

    public function __construct(private readonly FileHandler $file_handler)
    {
        $this->expiration = (int) apply_filters('pdfm_processor_file_expiration', HOUR_IN_SECONDS);
    }

    public function purge(): void
    {
        $files = $this->file_handler->list_stored_files();

        foreach ($files as $file) {
            $filetime = filemtime($file);
            if ($filetime === false) {
                continue;
            }

            if ((time() - $filetime) >= $this->expiration) {
                $this->file_handler->remove($file);
            }
        }
    }
}
