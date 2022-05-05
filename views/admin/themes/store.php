<?php
/**
 * @var string $sort
 * @var string $store_url
 * @var string $upload_nonce
 * @var string $upload_url
 */
?>
<div class="wrap mobili-store-wrap">
    <h1 class="wp-heading-inline"><?php _e('Add Mobile Themes', 'mobili'); ?></h1>
    <button type="button" class="upload-view-toggle page-title-action hide-if-no-js" id="upload-form-toggle" aria-expanded="false">
		<?php _e('Upload Theme'); ?>
    </button>
    <hr class="wp-header-end">
    <div class="error hide-if-js">
        <p><?php _e('The Theme Installer screen requires JavaScript.'); ?></p>
    </div>
	<?php mi_get_core_view(
		'admin/themes/upload.php', [
			                         'upload_url' => $upload_url,
			                         'upload_nonce' => $upload_nonce
		                         ]
	); ?>
    <div class="wp-filter hide-if-no-js">
        <div class="filter-count">
            <span class="count theme-count"><?php _e('Loading','mobili'); ?></span>
        </div>
        <ul class="filter-links">
            <li>
                <a href="<?php echo add_query_arg(
					['sort' => 'popular'], $store_url
				); ?>" data-slug="popular" class="<?php echo $sort === 'popular' ? 'current' : ''; ?>">
					<?php _e('Popular'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg(
					['sort' => 'latest'], $store_url
				); ?>" data-slug="latest" class="<?php echo $sort === 'latest' ? 'current' : ''; ?>">
					<?php _e('Latest'); ?>
                </a>
            </li>
            <li>
                <a href="<?php echo add_query_arg(
					['sort' => 'favorites'], $store_url
				); ?>" data-slug="favorites" class="<?php echo $sort === 'favorites' ? 'current' : ''; ?>">
					<?php _e('Favorites'); ?>
                </a>
            </li>
        </ul>

        <form class="search-form">
            <label class="screen-reader-text" for="wp-filter-search-input">
				<?php _e('Search Mobile Themes','mobili'); ?>
            </label>
            <input type="hidden" name="page" value="install-mobile-theme">
            <input type="hidden" name="sort" value="<?php echo $sort; ?>">
            <input placeholder="<?php _e(
				'Search themes...'
			); ?>" type="search" name="search" value="<?php esc_attr_e($_GET['search'] ?? ''); ?>" id="wp-filter-search-input" class="wp-filter-search">
        </form>
    </div>
    <h2 class="screen-reader-text hide-if-no-js"><?php _e('Mobile themes list', 'mobili'); ?></h2>
    <div class="theme-browser content-filterable rendered">
        <div class="themes wp-clearfix">
        </div>
    </div>
    <div class="theme-install-overlay wp-full-overlay expanded"></div>
    <p class="no-themes"><?php _e('No themes found. Try a different search.'); ?></p>
    <span class="spinner"></span>
    <div class="page-form-data">
        <input type="hidden" name="pageNum" value="1">
    </div>
</div>

