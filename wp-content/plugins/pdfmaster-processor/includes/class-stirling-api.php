<?php
/**
 * Stirling PDF API integration stub.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

use WP_Error;

if (! defined('ABSPATH')) {
    exit;
}

class StirlingApi
{
    private string $endpoint;

    public function __construct()
    {
        $this->endpoint = (string) apply_filters('pdfm_stirling_api_endpoint', 'https://api.stirlingpdf.com');
    }

    /**
     * Dispatch API request to process PDFs.
     *
     * @param array<int, string> $files
     */
    public function process(array $files, string $operation): array|WP_Error
    {
        // TODO: Integrate with Stirling PDF API using authenticated requests.
        unset($files, $operation);

        return new WP_Error('pdfm_api_stub', __('Stirling PDF integration not implemented yet.', 'pdfmaster-processor'));
    }
}
