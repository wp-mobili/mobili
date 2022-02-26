<?php
function mi_hook_customize_previewable_devices($devices): array
{
    if (!mi_can_load_assets()){
        return $devices;
    }
    if (isset($devices['desktop'])) {
        unset($devices['desktop']);
    }
    if (isset($devices['tablet'])) {
        $devices['tablet']['default'] = true;
    }
    return $devices;
}

function mi_hook_customize_enqueue_scripts()
{
    if (mi_can_load_assets()){
        return;
    }
    wp_enqueue_script('mi-customize-scripts', MOBILI_URL . '/assets/scripts/admin/customize.js', ['jquery'],rand(0,100));
    wp_localize_script(
        'mi-customize-scripts', 'mobiliCustomizeArgs', [
            'mobileVersionLabel' => __('Mobile Version','mobili'),
            'mobileVersionUrl' => add_query_arg(['mobili_theme'=>'active'])
        ]
    );
}


add_action('customize_controls_print_scripts', 'mi_hook_customize_enqueue_scripts');
add_filter('customize_previewable_devices', 'mi_hook_customize_previewable_devices', 10, 1);