<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Testimonial extends Widget_Base {

	public function get_name() {
		return 'testimonial';
	}

	public function get_title() {
		return __( 'Hello World', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		
		$settings['testimonial_image_position'] = (!empty($settings['testimonial_image_position']) ? $settings['testimonial_image_position']:'aside');
		$settings['testimonial_alignment'] = (!empty($settings['testimonial_alignment']) ? $settings['testimonial_alignment']:'center');
		$settings['content_content_color'] = (!empty($settings['content_content_color']) ? $settings['content_content_color']:'#7a7a7a');
		$settings['image_size']['size'] = (!empty($settings['image_size']['size']) ? $settings['image_size']['size']:'60');
		$settings['image_size']['unit'] = (!empty($settings['image_size']['unit']) ? $settings['image_size']['unit']:'px');
		$settings['name_text_color'] = (!empty($settings['name_text_color']) ? $settings['name_text_color']:'#6ec1e4');
		$settings['job_text_color'] = (!empty($settings['job_text_color']) ? $settings['job_text_color']:'#54595f');
		$inline_styles = '
			.elementor-element-'.$this->get_id().' .elementor-testimonial-wrapper{
				width:100%;
				margin:0 auto;
				text-align:'.$settings['testimonial_alignment'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-content{
				color:'.$settings['content_content_color'].';
				font-size:20px;
				line-height: 1.5;
				margin-bottom: 20px;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-meta-inner{
				display: inline-flex;
			    align-items: center; 
			    justify-content: '.$settings['testimonial_alignment'].';
			    width: 100%;
			    flex-wrap: wrap;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image .elementor-testimonial-img{
				width:'.$settings['image_size']['size'].''.$settings['image_size']['unit'].';
				height:'.$settings['image_size']['size'].''.$settings['image_size']['unit'].';
				margin:0 auto;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image .elementor-testimonial-img amp-img{
				border-radius: 100%;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-name{
				font-size:16px;
				color:'.$settings['name_text_color'].';
				line-height: 1.3;
				font-weight: 600;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-job{
				font-size:14px;
				color:'.$settings['job_text_color'].';
				line-height: 1.3;
				text-align:left;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image{
				padding-right:15px;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image amp-img{
				height:100%;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-text-align-right .elementor-testimonial-meta{
				float:right;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-text-align-left .elementor-testimonial-image .elementor-testimonial-img{
				margin: 0;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image-position-top .elementor-testimonial-meta-inner{
				display:inline-block;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-image-position-top .elementor-testimonial-image {
				padding-right: 0;
				margin-bottom:20px;
			}
			.elementor-element-'.$this->get_id().' .elementor-testimonial-details{
				display: inline-flex;
    			flex-direction: column;
			}
		';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-testimonials'][$this->get_id()] = $inline_styles;
	}
	
	protected function render() {
		$this->amp_elementor_widget_styles();
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wrapper', 'class', 'elementor-testimonial-wrapper' );

		if ( $settings['testimonial_alignment'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'elementor-testimonial-text-align-' . $settings['testimonial_alignment'] );
		}

		$this->add_render_attribute( 'meta', 'class', 'elementor-testimonial-meta' );

		if ( $settings['testimonial_image']['url'] ) {
			$this->add_render_attribute( 'meta', 'class', 'elementor-has-image' );
		}

		if ( $settings['testimonial_image_position'] ) {
			$this->add_render_attribute( 'meta', 'class', 'elementor-testimonial-image-position-' . $settings['testimonial_image_position'] );
		}

		$has_content = ! ! $settings['testimonial_content'];
		$has_image = ! ! $settings['testimonial_image']['url'];
		$has_name = ! ! $settings['testimonial_name'];
		$has_job = ! ! $settings['testimonial_job'];

		if ( ! $has_content && ! $has_image && ! $has_name && ! $has_job ) {
			return;
		}

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_render_attribute( 'link', 'href', $settings['link']['url'] );

			if ( $settings['link']['is_external'] ) {
				$this->add_render_attribute( 'link', 'target', '_blank' );
			}

			if ( ! empty( $settings['link']['nofollow'] ) ) {
				$this->add_render_attribute( 'link', 'rel', 'nofollow' );
			}
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $has_content ) :
				$this->add_render_attribute( 'testimonial_content', 'class', 'elementor-testimonial-content' );

				$this->add_inline_editing_attributes( 'testimonial_content' );
				?>
				<div <?php echo $this->get_render_attribute_string( 'testimonial_content' ); ?>><?php echo $settings['testimonial_content']; ?></div>
			<?php endif; ?>

			<?php if ( $has_image || $has_name || $has_job ) : ?>
			<div <?php echo $this->get_render_attribute_string( 'meta' ); ?>>
				<div class="elementor-testimonial-meta-inner">
					<?php if ( $has_image ) : ?>
						<div class="elementor-testimonial-image">
							<figure class="elementor-testimonial-img">
							<?php
							$image_html = Group_Control_Image_Size::get_attachment_image_html( $settings, 'testimonial_image' );
							if ( ! empty( $settings['link']['url'] ) ) :
								$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
							endif;
							echo $image_html;
							?>
							</figure>
						</div>
					<?php endif; ?>

					<?php if ( $has_name || $has_job ) : ?>
					<div class="elementor-testimonial-details">
						<?php
						if ( $has_name ) :
							$this->add_render_attribute( 'testimonial_name', 'class', 'elementor-testimonial-name' );

							$this->add_inline_editing_attributes( 'testimonial_name', 'none' );

							$testimonial_name_html = $settings['testimonial_name'];
							if ( ! empty( $settings['link']['url'] ) ) :
								$testimonial_name_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_name_html . '</a>';
							endif;
							?>
							<div <?php echo $this->get_render_attribute_string( 'testimonial_name' ); ?>><?php echo $testimonial_name_html; ?></div>
						<?php endif; ?>
						<?php
						if ( $has_job ) :
							$this->add_render_attribute( 'testimonial_job', 'class', 'elementor-testimonial-job' );

							$this->add_inline_editing_attributes( 'testimonial_job', 'none' );

							$testimonial_job_html = $settings['testimonial_job'];
							if ( ! empty( $settings['link']['url'] ) ) :
								$testimonial_job_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $testimonial_job_html . '</a>';
							endif;
							?>
							<div <?php echo $this->get_render_attribute_string( 'testimonial_job' ); ?>><?php echo $testimonial_job_html; ?></div>
						<?php endif; ?>
					</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		var image = {
				id: settings.testimonial_image.id,
				url: settings.testimonial_image.url,
				size: settings.testimonial_image_size,
				dimension: settings.testimonial_image_custom_dimension,
				model: view.getEditModel()
			};
		var imageUrl = false, hasImage = '';

		if ( '' !== settings.testimonial_image.url ) {
			imageUrl = elementor.imagesManager.getImageUrl( image );
			hasImage = ' elementor-has-image';

			var imageHtml = '<img src="' + imageUrl + '" alt="testimonial" />';
			if ( settings.link.url ) {
				imageHtml = '<a href="' + settings.link.url + '">' + imageHtml + '</a>';
			}
		}

		var testimonial_alignment = settings.testimonial_alignment ? ' elementor-testimonial-text-align-' + settings.testimonial_alignment : '';
		var testimonial_image_position = settings.testimonial_image_position ? ' elementor-testimonial-image-position-' + settings.testimonial_image_position : '';
		#>
		<div class="elementor-testimonial-wrapper{{ testimonial_alignment }}">
			<# if ( '' !== settings.testimonial_content ) {
				view.addRenderAttribute( 'testimonial_content', 'class', 'elementor-testimonial-content' );

				view.addInlineEditingAttributes( 'testimonial_content' );
				#>
				<div {{{ view.getRenderAttributeString( 'testimonial_content' ) }}}>{{{ settings.testimonial_content }}}</div>
			<# } #>
			<div class="elementor-testimonial-meta{{ hasImage }}{{ testimonial_image_position }}">
				<div class="elementor-testimonial-meta-inner">
					<# if ( imageUrl ) { #>
					<div class="elementor-testimonial-image">{{{ imageHtml }}}</div>
					<# } #>

					<div class="elementor-testimonial-details">
						<# if ( '' !== settings.testimonial_name ) {
							view.addRenderAttribute( 'testimonial_name', 'class', 'elementor-testimonial-name' );

							view.addInlineEditingAttributes( 'testimonial_name', 'none' );

							var testimonialNameHtml = settings.testimonial_name;
							if ( settings.link.url ) {
								testimonialNameHtml = '<a href="' + settings.link.url + '">' + testimonialNameHtml + '</a>';
							}
							#>
							<div {{{ view.getRenderAttributeString( 'testimonial_name' ) }}}>{{{ testimonialNameHtml }}}</div>
						<# } #>

						<# if ( '' !== settings.testimonial_job ) {
							view.addRenderAttribute( 'testimonial_job', 'class', 'elementor-testimonial-job' );

							view.addInlineEditingAttributes( 'testimonial_job', 'none' );

							var testimonialJobHtml = settings.testimonial_job;
							if ( settings.link.url ) {
								testimonialJobHtml = '<a href="' + settings.link.url + '">' + testimonialJobHtml + '</a>';
							}
							#>
							<div {{{ view.getRenderAttributeString( 'testimonial_job' ) }}}>{{{ testimonialJobHtml }}}</div>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
