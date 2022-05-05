<?php


namespace Mobili;


use WP_Admin_Bar;

class Mobile_Mode
{
    static $menuSlug = 'mobili-mobile-mode';

    public static function getAdminUrl()
    {
        return admin_url('options-general.php?page=' . self::$menuSlug);
    }

    public static function registerAdminMenu()
    {
        if (defined('MOBILI_DISABLE_SESSION')) {
            return;
        }
        $init = new self();
        add_submenu_page(
            null, __('Mobile mode', 'wp-mobili'), __('Mobile mode', 'wp-mobili'), 'manage_options',
            self::$menuSlug, [
            $init,
            'adminMenuContent'
        ], 3
        );
    }

    public function adminMenuContent()
    {
        if (!current_user_can('manage_options')) {
            mi_redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
            return;
        }
        self::setStatus(!self::getStatus());
        mi_redirect($_SERVER['HTTP_REFERER'] ?? admin_url());
    }

    public static function adminNotices()
    {
        if (!self::getStatus()) {
            return;
        }
        echo Log_Manager::printAdminMessage([
            'title' => __('Mobile mode is active!', 'mobili'),
            'content' => __('Please disable this option from the top bar when you are done.', 'mobili')
        ], false, 'warning');
    }

    public static function getStatus(): bool
    {
        return isset($_SESSION['mobili_mobile_mode']) && $_SESSION['mobili_mobile_mode'];
    }

    public static function setStatus(bool $status = true): void
    {
        $_SESSION['mobili_mobile_mode'] = $status;
    }

    public static function adminBar(WP_Admin_Bar $adminBar)
    {
        $adminBar->add_menu([
            'id' => 'mobili-enable_mobile_version',
            'title' => self::getStatus() ? __('Disable mobile mode', 'mobili') : __('Enable mobile mode', 'mobili'),
            'href' => self::getAdminUrl(),
            'parent' => 'top-secondary'
        ]);
        $adminBar->add_menu([
            'id' => 'mobili-about_mobile_version',
            'title' => __('What is the mobile mode?', 'mobili'),
            'href' => 'https://wp-mobili.com/article/mobile-mode-in-admin-panel/?utm_source=wordpress&utm_campaign=plugin',
            'parent' => 'mobili-enable_mobile_version'
        ]);
    }

    public static function canLoadAssets($default)
    {
        if (self::getStatus()) {
            return true;
        }
        return $default;
    }
}