<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class Tablet extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'tablet';
	}

	public function get_name() {
		return 'mobili_tablet';
	}

	public function get_label() {
		return __( 'Tablet', 'mobili' );
	}

	public function check( $args ) {
		return mi_is_tablet();
	}
}
