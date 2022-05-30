<?php
namespace ElementorForAmp\Widgets\Pro\Carousel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use ElementorPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Base extends Base_Widget {

	private $slide_prints_count = 0;

	public function get_script_depends() {
		return [ 'imagesloaded' ];
	}

	abstract protected function add_repeater_controls( Repeater $repeater );

	abstract protected function get_repeater_defaults();

	abstract protected function print_slide( array $slide, array $settings, $element_key );

	protected function _register_controls() {
		$this->start_controls_section(
			'section_slides',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$this->add_repeater_controls( $repeater );

		$this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $this->get_repeater_defaults(),
				'separator' => 'after',
			]
		);

		$this->add_control(
			'effect',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Effect', 'elementor-pro' ),
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'elementor-pro' ),
					'fade' => __( 'Fade', 'elementor-pro' ),
					'cube' => __( 'Cube', 'elementor-pro' ),
				],
				'frontend_available' => true,
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Slides Per View', 'elementor-pro' ),
				'options' => [ '' => __( 'Default', 'elementor-pro' ) ] + $slides_per_view,
				'condition' => [
					'effect' => 'slide',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Slides to Scroll', 'elementor-pro' ),
				'description' => __( 'Set how many slides are scrolled per swipe.', 'elementor-pro' ),
				'options' => [ '' => __( 'Default', 'elementor-pro' ) ] + $slides_per_view,
				'condition' => [
					'effect' => 'slide',
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Height', 'elementor-pro' ),
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => __( 'Width', 'elementor-pro' ),
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1140,
					],
					'%' => [
						'min' => 50,
					],
				],
				'size_units' => [ '%', 'px' ],
				'default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => __( 'Additional Options', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'show_arrows',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( 'Arrows', 'elementor-pro' ),
				'default' => 'yes',
				'label_off' => __( 'Hide', 'elementor-pro' ),
				'label_on' => __( 'Show', 'elementor-pro' ),
				'prefix_class' => 'elementor-arrows-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label' => __( 'Pagination', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'bullets',
				'options' => [
					'' => __( 'None', 'elementor-pro' ),
					'bullets' => __( 'Dots', 'elementor-pro' ),
					'fraction' => __( 'Fraction', 'elementor-pro' ),
					'progressbar' => __( 'Progress', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-pagination-type-',
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __( 'Transition Duration', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 500,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => __( 'Autoplay', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => __( 'Autoplay Speed', 'elementor-pro' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'loop',
			[
				'label' => __( 'Infinite Loop', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => __( 'Pause on Interaction', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image_size',
				'default' => 'full',
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_slides_style',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
				],
				'desktop_default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'slide_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_border_size',
			[
				'label' => __( 'Border Size', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'%' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'slide_border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'slide_padding',
			[
				'label' => __( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .elementor-main-swiper .swiper-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_navigation',
			[
				'label' => __( 'Navigation', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_arrows',
			[
				'label' => __( 'Arrows', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'none',
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'min' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_pagination',
			[
				'label' => __( 'Pagination', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label' => __( 'Position', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => __( 'Outside', 'elementor-pro' ),
					'inside' => __( 'Inside', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-pagination-position-',
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-container-horizontal .swiper-pagination-progressbar' => 'height: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet-active, {{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pagination!' => '',
				],
			]
		);

		$this->end_controls_section();
	}
	public function amp_image_carousel_pro_styles(){
		$settings = $this->get_settings_for_display();
		$inline_styles = '';
		$inline_styles .= '.elementor-'.get_the_ID().' .elementor-element.elementor-element-'.$this->get_id().' .elementor-main-swiper{width:100%;}';
		if($settings['skin'] == 'slideshow'){
			$inline_styles .= '.elementor.elementor-'.get_the_ID().'  .elementor-element.elementor-skin-slideshow .elementor-main-swiper{height:auto;} .ele-skin-slideshow .e-mn-sr{height: 100%;position: relative;display: inline-block;}#slidshowCarousel{ height: auto;}.elementor .slidshow-img-selector .carousel-preview amp-img { height: 100px; width: 200px;}@media(max-width:600px){.elementor .slidshow-img-selector .carousel-preview amp-img{width:120px;}}';
		}
		if($settings['skin'] == 'carousel'){
			$inline_styles .= '.carousel-img-media .ele-custom-embed-play { position: absolute; top: 35%; left: 50%; transform: translate(-50%,-50%); } .carousel-img-media .ele-custom-embed-play i { font-size: 100px; color: #fff; opacity: .8; text-shadow: 1px 0 6px rgb(0 0 0 / 30%); transition: all .5s; } .ele-screen-only, .screen-reader-text, .screen-reader-text span, .ui-helper-hidden-accessible { position: absolute; top: -10000em; width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; clip: rect(0,0,0,0); border: 0; } amp-carousel.carousel-img-media { cursor: pointer; } .carousel-img-media .amp-carousel-button { top: 35%; } .carousel-img-media .modal-body amp-youtube { top: 75px; } .carousel-img-media .dialog-close-button { background: #333; border-radius: 50px; padding: 15px; color: #fff; } .swiper-pagination-bullet { margin: 0 6px; } amp-selector [option][selected] { opacity: 1; outline: none; } amp-selector#amcselectorID, amp-selector#aicselectorID{ text-align: center; } @media(min-width:1250px){ amp-selector#amcselectorID { bottom: 42px; } }';
		}
		echo $inline_styles;
	}
	protected function print_slider( array $settings = null ) {
		add_action( 'amp_post_template_css',[$this,'amp_image_carousel_pro_styles'],999);
		if ( null === $settings ) {
			$settings = $this->get_active_settings();
		}
		$default_settings = [
			'container_class' => 'elementor-main-swiper',
			'video_play_icon' => true,
		];
		$autoplay_speed = $settings['autoplay_speed'];
		$settings = array_merge( $default_settings, $settings );
		
		$slides_count = count( $settings['slides'] );
		$alignment = '';
		if(isset($settings['alignment'])){
			$alignment = 'text-align:'.$settings['alignment'];
		}
		$autoplay_enable = $settings['autoplay'];
		$loop = $settings['loop'];
		$delay_time = '';
		$autoplay = '';
		if($autoplay_enable == 'yes'){
			$autoplay = 'autoplay';
			$delay_time = 'delay="'.$settings['autoplay_speed'].'"';
		}
		$slides_per_view = 1;
		if(isset($settings['slides'][0]['type']) || !empty($settings['slides_per_view']) ){
			$slides_per_view = (isset($settings['slides_per_view']) && !empty($settings['slides_per_view'])) ? $settings['slides_per_view']: 3 ;
		}
		$slider_type = 'slides';
		$slide_width = 'width:100%';
		$layout = 'responsive';
		$carousel_width = 'width="400"';
		$video_amp_carousel_check = false;
		foreach ( $settings['slides'] as $index => $slide ){
			if ( 'video' === $slide['type'] && $slide['video']['url'] ) {
				$video_amp_carousel_check = true;
			}
		}
		//This is not required.
		if( false && $slides_per_view > 1 ){
			if(!$video_amp_carousel_check){
				$slider_type = 'carousel';
			}
			$slide_width = 1140/$slides_per_view;
			$slide_width = 'width:'.round($slide_width, 2).'px;';
			$layout = 'fixed-height';
			$carousel_width = '';
		}
		
		$carousel_height = 250;
		if($slider_type == 'carousel'){
			$carousel_height = (isset($settings['height']['size']) && !empty($settings['height']['size']))? $settings['height']['size']: 260;
		}
		$space_between = (isset($settings['space_between']['size']) && !empty($settings['space_between']['size']))? $settings['space_between']['size']:5;
		
		$slideshow_selector_attr = $bullet_selatr = $loop = '';
		$thumnail_slides_per_view = 5;
		if(isset($settings['slideshow_slides_per_view']) && !empty($settings['slideshow_slides_per_view']) ){
			$thumnail_slides_per_view = $settings['slideshow_slides_per_view'];
		}
		if($settings['skin'] == 'slideshow'){
			$slider_type = "slides";
			$layout = "responsive";
			$carousel_height = 300;
			$carousel_width = 'width="400"';
			$slide_width = 'width:100%';
			$slideshow_selector_attr = 'on="slideChange:
              ImgSelector-'.$this->get_id().'.toggle(index=event.index, value=true),
              ImgPreview-'.$this->get_id().'.goToSlide(index=event.index)"';
		}
		if( $settings['pagination'] == 'bullets' ) {
			$bullet_selatr = 'on="slideChange:amcselectorID.toggle(index=event.index, value=true), carouselDots.goToSlide(index=event.index)"';
		}
		if( $settings['loop'] == 'yes' ) {
			$loop = 'loop';
		}

		?>
		<div class="elementor-swiper">
			<div class="<?php echo esc_attr( $settings['container_class'] ); ?> swiper-container" style="<?php echo $alignment;?>">
					<amp-carousel id="slidshowCarousel-<?php echo $this->get_id();?>" class="carousel-img-media" <?php echo $carousel_width;?> height="<?php echo $carousel_height;?>" layout="<?php echo $layout;?>"  type="<?php echo $slider_type;?>" <?php echo $slideshow_selector_attr;?> <?php echo $loop;?> controls <?php echo $bullet_selatr;?>>
					<?php
					foreach ( $settings['slides'] as $index => $slide ) :
						$this->slide_prints_count++;
						?>
						<div class="swiper-slide" style="<?php echo $slide_width;?>margin-right:<?php echo $space_between.'px;';?>">
							<?php $this->print_slide( $slide, $settings, 'slide-' . $index . '-' . $this->slide_prints_count ); ?>
						</div>
					<?php endforeach; ?>
					</amp-carousel>
					<?php
					if( $settings['pagination'] == 'bullets' ) { ?>
						<amp-selector id="amcselectorID"
						  on="select:slidshowCarousel-<?php echo $this->get_id();?>.goToSlide(index=event.targetOption)"
						  layout="container">
						    <ul class="swiper-pagination-bullets">
						    <?php $countids = count($settings['slides']);
						    for ($i=0; $i < $countids; $i++) { 
						    	$selected = ($i == 0 ) ? 'selected' : ''; ?>
						     	<li class="swiper-pagination-bullet" option="<?php echo $i; ?>" <?php echo $selected; ?>></li>
						    <?php } ?>
						    </ul>	
						</amp-selector>
					<?php }
					if($settings['skin'] == 'slideshow'){
						?>
						<div class="slidshow-img-selector" style="width:100%;height:92px;margin-top:10px;">
						<amp-selector id="ImgSelector-<?php echo $this->get_id()?>" on="select:slidshowCarousel-<?php echo $this->get_id();?>.goToSlide(index=event.targetOption)" layout="container">
							<amp-carousel id="ImgPreview-<?php echo $this->get_id();?>" class="carousel-preview" height="100" layout="fixed-height" type="carousel">
								<?php
								$i = 0;
							foreach ( $settings['slides'] as $index => $slide ): ;
								if(!empty($slide['image']['url'])){
								?>
								<amp-img option="<?php echo $i;?>" src="<?php echo $slide['image']['url'];?>" width="400" height="300" alt="a sample image" layout="responsive"></amp-img>
							<?php 
								}
								$i++;
							endforeach; ?>
							</amp-carousel>
					    </amp-selector>
						</div>
						<?php 
					}
					?>
			</div>
		</div>
		<?php
	}

	protected function get_slide_image_url( $slide, array $settings ) {
		$image_url = Group_Control_Image_Size::get_attachment_image_src( $slide['image']['id'], 'image_size', $settings );

		if ( ! $image_url ) {
			$image_url = $slide['image']['url'];
		}

		return $image_url;
	}
}
