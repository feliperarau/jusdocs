<?php

if ( fusion_is_element_enabled( 'fusion_products_slider' ) ) {

	if ( ! class_exists( 'AMP_PC_FusionSC_WooProductSlider' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @package fusion-builder
		 * @since 1.0
		 */
		class AMP_PC_FusionSC_WooProductSlider extends Fusion_Element {

			/**
			 * An array of the shortcode arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 */
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_woo-product-slider-shortcode', array( $this, 'attr' ) );
				add_filter( 'fusion_attr_woo-product-slider-shortcode-carousel', array( $this, 'carousel_attr' ) );
				add_filter( 'fusion_attr_woo-product-slider-shortcode-img-div', array( $this, 'img_div_attr' ) );

				add_shortcode( 'fusion_products_slider', array( $this, 'render' ) );

			}

			function print_css_data(){
                echo '.fusion-woo-product-slider amp-carousel .slide {
                    width: 30%;
                    margin-right: 20px;
                }';
            }

			/**
			 * Render the shortcode.
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output
			 */
			public function render( $args, $content = '' ) {
				add_action("amp_post_template_css", array($this,'print_css_data'));
				global $woocommerce, $fusion_library, $fusion_settings;

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'hide_on_mobile'  => fusion_builder_default_visibility( 'string' ),
						'class'           => '',
						'id'              => '',
						'autoplay'        => 'no',
						'carousel_layout' => 'title_on_rollover',
						'cat_slug'        => '',
						'columns'         => '5',
						'column_spacing'  => '13',
						'mouse_scroll'    => 'no',
						'number_posts'    => 10,
						'picture_size'    => 'fixed',
						'scroll_items'    => '',
						'show_buttons'    => 'yes',
						'show_cats'       => 'yes',
						'show_nav'        => 'yes',
						'show_price'      => 'yes',
					),
					$args,
					'fusion_products_slider'
				);

				$defaults['column_spacing'] = FusionBuilder::validate_shortcode_attr_value( $defaults['column_spacing'], '' );

				( 'yes' == $defaults['show_cats'] ) ? ( $defaults['show_cats']   = 'enable' ) : ( $defaults['show_cats'] = 'disable' );
				( 'yes' == $defaults['show_price'] ) ? ( $defaults['show_price'] = true ) : ( $defaults['show_price'] = false );
				( 'yes' == $defaults['show_buttons'] ) ? ( $defaults['show_buttons']                                  = true ) : ( $defaults['show_buttons'] = false );

				extract( $defaults );

				$this->args = $defaults;

				$html    = '';
				$buttons = '';

				if ( class_exists( 'Woocommerce' ) ) {

					$items_in_cart = array();

					if ( $woocommerce->cart && $woocommerce->cart->get_cart() && is_array( $woocommerce->cart->get_cart() ) ) {
						foreach ( $woocommerce->cart->get_cart() as $cart ) {
							$items_in_cart[] = $cart['product_id'];
						}
					}

					$design_class = 'fusion-' . $fusion_settings->get( 'woocommerce_product_box_design', false, 'classic' ) . '-product-image-wrapper';

					$number_posts = (int) $number_posts;

					$args = array(
						'post_type'      => 'product',
						'posts_per_page' => $number_posts,
						'meta_query'     => array(
							array(
								'key'     => '_thumbnail_id',
								'compare' => '!=',
								'value'   => null,
							),
						),
					);

					if ( $cat_slug ) {
						$cat_id = $cat_slug;
						if ( false !== strpos( $cat_slug, ',' ) ) {
							$cat_id = explode( ',', $cat_slug );
						} elseif ( false !== strpos( $cat_slug, '|' ) ) {
							$cat_id = explode( '|', $cat_slug );
						}
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'product_cat',
								'field'    => 'slug',
								'terms'    => $cat_id,
							),
						);
					}

					$args['tax_query']['relation'] = 'AND';
					$args['tax_query'][]           = array(
						'taxonomy' => 'product_visibility',
						'field'    => 'slug',
						'terms'    => array( 'exclude-from-catalog', 'exclude-from-search' ),
						'operator' => 'NOT IN',
					);

					$featured_image_size = 'full';
					if ( 'fixed' === $picture_size ) {
						$featured_image_size = 'portfolio-five';
					}

					$products = fusion_cached_query( $args );

					if ( ! $products->have_posts() ) {
						return fusion_builder_placeholder( 'product', 'products' );
					}

					$product_list = '';

					if ( $products->have_posts() ) {

						while ( $products->have_posts() ) {
							$products->the_post();

							$id      = get_the_ID();
							$in_cart = in_array( $id, $items_in_cart, true );
							$image   = $price_tag = $terms = '';

							if ( 'auto' === $picture_size ) {
								$fusion_library->images->set_grid_image_meta(
									array(
										'layout'       => 'grid',
										'columns'      => $columns,
										'gutter_width' => $column_spacing,
									)
								);
							}

							// Title on rollover layout.
							if ( 'title_on_rollover' === $carousel_layout ) {
								$image = fusion_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), true, $show_price, $show_buttons, $show_cats );
								// Title below image layout.
							} else {
								$image = fusion_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), true, false, $show_buttons, 'disable', 'disable', '', '', 'no' );
								if ( 'yes' == $show_buttons ) {
									$image = fusion_render_first_featured_image_markup( get_the_ID(), $featured_image_size, get_permalink( get_the_ID() ), true, false, $show_buttons, 'disable', 'disable' );
								}

								// Get the post title.
								$image .= '<h4 ' . FusionBuilder::attributes( 'fusion-carousel-title' ) . '><a href="' . get_permalink( get_the_ID() ) . '" target="_self">' . get_the_title() . '</a></h4>';
								$image .= '<div class="fusion-carousel-meta">';

								// Get the terms.
								if ( 'enable' === $show_cats ) {
									$image .= get_the_term_list( get_the_ID(), 'product_cat', '', ', ', '' );
								}

								// Check if we should render the woo product price.
								if ( $show_price ) {
									ob_start();
									fusion_wc_get_template( 'loop/price.php' );
									$image .= '<div class="fusion-carousel-price">' . ob_get_clean() . '</div>';
								}

								$image .= '</div>';
							}

							if ( 'auto' === $picture_size ) {
								$fusion_library->images->set_grid_image_meta( array() );
							}

							if ( $in_cart ) {
								$product_list .= '<li ' . FusionBuilder::attributes( 'fusion-carousel-item' ) . '><div class="' . $design_class . ' fusion-item-in-cart"><div ' . FusionBuilder::attributes( 'fusion-carousel-item-wrapper' ) . '>' . $image . '</div></div></li>';
							} else {
								$product_list .= '<li ' . FusionBuilder::attributes( 'fusion-carousel-item' ) . '><div class="' . $design_class . '"><div ' . FusionBuilder::attributes( 'fusion-carousel-item-wrapper' ) . '>' . $image . '</div></div></li>';
							}
						}
					}
					// @codingStandardsIgnoreLine
					wp_reset_query();

					$html  = '<div ' . FusionBuilder::attributes( 'woo-product-slider-shortcode' ) . '>';
					$html .= '<div ' . FusionBuilder::attributes( 'woo-product-slider-shortcode-carousel' ) . '>';
					$html .= '<div ' . FusionBuilder::attributes( 'fusion-carousel-positioner' ) . '>';
					$html .= '<amp-carousel class="carousel2"
                    layout="fixed-height"
					height="365"
					type="carousel" ' . FusionBuilder::attributes( 'fusion-carousel-holder' ) . '>';
					$html .= $product_list;
					$html .= '</amp-carousel>';
					// Check if navigation should be shown.
					if ( 'yes' === $show_nav ) {
						$html .= sprintf(
							'<div %s><span %s></span><span %s></span></div>',
							FusionBuilder::attributes( 'fusion-carousel-nav' ),
							FusionBuilder::attributes( 'fusion-nav-prev' ),
							FusionBuilder::attributes( 'fusion-nav-next' )
						);
					}
					$html .= '</div>';
					$html .= '</div>';
					$html .= '</div>';
				}

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function attr() {

				$attr = fusion_builder_visibility_atts(
					$this->args['hide_on_mobile'],
					array(
						'class' => 'fusion-woo-product-slider fusion-woo-slider',
					)
				);

				if ( $this->args['class'] ) {
					$attr['class'] .= ' ' . $this->args['class'];
				}

				if ( $this->args['id'] ) {
					$attr['id'] = $this->args['id'];
				}

				return $attr;

			}

			/**
			 * Builds the carousel attributes.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function carousel_attr() {

				$attr = array(
					'class' => 'fusion-carousel',
				);

				if ( 'title_below_image' === $this->args['carousel_layout'] ) {
					$attr['class']           .= ' fusion-carousel-title-below-image';
					$attr['data-metacontent'] = 'yes';
				} else {
					$attr['class'] .= ' fusion-carousel-title-on-rollover';
				}

				$attr['data-autoplay']    = $this->args['autoplay'];
				$attr['data-columns']     = $this->args['columns'];
				$attr['data-itemmargin']  = $this->args['column_spacing'];
				$attr['data-itemwidth']   = 180;
				$attr['data-touchscroll'] = $this->args['mouse_scroll'];
				$attr['data-imagesize']   = $this->args['picture_size'];
				$attr['data-scrollitems'] = $this->args['scroll_items'];

				return $attr;
			}

			/**
			 * Builds the dynamic styling.
			 *
			 * @access public
			 * @since 1.1
			 * @return array
			 */
			public function add_styling() {

				global $wp_version, $content_media_query, $six_fourty_media_query, $three_twenty_six_fourty_media_query, $ipad_portrait_media_query, $fusion_library, $fusion_settings, $dynamic_css_helpers;

				$elements = array(
					'.fusion-carousel .fusion-carousel-nav .fusion-nav-prev',
					'.fusion-carousel .fusion-carousel-nav .fusion-nav-next',
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'carousel_nav_color' ) );

				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['width'] = $fusion_library->sanitize->size( $fusion_settings->get( 'slider_nav_box_dimensions', 'width' ) );

				preg_match_all( '!\d+!', $fusion_settings->get( 'slider_nav_box_dimensions', 'height' ), $matches );
				$half_slider_nav_box_height = '' !== $fusion_settings->get( 'slider_nav_box_dimensions', 'height' ) ? $matches[0][0] / 2 . $fusion_library->sanitize->get_unit( $fusion_settings->get( 'slider_nav_box_dimensions', 'height' ) ) : '';

				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['height']     = $fusion_library->sanitize->size( $fusion_settings->get( 'slider_nav_box_dimensions', 'height' ) );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['margin-top'] = '-' . $half_slider_nav_box_height;

				$elements = array(
					'.fusion-carousel .fusion-carousel-nav .fusion-nav-prev:before',
					'.fusion-carousel .fusion-carousel-nav .fusion-nav-next:before',
				);

				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['line-height'] = $fusion_library->sanitize->size( $fusion_settings->get( 'slider_nav_box_dimensions', 'height' ) );

				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['font-size'] = $fusion_library->sanitize->size( $fusion_settings->get( 'slider_arrow_size' ) );

				return $css;
			}

			/**
			 * Sets the necessary scripts.
			 *
			 * @access public
			 * @since 1.1
			 * @return void
			 */
			public function add_scripts() {
				Fusion_Dynamic_JS::enqueue_script( 'fusion-carousel' );
			}
		}
	}
    remove_shortcode('fusion_products_slider');
	new AMP_PC_FusionSC_WooProductSlider();

}