<script id="tmpl-theme" type="text/template">
        <# if ( data.screenshot_url ) { #>
        <div class="theme-screenshot">
            <img src="{{ data.screenshot_url }}" alt=""/>
        </div>
        <# } else { #>
        <div class="theme-screenshot blank"></div>
        <# } #>

        <# if ( data.installed ) { #>
        <div class="notice notice-success notice-alt"><p><?php _ex('Installed', 'theme'); ?></p></div>
        <# } #>

        <# if ( ! data.compatible_wp || ! data.compatible_php || ! data.compatible_mi ) { #>
        <div class="notice notice-error notice-alt"><p>
                <# if ( ! data.compatible_wp && ! data.compatible_php ) { #>
				<?php
				_e('This theme doesn&#8217;t work with your versions of WordPress and PHP.');
				if ( current_user_can('update_core') && current_user_can('update_php') ) {
					printf(
					/* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */ ' ' . __(
						                                                                                  '<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.'
					                                                                                  ), self_admin_url(
						                                                                                  'update-core.php'
					                                                                                  ), esc_url(
						                                                                                  wp_get_update_php_url(
						                                                                                  )
					                                                                                  )
					);
					wp_update_php_annotation('</p><p><em>', '</em>');
				} elseif ( current_user_can('update_core') ) {
					printf(
					/* translators: %s: URL to WordPress Updates screen. */
						' ' . __('<a href="%s">Please update WordPress</a>.'), self_admin_url('update-core.php')
					);
				} elseif ( current_user_can('update_php') ) {
					printf(
					/* translators: %s: URL to Update PHP page. */
						' ' . __('<a href="%s">Learn more about updating PHP</a>.'), esc_url(wp_get_update_php_url())
					);
					wp_update_php_annotation('</p><p><em>', '</em>');
				}
				?>
                <# } else if ( ! data.compatible_wp ) { #>
				<?php
				_e('This theme doesn&#8217;t work with your version of WordPress.');
				if ( current_user_can('update_core') ) {
					printf(
					/* translators: %s: URL to WordPress Updates screen. */
						' ' . __('<a href="%s">Please update WordPress</a>.'), self_admin_url('update-core.php')
					);
				}
				?>
                <# } else if ( ! data.compatible_php ) { #>
				<?php
				_e('This theme doesn&#8217;t work with your version of PHP.');
				if ( current_user_can('update_php') ) {
					printf(
					/* translators: %s: URL to Update PHP page. */
						' ' . __('<a href="%s">Learn more about updating PHP</a>.'), esc_url(wp_get_update_php_url())
					);
					wp_update_php_annotation('</p><p><em>', '</em>');
				}
				?>
                <# } else if ( ! data.compatible_mi ) { #>
				<?php
				_e('This theme doesn&#8217;t work with your version of Mobili plugin.','mobili');
				?>
                <# } #>
            </p></div>
        <# } #>

        <# if ( data.slug ) { #>
        <span class="more-details"><?php _ex('Details &amp; Preview', 'theme'); ?></span>
        <# } #>
        <div class="theme-author">
			<?php
			/* translators: %s: Theme author name. */
			printf(__('By %s'), '{{ data.author }}');
			?>
        </div>

        <div class="theme-id-container">
            <h3 class="theme-name">{{ data.name }}</h3>

            <div class="theme-actions">
                <# if ( data.installed ) { #>
                <# if ( data.compatible_wp && data.compatible_php ) { #>
				<?php
				/* translators: %s: Theme name. */
				$aria_label = sprintf(_x('Activate %s', 'theme'), '{{ data.name }}');
				?>
                <# if ( data.activate_url ) { #>
                <# if ( ! data.active ) { #>
                <a class="button button-primary activate" href="{{ data.activate_url }}" aria-label="<?php echo esc_attr(
					$aria_label
				); ?>"><?php _e('Activate'); ?></a>
                <# } else { #>
                <button class="button button-primary disabled"><?php _ex('Activated', 'theme'); ?></button>
                <# } #>
                <# } #>
                <# if ( data.customize_url ) { #>
                <# if ( ! data.active ) { #>
                <a class="button load-customize" href="{{ data.customize_url }}"><?php _e('Live Preview'); ?></a>
                <# } else { #>
                <a class="button load-customize" href="{{ data.customize_url }}"><?php _e('Customize'); ?></a>
                <# } #>
                <# } else { #>
                <button class="button preview install-theme-preview"><?php _e('Preview'); ?></button>
                <# } #>
                <# } else { #>
				<?php
				/* translators: %s: Theme name. */
				$aria_label = sprintf(_x('Cannot Activate %s', 'theme'), '{{ data.name }}');
				?>
                <# if ( data.activate_url ) { #>
                <a class="button button-primary disabled" aria-label="<?php echo esc_attr($aria_label); ?>"><?php _ex(
						'Cannot Activate', 'theme'
					); ?></a>
                <# } #>
                <# if ( data.customize_url ) { #>
                <a class="button disabled"><?php _e('Live Preview'); ?></a>
                <# } else { #>
                <button class="button disabled"><?php _e('Preview'); ?></button>
                <# } #>
                <# } #>
                <# } else { #>
                <# if ( data.compatible_wp && data.compatible_php && data.compatible_mi ) { #>
				<?php
				/* translators: %s: Theme name. */
				$aria_label = sprintf(_x('Install %s', 'theme'), '{{ data.name }}');
				?>

                <# if ( !data.buy ) { #>
                <a class="button button-primary theme-install" data-name="{{ data.name }}" data-slug="{{ data.id }}" href="{{ data.install_url }}" aria-label="<?php echo esc_attr(
					$aria_label
				); ?>"><?php _e('Install'); ?></a>
                <# } else { #>
                <a class="button button-primary" target="_blank" href="{{ data.buy }}" aria-label="<?php echo esc_attr(
                    $aria_label
                ); ?>"><?php _e('Buy'); ?></a>
                <# } #>

                <button class="button preview install-theme-preview"><?php _e('Preview'); ?></button>
                <# } else { #>
				<?php
				/* translators: %s: Theme name. */
				$aria_label = sprintf(_x('Cannot Install %s', 'theme'), '{{ data.name }}');
				?>
                <a class="button button-primary disabled" data-name="{{ data.name }}" aria-label="<?php echo esc_attr(
					$aria_label
				); ?>"><?php _ex('Cannot Install', 'theme'); ?></a>
                <button class="button disabled"><?php _e('Preview'); ?></button>
                <# } #>
                <# } #>
            </div>
        </div>
