<?php
?>
<div class="wrap">
    <h1><?php esc_html_e('PDFMaster Processor Settings', 'pdfmaster-processor'); ?></h1>

    <form method="post" action="options.php" id="pdfm-processor-settings-form">
        <?php settings_fields('pdfm_processor_settings'); ?>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="pdfm_stirling_endpoint"><?php esc_html_e('Stirling PDF Endpoint URL', 'pdfmaster-processor'); ?></label>
                    </th>
                    <td>
                        <input name="pdfm_stirling_endpoint" type="url" id="pdfm_stirling_endpoint" value="<?php echo esc_attr(get_option('pdfm_stirling_endpoint', 'http://localhost:8080')); ?>" class="regular-text" />
                        <p class="description"><?php esc_html_e('Typically http://localhost:8080 when running Docker locally.', 'pdfmaster-processor'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="pdfm_stirling_timeout"><?php esc_html_e('API Timeout (seconds)', 'pdfmaster-processor'); ?></label>
                    </th>
                    <td>
                        <input name="pdfm_stirling_timeout" type="number" min="5" id="pdfm_stirling_timeout" value="<?php echo (int) get_option('pdfm_stirling_timeout', 30); ?>" class="small-text" />
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="pdfm_max_file_size"><?php esc_html_e('Max File Size (MB)', 'pdfmaster-processor'); ?></label>
                    </th>
                    <td>
                        <?php $max_bytes = (int) get_option('pdfm_max_file_size', 104857600); ?>
                        <input name="pdfm_max_file_size" type="number" min="1" id="pdfm_max_file_size" value="<?php echo (int) ($max_bytes / 1024 / 1024); ?>" class="small-text" />
                        <span class="description"><?php esc_html_e('Will be converted to bytes on save.', 'pdfmaster-processor'); ?></span>
                        <p class="description"><code><?php echo esc_html(number_format_i18n($max_bytes)); ?></code> bytes</p>
                        <script>
                            document.getElementById('pdfm-processor-settings-form').addEventListener('submit', function(){
                                var mb = document.getElementById('pdfm_max_file_size');
                                if(mb){ mb.value = Math.max(1, parseInt(mb.value || '1', 10)) * 1024 * 1024; }
                            });
                        </script>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php submit_button(__('Save Settings', 'pdfmaster-processor')); ?>
        <button type="button" class="button" id="pdfm-test-connection"><?php esc_html_e('Test Connection', 'pdfmaster-processor'); ?></button>
        <span id="pdfm-test-connection-result"></span>
    </form>

    <script>
        (function(){
            const btn = document.getElementById('pdfm-test-connection');
            const out = document.getElementById('pdfm-test-connection-result');
            btn && btn.addEventListener('click', function(){
                out.textContent = 'Testing...';
                const data = new FormData();
                data.append('action', 'pdfm_test_stirling_connection');
                data.append('nonce', '<?php echo wp_create_nonce('pdfm_test_connection'); ?>');
                fetch(ajaxurl, { method: 'POST', body: data })
                    .then(r => r.json())
                    .then(j => { out.textContent = (j.success ? '✅ ' : '❌ ') + (j.data && j.data.message ? j.data.message : 'Unknown'); })
                    .catch(() => { out.textContent = '❌ Request failed'; });
            });
        })();
    </script>
</div>
