<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class IOS extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'ios';
	}

	public function get_name() {
		return 'mobili_ios';
	}

	public function get_label() {
		return __( 'IOS', 'mobili' );
	}

	public function check( $args ) {
		return mi_is_ios();
	}
}
