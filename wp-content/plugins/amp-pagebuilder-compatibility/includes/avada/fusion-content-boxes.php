<?php

global $amp_avada_custom_css;
if ( fusion_is_element_enabled( 'fusion_content_boxes' ) ) {

	if ( ! class_exists( 'AMP_PC_FusionSC_ContentBoxes' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @package fusion-builder
		 * @since 1.0
		 */
		class AMP_PC_FusionSC_ContentBoxes extends Fusion_Element {

			/**
			 * Content box counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $content_box_counter = 1;

			/**
			 * Columns counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $column_counter = 1;

			/**
			 * Number of columns.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $num_of_columns = 1;

			/**
			 * Total number of columns.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $total_num_of_columns = 1;

			/**
			 * Rows counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $row_counter = 1;

			/**
			 * Parent SC arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $parent_args;

			/**
			 * Transparent child.
			 *
			 * @access protected
			 * @since 1.1.6
			 * @var bool
			 */
			protected $transparent_child = false;

			/**
			 * Child SC arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $child_args;

			/**
			 * Constructor.
			 *
			 * @access public
			 * @since 1.0
			 * @since 1.0
			 */
			public $amp_icon_styles = '';
			public $amp_content_box_heading = '';
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_content-box-shortcode', array( $this, 'child_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-content-wrapper', array( $this, 'content_wrapper_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-heading-wrapper', array( $this, 'heading_wrapper_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-content-container', array( $this, 'content_container_attr' ) );

				add_filter( 'fusion_attr_content-box-shortcode-link', array( $this, 'link_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-icon-parent', array( $this, 'icon_parent_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-icon-wrapper', array( $this, 'icon_wrapper_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-icon', array( $this, 'icon_attr' ) );
				add_filter( 'fusion_attr_content-box-shortcode-timeline', array( $this, 'timeline_attr' ) );
				add_filter( 'fusion_attr_content-box-heading', array( $this, 'content_box_heading_attr' ) );
				add_shortcode( 'fusion_content_box', array( $this, 'render_child' ) );
				add_action( 'amp_post_template_css', array( $this, 'amp_pc_conten_box_styles' ) );

				add_filter( 'fusion_attr_content-boxes-shortcode', array( $this, 'parent_attr' ) );
				add_shortcode( 'fusion_content_boxes', array( $this, 'render_parent' ) );

			}
			function amp_pc_conten_box_styles(){
				//echo $this->boxStyle;
				echo $amp_avada_custom_css;
				echo $this->amp_icon_styles;
				echo $this->amp_content_box_heading;
			}
			/**
			 * Render the shortcode.
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render_parent( $args, $content = '' ) {

				global $fusion_library, $fusion_settings, $amp_avada_custom_css;
				if ( ! $fusion_settings ) {
					$fusion_settings = Fusion_Settings::get_instance();
				}

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'hide_on_mobile'         => fusion_builder_default_visibility( 'string' ),
						'class'                  => '',
						'id'                     => '',
						'backgroundcolor'        => $fusion_settings->get( 'content_box_bg_color' ),
						'columns'                => '',
						'circle'                 => '',
						'heading_size'           => '2',
						'icon'                   => '',
						'iconflip'               => '',
						'iconrotate'             => '',
						'iconspin'               => '',
						'iconcolor'              => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_icon_color' ) ),
						'circlecolor'            => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_icon_bg_color' ) ),
						'circlebordercolor'      => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_icon_bg_inner_border_color' ) ),
						'circlebordersize'       => intval( $fusion_settings->get( 'content_box_icon_bg_inner_border_size' ) ) . 'px',
						'outercirclebordercolor' => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_icon_bg_outer_border_color' ) ),
						'outercirclebordersize'  => intval( $fusion_settings->get( 'content_box_icon_bg_outer_border_size' ) ) . 'px',
						'icon_circle'            => $fusion_settings->get( 'content_box_icon_circle' ),
						'icon_circle_radius'     => $fusion_settings->get( 'content_box_icon_circle_radius' ),
						'icon_size'              => $fusion_library->sanitize->size( $fusion_settings->get( 'content_box_icon_size' ) ),
						'icon_align'             => '',
						'icon_hover_type'        => $fusion_settings->get( 'content_box_icon_hover_type' ),
						'hover_accent_color'     => ( '' !== $fusion_settings->get( 'content_box_hover_animation_accent_color' ) ) ? $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_hover_animation_accent_color' ) ) : $fusion_library->sanitize->color( $fusion_settings->get( 'primary_color' ) ),
						'image'                  => '',
						'image_id'               => '',
						'image_max_width'        => '',
						'layout'                 => 'icon-with-title',
						'margin_top'             => $fusion_settings->get( 'content_box_margin', 'top' ),
						'margin_bottom'          => $fusion_settings->get( 'content_box_margin', 'bottom' ),
						'title_size'             => $fusion_library->sanitize->size( $fusion_settings->get( 'content_box_title_size' ) ),
						'title_color'            => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_title_color' ) ),
						'body_color'             => $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_body_color' ) ),
						'link_type'              => $fusion_settings->get( 'content_box_link_type' ),
						'button_span'            => $fusion_settings->get( 'content_box_button_span' ),
						'link_area'              => $fusion_settings->get( 'content_box_link_area' ),
						'link_target'            => $fusion_settings->get( 'content_box_link_target' ),
						'animation_type'         => '',
						'animation_delay'        => '',
						'animation_direction'    => 'left',
						'animation_speed'        => '0.1',
						'animation_offset'       => $fusion_settings->get( 'animation_offset' ),
						'settings_lvl'           => 'child',
						'linktarget'             => '', // Deprecated.
					),
					$args,
					'fusion_content_boxes'
				);

				// Backwards compatibility for when we had image width and height params.
				if ( isset( $args['image_width'] ) ) {
					$defaults['image_width'] = ( $args['image_width'] ) ? $args['image_width'] : '35';
				} else {
					$defaults['image_width'] = $defaults['image_max_width'];
				}

				$defaults['title_size']            = ( $defaults['title_size'] ) ? (float) fusion_library()->sanitize->number( $defaults['title_size'] ) : (float) fusion_library()->sanitize->number( $fusion_settings->get( 'content_box_title_size' ) );
				$defaults['icon_circle_radius']    = FusionBuilder::validate_shortcode_attr_value( $defaults['icon_circle_radius'], 'px' );
				$defaults['icon_size']             = FusionBuilder::validate_shortcode_attr_value( $defaults['icon_size'], 'px' );
				$defaults['margin_top']            = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_top'], 'px' );
				$defaults['margin_bottom']         = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_bottom'], 'px' );
				$defaults['margin_bottom']         = FusionBuilder::validate_shortcode_attr_value( $defaults['margin_bottom'], 'px' );
				$defaults['circlebordersize']      = FusionBuilder::validate_shortcode_attr_value( $defaults['circlebordersize'], 'px' );
				$defaults['outercirclebordersize'] = FusionBuilder::validate_shortcode_attr_value( $defaults['outercirclebordersize'], 'px' );

				if ( $defaults['linktarget'] ) {
					$defaults['link_target'] = $defaults['linktarget'];
				}

				if ( 'timeline-vertical' === $defaults['layout'] ) {
					$defaults['columns'] = 1;
				}

				if ( 'timeline-vertical' === $defaults['layout'] || 'timeline-horizontal' === $defaults['layout'] ) { // See #1362.
					$defaults['animation_delay']     = 350;
					$defaults['animation_speed']     = 0.25;
					$defaults['animation_type']      = 'fade';
					$defaults['animation_direction'] = '';
				}

				extract( $defaults );

				$this->parent_args = $defaults;

				$this->column_counter    = 1;
				$this->row_counter       = 1;
				$this->transparent_child = false;

				preg_match_all( '/\[fusion_content_box (.*?)\]/s', $content, $matches );

				if ( is_array( $matches ) && ! empty( $matches ) ) {
					$this->total_num_of_columns = count( $matches[0] );
				}

				$this->num_of_columns = $columns;
				if ( ! $columns || empty( $columns ) ) {
					$this->num_of_columns = $this->total_num_of_columns;
					$this->num_of_columns = max( 6, $this->num_of_columns );
				} elseif ( $columns > 6 ) {
					$this->num_of_columns = 6;
				}

				$styles = ''; //'<style type="text/css" scoped="scoped">';

				if ( $title_color ) {
					$styles .= ".fusion-content-boxes-{$this->content_box_counter} .heading .content-box-heading {color:{$title_color};}";
				}

				$styles .= "
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .heading .content-box-heading,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .heading .heading-link .content-box-heading,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .heading .content-box-heading,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .heading .heading-link .content-box-heading,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover.link-area-box .fusion-read-more,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover.link-area-box .fusion-read-more::after,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover.link-area-box .fusion-read-more::before,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .fusion-read-more:hover:after,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .fusion-read-more:hover:before,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .fusion-read-more:hover,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover.link-area-box .fusion-read-more,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover.link-area-box .fusion-read-more::after,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover.link-area-box .fusion-read-more::before,
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .icon .circle-no,
					.fusion-content-boxes-{$this->content_box_counter} .heading .heading-link:hover .content-box-heading {
						color: {$hover_accent_color};
					}";

				$styles .= "
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .icon .circle-no {
						color: {$hover_accent_color} !important;
					}";

				$styles .= ".fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box.link-area-box-hover .fusion-content-box-button {";
				$styles .= 'background: ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) ) . ';';
				$styles .= 'color: ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_hover_color' ) ) . ';';
				if ( $fusion_settings->get( 'button_gradient_top_color_hover' ) !== $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) {
					$styles .= 'background-image: -webkit-gradient( linear, left bottom, left top, from( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) . ' ), to( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) ) . ' ) );';
					$styles .= 'background-image: linear-gradient( to top, ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) . ', ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) ) . ' )';
				}
				$styles .= '}';
				$styles .= ".fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box.link-area-box-hover .fusion-content-box-button .fusion-button-text {";
				$styles .= 'color: ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_hover_color' ) ) . ';';
				$styles .= '}';

				$circle_hover_accent_color = $hover_accent_color;
				if ( 'transparent' === $circlecolor || 0 === Fusion_Color::new_color( $circlecolor )->alpha ) {
					$circle_hover_accent_color = 'transparent';
				}

				$styles .= "
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .heading .icon > span {
						background-color: {$circle_hover_accent_color} !important;
					}";

				$styles .= "
					.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .heading .icon > span {
						border-color: {$hover_accent_color} !important;
					}";

				if ( 'pulsate' === $icon_hover_type && $hover_accent_color ) {

					$styles .= "
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover.icon-hover-animation-pulsate .fontawesome-icon:after,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover.icon-hover-animation-pulsate .fontawesome-icon:after,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover.icon-wrapper-hover-animation-pulsate .icon span:after,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover.icon-wrapper-hover-animation-pulsate .icon span:after {
							-webkit-box-shadow:0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px {$hover_accent_color}, 0 0 0 10px rgba(255,255,255,0.5);
							-moz-box-shadow:0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px {$hover_accent_color}, 0 0 0 10px rgba(255,255,255,0.5);
							box-shadow: 0 0 0 2px rgba(255,255,255,0.1), 0 0 10px 10px {$hover_accent_color}, 0 0 0 10px rgba(255,255,255,0.5);
						}
					";
				}

				$amp_avada_custom_css['fusion-content-boxes'][$this->content_box_counter] = $styles;
				//$styles .= '</style>';

				$html  = '<div ' . FusionBuilder::attributes( 'content-boxes-shortcode' ) . '>';
				$html .= do_shortcode( $content );
				$html .= '<div class="fusion-clearfix"></div></div>';

				$this->content_box_counter++;

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function parent_attr() {

				$attr = array(
					'class' => '',
				);

				$attr['class']  = 'fusion-content-boxes content-boxes columns row';
				$attr['class'] .= ' fusion-columns-' . $this->num_of_columns;
				$attr['class'] .= ' fusion-columns-total-' . $this->total_num_of_columns;
				$attr['class'] .= ' fusion-content-boxes-' . $this->content_box_counter;
				$attr['class'] .= ' content-boxes-' . $this->parent_args['layout'];
				$attr['class'] .= ' content-' . $this->parent_args['icon_align'];

				$attr = fusion_builder_visibility_atts( $this->parent_args['hide_on_mobile'], $attr );

				if ( 'timeline-horizontal' === $this->parent_args['layout'] || 'clean-vertical' === $this->parent_args['layout'] ) {
					$attr['class'] .= ' content-boxes-icon-on-top';
				}

				if ( 'timeline-vertical' === $this->parent_args['layout'] ) {
					$attr['class'] .= ' content-boxes-icon-with-title';
				}

				if ( 'clean-horizontal' === $this->parent_args['layout'] ) {
					$attr['class'] .= ' content-boxes-icon-on-side';
				}

				if ( $this->parent_args['class'] ) {
					$attr['class'] .= ' ' . $this->parent_args['class'];
				}

				if ( $this->parent_args['id'] ) {
					$attr['id'] = $this->parent_args['id'];
				}

				if ( $this->parent_args['animation_delay'] ) {
					$attr['data-animation-delay'] = $this->parent_args['animation_delay'];
					$attr['class']               .= ' fusion-delayed-animation';
				}

				if ( $this->parent_args['animation_offset'] ) {
					$animations = FusionBuilder::animations(
						array(
							'offset' => $this->parent_args['animation_offset'],
						)
					);

					$attr = array_merge( $attr, $animations );
				}

				$attr['style'] = 'margin-top:' . $this->parent_args['margin_top'] . ';margin-bottom:' . $this->parent_args['margin_bottom'] . ';';

				return $attr;

			}

			/**
			 * Render the child shortcode.
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args    Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string          HTML output.
			 */
			public function render_child( $args, $content = '' ) {
				global $fusion_library,$amp_avada_custom_css;

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(
						'class'                  => '',
						'id'                     => '',
						'backgroundcolor'        => $this->parent_args['backgroundcolor'],
						'circle'                 => '',
						'circlecolor'            => $this->parent_args['circlecolor'],
						'circlebordercolor'      => $this->parent_args['circlebordercolor'],
						'circlebordersize'       => $this->parent_args['circlebordersize'],
						'heading_size'           => $this->parent_args['heading_size'],
						'outercirclebordercolor' => $this->parent_args['outercirclebordercolor'],
						'outercirclebordersize'  => $this->parent_args['outercirclebordersize'],
						'icon'                   => $this->parent_args['icon'],
						'iconcolor'              => $this->parent_args['iconcolor'],
						'iconflip'               => $this->parent_args['iconflip'],
						'iconrotate'             => $this->parent_args['iconrotate'],
						'iconspin'               => $this->parent_args['iconspin'],
						'image'                  => $this->parent_args['image'],
						'image_id'               => $this->parent_args['image_id'],
						'image_max_width'        => $this->parent_args['image_max_width'],
						'link'                   => '',
						'link_target'            => $this->parent_args['link_target'],
						'linktext'               => '',
						'textcolor'              => '',
						'title'                  => '',
						'animation_type'         => $this->parent_args['animation_type'],
						'animation_direction'    => $this->parent_args['animation_direction'],
						'animation_speed'        => $this->parent_args['animation_speed'],
						'animation_offset'       => $this->parent_args['animation_offset'],
						'linktarget'             => '', // Deprecated.
					),
					$args,
					'fusion_content_box'
				);

				// Case when image is set on parent element and icon on child element.
				if ( empty( $args['image'] ) && ! empty( $args['icon'] ) ) {
					$defaults['image'] = '';
				}

				// Backwards compatibility for when we had image width and height params.
				if ( isset( $args['image_width'] ) && $args['image_width'] ) {
					$defaults['image_width'] = $args['image_width'];
				} else {
					$defaults['image_width'] = $defaults['image_max_width'];
				}

				$defaults['image_width'] = FusionBuilder::validate_shortcode_attr_value( $defaults['image_width'], '' );
				
				if ( $defaults['image'] ) {
					$image_data = $fusion_library->images->get_attachment_data_by_helper( $defaults['image_id'], $defaults['image'] );
					$image_width  = $image_data['width'];
					$image_height = $image_data['height'];

					if ( '-1' === $defaults['image_width'] || '' === $defaults['image_width'] ) {
						$defaults['image_width']  = ( $image_width ) ? $image_width : '35';
					}

					$defaults['image_height'] = ( $image_width ) ? round( $defaults['image_width'] / $image_width * $image_height, 2 ) : '35';

				} else {
					$defaults['image_width']  = '' === $defaults['image_width'] ? '35' : $defaults['image_width'];
					$defaults['image_height'] = '35';
				}				

				if ( $defaults['linktarget'] ) {
					$defaults['link_target'] = $defaults['linktarget'];
				}

				if ( 'timeline-vertical' === $this->parent_args['layout'] || 'timeline-horizontal' === $this->parent_args['layout'] ) {
					$defaults['animation_speed']     = 0.25;
					$defaults['animation_type']      = 'fade';
					$defaults['animation_direction'] = '';
				}

				// This block is needed for legacy setups, #494.
				if ( 'parent' === $this->parent_args['settings_lvl'] ) {
					$defaults['backgroundcolor']        = $this->parent_args['backgroundcolor'];
					$defaults['circlecolor']            = $this->parent_args['circlecolor'];
					$defaults['circlebordercolor']      = $this->parent_args['circlebordercolor'];
					$defaults['circlebordersize']       = $this->parent_args['circlebordersize'];
					$defaults['outercirclebordercolor'] = $this->parent_args['outercirclebordercolor'];
					$defaults['outercirclebordersize']  = $this->parent_args['outercirclebordersize'];
					$defaults['iconcolor']              = $this->parent_args['iconcolor'];
					$defaults['animation_type']         = $this->parent_args['animation_type'];
					$defaults['animation_direction']    = $this->parent_args['animation_direction'];
					$defaults['animation_speed']        = $this->parent_args['animation_speed'];
					$defaults['link_target']            = $this->parent_args['link_target'];
				}

				// Make sure original child animation type is accessible, for later check on column-wrapper attributes.
				$defaults['animation_type_original'] = ( isset( $args['animation_type'] ) ) ? $args['animation_type'] : '';

				extract( $defaults );

				$this->child_args = $defaults;

				$output         = '';
				$icon_output    = '';
				$title_output   = '';
				$content_output = '';
				$link_output    = '';
				$alt            = '';
				$heading        = '';

				if ( $image && $image_width && $image_height ) {

					$image_data = $fusion_library->images->get_attachment_data_by_helper( $this->child_args['image_id'], $image );

					$alt = $image_data['alt'];

					if ( $image_data['url'] ) {
						$image = $image_data['url'];
					}

					$image = '<img src="' . $image . '" width="' . $image_width . '" height="' . $image_height . '" alt="' . $alt . '" />';
					$image = $fusion_library->images->apply_lazy_loading( $image, null, $this->child_args['image_id'], 'full' );

					$icon_output  = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-icon' ) . '>';
					$icon_output .= $image;
					$icon_output .= '</div>';
				} elseif ( $icon ) {
					$icon_output  = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-icon-parent' ) . '>';
					$icon_output .= '<i ' . FusionBuilder::attributes( 'content-box-shortcode-icon' ) . '></i>';
					$icon_output .= '</div>';
					if ( $outercirclebordercolor && $outercirclebordersize && intval( $outercirclebordersize ) ) {
						$icon_output  = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-icon-parent' ) . '>';
						$icon_output .= '<span ' . FusionBuilder::attributes( 'content-box-shortcode-icon-wrapper' ) . '>';
						$icon_output .= '<i ' . FusionBuilder::attributes( 'content-box-shortcode-icon' ) . '></i>';
						$icon_output .= '</span></div>';
					}
				}

				if ( $title ) {
					$title_output = '<h' . $heading_size . ' ' . FusionBuilder::attributes( 'content-box-heading' ) . '>' . $title . '</h' . $heading_size . '>';
				}

				if ( 'right' === $this->parent_args['icon_align'] && in_array( $this->parent_args['layout'], array( 'icon-on-side', 'icon-with-title', 'timeline-vertical', 'clean-horizontal' ), true ) ) {
					$heading_content = $title_output . $icon_output;
				} else {
					$heading_content = $icon_output . $title_output;
				}

				if ( $link ) {
					$heading_content = '<a ' . FusionBuilder::attributes( 'content-box-shortcode-link', array( 'heading_link' => true ) ) . '>' . $heading_content . '</a>';
				}

				if ( $heading_content ) {
					$heading = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-heading-wrapper' ) . '>' . $heading_content . '</div>';
				}

				if ( $link && $linktext ) {
					if ( 'text' === $this->parent_args['link_type'] || 'button-bar' === $this->parent_args['link_type'] ) {
						$link_output  = '<div class="fusion-clearfix"></div>';
						$link_output .= '<a ' . FusionBuilder::attributes( 'fusion-read-more' ) . ' ' . FusionBuilder::attributes( 'content-box-shortcode-link', array( 'readmore' => true ) ) . '>' . $linktext . '</a>';
						$link_output .= '<div class="fusion-clearfix"></div>';
					} elseif ( 'button' === $this->parent_args['link_type'] ) {
						$link_output  = '<div class="fusion-clearfix"></div>';
						$link_output .= '<a ' . FusionBuilder::attributes( 'content-box-shortcode-link', array( 'readmore' => true ) ) . '><span class="fusion-button-text">' . $linktext . '</span></a>';
						$link_output .= '<div class="fusion-clearfix"></div>';
					}
				}

				if ( $content ) {
					$content_output .= '<div class="fusion-clearfix"></div>';
					$content_output .= '<div ' . FusionBuilder::attributes( 'content-box-shortcode-content-container' ) . '>' . do_shortcode( $content ) . '</div>';
				}

				$output   = $heading . $content_output . $link_output;
				$timeline = '';

				if ( $icon && 'yes' === $this->parent_args['icon_circle'] && 'timeline-horizontal' === $this->parent_args['layout'] && '1' != $this->parent_args['columns'] ) {
					$timeline = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-timeline' ) . '></div>';
				}

				if ( $icon && 'yes' === $this->parent_args['icon_circle'] && 'timeline-vertical' === $this->parent_args['layout'] ) {
					$timeline = '<div ' . FusionBuilder::attributes( 'content-box-shortcode-timeline' ) . '></div>';
				}

				$html  = '<div ' . FusionBuilder::attributes( 'content-box-shortcode' ) . '>';
				$html .= '<div ' . FusionBuilder::attributes( 'content-box-shortcode-content-wrapper' ) . '>' . $output . $timeline . '</div>';
				$html .= '</div>';

				$this->column_counter++;

				if ( 'transparent' === $circlecolor || 0 === Fusion_Color::new_color( $circlecolor )->alpha ) {
					$this->transparent_child = true;
				}
				$styles = '';
				$icon_css = '';
				$icon_styles = FusionBuilder::attributes( 'content-box-shortcode-icon' );
				preg_match('/style="(.*?)"/', $icon_styles, $matches);
				$icon_css = $matches[1];

				if ( ( 1 + $this->total_num_of_columns ) === $this->column_counter && true === $this->transparent_child ) {
					$hover_accent_color        = $this->parent_args['hover_accent_color'];
					$styles                    = ''; //'<style type="text/css" scoped="scoped">';
					$circle_hover_accent_color = $hover_accent_color;

					if ( 'transparent' === $circlecolor || 0 === Fusion_Color::new_color( $circlecolor )->alpha ) {
						$circle_hover_accent_color = 'transparent';
					}

					$styles     .= "
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .heading-link:hover .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box:hover .heading-link .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .heading .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .heading .icon i.circle-yes {
							background-color: {$circle_hover_accent_color} !important;
							border-color: {$hover_accent_color} !important;
						}";
						//$styles .= '</style>';
					$styles .= ".fusion-content-boxes-{$this->content_box_counter} .icon i{{$icon_css}}";
						//$html .= $styles;

				} elseif ( ( 1 + $this->total_num_of_columns ) === $this->column_counter && false === $this->transparent_child ) {
					$hover_accent_color = $this->parent_args['hover_accent_color'];
					//$styles             = '<style type="text/css" scoped="scoped">';

					$styles     .= "
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .heading-link:hover .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box:hover .heading-link .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-link-icon-hover .heading .icon i.circle-yes,
						.fusion-content-boxes-{$this->content_box_counter} .fusion-content-box-hover .link-area-box-hover .heading .icon i.circle-yes {
							background-color: {$hover_accent_color} !important;
							border-color: {$hover_accent_color} !important;
						}";
						$styles .= ".fusion-content-boxes-{$this->content_box_counter} .icon i{{$icon_css}}";
						//$styles .= '</style>';

						//$html .= $styles;

				}

				$amp_avada_custom_css['fusion-content-box-heading'][$this->content_box_counter] = $styles.' '.$heading_styles;

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function child_attr() {

				$columns = 12 / $this->num_of_columns;

				if ( $this->row_counter > intval( $this->num_of_columns ) ) {
					$this->row_counter = 1;
				}

				$attr           = array(
					'style' => '',
					'class' => 'fusion-column content-box-column',
				);
				$attr['class'] .= ' content-box-column content-box-column-' . $this->column_counter . ' col-lg-' . $columns . ' col-md-' . $columns . ' col-sm-' . $columns;

				if ( '5' == $this->num_of_columns ) {
					$attr['class'] = 'fusion-column content-box-column content-box-column-' . $this->column_counter . ' col-lg-2 col-md-2 col-sm-2';
				}

				$attr['class'] .= ' fusion-content-box-hover ';

				$border_color = '';

				if ( $this->child_args['circlebordercolor'] ) {
					$border_color = $this->child_args['circlebordercolor'];
				}

				if ( $this->child_args['outercirclebordercolor'] ) {
					$border_color = $this->child_args['outercirclebordercolor'];
				}

				if ( ! $this->child_args['circlebordercolor'] && ! $this->child_args['outercirclebordercolor'] ) {
					$border_color = '#f6f6f6';
				}

				if ( 1 === $this->column_counter % $this->num_of_columns ) {
					$attr['class'] .= ' content-box-column-first-in-row';
				}

				if ( $this->column_counter === $this->total_num_of_columns ) {
					$attr['class'] .= ' content-box-column-last';
				}

				if ( $this->num_of_columns === $this->row_counter ) {
					$attr['class'] .= ' content-box-column-last-in-row';
				}

				if ( $border_color && in_array( $this->parent_args['layout'], array( 'clean-vertical', 'clean-horizontal' ), true ) ) {
					$attr['style'] .= 'border-color:' . $border_color . ';';
				}

				if ( $this->child_args['class'] ) {
					$attr['class'] .= ' ' . $this->child_args['class'];
				}

				if ( $this->child_args['id'] ) {
					$attr['id'] = $this->child_args['id'];
				}

				$this->row_counter++;

				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function content_wrapper_attr() {

				$attr = array(
					'class' => 'col content-box-wrapper content-wrapper',
				);

				// Set parent values if child values are unset to get downwards compatibility.
				if ( ! $this->child_args['backgroundcolor'] ) {
					$this->child_args['backgroundcolor'] = $this->parent_args['backgroundcolor'];
				}

				if ( $this->child_args['backgroundcolor'] ) {
					$attr['style'] = 'background-color:' . $this->child_args['backgroundcolor'] . ';';

					if ( 'transparent' !== $this->child_args['backgroundcolor'] && '0' != Fusion_Color::new_color( $this->child_args['backgroundcolor'] )->alpha ) {
						$attr['class'] .= '-background';
					}
				}

				if ( 'icon-boxed' === $this->parent_args['layout'] ) {
					$attr['class'] .= ' content-wrapper-boxed';
				}

				if ( $this->child_args['link'] && 'box' === $this->parent_args['link_area'] ) {
					$attr['data-link'] = $this->child_args['link'];

					$attr['data-link-target'] = $this->child_args['link_target'];
				}

				$attr['class'] .= ' link-area-' . $this->parent_args['link_area'];

				if ( $this->child_args['link'] && $this->parent_args['link_type'] ) {
					$attr['class'] .= ' link-type-' . $this->parent_args['link_type'];
				}

				if ( $this->child_args['outercirclebordercolor'] && $this->child_args['outercirclebordersize'] && intval( $this->child_args['outercirclebordersize'] ) ) {
					$attr['class'] .= ' content-icon-wrapper-yes';
				}
				if ( $this->child_args['outercirclebordercolor'] && $this->child_args['outercirclebordersize'] && intval( $this->child_args['outercirclebordersize'] ) && 'pulsate' === $this->parent_args['icon_hover_type'] ) {
					$attr['class'] .= ' icon-wrapper-hover-animation-' . $this->parent_args['icon_hover_type'];
				} else {
					$attr['class'] .= ' icon-hover-animation-' . $this->parent_args['icon_hover_type'];
				}

				if ( $this->child_args['textcolor'] ) {
					$attr['style'] .= 'color:' . $this->child_args['textcolor'] . ';';
				}

				if ( isset( $this->child_args['animation_type_original'] ) ) {

					if ( '' === $this->child_args['animation_type_original'] ) {

						$animations = FusionBuilder::animations(
							array(
								'type'      => $this->parent_args['animation_type'],
								'direction' => $this->parent_args['animation_direction'],
								'speed'     => $this->parent_args['animation_speed'],
								'offset'    => $this->parent_args['animation_offset'],
							)
						);

					} else {

						$animations = FusionBuilder::animations(
							array(
								'type'      => $this->child_args['animation_type'],
								'direction' => $this->child_args['animation_direction'],
								'speed'     => $this->child_args['animation_speed'],
								'offset'    => $this->child_args['animation_offset'],
							)
						);
					}

					if ( 'none' !== $this->child_args['animation_type'] ) {
						$attr = array_merge( $attr, $animations );

						if ( isset( $attr['animation_class'] ) ) {
							$attr['class'] .= ' ' . $attr['animation_class'];
							unset( $attr['animation_class'] );
						}
					}
				}

				return $attr;
			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @param array $args The arguments array.
			 * @return array
			 */
			public function link_attr( $args ) {

				global $fusion_settings;
				if ( ! $fusion_settings ) {
					$fusion_settings = Fusion_Settings::get_instance();
				}

				$attr = array(
					'class' => '',
					'style' => '',
				);

				if ( isset( $args['heading_link'] ) ) {
					$attr['class'] .= 'heading-link';
				}

				if ( $this->child_args['link'] ) {
					$attr['href'] = $this->child_args['link'];
				}

				if ( $this->child_args['link_target'] ) {
					$attr['target'] = $this->child_args['link_target'];
				}
				if ( '_blank' === $this->child_args['link_target'] ) {
					$attr['rel'] = 'noopener noreferrer';
				}
				if ( ( 'button' === $this->parent_args['link_type'] || 'button-bar' === $this->parent_args['link_type'] ) && ! isset( $args['heading_link'] ) ) {
					$attr['class'] .= 'fusion-read-more-button fusion-content-box-button fusion-button button-default button-' . strtolower( $fusion_settings->get( 'button_size' ) ) . ' button-' . strtolower( $fusion_settings->get( 'button_shape' ) ) . ' button-' . strtolower( $fusion_settings->get( 'button_type' ) );
				}

				if ( 'button-bar' === $this->parent_args['link_type'] && 'timeline-vertical' === $this->parent_args['layout'] && isset( $args['readmore'] ) ) {

					$addition_margin = 20 + 15;
					if ( $this->child_args['backgroundcolor'] && 'transparent' !== $this->child_args['backgroundcolor'] && '0' != Fusion_Color::new_color( $this->child_args['backgroundcolor'] )->alpha ) {
						$addition_margin += 35;
					}

					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
						$full_icon_size = $this->child_args['image_width'];
					} elseif ( $this->child_args['icon'] ) {
						if ( 'yes' === $this->parent_args['icon_circle'] ) {
							$full_icon_size = ( $this->parent_args['icon_size'] + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = $this->parent_args['icon_size'];
						}
					}

					if ( 'right' === $this->parent_args['icon_align'] ) {
						$attr['style'] .= 'margin-right:' . ( $full_icon_size + $addition_margin ) . 'px;';
					} else {
						$attr['style'] .= 'margin-left:' . ( $full_icon_size + $addition_margin ) . 'px;';
					}

					$attr['style'] .= 'width:calc(100% - ' . ( $full_icon_size + $addition_margin + 15 ) . 'px);';
				} elseif ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'clean-horizontal', 'timeline-vertical' ), true ) && in_array( $this->parent_args['link_type'], array( 'text', 'button' ), true ) && isset( $args['readmore'] ) ) {

					$addition_margin = 20;

					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
						$full_icon_size = $this->child_args['image_width'];
					} elseif ( $this->child_args['icon'] ) {
						if ( 'yes' === $this->parent_args['icon_circle'] ) {
							$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = $this->parent_args['icon_size'];
						}
					}

					if ( 'right' === $this->parent_args['icon_align'] ) {
						$attr['style'] .= 'margin-right:' . ( $full_icon_size + $addition_margin ) . 'px;';
					} else {
						$attr['style'] .= 'margin-left:' . ( $full_icon_size + $addition_margin ) . 'px;';
					}
					if ( 'button' === $this->parent_args['link_type'] && 'yes' === $this->parent_args['button_span'] ) {
						$attr['style'] .= 'width: calc(100% - ' . ( $full_icon_size + $addition_margin ) . 'px);';
					}
				} elseif ( 'icon-with-title' === $this->parent_args['layout'] ) {
					$attr['style'] .= 'float:' . $this->parent_args['icon_align'] . ';';
				}

				if ( ! in_array( $this->parent_args['layout'], array( 'icon-on-side', 'clean-horizontal', 'timeline-vertical' ), true ) && 'button' === $this->parent_args['link_type'] && 'yes' === $this->parent_args['button_span'] ) {
					$attr['style'] .= 'width: 100%;';
				}

				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function heading_wrapper_attr() {

				$attr = array(
					'class' => 'heading',
					'style' => '',
				);

				if ( $this->child_args['icon'] || $this->child_args['image'] ) {
					$attr['class'] .= ' heading-with-icon';

					if ( 'timeline-vertical' === $this->parent_args['layout'] ) {

						// Image is used.
						if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {

							$image_height = $this->child_args['image_height'];

							if ( $image_height > $this->parent_args['title_size'] && $image_height - $this->parent_args['title_size'] - 15 > 0 ) {
								$attr['style'] .= 'margin-top:' . ( $image_height - $this->parent_args['title_size'] ) . 'px;';
							}
						} elseif ( $this->child_args['icon'] ) {

							// Icon is used.
							if ( 'yes' === $this->parent_args['icon_circle'] ) {
								$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
							} else {
								$full_icon_size = intval( $this->parent_args['icon_size'] );
							}

							if ( $full_icon_size > $this->parent_args['title_size'] && $full_icon_size - $this->parent_args['title_size'] - 15 > 0 ) {
								$attr['style'] .= 'margin-top:' . ( ( intval( $full_icon_size ) - $this->parent_args['title_size'] ) / 2 ) . 'px;';
							}
						}
					}
				}

				if ( $this->parent_args['icon_align'] ) {
					$attr['class'] .= ' icon-' . $this->parent_args['icon_align'];
				}

				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function icon_parent_attr() {
				$attr = array(
					'class' => 'icon',
					'style' => '',
				);

				if ( 'yes' !== $this->parent_args['icon_circle'] && 'icon-boxed' === $this->parent_args['layout'] ) {
					$attr['style'] .= 'position:absolute;width: 100%;top:-' . ( 50 + ( intval( $this->parent_args['icon_size'] ) / 2 ) ) . 'px;';
				}

				if ( 'timeline-vertical' === $this->parent_args['layout'] && 'right' === $this->parent_args['icon_align'] && ( ! $this->child_args['outercirclebordercolor'] || ! $this->child_args['circlebordersize'] ) ) {
					$attr['style'] .= 'padding-left:20px;';
				}

				if ( 'timeline-vertical' === $this->parent_args['layout'] ) {

					// Image is used.
					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {

						$image_height = $this->child_args['image_height'];
						if ( $image_height > $this->parent_args['title_size'] && $image_height - $this->parent_args['title_size'] - 15 > 0 ) {
							$attr['style'] .= 'margin-top:-' . ( $image_height - $this->parent_args['title_size'] ) . 'px;';
							$attr['style'] .= 'margin-bottom:-' . ( $image_height - $this->parent_args['title_size'] ) . 'px;';
						}
					} elseif ( $this->child_args['icon'] ) {

						// Icon is used.
						if ( 'yes' === $this->parent_args['icon_circle'] ) {
							$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = intval( $this->parent_args['icon_size'] );
						}

						if ( $full_icon_size > $this->parent_args['title_size'] && $full_icon_size - $this->parent_args['title_size'] - 15 > 0 ) {
							$attr['style'] .= 'margin-top:-' . ( ( intval( $full_icon_size ) - $this->parent_args['title_size'] ) / 2 ) . 'px;';
							$attr['style'] .= 'margin-bottom:-' . ( ( intval( $full_icon_size ) - $this->parent_args['title_size'] ) / 2 ) . 'px;';
						}
					}
				}

				if ( $this->parent_args['animation_delay'] ) {
					$animation_delay = $this->parent_args['animation_delay'];
					$attr['style']  .= '-webkit-animation-duration: ' . $animation_delay . 'ms;';
					$attr['style']  .= 'animation-duration: ' . $animation_delay . 'ms;';
				}

				return $attr;
			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function icon_wrapper_attr() {

				$attr = array(
					'style' => '',
				);

				if ( $this->child_args['icon'] ) {

					$attr['class'] = '';

					if ( 'yes' === $this->parent_args['icon_circle'] ) {
						$attr['style'] .= 'height:' . ( ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) ) * 2 ) . 'px;width:' . ( ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) ) * 2 ) . 'px;line-height:' . ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) * 2 ) . 'px;';

						if ( $this->child_args['outercirclebordercolor'] ) {
							$attr['style'] .= 'border-color:' . $this->child_args['outercirclebordercolor'] . ';';
						}

						if ( $this->child_args['outercirclebordersize'] && intval( $this->child_args['outercirclebordersize'] ) ) {
							$attr['style'] .= 'border-width:' . $this->child_args['outercirclebordersize'] . ';';
						}

						$attr['style'] .= 'border-style:solid;';

						if ( $this->child_args['circlebordercolor'] ) {
							$attr['style'] .= 'background-color:' . $this->child_args['circlebordercolor'] . ';';
						}

						if ( 'icon-boxed' === $this->parent_args['layout'] ) {
							$attr['style'] .= 'position:absolute;top:-' . ( 50 + $this->parent_args['icon_size'] + intval( $this->child_args['circlebordersize'] ) ) . 'px;margin-left:-' . ( $this->parent_args['icon_size'] + intval( $this->child_args['circlebordersize'] ) ) . 'px;';
						}

						if ( 'round' === $this->parent_args['icon_circle_radius'] ) {
							$this->parent_args['icon_circle_radius'] = '100%';
						}

						if ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'timeline-vertical', 'clean-horizontal' ), true ) ) {
							$margin_direction = 'margin-right';
							if ( 'right' === $this->parent_args['icon_align'] ) {
								$margin_direction = 'margin-left';
							}

							$margin = '20px';
							if ( 'timeline-vertical' === $this->parent_args['layout'] && 'right' === $this->parent_args['icon_align'] ) {
								$margin = '10px';
							}

							$attr['style'] .= $margin_direction . ':' . $margin . ';';
						}

						$attr['style'] .= 'box-sizing:content-box;';
						$attr['style'] .= 'border-radius:' . $this->parent_args['icon_circle_radius'] . ';';
					}
				}
				$this->amp_icon_styles = '.icon span{'.$attr['style'].'}';
				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function icon_attr() {
				global $fusion_library;

				$attr = array(
					'style' => '',
				);

				if ( $this->child_args['image'] ) {
					$attr['class'] = 'image';

					if ( 'icon-boxed' === $this->parent_args['layout'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
						$attr['style']  = 'margin-left:-' . ( $this->child_args['image_width'] / 2 ) . 'px;';
						$attr['style'] .= 'top:-' . ( $this->child_args['image_height'] / 2 + 50 ) . 'px;';
					}
				} elseif ( $this->child_args['icon'] ) {

					$attr['class'] = 'fontawesome-icon ' . FusionBuilder::font_awesome_name_handler( $this->child_args['icon'] );

					// Set parent values if child values are unset to get downwards compatibility.
					if ( ! $this->child_args['circle'] ) {
						$this->child_args['circle'] = $this->parent_args['circle'];
					}

					if ( 'yes' === $this->parent_args['icon_circle'] ) {

						$attr['class'] .= ' circle-yes';

						if ( $this->child_args['circlebordercolor'] ) {
							$attr['style'] .= 'border-color:' . $this->child_args['circlebordercolor'] . ';';
						}

						$icon_circle_dimension = intval( $this->parent_args['icon_size'] ) * 2;

						$this->child_args['circlebordersize'] = FusionBuilder::validate_shortcode_attr_value( $this->child_args['circlebordersize'], 'px' );

						if ( $this->child_args['circlebordersize'] ) {
							$attr['style'] .= 'border-width:' . $this->child_args['circlebordersize'] . ';';
						}

						if ( $this->child_args['circlecolor'] ) {
							$attr['style'] .= 'background-color:' . $this->child_args['circlecolor'] . ';';
						}

						$attr['style'] .= 'box-sizing:content-box;height:' . $icon_circle_dimension . 'px;width:' . $icon_circle_dimension . 'px;line-height:' . $icon_circle_dimension . 'px;';

						if ( 'icon-boxed' === $this->parent_args['layout'] && ( ! $this->child_args['outercirclebordercolor'] || ! $this->child_args['outercirclebordersize'] || ! intval( $this->child_args['outercirclebordersize'] ) ) ) {
							$attr['style'] .= 'top:-' . ( 50 + intval( $this->parent_args['icon_size'] ) ) . 'px;margin-left:-' . intval( $this->parent_args['icon_size'] ) . 'px;';
						}

						if ( 'round' === $this->parent_args['icon_circle_radius'] ) {
							$this->parent_args['icon_circle_radius'] = '100%';
						}

						$attr['style'] .= 'border-radius:' . $this->parent_args['icon_circle_radius'] . ';';

						if ( $this->child_args['outercirclebordercolor'] && $this->child_args['outercirclebordersize'] && intval( $this->child_args['outercirclebordersize'] ) ) {
							// If there is a thick border, kill border width and make it center aligned positioned.
							$attr['style'] .= 'position:relative;';
							$attr['style'] .= 'top:auto;';
							$attr['style'] .= 'left:auto;';
							$attr['style'] .= 'margin:0;';
							$attr['style'] .= 'border-radius:' . $this->parent_args['icon_circle_radius'] . ';';
						}
					} else {

						$attr['class'] .= ' circle-no';

						$attr['style'] .= 'background-color:transparent;border-color:transparent;height:auto;width: ' . $fusion_library->sanitize->get_value_with_unit( $this->parent_args['icon_size'] ) . ';line-height:normal;';

						if ( 'icon-boxed' === $this->parent_args['layout'] ) {
							$attr['style'] .= 'position:relative;left:auto;right:auto;top:auto;margin-left:auto;margin-right:auto;';
						}
					}

					if ( $this->child_args['iconcolor'] ) {
						$attr['style'] .= 'color:' . $this->child_args['iconcolor'] . ';';
					}

					if ( $this->child_args['iconflip'] && 'none' !== $this->child_args['iconflip'] ) {
						$attr['class'] .= ' fa-flip-' . $this->child_args['iconflip'];
					}

					if ( $this->child_args['iconrotate'] && 'none' !== $this->child_args['iconrotate'] ) {
						$attr['class'] .= ' fa-rotate-' . $this->child_args['iconrotate'];
					}

					if ( 'yes' === $this->child_args['iconspin'] ) {
						$attr['class'] .= ' fa-spin';
					}

					$attr['style'] .= 'font-size:' . $this->parent_args['icon_size'] . ';';
				}

				$this->amp_icon_styles = '.fusion-content-boxes.content-boxes-icon-on-top .fontawesome-icon.'.$circle.'{'.$attr['style'].'}';
				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function content_container_attr() {
				$attr = array(
					'class' => 'content-container',
					'style' => '',
				);

				if ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'timeline-vertical', 'clean-horizontal' ), true ) && $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
					if ( 'clean-horizontal' === $this->parent_args['layout'] ) {
						$attr['style'] .= 'padding-left:' . ( $this->child_args['image_width'] + 20 ) . 'px;';
					} else {
						if ( 'right' === $this->parent_args['icon_align'] ) {
							$attr['style'] .= 'padding-right:' . ( $this->child_args['image_width'] + 20 ) . 'px;';
						} else {
							$attr['style'] .= 'padding-left:' . ( $this->child_args['image_width'] + 20 ) . 'px;';
						}
					}
				} elseif ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'timeline-vertical', 'clean-horizontal' ), true ) && $this->child_args['icon'] ) {
					if ( 'yes' === $this->parent_args['icon_circle'] ) {
						$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
					} else {
						$full_icon_size = $this->parent_args['icon_size'];
					}

					if ( 'clean-horizontal' === $this->parent_args['layout'] ) {
						$attr['style'] .= 'padding-left:' . ( intval( $full_icon_size ) + 20 ) . 'px;';
					} else {
						if ( 'right' === $this->parent_args['icon_align'] ) {
							$attr['style'] .= 'padding-right:' . ( intval( $full_icon_size ) + 20 ) . 'px;';
						} else {
							$attr['style'] .= 'padding-left:' . ( intval( $full_icon_size ) + 20 ) . 'px;';
						}
					}
				}

				if ( 'right' === $this->parent_args['icon_align'] && isset( $attr['style'] ) && ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'icon-with-title', 'timeline-vertical', 'clean-horizontal' ), true ) ) ) {
					$attr['style'] .= ' text-align:' . $this->parent_args['icon_align'] . ';';
				} elseif ( 'left' === $this->parent_args['icon_align'] && is_rtl() && ( in_array( $this->parent_args['layout'], array( 'icon-on-side', 'icon-with-title', 'timeline-vertical', 'clean-horizontal' ), true ) ) ) {
					$attr['style'] .= ' text-align:' . $this->parent_args['icon_align'] . ';';
				}

				if ( $this->parent_args['body_color'] ) {
					$attr['style'] .= 'color:' . $this->parent_args['body_color'] . ';';
				}

				return $attr;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function timeline_attr() {
				$attr = array();
				if ( 'timeline-horizontal' === $this->parent_args['layout'] ) {
					$attr['class'] = 'content-box-shortcode-timeline';
					$attr['style'] = '';

					$border_color = '';
					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
						$full_icon_size = $this->child_args['image_height'];
					} elseif ( 'yes' === $this->parent_args['icon_circle'] ) {
						if ( intval( $this->child_args['outercirclebordersize'] ) ) {
							$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = intval( $this->parent_args['icon_size'] ) * 2;
						}
					} else {
						$full_icon_size = intval( $this->parent_args['icon_size'] );
					}

					$position_top = $full_icon_size / 2;

					if ( $this->child_args['backgroundcolor'] && 'transparent' !== $this->child_args['backgroundcolor'] && '0' != Fusion_Color::new_color( $this->child_args['backgroundcolor'] )->alpha ) {
						$position_top += 35;
					}

					if ( $this->child_args['circlebordercolor'] ) {
						$border_color = $this->child_args['circlebordercolor'];
					}

					if ( $this->child_args['outercirclebordercolor'] && $this->child_args['outercirclebordersize'] ) {
						$border_color = $this->child_args['outercirclebordercolor'];
					}

					if ( ! $this->child_args['circlebordercolor'] && ! $this->child_args['outercirclebordercolor'] ) {
						$border_color = '#f6f6f6';
					}

					if ( $border_color ) {
						$attr['style'] .= 'border-color:' . $border_color . ';';
					}

					if ( $position_top ) {
						$attr['style'] .= 'top:' . intval( $position_top ) . 'px;';
					}
				} elseif ( 'timeline-vertical' === $this->parent_args['layout'] ) {
					$attr['class'] = 'content-box-shortcode-timeline-vertical';
					$attr['style'] = '';

					$border_color = '';

					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {
						$full_icon_size = $this->child_args['image_height'];
					} elseif ( 'yes' === $this->parent_args['icon_circle'] ) {
						if ( intval( $this->child_args['outercirclebordersize'] ) ) {
							$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = intval( $this->parent_args['icon_size'] ) * 2;
						}
					} else {
						$full_icon_size = intval( $this->parent_args['icon_size'] );
					}

					$position_top = $full_icon_size / 2;
					if ( $this->parent_args['title_size'] > $full_icon_size ) {
						$position_top = $this->parent_args['title_size'] / 2;
					}
					$position_horizontal = $full_icon_size / 2 + 15;

					if ( $this->child_args['backgroundcolor'] && 'transparent' !== $this->child_args['backgroundcolor'] && '0' != Fusion_Color::new_color( $this->child_args['backgroundcolor'] )->alpha ) {
						$position_top        += 35;
						$position_horizontal += 35;
					}

					if ( $this->child_args['circlebordercolor'] ) {
						$border_color = $this->child_args['circlebordercolor'];
					}

					if ( $this->child_args['outercirclebordercolor'] && fusion_library()->sanitize->number( $this->child_args['outercirclebordersize'] ) ) {
						$border_color = $this->child_args['outercirclebordercolor'];

					}

					if ( ! $this->child_args['circlebordercolor'] && ! $this->child_args['outercirclebordercolor'] ) {
						$border_color = '#f6f6f6';
					}

					if ( $border_color ) {
						$attr['style'] .= 'border-color:' . $border_color . ';';
					}

					if ( $position_horizontal ) {
						if ( 'right' === $this->parent_args['icon_align'] ) {
							$attr['style'] .= 'right:' . intval( $position_horizontal ) . 'px;';
						} else {
							$attr['style'] .= 'left:' . intval( $position_horizontal ) . 'px;';
						}
					}

					if ( $position_top ) {
						$attr['style'] .= 'top:' . $position_top . 'px;';
					}
				}

				if ( $this->parent_args['animation_delay'] ) {
					$animation_delay = $this->parent_args['animation_delay'];
					$attr['style']  .= '-webkit-transition-duration: ' . $animation_delay . 'ms;';
					$attr['style']  .= 'animation-duration: ' . $animation_delay . 'ms;';
				}

				return $attr;
			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function content_box_heading_attr() {
				$attr = array(
					'class' => 'content-box-heading',
					'style' => '',
				);

				if ( $this->parent_args['title_size'] ) {
					$font_size = $this->parent_args['title_size'];

					$attr['style'] = 'font-size:' . $font_size . 'px;line-height:' . ( $font_size + 5 ) . 'px;';
				}

				if ( 'icon-on-side' === $this->parent_args['layout'] || 'clean-horizontal' === $this->parent_args['layout'] ) {

					if ( $this->child_args['image'] && $this->child_args['image_width'] && $this->child_args['image_height'] ) {

						if ( 'right' === $this->parent_args['icon_align'] ) {
							$attr['style'] .= 'padding-right:' . ( $this->child_args['image_width'] + 20 ) . 'px;';
						} else {
							$attr['style'] .= 'padding-left:' . ( $this->child_args['image_width'] + 20 ) . 'px;';
						}
					} elseif ( $this->child_args['icon'] ) {
						if ( 'yes' === $this->parent_args['icon_circle'] ) {
							$full_icon_size = ( intval( $this->parent_args['icon_size'] ) + intval( $this->child_args['circlebordersize'] ) + intval( $this->child_args['outercirclebordersize'] ) ) * 2;
						} else {
							$full_icon_size = $this->parent_args['icon_size'];
						}

						if ( 'right' === $this->parent_args['icon_align'] ) {
							$attr['style'] .= 'padding-right:' . ( intval( $full_icon_size ) + 20 ) . 'px;';
						} else {
							$attr['style'] .= 'padding-left:' . ( intval( $full_icon_size ) + 20 ) . 'px;';
						}
					}
				}

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

				$main_elements = apply_filters( 'fusion_builder_element_classes', array( '.fusion-content-boxes' ), '.fusion-content-boxes' );
				$elements      = $dynamic_css_helpers->map_selector( $main_elements, ' .content-box-heading' );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['font-size'] = $fusion_library->sanitize->size( $fusion_settings->get( 'content_box_title_size' ) );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color']     = $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_title_color' ) );

				$elements = $dynamic_css_helpers->map_selector( $main_elements, ' .content-container' );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_body_color' ) );

				$elements = $dynamic_css_helpers->map_selector( $main_elements, '  .content-wrapper-background' );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'content_box_bg_color' ) );

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-clean-vertical .content-box-column' ),
					$dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-clean-horizontal .content-box-column' )
				);
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['border-right-width'] = '1px';

				$elements = $dynamic_css_helpers->map_selector( $main_elements, ' .content-box-shortcode-timeline' );
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['display'] = 'none';

				$elements = $dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-boxed .content-wrapper-boxed' );
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-bottom']                 = '20px';
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-left']                   = '3%';
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-right']                  = '3%';
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['min-height']                  = 'inherit !important';
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-bottom']              = '20px';
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-left']                = '3% !important';
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-right']               = '3% !important';
				$css[ $three_twenty_six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['min-height']     = 'inherit !important';
				$css[ $three_twenty_six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-bottom'] = '20px';
				$css[ $three_twenty_six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-left']   = '3% !important';
				$css[ $three_twenty_six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-right']  = '3% !important';
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-bottom']           = '20px';
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-left']             = '3%';
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['padding-right']            = '3%';

				// Single column changes for 1268.
				$css[ $content_media_query ]['.fusion-content-boxes .fusion-column']['margin-bottom']        = '55px';
				$css[ $content_media_query ]['.fusion-content-boxes .fusion-read-more-button']['margin-top'] = '8px';

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-on-top .content-box-column' ),
					$dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-boxed .content-box-column' )
				);
				$css[ $content_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-bottom']                 = '55px';
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-bottom']              = '55px';
				$css[ $three_twenty_six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-bottom'] = '55px';
				$css[ $ipad_portrait_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-bottom']           = '55px';

				for ( $i = 1; $i <= 6; $i++ ) {
					$elements = $dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-boxed .content-box-column .heading h' . $i );
					$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-top'] = '-5px';
				}

				$elements = $dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-boxed .content-box-column .more' );
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['margin-top'] = '12px';

				$elements = $dynamic_css_helpers->map_selector( $main_elements, '.content-boxes-icon-boxed .col' );
				$css[ $six_fourty_media_query ][ $dynamic_css_helpers->implode( $elements ) ]['box-sizing'] = 'border-box';

				// Content box buttons.
				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more' )
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background'] = $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color' ) );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color']      = $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_color' ) );
				if ( $fusion_settings->get( 'button_gradient_top_color' ) != $fusion_settings->get( 'button_gradient_bottom_color' ) ) {
					$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-image'][] = '-webkit-gradient( linear, left bottom, left top, from( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color' ) ) . ' ), to( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color' ) ) . ' ) )';
					$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-image'][] = 'linear-gradient( to top, ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color' ) ) . ', ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color' ) ) . ' )';
				}

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more:after' ),
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more:before' )
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_color' ) );

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more:hover' ),
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar.link-area-box:hover .fusion-read-more' )
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background'] = $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) );
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color']      = $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_hover_color' ) ) . '!important';
				if ( $fusion_settings->get( 'button_gradient_top_color_hover' ) != $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) {
					$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-image'][] = '-webkit-gradient( linear, left bottom, left top, from( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) . ' ), to( ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) ) . ' ) )';
					$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['background-image'][] = 'linear-gradient( to top, ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_bottom_color_hover' ) ) . ', ' . $fusion_library->sanitize->color( $fusion_settings->get( 'button_gradient_top_color_hover' ) ) . ' )';
				}

				$elements = array_merge(
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more:hover:after' ),
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar .fusion-read-more:hover:before' ),
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar.link-area-box:hover .fusion-read-more:after' ),
					$dynamic_css_helpers->map_selector( $main_elements, ' .link-type-button-bar.link-area-box:hover .fusion-read-more:before' )
				);
				$css['global'][ $dynamic_css_helpers->implode( $elements ) ]['color'] = $fusion_library->sanitize->color( $fusion_settings->get( 'button_accent_hover_color' ) ) . '!important';

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

				Fusion_Dynamic_JS::enqueue_script(
					'fusion-content-boxes',
					FusionBuilder::$js_folder_url . '/general/fusion-content-boxes.js',
					FusionBuilder::$js_folder_path . '/general/fusion-content-boxes.js',
					array( 'jquery', 'fusion-animations', 'fusion-equal-heights' ),
					'1',
					true
				);
			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections Content Box settings.
			 */
			public function add_options() {

				return array(
					'content_boxes_shortcode_section' => array(
						'label'  => esc_html__( 'Content Boxes Element', 'fusion-builder' ),
						'id'     => 'content_box_shortcode_section',
						'type'   => 'accordion',
						'fields' => array(
							'content_box_bg_color'        => array(
								'label'       => esc_html__( 'Content Box Background Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the background color for content boxes.', 'fusion-builder' ),
								'id'          => 'content_box_bg_color',
								'default'     => 'rgba(255,255,255,0)',
								'type'        => 'color-alpha',
							),
							'content_box_title_size'      => array(
								'label'       => esc_html__( 'Content Box Title Font Size', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the size of the title text.', 'fusion-builder' ),
								'id'          => 'content_box_title_size',
								'default'     => '18px',
								'type'        => 'dimension',
							),
							'content_box_title_color'     => array(
								'label'       => esc_html__( 'Content Box Title Font Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the title font.', 'fusion-builder' ),
								'id'          => 'content_box_title_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
							),
							'content_box_body_color'      => array(
								'label'       => esc_html__( 'Content Box Body Font Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the body font.', 'fusion-builder' ),
								'id'          => 'content_box_body_color',
								'default'     => '#747474',
								'type'        => 'color-alpha',
							),
							'content_box_icon_size'       => array(
								'label'       => esc_html__( 'Content Box Icon Font Size', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the size of the icon.', 'fusion-builder' ),
								'id'          => 'content_box_icon_size',
								'default'     => '21',
								'type'        => 'slider',
								'choices'     => array(
									'min'  => '0',
									'max'  => '250',
									'step' => '1',
								),
							),
							'content_box_icon_color'      => array(
								'label'       => esc_html__( 'Content Box Icon Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the content box icon.', 'fusion-builder' ),
								'id'          => 'content_box_icon_color',
								'default'     => '#ffffff',
								'type'        => 'color-alpha',
							),
							'content_box_icon_circle'     => array(
								'label'       => esc_html__( 'Content Box Icon Background', 'fusion-builder' ),
								'description' => esc_html__( 'Turn on to display a background behind the icon.', 'fusion-builder' ),
								'id'          => 'content_box_icon_circle',
								'default'     => 'yes',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'yes' => esc_html__( 'On', 'fusion-builder' ),
									'no'  => esc_html__( 'Off', 'fusion-builder' ),
								),
							),
							'content_box_icon_circle_radius' => array(
								'label'       => esc_html__( 'Content Box Icon Background Radius', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the border radius of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_circle_radius',
								'default'     => '50%',
								'type'        => 'dimension',
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_bg_color'   => array(
								'label'       => esc_html__( 'Content Box Icon Background Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_bg_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_bg_inner_border_color' => array(
								'label'       => esc_html__( 'Content Box Icon Background Inner Border Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the inner border color of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_bg_inner_border_color',
								'default'     => '#333333',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_bg_inner_border_size' => array(
								'label'       => esc_html__( 'Content Box Icon Background Inner Border Size', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the inner border size of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_bg_inner_border_size',
								'default'     => '1',
								'type'        => 'slider',
								'choices'     => array(
									'min'  => '0',
									'max'  => '20',
									'step' => '1',
								),
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_bg_outer_border_color' => array(
								'label'       => esc_html__( 'Content Box Icon Background Outer Border Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the outer border color of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_bg_outer_border_color',
								'default'     => 'rgba(255,255,255,0)',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_bg_outer_border_size' => array(
								'label'       => esc_html__( 'Content Box Icon Background Outer Border Size', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the outer border size of the icon background.', 'fusion-builder' ),
								'id'          => 'content_box_icon_bg_outer_border_size',
								'default'     => '0',
								'type'        => 'slider',
								'choices'     => array(
									'min'  => '0',
									'max'  => '20',
									'step' => '1',
								),
								'required'    => array(
									array(
										'setting'  => 'content_box_icon_circle',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
							'content_box_icon_hover_type' => array(
								'label'       => esc_html__( 'Content Box Hover Animation Type', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the hover effect of the icon.', 'fusion-builder' ),
								'id'          => 'content_box_icon_hover_type',
								'default'     => 'fade',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'none'    => esc_html__( 'None', 'fusion-builder' ),
									'fade'    => esc_html__( 'Fade', 'fusion-builder' ),
									'slide'   => esc_html__( 'Slide', 'fusion-builder' ),
									'pulsate' => esc_html__( 'Pulsate', 'fusion-builder' ),
								),
							),
							'content_box_hover_animation_accent_color' => array(
								'label'       => esc_html__( 'Content Box Hover Accent Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the accent color on hover.', 'fusion-builder' ),
								'id'          => 'content_box_hover_animation_accent_color',
								'default'     => '#a0ce4e',
								'type'        => 'color-alpha',
							),
							'content_box_link_type'       => array(
								'label'       => esc_html__( 'Content Box Link Type', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the type of link that displays in the content box.', 'fusion-builder' ),
								'id'          => 'content_box_link_type',
								'default'     => 'text',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'text'       => esc_html__( 'Text', 'fusion-builder' ),
									'button-bar' => esc_html__( 'Button Bar', 'fusion-builder' ),
									'button'     => esc_html__( 'Button', 'fusion-builder' ),
								),
							),
							'content_box_button_span'     => array(
								'label'       => esc_html__( 'Button Span', 'fusion-builder' ),
								'description' => esc_html__( 'Choose to have the button span the full width.', 'fusion-builder' ),
								'id'          => 'content_box_button_span',
								'default'     => 'no',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'yes' => esc_html__( 'Yes', 'fusion-builder' ),
									'no'  => esc_html__( 'No', 'fusion-builder' ),
								),
								'required'    => array(
									array(
										'setting'  => 'content_box_link_type',
										'operator' => '==',
										'value'    => 'button',
									),
								),
							),
							'content_box_link_area'       => array(
								'label'       => esc_html__( 'Content Box Link Area', 'fusion-builder' ),
								'description' => esc_html__( 'Controls which area the link will be assigned to.', 'fusion-builder' ),
								'id'          => 'content_box_link_area',
								'default'     => 'link-icon',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'link-icon' => esc_html__( 'Link + Icon', 'fusion-builder' ),
									'box'       => esc_html__( 'Entire Content Box', 'fusion-builder' ),
								),
							),
							'content_box_link_target'     => array(
								'label'       => esc_html__( 'Content Box Link Target', 'fusion-builder' ),
								'description' => esc_html__( 'Controls how the link will open.', 'fusion-builder' ),
								'id'          => 'content_box_link_target',
								'default'     => '_self',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'_self'  => esc_html__( 'Same Window', 'fusion-builder' ),
									'_blank' => esc_html__( 'New Window/Tab', 'fusion-builder' ),
								),
							),
							'content_box_margin'          => array(
								'label'       => esc_html__( 'Content Box Top/Bottom Margins', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the top/bottom margin for content boxes.', 'fusion-builder' ),
								'id'          => 'content_box_margin',
								'type'        => 'spacing',
								'choices'     => array(
									'top'    => true,
									'bottom' => true,
									'units'  => array( 'px', '%' ),
								),
								'default'     => array(
									'top'    => '0px',
									'bottom' => '60px',
								),
							),
						),
					),
				);
			}
		}
	}
	remove_shortcode( 'fusion_content_boxes' );
	new AMP_PC_FusionSC_ContentBoxes();

}
