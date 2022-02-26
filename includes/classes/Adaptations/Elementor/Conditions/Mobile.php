<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class Mobile extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'mobile';
	}

	public function get_name() {
		return 'mobili_mobile';
	}

	public function get_label() {
		return __( 'Mobile', 'mobili' );
	}

	public function check( $args ) {
		return mi_is_mobile() && ! mi_is_tablet();
	}
}
