<?php

use Mobili\Adaptations\Elementor\Elementor;

function mi_hook_elementor_create_template_dialog()
{ ?>
    <p id="elementor-new-template__form__mobili__wrapper">
        <?php _e('If you do not see the features of your mobile template, enable the mobile mode option from the top bar.','mobili'); ?>
    </p>
<?php }

add_action('elementor/template-library/create_new_dialog_fields', 'mi_hook_elementor_create_template_dialog');
add_action('elementor/theme/register_conditions', [Elementor::class,'register_conditions']);