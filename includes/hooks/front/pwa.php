<?php

use Mobili\PWA\Setup as PWA_Setup;

add_action('wp_footer', [PWA_Setup::class,'installBox']);
add_action('init', [PWA_Setup::class,'init']);
add_action('wp_head', [PWA_Setup::class,'manifestLoader']);