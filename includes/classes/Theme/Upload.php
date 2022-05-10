<?php

namespace Mobili\Theme;

use File_Upload_Upgrader;
use Mobili\Theme_Upgrader;
use Theme_Installer_Skin;

class Upload
{
    static $menuSlug = 'upload-mobile-theme';

    public static function getAdminUrl()
    {
        return admin_url('themes.php?page=' . self::$menuSlug);
    }

    public static function registerAdminMenu()
    {
        $init = new self();
        add_submenu_page(
            null, __('Upload Mobile Themes', 'wp-mobili'), __('Upload Mobile Themes', 'wp-mobili'), 'upload_themes',
            self::$menuSlug, [
                $init,
                'adminMenuContent'
            ]
        );
    }

    public function adminMenuContent()
    {
        if (!current_user_can('upload_themes') || !isset($_FILES['themezip'], $_POST['_wpnonce']) || !wp_verify_nonce(esc_sql($_POST['_wpnonce']), 'mobile_theme_upload')) {
            wp_die(__('Sorry, you are not allowed to upload theme on this site.','mobili'));
        }


        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

        $file_upload = new File_Upload_Upgrader('themezip', 'package');

        require_once ABSPATH . 'wp-admin/admin-header.php';

        /* translators: %s: File name. */
        $title = sprintf(__('Installing theme from uploaded file: %s'), esc_html(basename($file_upload->filename)));
        $nonce = 'theme-upload';
        $url = add_query_arg(['package' => $file_upload->id], 'update.php?action=upload-theme');
        $type = 'upload'; // Install theme type, From Web or an Upload.

        $overwrite = isset($_GET['overwrite']) ? sanitize_text_field($_GET['overwrite']) : '';
        $overwrite = in_array($overwrite, ['update-theme', 'downgrade-theme'], true) ? $overwrite : '';

        add_filter('install_theme_complete_actions', [$this, 'installThemeCompleteActions'], 10, 4);
        delete_transient('mobili_themes_updates');

        $upgrader = new Theme_Upgrader(new Theme_Installer_Skin(compact('type', 'title', 'nonce', 'url', 'overwrite')));

        $result = $upgrader->install($file_upload->package, ['overwrite_package' => $overwrite]);

        if ($result || is_wp_error($result)) {
            $file_upload->cleanup();
        }

        remove_filter('install_theme_complete_actions', [$this, 'installThemeCompleteActions']);
    }

    public function installThemeCompleteActions($install_actions, $api, $stylesheet, $theme_info)
    {
        Manager::convertTheme($stylesheet);
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