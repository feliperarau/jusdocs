<?php
if(!class_exists('AMP_ET_Builder_Module_Video')){
class AMP_ET_Builder_Module_Video extends ET_Builder_Module {
	function init() {
		$this->name = esc_html__( 'Video', 'et_builder' );
		$this->slug = 'et_pb_video';
		$this->vb_support = 'on';

		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Video', 'et_builder' ),
					'overlay'      => esc_html__( 'Overlay', 'et_builder' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'play_icon' => esc_html__( 'Play Icon', 'et_builder' ),
				),
			),
		);

		$this->custom_css_fields = array(
			'video_icon' => array(
				'label'    => esc_html__( 'Video Icon', 'et_builder' ),
				'selector' => '.et_pb_video_play',
			),
		);

		$this->advanced_fields = array(
			'background'            => array(
				'options' => array(
					'background_color' => array(
						'depends_on'      => array(
							'custom_padding',
						),
						'depends_on_responsive' => array(
							'custom_padding',
						),
						'depends_show_if_not' => array(
							'',
							'|||',
						),
						'is_toggleable' => true,
					),
				),
			),
			'box_shadow'            => array(
				'default' => array(
					'css' => array(
						'custom_style' => true,
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling
				),
				'custom_padding' => array(
					'responsive_affects' => array(
						'background_color',
					),
				),
			),
			'fonts'                 => false,
			'text'                  => false,
			'button'                => false,
		);

		$this->help_videos = array(
			array(
				'id'   => esc_html( '3jXN8CBz0TU' ),
				'name' => esc_html__( 'An introduction to the Video module', 'et_builder' ),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'src' => array(
				'label'              => esc_html__( 'Video MP4/URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Video MP4 File', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Video', 'et_builder' ),
				'description'        => esc_html__( 'Upload your desired video in .MP4 format, or type in the URL to the video you would like to display', 'et_builder' ),
				'toggle_slug'        => 'main_content',
				'computed_affects' => array(
					'__video',
				),
			),
			'src_webm' => array(
				'label'              => esc_html__( 'Video Webm', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'data_type'          => 'video',
				'upload_button_text' => esc_attr__( 'Upload a video', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose a Video WEBM File', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Video', 'et_builder' ),
				'description'        => esc_html__( 'Upload the .WEBM version of your video here. All uploaded videos should be in both .MP4 .WEBM formats to ensure maximum compatibility in all browsers.', 'et_builder' ),
				'toggle_slug'        => 'main_content',
				'computed_affects' => array(
					'__video',
				),
			),
			'image_src' => array(
				'label'              => esc_html__( 'Image Overlay URL', 'et_builder' ),
				'type'               => 'upload',
				'option_category'    => 'basic_option',
				'upload_button_text' => esc_attr__( 'Upload an image', 'et_builder' ),
				'choose_text'        => esc_attr__( 'Choose an Image', 'et_builder' ),
				'update_text'        => esc_attr__( 'Set As Image', 'et_builder' ),
				'additional_button'  => sprintf(
					'<input type="button" class="button et-pb-video-image-button" value="%1$s" />',
					esc_attr__( 'Generate From Video', 'et_builder' )
				),
				'additional_button_type' => 'generate_image_url_from_video',
				'additional_button_attrs' => array(
					'video_source' => 'src',
				),
				'classes'            => 'et_pb_video_overlay',
				'description'        => esc_html__( 'Upload your desired image, or type in the URL to the image you would like to display over your video. You can also generate a still image from your video.', 'et_builder' ),
				'toggle_slug'        => 'overlay',
				'computed_affects' => array(
					'__video_cover_src',
				),
			),
			'play_icon_color' => array(
				'label'             => esc_html__( 'Play Icon Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'play_icon',
			),
			'__video' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'ET_Builder_Module_Video', 'get_video' ),
				'computed_depends_on' => array(
					'src',
					'src_webm',
				),
				'computed_minimum' => array(
					'src',
					'src_webm',
				),
			),
			'__video_cover_src' => array(
				'type'                => 'computed',
				'computed_callback'   => array( 'ET_Builder_Module_Video', 'get_video_cover_src' ),
				'computed_depends_on' => array(
					'image_src',
				),
				'computed_minimum' => array(
					'image_src',
				),
			),

		);
		return $fields;
	}

	static function get_video( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'src'      => '',
			'src_webm' => '',
			'id'	   => ''
		);

		$args = wp_parse_args( $args, $defaults );

		$video_src = '';

		if ( false !== et_pb_check_oembed_provider( esc_url( $args['src'] ) ) ) {
			$video_src = wp_oembed_get( esc_url( $args['src'] ) );
		} else {
			$video_src = sprintf( '
				<video %3$s controls>
					%1$s
					%2$s
				</video>',
				( '' !== $args['src'] ? sprintf( '<source type="video/mp4" src="%s" />', esc_url( $args['src'] ) ) : '' ),
				( '' !== $args['src_webm'] ? sprintf( '<source type="video/webm" src="%s" />', esc_url( $args['src_webm'] ) ) : '' ),
				( '' !== $args['id'] ? sprintf( ' id="%s" ', esc_attr( $args['id'] ) ) : '' )
			);

			//wp_enqueue_style( 'wp-mediaelement' );
			//wp_enqueue_script( 'wp-mediaelement' );
		}

		return $video_src;
	}

	static function get_video_cover_src( $args = array(), $conditional_tags = array(), $current_page = array() ) {
		$defaults = array(
			'image_src' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$image_output = '';

		if ( '' !== $args['image_src'] ) {
			$image_output = et_pb_set_video_oembed_thumbnail_resolution( $args['image_src'], 'high' );
		}

		return $image_output;
	}
	public function amp_divi_inline_styles(){
    	$standard_styles = '/* Video Module */
.et_pb_video {	position: relative;}
.et_pb_video_box {
	display: block;
	position: relative;
	z-index: 1;
	line-height: 0;
}
.et_pb_video_box video {
	width: 100% !important;
	height: auto !important;
}
.et_pb_video_overlay {
	position: absolute;
	z-index: 2;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-repeat: no-repeat;
	background-position: center;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	background-size: cover;
	cursor: pointer;
}
.et_pb_video_play:before {
	font-family: "ETmodules";
	content: "\49";
}
.et_pb_video_play {
	display: block;
	position: absolute;
	z-index: 100;
	top: 50%;
	left: 50%;
	color: #fff;
}
.et_pb_bg_layout_light .et_pb_video_play {
	color: #333;
}
.et_pb_video_overlay_hover {
	position: absolute;
	z-index: 100;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0);
	-webkit-transition: all 0.5s ease-in-out;
	-moz-transition: all 0.5s ease-in-out;
	-o-transition: all 0.5s ease-in-out;
	transition: all 0.5s ease-in-out;
}
.et_pb_video .et_pb_video_overlay_hover:hover {
	background: rgba(0, 0, 0, 0.6);
}
/* Column Adjustments */
.et_pb_column_4_4 .et_pb_video_play,
.et_pb_column_3_4 .et_pb_video_play,
.et_pb_column_2_3 .et_pb_video_play,
.et_pb_column_1_2 .et_pb_video_play {
	margin-top: -3rem;
	margin-left: -3rem;
	font-size: 6rem;
	line-height: 6rem;
}
.et_pb_column_3_8 .et_pb_video_play,
.et_pb_column_1_3 .et_pb_video_play,
.et_pb_column_1_4 .et_pb_video_play {
	margin-top: -1.5rem;
	margin-left: -1.5rem;
	font-size: 3rem;
	line-height: 3rem;
}
';
		$inline_styles = '.video-player {
			      position: relative;
			      overflow: hidden;
			    }
		    .video-player {
		      position: relative;
		      overflow: hidden;
		    }
		    .click-to-play-overlay {
		      position: absolute;
		      top: 0;
		      left: 0;
		      right: 0;
		      bottom: 0;
		    }

		    .poster-image {
		      position: absolute;
		      z-index: 1;
		    }

		    .poster-image img {
		      object-fit: cover;
		    }

		    .video-title {
		      position: absolute;
		      z-index: 2;

		      /* Align to the top left */
		      top: 0;
		      left: 0;

		      font-size: 1.3em;
		      background-color: rgba(0,0,0,0.8);
		      color: #fafafa;
		      padding: 0.5rem;
		      margin: 0px;
		    }

		    .play-icon {
		      position: absolute;
		      z-index: 2;

		      width: 100px;
		      height: 100px;

		      background-image: url(https://amp.dev/static/samples/img/play-icon.png);
		      background-repeat: no-repeat;
		      background-size: 100% 100%;

		      
		      top: 50%;
		      left: 50%;
		      transform: translate(-50%, -50%);

		      cursor: pointer;
		      opacity: 0.9;
		    }

		    .play-icon:hover, .play-icon:focus {
		      opacity: 1;
		    }';
        echo $standard_styles.''.$inline_styles;
  	}
  	function amp_divi_pagebuilder_scripts($data){
  		$data['amp_component_scripts']['amp-video'] = 'https://cdn.ampproject.org/v0/amp-video-0.1.js';
  		$data['amp_component_scripts']['amp-facebook'] = 'https://cdn.ampproject.org/v0/amp-facebook-0.1.js';
  		return $data;
  	}
	function render( $attrs, $content = null, $render_slug='' ) {
		add_filter('amp_post_template_data', [$this, 'amp_divi_pagebuilder_scripts']);
		add_action('amp_post_template_css',array($this,'amp_divi_inline_styles'));
		$src             = $this->props['src'];
		$src_webm        = $this->props['src_webm'];
		$image_src       = $this->props['image_src'];
		$play_icon_color = $this->props['play_icon_color'];

		$module_id = '';
		preg_match('/(\d+)/',  $this->module_classname( $render_slug ), $matches);
		if( isset($matches[0]) ) {
			$module_id = "_".$matches[0];
		}

		$video_src       = self::get_video( array(
			'src'      => $src,
			'src_webm' => $src_webm,
			'id'=>'myVideo'.$module_id
		) );

		$image_output = self::get_video_cover_src( array(
			'image_src' => $image_src,
		) );

		$video_background          = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();

		if ( '' !== $play_icon_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => '%%order_class%% .et_pb_video_overlay .et_pb_video_play',
				'declaration' => sprintf(
					'color: %1$s;',
					esc_html( $play_icon_color )
				),
			) );
		}

	if(strpos($src, 'facebook') !== false){
			$output = '<amp-facebook 
			width="552" 
			height="310" 
			layout="responsive" 
			data-embed-as="video"
			data-href="'.$src.'">
            </amp-facebook>';
	}elseif(strpos($video_src, 'iframe') !== false){
		$output = sprintf(
			'<div%2$s class="%3$s video-player" style="z-index: 1;">
		      %1$s
		      	<div id="myOverlay'.$module_id.'" class="click-to-play-overlay">
		        	<div class="play-icon" role="button" tabindex="0"  on="tap:myOverlay'.$module_id.'.hide, myVideo'.$module_id.'.play"></div>%4$s</div>
		    </div>',
			( '' !== $video_src ? $video_src : '' ),
			$this->module_id(),
			$this->module_classname( $render_slug ),
			( '' !== $image_output
				? sprintf(
					'<amp-img class="poster-image" layout="fill" src="%1$s"></amp-img>',
					esc_attr( $image_output )
				)
				: ''
			),
			$video_background,
			$parallax_image_background
		);
	}else{
		$output = sprintf(
			'<div%2$s class="%3$s video-player">
		      %1$s
		      	<div id="myOverlay'.$module_id.'" class="click-to-play-overlay">
		        	<div class="play-icon" role="button" tabindex="0"  on="tap:myOverlay'.$module_id.'.hide, myVideo'.$module_id.'.play"></div>%4$s</div>
		    </div>',
			( '' !== $video_src ? $video_src : '' ),
			$this->module_id(),
			$this->module_classname( $render_slug ),
			( '' !== $image_output
				? sprintf(
					'<amp-img class="poster-image" layout="fill" src="%1$s"></amp-img>',
					esc_attr( $image_output )
				)
				: ''
			),
			$video_background,
			$parallax_image_background
		);
	}
		
		return $output;
	}
}

$videoObj = new AMP_ET_Builder_Module_Video();
remove_shortcode( 'et_pb_video' );
add_shortcode( 'et_pb_video', array($videoObj, '_render'));
}