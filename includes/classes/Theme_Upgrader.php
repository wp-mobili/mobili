<?php

namespace Mobili;

use Mobili\Theme\Store;
use WP_Error;
use WP_Upgrader;

/**
 * Class Theme_Upgrader
 * @package Mobili
 * @since 1.0.0
 */
class Theme_Upgrader extends \Theme_Upgrader
{
    public function upgrade($theme, $args = array())
    {
        $defaults = array(
            'clear_update_cache' => true,
        );
        $parsed_args = wp_parse_args($args, $defaults);

        $this->init();
        $this->upgrade_strings();


        if (!mobile_theme_update_available($theme)) {
            $this->skin->before();
            $this->skin->set_result(false);
            $this->skin->error('up_to_date');
            $this->skin->after();
            return false;
        }

        add_filter('upgrader_pre_install', array($this, 'current_before'), 10, 2);
        add_filter('upgrader_post_install', array($this, 'current_after'), 10, 2);
        add_filter('upgrader_clear_destination', array($this, 'delete_old_theme'), 10, 4);
        if ($parsed_args['clear_update_cache']) {
            // Clear cache so wp_update_themes() knows about the new theme.
            add_action('upgrader_process_complete', 'wp_clean_themes_cache', 9, 0);
        }

        $packageDownload = Store::downloadTheme($theme);

        $this->run(
            array(
                'package' => $packageDownload->download_link ?? '',
                'destination' => mi_get_themes_directory() . MOBILI_DSP . $theme,
                'clear_destination' => true,
                'clear_working' => true,
                'hook_extra' => array(
                    'theme' => $theme,
                    'type' => 'theme',
                    'action' => 'update',
                ),
            )
        );

        remove_action('upgrader_process_complete', 'wp_clean_themes_cache', 9);
        remove_filter('upgrader_pre_install', array($this, 'current_before'));
        remove_filter('upgrader_post_install', array($this, 'current_after'));
        remove_filter('upgrader_clear_destination', array($this, 'delete_old_theme'));

        if (!$this->result || is_wp_error($this->result)) {
            return $this->result;
        }

        wp_clean_themes_cache($parsed_args['clear_update_cache']);

        // Ensure any future auto-update failures trigger a failure email by removing
        // the last failure notification from the list when themes update successfully.
        $past_failure_emails = get_option('auto_plugin_theme_update_emails', array());

        if (isset($past_failure_emails[$theme])) {
            unset($past_failure_emails[$theme]);
            update_option('auto_plugin_theme_update_emails', $past_failure_emails);
        }

        return true;
    }
}