<?php

namespace Mobili\Theme;

use Mobili\Theme_Upgrader;
use Theme_Installer_Skin;

class Download
{
    static $menuSlug = 'download-mobile-theme';

    public static function getAdminUrl()
    {
        return admin_url('themes.php?page=' . self::$menuSlug);
    }

    /**
     * mobile theme install url builder
     *
     * @param string $slug
     *
     * @return string
     * @since 1.0.0
     */
    public static function getInstallThemeAdminUrl (string $slug) : string {
        return add_query_arg(
            [
                '_wpnonce' => wp_create_nonce('mobile_theme_download'),
                'theme'   => $slug
            ], self::getAdminUrl()
        );
    }
    public static function registerAdminMenu()
    {
        $init = new self();
        add_submenu_page(
            null, __('Download Mobile Themes', 'wp-mobili'), __('Download Mobile Themes', 'wp-mobili'), 'manage_options',
            self::$menuSlug, [
                $init,
                'adminMenuContent'
            ]
        );
    }

    public function adminMenuContent()
    {
        if (!current_user_can('install_themes') || !isset($_REQUEST['_wpnonce'], $_REQUEST['theme']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'mobile_theme_download')) {
            wp_die( __( 'Sorry, you are not allowed to install themes on this site.' ) );
        }
        $theme  = isset( $_REQUEST['theme'] ) ? urldecode( $_REQUEST['theme'] ) : '';

        include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php'; // For themes_api().

        $api = Store::downloadTheme($theme); // Save on a bit of bandwidth.

        if ( is_wp_error( $api ) ) {
            wp_die( $api );
        }

        $title        = __( 'Install Mobile Themes','mobili' );
        require_once ABSPATH . 'wp-admin/admin-header.php';

        /* translators: %s: Theme name and version. */
        $title = sprintf( __( 'Installing Theme: %s' ), $api->name . ' ' . $api->version );
        $nonce = 'install-theme_' . $theme;
        $url   = 'update.php?action=install-theme&theme=' . urlencode( $theme );
        $type  = 'web'; // Install theme type, From Web or an Upload.

        add_filter('install_theme_complete_actions', [$this, 'installThemeCompleteActions'], 10, 4);

        delete_transient('mobili_themes_updates');
        $upgrader = new Theme_Upgrader( new Theme_Installer_Skin( compact( 'title', 'url', 'nonce', 'api' ) ) );
        $upgrader->install( $api->download_link );

        remove_filter('install_theme_complete_actions', [$this, 'installThemeCompleteActions']);

        require_once ABSPATH . 'wp-admin/admin-footer.php';
    }

    public function installThemeCompleteActions($install_actions, $api, $stylesheet, $theme_info)
    {
        if (isset($install_actions['themes_page'])) {
            $install_actions['themes_page'] = sprintf(
                '<a href="%s" target="_parent">%s</a>', Manager::getAdminUrl(), __('Go to Mobile Themes page', 'mobili')
            );
        }
        if (isset($install_actions['activate'])) {
            $install_actions['activate'] = sprintf(
                '<a href="%s" class="activatelink"><span aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
                Manager::getActivateThemeAdminUrl($stylesheet), __('Activate'),
                sprintf(__('Activate “%s”', 'mobili'), $stylesheet)
            );
        }
        if (isset($install_actions['preview'])) {
            $customize_url = add_query_arg(
                [
                    'theme' => urlencode($stylesheet),
                    'return' => urlencode(Install::getAdminUrl()),
                    'mobili_theme' => 'active'
                ], admin_url('customize.php')
            );
            $install_actions['preview'] = sprintf(
                '<a href="%s" class="hide-if-no-customize load-customize">' . '<span aria-hidden="true">%s</span><span class="screen-reader-text">%s</span></a>',
                esc_url($customize_url), __('Live Preview'), sprintf(__('Live Preview &#8220;%s&#8221;'), $stylesheet)
            );

            unset($install_actions['preview']);
        }

        return $install_actions;
    }
}