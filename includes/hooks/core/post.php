<?php

function mi_hook_post_mobile_version_link($actions, $post)
{
    if (!current_user_can('edit_posts')) {
        return $actions;
    }

    if (!empty(get_post_meta($post->ID, 'is_mobile_version', true))) {
        return $actions;
    }

    $mobileVersionID = get_post_meta($post->ID, 'has_mobile_version', true);

    if (!empty($mobileVersionID) && get_post_status($mobileVersionID) !== false) {
        $url = add_query_arg(
            array(
                'action' => 'edit',
                'post' => $mobileVersionID
            ),
            admin_url('post.php')
        );
    } else {
        $url = wp_nonce_url(
            add_query_arg(
                array(
                    'action' => 'mobile_version',
                    'mobili_theme' => 'true',
                    'post' => $post->ID,
                ),
                admin_url('admin.php')
            ),
            basename(__FILE__),
            'mobili_nonce'
        );
    }

    $actions['mobile_version'] = sprintf('<a href="%s" title="%s" rel="permalink">%s</a>', $url, __('Edit mobile version of post', 'mobili'), __('Edit mobile version', 'mobili'));

    return $actions;
}

function mi_hook_create_post_mobile_version()
{
    if (empty($_GET['post'])) {
        wp_die('No post to duplicate has been provided!');
    }

    if (!isset($_GET['mobili_nonce']) || !wp_verify_nonce(esc_sql($_GET['mobili_nonce']), basename(__FILE__))) {
        return;
    }

    $postID = absint(esc_sql($_GET['post']));
    $post = get_post($postID);
    $currentUser = wp_get_current_user();
    $newPostAuthor = $currentUser->ID;

    if ($post) {
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status' => $post->ping_status,
            'post_author' => $newPostAuthor,
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_name' => $post->post_name,
            'post_parent' => $post->post_parent,
            'post_password' => $post->post_password,
            'post_status' => 'draft',
            'post_title' => $post->post_title,
            'post_type' => $post->post_type,
            'to_ping' => $post->to_ping,
            'menu_order' => $post->menu_order
        );

        $newPostID = wp_insert_post($args);

        $taxonomies = get_object_taxonomies(get_post_type($post));
        if ($taxonomies) {
            foreach ($taxonomies as $taxonomy) {
                $postTerms = wp_get_object_terms($postID, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($newPostID, $postTerms, $taxonomy, false);
            }
        }


        $postMeta = get_post_meta($postID);
        if ($postMeta) {
            foreach ($postMeta as $meta_key => $meta_values) {
                if ('_wp_old_slug' == $meta_key) {
                    continue;
                }

                foreach ($meta_values as $meta_value) {
                    add_post_meta($newPostID, $meta_key, $meta_value);
                }
            }
        }
        update_post_meta($newPostID, 'is_mobile_version', $postID);
        update_post_meta($postID, 'has_mobile_version', $newPostID);

        wp_safe_redirect(
            add_query_arg(
                array(
                    'action' => 'edit',
                    'post' => $newPostID
                ),
                admin_url('post.php')
            )
        );
        exit;

    }
    wp_die('Post creation failed, could not find original post.');
}

function mi_hook_remove_mobile_version_post(int $postID, WP_Post $post)
{
    if (isset($post->post_parent) && !empty($post->post_parent)) {
        $postID = $post->post_parent;
    }
    $parentPost = get_post_meta($post->post_parent, 'is_mobile_version', true);

    if (!empty($parentPost)) {
        delete_post_meta($postID, 'is_mobile_version');
        delete_post_meta($parentPost, 'has_mobile_version');
    }
}

function mi_hook_mobile_version_edit_admin_bar($admin_bar)
{
    if (!is_singular('page') || !current_user_can('edit_posts') || !empty(get_post_meta(get_the_ID(), 'is_mobile_version', true))) {
        return;
    }

    $mobileVersionID = get_post_meta(get_the_ID(), 'has_mobile_version', true);
    $menuTitle = __('Edit mobile version', 'mobili');
    $menuLink = add_query_arg(
        [
            'action' => 'edit',
            'post' => $mobileVersionID
        ],
        admin_url('post.php')
    );

    if (empty($mobileVersionID)) {
        $menuTitle = __('Create mobile version', 'mobili');
        $menuLink = wp_nonce_url(
            add_query_arg(
                [
                    'action' => 'mobile_version',
                    'mobili_theme' => 'true',
                    'post' => get_the_ID(),
                ],
                admin_url('admin.php')
            ),
            basename(__FILE__),
            'mobili_nonce'
        );
    }

    $admin_bar->add_menu([
        'id' => 'mobili-edit_mobile_version',
        'title' => $menuTitle,
        'parent' => 'edit',
        'href' => $menuLink
    ]);
}

function mi_hook_post_mobile_version_redirect()
{

    if (!mi_can_load_assets() && !is_front_page() && !isset($_GET['mobili_theme']) && is_singular('page') && !empty(get_post_meta(get_the_ID(), 'is_mobile_version', true))) {
        wp_redirect(add_query_arg(['mobili_theme' => 'true']));
        die;
    }

}

function mi_hook_setup_post_mobile_version_content(WP_Post &$currentPost, WP_Query $query)
{
    if (!mi_can_load_assets()) {
        return;
    }
    if (is_admin()) {
        return;
    }
    global $post;
    $mobileVersion = get_post_meta($currentPost->ID, 'has_mobile_version', true);
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
    $currentPost = $mobilePost;
    $query->setup_postdata($mobilePost);
}

function mi_hook_display_post_mobile_version_states($post_states, $post)
{
    if (!empty(get_post_meta($post->ID, 'is_mobile_version', true))) {
        $post_states[] = __('Mobile Version', 'mobili');
    }

    return $post_states;
}

function mi_hook_option_page_on_front($value , $option){
    if (!mi_can_load_assets()){
        return $value;
    }

    $mobileVersion = '';
    if (!empty($value)){
        $mobileVersion = get_post_meta($value, 'has_mobile_version', true);
    }

    if (empty($mobileVersion)) {
        return $value;
    }
    $mobilePost = get_post($mobileVersion);
    if (empty($mobilePost)) {
        return $value;
    }

    if (isset($mobilePost->post_status) && $mobilePost->post_status === 'publish') {
        return $mobileVersion;
    }


    return $value;
}

add_filter('page_row_actions', 'mi_hook_post_mobile_version_link', 10, 2);
add_action('admin_action_mobile_version', 'mi_hook_create_post_mobile_version');
add_action('delete_post', 'mi_hook_remove_mobile_version_post', 10, 2);
add_action('admin_bar_menu', 'mi_hook_mobile_version_edit_admin_bar');
add_action('template_redirect', 'mi_hook_post_mobile_version_redirect');
add_action('the_post', 'mi_hook_setup_post_mobile_version_content', 10, 2);
add_filter('display_post_states', 'mi_hook_display_post_mobile_version_states', 10, 2);
add_filter('option_page_on_front','mi_hook_option_page_on_front',10,2);