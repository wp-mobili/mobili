<?php
/**
 * Plugin Name: Mobili
 * Description: The Website Mobile Version Builder.
 * Plugin URI: https://test.org/plugins/mobili/
 * Author: test.com
 * Version: 1.0.0
 * Author URI: https://test.com/
 * Text Domain: mobili
 */

if ( !defined('ABSPATH') ) {
	exit;
}
if ( !defined('MOBILI_DISABLE_SESSION') && !session_id()) {
    session_start();
}


const MOBILI_DSP = DIRECTORY_SEPARATOR;

const MOBILI_FILE   = __FILE__;
const MOBILI_PREFIX = 'mi';

define("MOBILI_DIR", plugin_dir_path(MOBILI_FILE));
define("MOBILI_URL", plugin_dir_url(MOBILI_FILE));
define("MOBILI_BASENAME", plugin_basename(MOBILI_FILE));

const MOBILI_VERSION = '1.0.0';

const MOBILI_ASSETS_DIR = MOBILI_DIR . 'assets';
const MOBILI_ASSETS_URL = MOBILI_URL . 'assets';

const MOBILI_TEMPLATES_DIR = MOBILI_DIR . 'templates';
const MOBILI_TEMPLATES_URL = MOBILI_URL . 'templates';


add_action('plugins_loaded', 'mobiliLoadTextDomain');

if ( !version_compare(PHP_VERSION, '7.2', '>=') ) {
	add_action('admin_notices', 'mobiliPhpVersionError');
} elseif ( !version_compare(get_bloginfo('version'), '5.2', '>=') ) {
	add_action('admin_notices', 'mobiliWpVersionError');
} else {
	require MOBILI_DIR . 'vendor' . MOBILI_DSP . 'autoload.php';
	require MOBILI_DIR . 'includes' . MOBILI_DSP . 'bootstrap.php';
}


/**
 * Load Mobili textdomain.
 *
 * @return void
 * @since 1.0.0
 *
 */
function mobiliLoadTextDomain () {
	load_plugin_textdomain('mobili');
}

/**
 * Mobili admin notice for minimum PHP version.
 *
 * @return void
 * @since 1.0.0
 *
 */
function mobiliPhpVersionError () {
	$message      = sprintf(esc_html__('Mobili requires PHP version %s+, plugin is currently NOT RUNNING.', 'mobili'), '7.4');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}

/**
 * Mobili admin notice for minimum WordPress version.
 *
 * @return void
 * @since 1.0.0
 *
 */
function mobiliWpVersionError () {
	$message      = sprintf(esc_html__('Mobili requires WordPress version %s+. Because you are using an earlier version, the plugin is currently NOT RUNNING.', 'mobili'), '5.2');
	$html_message = sprintf('<div class="error">%s</div>', wpautop($message));
	echo wp_kses_post($html_message);
}