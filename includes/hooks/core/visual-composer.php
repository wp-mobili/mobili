<?php
function mi_hook_setup_post_mobile_version_head()
{
    if (!mi_can_load_assets()) {
        return;
    }
    if (is_admin()) {
        return;
    }
    global $post;
    $mobileVersion = get_post_meta(get_the_ID(), 'has_mobile_version', true);
    if (empty($mobileVersion)) {
        return;
    }
    $mobilePost = get_post($mobileVersion);
    if (empty($mobilePost)) {
        return;
    }
    if (!isset($mobilePost->post_status) || $mobilePost->post_status !== 'publish') {
        return;
    }

    $post = $mobilePost;
}

function mi_hook_wpb_navbar_getControlScreenSize($sizes): array
{
    if (empty($sizes)) {
        return [];
    }
    foreach ($sizes as $key => $val) {
        if ($val['key'] === 'default') {
            unset($sizes[$key]);
        }
        if ($val['key'] === 'portrait-tablets') {
            $sizes[$key]['key'] = 'default';
            $sizes[$key]['active'] = true;
        }
    }

    return $sizes;
}

add_action('wp_head', 'mi_hook_setup_post_mobile_version_head', -10);
add_filter('wpb_navbar_getControlScreenSize', 'mi_hook_wpb_navbar_getControlScreenSize');