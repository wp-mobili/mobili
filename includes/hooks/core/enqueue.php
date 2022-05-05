<?php

function mi_hook_admin_enqueue_styles () {
	wp_enqueue_style('mi-style-mobili-dashboard', MOBILI_URL . 'assets/styles/dashboard.css');
}

function mi_hook_admin_enqueue_scripts () {
	wp_register_script('mi-script-settings', MOBILI_URL . 'assets/scripts/admin/settings.js',['jquery']);
    wp_register_script('mi-script-theme-manager', MOBILI_URL . 'assets/scripts/admin/theme-manager.js',['jquery','wp-util']);
}

add_action('admin_enqueue_scripts', 'mi_hook_admin_enqueue_styles');
add_action('admin_enqueue_scripts', 'mi_hook_admin_enqueue_scripts');