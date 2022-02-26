<?php
/**
 * Mobili themes admin page
 *
 * @var $templates            \WP_Theme[]
 * @var $currentTemplate      string|false
 * @var $nonce                string
 * @var $installUrl           string
 */

use Mobili\Theme\Update;

$themes = mi_prepare_themes_for_js($templates);
?>
<?php do_action('mobili_admin_themes_before_page'); ?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?= __('Mobile Themes', 'mobili') ?>
        <span class="title-count theme-count"><?= count($templates); ?></span>
    </h1>

    <a href="<?= $installUrl ?>" class="hide-if-no-js page-title-action"><?= __('Add New', 'mobili') ?></a>

    <hr class="wp-header-end">
    <?php do_action('mobili_admin_themes_before_content'); ?>
    <br>
    <?php if (empty($templates)) {
        echo apply_filters('mobili_admin_themes_not_found_alert', sprintf('<p class="no-themes">%s</p>', __('No mobile themes found.', 'mobili')));
    } else { ?>
        <div class="theme-browser rendered">
            <div class="themes wp-clearfix">
                <?php
                foreach ($themes as $theme) :
                    $aria_action = $theme['id'] . '-action';
                    $aria_name = $theme['id'] . '-name';

                    $active_class = '';
                    if ($theme['active']) {
                        $active_class = ' active';
                    }
                    ?>
                    <div class="theme<?php echo $active_class; ?>" data-slug="<?php echo $theme['id']; ?>">
                        <?php if (!empty($theme['screenshot'][0])) { ?>
                            <div class="theme-screenshot">
                                <img src="<?php echo esc_attr($theme['screenshot'][0]); ?>" alt=""/>
                            </div>
                        <?php } else { ?>
                            <div class="theme-screenshot blank"></div>
                        <?php } ?>

                        <?php if ($theme['hasUpdate']) : ?>
                            <?php if ($theme['updateResponse']['compatibleWP'] && $theme['updateResponse']['compatiblePHP']) : ?>
                                <div class="update-message notice inline notice-warning notice-alt">
                                    <p>
                                        <?php _e('New version available.'); ?>
                                        <a class="button-link"
                                           href="<?php echo Update::getUpdateThemeAdminUrl($theme['id']); ?>"><?php _e('Update now'); ?></a>
                                    </p>
                                </div>
                            <?php else : ?>
                                <div class="update-message notice inline notice-error notice-alt"><p>
                                        <?php
                                        if (!$theme['updateResponse']['compatibleWP'] && !$theme['updateResponse']['compatiblePHP']) {
                                            printf(
                                            /* translators: %s: Theme name. */
                                                __('There is a new version of %s available, but it doesn&#8217;t work with your versions of WordPress and PHP.'),
                                                $theme['name']
                                            );
                                            if (current_user_can('update_core') && current_user_can('update_php')) {
                                                printf(
                                                /* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
                                                    ' ' . __('<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.'),
                                                    self_admin_url('update-core.php'),
                                                    esc_url(wp_get_update_php_url())
                                                );
                                                wp_update_php_annotation('</p><p><em>', '</em>');
                                            } elseif (current_user_can('update_core')) {
                                                printf(
                                                /* translators: %s: URL to WordPress Updates screen. */
                                                    ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                                    self_admin_url('update-core.php')
                                                );
                                            } elseif (current_user_can('update_php')) {
                                                printf(
                                                /* translators: %s: URL to Update PHP page. */
                                                    ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                                    esc_url(wp_get_update_php_url())
                                                );
                                                wp_update_php_annotation('</p><p><em>', '</em>');
                                            }
                                        } elseif (!$theme['updateResponse']['compatibleWP']) {
                                            printf(
                                            /* translators: %s: Theme name. */
                                                __('There is a new version of %s available, but it doesn&#8217;t work with your version of WordPress.'),
                                                $theme['name']
                                            );
                                            if (current_user_can('update_core')) {
                                                printf(
                                                /* translators: %s: URL to WordPress Updates screen. */
                                                    ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                                    self_admin_url('update-core.php')
                                                );
                                            }
                                        } elseif (!$theme['updateResponse']['compatiblePHP']) {
                                            printf(
                                            /* translators: %s: Theme name. */
                                                __('There is a new version of %s available, but it doesn&#8217;t work with your version of PHP.'),
                                                $theme['name']
                                            );
                                            if (current_user_can('update_php')) {
                                                printf(
                                                /* translators: %s: URL to Update PHP page. */
                                                    ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                                    esc_url(wp_get_update_php_url())
                                                );
                                                wp_update_php_annotation('</p><p><em>', '</em>');
                                            }
                                        }
                                        ?>
                                    </p></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php
                        if (!$theme['compatibleWP'] || !$theme['compatiblePHP']) {
                            echo '<div class="notice inline notice-error notice-alt"><p>';
                            if (!$theme['compatibleWP'] && !$theme['compatiblePHP']) {
                                _e('This theme doesn&#8217;t work with your versions of WordPress and PHP.');
                                if (current_user_can('update_core') && current_user_can('update_php')) {
                                    printf(
                                    /* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
                                        ' ' . __('<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.'),
                                        self_admin_url('update-core.php'),
                                        esc_url(wp_get_update_php_url())
                                    );
                                    wp_update_php_annotation('</p><p><em>', '</em>');
                                } elseif (current_user_can('update_core')) {
                                    printf(
                                    /* translators: %s: URL to WordPress Updates screen. */
                                        ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                        self_admin_url('update-core.php')
                                    );
                                } elseif (current_user_can('update_php')) {
                                    printf(
                                    /* translators: %s: URL to Update PHP page. */
                                        ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                        esc_url(wp_get_update_php_url())
                                    );
                                    wp_update_php_annotation('</p><p><em>', '</em>');
                                }
                            } elseif (!$theme['compatibleWP']) {
                                _e('This theme doesn&#8217;t work with your version of WordPress.');
                                if (current_user_can('update_core')) {
                                    printf(
                                    /* translators: %s: URL to WordPress Updates screen. */
                                        ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                        self_admin_url('update-core.php')
                                    );
                                }
                            } elseif (!$theme['compatiblePHP']) {
                                _e('This theme doesn&#8217;t work with your version of PHP.');
                                if (current_user_can('update_php')) {
                                    printf(
                                    /* translators: %s: URL to Update PHP page. */
                                        ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                        esc_url(wp_get_update_php_url())
                                    );
                                    wp_update_php_annotation('</p><p><em>', '</em>');
                                }
                            }
                            echo '</p></div>';
                        }
                        ?>

                        <?php
                        /* translators: %s: Theme name. */
                        $details_aria_label = sprintf(_x('View Theme Details for %s', 'theme'), $theme['name']);
                        ?>
                        <button type="button" aria-label="<?php echo esc_attr($details_aria_label); ?>"
                                class="more-details"
                                id="<?php echo esc_attr($aria_action); ?>"><?php _e('Theme Details'); ?></button>
                        <div class="theme-author">
                            <?php
                            /* translators: %s: Theme author name. */
                            printf(__('By %s'), $theme['author']);
                            ?>
                        </div>

                        <div class="theme-id-container">
                            <?php if ($theme['active']) { ?>
                                <h2 class="theme-name" id="<?php echo esc_attr($aria_name); ?>">
                                    <span><?php _ex('Active:', 'theme'); ?></span> <?php echo $theme['name']; ?>
                                </h2>
                            <?php } else { ?>
                                <h2 class="theme-name"
                                    id="<?php echo esc_attr($aria_name); ?>"><?php echo $theme['name']; ?></h2>
                            <?php } ?>

                            <div class="theme-actions">
                                <?php if ($theme['active']) { ?>
                                    <?php
                                    if ($theme['actions']['customize'] && current_user_can('edit_theme_options') && current_user_can('customize')) {
                                        /* translators: %s: Theme name. */
                                        $customize_aria_label = sprintf(_x('Customize %s', 'theme'), $theme['name']);
                                        ?>
                                        <a aria-label="<?php echo esc_attr($customize_aria_label); ?>"
                                           class="button button-primary customize load-customize hide-if-no-customize"
                                           href="<?php echo $theme['actions']['customize']; ?>"><?php _e('Customize'); ?></a>
                                    <?php } ?>
                                <?php } elseif ($theme['compatibleWP'] && $theme['compatiblePHP']) { ?>
                                    <?php
                                    /* translators: %s: Theme name. */
                                    $aria_label = sprintf(_x('Activate %s', 'theme'), '{{ data.name }}');
                                    ?>
                                    <a class="button activate" href="<?php echo $theme['actions']['activate']; ?>"
                                       aria-label="<?php echo esc_attr($aria_label); ?>"><?php _e('Activate'); ?></a>
                                    <?php
                                    if (!$theme['blockTheme'] && current_user_can('edit_theme_options') && current_user_can('customize')) {
                                        /* translators: %s: Theme name. */
                                        $live_preview_aria_label = sprintf(_x('Live Preview %s', 'theme'), '{{ data.name }}');
                                        ?>
                                        <a aria-label="<?php echo esc_attr($live_preview_aria_label); ?>"
                                           class="button button-primary load-customize hide-if-no-customize"
                                           href="<?php echo $theme['actions']['customize']; ?>"><?php _e('Live Preview'); ?></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php
                                    /* translators: %s: Theme name. */
                                    $aria_label = sprintf(_x('Cannot Activate %s', 'theme'), '{{ data.name }}');
                                    ?>
                                    <a class="button disabled"
                                       aria-label="<?php echo esc_attr($aria_label); ?>"><?php _ex('Cannot Activate', 'theme'); ?></a>
                                    <?php if (!$theme['blockTheme'] && current_user_can('edit_theme_options') && current_user_can('customize')) { ?>
                                        <a class="button button-primary hide-if-no-customize disabled"><?php _e('Live Preview'); ?></a>
                                    <?php } ?>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="theme add-new-theme"><a href="<?= $installUrl ?>">
                        <div class="theme-screenshot"><span></span></div>
                        <h2 class="theme-name"><?php _e('Add New Theme', 'mobili'); ?></h2></a></div>
            </div>
        </div>
        <div class="theme-overlay" tabindex="0" role="dialog" aria-label="<?php _e('Theme Details'); ?>"></div>
    <?php } ?>
    <?php do_action('mobili_admin_themes_after_content'); ?>
</div>
<?php do_action('mobili_admin_themes_after_page'); ?>

<script id="tmpl-theme-single" type="text/template">
    <div class="theme-backdrop"></div>
    <div class="theme-wrap wp-clearfix" role="document">
        <div class="theme-header">
            <button class="close dashicons dashicons-no"><span
                        class="screen-reader-text"><?php _e('Close details dialog'); ?></span></button>
        </div>
        <div class="theme-about wp-clearfix">
            <div class="theme-screenshots">
                <# if ( data.screenshot[0] ) { #>
                <div class="screenshot"><img src="{{ data.screenshot[0] }}" alt=""/></div>
                <# } else { #>
                <div class="screenshot blank"></div>
                <# } #>
            </div>

            <div class="theme-info">
                <# if ( data.active ) { #>
                <span class="current-label"><?php _e('Current Theme'); ?></span>
                <# } #>
                <h2 class="theme-name">{{{ data.name }}}<span class="theme-version">
					<?php
                    /* translators: %s: Theme version. */
                    printf(__('Version: %s'), '{{ data.version }}');
                    ?>
				</span></h2>
                <p class="theme-author">
                    <?php
                    /* translators: %s: Theme author link. */
                    printf(__('By %s'), '{{{ data.authorAndUri }}}');
                    ?>
                </p>

                <# if ( ! data.compatibleWP || ! data.compatiblePHP ) { #>
                <div class="notice notice-error notice-alt notice-large"><p>
                        <# if ( ! data.compatibleWP && ! data.compatiblePHP ) { #>
                        <?php
                        _e('This theme doesn&#8217;t work with your versions of WordPress and PHP.');
                        if (current_user_can('update_core') && current_user_can('update_php')) {
                            printf(
                            /* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
                                ' ' . __('<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.'),
                                self_admin_url('update-core.php'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        } elseif (current_user_can('update_core')) {
                            printf(
                            /* translators: %s: URL to WordPress Updates screen. */
                                ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                self_admin_url('update-core.php')
                            );
                        } elseif (current_user_can('update_php')) {
                            printf(
                            /* translators: %s: URL to Update PHP page. */
                                ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        }
                        ?>
                        <# } else if ( ! data.compatibleWP ) { #>
                        <?php
                        _e('This theme doesn&#8217;t work with your version of WordPress.');
                        if (current_user_can('update_core')) {
                            printf(
                            /* translators: %s: URL to WordPress Updates screen. */
                                ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                self_admin_url('update-core.php')
                            );
                        }
                        ?>
                        <# } else if ( ! data.compatiblePHP ) { #>
                        <?php
                        _e('This theme doesn&#8217;t work with your version of PHP.');
                        if (current_user_can('update_php')) {
                            printf(
                            /* translators: %s: URL to Update PHP page. */
                                ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        }
                        ?>
                        <# } #>
                    </p></div>
                <# } #>

                <# if ( data.hasUpdate ) { #>
                <# if ( data.updateResponse.compatibleWP && data.updateResponse.compatiblePHP ) { #>
                <div class="notice notice-warning notice-alt notice-large">
                    <h3 class="notice-title"><?php _e('Update Available'); ?></h3>
                    {{{ data.update }}}
                </div>
                <# } else { #>
                <div class="notice notice-error notice-alt notice-large">
                    <h3 class="notice-title"><?php _e('Update Incompatible'); ?></h3>
                    <p>
                        <# if ( ! data.updateResponse.compatibleWP && ! data.updateResponse.compatiblePHP ) { #>
                        <?php
                        printf(
                        /* translators: %s: Theme name. */
                            __('There is a new version of %s available, but it doesn&#8217;t work with your versions of WordPress and PHP.'),
                            '{{{ data.name }}}'
                        );
                        if (current_user_can('update_core') && current_user_can('update_php')) {
                            printf(
                            /* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
                                ' ' . __('<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.'),
                                self_admin_url('update-core.php'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        } elseif (current_user_can('update_core')) {
                            printf(
                            /* translators: %s: URL to WordPress Updates screen. */
                                ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                self_admin_url('update-core.php')
                            );
                        } elseif (current_user_can('update_php')) {
                            printf(
                            /* translators: %s: URL to Update PHP page. */
                                ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        }
                        ?>
                        <# } else if ( ! data.updateResponse.compatibleWP ) { #>
                        <?php
                        printf(
                        /* translators: %s: Theme name. */
                            __('There is a new version of %s available, but it doesn&#8217;t work with your version of WordPress.'),
                            '{{{ data.name }}}'
                        );
                        if (current_user_can('update_core')) {
                            printf(
                            /* translators: %s: URL to WordPress Updates screen. */
                                ' ' . __('<a href="%s">Please update WordPress</a>.'),
                                self_admin_url('update-core.php')
                            );
                        }
                        ?>
                        <# } else if ( ! data.updateResponse.compatiblePHP ) { #>
                        <?php
                        printf(
                        /* translators: %s: Theme name. */
                            __('There is a new version of %s available, but it doesn&#8217;t work with your version of PHP.'),
                            '{{{ data.name }}}'
                        );
                        if (current_user_can('update_php')) {
                            printf(
                            /* translators: %s: URL to Update PHP page. */
                                ' ' . __('<a href="%s">Learn more about updating PHP</a>.'),
                                esc_url(wp_get_update_php_url())
                            );
                            wp_update_php_annotation('</p><p><em>', '</em>');
                        }
                        ?>
                        <# } #>
                    </p>
                </div>
                <# } #>
                <# } #>

                <# if ( data.actions.autoupdate ) { #>
                <?php echo wp_theme_auto_update_setting_template(); ?>
                <# } #>

                <p class="theme-description">{{{ data.description }}}</p>

                <# if ( data.parent ) { #>
                <p class="parent-theme">
                    <?php
                    /* translators: %s: Theme name. */
                    printf(__('This is a child theme of %s.'), '<strong>{{{ data.parent }}}</strong>');
                    ?>
                </p>
                <# } #>

                <# if ( data.tags ) { #>
                <p class="theme-tags"><span><?php _e('Tags:'); ?></span> {{{ data.tags }}}</p>
                <# } #>
            </div>
        </div>

        <div class="theme-actions">
            <div class="active-theme">
                <a href="{{{ data.actions.customize }}}"
                   class="button button-primary customize load-customize hide-if-no-customize"><?php _e('Customize'); ?></a>

                <# if ( data.active ) { #>
                <a href="{{{ data.actions.deactivate }}}"
                   class="button button-error"><?php _e('Deactivate','mobili'); ?></a>
                <# } #>

            </div>
            <div class="inactive-theme">
                <# if ( data.compatibleWP && data.compatiblePHP ) { #>
                <?php
                /* translators: %s: Theme name. */
                $aria_label = sprintf(_x('Activate %s', 'theme'), '{{ data.name }}');
                ?>
                <# if ( data.actions.activate ) { #>
                <a href="{{{ data.actions.activate }}}" class="button activate"
                   aria-label="<?php echo esc_attr($aria_label); ?>"><?php _e('Activate'); ?></a>
                <# } #>
                <# if ( ! data.blockTheme ) { #>
                <a href="{{{ data.actions.customize }}}"
                   class="button button-primary load-customize hide-if-no-customize"><?php _e('Live Preview'); ?></a>
                <# } #>
                <# } else { #>
                <?php
                /* translators: %s: Theme name. */
                $aria_label = sprintf(_x('Cannot Activate %s', 'theme'), '{{ data.name }}');
                ?>
                <# if ( data.actions.activate ) { #>
                <a class="button disabled"
                   aria-label="<?php echo esc_attr($aria_label); ?>"><?php _ex('Cannot Activate', 'theme'); ?></a>
                <# } #>
                <# if ( ! data.blockTheme ) { #>
                <a class="button button-primary hide-if-no-customize disabled"><?php _e('Live Preview'); ?></a>
                <# } #>
                <# } #>
            </div>

            <# if ( ! data.active && data.actions['delete'] ) { #>
            <?php
            /* translators: %s: Theme name. */
            $aria_label = sprintf(_x('Delete %s', 'theme'), '{{ data.name }}');
            ?>
            <a href="{{{ data.actions['delete'] }}}" class="button delete-theme"
               aria-label="<?php echo esc_attr($aria_label); ?>"><?php _e('Delete'); ?></a>
            <# } #>
        </div>
    </div>
</script>

<script>
    let mobiliThemesOBJ = <?php echo json_encode($themes); ?>;
</script>