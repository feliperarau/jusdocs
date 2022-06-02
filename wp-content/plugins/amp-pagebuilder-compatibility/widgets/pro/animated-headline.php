<?php
namespace ElementorForAmp\Widgets\Pro;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Amp_Animated_Headline extends Widget_Base {

	public function get_name() {
		return 'animated-headline';
	}

	public function get_title() {
		return __( 'Animated Headline', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-animated-headline';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
	}

	public function get_keywords() {
		return [ 'headline', 'heading', 'animation', 'title', 'text' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'text_elements',
			[
				'label' => __( 'Headline', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'headline_style',
			[
				'label' => __( 'Style', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'highlight',
				'options' => [
					'highlight' => __( 'Highlighted', 'elementor-pro' ),
					'rotate' => __( 'Rotating', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-headline--style-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'animation_type',
			[
				'label' => __( 'Animation', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'typing' => 'Typing',
					'clip' => 'Clip',
					'flip' => 'Flip',
					'swirl' => 'Swirl',
					'blinds' => 'Blinds',
					'drop-in' => 'Drop-in',
					'wave' => 'Wave',
					'slide' => 'Slide',
					'slide-down' => 'Slide Down',
				],
				'default' => 'typing',
				'condition' => [
					'headline_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'marker',
			[
				'label' => __( 'Shape', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => [
					'circle' => _x( 'Circle', 'Shapes', 'elementor-pro' ),
					'curly' => _x( 'Curly', 'Shapes', 'elementor-pro' ),
					'underline' => _x( 'Underline', 'Shapes', 'elementor-pro' ),
					'double' => _x( 'Double', 'Shapes', 'elementor-pro' ),
					'double_underline' => _x( 'Double Underline', 'Shapes', 'elementor-pro' ),
					'underline_zigzag' => _x( 'Underline Zigzag', 'Shapes', 'elementor-pro' ),
					'diagonal' => _x( 'Diagonal', 'Shapes', 'elementor-pro' ),
					'strikethrough' => _x( 'Strikethrough', 'Shapes', 'elementor-pro' ),
					'x' => 'X',
				],
				'render_type' => 'template',
				'condition' => [
					'headline_style' => 'highlight',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'before_text',
			[
				'label' => __( 'Before Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'default' => __( 'This page is', 'elementor-pro' ),
				'placeholder' => __( 'Enter your headline', 'elementor-pro' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'highlighted_text',
			[
				'label' => __( 'Highlighted Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Amazing', 'elementor-pro' ),
				'label_block' => true,
				'condition' => [
					'headline_style' => 'highlight',
				],
				'separator' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'rotating_text',
			[
				'label' => __( 'Rotating Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Enter each word in a separate line', 'elementor-pro' ),
				'separator' => 'none',
				'default' => "Better\nBigger\nFaster",
				'rows' => 5,
				'condition' => [
					'headline_style' => 'rotate',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'after_text',
			[
				'label' => __( 'After Text', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::TEXT_CATEGORY,
					],
				],
				'placeholder' => __( 'Enter your headline', 'elementor-pro' ),
				'label_block' => true,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'elementor-pro' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-pro' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-pro' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-pro' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-headline' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tag',
			[
				'label' => __( 'HTML Tag', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_marker',
			[
				'label' => __( 'Shape', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'headline_style' => 'highlight',
				],
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'stroke_width',
			[
				'label' => __( 'Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'above_content',
			[
				'label' => __( 'Bring to Front', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper svg' => 'z-index: 2',
					'{{WRAPPER}} .elementor-headline-dynamic-text' => 'z-index: auto',
				],
			]
		);

		$this->add_control(
			'rounded_edges',
			[
				'label' => __( 'Rounded Edges', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-wrapper path' => 'stroke-linecap: round; stroke-linejoin: round',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_text',
			[
				'label' => __( 'Headline', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-plain-text' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-headline',
			]
		);

		$this->add_control(
			'heading_words_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Animated Text', 'elementor-pro' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'words_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-headline-dynamic-text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'words_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-headline-dynamic-text',
				'exclude' => [ 'font_size' ],
			]
		);

		$this->end_controls_section();
	}

	public function load_rotating_text_css($css){
		static $alreadyAddedrotatetext = 1;
		if($alreadyAddedrotatetext==2){ return $css; }
		$alreadyAddedrotatetext++;
		$css.='
		.elementor-headline-animation-type-slide-down .elementor-headline-dynamic-wrapper{
			height: 60px;
			padding:0px;
		}
		.elementor-headline-dynamic-wrapper {
			height: 70px;
			margin: auto;
			overflow: hidden;
			position: relative;
		  }
		  .elementor-headline-dynamic-wr.highlight-text {
		    height: auto;
		  }
		  .elementor-headline-dynamic-wrapper ul {
			margin: 0;
			padding: 0;
			-webkit-animation: scrollUp 1.5s .16s infinite forwards;
					animation: scrollUp 1.5s .16s infinite forwards;
		  }
		  .elementor-headline-dynamic-wrapper ul li {
			opacity: 1;
			height: 60px;
			padding: 10px;
    		line-height: 100px;
			list-style: none;
		  }
		  
		  @-webkit-keyframes fadeOut {
			from {
			  opacity: 1;
			}
			to {
			  opacity: 0;
			}
		  }
		  
		  @keyframes fadeOut {
			from {
			  opacity: 1;
			}
			to {
			  opacity: 0;
			}
		  }
		  @-webkit-keyframes scrollUp {
			from {
			  -webkit-transform: translateY(0);
					  transform: translateY(0);
			}
			to {
			  -webkit-transform: translateY(-83.3333333333%);
					  transform: translateY(-83.3333333333%);
			}
		  }
		  @keyframes scrollUp {
			from {
			  -webkit-transform: translateY(0);
					  transform: translateY(0);
			}
			to {
			  -webkit-transform: translateY(-83.3333333333%);
					  transform: translateY(-83.3333333333%);
			}
		  }';
		  return $css;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if(isset($settings['rotating_text']) && !empty($settings['rotating_text']) ){

			add_filter('amp_pc_add_custom_css', array($this, 'load_rotating_text_css'),10,1);
		}
		$tag = $settings['tag'];

		$this->add_render_attribute( 'headline', 'class', 'elementor-headline' );

		if ( 'rotate' === $settings['headline_style'] ) {
			$this->add_render_attribute( 'headline', 'class', 'elementor-headline-animation-type-' . $settings['animation_type'] );

			$is_letter_animation = in_array( $settings['animation_type'], [ 'typing', 'swirl', 'blinds', 'wave' ] );

			if ( $is_letter_animation ) {
				$this->add_render_attribute( 'headline', 'class', 'elementor-headline-letters' );
			}
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'url', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'url', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'url', 'rel', 'nofollow' );
			}

			echo '<a ' . $this->get_render_attribute_string( 'url' );
		}

		?>
		<<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'headline' ); ?>>
			<?php if ( ! empty( $settings['before_text'] ) ) : ?>
				<span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php echo $settings['before_text']; ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $settings['highlighted_text'] ) ) : ?>
		        <span class="elementor-headline-dynamic-wrapper highlight-text elementor-headline-text-wrapper"><span class="elementor-headline-dynamic-text elementor-headline-text-active"><?php echo $settings['highlighted_text']; ?></span>
		        <?php if ( $settings['marker'] == 'circle' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M325,18C228.7-8.3,118.5,8.3,78,21C22.4,38.4,4.6,54.6,5.6,77.6c1.4,32.4,52.2,54,142.6,63.7 c66.2,7.1,212.2,7.5,273.5-8.3c64.4-16.6,104.3-57.6,33.8-98.2C386.7-4.9,179.4-1.4,126.3,20.7"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'curly' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M3,146.1c17.1-8.8,33.5-17.8,51.4-17.8c15.6,0,17.1,18.1,30.2,18.1c22.9,0,36-18.6,53.9-18.6 c17.1,0,21.3,18.5,37.5,18.5c21.3,0,31.8-18.6,49-18.6c22.1,0,18.8,18.8,36.8,18.8c18.8,0,37.5-18.6,49-18.6c20.4,0,17.1,19,36.8,19 c22.9,0,36.8-20.6,54.7-18.6c17.7,1.4,7.1,19.5,33.5,18.8c17.1,0,47.2-6.5,61.1-15.6"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'double' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M8.4,143.1c14.2-8,97.6-8.8,200.6-9.2c122.3-0.4,287.5,7.2,287.5,7.2"></path><path d="M8,19.4c72.3-5.3,162-7.8,216-7.8c54,0,136.2,0,267,7.8"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'underline' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M7.7,145.6C109,125,299.9,116.2,401,121.3c42.1,2.2,87.6,11.8,87.3,25.7"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'double_underline' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M5,125.4c30.5-3.8,137.9-7.6,177.3-7.6c117.2,0,252.2,4.7,312.7,7.6"></path><path d="M26.9,143.8c55.1-6.1,126-6.3,162.2-6.1c46.5,0.2,203.9,3.2,268.9,6.4"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'underline_zigzag' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M9.3,127.3c49.3-3,150.7-7.6,199.7-7.4c121.9,0.4,189.9,0.4,282.3,7.2C380.1,129.6,181.2,130.6,70,139 c82.6-2.9,254.2-1,335.9,1.3c-56,1.4-137.2-0.3-197.1,9"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'strikethrough' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M3,75h493.5"></path></svg>
		        <?php endif; ?>
		        <?php if ( $settings['marker'] == 'x' ) : ?>
		          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 150" preserveAspectRatio="none"><path d="M497.4,23.9C301.6,40,155.9,80.6,4,144.4"></path><path d="M14.1,27.6c204.5,20.3,393.8,74,467.3,111.7"></path></svg>
		        <?php endif; ?>
		      </span>
		    <?php endif; ?>

			<?php if( ! empty( $settings['rotating_text'] ) ) : ?>
			<span class="elementor-headline-dynamic-wrapper elementor-headline-text-wrapper"><ul style="color: <?php echo $settings['words_color'] ?>;"><?php
			$rotating_text = explode("\n", $settings['rotating_text']);
			
			echo '<li class="">'.implode('</li><li class="">', $rotating_text)."</li>";			
			?></ul></span>
			<?php endif; ?>
			
			<?php if ( ! empty( $settings['after_text'] ) ) : ?>
				<span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php echo $settings['after_text']; ?></span>
			<?php endif; ?>
		</<?php echo $tag; ?>>
		<?php

		if ( ! empty( $settings['link']['url'] ) ) {
			echo '</a>';
		}
	}

	protected function _content_template() {
		?>
		<#
		var headlineClasses = 'elementor-headline',
			tag = settings.tag;

		if ( 'rotate' === settings.headline_style ) {
			headlineClasses += ' elementor-headline-animation-type-' + settings.animation_type;

			var isLetterAnimation = -1 !== [ 'typing', 'swirl', 'blinds', 'wave' ].indexOf( settings.animation_type );

			if ( isLetterAnimation ) {
				headlineClasses += ' elementor-headline-letters';
			}
		}

		if ( settings.link.url ) { #>
			<a htef="#">
		<# } #>
				<{{{ tag }}} class="{{{ headlineClasses }}}">
					<# if ( settings.before_text ) { #>
						<span class="elementor-headline-plain-text elementor-headline-text-wrapper">{{{ settings.before_text }}}</span>
					<# } #>

					<# if ( settings.rotating_text ) { #>
						<span class="elementor-headline-dynamic-wrapper elementor-headline-text-wrapper"></span>
					<# } #>

					<# if ( settings.after_text ) { #>
						<span class="elementor-headline-plain-text elementor-headline-text-wrapper">{{{ settings.after_text }}}</span>
					<# } #>
				</{{{ tag }}}>
		<# if ( settings.link.url ) { #>
			<a htef="#">
		<# } #>
		<?php
	}
}
