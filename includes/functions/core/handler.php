<?php

use Mobili\Inline_Theme;
use Mobili\Theme\Manager;
use Mobili\Theme\Update;

/**
 * get core templates
 *
 * @param string $path
 * @param array $args
 * @param bool $return
 *
 * @return false|string
 */
function mi_get_core_view(string $path, array $args = [], bool $return = false)
{
    extract($args);
    $fullPath = MOBILI_DIR . 'views' . MOBILI_DSP . $path;
    if (!file_exists($fullPath)) {
        $fullPath = $path;
    }
    if ($return) {
        ob_start();
    }
    include realpath($fullPath);
    if ($return) {
        return ob_get_clean();
    }
}

function mi_page_related(string $url, string $required = ''): bool
{
    if (strpos($_SERVER['REQUEST_URI'], $url) !== false && isset($_REQUEST[$required])) {
        return true;
    }
    $referer = parse_url($_SERVER['HTTP_REFERER'] ?? '');
    $refererQueries = $referer['query'] ?? '';
    parse_str($refererQueries, $refererQueries);

    if (strpos($referer['path'] ?? '', $url) !== false && isset($refererQueries[$required])) {
        return true;
    }

    return false;
}

/**
 * check page can load mobile version assets
 *
 * @return bool
 * @since 1.0.0
 */
function mi_can_load_assets(): bool
{
    $return = false;

    if (mi_page_related('site-editor.php', 'mobili_theme')) {
        $return = true;
    }

    if (wp_is_mobile() && !is_admin()) {
        $return = true;
    }
    if (!empty(Manager::getActiveTemplateSlug()) && isset($_GET['mobili_theme'])) {
        $return = true;
    }

    return apply_filters(
        'mobili_can_load_assets',
        $return
    );
}

/**
 * mobili force redirect
 *
 * @param $url
 *
 * @since 1.0.0
 */
function mi_redirect($url)
{
    @wp_redirect($url);
    echo '<script>window.location.href = "' . $url . '";</script>';
    exit();
}

/**
 * setup options default values
 *
 * @param string $name
 * @param        $value
 *
 * @since 1.0.0
 */
function mi_set_option_default(string $name, $value)
{
    $option = get_option($name, 'mi_not_exists');
    if ($option === 'mi_not_exists') {
        update_option($name, $value);
    }
}

/**
 * get mobile themes base directory
 *
 * @return string
 * @since 1.0.0
 */
function mi_get_themes_directory(): string
{
    return WP_CONTENT_DIR . MOBILI_DSP . 'themes';
}

/**
 * get mobile theme directory
 *
 * @param string $slug
 *
 * @return false|string
 * @since 1.0.0
 */
function mi_get_template_directory(string $slug = '')
{
    if (Inline_Theme::canLoadAssets()) {
        $activeTemplate = get_option('stylesheet');
    } else {
        $activeTemplate = Manager::getActiveTemplateSlug();
    }
    $targetPath = mi_get_themes_directory() . MOBILI_DSP . $activeTemplate;

    if (empty($activeTemplate) || !file_exists($targetPath)) {
        return false;
    }
    $targetPath .= MOBILI_DSP . $slug;

    if (file_exists($targetPath)) {
        return $targetPath;
    }

    return false;
}

/**
 * smart get template part
 *
 * @param string $path
 * @param array $args
 * @param bool $return
 *
 * @return false|string
 * @since 1.0.0
 */
function mi_get_template_part(string $path, array $args = [], bool $return = false)
{
    $fullPath = mi_get_template_directory(apply_filters('mobili_templates_directory', 'mobili') . MOBILI_DSP . $path);

    if (!file_exists($fullPath)) {
        $fullPath = MOBILI_TEMPLATES_DIR . MOBILI_DSP . $path;
    }

    if ($return) {
        ob_start();
    }
    if (file_exists($fullPath)) {
        include $fullPath;
    } else {
        return false;
    }

    if ($return) {
        return ob_get_clean();
    }
}

/**
 * Checks compatibility with the current Mobili version.
 *
 * @param string $ver
 *
 * @return bool
 * @since 1.0.0
 */
function is_mobili_version_compatible(string $ver): bool
{
    return empty($ver) || version_compare($ver, MOBILI_VERSION, '<=');
}

/**
 * check mobile theme update is available
 *
 * @param string|WP_Theme $slug
 * @return bool
 */
function mobile_theme_update_available($slug): bool
{
    global $mobili_updates;
    if (empty($mobili_updates)) {
        $mobili_updates = Update::checkThemesUpdate();
    }
    if (empty($mobili_updates)) {
        return false;
    }

    if (is_string($slug)) {
        $theme = Manager::getTemplates($slug);
        if (!empty($theme)) {
            $theme = $theme[0];
        }
    } else {
        $theme = $slug;
    }

    if (isset($mobili_updates[$theme->get_stylesheet()]) && $mobili_updates[$theme->get_stylesheet()]['theme'] === $theme->get_stylesheet() && version_compare($mobili_updates[$theme->get_stylesheet()]['new_version'], $theme->get('Version'), '>')) {
        return true;
    }

    return false;
}

/**
 * customized wp_prepare_themes_for_js function
 *
 * @param array $themes
 * @return array
 */
function mi_prepare_themes_for_js(array $themes = []): array
{
    add_filter('pre_site_transient_update_themes', [Update::class, 'themeUpdates']);
    $themes = wp_prepare_themes_for_js($themes);
    remove_filter('pre_site_transient_update_themes', [Update::class, 'themeUpdates']);

    if (empty($themes)) {
        return [];
    }

    foreach ($themes as &$theme) {
        if (isset($theme['active'])) {
            $theme['active'] = Manager::getActiveTemplateSlug() === $theme['id'];
        }
        if (isset($theme['actions']['delete'])) {
            $theme['actions']['delete'] = Manager::getDeleteThemeAdminUrl($theme['id']);
        }
        if (isset($theme['actions']['activate'])) {
            $theme['actions']['activate'] = Manager::getActivateThemeAdminUrl($theme['id']);
        }
        if (isset($theme['actions']['activate'])) {
            $theme['actions']['deactivate'] = Manager::getDeactivateThemeAdminUrl($theme['id']);
        }
        if (isset($theme['actions']['autoupdate'])) {
            $theme['actions']['autoupdate'] = '';
        }
        if (isset($theme['actions']['customize'])) {
            $theme['actions']['customize'] = add_query_arg(['mobili_theme' => 'true'], $theme['actions']['customize']);
        }
    }
    unset($theme);
    return $themes;
}