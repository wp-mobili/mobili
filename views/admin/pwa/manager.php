<?php
/**
 * @var string $menuSlug
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?>
        <br>
        <small><?php _e('Make your website an installable application!', 'mobili'); ?>
            <a href="https://wp-mobili.com/article/meaning-and-use-cases-of-pwa/?utm_source=wordpress&utm_campaign=plugin" target="_blank"><?php _e('Need help?','mobili'); ?></a>
        </small>
    </h1>
    <br>
    <div class="tab-content">
		<?php settings_errors('mobili_pwa_messages'); ?>
        <div class="mi-panel-outer">
            <div class="half-panel w100-in-tablet">
                <form method="post" action="<?= admin_url('options.php') ?>">
					<?php
					settings_fields('mobili_pwa');
					do_settings_sections($menuSlug);
					submit_button();
					?>
                </form>
            </div>
            <div class="half-panel hide-in-tablet">
                <div class="mi-iphone-mockup">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 380 700">
                        <defs>
                            <style>.cls-1{fill:#fff;stroke:#000;stroke-miterlimit:10;stroke-width:6px;}.cls-2{fill:#666;}</style>
                        </defs>
                        <title>iphone</title>
                        <g>
                            <g>
                                <rect data-style="fill: [value]" data-input="#mobili-pwa_color-background" class="cls-1 smart-content" x="3" y="3" width="374" height="694" rx="54.87"/>
                                <path d="M121,3.5H259a0,0,0,0,1,0,0v13a25,25,0,0,1-25,25H146a25,25,0,0,1-25-25V3.5A0,0,0,0,1,121,3.5Z"/>
                                <rect class="cls-2" x="133.5" y="683.5" width="113" height="5" rx="2.5"/>
                            </g>
                        </g>
                    </svg>
                    <div class="height-fill"></div>
                    <div class="mockup-inner">
                        <div class="app-icon">
                            <img class="smart-content" data-src="#mobili-pwa_icon-192" src="<?= get_option(
	                            'mobili-pwa_icon-192'
                            ) ?>" alt="App Icon"></div>
                        <div class="app-title smart-content" data-text="#mobili-pwa_name"><?= get_option(
								'mobili-pwa_name'
							) ?></div>
                        <div class="app-desc smart-content" data-text="#mobili-pwa_description"><?= get_option(
								'mobili-pwa_description'
							) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>