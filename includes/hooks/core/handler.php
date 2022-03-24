<?php

use Mobili\Inline_Theme;
use Mobili\Theme\Manager;

function mi_hook_get_template($template)
{
    $activeTemplateSlug = Manager::getActiveTemplateSlug();
    if (!empty($activeTemplateSlug) && mi_can_load_assets()) {
        return $activeTemplateSlug;
    }

    return $template;
}

function mi_hook_setup_theme()
{
    $activeTemplateSlug = Manager::getActiveTemplateSlug();
    $templateGlobals = WP_CONTENT_DIR . '/themes/' . $activeTemplateSlug . '/mobili.php';
    add_filter('mobili_can_load_assets','__return_true');
    if (!empty($activeTemplateSlug) && file_exists($templateGlobals)) {
        require_once $templateGlobals;
    }
    remove_filter('mobili_can_load_assets','__return_true');
}

function mi_hook_pre_option_stylesheet($false, $option, $default)
{
    $activeTemplateSlug = Manager::getActiveTemplateSlug();
    if (!empty($activeTemplateSlug) && mi_can_load_assets()) {
        return $activeTemplateSlug;
    }

    return $false;
}

function mi_hook_wp_prepare_themes_for_js($prepared_themes)
{
    if (empty($prepared_themes)) {
        return [];
    }
    foreach ($prepared_themes as $slug => $theme) {
        if (empty($theme)) {
            continue;
        }
        if (Manager::isValidMobileTemplate($theme['id'])) {
            unset($prepared_themes[$slug]);
        }
    }
    return $prepared_themes;
}

function mi_hook_themes_screen()
{
    if (is_admin()) {
        $currentScreen = get_current_screen();
        if (isset($currentScreen->id) && 'themes' === $currentScreen->id) {
            add_filter('wp_prepare_themes_for_js', 'mi_hook_wp_prepare_themes_for_js');
        }
    }
}


add_filter('template', 'mi_hook_get_template', 10, 1);
add_action('setup_theme', 'mi_hook_setup_theme');
add_filter('pre_option_stylesheet', 'mi_hook_pre_option_stylesheet', 1, 3);
add_action('current_screen', 'mi_hook_themes_screen');
