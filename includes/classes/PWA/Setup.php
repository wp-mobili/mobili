<?php


namespace Mobili\PWA;

/**
 * Class Setup
 *
 * PWA setup
 *
 * @since   1.0.0
 * @package Mobili\PWA
 */
class Setup
{
    public static function init()
    {
        if (!mi_pwa_is_active()) {
            return;
        }

        $class = new self();
        $class->serviceWorker();
        $class->offlinePage();
        $class->manifest();
    }

    public static function installBox()
    {
        if (!mi_can_load_assets() || !mi_pwa_is_active()) {
            return;
        }
        mi_get_template_part('install.php');
    }

    public static function manifestLoader()
    {
        if (!mi_can_load_assets() || !mi_pwa_is_active()) {
            return;
        }
        echo '<link rel="manifest" href="' . site_url('/manifest.json') . '">' . PHP_EOL;
        echo '<meta name="theme-color" content="' . get_option('mobili-pwa_color-theme') . '">';
    }

    public function manifest()
    {
        $siteUrl = str_replace(
                [
                    'https://',
                    'http://',
                    $_SERVER['SERVER_NAME']
                ], '', site_url()
            ) . '/';
        $manifest = [
            'short_name' => get_option('mobili-pwa_shortname'),
            'name' => get_option('mobili-pwa_name'),
            'description' => get_option('mobili-pwa_description'),
            'background_color' => get_option('mobili-pwa_color-background'),
            'theme_color' => get_option('mobili-pwa_color-theme'),
            'display' => 'standalone',
            'start_url' => $siteUrl,
            'scope' => $siteUrl,
            'icons' => []
        ];
        $iconSizes = ['192', '512', '48', '72', '96', '144', '168'];
        foreach ($iconSizes as $size) {
            $iconUrl = get_option('mobili-pwa_icon-' . $size, '');
            if (empty($iconUrl)) {
                continue;
            }

            $manifest['icons'][] = [
                'src' => $iconUrl,
                'type' => image_type_to_mime_type(exif_imagetype($iconUrl)),
                'sizes' => $size . 'x' . $size
            ];
        }

        $content = json_encode($manifest);
        $targetDir = ABSPATH . 'manifest.json';

        if (file_exists($targetDir) && sha1_file($targetDir) === sha1($content)) {
            return;
        }
        file_put_contents($targetDir, $content);
    }

    public function offlinePage()
    {
        $content = mi_get_template_part('offline.php', [], true);

        $targetDir = ABSPATH . 'offline.html';

        if (file_exists($targetDir) && sha1_file($targetDir) === sha1($content)) {
            return;
        }
        file_put_contents($targetDir, $content);
    }

    public function serviceWorker()
    {
        $content = mi_get_template_part('service-worker.js', [], true);
        $targetDir = ABSPATH . 'service-worker.js';

        if (file_exists($targetDir) && sha1_file($targetDir) === sha1($content)) {
            return;
        }
        file_put_contents($targetDir, $content);
    }
}