<?php

namespace Mobili\Adaptations\Elementor\Conditions;

class Mobili extends \ElementorPro\Modules\ThemeBuilder\Conditions\Condition_Base {

	public static function get_type() {
		return 'mobili';
	}

	public function get_name() {
		return 'mobili';
	}

	public function get_label() {
		return __( 'Mobile', 'mobili' );
	}

	public function get_all_label() {
		return __( 'All', 'mobili' );
	}

	public function register_sub_conditions() {
		$this->register_sub_condition( new Mobile() );
		$this->register_sub_condition( new Tablet() );
		$this->register_sub_condition( new Watch() );
		$this->register_sub_condition( new IOS() );
		$this->register_sub_condition( new Android() );
	}

	public function check( $args ) {
		return mi_can_load_assets();
	}
}
