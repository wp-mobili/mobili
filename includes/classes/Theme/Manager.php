<?php

namespace Mobili\Theme;

use Mobili\Inline_Theme;
use Mobili\Log_Manager;
use WP_Filesystem_Base;
use WP_Theme;

/**
 * Class Theme_Manager
 *
 * @since   1.0.0
 * @package Mobili
 */
class Manager
{
    const MOBILI_THEMES_DIR = WP_CONTENT_DIR . '/themes';
    static $menuSlug = 'mobile-themes';

    public static function getAdminUrl()
    {
        return admin_url('themes.php?page=' . self::$menuSlug);
    }

    /**
     * mobile theme activator url builder
     *
     * @param string $slug
     *
     * @return string
     * @since 1.0.0
     */
    public static function getActivateThemeAdminUrl(string $slug): string
    {
        return add_query_arg(
            [
                '_nonce' => wp_create_nonce('mobile_theme'),
                'action' => 'activate',
                'slug' => $slug
            ], self::getAdminUrl()
        );
    }

    /**
     * mobile theme deactivate url builder
     *
     * @param string $slug
     *
     * @return string
     * @since 1.0.0
     */
    public static function getDeactivateThemeAdminUrl(string $slug): string
    {
        return add_query_arg(
            [
                '_nonce' => wp_create_nonce('mobile_theme'),
                'action' => 'deactivate',
                'slug' => $slug
            ], self::getAdminUrl()
        );
    }

    /**
     * mobile theme delete url builder
     *
     * @param string $slug
     *
     * @return string
     * @since 1.0.0
     */
    public static function getDeleteThemeAdminUrl(string $slug): string
    {
        return add_query_arg(
            [
                '_nonce' => wp_create_nonce('mobile_theme'),
                'action' => 'delete',
                'slug' => $slug
            ], self::getAdminUrl()
        );
    }

    /**
     * mobile theme convert url builder
     *
     * @param string $slug
     * @param string $mode
     * @return string
     * @since 1.0.0
     */
    public static function getConvertThemeAdminUrl(string $slug, string $mode = 'mobile'): string
    {
        return add_query_arg(
            [
                '_nonce' => wp_create_nonce('mobile_theme'),
                'action' => 'convert',
                'slug' => $slug,
                'convert' => $mode
            ], self::getAdminUrl()
        );
    }

    public static function getActiveTemplateSlug()
    {
        $slug = get_option('mi_active_theme', '');

        return !empty($slug) && file_exists(self::MOBILI_THEMES_DIR . '/' . $slug) ? $slug : '';
    }

    public function setActiveTemplate(string $slug): bool
    {
        return update_option('mi_active_theme', $slug, 'true');
    }

    /**
     * @return false|WP_Theme
     */
    public function getActiveTemplate()
    {
        $activeTheme = self::getActiveTemplateSlug();
        if (empty($activeTheme)) {
            return false;
        }
        $getTemplate = self::getTemplates($activeTheme);

        return $getTemplate[0] ?? false;
    }

    /**
     * check slug is mobile template
     *
     * @param string $slug
     *
     * @return bool
     * @since 1.0.0
     */
    public static function isValidMobileTemplate(string $slug): bool
    {
        if (empty($slug)) {
            return false;
        }
        if (!empty(self::getTemplates($slug))) {
            return true;
        }

        return false;

    }

    /**
     * @param string $slug
     * @return WP_Theme[]
     */
    public static function getTemplates(string $slug = ''): array
    {
        if (!file_exists(self::MOBILI_THEMES_DIR)) {
            return [];
        }
        if (empty($slug)) {
            $directories = array_filter(
                scandir(self::MOBILI_THEMES_DIR), function ($file) {
                return !is_file($file) && !in_array($file, ['.', '..']);
            }
            );
        } else {
            $directories = [$slug];
        }

        $templates = [];
        $convertedThemes = mi_get_converted_themes();

        foreach ($directories as $directory) {
            $theme = wp_get_theme($directory);
            if ($theme->errors() !== false) {
                continue;
            }
            $customHeaders = get_file_data($theme->get_file_path('style.css'), ['Mobili'], 'theme');
            if ((!isset($customHeaders[0]) || empty($customHeaders[0])) && !(isset($convertedThemes[$theme->get_stylesheet()]) && $convertedThemes[$theme->get_stylesheet()] === 'mobile')) {
                continue;
            }

            if (isset($convertedThemes[$theme->get_stylesheet()]) && $convertedThemes[$theme->get_stylesheet()] === 'desktop') {
                continue;
            }

            $theme->offsetSet('Mobili', true);
            $templates[] = $theme;

        }

        return $templates;
    }

    public static function registerAdminMenu()
    {
        $init = new self();
        $menuTitle = __('Mobile Themes', 'wp-mobili');
        $updates = Update::checkThemesUpdate();
        if (!empty($updates)) {
            $menuTitle .= sprintf(' <span class="update-plugins">%s</span>', count($updates));
        }
        $submenu = add_submenu_page(
            'themes.php', __('Mobile Themes', 'wp-mobili'), $menuTitle, 'install_themes',
            self::$menuSlug, [
            $init,
            'adminMenuContent'
        ], 1
        );
        add_action('admin_print_scripts-' . $submenu, [$init, 'enqueueScripts']);
    }

    public function enqueueScripts()
    {
        wp_enqueue_script('mi-script-settings');
        wp_enqueue_script('mi-script-theme-manager');
    }

