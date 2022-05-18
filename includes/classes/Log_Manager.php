<?php

namespace Mobili;

/**
 * Class Log_Manager
 *
 * @since   1.0.0
 * @package Mobili
 */
class Log_Manager
{
    /**
     * print admin messages html
     *
     * @param array|string $content
     * @param bool $dismissible
     * @param string $type
     * @param bool $echo
     * @return false|string
     */
    public static function printAdminMessage($content, bool $dismissible = true, string $type = 'success', bool $echo = false)
    {
        $classes = [];

        if ($dismissible) {
            $classes[] = 'is-dismissible';
        }
        if (!empty($type)) {
            $classes[] = 'notice-' . $type;
        }

        if (!$echo) {
            ob_start();
        }
        printf('<div class="notice %s">', implode(' ', $classes));
        if (is_array($content)) {
            if (isset($content['title'])) {
                printf('<p><b>%s</b></p>', esc_html($content['title']));
            }
            if (isset($content['content'])) {
                printf('<p>%s</p>', esc_html($content['content']));
            }
        } else if (is_string($content)) {
            printf('<p>%s</p>', esc_html($content));
        }
        echo '</div>';

        if (!$echo) {
            return ob_get_clean();
        }
        return '';
    }
}