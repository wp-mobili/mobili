<?php

namespace Mobili;

use Mobili\Theme\Manager;

/**
 * Class Inline_Theme
 * @package Mobili
 */
class Inline_Theme
{
    public static function canLoadAssets()
    {
        if (self::isInlineTheme() && empty(Manager::getActiveTemplateSlug()) && Manager::getActiveTemplateSlug() !== get_option('stylesheet')) {
            return true;
        }

        return false;
    }

    public static function isInlineTheme(string $slug = ''): bool
    {
        if (empty($slug)) {
            $themeDirectory = get_template_directory();
        } else {
            $theme = wp_get_theme($slug);
            if ($theme->errors() !== false) {
                return false;
            }
            $themeDirectory = $theme->get_template_directory();
        }
        if (file_exists($themeDirectory . MOBILI_DSP . apply_filters('mobili_templates_directory', 'mobili'))) {
            return true;
        }
        return false;
    }
}