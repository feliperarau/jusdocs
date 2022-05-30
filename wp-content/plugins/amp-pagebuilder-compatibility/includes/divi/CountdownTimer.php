<?php
if(!class_exists('AMP_ET_Builder_Module_Countdown_Timer')){
class AMP_ET_Builder_Module_Countdown_Timer extends ET_Builder_Module {
	function init() {
		$this->name       = esc_html__( 'Countdown Timer', 'amp-pagebuilder-compatibility' );
		$this->slug       = 'et_pb_countdown_timer';
		$this->vb_support = 'on';
		$this->main_css_element = '%%order_class%%.et_pb_countdown_timer';
		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'main_content' => esc_html__( 'Text', 'amp-pagebuilder-compatibility' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'text' => esc_html__( 'Text', 'amp-pagebuilder-compatibility' ),
				),
			),
		);
		$this->advanced_fields = array(
			'fonts'                 => array(
				'header' => array(
					'label'    => esc_html__( 'Title', 'amp-pagebuilder-compatibility' ),
					'css'      => array(
						'main'      => "{$this->main_css_element} h4, {$this->main_css_element} h1.title, {$this->main_css_element} h2.title, {$this->main_css_element} h3.title, {$this->main_css_element} h5.title, {$this->main_css_element} h6.title",
						'important' => array( 'size', 'plugin_all' ),
					),
					'header_level' => array(
						'default' => 'h4',
					),
				),
				'numbers' => array(
					'label'    => esc_html__( 'Numbers', 'amp-pagebuilder-compatibility' ),
					'css'      => array(
						'main'        => ".et_pb_column {$this->main_css_element} .section p.value",
						'text_shadow' => ".et_pb_column {$this->main_css_element} .section p.value, .et_pb_column {$this->main_css_element} .section.sep p",
						'important'   => 'all',
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
				'label' => array(
					'label'    => esc_html__( 'Label', 'amp-pagebuilder-compatibility' ),
					'css'      => array(
						'main'      => ".et_pb_column {$this->main_css_element} .section p.label",
						'important' => array(
							'size',
							'line-height',
						),
					),
					'line_height' => array(
						'range_settings' => array(
							'min'  => '1',
							'max'  => '100',
							'step' => '1',
						),
					),
				),
			),
			'background'            => array(
				'has_background_color_toggle' => true,
				'use_background_color' => 'fields_only',
				'options' => array(
					'background_color' => array(
						'depends_show_if'  => 'on',
						'default'          => et_builder_accent_color(),
					),
					'use_background_color' => array(
						'default'          => 'on',
					),
				),
			),
			'margin_padding' => array(
				'css' => array(
					'important' => 'all',
				),
			),
			'text'                  => array(
				'use_background_layout' => true,
				'css' => array(
					'text_orientation' => '%%order_class%% .et_pb_countdown_timer_container, %%order_class%% .title',
				),
				'options' => array(
					'text_orientation'  => array(
						'default' => 'center',
					),
					'background_layout' => array(
						'default' => 'dark',
					),
				),
			),
			'button'                => false,
		);
		$this->custom_css_fields = array(
			'container' => array(
				'label'    => esc_html__( 'Container', 'amp-pagebuilder-compatibility' ),
				'selector' => '.et_pb_countdown_timer_container',
			),
			'title' => array(
				'label'    => esc_html__( 'Title', 'amp-pagebuilder-compatibility' ),
				'selector' => '.title',
			),
			'timer_section' => array(
				'label'    => esc_html__( 'Timer Section', 'amp-pagebuilder-compatibility' ),
				'selector' => '.section',
			),
		);
		$this->help_videos = array(
			array(
				'id'   => esc_html( 'irIXKlOw6JA' ),
				'name' => esc_html__( 'An introduction to the Countdown Timer module', 'amp-pagebuilder-compatibility' ),
			),
		);
	}
	function get_fields() {
		$fields = array(
			'title' => array(
				'label'           => esc_html__( 'Countdown Timer Title', 'amp-pagebuilder-compatibility' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'This is the title displayed for the countdown timer.', 'amp-pagebuilder-compatibility' ),
				'toggle_slug'     => 'main_content',
			),
			'date_time' => array(
				'label'           => esc_html__( 'Countdown To', 'amp-pagebuilder-compatibility' ),
				'type'            => 'date_picker',
				'option_category' => 'basic_option',
				'description'     => et_get_safe_localization( sprintf( __( 'This is the date the countdown timer is counting down to. Your countdown timer is based on your timezone settings in your <a href="%1$s" target="_blank" title="WordPress General Settings">WordPress General Settings</a>', 'amp-pagebuilder-compatibility' ), esc_url( admin_url( 'options-general.php' ) ) ) ),
				'toggle_slug'     => 'main_content',
			),
		);
		return $fields;
	}
	public function amp_divi_inline_styles(){
   		if(empty($standard_styles)){$standard_styles = '';}
		$inline_styles = 'amp-date-countdown {
		      display: block;
		    }
    	.et_pb_countdown_timer{
			background-color: #7EBEC5;
			width: 100%;
		    text-align: center;
		    position: relative;
		}
		.et_pb_countdown_timer .title {
			color:#fff;
			font-size:25px;
			line_height:1.4;
		    font-weight: 400;
		    padding-bottom:15px;
		    text-align: center;
		}
		.section.values {
		    padding: 0px 15px;
		}
		.section{
			display:inline-block;
			position:relative;
		}
		.section p {
		    font-size: 48px;
		    line-height: 1.4;
		    padding-bottom: 0;
		    text-align: center;
		    display: inline-block;
		    color:#fff;
		}
		.section p.label {
		    text-align: center;
		    font-size: 14px;
		    line-height: 25px;
		    display: block;
		    margin:0;
		    color:#fff;
		}
		.sep {
		    position: relative;
		    top: -36px;
		}
		.section p.value{
			min-width:100%;
			margin:0;
			color:#fff;
		}
		@media(max-width:500px){
			.section p {
		    	font-size: 30px;
			}
			.sep {
			    top: -30px;
			}
		}
		';
        echo $standard_styles.''.$inline_styles;
  	}
  	function amp_divi_pagebuilder_scripts($data){
  		$data['amp_component_scripts']['amp-date-countdown'] = 'https://cdn.ampproject.org/v0/amp-date-countdown-0.1.js';
  		return $data;
  	}
	function render( $attrs, $content = null, $render_slug='' ) {
		add_filter('amp_post_template_data', [$this, 'amp_divi_pagebuilder_scripts']);
		add_action('amp_post_template_css',array($this,'amp_divi_inline_styles'));
		$title                = $this->props['title'];
		$date_time            = $this->props['date_time'];
		$background_layout    = $this->props['background_layout'];
		$background_color     = $this->props['background_color'];
		$use_background_color = $this->props['use_background_color'];
		$header_level         = $this->props['header_level'];
		$end_date = gmdate( 'M d, Y H:i:s', strtotime( $date_time ) );
		$gmt_offset        = get_option( 'gmt_offset' );
		$gmt_divider       = '-' === substr( $gmt_offset, 0, 1 ) ? '-' : '+';
		$gmt_offset_hour   = str_pad( abs( intval( $gmt_offset ) ), 2, "0", STR_PAD_LEFT );
		$gmt_offset_minute = str_pad( ( ( abs( $gmt_offset ) * 100 ) % 100 ) * ( 60 / 100 ), 2, "0", STR_PAD_LEFT );
		$gmt               = "GMT{$gmt_divider}{$gmt_offset_hour}{$gmt_offset_minute}";
		if ( '' !== $title ) {
			$title = sprintf( '<%2$s class="title">%s</%2$s>', esc_html( $title ), et_pb_process_header_level( $header_level, 'h4' ) );
		}
		$video_background = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();
		$background_color_style = '';
		if ( ! empty( $background_color ) && 'on' == $use_background_color ) {
			$background_color_style = sprintf( ' style="background-color: %1$s;"', esc_attr( $background_color ) );
		}
		// Module classnames
		$this->add_classname( array(
			"et_pb_bg_layout_{$background_layout}",
		) );
		if ( 'on' !== $use_background_color ) {
			$this->add_classname( 'et_pb_no_bg' );
		}
		$output = sprintf(
			'<amp-date-countdown%1$s class="%2$s" data-end-timestamp="%4$s" timestamp-seconds="%4$s"  layout="fixed-height" height="150">
					<template type="amp-mustache">
					%5$s
						<div class="days section values" >
							<p class="value">{{d}}</p>
							<p class="label">%6$s</p>
						</div>
						<div class="sep section"><p>:</p></div>
						<div class="hours section values" >
							<p class="value">{{h}}</p>
							<p class="label">%7$s</p>
						</div>
						<div class="sep section"><p>:</p></div>
						<div class="days section values" >
							<p class="value">{{m}}</p>
							<p class="label">%9$s</p>
						</div>
						<div class="sep section"><p>:</p></div>
						<div class="days section values" >
							<p class="value">{{s}}</p>
							<p class="label">%11$s</p>
						</div>
				    </template>
			</amp-date-countdown>',
			$this->module_id(),
			$this->module_classname( $render_slug ),
			$background_color_style,
			esc_attr( strtotime( "{$end_date} {$gmt}" ) ),
			$title,
			esc_html__( 'Day(s)', 'amp-pagebuilder-compatibility' ),
			esc_html__( 'Hour(s)', 'amp-pagebuilder-compatibility' ),
			esc_attr__( 'Hrs', 'amp-pagebuilder-compatibility' ),
			esc_html__( 'Minute(s)', 'amp-pagebuilder-compatibility' ),
			esc_attr__( 'Min', 'amp-pagebuilder-compatibility' ),
			esc_html__( 'Second(s)', 'amp-pagebuilder-compatibility' ),
			esc_attr__( 'Sec', 'amp-pagebuilder-compatibility' ),
			esc_attr__( 'Day', 'amp-pagebuilder-compatibility' ),
			$video_background,
			$parallax_image_background
		);
		
		return $output;
	}
}
$countdownTimerObj = new AMP_ET_Builder_Module_Countdown_Timer();
remove_shortcode( 'et_pb_countdown_timer' );
add_shortcode( 'et_pb_countdown_timer', array($countdownTimerObj, '_render'));
}