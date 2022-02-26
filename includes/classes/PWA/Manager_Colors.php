<?php

namespace Mobili\PWA;

use Mobili\Settings;

/**
 * Class Manager_Colors
 *
 * @since   1.0.0
 * @package Mobili\PWA
 */
class Manager_Colors {
	public $menuSlug;

	public function __construct ($menuSlug) {
		$this->menuSlug = $menuSlug;
	}

	public function adminInit () {
		add_settings_section(
			'mobili_pwa_colors_section', __('Colors', 'mobili'), null, $this->menuSlug
		);

		add_settings_field(
			'pwa-colors-background-field', __('Background', 'mobili'), [Settings::class, 'colorPicker'], $this->menuSlug,
			'mobili_pwa_colors_section', [
				'name'     => 'mobili-pwa_color-background',
				'desc'     => __('Loading page background color. (Required)', 'mobili'),
				'required' => true
			]
		);

		add_settings_field(
			'pwa-colors-theme-field', __('Theme', 'mobili'), [Settings::class, 'colorPicker'], $this->menuSlug,
			'mobili_pwa_colors_section', [
				'name'     => 'mobili-pwa_color-theme',
				'desc'     => __('Primary color. (Required)', 'mobili'),
				'required' => true
			]
		);

		register_setting('mobili_pwa', 'mobili-pwa_color-background');
		register_setting('mobili_pwa', 'mobili-pwa_color-theme');
	}
}