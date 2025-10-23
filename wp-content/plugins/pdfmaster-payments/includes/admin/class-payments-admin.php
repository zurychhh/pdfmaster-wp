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
                'mode' => 'test',
                'test_publishable_key' => '',
                'test_secret_key' => '',
                'test_webhook_secret' => '',
                'live_publishable_key' => '',
                'live_secret_key' => '',
                'live_webhook_secret' => '',
                // Legacy fields for backward compatibility
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
                'test_mode' => 1,
            ],
        ]);

        add_settings_section('pdfm_payments_mode', __('Payment Mode', 'pdfmaster-payments'), function() {
            $options = get_option(self::OPTION_KEY, []);
            $mode = $options['mode'] ?? 'test';
            if ($mode === 'live') {
                echo '<div class="notice notice-warning inline"><p><strong>⚠️ Live Mode Active</strong> - Real payments are being processed.</p></div>';
            }
        }, self::OPTION_KEY);

        add_settings_field('mode', __('Stripe Mode', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $mode = $options['mode'] ?? 'test';
            ?>
            <fieldset>
                <label>
                    <input type="radio" name="<?php echo esc_attr(self::OPTION_KEY); ?>[mode]" value="test" <?php checked($mode, 'test'); ?> />
                    <strong>Test Mode</strong> (safe for development)
                </label><br>
                <label>
                    <input type="radio" name="<?php echo esc_attr(self::OPTION_KEY); ?>[mode]" value="live" <?php checked($mode, 'live'); ?> />
                    <strong>Live Mode</strong> (real payments)
                </label>
            </fieldset>
            <?php
        }, self::OPTION_KEY, 'pdfm_payments_mode');

        add_settings_section('pdfm_payments_test', __('Test Mode Keys', 'pdfmaster-payments'), function() {
            echo '<p>Use these keys for testing with Stripe test cards (4242 4242 4242 4242)</p>';
        }, self::OPTION_KEY);

        add_settings_field('test_publishable_key', __('Test Publishable Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            // Try new key first, fallback to legacy
            $value = $options['test_publishable_key'] ?? $options['publishable_key'] ?? '';
            printf('<input type="text" name="%s[test_publishable_key]" value="%s" class="regular-text" placeholder="pk_test_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_test');

        add_settings_field('test_secret_key', __('Test Secret Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $value = $options['test_secret_key'] ?? $options['secret_key'] ?? '';
            printf('<input type="password" name="%s[test_secret_key]" value="%s" class="regular-text" placeholder="sk_test_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_test');

        add_settings_field('test_webhook_secret', __('Test Webhook Secret', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $value = $options['test_webhook_secret'] ?? $options['webhook_secret'] ?? '';
            printf('<input type="password" name="%s[test_webhook_secret]" value="%s" class="regular-text" placeholder="whsec_test_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_test');

        add_settings_section('pdfm_payments_live', __('Live Mode Keys', 'pdfmaster-payments'), function() {
            echo '<p><strong style="color: #d63638;">⚠️ Warning:</strong> Live keys process real payments. Keep these secure.</p>';
        }, self::OPTION_KEY);

        add_settings_field('live_publishable_key', __('Live Publishable Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $value = $options['live_publishable_key'] ?? '';
            printf('<input type="password" name="%s[live_publishable_key]" value="%s" class="regular-text" placeholder="pk_live_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_live');

        add_settings_field('live_secret_key', __('Live Secret Key', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $value = $options['live_secret_key'] ?? '';
            printf('<input type="password" name="%s[live_secret_key]" value="%s" class="regular-text" placeholder="sk_live_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_live');

        add_settings_field('live_webhook_secret', __('Live Webhook Secret', 'pdfmaster-payments'), function () {
            $options = get_option(self::OPTION_KEY, []);
            $value = $options['live_webhook_secret'] ?? '';
            printf('<input type="password" name="%s[live_webhook_secret]" value="%s" class="regular-text" placeholder="whsec_..." />', esc_attr(self::OPTION_KEY), esc_attr($value));
        }, self::OPTION_KEY, 'pdfm_payments_live');
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
        $sanitized = [
            'mode' => in_array($input['mode'] ?? '', ['test', 'live'], true) ? $input['mode'] : 'test',
            'test_publishable_key' => sanitize_text_field($input['test_publishable_key'] ?? ''),
            'test_secret_key' => sanitize_text_field($input['test_secret_key'] ?? ''),
            'test_webhook_secret' => sanitize_text_field($input['test_webhook_secret'] ?? ''),
            'live_publishable_key' => sanitize_text_field($input['live_publishable_key'] ?? ''),
            'live_secret_key' => sanitize_text_field($input['live_secret_key'] ?? ''),
            'live_webhook_secret' => sanitize_text_field($input['live_webhook_secret'] ?? ''),
            // Legacy fields for backward compatibility
            'publishable_key' => sanitize_text_field($input['publishable_key'] ?? $input['test_publishable_key'] ?? ''),
            'secret_key' => sanitize_text_field($input['secret_key'] ?? $input['test_secret_key'] ?? ''),
            'webhook_secret' => sanitize_text_field($input['webhook_secret'] ?? $input['test_webhook_secret'] ?? ''),
            'test_mode' => ($input['mode'] ?? 'test') === 'test' ? 1 : 0,
        ];

        // Validate live keys if switching to live mode
        if ($sanitized['mode'] === 'live') {
            $errors = [];

            if (empty($sanitized['live_publishable_key']) || strpos($sanitized['live_publishable_key'], 'pk_live_') !== 0) {
                $errors[] = 'Live Publishable Key must start with pk_live_';
            }

            if (empty($sanitized['live_secret_key']) || strpos($sanitized['live_secret_key'], 'sk_live_') !== 0) {
                $errors[] = 'Live Secret Key must start with sk_live_';
            }

            if (!empty($errors)) {
                add_settings_error(
                    self::OPTION_KEY,
                    'invalid_live_keys',
                    'Cannot enable Live Mode: ' . implode(', ', $errors),
                    'error'
                );
                $sanitized['mode'] = 'test'; // Force back to test mode
            }
        }

        return $sanitized;
    }

    public function filter_publishable_key(string $value): string
    {
        $options = get_option(self::OPTION_KEY, []);
        $mode = $options['mode'] ?? 'test';

        if ($mode === 'live') {
            return (string) ($options['live_publishable_key'] ?? $value);
        }

        // Test mode - try new key, fallback to legacy
        return (string) ($options['test_publishable_key'] ?? $options['publishable_key'] ?? $value);
    }

    public function filter_secret_key(string $value): string
    {
        $options = get_option(self::OPTION_KEY, []);
        $mode = $options['mode'] ?? 'test';

        if ($mode === 'live') {
            return (string) ($options['live_secret_key'] ?? $value);
        }

        // Test mode - try new key, fallback to legacy
        return (string) ($options['test_secret_key'] ?? $options['secret_key'] ?? $value);
    }

    public function filter_test_mode(bool $value): bool
    {
        $options = get_option(self::OPTION_KEY, []);
        $mode = $options['mode'] ?? 'test';
        return $mode === 'test';
    }

    /**
     * Get the active webhook secret based on current mode.
     */
    public function get_webhook_secret(): string
    {
        $options = get_option(self::OPTION_KEY, []);
        $mode = $options['mode'] ?? 'test';

        if ($mode === 'live') {
            return (string) ($options['live_webhook_secret'] ?? '');
        }

        // Test mode - try new key, fallback to legacy
        return (string) ($options['test_webhook_secret'] ?? $options['webhook_secret'] ?? '');
    }
}
