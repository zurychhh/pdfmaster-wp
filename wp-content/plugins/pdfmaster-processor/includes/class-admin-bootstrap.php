<?php
/**
 * Autoloaded bootstrap to register admin settings hooks without editing main plugin file.
 */

declare(strict_types=1);

namespace PDFMaster\Processor;

use PDFMaster\Processor\Admin\AdminSettings;

if (! defined('ABSPATH')) {
    exit;
}

class Admin_Bootstrap
{
    public function __construct()
    {
        if (is_admin()) {
            add_action('plugins_loaded', [$this, 'init'], 25);
        }
    }

    public function init(): void
    {
        $settings = new AdminSettings();
        add_action('admin_menu', [$settings, 'register_settings_page']);
        add_action('admin_init', [$settings, 'register_settings']);
        add_action('wp_ajax_pdfm_test_stirling_connection', [$settings, 'test_connection']);
    }
}

// Instantiate immediately so hooks are registered.
new Admin_Bootstrap();
