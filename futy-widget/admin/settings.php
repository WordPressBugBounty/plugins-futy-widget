<?php

if (! defined('ABSPATH')) {
    die;
}

if (! class_exists('FutyWidgetSettings')) {
    class FutyWidgetSettings
    {

        /**
         * Render widget options page
         *
         * @return void
         */
        public function options_page_render()
        {
            // check user capabilities
            if (! current_user_can('manage_options')) {
                return;
            }

            $errors = false;

            // get the current settings values
            $widget_code       = get_option('futy_widget_code', null);
            $widget_visibility = get_option('futy_widget_visibility', null);
            $new_script        = get_option('futy_new_script', 0);

            if (!$new_script) {
                // Check if code is in futy.io
                $is_futy_io = $this->exists_in_futy_io($widget_code);

                if ($is_futy_io) {
                    update_option('futy_new_script', 1);
                }
            }

            // handle form posts
            if (array_key_exists('submit_settings', $_POST)) {
                check_admin_referer('update-futy-options');

                $widget_code       = sanitize_key($_POST['widget_code']);
                $widget_visibility = sanitize_key($_POST['widget_visibility']);
                $errorMessage = '';

                // validate if code has valid pattern
                if (strlen($widget_code) !== 13 || ! ctype_alnum($widget_code)) {
                    $errors = true;
                    $errorMessage = __('The Futy key is invalid', 'futy');
                }

                // validate if code exists
                if ($errors === false) {

                    // Check if code is in futy.io
                    $is_futy_io = $this->exists_in_futy_io($widget_code);

                    if ($is_futy_io) {
                        update_option('futy_new_script', 1);
                    } else {
                        // Check if code is in futy-widget.com
                        $is_futy_widget = $this->exists_in_futy_widget($widget_code);

                        if ($is_futy_widget) {
                            update_option('futy_new_script', 0);
                        } else {
                            $errors = true;
                            $errorMessage = __('This key cannot be found on futy.io', 'futy');
                        }
                    }
                }

                if ($errors === false) {
                    update_option('futy_widget_code', $widget_code);
                    update_option('futy_widget_visibility', $widget_visibility);

                    add_settings_error('futy_messages', 'futy_message', __('The settings are saved', 'futy'), 'updated');
                } else {
                    add_settings_error(
                        'futy_messages',
                        'futy_message',
                        $errorMessage,
                        'error'
                    );
                }
            }

            // show error/update messages
            settings_errors('futy_messages');

            include('options_form.php');
        }

        /**
         * Check if code exists in futy-widget.com
         */
        public function exists_in_futy_widget($code)
        {
            $response_code = wp_remote_retrieve_response_code(wp_remote_get('https://app.futy-widget.com/api/widget/' . $code, [ 'sslverify' => false ]));
            return $response_code === 200;
        }

        /**
         * Check if code exists in futy.io
         */
        public function exists_in_futy_io($code)
        {
            $response_code = wp_remote_retrieve_response_code(wp_remote_get('https://api.widget.futy.io/v1/space/' . $code, [ 'sslverify' => false ]));
            return $response_code === 200;
        }
    }
}
