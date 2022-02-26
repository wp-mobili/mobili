<?php

namespace Mobili\Theme;
/**
 * Class Store
 * @package Mobili\Theme
 */
class Store
{
    const apiUrl = 'https://wp-mobili.com/wp-json/themes/v1/';

    private function _request(string $url, array $args = [], string $method = 'GET'): array
    {
        $request = wp_remote_request(
            $url, [
                'method' => $method,
                'body' => $args
            ]
        );
        if (!is_wp_error($request)) {
            return $request;
        }

        return [];
    }

    public static function downloadTheme($slug)
    {
        $self = new self();
        $request = $self->_request(self::apiUrl . 'download/', [
            'slug' => $slug
        ]);


        if (!isset($request['body']) || empty($request['body'])) {
            return (object)[];
        }

        return json_decode($request['body']);
    }

    public static function checkUpdates(array $slugs)
    {
        $self = new self();
        $request = $self->_request(self::apiUrl . 'updates/', [
            'themes' => implode(',',$slugs)
        ]);


        if (!isset($request['body']) || empty($request['body'])) {
            return [];
        }

        return json_decode($request['body'],true);
    }

    public static function getList()
    {
        $sort = $_POST['sort'] ?? 'popular';
        $search = $_POST['search'] ?? '';
        $page = !isset($_POST['page']) || !is_numeric(
            $_POST['page']
        ) || $_POST['page'] <= 0 ? 1 : (int)$_POST['page'];

        $requestArgs = [
            'sort' => $sort,
            'page' => $page
        ];
        if (!empty($search)) {
            $requestArgs['sort'] = 'search';
            $requestArgs['query'] = $search;
        }

        $self = new self();

        $transientName = 'mobili_trans_' . md5(json_encode($requestArgs));
        $transient = get_transient($transientName);

        if ($transient === false) {
            $request = $self->_request(self::apiUrl . 'list/', $requestArgs);
        } else {
            $request = $transient;
        }
        $response = [
            'status' => false,
            'data' => []
        ];

        if (!isset($request['body']) || empty($request['body'])) {
            wp_send_json($response);
        }
        $request['body'] = json_decode($request['body'], true);
        if (!is_array($request['body']) || empty($request['body']) || !is_array(
                $request['body']['themes']
            ) || empty($request['body']['themes'])) {
            wp_send_json($response);
        }
        $request['body']['themes'] = $request['body']['themes'] ?? [];

        if ($transient === false) {
            $request['body'] = json_encode($request['body']);
            set_transient($transientName, $request, 600);
            $request['body'] = json_decode($request['body'], true);
        }

        foreach ($request['body']['themes'] as &$theme) {
            $theme['install_url'] = Download::getInstallThemeAdminUrl($theme['slug']);

            if (current_user_can('switch_themes')) {
                $theme['activate_url'] = Manager::getActivateThemeAdminUrl($theme['slug']);
            }

            if (!is_multisite() && current_user_can('edit_theme_options') && current_user_can('customize')) {
                $theme['customize_url'] = add_query_arg(
                    [
                        'return' => urlencode(network_admin_url('theme-install.php', 'relative')),
                    ], wp_customize_url($theme['slug'])
                );
            }


            $theme['stars'] = wp_star_rating(
                [
                    'rating' => $theme['rating'],
                    'type' => 'percent',
                    'number' => $theme['num_ratings'],
                    'echo' => false,
                ]
            );

            $theme['num_ratings'] = number_format_i18n($theme['num_ratings']);
            $theme['preview_url'] = set_url_scheme($theme['preview_url']);
            $theme['compatible_wp'] = is_wp_version_compatible($theme['requires']);
            $theme['compatible_php'] = is_php_version_compatible($theme['requires_php']);
            $theme['compatible_mi'] = is_mobili_version_compatible($theme['requires_mi'] ?? '');
            $theme['installed'] = Manager::isValidMobileTemplate($theme['slug']);
            $theme['active'] = !empty($theme['slug']) && Manager::getActiveTemplateSlug() === $theme['slug'];
        }
        unset($theme);
        $response['status'] = true;
        $response['data'] = $request['body'];


        wp_send_json($response);
    }
}