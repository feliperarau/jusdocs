<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Image extends Widget_Base {

	public function get_name() {
		return 'image';
	}

	public function get_title() {
		return __( 'Amp Image', 'elementor-hello-world' );
	}
	
	public function get_icon() {
		return 'eicon-posts-ticker';
	}
	
	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'image', 'photo', 'visual' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		$settings['align'] = (!empty($settings['align']) ? $settings['align']:'center');
		$sizes_type = $settings['image_size'];
		list($img_width, $img_height) = getimagesize($settings['image']['url']);
		if( $sizes_type == 'custom' ){
			$width = $settings['image_custom_dimension']['width'];
			$height = $settings['image_custom_dimension']['height'];
			if($width < 100 ){
				$width_px = $width.'px';
				$res_width = $width;
			}else{
				$width_px = '100%';
				$res_width = $width;
			}	
		}elseif($sizes_type == 'medium'){
			$width_px = '200px';	
		}elseif( $sizes_type == 'medium_large'){
			$width_px = '768px';	
		}elseif( $sizes_type == 'large'){
			$width_px = '500px';	
		}
		$alignment = '';
		if( $settings['align'] == 'center'){
			$alignment = 'margin:0 auto;';
		}else{
			$alignment = 'text-align:'.$settings['align'].';';
		}
		$width = $settings['image_custom_dimension']['width'];
		$inline_styles = '
			.elementor-element-'.$this->get_id().' amp-img{
			    width:'.$width_px.';
			    height:100%;
			}
			.elementor-element-'.$this->get_id().' .widget-image-caption {
				font-size:18px;
				color:#555;
				font-weight:400;
			}
			.elementor-element-'.$this->get_id().' .elementor-image amp-img{
				max-width: '.$res_width.'px;
    			max-height: '.$height.'px;
    			'.$alignment.'
			}';//
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-image'][$this->get_id()] = $inline_styles;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$sizes_type = $settings['image_size'];
		$this->amp_elementor_widget_styles();
		if ( empty( $settings['image']['url'] ) ) {
			return;
		}
		$has_caption = ! empty( $settings['caption'] );

		$this->add_render_attribute( 'wrapper', 'class', 'elementor-image' );

		if ( ! empty( $settings['shape'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-image-shape-' . $settings['shape'] );
		}

		$link = $this->get_link_url( $settings );

		if ( $link ) {
			$this->add_render_attribute( 'link', [
				'href' => $link['url'],
				'data-elementor-open-lightbox' => $settings['open_lightbox'],
			] );

		} ?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php if ( $has_caption ) : ?>
				<figure class="wp-caption">
			<?php endif; ?>
			<?php if ( $link ) : ?>
					<a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
			<?php endif; ?>

			<?php
			if( $sizes_type == 'custom' ){
				$width = $settings['image_custom_dimension']['width'];
				$height = $settings['image_custom_dimension']['height'];
			}elseif( $sizes_type == 'medium' ){
				$width = 200;
			}else{
				list($width, $height) = getimagesize($settings['image']['url']);
			}
			if( empty($width) || empty($height) ){
				list($width, $height) = getimagesize($settings['image']['url']);
			}
			?>
			<?php $image = Group_Control_Image_Size::get_attachment_image_html( $settings );
			preg_match_all('/<img[^>]+src="([^">]+)"/i',$image, $result);
			$image_src = $result[1][0];
			?>
			<amp-img src="<?php echo $image_src;?>" width="<?php echo $width;?>" height="<?php echo $height;?>" layout="responsive" alt="AMP"></amp-img>
			<?php if ( $link ) : ?>
					</a>
			<?php endif; ?>
			<?php if ( $has_caption ) : ?>
					<figcaption class="widget-image-caption wp-caption-text"><?php echo $settings['caption']; ?></figcaption>
			<?php endif; ?>
			<?php if ( $has_caption ) : ?>
				</figure>
			<?php endif; ?>
		</div>
		<?php
	}
	
	protected function _content_template() {
		?>
		<# if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			if ( ! image_url ) {
				return;
			}

			var link_url;

			if ( 'custom' === settings.link_to ) {
				link_url = settings.link.url;
			}

			if ( 'file' === settings.link_to ) {
				link_url = settings.image.url;
			}

			#><div class="elementor-image{{ settings.shape ? ' elementor-image-shape-' + settings.shape : '' }}"><#
			var imgClass = '',
				hasCaption = '' !== settings.caption;

			if ( '' !== settings.hover_animation ) {
				imgClass = 'elementor-animation-' + settings.hover_animation;
			}

			if ( hasCaption ) {
				#><figure class="wp-caption"><#
			}

			if ( link_url ) {
					#><a class="elementor-clickable" data-elementor-open-lightbox="{{ settings.open_lightbox }}" href="{{ link_url }}"><#
			}
						#><img src="{{ image_url }}" class="{{ imgClass }}" /><#

			if ( link_url ) {
					#></a><#
			}

			if ( hasCaption ) {
					#><figcaption class="widget-image-caption wp-caption-text">{{{ settings.caption }}}</figcaption><#
			}

			if ( hasCaption ) {
				#></figure><#
			}

			#></div><#
		} #>
		<?php
	}

	private function get_link_url( $settings ) {
		if ( 'none' === $settings['link_to'] ) {
			return false;
		}

		if ( 'custom' === $settings['link_to'] ) {
			if ( empty( $settings['link']['url'] ) ) {
				return false;
			}
			return $settings['link'];
		}

		return [
			'url' => $settings['image']['url'],
		];
	}
}
