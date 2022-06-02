<?php

if ( fusion_is_element_enabled( 'fusion_checklist' ) ) {

	if ( ! class_exists( 'AMP_PC_FusionSC_Checklist' ) ) {
		/**
		 * Shortcode class.
		 *
		 * @package fusion-builder
		 * @since 1.0
		 */
		class AMP_PC_FusionSC_Checklist extends Fusion_Element {

			/**
			 * The checklist counter.
			 *
			 * @access private
			 * @since 1.0
			 * @var int
			 */
			private $checklist_counter = 1;

			/**
			 * The CSS class of circle elements.
			 *
			 * @access private
			 * @since 1.0
			 * @var string
			 */
			private $circle_class = 'circle-no';

			/**
			 * Parent SC arguments.
			 *
			 * @access protected
			 * @since 1.0
			 * @var array
			 */
			protected $parent_args;

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
			 */
			public $amp_checklist_styles = array();
			public function __construct() {
				parent::__construct();
				add_filter( 'fusion_attr_checklist-shortcode', array( $this, 'attr' ) );
				add_shortcode( 'fusion_checklist', array( $this, 'render_parent' ) );

				add_filter( 'fusion_attr_checklist-shortcode-li-item', array( $this, 'li_attr' ) );
				add_filter( 'fusion_attr_checklist-shortcode-span', array( $this, 'span_attr' ) );
				add_filter( 'fusion_attr_checklist-shortcode-icon', array( $this, 'icon_attr' ) );
				add_filter( 'fusion_attr_checklist-shortcode-item-content', array( $this, 'item_content_attr' ) );

				add_action('amp_post_template_css', array( $this,'amp_pc_checklist_styles' ) ) ;
				add_shortcode( 'fusion_li_item', array( $this, 'render_child' ) );

			}
			public function amp_pc_checklist_styles(){
				echo implode('',array_unique($this->amp_checklist_styles));
			}
			/**
			 * Render the parent shortcode.
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args   Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string         HTML output.
			 */
			public function render_parent( $args, $content = '' ) {
				global $fusion_settings;

				if ( ! $fusion_settings ) {
					$fusion_settings = Fusion_Settings::get_instance();
				}

				$defaults = FusionBuilder::set_shortcode_defaults(
					array(

						'circle'         => strtolower( $fusion_settings->get( 'checklist_circle' ) ),
						'circlecolor'    => $fusion_settings->get( 'checklist_circle_color' ),
						'class'          => '',
						'divider'        => $fusion_settings->get( 'checklist_divider' ),
						'divider_color'  => $fusion_settings->get( 'checklist_divider_color' ),
						'hide_on_mobile' => fusion_builder_default_visibility( 'string' ),
						'icon'           => 'fa-check',
						'iconcolor'      => $fusion_settings->get( 'checklist_icons_color' ),
						'id'             => '',
						'size'           => $fusion_settings->get( 'checklist_item_size' ),
					),
					$args,
					'fusion_checklist'
				);

				$defaults['size'] = FusionBuilder::validate_shortcode_attr_value( $defaults['size'], 'px' );

				$defaults['circle'] = ( 1 == $defaults['circle'] ) ? 'yes' : $defaults['circle'];

				// Fallbacks for old size parameter and 'px' check.
				if ( 'small' === $defaults['size'] ) {
					$defaults['size'] = '13px';
				} elseif ( 'medium' === $defaults['size'] ) {
					$defaults['size'] = '18px';
				} elseif ( 'large' === $defaults['size'] ) {
					$defaults['size'] = '40px';
				} elseif ( ! strpos( $defaults['size'], 'px' ) ) {
					$defaults['size'] = fusion_library()->sanitize->convert_font_size_to_px( $defaults['size'], fusion_library()->get_option( 'body_typography', 'font-size' ) );
					$defaults['size'] .= 'px';
				}

				// Dertmine line-height and margin from font size.
				$font_size                           = fusion_library()->sanitize->number( $defaults['size'] );
				$defaults['circle_yes_font_size']    = $font_size * 0.88;
				$defaults['line_height']             = $font_size * 1.7;
				$defaults['icon_margin']             = $font_size * 0.7;
				$defaults['icon_margin_position']    = ( is_rtl() ) ? 'left' : 'right';
				$defaults['content_margin']          = $defaults['line_height'] + $defaults['icon_margin'];
				$defaults['content_margin_position'] = ( is_rtl() ) ? 'right' : 'left';

				extract( $defaults );

				$this->parent_args = $defaults;

				// Legacy checklist integration.
				if ( strpos( $content, '<li>' ) && strpos( $content, '[fusion_li_item' ) === false ) {
					$content = str_replace( '<ul>', '', $content );
					$content = str_replace( '</ul>', '', $content );
					$content = str_replace( '<li>', '[fusion_li_item]', $content );
					$content = str_replace( '</li>', '[/fusion_li_item]', $content );
				}

				$html = '<ul ' . FusionBuilder::attributes( 'checklist-shortcode' ) . '>' . do_shortcode( $content ) . '</ul>';

				$html = str_replace( '</li><br />', '</li>', $html );

				$this->checklist_counter++;

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

				$attr = array();

				$attr['class'] = 'fusion-checklist fusion-checklist-' . $this->checklist_counter;

				$attr = fusion_builder_visibility_atts( $this->parent_args['hide_on_mobile'], $attr );

				if ( 'yes' === $this->parent_args['divider'] ) {
					$attr['class'] .= ' fusion-checklist-divider';
				}

				$font_size     = str_replace( 'px', '', $this->parent_args['size'] );
				$line_height   = $font_size * 1.7;
				$attr['style'] = 'font-size:' . $this->parent_args['size'] . ';line-height:' . $line_height . 'px;';

				if ( $this->parent_args['class'] ) {
					$attr['class'] .= ' ' . $this->parent_args['class'];
				}

				if ( $this->parent_args['id'] ) {
					$attr['id'] = $this->parent_args['id'];
				}

				$this->amp_checklist_styles[$this->checklist_counter] = '.fusion-checklist-'.$this->checklist_counter.' li.fusion-li-item span{'.$span_attr['style'].'}.fusion-column-wrapper .fusion-checklist-'.$this->checklist_counter.'{'.$attr['style'].'}';

				return $attr;

			}

			/**
			 * Render the child shortcode.
			 *
			 * @access public
			 * @since 1.0
			 * @param  array  $args   Shortcode parameters.
			 * @param  string $content Content between shortcode.
			 * @return string         HTML output.
			 */
			public function render_child( $args, $content = '' ) {

				$defaults = shortcode_atts(
					array(
						'circle'      => '',
						'circlecolor' => '',
						'icon'        => '',
						'iconcolor'   => '',
					),
					$args,
					'fusion_li_item'
				);

				extract( $defaults );

				$this->child_args = $defaults;

				$html  = '<li ' . FusionBuilder::attributes( 'checklist-shortcode-li-item' ) . '>';
				$html .= '<span ' . FusionBuilder::attributes( 'checklist-shortcode-span' ) . '>';
				$html .= '<i ' . FusionBuilder::attributes( 'checklist-shortcode-icon' ) . '></i>';
				$html .= '</span>';
				$html .= '<div ' . FusionBuilder::attributes( 'checklist-shortcode-item-content' ) . '>' . do_shortcode( $content ) . '</div>';
				$html .= '</li>';

				$this->circle_class = 'circle-no';

				return $html;

			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function li_attr() {

				$attr = array();

				$attr['class'] = 'fusion-li-item';

				if ( 'yes' === $this->parent_args['divider'] ) {
					$attr['style'] = 'border-bottom-color:' . $this->parent_args['divider_color'];
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
			public function item_content_attr() {
				return array(
					'class' => 'fusion-li-item-content',
					'style' => 'margin-' . $this->parent_args['content_margin_position'] . ':' . $this->parent_args['content_margin'] . 'px;',
				);
			}

			/**
			 * Builds the attributes array.
			 *
			 * @access public
			 * @since 1.0
			 * @return array
			 */
			public function span_attr() {

				$attr = array(
					'style' => '',
				);

				if ( 'yes' === $this->child_args['circle'] || 'yes' === $this->parent_args['circle'] && ( 'no' !== $this->child_args['circle'] ) ) {
					$this->circle_class = 'circle-yes';

					if ( ! $this->child_args['circlecolor'] ) {
						$circlecolor = $this->parent_args['circlecolor'];
					} else {
						$circlecolor = $this->child_args['circlecolor'];
					}
					$attr['style'] = 'background-color:' . $circlecolor . ';';

					$attr['style'] .= 'font-size:' . $this->parent_args['circle_yes_font_size'] . 'px;';
				}

				$attr['class'] = 'icon-wrapper ' . $this->circle_class;

				$attr['style'] .= 'height:' . $this->parent_args['line_height'] . 'px;';
				$attr['style'] .= 'width:' . $this->parent_args['line_height'] . 'px;';
				$attr['style'] .= 'margin-' . $this->parent_args['icon_margin_position'] . ':' . $this->parent_args['icon_margin'] . 'px;';

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

				if ( ! $this->child_args['icon'] ) {
					$icon = FusionBuilder::font_awesome_name_handler( $this->parent_args['icon'] );
				} else {
					$icon = FusionBuilder::font_awesome_name_handler( $this->child_args['icon'] );
				}

				if ( ! $this->child_args['iconcolor'] ) {
					$iconcolor = $this->parent_args['iconcolor'];
				} else {
					$iconcolor = $this->child_args['iconcolor'];
				}

				return array(
					'class' => 'fusion-li-icon ' . $icon,
					'style' => 'color:' . $iconcolor . ';',
				);
			}

			/**
			 * Adds settings to element options panel.
			 *
			 * @access public
			 * @since 1.1
			 * @return array $sections Checklist settings.
			 */
			public function add_options() {
				global $fusion_settings;
				if ( ! $fusion_settings ) {
					$fusion_settings = Fusion_Settings::get_instance();
				}

				return array(
					'checklist_shortcode_section' => array(
						'label'       => esc_html__( 'Checklist Element', 'fusion-builder' ),
						'description' => '',
						'id'          => 'checklist_shortcode_section',
						'type'        => 'accordion',
						'fields'      => array(
							'checklist_icons_color'   => array(
								'label'       => esc_html__( 'Checklist Icon Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the checklist icon.', 'fusion-builder' ),
								'id'          => 'checklist_icons_color',
								'default'     => '#ffffff',
								'type'        => 'color-alpha',
							),
							'checklist_circle'        => array(
								'label'       => esc_html__( 'Checklist Circle', 'fusion-builder' ),
								'description' => esc_html__( 'Turn on if you want to display a circle background for checklists.', 'fusion-builder' ),
								'id'          => 'checklist_circle',
								'default'     => '1',
								'type'        => 'switch',
							),
							'checklist_circle_color'  => array(
								'label'       => esc_html__( 'Checklist Circle Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the checklist circle background.', 'fusion-builder' ),
								'id'          => 'checklist_circle_color',
								'default'     => '#a0ce4e',
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'checklist_circle',
										'operator' => '!=',
										'value'    => '0',
									),
								),
							),
							'checklist_item_size'     => array(
								'label'       => esc_attr__( 'Item Size', 'fusion-builder' ),
								'description' => esc_attr__( 'Controls the size of the list items.', 'fusion-builder' ),
								'id'          => 'checklist_item_size',
								'default'     => '14px',
								'type'        => 'dimension',
							),
							'checklist_divider'       => array(
								'label'       => esc_attr__( 'Divider Lines', 'fusion-builder' ),
								'description' => esc_attr__( 'Choose if a divider line shows between each list item.', 'fusion-builder' ),
								'id'          => 'checklist_divider',
								'default'     => 'no',
								'type'        => 'radio-buttonset',
								'choices'     => array(
									'yes' => esc_attr__( 'Yes', 'fusion-builder' ),
									'no'  => esc_attr__( 'No', 'fusion-builder' ),
								),
							),
							'checklist_divider_color' => array(
								'label'       => esc_html__( 'Divider Line Color', 'fusion-builder' ),
								'description' => esc_html__( 'Controls the color of the divider lines.', 'fusion-builder' ),
								'id'          => 'checklist_divider_color',
								'default'     => $fusion_settings->get( 'sep_color' ),
								'type'        => 'color-alpha',
								'required'    => array(
									array(
										'setting'  => 'checklist_divider',
										'operator' => '==',
										'value'    => 'yes',
									),
								),
							),
						),
					),
				);
			}
		}
	}
	remove_shortcode( 'fusion_checklist' );
	new AMP_PC_FusionSC_Checklist();

}
