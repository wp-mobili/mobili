<?php

namespace Mobili\Theme;

use Mobili\Theme_Upgrader;
use Theme_Upgrader_Skin;

class Update
{
    static $menuSlug = 'update-mobile-theme';

    public static function getAdminUrl()
    {
        return admin_url('themes.php?page=' . self::$menuSlug);
    }

    /**
     * mobile theme updater url builder
     *
     * @param string $slug
     *
     * @return string
     * @since 1.0.0
     */
    public static function getUpdateThemeAdminUrl(string $slug): string
    {
        return add_query_arg(
            [
                '_wpnonce' => wp_create_nonce('mobile_theme_update'),
                'theme' => $slug
            ], self::getAdminUrl()
        );
    }

    public static function registerAdminMenu()
    {
        $init = new self();
        add_submenu_page(
            null, __('Update mobile theme', 'wp-mobili'), __('Update mobile theme', 'wp-mobili'), 'update_themes',
            self::$menuSlug, [
                $init,
                'adminMenuContent'
            ]
        );
    }

    public static function checkThemesUpdate()
    {
        $themesList = Manager::getTemplates();
        $themesSlugs = [];
        $themesVersions = [];
        if (empty($themesList)) {
            return [];
        }
        foreach ($themesList as $item) {
            $themesSlugs[] = $item->get_stylesheet();
            $themesVersions[$item->get_stylesheet()] = $item->get('Version');
        }
        $transientName = 'mobili_themes_updates';
        $transient = get_transient($transientName);
        if ($transient === false) {
            $updates = Store::checkUpdates($themesSlugs);
            set_transient($transientName, $updates, 60 * 60 * 12);
        } else {
            $updates = $transient;
        }

        if (!empty($updates)) {
            foreach ($updates as $key => $update) {
                if (version_compare($update['new_version'], $themesVersions[$update['theme']], '<=')) {
                    unset($updates[$key]);
                }
            }
            unset($update);
        }
        return $updates;
    }

    public static function themeUpdates(): object
    {
        $updates = self::checkThemesUpdate();
        $parsedUpdates = [];
        if (!empty($updates)) {
            foreach ($updates as $update) {
                $parsedUpdates[$update['theme']] = $update;
            }
        }

        return (object)[
            'last_checked' => time(),
            'response' => $parsedUpdates,
        ];
    }

    public function adminMenuContent()
    {
        if (!current_user_can('update_themes') || !isset($_REQUEST['_wpnonce'], $_REQUEST['theme']) || !wp_verify_nonce(esc_sql($_REQUEST['_wpnonce']), 'mobile_theme_update')) {
            wp_die(__('Sorry, you are not allowed to update themes on this site.'));
        }

        $theme = isset($_REQUEST['theme']) ? urldecode(sanitize_key($_REQUEST['theme'])) : '';

        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        // Used in the HTML title tag.
        $title = __('Update Theme');

        require_once ABSPATH . 'wp-admin/admin-header.php';

        $nonce = 'upgrade-theme_' . $theme;
        $url = 'update.php?action=upgrade-theme&theme=' . urlencode($theme);

        add_filter('update_theme_complete_actions', [$this, 'installThemeCompleteActions'], 10, 2);
        delete_transient('mobili_themes_updates');
        $upgrader = new Theme_Upgrader(new Theme_Upgrader_Skin(compact('title', 'nonce', 'url', 'theme')));
        $upgrader->upgrade($theme);

        remove_filter('update_theme_complete_actions', [$this, 'installThemeCompleteActions']);


        require_once ABSPATH . 'wp-admin/admin-footer.php';
    }

    public function installThemeCompleteActions($update_actions, $theme)
    {
        if (isset($update_actions['themes_page'])) {
            $update_actions['themes_page'] = sprintf(
                '<a href="%s" target="_parent">%s</a>', esc_attr(Manager::getAdminUrl()), __('Go to Mobile Themes page', 'mobili')
            );
        }


        return $update_actions;
    }
}