    /**
     * admin mobile themes page controller
     *
     * @since 1.0.0
     */
    public function adminMenuContent()
    {
        if (!current_user_can('install_themes')) {
            wp_die(__('Sorry, you are not allowed to manage themes on this site.'));
        }
        $action = sanitize_key($_GET['action'] ?? '');
        if ($action === 'activate') {
            $this->adminMenuThemeActivator();
            mi_redirect(admin_url('themes.php?page=mobile-themes'));
        }
        if ($action === 'deactivate') {
            $this->adminMenuThemeDeactivate();
            mi_redirect(admin_url('themes.php?page=mobile-themes'));
        }
        if ($action === 'delete') {
            $this->adminMenuThemeDelete();
            mi_redirect(admin_url('themes.php?page=mobile-themes'));
        }
        if ($action === 'convert') {
            $this->adminMenuThemeConvert();
            $convertTo = sanitize_key($_GET['convert'] ?? 'mobile');
            if ($convertTo == 'mobile') {
                mi_redirect(admin_url('themes.php?page=mobile-themes'));
            } else {
                mi_redirect(admin_url('themes.php'));
            }
        }

        $templates = self::getTemplates();
        $currentTemplate = $this->getActiveTemplate();
        $currentTemplateSlug = self::getActiveTemplateSlug();

        if ($currentTemplate === false) {
            if (Inline_Theme::isInlineTheme()) {
                add_action('mobili_admin_themes_before_content', function () {
                    $currentDesktopTheme = wp_get_theme();
                    Log_Manager::printAdminMessage(
                        sprintf(__('Your desktop mode theme (%s) supports the Mobili plugin.', 'mobili'), $currentDesktopTheme->get('Name')), false, 'info', true
                    );
                });
            } else {
                add_action('mobili_admin_themes_before_content', function () {
                    Log_Manager::printAdminMessage(
                        __('There is no active mobile theme!', 'mobili'), false, 'warning', true
                    );
                });
            }
        }

        if ($currentTemplate instanceof WP_Theme && $currentTemplate->errors() !== false && !empty($currentTemplate->errors())) {
            foreach ($currentTemplate->errors()->get_error_messages() as $message) {
                add_action('mobili_admin_themes_before_content', function () use ($message) {
                    Log_Manager::printAdminMessage(
                        [
                            'title' => __('Template error', 'mobili'),
                            'content' => $message
                        ], false, 'error', true
                    );
                });
            }
        }

        if (!empty($currentTemplateSlug) && !self::isValidMobileTemplate($currentTemplateSlug)) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('Activated template is not available!', 'mobili'), false, 'error', true
                );
            });
        }

        mi_get_core_view(
            'admin/themes/manager.php', [
                'nonce' => wp_create_nonce('mobile_theme'),
                'templates' => $templates,
                'currentTemplate' => $currentTemplateSlug,
                'installUrl' => Install::getAdminUrl()
            ]
        );
    }

    public function adminMenuThemeActivator()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            )) {

            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The operation failed, please try again!', 'mobili'), false, 'error', true
                );
            });

            return;
        }

        if (!isset($_GET['slug']) || empty($_GET['slug']) || !$this->setCurrentTheme(sanitize_key($_GET['slug']))) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The template could not be found!', 'mobili'), false, 'error', true
                );
            });
        }
    }

    public function adminMenuThemeDelete()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            )) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The operation failed, please try again!', 'mobili'), false, 'error', true
                );
            });

            return;
        }

        if (!isset($_GET['slug']) || empty($_GET['slug']) || !$this->deleteTheme(sanitize_key($_GET['slug']))) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The template could not be deleted!', 'mobili'), false, 'error', true
                );
            });
        }
    }

    public function adminMenuThemeDeactivate()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            ) || !$this->setActiveTemplate('')) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The operation failed, please try again!', 'mobili'), false, 'error', true
                );
            });
        }
    }

    public function adminMenuThemeConvert()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            )) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The operation failed, please try again!', 'mobili'), false, 'error', true
                );
            });

            return;
        }
        $convertTo = sanitize_key($_GET['convert'] ?? 'mobile');

        if (!isset($_GET['slug']) || empty($_GET['slug']) || !self::convertTheme(sanitize_key($_GET['slug']), $convertTo)) {
            add_action('mobili_admin_themes_before_content', function () {
                Log_Manager::printAdminMessage(
                    __('The template could not be converted!', 'mobili'), false, 'error', true
                );
            });
        }
    }

    public function setCurrentTheme(string $slug): bool
    {
        if ($slug === self::getActiveTemplateSlug() || (self::isValidMobileTemplate($slug) && $this->setActiveTemplate(
                    $slug
                ))) {
            return true;
        }

        return false;
    }

    public function deleteTheme(string $slug): bool
    {
        global $wp_filesystem;
        require_once ABSPATH . 'wp-admin/includes/file.php';
        WP_Filesystem();

        if (self::isValidMobileTemplate($slug) && file_exists(self::MOBILI_THEMES_DIR . '/' . $slug) && $wp_filesystem->rmdir(self::MOBILI_THEMES_DIR . '/' . $slug, true)) {
            return true;
        }

        return false;
    }

    public static function convertTheme(string $slug, string $mode = 'mobile'): bool
    {
        $getOptions = mi_get_converted_themes(); // Get all converted themes.

        if (!in_array($mode, ['mobile', 'desktop'])) {
            $mode = 'mobile';
        }

        $getOptions[$slug] = $mode;

        if (update_option('mobili-converted_themes', $getOptions)) {
            return true;
        }

        return false;
    }
}