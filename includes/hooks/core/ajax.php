<?php

use Mobili\Theme\Store;

add_action('wp_ajax_mi_ajax_store_list', [Store::class, 'getList']);