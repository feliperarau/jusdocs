<?php

if ( ! class_exists( 'AMP_PC_FusionSC_Container' ) ) {
	/**
	 * Shortcode class.
	 *
	 * @package fusion-builder
	 * @since 1.0
	 */
	class AMP_PC_FusionSC_Container extends Fusion_Element {

		/**
		 * An array of the shortcode arguments.
		 *
		 * @access protected
		 * @since 1.0
		 * @var array
		 */
		protected $args;

		/**
		 * The internal container counter.
		 *
		 * @access private
		 * @since 1.3
		 * @var int
		 */
		private $container_counter = 1;

		/**
		 * The counter for 100% height scroll sections.
		 *
		 * @access private
		 * @since 1.3
		 * @var int
		 */
		private $scroll_section_counter = 0;

		/**
		 * The counter for elements in a 100% height scroll section.
		 *
		 * @access private
		 * @since 1.3
		 * @var int
		 */
		private $scroll_section_element_counter = 1;

		/**
		 * Stores the navigation for a scroll section.
		 *
		 * @access private
		 * @since 1.3
		 * @var int
		 */
		private $scroll_section_navigation = '';

		private static $instance;
		
		/**
		 * Constructor.
		 *
		 * @access public
		 * @since 1.0
		 */
		public function __construct() {
			parent::__construct();
			add_shortcode( 'fusion_builder_container', array( $this, 'render' ) );
		}

		public static function get_instance() {

			// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
			if ( null === self::$instance ) {
				self::$instance = new AMP_PC_FusionSC_Container();
			}
			return self::$instance;
		}

		/**
		 * Container shortcode.
		 *
		 * @access public
		 * @since 1.0
		 * @param array  $atts    The attributes array.
		 * @param string $content The content.
		 * @return string
		 */
		public function render( $atts, $content = '' ) {

			global $fusion_settings;
			if ( ! $fusion_settings ) {
				$fusion_settings = Fusion_Settings::get_instance();
			}
			$atts = fusion_section_deprecated_args( $atts );

			$this->args = FusionBuilder::set_shortcode_defaults(
				array(
					'admin_label'                   => '',
					'is_nested'                     => '0', // Variable that simply checks if the current container is a nested one (e.g. from FAQ or blog element).
					'hide_on_mobile'                => fusion_builder_default_visibility( 'string' ),
					'id'                            => '',
					'class'                         => '',
					'status'                        => 'published',
					'publish_date'                  => '',

					// Background.
					'background_color'              => $fusion_settings->get( 'full_width_bg_color' ),
					'background_image'              => '',
					'background_position'           => 'center center',
					'background_repeat'             => 'no-repeat',
					'background_parallax'           => 'none',
					'parallax_speed'                => '0.3',
					'opacity'                       => '100',
					'break_parents'                 => '0',
					'fade'                          => 'no',

					// Style.
					'hundred_percent'               => 'no',
					'hundred_percent_height'        => 'no',
					'hundred_percent_height_scroll' => 'no',
					'hundred_percent_height_center_content' => 'no',
					'padding_bottom'                => '',
					'padding_left'                  => '',
					'padding_right'                 => '',
					'padding_top'                   => '',
					'border_color'                  => $fusion_settings->get( 'full_width_border_color' ),
					'border_size'                   => $fusion_settings->get( 'full_width_border_size' ),
					'border_style'                  => 'solid',
					'equal_height_columns'          => 'no',
					'data_bg_height'                => '',
					'data_bg_width'                 => '',
					'enable_mobile'                 => 'no',
					'menu_anchor'                   => '',
					'margin_top'                    => '',
					'margin_bottom'                 => '',

					// Video Background.
					'video_mp4'                     => '',
					'video_webm'                    => '',
					'video_ogv'                     => '',
					'video_loop'                    => 'yes',
					'video_mute'                    => 'yes',
					'video_preview_image'           => '',
					'overlay_color'                 => '',
					'overlay_opacity'               => '0.5',
					'video_url'                     => '',
					'video_loop_refinement'         => '',
					'video_aspect_ratio'            => '16:9',

				),
				$atts,
				'fusion_builder_container'
			);

			extract( $this->args );

			// If container is no published, return early.
			if ( ! apply_filters( 'fusion_is_container_viewable', $this->is_container_viewable(), $this->args ) ) {
				return;
			}

			// @codingStandardsIgnoreLine
			global $parallax_id, $fusion_fwc_type, $is_IE, $is_edge, $fusion_library, $columns, $global_container_count;

			$fusion_fwc_type = array();

			$style      = '';
			$classes    = 'fusion-fullwidth fullwidth-box';
			$outer_html = '';
			$lazy_load  = $fusion_settings->get( 'lazy_load' );

			if ( ! $background_image || '' === $background_image ) {
				$lazy_load = false;
			}

			// Video background.
			$video_bg  = false;
			$video_src = '';

			// TODO: refactor this whole section.
			$c_page_id = $fusion_library->get_page_id();

			$width_100     = false;
			$page_template = '';

			// Placeholder background color.
			if ( false !== strpos( $background_image, 'https://placehold.it/' ) ) {
				$dimensions = str_replace( 'x', '', str_replace( 'https://placehold.it/', '', $background_image ) );
				if ( is_numeric( $dimensions ) ) {
					$background_image = $background_image . '/333333/ffffff/';
				}
			}
			if ( function_exists( 'is_woocommerce' ) ) {
				if ( is_woocommerce() ) {
					$custom_fields = get_post_custom_values( '_wp_page_template', $c_page_id );
					$page_template = ( is_array( $custom_fields ) && ! empty( $custom_fields ) ) ? $custom_fields[0] : '';
				}
			}

			$background_color = ( '' !== $overlay_color ) ? $fusion_library->sanitize->get_rgba( $overlay_color, $overlay_opacity ) : $background_color;

			$alpha = 1;
			if ( class_exists( 'Fusion_Color' ) ) {
				$alpha = Fusion_Color::new_color( $background_color )->alpha;
			}

			if ( 1 > $alpha && 0 !== $alpha ) {
				$classes .= ' fusion-blend-mode';
			}

			// @codingStandardsIgnoreLine
			if ( $is_IE || $is_edge ) {
				if ( 1 > $alpha ) {
					$classes .= ' fusion-ie-mode';
				}
			}

			if ( ! empty( $video_mp4 ) ) {
				$video_src .= '<source src="' . $video_mp4 . '" type="video/mp4">';
				$video_bg   = true;
			}

			if ( ! empty( $video_webm ) ) {
				$video_src .= '<source src="' . $video_webm . '" type="video/webm">';
				$video_bg   = true;
			}

			if ( ! empty( $video_ogv ) ) {
				$video_src .= '<source src="' . $video_ogv . '" type="video/ogg">';
				$video_bg   = true;
			}

			if ( ! empty( $video_url ) ) {
				$video_bg = true;
			}

			if ( true == $video_bg ) {

				$classes .= ' video-background';

				if ( ! empty( $video_url ) ) {
					$video_url = fusion_builder_get_video_provider( $video_url );

					if ( 'youtube' == $video_url['type'] ) {
						$outer_html .= "<div style='opacity:0;' class='fusion-background-video-wrapper' id='video-" . ( $parallax_id++ ) . "' data-youtube-video-id='" . $video_url['id'] . "' data-mute='" . $video_mute . "' data-loop='" . ( 'yes' == $video_loop ? 1 : 0 ) . "' data-loop-adjustment='" . $video_loop_refinement . "' data-video-aspect-ratio='" . $video_aspect_ratio . "'><div class='fusion-container-video-bg' id='video-" . ( $parallax_id++ ) . "-inner'></div></div>";
					} elseif ( 'vimeo' == $video_url['type'] ) {
						$outer_html .= '<div id="video-' . $parallax_id . '" class="fusion-background-video-wrapper" data-vimeo-video-id="' . $video_url['id'] . '" data-mute="' . $video_mute . '" data-video-aspect-ratio="' . $video_aspect_ratio . '" style="opacity:0;"><iframe id="video-iframe-' . $parallax_id . '" class="fusion-container-video-bg" src="//player.vimeo.com/video/' . $video_url['id'] . '?html5=1&autopause=0&autoplay=1&badge=0&byline=0&autopause=0&loop=' . ( 'yes' == $video_loop ? '1' : '0' ) . '&title=0' . ( 'yes' === $video_mute ? '&muted=1' : '' ) . '" frameborder="0"></iframe></div>';
					}
				} else {
					$video_attributes = 'preload="auto" autoplay';

					if ( 'yes' == $video_loop ) {
						$video_attributes .= ' loop';
					}

					if ( 'yes' == $video_mute ) {
						$video_attributes .= ' muted';
					}

					// Video Preview Image.
					if ( ! empty( $video_preview_image ) ) {
						$video_preview_image_style = 'background-image:url(' . $video_preview_image . ');';
						$outer_html               .= '<div class="fullwidth-video-image" style="' . $video_preview_image_style . '"></div>';
					}

					$outer_html .= '<div class="fullwidth-video"><video ' . $video_attributes . '>' . $video_src . '</video></div>';
				}

				// Video Overlay.
				if ( ! empty( $background_color ) && 1 > $alpha ) {
					$overlay_style = 'background-color:' . $background_color . ';';
					$outer_html   .= '<div class="fullwidth-overlay" style="' . $overlay_style . '"></div>';
				}
			}

			// Background.
			if ( ! empty( $background_color ) && ! ( 'yes' === $fade && ! empty( $background_image ) && false === $video_bg ) ) {
				$style .= 'background-color: ' . esc_attr( $background_color ) . ';';
			}

			if ( ! empty( $background_image ) && 'yes' !== $fade && ! $lazy_load ) {
				$style .= 'background-image: url("' . esc_url_raw( $background_image ) . '");';
			}

			if ( ! empty( $background_position ) ) {
				$style .= 'background-position: ' . esc_attr( $background_position ) . ';';
			}

			if ( ! empty( $background_repeat ) ) {
				$style .= 'background-repeat: ' . esc_attr( $background_repeat ) . ';';
			}

			// Get correct container padding.
			$paddings = array( 'top', 'right', 'bottom', 'left' );

			foreach ( $paddings as $padding ) {
				$padding_name = 'padding_' . $padding;

				if ( '' === ${$padding_name} ) {

					// TO padding.
					${$padding_name}             = $fusion_settings->get( 'container_padding_default', $padding );
					$is_hundred_percent_template = apply_filters( 'fusion_is_hundred_percent_template', false, $c_page_id );
					if ( $is_hundred_percent_template ) {
						${$padding_name} = $fusion_settings->get( 'container_padding_100', $padding );
					}
				}

				// Add padding to style.
				if ( ! empty( ${$padding_name} ) ) {
					$style .= 'padding-' . $padding . ':' . $fusion_library->sanitize->get_value_with_unit( ${$padding_name} ) . ';';
				}
			}

			// Margin; for separator conversion only.
			if ( ! empty( $margin_bottom ) ) {
				$style .= 'margin-bottom: ' . $fusion_library->sanitize->get_value_with_unit( $margin_bottom ) . ';';
			}

			if ( ! empty( $margin_top ) ) {
				$style .= 'margin-top: ' . $fusion_library->sanitize->get_value_with_unit( $margin_top ) . ';';
			}

			// Border.
			if ( ! empty( $border_size ) ) {
				$style .= 'border-top-width:' . esc_attr( FusionBuilder::validate_shortcode_attr_value( $border_size, 'px' ) ) . ';';
				$style .= 'border-bottom-width:' . esc_attr( FusionBuilder::validate_shortcode_attr_value( $border_size, 'px' ) ) . ';';
				$style .= 'border-color:' . esc_attr( $border_color ) . ';';
				$style .= 'border-top-style:' . esc_attr( $border_style ) . ';';
				$style .= 'border-bottom-style:' . esc_attr( $border_style ) . ';';
			}

			// Fading Background.
			if ( 'yes' === $fade && ! empty( $background_image ) && false === $video_bg ) {
				$bg_type    = 'faded';
				$fade_style = '';
				$classes   .= ' faded-background';

				if ( 'fixed' === $background_parallax ) {
					$fade_style .= 'background-attachment:' . $background_parallax . ';';
				}

				if ( $background_color ) {
					$fade_style .= 'background-color:' . $background_color . ';';
				}

				if ( $background_image && ! $lazy_load ) {
					$fade_style .= 'background-image: url(' . $background_image . ');';
				}

				if ( $background_position ) {
					$fade_style .= 'background-position:' . $background_position . ';';
				}

				if ( $background_repeat ) {
					$fade_style .= 'background-repeat:' . $background_repeat . ';';
				}

				if ( 'no-repeat' === $background_repeat ) {
					$fade_style .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
				}

				if ( ! $lazy_load ) {
					$outer_html .= '<div class="fullwidth-faded" style="' . $fade_style . '"></div>';
				} else {
					$outer_html .= '<div class="fullwidth-faded lazyload" style="' . $fade_style . '" data-bg="' . $background_image . '"></div>';
				}
			}

			if ( ! empty( $background_image ) && false == $video_bg ) {
				if ( 'no-repeat' == $background_repeat ) {
					$style .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
				}
			}

			// Parallax.
			$parallax_helper = '';
			if ( false === $video_bg && ! empty( $background_image ) ) {
				$parallax_data  = '';
				$parallax_data .= ' data-bg-align="' . esc_attr( $background_position ) . '"';
				$parallax_data .= ' data-direction="' . $background_parallax . '"';
				$parallax_data .= ' data-mute="' . ( 'mute' == $video_mute ? 'true' : 'false' ) . '"';
				$parallax_data .= ' data-opacity="' . esc_attr( $opacity ) . '"';
				$parallax_data .= ' data-velocity="' . esc_attr( (float) $parallax_speed * -1 ) . '"';
				$parallax_data .= ' data-mobile-enabled="' . ( ( 'yes' === $enable_mobile ) ? 'true' : 'false' ) . '"';
				$parallax_data .= ' data-break_parents="' . esc_attr( $break_parents ) . '"';
				$parallax_data .= ' data-bg-image="' . esc_attr( $background_image ) . '"';
				$parallax_data .= ' data-bg-repeat="' . esc_attr( isset( $background_repeat ) && 'no-repeat' != $background_repeat ? 'true' : 'false' ) . '"';
				if ( 1 > Fusion_Color::new_color( $background_color )->alpha ) {
					$parallax_data .= ' data-bg-color="' . esc_attr( $background_color ) . '"';
				}
				$parallax_data .= ' data-bg-height="' . esc_attr( $data_bg_height ) . '"';
				$parallax_data .= ' data-bg-width="' . esc_attr( $data_bg_width ) . '"';

				if ( 'none' !== $background_parallax && 'fixed' !== $background_parallax ) {
					$parallax_helper = '<div class="fusion-bg-parallax" ' . $parallax_data . '></div>';
				}

				// Parallax css class.
				if ( ! empty( $background_parallax ) ) {
					$classes .= " fusion-parallax-{$background_parallax}";
				}

				if ( 'fixed' === $background_parallax ) {
					$style .= 'background-attachment:' . $background_parallax . ';';
				}
			}

			// Custom CSS class.
			if ( ! empty( $class ) ) {
				$classes .= " {$class}";
			}

			if ( '100%' === $fusion_settings->get( 'site_width' ) ||
				is_page_template( '100-width.php' ) ||
				is_page_template( 'blank.php' ) ||
				( '1' == FusionBuilder::get_page_option( 'portfolio_width_100', 'portfolio_width_100', $c_page_id ) || 'yes' === FusionBuilder::get_page_option( 'portfolio_width_100', 'portfolio_width_100', $c_page_id ) && 'avada_portfolio' == get_post_type( $c_page_id ) ) ||
				'100-width.php' === $page_template ) {
				$width_100 = true;
			}

			// Hundred percent.
			$classes                             .= ( 'yes' === $hundred_percent ) ? ' hundred-percent-fullwidth' : ' nonhundred-percent-fullwidth';
			$fusion_fwc_type['content']           = ( 'yes' === $hundred_percent ) ? 'fullwidth' : 'contained';
			$fusion_fwc_type['width_100_percent'] = $width_100;
			$fusion_fwc_type['padding']           = array(
				'left'  => $padding_left,
				'right' => $padding_right,
			);

			// Hundred percent height.
			$scroll_section_container = $data_attr = $active_class = $css_id = '';

			if ( 'yes' === $hundred_percent_height ) {
				$classes .= ' hundred-percent-height';

				if ( 'yes' === $hundred_percent_height_center_content ) {
					$classes .= ' hundred-percent-height-center-content';
				}

				if ( 'yes' === $hundred_percent_height_scroll && ! $is_nested ) {

					if ( 1 === $this->scroll_section_element_counter ) {
						$this->scroll_section_counter++;
						$scroll_section_container = '<div id="fusion-scroll-section-' . $this->scroll_section_counter . '" class="fusion-scroll-section" data-section="' . $this->scroll_section_counter . '">';

						$active_class .= ' active';
					}

					$classes .= ' hundred-percent-height-scrolling';

					if ( '' !== $id ) {
						$css_id = $id;
					}
					$id        = 'fusion-scroll-section-element-' . $this->scroll_section_counter . '-' . $this->scroll_section_element_counter;
					$data_attr = ' data-section="' . $this->scroll_section_counter . '" data-element="' . $this->scroll_section_element_counter . '"';

					$this->scroll_section_navigation .= '<li><a href="#' . $id . '" class="fusion-scroll-section-link" data-name="' . $admin_label . '" data-element="' . $this->scroll_section_element_counter . '"><span class="fusion-scroll-section-link-bullet"></span></a></li>';

					$this->scroll_section_element_counter++;
				} else {
					$classes .= ' non-hundred-percent-height-scrolling';
				}
			} else {
				$classes .= ' non-hundred-percent-height-scrolling';
			}

			if ( ( $global_container_count === $this->container_counter || 'no' === $hundred_percent_height_scroll || 'no' === $hundred_percent_height ) && ! $is_nested ) {

				if ( 1 < $this->scroll_section_element_counter ) {
					$scroll_navigation_position = ( 'Right' === $fusion_settings->get( 'header_position' ) || is_rtl() ) ? 'scroll-navigation-left' : 'scroll-navigation-right';
					$scroll_section_container   = '<nav id="fusion-scroll-section-nav-' . $this->scroll_section_counter . '" class="fusion-scroll-section-nav ' . $scroll_navigation_position . '" data-section="' . $this->scroll_section_counter . '"><ul>' . $this->scroll_section_navigation . '</ul></nav>';
					$scroll_section_container  .= '</div>';
				}

				$this->scroll_section_element_counter = 1;
				$this->scroll_section_navigation      = '';
			}

			// Equal column height.
			if ( 'yes' === $equal_height_columns ) {
				$classes .= ' fusion-equal-height-columns';
			}

			// Visibility classes.
			if ( 'no' === $hundred_percent_height || 'no' === $hundred_percent_height_scroll ) {
				$classes = fusion_builder_visibility_atts( $hide_on_mobile, $classes );
			}

			$main_content = do_shortcode( fusion_builder_fix_shortcodes( $content ) );

			// Additional wrapper for content centering.
			if ( 'yes' === $hundred_percent_height && 'yes' === $hundred_percent_height_center_content ) {
				$main_content = '<div class="fusion-fullwidth-center-content">' . $main_content . '</div>';
			}

			// CSS inline style.
			$style = ! empty( $style ) ? " style='{$style}'" : '';

			// Custom CSS ID.
			$id = ( '' !== $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

			if ( $lazy_load ) {
				$classes .= ' lazyload';
				$style   .= ' data-bg="' . $background_image . '"';
			}
			$output = $parallax_helper . '<div' . $id . ' class="' . $classes . '" ' . $style . '>' . $outer_html . $main_content . '</div>';

			// Menu anchor.
			if ( ! empty( $menu_anchor ) ) {
				$output = '<div id="' . $menu_anchor . '">' . $output . '</div>';
			}

			if ( 'yes' === $hundred_percent_height_scroll && 'yes' === $hundred_percent_height && ! $is_nested ) {

				// Custom CSS ID.
				$css_id = ( '' !== $css_id ) ? ' id="' . esc_attr( $css_id ) . '"' : '';

				$output = '<div' . $css_id . ' class="fusion-scroll-section-element' . $active_class . '"' . $data_attr . '>' . $output . '</div>';
			}

			if ( $global_container_count === $this->container_counter && 'yes' === $hundred_percent_height_scroll && 'yes' === $hundred_percent_height && ! $is_nested ) {
				$output = $output . $scroll_section_container;
			} else {
				$output = $scroll_section_container . $output;
			}

			$fusion_fwc_type = array();
			$columns         = 0;

			if ( ! $is_nested ) {
				$this->container_counter++;
			}

			return $output;
		}

		/**
		 * Check if container should render.
		 *
		 * @access public
		 * @since 1.7
		 * @return array
		 */
		public function is_container_viewable() {

			// Published, all can see.
			if ( 'published' === $this->args['status'] || '' === $this->args['status'] ) {
				return true;
			}

			// If is author, can also see.
			if ( is_user_logged_in() && current_user_can( 'publish_posts' ) ) {
				return true;
			}

			// Set to hide.
			if ( 'draft' === $this->args['status'] ) {
				return false;
			}

			// Set to show until or after.
			$time_check    = strtotime( $this->args['publish_date'] );
			$wp_local_time = current_time( 'timestamp' );
			if ( '' !== $this->args['publish_date'] && $time_check ) {
				if ( 'published_until' === $this->args['status'] ) {
					return $wp_local_time < $time_check;
				}
				if ( 'publish_after' === $this->args['status'] ) {
					return $wp_local_time > $time_check;
				}
			}

			// Any incorrect set-up default to show.
			return true;
		}

		/**
		 * Builds the dynamic styling.
		 *
		 * @access public
		 * @since 1.1
		 * @return array
		 */
		public function add_styling() {
			global $fusion_library, $fusion_settings;

			$css['global']['.fusion-builder-row.fusion-row']['max-width'] = $fusion_library->sanitize->size( $fusion_settings->get( 'site_width' ) );

			$css['global']['.fusion-scroll-section-nav']['background-color']         = $fusion_library->sanitize->color( $fusion_settings->get( 'container_scroll_nav_bg_color' ) );
			$css['global']['.fusion-scroll-section-link-bullet']['background-color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'container_scroll_nav_bullet_color' ) );

			return $css;
		}

		/**
		 * Adds settings to element options panel.
		 *
		 * @access public
		 * @since 1.1
		 * @return array $sections Column settings.
		 */
		public function add_options() {

			return array(
				'container_shortcode_section' => array(
					'label'       => esc_html__( 'Container Element', 'fusion-builder' ),
					'description' => '',
					'id'          => 'fullwidth_shortcode_section',
					'type'        => 'accordion',
					'fields'      => array(
						'container_padding_default'     => array(
							'label'       => esc_html__( 'Container Padding for Default Template', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the top/right/bottom/left padding of the container element when using the Default page template. ', 'fusion-builder' ),
							'id'          => 'container_padding_default',
							'choices'     => array(
								'top'    => true,
								'bottom' => true,
								'left'   => true,
								'right'  => true,
								'units'  => array( 'px', '%' ),
							),
							'default'     => array(
								'top'    => '0px',
								'bottom' => '0px',
								'left'   => '0px',
								'right'  => '0px',
							),
							'type'        => 'spacing',
						),
						'container_padding_100'         => array(
							'label'       => esc_html__( 'Container Padding for 100% Width Template', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the top/right/bottom/left padding of the container element when using the 100% width page template.', 'fusion-builder' ),
							'id'          => 'container_padding_100',
							'choices'     => array(
								'top'    => true,
								'bottom' => true,
								'left'   => true,
								'right'  => true,
								'units'  => array( 'px', '%' ),
							),
							'default'     => array(
								'top'    => '0px',
								'bottom' => '0px',
								'left'   => '30px',
								'right'  => '30px',
							),
							'type'        => 'spacing',
						),
						'full_width_bg_color'           => array(
							'label'       => esc_html__( 'Container Background Color', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the background color of the container element.', 'fusion-builder' ),
							'id'          => 'full_width_bg_color',
							'default'     => 'rgba(255,255,255,0)',
							'type'        => 'color-alpha',
						),
						'full_width_border_size'        => array(
							'label'       => esc_html__( 'Container Border Size', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the top and bottom border size of the container element.', 'fusion-builder' ),
							'id'          => 'full_width_border_size',
							'default'     => '0',
							'type'        => 'slider',
							'choices'     => array(
								'min'  => '0',
								'max'  => '50',
								'step' => '1',
							),
						),
						'full_width_border_color'       => array(
							'label'       => esc_html__( 'Container Border Color', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the border color of the container element.', 'fusion-builder' ),
							'id'          => 'full_width_border_color',
							'default'     => '#eae9e9',
							'type'        => 'color-alpha',
						),
						'container_scroll_nav_bg_color' => array(
							'label'       => esc_html__( 'Container 100% Height Navigation Background Color', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the background colors of the navigation area and name box when using 100% height containers.', 'fusion-builder' ),
							'id'          => 'container_scroll_nav_bg_color',
							'default'     => 'rgba(0, 0, 0, 0.2)',
							'type'        => 'color-alpha',
						),
						'container_scroll_nav_bullet_color' => array(
							'label'       => esc_html__( 'Container 100% Height Navigation Element Color', 'fusion-builder' ),
							'description' => esc_html__( 'Controls the color of the navigation circles and text name when using 100% height containers.', 'fusion-builder' ),
							'id'          => 'container_scroll_nav_bullet_color',
							'default'     => '#eeeeee',
							'type'        => 'color-alpha',
						),
						'container_hundred_percent_height_mobile' => array(
							'label'       => esc_html__( 'Container 100% Height On Mobile', 'fusion-builder' ),
							'description' => esc_html__( 'Turn on to enable the 100% height containers on mobile. Please note, this feature only works when your containers have minimal content. If the container has a lot of content it will overflow the screen height. In many cases, 100% height containers work well on desktop, but will need disabled on mobile.', 'fusion-builder' ),
							'id'          => 'container_hundred_percent_height_mobile',
							'default'     => '0',
							'type'        => 'switch',
						),
					),
				),
			);
		}

		/**
		 * Sets the necessary scripts.
		 *
		 * @access public
		 * @since 1.1
		 * @return void
		 */
		public function add_scripts() {
			global $fusion_library, $fusion_settings;

			if ( ! $fusion_settings ) {
				$fusion_settings = Fusion_Settings::get_instance();
			}

			$is_sticky_header_transparent = 0;
			$c_page_id                    = $fusion_library->get_page_id();
			if ( 1 > Fusion_Color::new_color( $fusion_settings->get( 'header_sticky_bg_color' ) )->alpha ) {
				$is_sticky_header_transparent = 1;
			}

			Fusion_Dynamic_JS::enqueue_script(
				'fusion-container',
				FusionBuilder::$js_folder_url . '/general/fusion-container.js',
				FusionBuilder::$js_folder_path . '/general/fusion-container.js',
				array( 'jquery', 'modernizr', 'fusion-animations', 'jquery-fade', 'fusion-parallax', 'fusion-video-general', 'fusion-video-bg' ),
				'1',
				true
			);
			Fusion_Dynamic_JS::localize_script(
				'fusion-container',
				'fusionContainerVars',
				array(
					'content_break_point'          => intval( $fusion_settings->get( 'content_break_point' ) ),
					'container_hundred_percent_height_mobile' => intval( $fusion_settings->get( 'container_hundred_percent_height_mobile' ) ),
					'is_sticky_header_transparent' => $is_sticky_header_transparent,
				)
			);
		}
	}
}

remove_shortcode('fusion_builder_container');

/**
 * Instantiates the container class.
 *
 * @return object FusionSC_Container
 */
function amp_fusion_builder_container() { // phpcs:ignore WordPress.NamingConventions
	return AMP_PC_FusionSC_Container::get_instance();
}

// Instantiate container.
amp_fusion_builder_container();

/**
 * Map Column shortcode to Avada Builder.
 *
 * @since 1.0
 */