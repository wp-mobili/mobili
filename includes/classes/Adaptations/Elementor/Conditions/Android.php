<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class Android extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'android';
	}

	public function get_name() {
		return 'mobili_android';
	}

	public function get_label() {
		return __( 'Android', 'mobili' );
	}

	public function check( $args ) {
		return mi_is_android();
	}
}
