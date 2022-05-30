<?php

namespace ElementorForAmp\Widgets\Soledad;

use PenciSoledadElementor\Base\Base_Widget;
use PenciSoledadElementor\Modules\QueryControl\Module;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Amp_PenciPopularPosts extends Base_Widget {

	public function get_name() {
		return 'penci-popular-posts';
	}

	public function get_title() {
		return esc_html__( 'Penci Popular Posts', 'soledad' );
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_keywords() {
		return array( 'popular-posts' );
	}


	protected function _register_controls() {
		parent::_register_controls();

		$this->register_query_section_controls();
		$this->start_controls_section(
			'section_page', array(
				'label' => esc_html__( 'Layout', 'soledad' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);
		$this->add_control(
			'penci_columns', array(
				'label'   => __( 'Columns', 'soledad' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => array(
					'4' => __( '4 Columns', 'soledad' ),
					'3' => __( '3 Columns', 'soledad' )
				)
			)
		);
		$this->add_control(
			'_title_length', array(
				'label'       => __( 'Custom Words Length for Post Titles', 'soledad' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => true,
				'default'     => '10',
			)
		);

		$this->add_control(
			'penci_featimg_size', array(
				'label'                => __( 'Image Size Type', 'soledad' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => '',
				'options'              => array(
					''           => esc_html__( 'Default', 'soledad' ),
					'horizontal' => esc_html__( 'Horizontal Size', 'soledad' ),
					'square'     => esc_html__( 'Square Size', 'soledad' ),
					'vertical'   => esc_html__( 'Vertical Size', 'soledad' ),
					'custom'     => esc_html__( 'Custom', 'soledad' ),
				),
				'selectors'            => array( '{{WRAPPER}} .penci-image-holder:before' => '{{VALUE}}', ),
				'selectors_dictionary' => array(
					'horizontal' => 'padding-top: 66.6667%;',
					'square'     => 'padding-top: 100%;',
					'vertical'   => 'padding-top: 135.4%;',
				)
			)
		);
		$this->add_responsive_control(
			'penci_featimg_ratio', array(
				'label'          => __( 'Image Ratio', 'soledad' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 0.66 ),
				'tablet_default' => array( 'size' => '' ),
				'mobile_default' => array( 'size' => 0.5 ),
				'range'          => array( 'px' => array( 'min' => 0.1, 'max' => 2, 'step' => 0.01 ) ),
				'selectors'      => array(
					'{{WRAPPER}} .penci-image-holder:before' => 'padding-top: calc( {{SIZE}} * 100% );',
				),
				'condition'      => array( 'penci_featimg_size' => 'custom' ),
			)
		);

		$this->end_controls_section();
		$this->register_block_title_section_controls_post();

		$this->start_controls_section(
			'section_popular_posts_style',
			array(
				'label' => __( 'Popular Posts', 'soledad' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ptitle_style', array(
				'label' => __( 'Post Title', 'soledad' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'ptitle_color', array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related h3 a' => 'color: {{VALUE}};',
				),
			)
		);
		$this->add_control(
			'ptitle_hcolor', array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related h3 a:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'ptitle_typo',
				'selector' => '{{WRAPPER}} .penci-home-popular-post .item-related h3, {{WRAPPER}} .penci-home-popular-post  .item-related h3 a'
			)
		);
		// Post meta
		$this->add_control(
			'heading_meta_style',
			array(
				'label'     => __( 'Post Meta', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'pmeta_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .item-related span.date' => 'color: {{VALUE}};',

				),
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(), array(
				'name'     => 'pmeta_typo',
				'selector' => '{{WRAPPER}} .item-related span.date'
			)
		);

		$this->add_control(
			'heading_dots_style',
			array(
				'label'     => __( 'Dots', 'soledad' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);
		$this->add_control(
			'_dot_color',
			array(
				'label'     => __( 'Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array( '{{WRAPPER}} .owl-dots .owl-dot span' => 'border-color: {{VALUE}};background-color: {{VALUE}};', ),
			)
		);
		$this->add_control(
			'dot_hcolor',
			array(
				'label'     => __( 'Hover Color', 'soledad' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .owl-dots .owl-dot:hover span'  => 'border-color: {{VALUE}};background-color: {{VALUE}};',
					'{{WRAPPER}} .owl-dots .owl-dot.active span' => 'border-color: {{VALUE}};background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->register_block_title_style_section_controls();

	}

	protected function render() {
		$settings = $this->get_settings();

		$query_args = Module::get_query_args( 'posts', $settings );

		echo \Amp_Soledad_VC_Shortcodes::popular_posts( array(
			'title'               => $settings['heading'],
			'hide_block_heading'  => $settings['hide_block_heading'],
			'heading_title_style' => $settings['heading_title_style'],
			'heading_title_link'  => $settings['heading_title_link'],
			'heading_title_align' => $settings['block_title_align'],

			'columns'       => $settings['penci_columns'],
			'_title_length' => $settings['_title_length'],
			'elementor_query' => $query_args,
		) );
	}
}
