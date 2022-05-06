<?php


namespace Mobili\Theme;


class Install {
	static $menuSlug = 'install-mobile-theme';

	public static function getAdminUrl () {
		return admin_url('themes.php?page=' . self::$menuSlug);
	}

	public static function registerAdminMenu () {
		$init    = new self();
		$submenu = add_submenu_page(
			null, __('Mobile Themes', 'wp-mobili'), __('Mobile Themes', 'wp-mobili'), 'install_themes', self::$menuSlug,
			[
				$init,
				'adminMenuContent'
			]
		);
		add_action('admin_print_scripts-' . $submenu, [$init, 'enqueueScripts']);
	}

	public function enqueueScripts () {
		wp_enqueue_script(
			'mi-script-install-theme', MOBILI_URL . '/assets/scripts/admin/theme-install.js',['jquery','wp-util']
		);
		wp_localize_script(
			'mi-script-install-theme', 'mobiliInstallArgs', [
				                        'adminAjax' => admin_url('admin-ajax.php')
			                        ]
		);
	}

	public function adminMenuContent () {
        if (!current_user_can('install_themes')){
            wp_die(__('Sorry, you are not allowed to install themes on this site.'));
        }
		mi_get_core_view(
			'admin/themes/store.php', [
			'sort'    => isset($_GET['sort']) && in_array(sanitize_key($_GET['sort']),['popular','latest','favorites']) ? sanitize_key($_GET['sort']) : 'popular',
			'store_url'    => self::getAdminUrl(),
			'nonce'        => wp_create_nonce('mobile_theme_install'),
			'upload_nonce' => wp_create_nonce('mobile_theme_upload'),
			'upload_url'   => Upload::getAdminUrl()
		]
		);
	}
}