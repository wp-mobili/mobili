<?php

namespace Mobili\Adaptations\Elementor;

use Mobili\Adaptations\Elementor\Conditions\Mobili;

class Elementor
{
    public static function register_conditions($conditionsManager ) {
        $mobili = new Mobili();
        $conditionsManager->get_condition( 'general' )->register_sub_condition( $mobili );
    }
}