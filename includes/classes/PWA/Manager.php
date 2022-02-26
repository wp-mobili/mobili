<?php

namespace Mobili\PWA;

/**
 * Class PWA Manager
 *
 * @since   1.0.0
 * @package Mobili\PWA
 */
class Manager {
	static $menuSlug = 'mobili-pwa';

	public static function getAdminUrl () {
		return admin_url('options-general.php?page=' . self::$menuSlug);
	}

	public static function adminInit () {
		$generalTab = new Manager_Basic(self::$menuSlug);
		$generalTab->adminInit();

		$iconsTab = new Manager_Icons(self::$menuSlug);
		$iconsTab->adminInit();

		$colorsTab = new Manager_Colors(self::$menuSlug);
		$colorsTab->adminInit();
	}

	public static function registerAdminMenu () {
		$init    = new self();
		$submenu = add_submenu_page(
			'options-general.php', __('PWA Settings', 'wp-mobili'), __('PWA', 'wp-mobili'), 'manage_options',
			self::$menuSlug, [
				$init,
				'adminMenuContent'
			], 3
		);
		add_action('admin_print_scripts-' . $submenu, [$init, 'enqueueScripts']);
	}

	public function enqueueScripts () {
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script('mi-script-settings');
	}

	public function adminMenuContent () {
		if (!mi_pwa_is_active()){
			add_settings_error('mobili_pwa_messages','pwa-not-active',__('PWA feature is deactivated!'),'warning');
		}
		mi_get_core_view(
			'admin/pwa/manager.php', [
				                       'menuSlug' => self::$menuSlug
			                       ]
		);
	}

}