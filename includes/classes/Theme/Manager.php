<?php

namespace Mobili\Theme;

use Mobili\Inline_Theme;
use Mobili\Log_Manager;
use WP_Filesystem_Base;

/**
 * Class Theme_Manager
 *
 * @since   1.0.0
 * @package Mobili
 */
class Manager
{
    const MOBILI_THEMES_DIR = WP_CONTENT_DIR . '/themes';
    private $messages = [];
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
     * @return false|\WP_Theme
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
     * @return \WP_Theme[]
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

        foreach ($directories as $directory) {
            $theme = wp_get_theme($directory);
            if ($theme->errors() !== false) {
                continue;
            }
            $customHeaders = get_file_data($theme->get_file_path('style.css'), ['Mobili'], 'theme');
            if (!isset($customHeaders[0]) || empty($customHeaders[0])) {
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
        if (!current_user_can('install_themes')){
            wp_die(__('Sorry, you are not allowed to manage themes on this site.'));
        }
        $action = esc_sql($_GET['action'] ?? '');
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

        $templates = self::getTemplates();
        $currentTemplate = $this->getActiveTemplate();
        $currentTemplateSlug = self::getActiveTemplateSlug();

        if ($currentTemplate === false) {
            if (Inline_Theme::isInlineTheme()) {
                $currentDesktopTheme = wp_get_theme();
                $this->messages[] = Log_Manager::printAdminMessage(
                    sprintf(__('Your desktop mode theme (%s) supports WP Mobili plugin.', 'mobili'),$currentDesktopTheme->get('Name')), false, 'info'
                );
            } else {
                $this->messages[] = Log_Manager::printAdminMessage(
                    __('There is no active mobile theme!', 'mobili'), false, 'warning'
                );
            }
        }

        if ($currentTemplate instanceof \WP_Theme && $currentTemplate->errors() !== false && !empty($currentTemplate->errors())) {
            foreach ($currentTemplate->errors()->get_error_messages() as $message) {
                $this->messages[] = Log_Manager::printAdminMessage(
                    [
                        'title' => __('Template error', 'mobili'),
                        'content' => $message
                    ], false, 'error'
                );
            }
        }

        if (!empty($currentTemplateSlug) && !self::isValidMobileTemplate($currentTemplateSlug)) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('Activated template is not available!', 'mobili'), false, 'error'
            );
        }

        add_action('mobili_admin_themes_before_content', [$this, 'adminMenuThemesMessages']);

        mi_get_core_view(
            'admin/themes/manager.php', [
                'nonce' => wp_create_nonce('mobile_theme'),
                'templates' => $templates,
                'currentTemplate' => $currentTemplateSlug,
                'installUrl' => Install::getAdminUrl()
            ]
        );
    }

    /**
     * print admin messages
     *
     * @since 1.0.0
     */
    public function adminMenuThemesMessages()
    {
        if (empty($this->messages)) {
            return;
        }
        foreach ($this->messages as $message) {
            echo $message;
        }
    }

    public function adminMenuThemeActivator()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            )) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('The operation failed, please try again!', 'mobili'), false, 'error'
            );

            return;
        }

        if (!isset($_GET['slug']) || empty($_GET['slug']) || !$this->setCurrentTheme(esc_sql($_GET['slug']))) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('The template could not be found!', 'mobili'), false, 'error'
            );
        }
    }

    public function adminMenuThemeDelete()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            )) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('The operation failed, please try again!', 'mobili'), false, 'error'
            );

            return;
        }

        if (!isset($_GET['slug']) || empty($_GET['slug']) || !$this->deleteTheme(esc_sql($_GET['slug']))) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('The template could not be deleted!', 'mobili'), false, 'error'
            );
        }
    }

    public function adminMenuThemeDeactivate()
    {
        if (isset($_GET['_nonce']) && is_string($_GET['_nonce']) && !wp_verify_nonce(
                esc_sql($_GET['_nonce']), 'mobile_theme'
            ) || !$this->setActiveTemplate('')) {
            $this->messages[] = Log_Manager::printAdminMessage(
                __('The operation failed, please try again!', 'mobili'), false, 'error'
            );
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
}