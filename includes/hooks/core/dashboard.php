<?php

use Mobili\Mobile_Mode;
use Mobili\PWA\Manager as PWA_Manager;
use Mobili\Theme\Install as Theme_Install;
use Mobili\Theme\Manager as Theme_Manager;
use Mobili\Theme\Upload as Theme_Upload;
use Mobili\Theme\Download as Theme_Download;
use Mobili\Theme\Update as Theme_Update;

add_action('admin_menu', [Theme_Manager::class, 'registerAdminMenu']);
add_action('admin_menu', [Theme_Install::class, 'registerAdminMenu']);
add_action('admin_menu', [Theme_Upload::class, 'registerAdminMenu']);
add_action('admin_menu', [Theme_Download::class, 'registerAdminMenu']);
add_action('admin_menu', [Theme_Update::class, 'registerAdminMenu']);
add_action('admin_menu', [PWA_Manager::class, 'registerAdminMenu']);
add_action('admin_init', [PWA_Manager::class, 'adminInit']);
add_action('admin_menu', [Mobile_Mode::class, 'registerAdminMenu']);
add_action('admin_bar_menu', [Mobile_Mode::class,'adminBar']);
add_filter('mobili_can_load_assets', [Mobile_Mode::class,'canLoadAssets']);
add_action('admin_notices', [Mobile_Mode::class,'adminNotices']);