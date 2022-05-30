<?php
namespace ElementorForAmp\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Shapes;
use Elementor\Plugin;
use Elementor\Core\Schemes;
use Elementor\Embed;
use Elementor\Scheme_Color;
use Elementor\Schemes_Manager;
use Elementor\Group_Control_Css_Filter;

class Amp_Element_Section extends Element_Base {

	private static $presets = [];

	public static function get_type() {
		return 'section';
	}

	public function get_name() {

		return 'section';
	}

	/**
	 * Get section title.
	 *
	 * Retrieve the section title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section title.
	 */
	public function get_title() {
		
		return __( 'Section', 'elementor' );
	}

	/**
	 * Get section icon.
	 *
	 * Retrieve the section icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section icon.
	 */
	public function get_icon() {
		return 'eicon-columns';
	}

	public static function get_presets( $columns_count = null, $preset_index = null ) {
		if ( ! self::$presets ) {
			self::init_presets();
		}

		$presets = self::$presets;

		if ( null !== $columns_count ) {
			$presets = $presets[ $columns_count ];
		}

		if ( null !== $preset_index ) {
			$presets = $presets[ $preset_index ];
		}

		return $presets;
	}

	public static function init_presets() {
		$additional_presets = [
			2 => [
				[
					'preset' => [ 33, 66 ],
				],
				[
					'preset' => [ 66, 33 ],
				],
			],
			3 => [
				[
					'preset' => [ 25, 25, 50 ],
				],
				[
					'preset' => [ 50, 25, 25 ],
				],
				[
					'preset' => [ 25, 50, 25 ],
				],
				[
					'preset' => [ 16, 66, 16 ],
				],
			],
		];

		foreach ( range( 1, 10 ) as $columns_count ) {
			self::$presets[ $columns_count ] = [
				[
					'preset' => [],
				],
			];

			$preset_unit = floor( 1 / $columns_count * 100 );

			for ( $i = 0; $i < $columns_count; $i++ ) {
				self::$presets[ $columns_count ][0]['preset'][] = $preset_unit;
			}

			if ( ! empty( $additional_presets[ $columns_count ] ) ) {
				self::$presets[ $columns_count ] = array_merge( self::$presets[ $columns_count ], $additional_presets[ $columns_count ] );
			}

			foreach ( self::$presets[ $columns_count ] as $preset_index => & $preset ) {
				$preset['key'] = $columns_count . $preset_index;
			}
		}
	}

