<?php
/**
 * Admin settings for Stripe configuration.
 */

declare(strict_types=1);

namespace PDFMaster\Payments\Admin;

if (! defined('ABSPATH')) {
    exit;
}

class PaymentsAdmin
{
    private const OPTION_KEY = 'pdfm_stripe_settings';

    public function register_hooks(): void
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_filter('pdfm_stripe_publishable_key', [$this, 'filter_publishable_key']);
        add_filter('pdfm_stripe_secret_key', [$this, 'filter_secret_key']);
        add_filter('pdfm_stripe_test_mode', [$this, 'filter_test_mode']);
    }

    public function add_settings_page(): void
    {
        add_options_page(
            __('PDFMaster Payments', 'pdfmaster-payments'),
            __('PDFMaster Payments', 'pdfmaster-payments'),
            'manage_options',
            'pdfm-payments',
            [$this, 'render_page']
        );
    }

    public function register_settings(): void
    {
        register_setting(self::OPTION_KEY, self::OPTION_KEY, [
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_settings'],
            'default' => [
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
                'test_mode' => 1,
            ],
        ]);

        add_settings_section('pdfm_payments_main', __('Stripe Settings', 'pdfmaster-payments'), '__return_false', self::OPTION_KEY);

        add_settings_field('publishable_key', __('Publishable Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            printf('<input type="text" name="%s[publishable_key]" value="%s" class="regular-text" />', esc_attr(self::OPTION_KEY), esc_attr($options['publishable_key'] ?? ''));
        }, self::OPTION_KEY, 'pdfm_payments_main');

        add_settings_field('secret_key', __('Secret Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            printf('<input type="password" name="%s[secret_key]" value="%s" class="regular-text" />', esc_attr(self::OPTION_KEY), esc_attr($options['secret_key'] ?? ''));
        }, self::OPTION_KEY, 'pdfm_payments_main');

        add_settings_field('webhook_secret', __('Webhook Secret', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            printf('<input type="password" name="%s[webhook_secret]" value="%s" class="regular-text" />', esc_attr(self::OPTION_KEY), esc_attr($options['webhook_secret'] ?? ''));
        }, self::OPTION_KEY, 'pdfm_payments_main');

        add_settings_field('test_mode', __('Test Mode', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $checked = ! empty($options['test_mode']) ? 'checked' : '';
            printf('<label><input type="checkbox" name="%s[test_mode]" value="1" %s /> %s</label>', esc_attr(self::OPTION_KEY), $checked, esc_html__('Enable Stripe Test Mode', 'pdfmaster-payments'));
        }, self::OPTION_KEY, 'pdfm_payments_main');
    }

    public function render_page(): void
    {
        echo '<div class="wrap"><h1>' . esc_html__('PDFMaster Payments', 'pdfmaster-payments') . '</h1>';
        echo '<form method="post" action="options.php">';
        settings_fields(self::OPTION_KEY);
        do_settings_sections(self::OPTION_KEY);
        submit_button(__('Save Settings', 'pdfmaster-payments'));
        echo '</form></div>';
    }

    public function sanitize_settings(array $input): array
    {
        return [
            'publishable_key' => sanitize_text_field($input['publishable_key'] ?? ''),
            'secret_key' => sanitize_text_field($input['secret_key'] ?? ''),
            'webhook_secret' => sanitize_text_field($input['webhook_secret'] ?? ''),
            'test_mode' => empty($input['test_mode']) ? 0 : 1,
        ];
    }

    public function filter_publishable_key(string $value): string
    {
        $options = get_option(self::OPTION_KEY, []);
        return (string) ($options['publishable_key'] ?? $value);
    }

    public function filter_secret_key(string $value): string
    {
        $options = get_option(self::OPTION_KEY, []);
        return (string) ($options['secret_key'] ?? $value);
    }

    public function filter_test_mode(bool $value): bool
    {
        $options = get_option(self::OPTION_KEY, []);
        return (bool) ($options['test_mode'] ?? $value);
    }
}
