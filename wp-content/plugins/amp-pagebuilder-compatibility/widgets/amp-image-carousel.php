<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Control_Media;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Image_Carousel extends Widget_Base {

	public function get_name() {
		return 'image-carousel';
	}

	public function get_title() {
		return __( 'Amp Image Carousel', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual', 'carousel', 'slider' ];
	}

	public function amp_elementor_widget_styles(){
		if ( is_plugin_active( 'the-elementor/elementor.php' ) ) {
		$settings = $this->get_settings();
		}else{
		$settings = $this->get_settings_for_display();			
		}
		if(isset($settings['thumbnail_size'])){
		if($settings['thumbnail_size'] == 'custom'){
			$width = 'auto'; $height = 'auto';
		}elseif($settings['thumbnail_size'] == 'thumbnail'){
			$width = 150; $height = 150;
		}elseif($settings['thumbnail_size'] == 'medium'){
			$width = 300; $height = 250;
		}elseif($settings['thumbnail_size'] == 'medium_large'){
			$width = 350; $height = 300;
		}elseif($settings['thumbnail_size'] == 'large'){
			$width = 400; $height = 350;
		}elseif($settings['thumbnail_size'] == 'full'){
			$width = 600; $height = 400;
		}}else{
			$width = 150; $height = 150;
		}

		$slides_to_show = '3';
		$slide_width_arr = array('1'=>'100%','2'=>'50%','3'=>'33.3%','4'=>'25%','5'=>'20%','6'=>'16.6%','7'=>'14.28%','8'=>'12.5%','9'=>'11.11%','10'=>'10%');
		if(isset($settings['slides_to_show']) && !empty($settings['slides_to_show'])){
			$slides_to_show = $settings['slides_to_show'];
			$slide_width = $slide_width_arr[$slides_to_show];
		}else{
			$slide_width = $slide_width_arr[$slides_to_show];
		}

		$inline_styles = '.overlay-text {
				      position: absolute;
				      bottom: 16px;
				      left: 16px;
				      z-index: 1;
				      pointer-events: none;
				      background-color: #494A4D;
				      color: white;
				      padding: 2px 6px 2px 6px;
				      border-radius: 2px;
				      opacity: 0.7;
				      font-family: Roboto, sans-serif;
				      font-size: 1em;
				    }

				    figure {
				      margin: 0;
				    }
				    figcaption{
				    	white-space: pre-wrap;
				    }
				    amp-img[lightbox] {
				      cursor: pointer;
				    }

				   .container-amp-car {
				      margin: 0 6% 0 6%;
				    }
				    .elementor amp-carousel amp-img{
				      margin:auto;
				    }

				    .paragraph {
				      padding: 10px 0 10px 0;
				    }';
		foreach ( $settings['carousel'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );
			if(!empty($image_url)){
				list($img_width, $img_height, $type, $attr) = getimagesize($image_url);
				if(!empty($img_width) && !empty($img_height)){
					$width = $img_width;
					$height = $img_height;
				}
			}
		}
		//$inline_styles .= '.elementor amp-img.swiper-slide-image{width:'.$width.'px;}';
		$inline_styles .= 'amp-carousel#aiccarouselID { min-height: 200px; } #aiccarouselID .swiper-slide-inner { vertical-align: top; }';
		$inline_styles .= 'amp-carousel > div::-webkit-scrollbar { height:0px;  display:inline-block;} .woocommerce .elementor amp-carousel amp-img.swiper-slide-image {max-width: 100%;width: 100%;} amp-carousel#carousel-with-lightbox .swiper-slide-inner {display: flex;justify-content: center;}.swiper-pagination-bullet{margin:13px;opacity:1;background:darkgray;}amp-selector [option][selected] {outline: unset;background: black;}.swiper-pagination-bullets {text-align: center;}';
		if($slides_to_show != 1){
			$inline_styles .= '.elementor-element-'.$this->get_id().' .elementor-slick-slider{width:100%;}.elementor-element-'.$this->get_id().'  .slide-width-'.$slides_to_show.'{width:'.$slide_width.';text-align: center;margin: 0 auto;}
			@media(max-width:767px){
				.elementor-element-'.$this->get_id().' .elementor-slick-slider .swiper-slide-inner{width:88%;margin-right: 7px;}
				.elementor-element-'.$this->get_id().'  .slide-width-'.$slides_to_show.'{width:100%;}
			}';
		}else{
			$inline_styles .= '.elementor-element-'.$this->get_id().' .elementor-slick-'.$slides_to_show.'{width:100%;} .elementor-element-'.$this->get_id().' .slide-width-'.$slides_to_show.'{display:inherit;}
			';
		}

        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-image-carousel'][$this->get_id()] = $inline_styles;
	}
	public function amp_elementor_pagebuilder_scripts($data){
		$data['amp_component_scripts']['amp-carousel'] = 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js';
		$data['amp_component_scripts']['amp-lightbox-gallery'] = 'https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js';
		return $data;
	}
	protected function render() {
		add_filter('amp_post_template_data', [$this, 'amp_elementor_pagebuilder_scripts']);
		if ( is_plugin_active( 'the-elementor/elementor.php' ) ) {
		$settings = $this->get_settings();
		}else{

		$settings = $this->get_settings_for_display();
		}
		$this->amp_elementor_widget_styles();
		if ( empty( $settings['carousel'] ) ) {
			return;
		}
		$link_to = 'none';
		$slides_to_show = '3';
		$carousel_type = 'carousel';
		if(isset($settings['slides_to_show']) && !empty($settings['slides_to_show'])){
			$slides_to_show = $settings['slides_to_show'];
		}
		$autoplay = 'autoplay';
		$autoplay_speed = '5000';
		if(isset($settings['autoplay_speed']) && !empty($settings['autoplay_speed'])){
			$autoplay_speed = 'delay='.$settings['autoplay_speed'];
		}
		if(isset($settings['link_to']) && !empty($settings['link_to']) ){
			$link_to = $settings['link_to'];
		}
		$lightbox = '';
		$lightbox_status = '';
		if( $link_to == 'file'){
			$lightbox = 'lightbox';
			if(isset($settings['open_lightbox']) && !empty($settings['open_lightbox']) ){
				$lightbox_status = $settings['open_lightbox'];
				if( $lightbox_status == 'yes' ){
					$lightbox = 'lightbox';
				}else{
					$lightbox = '';
				}
			}
		}
		if( $slides_to_show == 1){
			$carousel_type = 'slides'; 
		}else{
			$autoplay = '';
			$autoplay_speed = '';
		}

		if(isset($settings['autoplay'])){
			if($settings['autoplay'] == 'no'){
				$autoplay = '';
				$autoplay_speed = '';
			}
		}
		
		$slides = [];
		
		if(isset($settings['thumbnail_size'])){
		if($settings['thumbnail_size'] == 'custom'){
			$width = $settings['thumbnail_custom_dimension']['width'];
			$height = $settings['thumbnail_custom_dimension']['height'];
		}elseif($settings['thumbnail_size'] == 'thumbnail'){
			$width = 150; $height = 150;
		}elseif($settings['thumbnail_size'] == 'medium'){
			$width = 300; $height = 250;
		}elseif($settings['thumbnail_size'] == 'medium_large'){
			$width = 350; $height = 300;
		}elseif($settings['thumbnail_size'] == 'large'){
			$width = 400; $height = 350;
		}elseif($settings['thumbnail_size'] == 'full'){
			$width = 600; $height = 400;
		}}else{
			$width = 150; $height = 150;
		}

		$image_layout_type = 'intrinsic';
		if($carousel_type == 'slides'){
			$width = '400';
			$height = '300';
			$image_layout_type = 'responsive';
		}else{
			$image_layout_type = 'intrinsic';
		}
		foreach ( $settings['carousel'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );
			if(!empty($image_url)){
				list($img_width, $img_height, $type, $attr) = getimagesize($image_url);
				if(!empty($img_width) && !empty($img_height)){
					$width = $img_width;
					$height = $img_height;
				}
			}
			$image_html = '<amp-img class="swiper-slide-image" src="'.esc_attr( $image_url ).'" width="'.$width.'" height="'.$height.'" layout="responsive" alt="' . esc_attr( Control_Media::get_image_alt( $attachment ) ) . '"></amp-img>';

			$link = $this->get_link_url( $attachment, $settings );

			if ( $link ) {
				
				$link_key = 'link_' . $index;

				$this->add_render_attribute( $link_key, [
					'href' => $link['url'],
					'data-elementor-open-lightbox' => isset($settings['open_lightbox'])? $settings['open_lightbox']: '',
					'data-elementor-lightbox-slideshow' => $this->get_id(),
					'data-elementor-lightbox-index' => $index,
				] );

				if ( Plugin::$instance->editor->is_edit_mode() ) {
					$this->add_render_attribute( $link_key, [
						'class' => 'elementor-clickable',
					] );
				}
				$target_blank = '';
				if ( ! empty( $link['is_external'] ) ) {
					$target_blank = 'target = "_blank"';
					$this->add_render_attribute( $link_key, 'target', '_blank' );
				}
				if ( ! empty( $link['nofollow'] ) ) {
					$this->add_render_attribute( $link_key, 'rel', 'nofollow' );
				}
                if($link_to == 'custom' &&  $slides_to_show == 1){
                	add_action( 'amp_post_template_css',[$this,'amp_image_carousel_ccss'],999);
                }
				
				if($lightbox != 'lightbox' && $link_to == 'custom'){
					
					$image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . ''.$target_blank.'>' . $image_html . '</a>';
				}

				$image_html = $image_html;
			}

			$image_caption = $this->get_image_caption( $attachment );
			
			$slide_html = '<div class="swiper-slide-inner slide-width-'.$slides_to_show.'">' . $image_html;

			if ( ! empty( $image_caption ) ) {

				$image_caption =  wp_trim_words( $image_caption, 7,'...');
				$slide_html .= '<figcaption class="image elementor-image-carousel-caption">' . $image_caption . '</figcaption>';
			}

			$slide_html .= '</div>';

			$slides[] = $slide_html;

		}

		if ( empty( $slides ) ) {
			return;
		}

		$this->add_render_attribute( 'carousel', 'class', 'elementor-image-carousel' );

		if ( 'none' !== @$settings['navigation'] ) {
			if ( 'dots' !== @$settings['navigation'] ) {
				$this->add_render_attribute( 'carousel', 'class', 'slick-arrows-' . @$settings['arrows_position'] );
			}

			if ( 'arrows' !== @$settings['navigation'] ) {
				$this->add_render_attribute( 'carousel', 'class', 'slick-dots-' . @$settings['dots_position'] );
			}
		}

		if ( 'yes' === @$settings['image_stretch'] ) {
			$this->add_render_attribute( 'carousel', 'class', 'slick-image-stretch' );
		}
		foreach ( $settings['carousel'] as $index => $attachment ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $attachment['id'], 'thumbnail', $settings );
			if( !empty($image_url) ){
				list($img_width, $img_height, $type, $attr) = getimagesize($image_url);
				break;
			}			
		}
		?>
		<div class="elementor-image-carousel-wrapper elementor-slick-slider  elementor-slick-<?php echo $slides_to_show;?>" dir="<?php echo @$settings['direction']; ?>">
	       	<?php
	       	if(isset($settings['thumbnail_size'])){
			if($settings['thumbnail_size'] == 'custom'){
				$height = $settings['thumbnail_custom_dimension']['height'];
			}elseif($settings['thumbnail_size'] == 'thumbnail'){
				$width = 150; $height = 150;
			}elseif($settings['thumbnail_size'] == 'medium'){
				$height = 250;
			}elseif($settings['thumbnail_size'] == 'medium_large'){
				$height = 300;
			}elseif($settings['thumbnail_size'] == 'large'){
				$height = 350;
			}elseif($settings['thumbnail_size'] == 'full'){
				$height = 400;
			}}else{
				$width = 150; $height = 150;
			}
			if ( ! empty( $image_caption ) ) {
				$height = $height + 60;
			}
			$width_attr = '';
			if( $carousel_type == 'slides'){
				$layout_type = 'responsive';
				$width_attr = 'width="400"';
				$height = 300;
			}else{
				$width_attr = 'auto';
				$layout_type = 'fixed-height';
			}

			if(!empty($img_height) && $slides_to_show >6){
				$height = $img_height+10;
			}
			?>
		<amp-carousel controls id="aiccarouselID" <?php echo $width_attr;?> height="<?php echo $height;?>" layout="<?php echo $layout_type;?>" type="<?php echo $carousel_type;?>" <?php echo $lightbox;?> on="slideChange:aicselectorID.toggle(index=event.index, value=true),carouselDots.goToSlide(index=event.index)" loop>
		    	<?php echo implode( '', $slides ); ?>
		    </amp-carousel>
		    <amp-selector id="aicselectorID"
		      on="select:aiccarouselID.goToSlide(index=event.targetOption)"
		      layout="container">
		        <ul id="carouselDots" class="swiper-pagination-bullets">
		        <?php
		        $countids = count($slides);
		        for ($i=0; $i < $countids; $i++) {
		        	$selected = ($i == 0 ) ? 'selected' : ''; ?>
		         	<li class="swiper-pagination-bullet" option="<?php echo $i; ?>" <?php echo $selected; ?>></li>
		        <?php } ?>	
		        </ul>	
		    </amp-selector>
		</div>
		<?php
		
	}

	private function get_link_url( $attachment, $instance ) {
		if ('none' === @$instance['link_to'] ) {
			return false;
		}

		if ('custom' === @$instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}

			return $instance['link'];
		}

		return [
			'url' => wp_get_attachment_url( $attachment['id'] ),
		];
	}

	private function get_image_caption( $attachment ) {
		if ( is_plugin_active( 'the-elementor/elementor.php' ) ) {
		$caption_type = $this->get_settings( 'caption_type' );
		}
		else{
		$caption_type = $this->get_settings_for_display( 'caption_type' );			
		}

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $attachment['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	function amp_image_carousel_ccss(){?>
		<!-- @media(min-width:850px){
			#carousel-with-lightbox amp-img{
			    position: relative;
			    left: 22em;
			    top: 16em;
			}
        } -->

	<?php }
}
