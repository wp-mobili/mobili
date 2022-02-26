<?php

function mi_hook_front_enqueue_styles () {
	wp_enqueue_style('mi-style-frontend', MOBILI_URL . 'assets/styles/frontend.css');
}

add_action('wp_enqueue_scripts', 'mi_hook_front_enqueue_styles');

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

add_action('wp_enqueue_scripts', 'mi_hook_front_enqueue_scripts');