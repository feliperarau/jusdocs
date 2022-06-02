<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Google_Maps extends Widget_Base {

	public function get_name() {
		return 'google_maps';
	}

	public function get_title() {
		return __( 'Amp Google Maps', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		$inline_styles = '';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-google-maps'][$this->get_id()] = $inline_styles;
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->amp_elementor_widget_styles();
		if ( empty( $settings['address'] ) ) {
			return;
		}

		if ( 0 === absint( $settings['zoom']['size'] ) ) {
			$settings['zoom']['size'] = 10;
		}

		printf(
			'<div class="elementor-custom-embed"><iframe frameborder="0" height="'.$settings['height']['size'].'" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe></div>',
			rawurlencode( $settings['address'] ),
			absint( $settings['zoom']['size'] ),
			esc_attr( $settings['address'] )
		);
	}

	protected function _content_template() {}
}
