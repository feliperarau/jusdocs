<?php
/**
 * Image Comparison item template
 */

$settings = $this->get_settings();
$prevArrow = $settings['handle_prev_arrow'];
$nextArrow = $settings['handle_next_arrow'];
$starting_position = $settings['starting_position'];
$starting_position_string = $starting_position['size'] . $starting_position['unit'];

$item_before_label = $this->__loop_item( array( 'item_before_label' ), '%s' );
$item_before_image = $this->__loop_item( array( 'item_before_image', 'url' ), '%s' );
$item_after_label = $this->__loop_item( array( 'item_after_label' ), '%s' );
$item_after_image = $this->__loop_item( array( 'item_after_image', 'url' ), '%s' );
$initial_slider_pos = '50';
if(isset($settings['starting_position']['size']) && !empty($settings['starting_position']['size'])){
	$initial_slider_pos = $settings['starting_position']['size']/100;
	$initial_slider_pos = round($initial_slider_pos, 1);
}
$placeholder = '';
if(strpos($item_before_image, 'placeholder.png') !==false || strpos($item_after_image, 'placeholder.png') !==false){
	$placeholder = 'style="display:none;"';
}
?>
<div class="jet-image-comparison__item" style="margin: 8px;">
	<div class="jet-image-comparison__container jet-juxtapose" data-prev-icon="<?php echo $prevArrow; ?>" data-next-icon="<?php echo $nextArrow; ?>" data-makeresponsive="true" data-startingposition="<?php echo $starting_position_string; ?>" <?php echo $placeholder; ?>>
		<amp-image-slider class="triangle-hint" width="300" height="200" layout="responsive" initial-slider-position="<?php echo $initial_slider_pos;?>" disable-hint-reappear>
			<amp-img class="jet-image-comparison__before-image a3-notlazy" src="<?php echo $item_before_image; ?>" alt="" layout="fill"></amp-img>
			<amp-img class="jet-image-comparison__after-image a3-notlazy" src="<?php echo $item_after_image; ?>" alt="" layout="fill"></amp-img>
				<div class="label label-left-center"><?php echo $item_before_label;?></div>
    			<div class="label label-right-center"><?php echo $item_after_label;?></div>
		</amp-image-slider>
	</div>
</div>