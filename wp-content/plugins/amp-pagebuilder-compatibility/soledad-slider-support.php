<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( ! class_exists( 'Amp_Soledad_VC_Shortcodes' ) ) {
	class Amp_Soledad_VC_Shortcodes {
		/**
		 * Add shortcodes
		 */
		public static $slider_columns = 4;
		public static function init() {
			$shortcodes = array(
				'latest_posts',
				'featured_cat',
				'popular_posts',
				'soledad_sidebar',
				'soledad_featured_boxes'
			);

			foreach ( $shortcodes as $shortcode ) {
				add_shortcode( $shortcode, array( __CLASS__, $shortcode ) );
			}
		}

		/**
		 * Retrieve HTML markup of latest_posts shortcode
		 *
		 * @param array  $atts
		 * @param string $content
		 *
		 * @return string
		 */
		public static function latest_posts( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'style'               => 'standard',
				'heading'             => '',
				'heading_title_style' => '',
				'heading_title_link'  => '',
				'heading_title_align' => '',
				'hide_block_heading'  => '',
				'number'              => '10',
				'paging'              => 'numbers',
				'morenum'             => '6',
				'exclude'             => '',
				'build_query'         => '',
				'elementor_query'     => '',
				'wpblock'             => '',
				'penci_mixed_style'   => 's1',
				'post_alignment'      => '',

				'block_title_color'     => '',
				'block_title_hcolor'    => '',
				'btitle_bcolor'         => '',
				'btitle_outer_bcolor'   => '',
				'btitle_style5_bcolor'  => '',
				'btitle_style78_bcolor' => '',
				'btitle_bgcolor'        => '',
				'btitle_outer_bgcolor'  => '',
				'btitle_style9_bgimg'   => '',
				'use_btitle_typo'       => '',
				'btitle_typo'           => '',
				'btitle_fsize'          => '',
				'block_title_offupper'  => '',
				'block_title_marginbt'  => '',
				'btitle_shapes_color'   => '',

				'pborder_color'   => '',
				'ptitle_color'    => '',
				'ptitle_hcolor'   => '',
				'use_ptitle_typo' => '',
				'ptitle_typo'     => '',
				'ptitle_fsize'    => '',

				'pmeta_color'        => '',
				'pmeta_hcolor'       => '',
				'pauthor_color'      => '',
				'pmeta_border_color' => '',
				'use_pmeta_typo'     => '',
				'pmeta_fsize'        => '',
				'pmeta_typo'         => '',

				'pexcrept_color'    => '',
				'use_pexcrept_typo' => '',
				'pexcrept_fsize'    => '',
				'pexcrept_typo'     => '',

				'pcat_color'    => '',
				'pcat_hcolor'   => '',
				'use_pcat_typo' => '',
				'pcat_fsize'    => '',
				'pcat_typo'     => '',

				'prmore_color'    => '',
				'prmore_hcolor'   => '',
				'use_prmore_typo' => '',
				'prmore_fsize'    => '',
				'prmore_typo'     => '',
				'pag_icon_fsize'  => '',

				'pshare_color'        => '',
				'pshare_hcolor'       => '',
				'pshare_border_color' => '',

				'pagination_icon'         => '',
				'pagination_size'         => '',
				'pagination_color'        => '',
				'pagination_bordercolor'  => '',
				'pagination_bgcolor'      => '',
				'pagination_hcolor'       => '',
				'pagination_hbordercolor' => '',
				'pagination_hbgcolor'     => '',

				'standard_meta_overlay'   => '',
				'standard_thumbnail'      => '',
				'std_dis_at_gallery'      => '',
				'standard_thumb_crop'     => '',
				'standard_share_box'      => '',
				'standard_cat'            => '',
				'standard_author'         => '',
				'standard_date'           => '',
				'standard_comment'        => '',
				'standard_viewscount'     => '',
				'standard_remove_line'    => '',
				'standard_auto_excerpt'   => '',
				'standard_effect_button'  => '',
				'std_continue_btn'        => '',
				'grid_icon_format'        => '',
				'grid_meta_overlay'       => '',
				'grid_uppercase_cat'      => '',
				'grid_lightbox_video'     => '',
				'grid_nocrop_list'        => '',
				'grid_share_box'          => '',
				'grid_cat'                => '',
				'grid_author'             => '',
				'grid_date'               => '',
				'grid_comment'            => '',
				'grid_viewscount'         => '',
				'grid_remove_line'        => '',
				'grid_remove_excerpt'     => '',
				'grid_add_readmore'       => '',
				'grid_remove_arrow'       => '',
				'grid_readmore_button'    => '',
				'grid_readmore_align'     => '',
				'grid_excerpt_length'     => '',
				'standard_excerpt_length' => '',
				'std_continue_align'      => '',
				'std_excerpt_align'       => '',
				'grid_excerpt_align'      => '',

				'order_columns'           => '',
				'order_columns_tablet'    => '',
				'order_columns_mobile'    => '',
				'order_column_gap'        => '',
				'order_row_gap'           => '',

				// Big Post
				'bptitle_color'         => '',
				'bptitle_hcolor'        => '',
				'bptitle_fsize'         => '',
				'bpmeta_color'          => '',
				'bpmeta_hcolor'         => '',
				'bpauthor_color'        => '',
				'bpmeta_border_color'   => '',
				'bpmeta_fsize'          => '',
				'bpcat_color'           => '',
				'bpcat_hcolor'          => '',
				'bpcat_fsize'           => '',
				'bpexcerpt_size'        => '',
				'bsocialshare_size'     => '',
				'standard_title_length' => '',
				'grid_title_length'     => '',
				'penci_featimg_size'    => '',
				'penci_featimg_ratio'   => '',

			), $atts, 'latest_posts' );

			$penci_mixed_style = 's1';

			extract( $atts );

			$standard_title_length = $standard_title_length ? $standard_title_length : '';
			$grid_title_length     = $grid_title_length ? $grid_title_length : '';

			$return = '';

			$is_el_builder = false;

			if ( $atts['elementor_query'] ) {
				$args          = $atts['elementor_query'];
				$number        = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : 10;
				$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
				$is_el_builder = true;

			} elseif ( $atts['build_query'] ) {
				$args          = penci_build_args_query( $atts['build_query'] );
				$args['paged'] = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
				$number        = isset( $args['posts_per_page'] ) ? $args['posts_per_page'] : 10;
			} else {
				if ( ! isset( $number ) || ! is_numeric( $number ) ): $number = '10'; endif;
				if ( ! isset( $morenum ) || ! is_numeric( $morenum ) ): $morenum = '6'; endif;
				$paged = max( get_query_var( 'paged' ), get_query_var( 'page' ), 1 );
				$args  = array( 'post_type' => 'post', 'paged' => $paged, 'posts_per_page' => $number );
				if ( ! empty( $exclude ) ) {
					$exclude_cats      = str_replace( ' ', '', $exclude );
					$exclude_array     = explode( ',', $exclude_cats );
					$args['tax_query'] = array(
						array(
							'taxonomy' => 'category',
							'field'    => 'slug',
							'terms'    => $exclude_array,
							'operator' => 'NOT IN'
						)
					);
				}
			}

			$block_id = '';
			if( class_exists( 'Penci_Vc_Helper' ) ){
				$block_id  = Penci_Vc_Helper::get_unique_id_block( 'latest_posts' );
			}

			$data_pag_arg = htmlentities( json_encode( $args ), ENT_QUOTES, "UTF-8" );
			$query_custom = new WP_Query( $args );
			if ( $query_custom->have_posts() ) :
				ob_start();

				$class_wrap = 'penci-latest-posts-sc';
				$class_wrap .= ' penci-latest-posts-' . $style;
				$class_wrap .= $is_el_builder ? ' penci-latest-posts-el' : '';
				$class_wrap .= ' penci-el-mixed-' . $penci_mixed_style;

				if( ! in_array( $style, array( 'overlay','boxed-1', 'boxed-2','photography' ) ) ) {
					if( $atts['post_alignment'] ) {
						$class_wrap .= ' penci-latest-posts-' . esc_attr( $atts['post_alignment'] );
					}

					if( $atts['std_continue_align'] ) {
						$class_wrap .= ' penci-std-continue-' . esc_attr( $atts['std_continue_align'] );
					}
					if( $atts['std_excerpt_align'] ) {
						$class_wrap .= ' penci-std-excerpt-' . esc_attr( $atts['std_excerpt_align'] );
					}
					if( $atts['grid_excerpt_align'] ) {
						$class_wrap .= ' penci-grid-excerpt-' . esc_attr( $atts['grid_excerpt_align'] );
					}
				}
				if ( in_array( $style, array( 'grid', 'masonry' ) ) ) {
					if ( $atts['order_columns'] ) {

						$class_wrap .= ' penci-lposts-ctcol';
						$class_wrap .= ' pencisc-grid-' . esc_attr( $atts['order_columns'] );

						if ( $atts['order_columns_tablet'] ) {
							$class_wrap .= ' pencisc-grid-tablet-' . esc_attr( $atts['order_columns_tablet'] );
						}
						if ( $atts['order_columns_mobile'] ) {
							$class_wrap .= ' pencisc-grid-mobile-' . esc_attr( $atts['order_columns_mobile'] );
						}
					}

				}

				?>
				<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $class_wrap ); ?>">
				<?php if ( $heading && ! $atts['hide_block_heading'] ) : ?>
					<?php
					$heading_title = get_theme_mod( 'penci_featured_cat_style' ) ? get_theme_mod( 'penci_featured_cat_style' ) : 'style-1';
					$heading_align = get_theme_mod( 'penci_heading_latest_align' ) ? get_theme_mod( 'penci_heading_latest_align' ) : 'pcalign-center';

					if ( $atts['heading_title_style'] ) {
						$heading_title = $atts['heading_title_style'];
					}

					if ( $atts['heading_title_align'] ) {
						$heading_align = $atts['heading_title_align'];
					}

					?>
					<div class="penci-border-arrow penci-homepage-title penci-home-latest-posts <?php echo sanitize_text_field( $heading_title . ' ' . $heading_align ); ?>">
						<h3 class="inner-arrow">
							<?php
							if( $atts['heading_title_link'] ){
								echo '<a href="' . esc_url( $atts['heading_title_link'] ) . '" title="' . esc_attr( $heading ) . '">';
							}
							echo do_shortcode( $heading );
							if( $atts['heading_title_link'] ){
								echo '</a>';
							}
							?>
						</h3>
					</div>
				<?php endif; ?>

				<div class="penci-wrapper-posts-content">

					<?php if( in_array( $style, array( 'standard', 'classic', 'overlay' ) ) ): ?><div class="penci-wrapper-data"><?php endif; ?>
						<?php if ( in_array( $style, array( 'mixed', 'mixed-2', 'overlay-grid', 'overlay-list', 'photography', 'grid', 'grid-2', 'list', 'boxed-1', 'boxed-2', 'boxed-3', 'standard-grid', 'standard-grid-2', 'standard-list', 'standard-boxed-1', 'classic-grid', 'classic-grid-2', 'classic-list', 'classic-boxed-1', 'magazine-1', 'magazine-2' ) ) ) : ?><ul class="penci-wrapper-data penci-grid penci-shortcode-render"><?php endif; ?>
							<?php if ( in_array( $style, array( 'masonry', 'masonry-2' ) ) ) : ?><div class="penci-wrap-masonry"><div class="penci-wrapper-data masonry penci-masonry"><?php endif; ?>
									<?php /* The loop */
									while ( $query_custom->have_posts() ) : $query_custom->the_post();
										include( locate_template( 'template-parts/latest-posts-sc/content-' . $style . '.php' ) );
									endwhile;
									?>

									<?php if( in_array( $style, array( 'standard', 'classic', 'overlay' ) ) ): ?></div><?php endif; ?>
								<?php if ( in_array( $style, array( 'masonry', 'masonry-2' ) ) ) : ?></div></div><?php endif; ?>
					<?php if ( in_array( $style, array( 'mixed', 'mixed-2', 'overlay-grid', 'overlay-list', 'photography', 'grid', 'grid-2', 'list', 'boxed-1', 'boxed-2', 'boxed-3', 'standard-grid', 'standard-grid-2', 'standard-list', 'standard-boxed-1', 'classic-grid', 'classic-grid-2', 'classic-list', 'classic-boxed-1', 'magazine-1', 'magazine-2' ) ) ) : ?></ul><?php endif; ?>


					<?php
					if( $paging == 'loadmore' || $paging == 'scroll' ) {
						$button_class = 'penci-ajax-more penci-ajax-home penci-ajax-more-click';
						if( $paging == 'loadmore' ):
							wp_enqueue_script( 'penci_ajax_more_posts' );
							wp_localize_script( 'penci_ajax_more_posts', 'ajax_var_more', array(
									'url'     => admin_url( 'admin-ajax.php' ),
									'nonce'   => wp_create_nonce( 'ajax-nonce' )
								)
							);
						endif;
						if( $paging == 'scroll' ):
							$button_class = 'penci-ajax-more penci-ajax-home penci-ajax-more-scroll';
							wp_enqueue_script( 'penci_ajax_more_scroll' );
							wp_localize_script( 'penci_ajax_more_scroll', 'ajax_var_more', array(
									'url'     => admin_url( 'admin-ajax.php' ),
									'nonce'   => wp_create_nonce( 'ajax-nonce' )
								)
							);
						endif;
						/* Get data template */
						$data_layout = $style;
						$data_template = 'sidebar';
						if ( in_array( $style, array( 'standard-grid', 'classic-grid', 'overlay-grid' ) ) ) {
							$data_layout = 'grid';
						} elseif ( in_array( $style, array( 'standard-grid-2', 'classic-grid-2' ) ) ) {
							$data_layout = 'grid-2';
						} elseif ( in_array( $style, array( 'standard-list', 'classic-list', 'overlay-list' ) ) ) {
							$data_layout = 'list';
						} elseif ( in_array( $style, array( 'standard-boxed-1', 'classic-boxed-1' ) ) ) {
							$data_layout = 'boxed-1';
						}

						if( is_page_template( 'page-vc.php' ) ) {
							$data_template = 'no-sidebar';
						}
						?>
						<div class="penci-pagination <?php echo $button_class; ?>">
							<a class="penci-ajax-more-button" data-blockuid="<?php echo $block_id; ?>" data-query="<?php echo $data_pag_arg; ?>" data-mes="<?php echo penci_get_setting('penci_trans_no_more_posts'); ?>"  data-layout="<?php echo esc_attr( $data_layout ); ?>" data-number="<?php echo absint($morenum); ?>" data-offset="<?php echo absint($number); ?>" data-exclude="<?php
							echo $exclude; ?>" data-from="vc" data-come_from="vc-elementor" data-template="<?php echo $data_template; ?>" data-mixed="<?php echo esc_attr( $penci_mixed_style ); ?>">
								<span class="ajax-more-text"><?php echo penci_get_setting('penci_trans_load_more_posts'); ?></span><span class="ajaxdot"></span><?php penci_fawesome_icon('fas fa-sync'); ?>
							</a>
						</div>
						<?php echo self::get_block_script( $block_id, $atts ); ?>
					<?php } elseif( 'none' != $paging ) { ?>
						<?php echo penci_pagination_numbers( $query_custom ); ?>
					<?php } ?>
				</div>
				</div>
				<?php
			endif;
			wp_reset_postdata();

			$block_id_css   = '#' . $block_id;
			 ?>
				<style>
				<?php if( 'yes' == $standard_meta_overlay ): ?>
					<?php echo $block_id_css; ?> .penci-wrapper-data .standard-post-image:not(.classic-post-image){ margin-bottom: 0; }
					<?php echo $block_id_css; ?> .header-standard.standard-overlay-meta{ margin: -30px 30px 19px; background: #fff; padding-top: 25px; padding-left: 5px; padding-right: 5px; z-index: 10; position: relative; }
					<?php echo $block_id_css; ?> .penci-wrapper-data .standard-post-image:not(.classic-post-image) .audio-iframe, .penci-wrapper-data .standard-post-image:not(.classic-post-image) .standard-content-special{ bottom: 50px; }
					@media only screen and (max-width: 479px){
					<?php echo $block_id_css; ?> .header-standard.standard-overlay-meta{ margin-left: 10px; margin-right: 10px; }
					}
					<?php if( get_theme_mod( 'penci_bg_color_dark' ) ): ?>
						<?php echo $block_id_css; ?> .header-standard.standard-overlay-meta{ background-color: <?php echo penci_get_setting( 'penci_bg_color_dark' ); ?>; }
					<?php endif; ?>
				<?php endif ?>
				<?php if( 'yes' == $grid_remove_line ): ?>
					<?php echo $block_id_css; ?> .list-post .header-list-style:after,
					<?php echo $block_id_css; ?> .grid-header-box:after,
					<?php echo $block_id_css; ?> .penci-overlay-over .overlay-header-box:after,
					<?php echo $block_id_css; ?> .home-featured-cat-content .first-post .magcat-detail .mag-header:after { content: none; }
					<?php echo $block_id_css; ?> .list-post .header-list-style, .grid-header-box,
					<?php echo $block_id_css; ?> .penci-overlay-over .overlay-header-box,
					<?php echo $block_id_css; ?> .home-featured-cat-content .first-post .magcat-detail .mag-header{ padding-bottom: 0; }
				<?php endif; ?>
				<?php if( 'yes' == $standard_remove_line ): ?>
					<?php echo $block_id_css; ?> .header-standard:after { content: none; }
					<?php echo $block_id_css; ?> .header-standard { padding-bottom: 0; }
				<?php endif; ?>
				<?php if( 'yes' == $standard_effect_button ): ?>
					<?php echo $block_id_css; ?> .penci-more-link a.more-link:hover:before { right: 100%; margin-right: 10px; width: 60px; }
					<?php echo $block_id_css; ?> .penci-more-link a.more-link:hover:after{ left: 100%; margin-left: 10px; width: 60px; }
					<?php echo $block_id_css; ?> .standard-post-entry a.more-link:hover,
					 <?php echo $block_id_css; ?> .standard-post-entry a.more-link:hover:before,
					 <?php echo $block_id_css; ?> .standard-post-entry a.more-link:hover:after { opacity: 0.8; }
				<?php endif; ?>
				</style>
			<?php ;

			$return = ob_get_clean();

			if( $block_id  && class_exists( 'Penci_Custom_CSS_Shortcode_Old' )){
				$return .= Penci_Custom_CSS_Shortcode_Old::latest_posts( $block_id, $atts );
			}

			return $return;
		}

		/**
		 * Retrieve HTML markup of featured_cat shortcode
		 *
		 * @param array  $atts
		 * @param string $content
		 *
		 * @return string
		 */
		public static function featured_cat( $atts, $content = null ) {

			$orderby = $order = '';
			$atts = shortcode_atts( array(
				'style'    => 'style-1',
				'category' => '',
				'number'   => '5',
				'orderby'  => 'date',
				'order'    => 'DESC',

				'penci_columns'        => '',
				'penci_columns_tablet' => '',
				'penci_columns_mobile' => '',
				'penci_column_gap'     => '',
				'penci_row_gap'        => '',

				'hide_block_heading'    => '',
				'heading'             => '',
				'heading_title_style' => '',
				'heading_title_link'  => '',
				'heading_title_align' => '',
				'build_query'         => '',
				'elementor_query'         => '',

				// View all button
				'cat_seemore'         => '',
				'cat_view_link'       => '',
				'cat_remove_arrow'    => '',
				'cat_readmore_button' => '',
				'cat_readmore_align'  => '',

				'block_title_color'     => '',
				'block_title_hcolor'    => '',
				'btitle_bcolor'         => '',
				'btitle_outer_bcolor'   => '',
				'btitle_style5_bcolor'  => '',
				'btitle_style78_bcolor' => '',
				'btitle_bgcolor'        => '',
				'btitle_outer_bgcolor'  => '',
				'btitle_style9_bgimg'   => '',
				'use_btitle_typo'       => '',
				'btitle_typo'           => '',
				'btitle_fsize'          => '',
				'block_title_offupper'  => '',
				'block_title_marginbt'  => '',
				'btitle_shapes_color'  => '',

				'pborder_color'     => '',
				'ptitle_color'      => '',
				'ptitle_hcolor'     => '',
				'bptitle_color'      => '',
				'bptitle_hcolor'     => '',
				'ptitle_fsize'      => '',
				'use_ptitle_typo'   => '',
				'ptitle_typo'       => '',
				'bptitle_fsize'     => '',
				'pmeta_color'       => '',
				'pmeta_hcolor'      => '',
				'pmeta_bordercolor'      => '',
				'use_pmeta_typo'    => '',
				'pmeta_fsize'       => '',
				'pmeta_typo'        => '',
				'pexcerpt_color'    => '',
				'use_pexcerpt_typo' => '',
				'pexcerpt_fsize'    => '',
				'pexcerpt_typo'     => '',
				'pcat_color'        => '',
				'pcat_hcolor'       => '',
				'pcat_fsize'        => '',
				'pcat_typo'         => '',

				'enable_meta_overlay' => '',
				'hide_author'         => '',
				'hide_cat'            => '',
				'hide_icon_format'    => '',
				'hide_date'           => '',
				'show_viewscount'     => '',
				'hide_excerpt'        => '',
				'hide_excerpt_line'   => '',
				'_excerpt_length'     => '',
				'big_title_length'    => '',
				'_title_length'       => '',
				'penci_featimg_size'    => '',
				'penci_featimg_ratio'   => '',
				'',
			), $atts, 'featured_cat' );

			extract( $atts );

			$big_title_length = $big_title_length ? $big_title_length : '';
			$_title_length    = $_title_length ? $_title_length : '';

			$block_id = '';
			if( class_exists( 'Penci_Vc_Helper' ) ){
				$block_id  = Penci_Vc_Helper::get_unique_id_block( 'featured_cat' );
			}

			$return = $block_heading = $block_heading_url = $cat_ads_code = '';

			$is_page_builder = false;

			$query_args = array();
			if ( $atts['elementor_query'] ) {
				$query_args    = $atts['elementor_query'];

				$is_page_builder =  true;
			}
			elseif ( $atts['build_query'] ) {
				$query_args    = penci_build_args_query( $atts['build_query'] );

				$is_page_builder =  true;
			}else{
				if ( ! isset( $number ) || ! is_numeric( $number ) ): $number = '5'; endif;
				$fea_oj = get_category_by_slug( $category );

				if( ! empty ( $fea_oj ) ) {
					$fea_cat_id = $fea_oj->term_id;
					$fea_cat_name = $fea_oj->name;
					$cat_meta   = get_option( "category_$fea_cat_id" );
					$cat_ads_code = isset( $cat_meta['mag_ads'] ) ? $cat_meta['mag_ads'] : '';

					$block_heading     = sanitize_text_field( $fea_cat_name );
					$block_heading_url = get_category_link( $fea_cat_id );

					$query_args = array(
						'post_type' => 'post',
						'showposts' => $number,
						'orderby'   => $orderby,
						'order'     => $order,
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'slug',
								'terms'    => $category
							)
						)
					);
				}
			}

			$fea_query = new WP_Query( $query_args );
			$numers_results = $fea_query->post_count;

			if ( ! $fea_query->have_posts() ) {
				return self::show_missing_settings( 'Featured Cat', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
			}

			if ( $atts['heading_title_style'] ) {
				$heading_title = $atts['heading_title_style'];
			} elseif ( get_theme_mod( 'penci_featured_cat_style' ) ) {
				$heading_title = get_theme_mod( 'penci_featured_cat_style' );
			} else {
				$heading_title = 'style-1';
			}

			if ( $atts['heading_title_align'] ) {
				$heading_align = $atts['heading_title_align'];
			} elseif ( get_theme_mod( 'penci_featured_cat_align' ) ) {
				$heading_align = get_theme_mod( 'penci_featured_cat_align' );
			} else {
				$heading_align = 'pcalign-left';
			}

			if( $atts['heading'] ){
				$block_heading = $atts['heading'];
			}
			if( $atts['heading_title_link'] ){
				$block_heading_url = $atts['heading_title_link'];
			}

			$class_wrap = 'penci-featured-cat-sc';

			if ( in_array( $atts['style'], array( 'style-3', 'style-11' ) ) ) {
				if ( $atts['penci_columns'] ) {
					$class_wrap .= ' penci-featured-cat-ctcol';
					$class_wrap .= ' pencisc-grid-' . esc_attr( $atts['penci_columns'] );

					if ( $atts['penci_columns_tablet'] ) {
						$class_wrap .= ' pencisc-grid-tablet-' . esc_attr( $atts['penci_columns_tablet'] );
					}
					if ( $atts['penci_columns_mobile'] ) {
						$class_wrap .= ' pencisc-grid-mobile-' . esc_attr( $atts['penci_columns_mobile'] );
					}
				}
			}

			ob_start();
			?>
			<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $class_wrap ); ?>">
			<?php if ( $style == 'style-2' || $style == 'style-14' ) {
				$wrap_class = '';
				if( $style == 'style-14' ): $wrap_class = ' mag-cat-style-14'; endif;
			?>
				<div class="home-featured-cat mag-cat-style-2<?php echo $wrap_class; ?>">
			<?php } else { ?>
				<section class="home-featured-cat mag-cat-<?php echo esc_attr( $style ); ?>">
			<?php } ?>
				<?php if( $block_heading && ! $atts['hide_block_heading'] ){ ?>
				<div class="penci-border-arrow penci-homepage-title penci-magazine-title <?php echo sanitize_text_field( $heading_title . ' ' . $heading_align ); ?>">
					<h3 class="inner-arrow">
						<?php
						if ( $block_heading_url ) {
							echo '<a href="' . esc_url( $block_heading_url ) . '">';
						}
						echo do_shortcode( $block_heading );
						if ( $block_heading_url ) {
							echo '</a>';
						}
						?>
					</h3>
				</div>
				<?php } ?>
				<div class="home-featured-cat-content <?php echo esc_attr( $style ); ?>">
			<?php if ( $style == 'style-4' ): ?>
				<div class="penci-single-mag-slider penci-owl-carousel penci-owl-carousel-slider" data-auto="true" data-dots="true" data-nav="false">
			<?php endif; ?>
			<?php if( $style == 'style-5' || $style == 'style-12' ):
			$data_item = 2;
			if( $style == 'style-12' ): $data_item = 3; endif;
			?>
					<div class="penci-magcat-carousel-wrapper">
						<div class="penci-owl-carousel penci-owl-carousel-slider penci-magcat-carousel" data-speed="400" data-auto="true" data-item="<?php echo $data_item; ?>" data-desktop="<?php echo $data_item; ?>" data-tablet="2" data-tabsmall="1">
			<?php endif; ?>
				<?php if ( $style == 'style-7' || $style == 'style-8' || $style == 'style-13' ): ?>
				<ul class="penci-grid penci-grid-maglayout penci-fea-cat-<?php echo $style; ?>">
			<?php endif; ?>
				<?php
				$m = 1;
				while ( $fea_query->have_posts() ): $fea_query->the_post();
					include( locate_template( 'template-parts/magazine-sc/magazine-' . $style . '.php' ) );
					$m ++; endwhile;
				?>
			<?php if ( $style == 'style-7' || $style == 'style-8' || $style == 'style-13' ): ?>
				</ul>
			<?php endif; ?>
			<?php if ( $style == 'style-5' || $style == 'style-12' ): ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if ( $style == 'style-4' ): ?>
				</div>
			<?php endif; ?>
				</div>
				<?php
				if( $is_page_builder  ): ?>
					<?php
					if( $atts['cat_seemore'] ){
						$viewall_class = '';

						if( $atts['cat_remove_arrow'] ): $viewall_class .= ' penci-btn-remove-arrow'; endif;
						if( $atts['cat_readmore_button'] ) : $viewall_class .= ' penci-btn-make-button'; endif;
						if( $atts['cat_readmore_align'] ) : $viewall_class .= ' penci-btn-align-' . esc_attr( $atts['cat_readmore_align'] ); endif;
						?>
							<div class="penci-featured-cat-seemore penci-seemore-<?php echo esc_attr( $style ); echo $viewall_class; ?>">
								<a href="<?php echo esc_url( $atts['cat_view_link'] ); ?>"><?php echo penci_get_setting( 'penci_trans_view_all' ); ?>
									<?php penci_fawesome_icon('fas fa-angle-double-right'); ?>
								</a>
							</div>
						<?php
					}
				?>
				<?php elseif ( get_theme_mod( 'penci_home_featured_cat_seemore' ) ):
					$viewall_class = '';
					if( get_theme_mod( 'penci_home_featured_cat_remove_arrow' ) ): $viewall_class .= ' penci-btn-remove-arrow'; endif;
					if( get_theme_mod( 'penci_home_featured_cat_readmore_button' ) ): $viewall_class .= ' penci-btn-make-button'; endif;
					if( get_theme_mod( 'penci_home_featured_cat_readmore_align' ) ): $viewall_class .= ' penci-btn-align-' . get_theme_mod( 'penci_home_featured_cat_readmore_align' ); endif;
					?>
					<div class="penci-featured-cat-seemore penci-seemore-<?php echo esc_attr( $style ); echo $viewall_class; ?>">
						<a href="<?php echo esc_url( get_category_link( $fea_cat_id ) ); ?>"><?php echo penci_get_setting( 'penci_trans_view_all' ); ?>
							<?php penci_fawesome_icon('fas fa-angle-double-right'); ?>
						</a>
					</div>
				<?php endif; ?>

			<?php if ( $cat_ads_code &&  ! $is_page_builder ): ?>
				<div class="penci-featured-cat-custom-ads">
					<?php echo stripslashes( $cat_ads_code ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $style == 'style-2' || $style == 'style-14' ) { ?>
				</div>
			<?php }
			else { ?>
			</section>
			<?php } ?>
			</div><!-- penci-featured-cat-sc -->
			<?php
			wp_reset_postdata();

			$block_id_css   = '#' . $block_id;
			?>
			<style>
				<?php if( 'yes' == $hide_excerpt_line ): ?>
				<?php echo $block_id_css; ?> .first-post .magcat-detail .mag-header:after,
				<?php echo $block_id_css; ?> .list-post .header-list-style:after,
				<?php echo $block_id_css; ?> .home-featured-cat-content.style-7 .grid-header-box:after { content: none; }
				<?php echo $block_id_css; ?> .grid-header-box,
				<?php echo $block_id_css; ?> .list-post .header-list-style,
				<?php echo $block_id_css; ?> .first-post .magcat-detail .mag-header { padding-bottom: 0;margin-bottom: 0; }
				<?php endif; ?>
			</style>
			<?php ;

			$return = ob_get_clean();

			if( $block_id && class_exists( 'Penci_Custom_CSS_Shortcode_Old' ) ){
				$return .= Penci_Custom_CSS_Shortcode_Old::featured_cat( $block_id, $atts );
			}

			return $return;
		}

		public static function amp_penci_popular_posts_styles(){
			$slides_to_show = 4;
			$slide_width_arr = array('1'=>'100%','2'=>'50%','3'=>'33.3%','4'=>'25%','5'=>'20%','6'=>'16.6%','7'=>'14.28%','8'=>'12.5%','9'=>'11.11%','10'=>'10%');
			if(isset(self::$slider_columns) && !empty(self::$slider_columns)){
				$slides_to_show = self::$slider_columns;
				$slide_width = $slide_width_arr[$slides_to_show];
			}else{
				$slide_width = $slide_width_arr[$slides_to_show];
			}
			$inline_styles = 'amp-carousel > div::-webkit-scrollbar { height:0px;  display:inline-block;} amp-carousel#penci-popular-posts-slider{display: grid;
    align-items: center;}.penci-home-popular-posts .penci-owl-carousel .item-related{width:'.$slide_width.';text-align:center;vertical-align: top;}@media only screen and (max-width: 767px) { .penci-home-popular-posts .penci-owl-carousel .item-related{width:100%;}}';
			echo $inline_styles;

		}
		public static function popular_posts( $atts, $content = null ) {
			add_action('amp_post_template_css',[get_called_class(),'amp_penci_popular_posts_styles']);
			$atts = shortcode_atts( array(
				'title'         => 'Popular Posts',
				'type'          => 'all',
				'category'      => '',
				'number'        => '12',
				'columns'       => '4',
				'_title_length' => '10',

				'hide_block_heading'  => '',
				'heading_title_style' => '',
				'heading_title_link'  => '',
				'heading_title_align' => '',
				'build_query'         => '',
				'elementor_query'     => '',

				'block_title_color'     => '',
				'block_title_hcolor'    => '',
				'btitle_bcolor'         => '',
				'btitle_outer_bcolor'   => '',
				'btitle_style5_bcolor'  => '',
				'btitle_style78_bcolor' => '',
				'btitle_bgcolor'        => '',
				'btitle_outer_bgcolor'  => '',
				'btitle_style9_bgimg'   => '',
				'use_btitle_typo'       => '',
				'btitle_typo'           => '',
				'btitle_fsize'          => '',
				'block_title_offupper'  => '',
				'block_title_marginbt'  => '',
				'btitle_shapes_color'   => '',
				'penci_featimg_size'    => '',
				'penci_featimg_ratio'   => '',

				'ptitle_color'    => '',
				'ptitle_hcolor'   => '',
				'ptitle_fsize'    => '',
				'use_ptitle_typo' => '',
				'ptitle_typo'     => '',
				'pmeta_color'     => '',
				'pmeta_hcolor'    => '',
				'pmeta_fsize'     => '',
				'use_pmeta_typo'  => '',
				'pmeta_typo'      => '',
				'_dot_color'      => '',
				'dot_hcolor'      => '',
			), $atts, 'popular_posts' );

			extract( $atts );

			$return = '';

			$_title_length    = $_title_length ? $_title_length : 10;

			if ( ! isset( $columns ) || ! is_numeric( $columns ) ): $columns = '4'; endif;

			if ( $atts['elementor_query'] ) {
				$query_args    = $atts['elementor_query'];
			}
			elseif ( $atts['build_query'] ) {
				$query_args    = penci_build_args_query( $atts['build_query'] );
			}else{
				if ( ! isset( $number ) || ! is_numeric( $number ) ): $number = '12'; endif;

				$query_args = array(
					'posts_per_page' => $number,
					'post_type'      => 'post',
					'meta_key'       => 'penci_post_views_count',
					'orderby'        => 'meta_value_num',
					'order'          => 'DESC'
				);

				if( $type == 'week' ) {
					$query_args = array(
						'posts_per_page' => $number,
						'post_type'      => 'post',
						'meta_key'       => 'penci_post_week_views_count',
						'orderby'        => 'meta_value_num',
						'order'          => 'DESC'
					);
				} elseif ( $type == 'month' ) {
					$query_args = array(
						'posts_per_page' => $number,
						'post_type'      => 'post',
						'meta_key'       => 'penci_post_month_views_count',
						'orderby'        => 'meta_value_num',
						'order'          => 'DESC'
					);
				}

				if( $category ){
					$query_args['category_name'] = $category;
				}
			}
			$popular = new WP_Query( $query_args );

			if( ! $popular->have_posts() ) {
				return self::show_missing_settings( 'Popular Posts', penci_get_setting( 'penci_ajaxsearch_no_post' ) );
			}
			$popular_title_length = $_title_length ? $_title_length : 10;
			$data_loop            = '';
			$number_posts_display = $popular->post_count;
			if ( ( $columns == '4' && $number_posts_display < 5 ) || ( $columns == '3' && $number_posts_display < 4 ) ):
				$data_loop = ' data-loop="false"';
			endif;

			$heading_title_style = '';
			$heading_align = '';

			if ( $atts['heading_title_style'] ) {
				$heading_title_style = $atts['heading_title_style'];
			}

			if ( $atts['heading_title_align'] ) {
				$heading_align = $atts['heading_title_align'];
			}
			$block_id = '';
			if( class_exists( 'Penci_Vc_Helper' ) ){
				$block_id  = Penci_Vc_Helper::get_unique_id_block( 'popular_posts' );
			}


			ob_start();
			?>
			<div id="<?php echo esc_attr( $block_id ); ?>" class="penci-popular-posts-sc">
			<div class="penci-home-popular-posts<?php echo ( ! $heading_title_style ? ' use-heading-default' : '' ); ?>">
				<?php if( $title && ! $atts['hide_block_heading'] ){ ?>
					<?php if( ! $heading_title_style ){ ?>
						<h2 class="home-pupular-posts-title <?php echo sanitize_text_field( $heading_align ); ?>">
						<?php
						echo ( $atts['heading_title_link'] ? '<a href="' . esc_url( $atts['heading_title_link'] ) . '">' : '<span>' );
						echo do_shortcode( $title );
						echo ( $atts['heading_title_link'] ? '</a>' : '</span>' );
						?>
						</h2>
					<?php }else{ ?>
						<div class="penci-border-arrow penci-homepage-title penci-magazine-title <?php echo sanitize_text_field( $heading_title_style . ' ' . $heading_align ); ?>">
							<h3 class="inner-arrow">
								<?php
								if( $atts['heading_title_link'] ){
									echo '<a href="' . esc_url( $atts['heading_title_link'] ) . '">';
								}
								echo do_shortcode( $title );
								if( $atts['heading_title_link'] ){
									echo '</a>';
								}
								?>
							</h3>
						</div>
					<?php } ?>
				<?php } ?>
				<?php
					$slide_width_arr = array('1'=>'100%','2'=>'50%','3'=>'33.3%','4'=>'25%','5'=>'20%','6'=>'16.6%','7'=>'14.28%','8'=>'12.5%','9'=>'11.11%','10'=>'10%');
					if(isset($columns) && !empty($columns)){
						$slides_to_show = $columns;
						$slide_width = $slide_width_arr[$slides_to_show];
					}else{
						$slide_width = $slide_width_arr[$slides_to_show];
					}
					self::$slider_columns = $columns;
					
				?>
				<amp-carousel id="penci-popular-posts-slider" class="penci-owl-carousel penci-owl-carousel-slider penci-related-carousel penci-home-popular-post"<?php echo $data_loop; ?> data-lazy="true" data-item="<?php echo $columns; ?>" data-desktop="<?php echo $columns; ?>" layout="fixed-height" type="carousel" height="400" >
					<?php while ( $popular->have_posts() ) : $popular->the_post(); ?>
						<div class="item-related" >
							<?php if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) : ?>
							<?php if( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
							<a class="related-thumb penci-image-holder owl-lazy" href="<?php the_permalink() ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>" data-src="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ); ?>">
								<amp-img src="<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ); ?>" width="400" height="300" alt="another sample image" layout="responsive"></amp-img>
								<?php } else { ?>
								<a class="related-thumb penci-image-holder" href="<?php the_permalink() ?>" title="<?php echo wp_strip_all_tags( get_the_title() ); ?>" style="background-image: url('<?php echo penci_get_featured_image_size( get_the_ID(), 'penci-thumb' ); ?>');">
									<?php }?>
									<?php if( has_post_thumbnail() && get_theme_mod('penci_enable_home_popular_icons') ): ?>
										<?php if ( has_post_format( 'video' ) ) : ?>
											<?php penci_fawesome_icon('fas fa-play'); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'audio' ) ) : ?>
											<?php penci_fawesome_icon('fas fa-music'); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'link' ) ) : ?>
											<?php penci_fawesome_icon('fas fa-link'); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'quote' ) ) : ?>
											<?php penci_fawesome_icon('fas fa-quote-left'); ?>
										<?php endif; ?>
										<?php if ( has_post_format( 'gallery' ) ) : ?>
											<?php penci_fawesome_icon('far fa-image'); ?>
										<?php endif; ?>
									<?php endif; ?>
								</a>
								<?php endif; ?>

								<h3 style="margin:10px 0px;text-align: center;"><a title="<?php echo wp_strip_all_tags( get_the_title() ); ?>" href="<?php the_permalink(); ?>" style="white-space: pre-wrap;color:#555;text-align: center;font-size: 15px;line-height: 1.4;"><?php echo wp_trim_words( wp_strip_all_tags( get_the_title() ), $popular_title_length, '...' ); ?></a></h3>
								<?php if ( ! get_theme_mod( 'penci_hide_date_home_popular' ) ) : ?>
									<span class="date" style="text-align: center;width:100%;display:inline-block;font-size: 14px;padding-bottom: 20px;"><?php penci_soledad_time_link(); ?></span>
								<?php endif; ?>
						</div>
						<?php
					endwhile;
					?>
				</amp-carousel>
			</div>
			</div><!-- penci-popular_posts-sc -->
			<?php
			$return = ob_get_clean();
			wp_reset_postdata();

			if( $block_id && class_exists( 'Penci_Custom_CSS_Shortcode_Old' ) ) {
				$return .= Penci_Custom_CSS_Shortcode_Old::popular_posts( $block_id, $atts );
			}

			return $return;
		}
		
		
		/**
		 * Retrieve HTML markup for sidebar element
		 *
		 * @param array  $atts
		 * @param string $content
		 *
		 * @return string
		 */
		public static function soledad_sidebar( $atts, $content = null ) {
			extract( shortcode_atts( array(
				'sidebar'    => 'main-sidebar',
				'style' => 'style-1',
				'align'   => 'center'
			), $atts ) );

			if ( ! isset( $sidebar ) ): $sidebar = 'main-sidebar'; endif;
			if ( ! isset( $style ) ): $style = 'style-1'; endif;
			if ( ! in_array( $align, array( 'pcalign-center', 'pcalign-left', 'pcalign-right' ) ) ): $align = 'pcalign-center'; endif;
			
			ob_start();
			?>

			<div id="sidebar" class="penci-sidebar-content penci-sidebar-content-vc <?php echo sanitize_text_field( $style . ' ' . $align ); ?>">
				<div class="theiaStickySidebar">
					<?php 
					if( is_active_sidebar( $sidebar ) ){
						dynamic_sidebar( $sidebar );
					} else {
						dynamic_sidebar( 'main-sidebar' );
					}
					?>
				</div>
			</div>

			<?php
			$return = ob_get_clean();

			return $return;
		}
		
		/**
		 * Retrieve HTML markup for featured boxes - like homepage featured boxes
		 *
		 * @param array  $atts
		 * @param string $content
		 *
		 * @return string
		 */
		public static function soledad_featured_boxes( $atts, $content = null ) {
			$atts = shortcode_atts( array(
				'style'         => 'boxes-style-1',
				'columns'       => 'boxes-3-columns',
				'size'          => 'horizontal',
				'margin_top'    => '0',
				'margin_bottom' => '0',
				'boxes_data'    => '',
				'new_tab'       => 'no',

				'img_box_border_color' => '',
				'img_box_text_color'   => '',
				'img_box_text_hcolor'  => '',
				'img_box_fsize'        => '',
				'use_img_box_typo'     => '',
				'img_box_typo'         => '',
				''
			), $atts );

			extract( $atts );
			
			if( ! function_exists( 'vc_param_group_parse_atts' ) ){
				return;
			}

			if( empty( $atts['boxes_data'] ) ) {
				return;
			}

			$featured_boxes = (array) vc_param_group_parse_atts( $atts['boxes_data'] );

			if( empty( $featured_boxes ) ) {
				return;
			}

			if ( ! isset( $style ) ): $style = 'boxes-style-1'; endif;
			if ( ! isset( $columns ) ): $columns = 'boxes-3-columns'; endif;
			if ( ! isset( $size ) ): $size = 'horizontal'; endif;
			if ( ! isset( $new_tab ) ): $new_tab = 'no'; endif;
			if ( ! is_numeric( $margin_top ) ): $margin_top = '0'; endif;
			if ( ! is_numeric( $margin_bottom ) ): $margin_bottom = '0'; endif;
			$style_css = ' style="margin-top: ' . $margin_top . 'px; margin-bottom: ' . $margin_bottom . 'px;"';
			$weight_text = get_theme_mod( 'penci_home_box_weight' ) ? get_theme_mod( 'penci_home_box_weight' ) : 'normal';
			$thumb = 'penci-thumb';
			if( $size == 'square' ){
				$thumb = 'penci-thumb-square';
			} elseif( $size == 'vertical' ) {
				$thumb = 'penci-thumb-vertical';
			}

			$block_id = '';
			if( class_exists( 'Penci_Vc_Helper' ) ){
				$block_id  = Penci_Vc_Helper::get_unique_id_block( 'featured_boxes' );
			}

			ob_start();
			?>
			<div id="<?php echo esc_attr( $block_id ); ?>" class="container home-featured-boxes home-featured-boxes-sc home-featured-boxes-vc boxes-weight-<?php echo $weight_text; ?> boxes-size-<?php echo $size; ?>"<?php echo $style_css; ?>>
				<ul class="homepage-featured-boxes <?php echo $columns; ?>">
					<?php
					foreach ( $featured_boxes as $item ) {
						if ( isset( $item['image'] ) ):
							$homepage_box_image = wp_get_attachment_url( $item['image'] );
							$homepage_box_text = isset( $item['text'] ) ? $item['text'] : '';
							$homepage_box_url = isset( $item['url'] ) ? $item['url'] : '';
						
							$open_url  = '';
							$close_url = '';
							$target = '';
							if( 'yes' == $new_tab ):
								$target = ' target="_blank"';
							endif;
							if ( $homepage_box_url ) {
								$open_url  = '<a href="' . do_shortcode( $homepage_box_url ) . '"' . $target . '>';
								$close_url = '</a>';
							}
							?>
							<li class="penci-featured-ct">
								<?php echo wp_kses( $open_url, penci_allow_html() ); ?>
								<div class="penci-fea-in <?php echo $style; ?>">
									<?php if( ! get_theme_mod( 'penci_disable_lazyload_layout' ) ) { ?>
										<div class="fea-box-img penci-image-holder penci-holder-load penci-lazy" data-src="<?php echo penci_get_image_size_url( $homepage_box_image, $thumb ); ?>"></div>
									<?php } else { ?>
										<div class="fea-box-img penci-image-holder" style="background-image: url('<?php echo penci_get_image_size_url( $homepage_box_image, $thumb ); ?>');"></div>
									<?php }?>

									<?php if( $homepage_box_text ): ?>
									<h4><span class="boxes-text"><span style="font-weight: <?php echo $weight_text; ?>"><?php echo do_shortcode( $homepage_box_text ); ?></span></span></h4>
									<?php endif; ?>
								</div>
								<?php echo wp_kses( $close_url, penci_allow_html() ) ; ?>
							</li>
						<?php
						endif;
					}
					?>
				</ul>
			</div>

			<?php
			$return = ob_get_clean();

			if( $block_id && class_exists( 'Penci_Custom_CSS_Shortcode_Old' ) ) {
				$return .= Penci_Custom_CSS_Shortcode_Old::featured_boxes( $block_id, $atts );
			}

			return $return;
		}

		public static function show_missing_settings( $label, $mess ) {
			$output = '';
			if ( is_user_logged_in() ) {
				$output .= '<div class="penci-missing-settings">';
				$output .= '<span>' . $label . '</span>';
				$output .= $mess;
				$output .= '</div>';
			}

			return $output;
		}

		public static function get_block_script( $unique_id, $atts ) {
			$atts['category_ids'] = '';
			$atts['taxonomy']     = '';

			if( isset( $atts['title'] ) ) {
				unset( $atts['title'] );
			}

			$output = '<script>';

			$output .= 'if( typeof(penciBlock) === "undefined" ) {';
			$output .= "function penciBlock() { this.atts_json = ''; this.content = ''; }";
			$output .= '}';
			$output .= 'var penciBlocksArray = penciBlocksArray || [];';
			$output .= 'var PENCILOCALCACHE = PENCILOCALCACHE || {};';
			$output .= 'var ' . $unique_id . ' = new penciBlock();';
			$output .= $unique_id . '.blockID="' . $unique_id . '";';
			$output .= $unique_id . ".atts_json = '" . json_encode( $atts ) . "';";
			$output .= "penciBlocksArray.push(" . $unique_id . ");";
			$output .= '</script>';


			echo $output;
		}

	}

	Amp_Soledad_VC_Shortcodes::init();
}