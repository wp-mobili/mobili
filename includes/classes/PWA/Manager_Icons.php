<?php

namespace Mobili\PWA;

use Mobili\Settings;

/**
 * Class Manager_Icons
 *
 * @since   1.0.0
 * @package Mobili\PWA
 */
class Manager_Icons {
	public $menuSlug;

	public function __construct ($menuSlug) {
		$this->menuSlug = $menuSlug;
	}

	public function adminInit () {
		add_settings_section(
			'mobili_pwa_icons_section', __('Icons', 'mobili'), null, $this->menuSlug
		);

		add_settings_field(
			'pwa-icons-icon-192-field', __('Icon 192x192', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_icons_section', [
				'name'     => 'mobili-pwa_icon-192',
				'desc'     => __('Required size! (192 pixel x 192 pixel)', 'mobili'),
				'required' => true
			]
		);


		add_settings_field(
			'pwa-icons-icon-512-field', __('Icon 512x512', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_icons_section', [
				'name'     => 'mobili-pwa_icon-512',
				'desc'     => __('Required size! (512 pixel x 512 pixel)', 'mobili'),
				'required' => true
			]
		);


		add_settings_section(
			'mobili_pwa_more_icons_section', '', [Settings::class,'moreOptions'], $this->menuSlug
		);

		add_settings_field(
			'pwa-icons-icon-48-field', __('Icon 48x48', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_more_icons_section', [
				'name'     => 'mobili-pwa_icon-48',
				'desc'     => __('48 pixel x 48 pixel', 'mobili')
			]
		);


		add_settings_field(
			'pwa-icons-icon-72-field', __('Icon 72x72', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_more_icons_section', [
				'name'     => 'mobili-pwa_icon-72',
				'desc'     => __('72 pixel x 72 pixel', 'mobili')
			]
		);

		add_settings_field(
			'pwa-icons-icon-96-field', __('Icon 96x96', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_more_icons_section', [
				'name'     => 'mobili-pwa_icon-96',
				'desc'     => __('96 pixel x 96 pixel', 'mobili')
			]
		);

		add_settings_field(
			'pwa-icons-icon-144-field', __('Icon 144x144', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_more_icons_section', [
				'name'     => 'mobili-pwa_icon-144',
				'desc'     => __('144 pixel x 144 pixel', 'mobili')
			]
		);

		add_settings_field(
			'pwa-icons-icon-168-field', __('Icon 168x168', 'mobili'), [Settings::class, 'imageUpload'], $this->menuSlug,
			'mobili_pwa_more_icons_section', [
				'name'     => 'mobili-pwa_icon-168',
				'desc'     => __('168 pixel x 168 pixel', 'mobili')
			]
		);

		register_setting('mobili_pwa', 'mobili-pwa_icon-192');
		register_setting('mobili_pwa', 'mobili-pwa_icon-512');
		register_setting('mobili_pwa', 'mobili-pwa_icon-48');
		register_setting('mobili_pwa', 'mobili-pwa_icon-72');
		register_setting('mobili_pwa', 'mobili-pwa_icon-96');
		register_setting('mobili_pwa', 'mobili-pwa_icon-144');
		register_setting('mobili_pwa', 'mobili-pwa_icon-168');
	}
}