<?php
/**
 * Slider start template
 */

$settings = $this->get_settings_for_display();
$class_array[] = 'jet-slider__items';
$class_array[] = 'sp-slides';

$classes = implode( ' ', $class_array );
$autoplay_delay = $settings['slider_autoplay_delay'];
$autoplay = $settings['slider_autoplay'];
$autoplay_markup = "";
if($autoplay  == "true"){
	$autoplay_markup = "autoplay delay='".$autoplay_delay."'";
}
?>

<div class="slider-pro"><?php
	echo sprintf( '<div class="jet-slider__arrow-icon-%s hidden-html">%s</div>', $this->get_id(), $this->_render_icon( 'slider_navigation_icon_arrow', '%s', '', false ) );
	echo sprintf( '<div class="jet-slider__fullscreen-icon-%s hidden-html">%s</div>', $this->get_id(), $this->_render_icon( 'slider_fullscreen_icon', '%s', '', false ) );
	?><div class="<?php echo $classes; ?>"><amp-carousel width="400" height="200" layout="responsive" type="slides" <?php echo $autoplay_markup; ?>>

