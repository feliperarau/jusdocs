<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Image_Box extends Widget_Base {

	public function get_name() {
		return 'image-box';
	}

	public function get_title() {
		return __( 'Amp Image Box', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'#6ec1e4');
		$settings['description_color'] = (!empty($settings['description_color']) ? $settings['description_color']:'#7a7a7a');
		$settings['image_size']['size'] = (!empty($settings['image_size']['size']) ? $settings['image_size']['size']:'30');
		$settings['image_size']['unit'] = (!empty($settings['image_size']['unit']) ? $settings['image_size']['unit']:'%');
		$settings['image_space']['size'] = (!empty($settings['image_space']['size']) ? $settings['image_space']['size']:'15');
		$settings['image_space']['unit'] = (!empty($settings['image_space']['unit']) ? $settings['image_space']['unit']:'px');
		$settings['title_bottom_space']['size'] = (!empty($settings['title_bottom_space']['size']) ? $settings['title_bottom_space']['size']:'0');
		$settings['title_bottom_space']['unit'] = (!empty($settings['title_bottom_space']['unit']) ? $settings['title_bottom_space']['unit']:'px');
		// print_r($settings);
		// die;
		$inline_styles = '
			.elementor-element-'.$this->get_id().' .elementor-image-box-wrapper{
				width:100%;
			}
			.elementor-element-'.$this->get_id().' .elementor-position-center .elementor-image-box-img{
				margin: 0px auto '.$settings['image_space']['size'].''.$settings['image_space']['unit'].' auto;
				width:'.$settings['image_size']['size'].''.$settings['image_size']['unit'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-position-center .elementor-image-box-content{
				text-align:center;
			}
			.elementor-element-'.$this->get_id().' .elementor-image-box-content{
				color: '.$settings['description_color'].';
    			font-size: 17px;
			}
			.elementor-element-'.$this->get_id().' .elementor-image-box-content .elementor-image-box-title{
				font-size:16px;
				color:'.$settings['title_color'].';
				font-weight:600;
    			display: inline-block;
    			margin-bottom:'.$settings['title_bottom_space']['size'].''.$settings['title_bottom_space']['unit'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-image-box-content .elementor-image-box-title a{
				color:'.$settings['title_color'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-position-left{
				display:inline-flex;
				text-align:left;
			}
			.elementor-element-'.$this->get_id().' .elementor-position-left .elementor-image-box-img{
				margin-right:15px;
				width:'.$settings['image_size']['size'].''.$settings['image_size']['unit'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-position-right{
				display: inline-flex;
			    flex-direction: row-reverse;
			    text-align: right;
			}
			.elementor-element-'.$this->get_id().' .elementor-position-right .elementor-image-box-img{
				width:'.$settings['image_size']['size'].''.$settings['image_size']['unit'].';
				margin-left:15px;
			}
			@media(max-width:767px){
				.elementor-element-'.$this->get_id().' .elementor-position-right, .elementor-element-'.$this->get_id().' .elementor-position-left{
					display: inline-block;
				    text-align: center;
				}
				.elementor-element-'.$this->get_id().' .elementor-position-right .elementor-image-box-img, .elementor-element-'.$this->get_id().' .elementor-position-left .elementor-image-box-img{
					margin: 0px auto '.$settings['image_space']['size'].''.$settings['image_space']['unit'].' auto;
				}
			}
		';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-image-box'][$this->get_id()] = $inline_styles;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings['position'] = (!empty($settings['position']) ? $settings['position']:'center');
		$settings['title_size'] = (!empty($settings['title_size'] || isset($settings['title_size'])) ? $settings['title_size']:'h3');
		$this->amp_elementor_widget_styles();
		$has_content = ! empty( $settings['title_text'] ) || ! empty( $settings['description_text'] );

		$html = '<div class="elementor-image-box-wrapper elementor-position-'.$settings['position'].'">';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}

		if ( ! empty( $settings['image']['url'] ) ) {
			$this->add_render_attribute( 'image', 'src', $settings['image']['url'] );
			$this->add_render_attribute( 'image', 'alt', Control_Media::get_image_alt( $settings['image'] ) );
			$this->add_render_attribute( 'image', 'title', Control_Media::get_image_title( $settings['image'] ) );

			if ( $settings['hover_animation'] ) {
				$this->add_render_attribute( 'image', 'class', 'elementor-animation-' . $settings['hover_animation'] );
			}

			$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' );

			if ( ! empty( $settings['link']['url'] ) ) {
				$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
			}

			$html .= '<figure class="elementor-image-box-img">' . $image_html . '</figure>';
		}

		if ( $has_content ) {
			$html .= '<div class="elementor-image-box-content">';

			if ( ! empty( $settings['title_text'] ) ) {
				$this->add_render_attribute( 'title_text', 'class', 'elementor-image-box-title' );

				$this->add_inline_editing_attributes( 'title_text', 'none' );

				$title_html = $settings['title_text'];

				if ( ! empty( $settings['link']['url'] ) ) {
					$title_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $title_html . '</a>';
				}

				$html .= sprintf( '<%1$s %2$s>%3$s</%1$s>', $settings['title_size'], $this->get_render_attribute_string( 'title_text' ), $title_html );
			}

			if ( ! empty( $settings['description_text'] ) ) {
				$this->add_render_attribute( 'description_text', 'class', 'elementor-image-box-description' );

				$this->add_inline_editing_attributes( 'description_text' );

				$html .= sprintf( '<p %1$s>%2$s</p>', $this->get_render_attribute_string( 'description_text' ), $settings['description_text'] );
			}

			$html .= '</div>';
		}

		$html .= '</div>';

		echo $html;
	}

	protected function _content_template() {
		?>
		<#
		var html = '<div class="elementor-image-box-wrapper">';

		if ( settings.image.url ) {
			var image = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.thumbnail_size,
				dimension: settings.thumbnail_custom_dimension,
				model: view.getEditModel()
			};

			var image_url = elementor.imagesManager.getImageUrl( image );

			var imageHtml = '<img src="' + image_url + '" class="elementor-animation-' + settings.hover_animation + '" />';

			if ( settings.link.url ) {
				imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
			}

			html += '<figure class="elementor-image-box-img">' + imageHtml + '</figure>';
		}

		var hasContent = !! ( settings.title_text || settings.description_text );

		if ( hasContent ) {
			html += '<div class="elementor-image-box-content">';

			if ( settings.title_text ) {
				var title_html = settings.title_text;

				if ( settings.link.url ) {
					title_html = '<a href="' + settings.link.url + '">' + title_html + '</a>';
				}

				view.addRenderAttribute( 'title_text', 'class', 'elementor-image-box-title' );

				view.addInlineEditingAttributes( 'title_text', 'none' );

				html += '<' + settings.title_size  + ' ' + view.getRenderAttributeString( 'title_text' ) + '>' + title_html + '</' + settings.title_size  + '>';
			}

			if ( settings.description_text ) {
				view.addRenderAttribute( 'description_text', 'class', 'elementor-image-box-description' );

				view.addInlineEditingAttributes( 'description_text' );

				html += '<p ' + view.getRenderAttributeString( 'description_text' ) + '>' + settings.description_text + '</p>';
			}

			html += '</div>';
		}

		html += '</div>';

		print( html );
		#>
		<?php
	}
}
