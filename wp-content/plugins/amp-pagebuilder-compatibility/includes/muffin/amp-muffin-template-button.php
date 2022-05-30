<?php

if (! function_exists('button_amp_pb_compatibility_for_muffin')) {
	function button_amp_pb_compatibility_for_muffin($attr, $content = null)
	{
		extract(shortcode_atts(array(

			'title' => 'Button',
			'link' => '',
			'target' => '',
			'align' => '',

			'icon' => '',
			'icon_position' => 'left',

			'color' => '',
			'font_color' => '',

			'size' => 2,
			'full_width' => '',

			'class' => '',
			'rel' => '',
			'download' => '',
			'onclick' => '',

		), $attr));

		// target

		if( 'lightbox' === $target ) {
			$target_escaped = false;
			$rel = 'prettyphoto '. $rel; // do not change order
		} elseif ( $target ) {
			$target_escaped = 'target="_blank"';
		} else {
			$target_escaped = false;
		}

		// download

		if ($download) {
			$download_escaped = 'download="'. $download .'"';
		} else {
			$download_escaped = false;
		}

		// onclick

		if ($onclick) {
			$onclick_escaped = 'onclick="'. $onclick .'"';
		} else {
			$onclick_escaped = false;
		}

		// icon_position

		if ($icon_position != 'left') {
			$icon_position = 'right';
		}

		// FIX | prettyphoto

		if ( strpos( $class, 'prettyphoto' ) !== false ) {
			$class = str_replace( 'prettyphoto', '', $class );
			$rel = 'prettyphoto '. $rel; // do not change order
		}

		// class

		if ( $icon ) {
			$class .= ' has-icon';
			$class .= ' button_'. $icon_position;
		}

		if ( $full_width ) {
			$class .= ' button_full_width';
		}

		if ($size) {
			$class .= ' button_size_'. $size;
		}

		// custom color

		$style = '';
		$style_icon	= '';

		if ($color) {
			if (strpos($color, '#') === 0) {
				if ( 'stroke' == mfn_opts_get( 'button-style' ) ) {

					// Stroke | Border
					$style .= 'border-color:'. $color .'!important;';
					$class .= ' button_stroke_custom';

				} else {

					// Default | Background
					$style .= 'background-color:'. $color .'!important;';

				}
			} else {
				$class .= ' button_'. $color;
			}
		} else {
			if( 'dark' == mfn_brightness( mfn_opts_get( 'background-button', '#f7f7f7' ) ) ){
				$class .= ' button_dark';
			}
		}

		if ($font_color) {
			$style .= 'color:'. $font_color .';';
			$style_icon = 'color:'. $font_color .'!important;';
		}

		if ($style) {
			$style_escaped = ' style="'. esc_attr( $style ) .'"';
		} else {
			$style_escaped = false;
		}

		if ($style_icon) {
			$style_icon_escaped = ' style="'. $style_icon .'"';
		} else {
			$style_icon_escaped = false;
		}

		// rel (do not move up)

		if( $rel ){
			$rel_escaped = 'rel="'. esc_attr( $rel ) .'"';
		} else {
			$rel_escaped = false;
		}

		// link attributes

		// This variable has been safely escaped above in this function
		$attributes_escaped = $style_escaped .' '. $target_escaped .' '. $rel_escaped .' '. $download_escaped .' '. $onclick_escaped;

		// output -----

		$output = '';

		if ( $align ) {
			$output .= '<div class="button_align align_'. esc_attr( $align ) .'">';
		}

			// This variable has been safely escaped above in this function
			$output .= '<a class="button '. esc_attr( $class ) .'" href="'. esc_url( $link ) .'"'. $attributes_escaped .'>';

				if ($icon) {
					// This variable has been safely escaped above in this function
					$output .= '<span class="button_icon"><i class="'. esc_attr( $icon ) .'" '. $style_icon_escaped .'></i></span>';
				}
				if ($title) {
					$output .= '<span class="button_label">'. wp_kses( $title, mfn_allowed_html( 'button' ) ) .'</span>';
				}

			$output .= '</a>';

		if ($align) {
			$output .= '</div>';
		}

		$output .= "\n";

		return $output;
	}
}