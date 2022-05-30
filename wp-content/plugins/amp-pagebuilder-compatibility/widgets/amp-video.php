<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Video extends Widget_Base {

	public function get_name() {
		return 'video';
	}

	public function get_title() {
		return __( 'Amp Video', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$inline_styles = '';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-video'][$this->get_id()] = $inline_styles;
	}
	public function amp_elementor_video_styles(){
		$inline_styles = '
    .amp-overlay-video-player {position: relative;overflow: hidden; }
    /* Overlay fills the parent and sits on top of the video */
    .click-to-play-overlay { position: absolute; top: 0;  left: 0; right: 0;bottom: 0;}
    .poster-image { position: absolute; z-index: 1;}
    .poster-image img { object-fit: cover; }.elementor-custom-embed-play{z-index:999;}.elementor-element-'.$this->get_id().'.elementor-widget-video .elementor-open-inline .elementor-custom-embed-image-overlay{position:relative;}';
		echo $inline_styles;
	}
	protected function render() {
		add_action( 'amp_post_template_css',[$this,'amp_elementor_video_styles']);
		$settings = $this->get_settings_for_display();
		$this->amp_elementor_widget_styles();
	
		if(empty($settings['video_type'])){
			$settings['video_type'] = 'youtube';
		}

	    $video_url = $settings[ $settings['video_type'] . '_url' ];

		if ( 'hosted' === $settings['video_type'] ) {
			$video_url = $this->get_hosted_video_url();
		}


		if ( empty( $video_url ) ) {
			return;
		}

		if ( 'hosted' === $settings['video_type'] ) {
			ob_start();

			$this->render_hosted_video();

			$video_html = ob_get_clean();
		} else {
			$embed_params = $this->get_embed_params();

			$embed_options = $this->get_embed_options();

			//$video_html = \Elementor\Embed::get_embed_html( $video_url, $embed_params, $embed_options );
	     }  

		// if ( empty( $video_html ) ) {
		// 	echo esc_url( $video_url );

		// 	return;
		// }

		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-wrapper' );

		if(!isset($settings['lightbox'])){
			$settings['lightbox'] ="";
		}

		$loop = 'data-param-loop="0"';
		$autoplay = '';
		$play_on_ovelay = '';
		$controls = 'data-param-controls="1"';
		if(isset($settings['loop']) && !empty($settings['loop'])){
			$loop = 'data-param-loop="1"';
		}
		if(isset($settings['autoplay']) && !empty($settings['autoplay'])){
			$autoplay = 'autoplay';
			$play_on_ovelay = ', overlay-'.$this->get_id().'.play';
		}
		if(isset($settings['controls'])){
			$controls = 'data-param-controls="0"';
		}

		
		$layout = 'responsive';
		$img_src = '';
		if(isset($settings['poster']['url']) && !empty($settings['poster']['url']) ){
			$img_src = $settings['poster']['url'];
		}
		$on_tap_lightbox = '';
		$image_overlay = '';
		if ( !isset($settings['show_play_icon']) || 'yes' === $settings['show_play_icon'] ){
			$on_tap_lightbox = 'on="tap:eleOverlay-'.$this->get_id().'.hide,embed-play-icon-'.$this->get_id().'.hide'.$play_on_ovelay.'"';
		}
		if ( $this->has_image_overlay() ) {
			$image_overlay = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_overlay' ); 
			preg_match('/<img(.*?)src="(.*?)"(.*?)alt="(.*?)"(.*?)>/', $image_overlay, $matches);
			$img_src = $matches[2];
			$alt_txt = $matches[4];
		}
		$youtube_video_id = '';
		$amp_video_html = '';

		if($settings['video_type'] == 'youtube' ){
			if(isset($settings['youtube_url']) && !empty($settings['youtube_url'])){
				preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $settings['youtube_url'], $match);
				$youtube_video_id = $match[1];
				$amp_video_html = '<amp-youtube width="480" id="overlay-'.$this->get_id().'" height="270" layout="'.$layout.'" data-videoid="'.$youtube_video_id.'" '.$loop.'  '.$autoplay.' '.$controls.' ></amp-youtube>';
			}
		}elseif($settings['video_type'] == 'vimeo'){
			if (preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $settings['vimeo_url'], $regs)){
		            $video_id = $regs[3];
		            $amp_video_html = '<amp-vimeo data-videoid="'.$video_id.'" layout="'.$layout.'" width="480" height="270" '.$loop.'  '.$autoplay.' '.$controls.'></amp-vimeo>';
			}
		}elseif($settings['video_type'] == 'dailymotion'){
			preg_match_all('/^.+dailymotion.com\/(?:video|swf\/video|embed\/video|hub|swf)\/([^&?]+)/',$settings['dailymotion_url'],$matches);
			$video_id = $matches[1][0];
			$amp_video_html = '<amp-dailymotion data-videoid="'.$video_id.'" layout="'.$layout.'" data-ui-highlight="FF4081" width="480" height="270" '.$loop.'  '.$autoplay.' '.$controls.'></amp-dailymotion>';
		}else{
			$amp_video_html = '<amp-video width="480" height="270" src="'.esc_url($this->get_hosted_video_url()).'" poster="'.$img_src.'" layout="'.$layout.'" controls><div fallback><p>Your browser doesn\'t support HTML5 video.</p>
			    </div>
			    <source type="video/mp4" src="'.$this->get_hosted_video_url().'">
			  </amp-video>';
		}
		
		$this->add_render_attribute( 'video-wrapper', 'class', 'elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ) );
		
		?>
		<div <?php echo $this->get_render_attribute_string( 'video-wrapper' ); ?>>
			<?php
			$video_html ='';
			if ( ! $settings['lightbox'] ) {
				echo $video_html; // XSS ok.
			}

			if ( $this->has_image_overlay() ) {
				$this->add_render_attribute( 'image-overlay', 'class', 'elementor-custom-embed-image-overlay' );

				if ( $settings['lightbox'] ) {
					if ( 'hosted' === $settings['video_type'] ) {
						$lightbox_url = $video_url;
					} else {
						$lightbox_url = \Elementor\Embed::get_embed_url( $video_url, $embed_params, $embed_options );
					}
					$lightbox_content_animation = isset($settings['lightbox_content_animation'])?$settings['lightbox_content_animation']:'';
					$aspect_ratio = isset($settings['aspect_ratio'])?$settings['aspect_ratio']: '';
					$lightbox_options = [
						'type' => 'video',
						'videoType' => $settings['video_type'],
						'url' => $lightbox_url,
						'modalOptions' => [
							'id' => 'elementor-lightbox-' . $this->get_id(),
							'entranceAnimation' => $lightbox_content_animation,
							'videoAspectRatio' => $aspect_ratio,
						],
					];

					if ( 'hosted' === $settings['video_type'] ) {
						$lightbox_options['videoParams'] = $this->get_hosted_params();
					}

					$this->add_render_attribute( 'image-overlay', [
						'data-elementor-open-lightbox' => 'yes',
						'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
					] );

					if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
						$this->add_render_attribute( 'image-overlay', [
							'class' => 'elementor-clickable',
						] );
					}
				} else {
					//$this->add_render_attribute( 'image-overlay', 'style', 'background-image: url(' . \Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings ) . ');' );

				}

				?>
				
				<div <?php echo $this->get_render_attribute_string( 'image-overlay' ); ?>>
					<?php if ( $settings['lightbox'] ) : ?>
						<div class="amp-overlay-video-player">
						    <?php echo $amp_video_html;?>
						    <div id="eleOverlay-<?php echo $this->get_id();?>" class="click-to-play-overlay">
							    <?php if ( !isset($settings['show_play_icon']) || 'yes' === $settings['show_play_icon'] ) :?>
									<div id="embed-play-icon-<?php echo $this->get_id();?>" class="elementor-custom-embed-play" tabindex="0" role="button" <?php echo $on_tap_lightbox;?>>
										<i class="eicon-play" aria-hidden="true"></i>
										<span class="elementor-screen-only"><?php echo __( 'Play Video', 'elementor' ); ?></span>
									</div>
								<?php endif; ?>
								<amp-img alt="<?php echo $alt_txt;?>" tabindex="0" role="button" src="<?php echo $img_src;?>" placeholder layout="fill" <?php echo $on_tap_lightbox;?>></amp-img>
							</div>
						</div>
					<?php endif; ?>
					<?php  
					if(isset($settings['lightbox']) && $settings['lightbox'] !== 'yes'): ?>
						<div class="amp-overlay-video-player">
						    <?php echo $amp_video_html;?>
						    <div id="eleOverlay-<?php echo $this->get_id();?>" class="click-to-play-overlay">
							    <?php if ( !isset($settings['show_play_icon']) || 'yes' === $settings['show_play_icon'] ) :?>
									<div id="embed-play-icon-<?php echo $this->get_id();?>" class="elementor-custom-embed-play" tabindex="0" role="button" <?php echo $on_tap_lightbox;?>>
										<i class="eicon-play" aria-hidden="true"></i>
										<span class="elementor-screen-only"><?php echo __( 'Play Video', 'elementor' ); ?></span>
									</div>
								<?php endif; ?>
								<amp-img alt="<?php echo $alt_txt;?>" tabindex="0" role="button" src="<?php echo $img_src;?>" placeholder layout="fill" <?php echo $on_tap_lightbox;?>></amp-img>
							</div>
						</div>
					<?php endif;?>
				</div>
			<?php }else{
				echo $amp_video_html;
			} ?>
		</div>
		
		<?php
	}

	public function render_plain_content() {
		$settings = $this->get_settings_for_display();

		$url = $settings[ $settings['video_type'] . '_url' ];

		echo esc_url( $url );
	}

	
	public function get_embed_params() {
		$settings = $this->get_settings_for_display();
		if(!isset($settings['video_type'])){
		 $settings['video_type']='';
		}
		if(!isset($settings['autoplay'])){
		 $settings['autoplay']='';
		}
		$params = [];

		if ( $settings['autoplay'] && ! $this->has_image_overlay() ) {
			$params['autoplay'] = '1';
		}

		$params_dictionary = [];

		if ( 'youtube' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'controls',
				'mute',
				'showinfo',
				'rel',
				'modestbranding',
			];

			if ( $settings['loop'] ) {
				$video_properties = \Elementor\Embed::get_video_properties( $settings['youtube_url'] );

				$params['playlist'] = $video_properties['video_id'];
			}

			$params['start'] = $settings['start'];

			$params['end'] = $settings['end'];

			$params['wmode'] = 'opaque';
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$params_dictionary = [
				'loop',
				'mute' => 'muted',
				'vimeo_title' => 'title',
				'vimeo_portrait' => 'portrait',
				'vimeo_byline' => 'byline',
			];

			$params['color'] = str_replace( '#', '', $settings['color'] );

			$params['autopause'] = '0';
		} elseif ( 'dailymotion' === $settings['video_type'] ) {
			$params_dictionary = [
				'controls',
				'mute',
				'showinfo' => 'ui-start-screen-info',
				'logo' => 'ui-logo',
			];

			$params['ui-highlight'] = str_replace( '#', '', $settings['color'] );

			$params['start'] = $settings['start'];

			$params['endscreen-enable'] = '0';
		}

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			if(!isset($settings[ $setting_name ])){
				$settings[ $setting_name ] = "";
			}

			$setting_value = $settings[ $setting_name ] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
	}

	protected function has_image_overlay() {
		$settings = $this->get_settings_for_display();

		return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
	}

	private function get_embed_options() {
		$settings = $this->get_settings_for_display();
		if(!isset($settings['video_type'])){
		 $settings['video_type']='';
		}
		$embed_options = [];

		if(!isset($settings['start'])){
			$settings['start'] = "";
		}

		if ( 'youtube' === $settings['video_type'] ) {
			$embed_options['privacy'] = $settings['yt_privacy'];
		} elseif ( 'vimeo' === $settings['video_type'] ) {
			$embed_options['start'] = $settings['start'];
		}

		$embed_options['lazy_load'] = ! empty( $settings['lazy_load'] );

		return $embed_options;
	}

	private function get_hosted_params() {
		$settings = $this->get_settings_for_display();
		if(!isset($settings['autoplay'])){
		 $settings['autoplay']='';
		}
		$video_params = [];

		foreach ( [ 'autoplay', 'loop', 'controls' ] as $option_name ) {
			if ( isset($settings[ $option_name ]) ) {
				$video_params[] = $option_name;
			}
		}

		if ( isset($settings['mute']) ) {
			$video_params[] = 'muted';
		}

		if ( isset($settings['download_button']) && ! $settings['download_button'] )  {
			$video_params[] = 'controlsList="nodownload"';
		}

		return $video_params;
	}

	private function get_hosted_video_url() {
		$settings = $this->get_settings_for_display();

		$video_url = $settings['hosted_url']['url'];

		if ( ! $video_url ) {
			return '';
		}

		$video_url .= '#t=';

		if (isset($settings['start']) ) {
			$video_url .= $settings['start'];
		}

		if ( isset($settings['end']) ) {
			$video_url .= ',' . $settings['end'];
		}

		return $video_url;
	}

	private function render_hosted_video() {
		$video_params = $this->get_hosted_params();

		$video_url = $this->get_hosted_video_url();
		?>
		<!-- <video class="elementor-video" src="<?php echo esc_url( $video_url ); ?>"  layout="responsive" <?php echo implode( ' ', $video_params ); ?>></video> -->
		<?php
	}
}
