<?php

use Mobili\Theme\Manager;

/**
 * enqueue admin panel styles
 */
function mi_hook_admin_enqueue_styles()
{
    wp_enqueue_style('mi-style-mobili-dashboard', MOBILI_URL . 'assets/styles/dashboard.css');
}

/**
 * enqueue admin panel scripts
 */
function mi_hook_admin_enqueue_scripts()
{
    wp_register_script('mi-script-settings', MOBILI_URL . 'assets/scripts/admin/settings.js', ['jquery']);
    wp_register_script('mi-script-theme-manager', MOBILI_URL . 'assets/scripts/admin/theme-manager.js', ['jquery', 'wp-util']);
    wp_localize_script('mi-script-theme-manager', 'MobiliThemeMobileData', [
        'switchText' => __('Convert to desktop theme', 'mobili'),
        'switchConform' => __('Are you sure you want to change this theme to desktop mode theme?', 'mobili'),
        'switchLink' => Manager::getConvertThemeAdminUrl('%theme%', 'desktop')
    ]);

    if (get_current_screen()->id === 'themes') {
        wp_enqueue_script('mi-script-settings');
        wp_enqueue_script('mi-script-theme-desktop', MOBILI_URL . 'assets/scripts/admin/theme-desktop.js', ['jquery']);
        wp_localize_script('mi-script-theme-desktop', 'MobiliThemeDesktopData', [
            'switchText' => __('Convert to mobile theme', 'mobili'),
            'switchConform' => __('Are you sure you want to change this theme to mobile mode theme?', 'mobili'),
            'switchLink' => Manager::getConvertThemeAdminUrl('%theme%')
        ]);
    }
}

add_action('admin_enqueue_scripts', 'mi_hook_admin_enqueue_styles');
add_action('admin_enqueue_scripts', 'mi_hook_admin_enqueue_scripts');