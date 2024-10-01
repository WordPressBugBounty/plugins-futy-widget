<?php
/*
Plugin Name: Futy.io Leadbots
Plugin URI: https://futy.io
Description: Turn your website visitors into leads with the Futy Leadbot: WhatsApp Chat, E-mail Form, Request Quote Chatbot, Phone button, Callback request, Contact forms, Appointments, Link buttons to conversion pages, Schedule a video call, Dynamic forms, FAQ’s.
Version: 2.0.7
Author: Futy
Author URI: https://futy.io
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: futy-widget
*/

/**
 * For Security
 */
if (! defined('ABSPATH')) {
    die;
}

defined('ABSPATH') or die('You can\t access this file!');

if (! function_exists('add_action')) {
    echo 'You can\t access this file!';
    exit;
}

/**
 * Plugin version
 */
const VERSION = '2.0.7';

/**
 * Uninstall of plugin.
 */
register_uninstall_hook(__FILE__, 'futy_uninstall');

function futy_uninstall()
{
    delete_option('futy_widget_code');
    delete_option('futy_widget_visibility');
    delete_option('futy_new_script');
}

require('admin/settings.php');

if (! class_exists('FutyWidgetPlugin')) {
    class FutyWidgetPlugin extends FutyWidgetSettings
    {
        /**
         * FutyWidgetPlugin constructor
         *
         */
        public function __construct()
        {
            if (is_admin()) {
                add_action('plugins_loaded', [$this, 'setup_translations']);
                add_action('admin_menu', [$this, 'setup_dashboard']);
                add_action('plugins_loaded', [$this, 'script_check']);
                add_action('admin_enqueue_scripts', [$this, 'load_admin_styles']);
            }

            add_action('wp_footer', [$this, 'display_widget']);
        }

        /**
         * Setup translations
         *
         */
        public function setup_translations()
        {
            load_plugin_textdomain('futy', false, basename(dirname(__FILE__)) . '/languages/');
        }

        /**
         * Display menu item
         *
         */
        public function setup_dashboard()
        {
            $icon = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDUiIGhlaWdodD0iNDUiIHZpZXdCb3g9IjAgMCA0NSA0NSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik05LjQ3MzY5IDBDNC4yNDE1MSAwIDAgNC4yNDE1MSAwIDkuNDczNjhWMzUuNTI2M0MwIDQwLjc1ODUgNC4yNDE1MSA0NSA5LjQ3MzY5IDQ1SDM1LjUyNjNDNDAuNzU4NSA0NSA0NSA0MC43NTg1IDQ1IDM1LjUyNjNWOS40NzM2OEM0NSA0LjI0MTUxIDQwLjc1ODUgMCAzNS41MjYzIDBIOS40NzM2OVpNMjguMjY3OCAyNS4yNzEyTDI5LjM4NTMgMzAuNDIyOEMyOS41NjUyIDMxLjI4NzcgMjkuMzI0OSAzMi4xNTEgMjguNzQ1NSAzMi43NzA0TDIxLjQ3NjcgNDAuMDM5M0MyMS4zMjQ0IDQwLjE5MTYgMjEuMDY4IDQwLjEwNjQgMjEuMDIzIDM5Ljg5MDJMMTkuNjcyNyAzMy4zNjQxQzE5LjY2MDUgMzMuMjc1OCAxOS42ODY5IDMzLjE2OSAxOS43NTM1IDMzLjEwMjRMMjIuNTMxNyAzMC4zMjQyQzIyLjU4ODggMzAuMjY3MSAyMi42MTU3IDMwLjE3OTkgMjIuNTk0IDMwLjEwMTFMMjEuNDExNCAyNC4zNTE3QzIxLjM4OTIgMjQuMjUzMyAyMS4zMDg4IDI0LjE3MyAyMS4yMTA1IDI0LjE1MDdMMTUuNDYxIDIyLjk2ODFDMTUuMzcyNyAyMi45NTU5IDE1LjI5NSAyMi45NzM0IDE1LjIzNzkgMjMuMDMwNUwxMi40NTk4IDI1LjgwODZDMTIuMzkzMiAyNS44NzUyIDEyLjI5NjQgMjUuOTExNyAxMi4xOTgxIDI1Ljg4OTVMNS42NzE5OSAyNC41MzkxQzUuNDY1MjcgMjQuNDg0NiA1LjM4MDEgMjQuMjI4MiA1LjUyMjgyIDI0LjA4NTVMMTIuNzkxNyAxNi44MTY2QzEzLjM5MTEgMTYuMjE3MiAxNC4yNjM5IDE1Ljk2NzMgMTUuMTE5MyAxNi4xNTY3TDIwLjI3MDggMTcuMjc0MkwyNS42MDgzIDExLjkzNjdDMjguMzY3NCA5LjE3NzU5IDMyLjM3MTMgNy45ODY3IDM2LjM2MDMgOC43NTk4MkwzNi43MTQgOC44MjgwOUwzNi43ODIyIDkuMTgxNzhDMzcuNTQ1MyAxMy4xNjA3IDM2LjM3NCAxNy4xNjUxIDMzLjYwNTMgMTkuOTMzN0wyOC4yNjc4IDI1LjI3MTJaTTI2LjkxOTMgMTQuNTAzNkMyNS44NDQyIDE1LjU3ODcgMjUuODkyNyAxNy4zNTg4IDI3LjAyNzkgMTguNDk0QzI4LjE2MzIgMTkuNjI5MyAyOS45NDMyIDE5LjY3NzcgMzEuMDE4MyAxOC42MDI2QzMyLjA5MzQgMTcuNTI3NSAzMi4wNDUgMTUuNzQ3NSAzMC45MDk3IDE0LjYxMjJDMjkuNzc0NSAxMy40NzcgMjcuOTk0NSAxMy40Mjg1IDI2LjkxOTMgMTQuNTAzNloiIGZpbGw9IiNFRkYxRkYiLz4KPC9zdmc+';

            add_menu_page(__('Futy.io settings', 'futy'), 'Futy.io', 'manage_options', 'futy', [$this, 'options_page_render'], $icon, 200);
        }

        /**
         * Load admin styles
         *
         */
        public function load_admin_styles()
        {
            wp_enqueue_style('futy-admin', plugins_url('assets/css/admin.css', __FILE__));
        }

        /**
         * Add script
         *
         * @param string $script_name
         * @param string $url
         * @param string $var_name
         * @param array $pass_var_value
         */
        public function add_script($script_name, $url, $var_name = null, $pass_var_value = [])
        {
            wp_register_script($script_name, plugins_url($url, __FILE__));

            if (! empty($var_name)) {
                wp_localize_script($script_name, $var_name, $pass_var_value);
            }

            wp_enqueue_script($script_name);
        }

        /**
         * Display widget
         *
         */
        public function display_widget()
        {
            $widget_code       = esc_js(get_option('futy_widget_code'));
            $widget_visibility = esc_js(get_option('futy_widget_visibility'));
            $new_script        = esc_js(get_option('futy_new_script'));

            if ($widget_code && $widget_visibility === 'visible') {
                $data = [
                    'widget_code' => $widget_code,
                    'plugin_version' => VERSION,
                ];

                if ($new_script) {
                    $this->add_script('futy-io-script', 'assets/js/futy-io.min.js', 'data', $data);
                } else {
                    $this->add_script('futy-widget-script', 'assets/js/futy-widget.min.js', 'data', $data);
                }
            }
        }

        /**
         * Check futy_new_script
         */
        public function script_check()
        {
            $new_script = get_option('futy_new_script');

            if ($new_script === false) {
                $widget_code = get_option('futy_widget_code', null);
                $is_futy_io = $this->exists_in_futy_io($widget_code);

                if ($is_futy_io) {
                    update_option('futy_new_script', 1);
                }
            }
        }
    }

    new FutyWidgetPlugin();
}
