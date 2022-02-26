<?php

namespace Mobili\PWA;

use Mobili\Settings;

/**
 * Class Manager_Basic
 *
 * @since   1.0.0
 * @package Mobili\PWA
 */
class Manager_Basic {
	public $menuSlug;

	public function __construct ($menuSlug) {
		$this->menuSlug = $menuSlug;
	}

	public function adminInit () {
		add_settings_section(
			'mobili_pwa_basic_section', __('Basic details', 'mobili'), null, $this->menuSlug
		);

		add_settings_field(
			'pwa-general-Status-field', __('PWA Status', 'mobili'), [Settings::class, 'checkbox'], $this->menuSlug,
			'mobili_pwa_basic_section', [
				'name' => 'mobili-pwa_status'
			]
		);

		add_settings_field(
			'pwa-general-shortname-field', __('Short name', 'mobili'), [Settings::class, 'input'], $this->menuSlug,
			'mobili_pwa_basic_section', [
				'name' => 'mobili-pwa_shortname',
				'required' => true
			]
		);

		add_settings_field(
			'pwa-general-name-field', __('Name', 'mobili'), [Settings::class, 'input'], $this->menuSlug,
			'mobili_pwa_basic_section', [
				'name' => 'mobili-pwa_name',
				'required' => true
			]
		);

		add_settings_field(
			'pwa-general-description-field', __('Description', 'mobili'), [Settings::class, 'textarea'],
			$this->menuSlug, 'mobili_pwa_basic_section', [
				'name' => 'mobili-pwa_description'
			]
		);

		register_setting('mobili_pwa', 'mobili-pwa_status');
		register_setting('mobili_pwa', 'mobili-pwa_shortname');
		register_setting('mobili_pwa', 'mobili-pwa_name');
		register_setting('mobili_pwa', 'mobili-pwa_description');
	}
}