</script>


<script id="tmpl-theme-preview" type="text/template">
    <div class="wp-full-overlay-sidebar">
        <div class="wp-full-overlay-header">
            <button class="close-full-overlay"><span class="screen-reader-text"><?php _e( 'Close' ); ?></span></button>
            <# if ( data.installed ) { #>
            <# if ( data.compatible_wp && data.compatible_php && data.compatible_mi ) { #>
			<?php
			/* translators: %s: Theme name. */
			$aria_label = sprintf( _x( 'Activate %s', 'theme' ), '{{ data.name }}' );
			?>
            <# if ( ! data.active ) { #>
            <a class="button button-primary activate" href="{{ data.activate_url }}" aria-label="<?php echo esc_attr( $aria_label ); ?>"><?php _e( 'Activate' ); ?></a>
            <# } else { #>
            <button class="button button-primary disabled"><?php _ex( 'Activated', 'theme' ); ?></button>
            <# } #>
            <# } else { #>
            <a class="button button-primary disabled" ><?php _ex( 'Cannot Activate', 'theme' ); ?></a>
            <# } #>
            <# } else { #>
            <# if ( data.compatible_wp && data.compatible_php && data.compatible_mi ) { #>
            <# if ( !data.buy ) { #>
            <a href="{{ data.install_url }}" class="button button-primary theme-install" data-name="{{ data.name }}" data-slug="{{ data.id }}"><?php _e( 'Install' ); ?></a>
            <# } else { #>
            <a class="button button-primary" target="_blank" href="{{ data.buy }}" aria-label="<?php echo esc_attr(
                $aria_label
            ); ?>"><?php _e('Buy'); ?></a>
            <# } #>

            <# } else { #>
            <a class="button button-primary disabled" ><?php _ex( 'Cannot Install', 'theme' ); ?></a>
            <# } #>
            <# } #>
        </div>
        <div class="wp-full-overlay-sidebar-content">
            <div class="install-theme-info">
                <h3 class="theme-name">{{ data.name }}</h3>
                <span class="theme-by">
						<?php
						/* translators: %s: Theme author name. */
						printf( __( 'By %s' ), '{{ data.author }}' );
						?>
					</span>

                <img class="theme-screenshot" src="{{ data.screenshot_url }}" alt="" />

                <div class="theme-details">
                    <# if ( data.rating ) { #>
                    <div class="theme-rating">
                        {{{ data.stars }}}
                        <a class="num-ratings" href="{{ data.reviews_url }}">
							<?php
							/* translators: %s: Number of ratings. */
							printf( __( '(%s ratings)' ), '{{ data.num_ratings }}' );
							?>
                        </a>
                    </div>
                    <# } else { #>
                    <span class="no-rating"><?php _e( 'This theme has not been rated yet.' ); ?></span>
                    <# } #>

                    <div class="theme-version">
						<?php
						/* translators: %s: Theme version. */
						printf( __( 'Version: %s' ), '{{ data.version }}' );
						?>
                    </div>

                    <# if ( ! data.compatible_wp || ! data.compatible_php || !data.compatible_mi ) { #>
                    <div class="notice notice-error notice-alt notice-large"><p>
                            <# if ( ! data.compatible_wp && ! data.compatible_php ) { #>
							<?php
							_e( 'This theme doesn&#8217;t work with your versions of WordPress and PHP.' );
							if ( current_user_can( 'update_core' ) && current_user_can( 'update_php' ) ) {
								printf(
								/* translators: 1: URL to WordPress Updates screen, 2: URL to Update PHP page. */
									' ' . __( '<a href="%1$s">Please update WordPress</a>, and then <a href="%2$s">learn more about updating PHP</a>.' ),
									self_admin_url( 'update-core.php' ),
									esc_url( wp_get_update_php_url() )
								);
								wp_update_php_annotation( '</p><p><em>', '</em>' );
							} elseif ( current_user_can( 'update_core' ) ) {
								printf(
								/* translators: %s: URL to WordPress Updates screen. */
									' ' . __( '<a href="%s">Please update WordPress</a>.' ),
									self_admin_url( 'update-core.php' )
								);
							} elseif ( current_user_can( 'update_php' ) ) {
								printf(
								/* translators: %s: URL to Update PHP page. */
									' ' . __( '<a href="%s">Learn more about updating PHP</a>.' ),
									esc_url( wp_get_update_php_url() )
								);
								wp_update_php_annotation( '</p><p><em>', '</em>' );
							}
							?>
                            <# } else if ( ! data.compatible_wp ) { #>
							<?php
							_e( 'This theme doesn&#8217;t work with your version of WordPress.' );
							if ( current_user_can( 'update_core' ) ) {
								printf(
								/* translators: %s: URL to WordPress Updates screen. */
									' ' . __( '<a href="%s">Please update WordPress</a>.' ),
									self_admin_url( 'update-core.php' )
								);
							}
							?>
                            <# } else if ( ! data.compatible_php ) { #>
							<?php
							_e( 'This theme doesn&#8217;t work with your version of PHP.' );
							if ( current_user_can( 'update_php' ) ) {
								printf(
								/* translators: %s: URL to Update PHP page. */
									' ' . __( '<a href="%s">Learn more about updating PHP</a>.' ),
									esc_url( wp_get_update_php_url() )
								);
								wp_update_php_annotation( '</p><p><em>', '</em>' );
							}
							?>
                            <# } else if ( ! data.compatible_mi ) { #>
		                    <?php
		                    _e('This theme doesn&#8217;t work with your version of Mobili plugin.','mobili');
		                    ?>
                            <# } #>
                        </p></div>
                    <# } #>

                    <div class="theme-description">{{{ data.description }}}</div>
                </div>
            </div>
        </div>
        <div class="wp-full-overlay-footer">
            <button type="button" class="collapse-sidebar button" aria-expanded="true" aria-label="<?php esc_attr_e( 'Collapse Sidebar' ); ?>">
                <span class="collapse-sidebar-arrow"></span>
                <span class="collapse-sidebar-label"><?php _e( 'Collapse' ); ?></span>
            </button>
        </div>
    </div>
    <div class="wp-full-overlay-main">
        <div class="phone-mockup" style="background-color: <?php echo get_option('mobili-pwa_color-theme','#eee') ; ?>">
            <div class="height-fill"></div>
            <iframe src="{{ data.preview_url }}" title="<?php esc_attr_e( 'Preview' ); ?>"></iframe>
        </div>
    </div>
</script>