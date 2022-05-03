<?php

/**
 * mobili plugin loaded
 *
 * @since 1.0.0
 */
function mobili_loaded () {
	mi_set_option_default('mobili-pwa_shortname', get_bloginfo('name'));
	mi_set_option_default('mobili-pwa_name', get_bloginfo('name'));
	mi_set_option_default('mobili-pwa_description', get_bloginfo('description'));
	mi_set_option_default('mobili-pwa_icon-192', MOBILI_ASSETS_URL . '/images/app-icon-192.png');
	mi_set_option_default('mobili-pwa_icon-512', MOBILI_ASSETS_URL . '/images/app-icon-512.png');
	mi_set_option_default('mobili-pwa_color-background', '#ffffff');
	mi_set_option_default('mobili-pwa_color-theme', '#fb951f');
	mi_set_option_default('mobili-pwa_status', 'on');
	mi_set_option_default('mobili-pwa_desktop', 'on');
}

add_action('plugins_loaded', 'mobili_loaded');