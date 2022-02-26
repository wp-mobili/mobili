<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class Watch extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'watch';
	}

	public function get_name() {
		return 'mobili_watch';
	}

	public function get_label() {
		return __( 'Watch', 'mobili' );
	}

	public function check( $args ) {
		return mi_is_watch();
	}
}
