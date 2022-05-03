<?php
/**
 * register mobili frontend styles
 */
function mi_hook_front_enqueue_styles () {
	wp_enqueue_style('mi-style-frontend', MOBILI_URL . 'assets/styles/frontend.css');
	if (mi_can_load_assets() && wp_style_is('woocommerce-general') && function_exists('WC') && class_exists('Automattic\Jetpack\Constants')) {
        wp_deregister_style('woocommerce-general');
        wp_register_style( 'woocommerce-general', WC()->plugin_url() . '/assets/css/woocommerce.css', array(), Automattic\Jetpack\Constants::get_constant( 'WC_VERSION' ) );
    }
}

/**
 * register mobili frontend scripts
 */
function mi_hook_front_enqueue_scripts () {
	if ( mi_pwa_is_active() && mi_can_load_assets()) {
		wp_enqueue_script('mi-script-frontend-pwa', MOBILI_URL . 'assets/scripts/frontend/pwa.js', null, null, true);
		wp_localize_script(
			'mi-script-frontend-pwa', 'mobiliArgs', [
			'serviceWorker' => site_url('/service-worker.js')
		]
		);
	}
}

add_action('wp_enqueue_scripts', 'mi_hook_front_enqueue_styles');
add_action('wp_enqueue_scripts', 'mi_hook_front_enqueue_scripts');