	protected function _get_initial_config() {

		$config = parent::_get_initial_config();

		$config['presets'] = self::get_presets();
		$config['controls'] = $this->get_controls();
		$config['tabs_controls'] = $this->get_tabs_controls();

		return $config;
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'elementor' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		// Element Name for the Navigator
		$this->add_control(
			'_title',
			[
				'label' => __( 'Title', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'render_type' => 'none',
			]
		);

		$this->add_control(
			'stretch_section',
			[
				'label' => __( 'Stretch Section', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'return_value' => 'section-stretched',
				'prefix_class' => 'elementor-',
				'hide_in_inner' => true,
				'description' => __( 'Stretch the section to the full width of the page using JS.', 'elementor' ) . sprintf( ' <a href="%1$s" target="_blank">%2$s</a>', 'https://go.elementor.com/stretch-section/', __( 'Learn more.', 'elementor' ) ),
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Content Width', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => [
					'boxed' => __( 'Boxed', 'elementor' ),
					'full_width' => __( 'Full Width', 'elementor' ),
				],
				'prefix_class' => 'elementor-section-',
			]
		);

		$this->add_control(
			'content_width',
			[
				'label' => __( 'Content Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 500,
						'max' => 1600,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-container' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => [ 'boxed' ],
				],
				'show_label' => false,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'gap',
			[
				'label' => __( 'Columns Gap', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'elementor' ),
					'no' => __( 'No Gap', 'elementor' ),
					'narrow' => __( 'Narrow', 'elementor' ),
					'extended' => __( 'Extended', 'elementor' ),
					'wide' => __( 'Wide', 'elementor' ),
					'wider' => __( 'Wider', 'elementor' ),
				],
			]
		);

		$this->add_control(
			'height',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'elementor' ),
					'full' => __( 'Fit To Screen', 'elementor' ),
					'min-height' => __( 'Min Height', 'elementor' ),
				],
				'prefix_class' => 'elementor-section-height-',
				'hide_in_inner' => true,
			]
		);

		$this->add_responsive_control(
			'custom_height',
			[
				'label' => __( 'Minimum Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'vw' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh', 'vw' ],
				'selectors' => [
					'{{WRAPPER}} > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} > .elementor-container:after' => 'content: ""; min-height: inherit;', // Hack for IE11
				],
				'condition' => [
					'height' => [ 'min-height' ],
				],
				'hide_in_inner' => true,
			]
		);

		$this->add_control(
			'height_inner',
			[
				'label' => __( 'Height', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => __( 'Default', 'elementor' ),
					'min-height' => __( 'Min Height', 'elementor' ),
				],
				'prefix_class' => 'elementor-section-height-',
				'hide_in_top' => true,
			]
		);

		$this->add_responsive_control(
			'custom_height_inner',
			[
				'label' => __( 'Minimum Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 400,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1440,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'height_inner' => [ 'min-height' ],
				],
				'hide_in_top' => true,
			]
		);

		$this->add_control(
			'column_position',
			[
				'label' => __( 'Column Position', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'middle',
				'options' => [
					'stretch' => __( 'Stretch', 'elementor' ),
					'top' => __( 'Top', 'elementor' ),
					'middle' => __( 'Middle', 'elementor' ),
					'bottom' => __( 'Bottom', 'elementor' ),
				],
				'prefix_class' => 'elementor-section-items-',
				'condition' => [
					'height' => [ 'full', 'min-height' ],
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => __( 'Vertical Align', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'elementor' ),
					'top' => __( 'Top', 'elementor' ),
					'middle' => __( 'Middle', 'elementor' ),
					'bottom' => __( 'Bottom', 'elementor' ),
					'space-between' => __( 'Space Between', 'elementor' ),
					'space-around' => __( 'Space Around', 'elementor' ),
					'space-evenly' => __( 'Space Evenly', 'elementor' ),
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-container > .elementor-row > .elementor-column > .elementor-column-wrap > .elementor-widget-wrap' => 'align-content: {{VALUE}}; align-items: {{VALUE}};',
				],
				// TODO: The following line is for BC since 2.7.0
				'prefix_class' => 'elementor-section-content-',
			]
		);

		$this->add_control(
			'overflow',
			[
				'label' => __( 'Overflow', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Default', 'elementor' ),
					'hidden' => __( 'Hidden', 'elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'overflow: {{VALUE}}',
				],
			]
		);

		$possible_tags = [
			'div',
			'header',
			'footer',
			'main',
			'article',
			'section',
			'aside',
			'nav',
		];

		$options = [
			'' => __( 'Default', 'elementor' ),
		] + array_combine( $possible_tags, $possible_tags );

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => $options,
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		// Section Structure
		$this->start_controls_section(
			'section_structure',
			[
				'label' => __( 'Structure', 'elementor' ),
				'tab' => Controls_Manager::TAB_LAYOUT,
			]
		);

		$this->add_control(
			'structure',
			[
				'label' => __( 'Structure', 'elementor' ),
				'type' => Controls_Manager::STRUCTURE,
				'default' => '10',
				'render_type' => 'none',
			]
		);

		$this->end_controls_section();

		// Section background
		$this->start_controls_section(
			'section_background',
			[
				'label' => __( 'Background', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_background' );

		$this->start_controls_tab(
			'tab_background_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'types' => [ 'classic', 'gradient', 'video', 'slideshow' ],
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_hover',
				'selector' => '{{WRAPPER}}:hover',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'render_type' => 'ui',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Background Overlay
		$this->start_controls_section(
			'section_background_overlay',
			[
				'label' => __( 'Background Overlay', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_background_overlay' );

		$this->start_controls_tab(
			'tab_background_overlay_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay',
				'selector' => '{{WRAPPER}} > .elementor-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_background' => [ 'classic', 'gradient' ],
				],
			]
		);
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .elementor-background-overlay',
			]
		);
	}

		$this->add_control(
			'overlay_blend_mode',
			[
				'label' => __( 'Blend Mode', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Normal', 'elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-background-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_background_overlay_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background_overlay_hover',
				'selector' => '{{WRAPPER}}:hover > .elementor-background-overlay',
			]
		);

		$this->add_control(
			'background_overlay_hover_opacity',
			[
				'label' => __( 'Opacity', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => .5,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover > .elementor-background-overlay' => 'opacity: {{SIZE}};',
				],
				'condition' => [
					'background_overlay_hover_background' => [ 'classic', 'gradient' ],
				],
			]
		);
		if ( is_plugin_active( 'elementor/elementor.php' ) ) {
		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}}:hover > .elementor-background-overlay',
			]
		);
	}

		$this->add_control(
			'background_overlay_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'render_type' => 'ui',
				'separator' => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section border
		$this->start_controls_section(
			'section_border',
			[
				'label' => __( 'Border', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_border' );

		$this->start_controls_tab(
			'tab_border_normal',
			[
				'label' => __( 'Normal', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}, {{WRAPPER}} > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_border_hover',
			[
				'label' => __( 'Hover', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border_hover',
				'selector' => '{{WRAPPER}}:hover',
			]
		);

		$this->add_responsive_control(
			'border_radius_hover',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}}:hover, {{WRAPPER}}:hover > .elementor-background-overlay' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow_hover',
				'selector' => '{{WRAPPER}}:hover',
			]
		);

		$this->add_control(
			'border_hover_transition',
			[
				'label' => __( 'Transition Duration', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default' => [
					'size' => 0.3,
				],
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'background_background',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'border_border',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'transition: background {{background_hover_transition.SIZE}}s, border {{SIZE}}s, border-radius {{SIZE}}s, box-shadow {{SIZE}}s',
					'{{WRAPPER}} > .elementor-background-overlay' => 'transition: background {{background_overlay_hover_transition.SIZE}}s, border-radius {{SIZE}}s, opacity {{background_overlay_hover_transition.SIZE}}s',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section Shape Divider
		$this->start_controls_section(
			'section_shape_divider',
			[
				'label' => __( 'Shape Divider', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_shape_dividers' );

		$shapes_options = [
			'' => __( 'None', 'elementor' ),
		];

		foreach ( Shapes::get_shapes() as $shape_name => $shape_props ) {
			$shapes_options[ $shape_name ] = $shape_props['title'];
		}

		foreach ( [
			'top' => __( 'Top', 'elementor' ),
			'bottom' => __( 'Bottom', 'elementor' ),
		] as $side => $side_label ) {
			$base_control_key = "shape_divider_$side";

			$this->start_controls_tab(
				"tab_$base_control_key",
				[
					'label' => $side_label,
				]
			);

			$this->add_control(
				$base_control_key,
				[
					'label' => __( 'Type', 'elementor' ),
					'type' => Controls_Manager::SELECT,
					'options' => $shapes_options,
					'render_type' => 'none',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				$base_control_key . '_color',
				[
					'label' => __( 'Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						"shape_divider_$side!" => '',
					],
					'selectors' => [
						"{{WRAPPER}} > .elementor-shape-$side .elementor-shape-fill" => 'fill: {{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				$base_control_key . '_width',
				[
					'label' => __( 'Width', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'unit' => '%',
					],
					'tablet_default' => [
						'unit' => '%',
					],
					'mobile_default' => [
						'unit' => '%',
					],
					'range' => [
						'%' => [
							'min' => 100,
							'max' => 300,
						],
					],
					'condition' => [
						"shape_divider_$side" => array_keys( Shapes::filter_shapes( 'height_only', Shapes::FILTER_EXCLUDE ) ),
					],
					'selectors' => [
						"{{WRAPPER}} > .elementor-shape-$side svg" => 'width: calc({{SIZE}}{{UNIT}} + 1.3px)',
					],
				]
			);

			$this->add_responsive_control(
				$base_control_key . '_height',
				[
					'label' => __( 'Height', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 500,
						],
					],
					'condition' => [
						"shape_divider_$side!" => '',
					],
					'selectors' => [
						"{{WRAPPER}} > .elementor-shape-$side svg" => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				$base_control_key . '_flip',
				[
					'label' => __( 'Flip', 'elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'condition' => [
						"shape_divider_$side" => array_keys( Shapes::filter_shapes( 'has_flip' ) ),
					],
					'selectors' => [
						"{{WRAPPER}} > .elementor-shape-$side svg" => 'transform: translateX(-50%) rotateY(180deg)',
					],
				]
			);

			$this->add_control(
				$base_control_key . '_negative',
				[
					'label' => __( 'Invert', 'elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'frontend_available' => true,
					'condition' => [
						"shape_divider_$side" => array_keys( Shapes::filter_shapes( 'has_negative' ) ),
					],
					'render_type' => 'none',
				]
			);

			$this->add_control(
				$base_control_key . '_above_content',
				[
					'label' => __( 'Bring to Front', 'elementor' ),
					'type' => Controls_Manager::SWITCHER,
					'selectors' => [
						"{{WRAPPER}} > .elementor-shape-$side" => 'z-index: 2; pointer-events: none',
					],
					'condition' => [
						"shape_divider_$side!" => '',
					],
				]
			);

			$this->end_controls_tab();
		}

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Section Typography
		$this->start_controls_section(
			'section_typo',
			[
				'label' => __( 'Typography', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		if ( in_array( Scheme_Color::get_type() || Schemes_Manager::get_enabled_schemes() || Schemes\Color::get_type() , Schemes\Manager::get_enabled_schemes(),true ) ) {
			$this->add_control(
				'colors_warning',
				[
					'type' => Controls_Manager::RAW_HTML,
					'raw' => __( 'Note: The following colors won\'t work if Default Colors are enabled.', 'elementor' ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
			);
		}

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Heading Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
				],
				'separator' => 'none',
			]
		);

		$this->add_control(
			'color_text',
			[
				'label' => __( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_link',
			[
				'label' => __( 'Link Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_link_hover',
			[
				'label' => __( 'Link Hover Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_align',
			[
				'label' => __( 'Text Align', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} > .elementor-container' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Section Advanced
		$this->start_controls_section(
			'section_advanced',
			[
				'label' => __( 'Advanced', 'elementor' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Margin', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'allowed_dimensions' => 'vertical',
				'placeholder' => [
					'top' => '',
					'right' => 'auto',
					'bottom' => '',
					'left' => 'auto',
				],
				'selectors' => [
					'{{WRAPPER}}' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'z_index',
			[
				'label' => __( 'Z-Index', 'elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'selectors' => [
					'{{WRAPPER}}' => 'z-index: {{VALUE}};',
				],
				'label_block' => false,
			]
		);

		$this->add_control(
			'_element_id',
			[
				'label' => __( 'CSS ID', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
				'label_block' => false,
				'style_transfer' => false,
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$this->add_control(
			'css_classes',
			[
				'label' => __( 'CSS Classes', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [
					'active' => true,
				],
				'prefix_class' => '',
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor' ),
				'label_block' => false,
				'classes' => 'elementor-control-direction-ltr',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_effects',
			[
				'label' => __( 'Motion Effects', 'elementor' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_responsive_control(
			'animation',
			[
				'label' => __( 'Entrance Animation', 'elementor' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'animation_duration',
			[
				'label' => __( 'Animation Duration', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'slow' => __( 'Slow', 'elementor' ),
					'' => __( 'Normal', 'elementor' ),
					'fast' => __( 'Fast', 'elementor' ),
				],
				'prefix_class' => 'animated-',
				'condition' => [
					'animation!' => '',
				],
			]
		);

		$this->add_control(
			'animation_delay',
			[
				'label' => __( 'Animation Delay', 'elementor' ) . ' (ms)',
				'type' => Controls_Manager::NUMBER,
				'default' => '',
				'min' => 0,
				'step' => 100,
				'condition' => [
					'animation!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		// Section Responsive
		$this->start_controls_section(
			'_section_responsive',
			[
				'label' => __( 'Responsive', 'elementor' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'reverse_order_tablet',
			[
				'label' => __( 'Reverse Columns', 'elementor' ) . ' (' . __( 'Tablet', 'elementor' ) . ')',
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'elementor-',
				'return_value' => 'reverse-tablet',
			]
		);

		$this->add_control(
			'reverse_order_mobile',
			[
				'label' => __( 'Reverse Columns', 'elementor' ) . ' (' . __( 'Mobile', 'elementor' ) . ')',
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'elementor-',
				'return_value' => 'reverse-mobile',
			]
		);

		$this->add_control(
			'heading_visibility',
			[
				'label' => __( 'Visibility', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'responsive_description',
			[
				'raw' => __( 'Responsive visibility will take effect only on preview or live page, and not while editing in Elementor.', 'elementor' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-descriptor',
			]
		);

		$this->add_control(
			'hide_desktop',
			[
				'label' => __( 'Hide On Desktop', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'elementor-',
				'label_on' => __( 'Hide', 'elementor' ),
				'label_off' => __( 'Show', 'elementor' ),
				'return_value' => 'hidden-desktop',
			]
		);

		$this->add_control(
			'hide_tablet',
			[
				'label' => __( 'Hide On Tablet', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'elementor-',
				'label_on' => __( 'Hide', 'elementor' ),
				'label_off' => __( 'Show', 'elementor' ),
				'return_value' => 'hidden-tablet',
			]
		);

		$this->add_control(
			'hide_mobile',
			[
				'label' => __( 'Hide On Mobile', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'prefix_class' => 'elementor-',
				'label_on' => __( 'Hide', 'elementor' ),
				'label_off' => __( 'Show', 'elementor' ),
				'return_value' => 'hidden-phone',
			]
		);

		$this->end_controls_section();

		Plugin::$instance->controls_manager->add_custom_css_controls( $this );
	}

	/**
	 * Render section output in the editor.
	 *
	 * Used to generate the live preview, using a Backbone JavaScript template.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<#
		if ( settings.background_video_link ) {
			let videoAttributes = 'autoplay muted playsinline';

			if ( ! settings.background_play_once ) {
				videoAttributes += ' loop';
			}

			view.addRenderAttribute( 'background-video-container', 'class', 'elementor-background-video-container' );

			if ( ! settings.background_play_on_mobile ) {
				view.addRenderAttribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
			}
		#>
			<div {{{ view.getRenderAttributeString( 'background-video-container' ) }}}>
				<div class="elementor-background-video-embed"></div>
				<video class="elementor-background-video-hosted elementor-html5-video" {{ videoAttributes }}></video>
			</div>
		<# } #>
		<div class="elementor-background-overlay"></div>
		<div class="elementor-shape elementor-shape-top"></div>
		<div class="elementor-shape elementor-shape-bottom"></div>
		<div class="elementor-container elementor-column-gap-{{ settings.gap }}">
			<div class="elementor-row"></div>
		</div>
		<?php
	}

	public function before_render() {
		if ( is_plugin_active( 'the-elementor/elementor.php' ) ) {
		$settings = $this->get_settings();
		}
		else{
			$settings = $this->get_settings_for_display();
		}
		$duration = '5000';
		if(isset($settings['background_slideshow_slide_duration']) && !empty($settings['background_slideshow_slide_duration'])){
			$duration = $settings['background_slideshow_slide_duration'];
		}
		?>
		<<?php echo esc_html( $this->get_html_tag() ); ?> 

		<?php 
		if ( is_plugin_active( 'the-elementor/elementor.php' ) ) {
		$this->get_render_attribute_string( '_wrapper' );
		}else{
		$this->print_render_attribute_string( '_wrapper' );
		}?>>

			<?php
			if( 'slideshow' === $settings['background_background']){

				$slide_duration = (isset($settings['background_slideshow_slide_duration']) && !empty($settings['background_slideshow_slide_duration']))? $settings['background_slideshow_slide_duration']: '';
				?>
				<div class="elementor-background-slideshow swiper-container swiper-container-horizontal" style="height:auto;">
					<amp-carousel width="400" height="300" layout="fill" type="slides" autoplay delay="<?php echo $duration;?>" class="amp-eecrsl">
					<?php
					foreach ($settings['background_slideshow_gallery'] as $key => $slide) {
						$image_url = $slide['url'];
						?>
						<amp-img src="<?php echo $image_url;?>"  width="400"  height="300"  layout="fill"  alt="AMP"></amp-img>
						<?php
					}
					?>
					</amp-carousel>
				</div>
				<?php
			}
			?>
			<?php
			$vidoe_url = "";
			if ( 'video' === $settings['background_background'] ) :
				if ( $settings['background_video_link'] ) :
					$video_properties = Embed::get_video_properties( $settings['background_video_link'] );

					$this->add_render_attribute( 'background-video-container', 'class', 'elementor-background-video-container' );

					if ( ! $settings['background_play_on_mobile'] ) {
						$this->add_render_attribute( 'background-video-container', 'class', 'elementor-hidden-phone' );
					}	
					$vidoe_url = $settings['background_video_link'];
					?>
					<div <?php echo $this->get_render_attribute_string( 'background-video-container' ); ?> style="height:600px;">
						<?php if ( $video_properties ) : ?>
							<!-- <div class="elementor-background-video-embed"></div> -->
							<?php
							$video_id = '';
							if(strpos($vidoe_url, 'youtu') !== false):
								preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $vidoe_url, $match);
								$video_id = $match[1];
								?>
						<amp-youtube width="480" height="270" layout="responsive" data-videoid="<?php echo $video_id;?>" loop autoplay></amp-youtube>
								<?php
							endif;
							if(strpos($vidoe_url, 'vimeo')):
								if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $vidoe_url, $regs)) :
						            $video_id = $regs[3];
						            ?>
						            <amp-vimeo data-videoid="<?php echo $video_id;?>" layout="responsive" width="16" height="9" loop autoplay></amp-vimeo>
						            <?php
						        endif;
							endif;
						else :
						$video_poster = '';
						if(isset($settings['background_video_fallback']['url']) && !empty($settings['background_video_fallback']['url'])){
							$video_poster = $settings['background_video_fallback']['url'];
						}
						$video_tag_attributes = 'autoplay muted playsinline';
						if ( 'yes' !== $settings['background_play_once'] ) :
							$video_tag_attributes .= ' loop';
						endif;
						?>

						<amp-video width="480" height="270" src="<?php echo $vidoe_url;?>" poster="<?php echo $video_poster;?>" layout="responsive" loop autoplay>
							<div fallback>
								<p>Your browser doesn't support HTML5 video.</p>
							</div>
							<source type="video/mp4" src="<?php echo $vidoe_url;?>">
						</amp-video>
						<?php endif; ?>
					</div>
					<?php
				endif;
			endif;

			$has_background_overlay = in_array( $settings['background_overlay_background'], [ 'classic', 'gradient' ], true ) ||
									in_array( $settings['background_overlay_hover_background'], [ 'classic', 'gradient' ], true );

			if ( $has_background_overlay ) :
				?>
				<div class="elementor-background-overlay"></div>
				<?php
			endif;

			if ( $settings['shape_divider_top'] ) {
				$this->print_shape_divider( 'top' );
			}

			if ( $settings['shape_divider_bottom'] ) {
				$this->print_shape_divider( 'bottom' );
			}
			?>
			<div class="elementor-container elementor-column-gap-<?php echo esc_attr( $settings['gap'] ); ?>">
				<div class="elementor-row">
		<?php
	}

	public function after_render() {
		?>
				</div>
			</div>
		</<?php echo esc_html( $this->get_html_tag() ); ?>>
		<?php
	}

	protected function _add_render_attributes() {
		parent::_add_render_attributes();

		$section_type = $this->get_data( 'isInner' ) ? 'inner' : 'top';

		$this->add_render_attribute(
			'_wrapper', 'class', [
				'elementor-section',
				'elementor-' . $section_type . '-section',
			]
		);
	}

	protected function _get_default_child_type( array $element_data ) {
		return Plugin::$instance->elements_manager->get_element_types( 'column' );
	}

	private function get_html_tag() {
		$html_tag = $this->get_settings( 'html_tag' );
		if ( empty( $html_tag ) ) {
			$html_tag = 'section';
		}
		return $html_tag;
	}

	private function print_shape_divider( $side ) {
		$settings = $this->get_active_settings();
		$base_setting_key = "shape_divider_$side";
		$negative = ! empty( $settings[ $base_setting_key . '_negative' ] );
		$shape_path = Shapes::get_shape_path( $settings[ $base_setting_key ], $negative );
		if ( ! is_file( $shape_path ) || ! is_readable( $shape_path ) ) {
			return;
		}
		?>
		<div class="elementor-shape elementor-shape-<?php echo esc_attr( $side ); ?>" data-negative="<?php echo var_export( $negative ); ?>">
			<?php echo file_get_contents( $shape_path ); ?>
		</div>
		<?php
	}
}
