<?php

namespace Mobili;

/**
 * Class Log_Manager
 *
 * @since   1.0.0
 * @package Mobili
 */
class Log_Manager {
	/**
	 * print admin messages html
	 *
	 * @param array|string $content
	 * @param bool         $dismissible
	 * @param string       $type
	 *
	 * @return string
	 */
	public static function printAdminMessage ($content, bool $dismissible = true, string $type = 'success') : string {
		$classes = [];

		if ( $dismissible ) {
			$classes[] = 'is-dismissible';
		}
		if ( !empty($type) ) {
			$classes[] = 'notice-' . $type;
		}

		$output = sprintf('<div class="notice %s">', implode(' ', $classes));
		if ( is_array($content) ) {
			if ( isset($content['title']) ) {
				$output .= sprintf('<p><b>%s</b></p>', $content['title']);
			}
			if ( isset($content['content']) ) {
				$output .= sprintf('<p>%s</p>', $content['content']);
			}
		} else if ( is_string($content) ) {
			$output .= sprintf('<p>%s</p>', $content);
		}
		$output .= '</div>';

		return $output;
	}
}