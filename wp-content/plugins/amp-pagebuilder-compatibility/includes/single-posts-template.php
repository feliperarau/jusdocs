<?php global $redux_builder_amp, $post;
amp_header(); ?>
<div>
	<div>
	<?php
		$postId = (is_object($post)? $post->ID: '');
		if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
			$postId = ampforwp_get_frontpage_id();
		}
		if ( 'on' === get_post_meta( $postId, '_et_pb_use_builder', true ) ) {
			$layout = get_post_meta( $postId, '_et_pb_page_layout', true );
			$showtitle = ($layout=='et_full_width_page');
		}
		?>
		<div class="pg">
       		<div id="page-container" class="el-pcr"><?php //cntn-wrp ?>
				<div class="pgb sp-artl">
					<div class="pg-lft entry-content sp-left">
						<div class="et_pb_module et_pb_image_0_tb_body">
							<span class="et_pb_image_wrap"><?php amp_featured_image(); ?></span>
						</div>
						<div class="et_pb_module et_pb_text et_pb_text_0_tb_body  et_pb_text_align_left et_pb_bg_layout_light">
							<div class="et_pb_text_inner"><?php amp_title(); ?></div>
						</div>
						<div class="et_pb_module et_pb_blurb et_pb_blurb_0_tb_body  et_pb_text_align_left et_pb_blurb_position_left et_pb_bg_layout_light">
							<div class="et_pb_blurb_content">
								<div class="et_pb_main_blurb_image"><span class="et_pb_image_wrap"><span class="et-waypoint et_pb_animation_off et_pb_animation_off_tablet et_pb_animation_off_phone et-pb-icon et-animated"></span></span></div>
								<div class="et_pb_blurb_container">
									<div class="et_pb_blurb_description"><?php amp_date(); ?></div>
								</div>
							</div> <!-- .et_pb_blurb_content -->
						</div>
						<div class="et_pb_module et_pb_post_content et_pb_post_content_0_tb_body"><?php amp_content(); ?></div>
					</div>
					<?php if ( is_active_sidebar( 'swift-sidebar' ) ) : ?>
					<div class="sdbr-right">
						<?php 
							$sanitized_sidebar = ampforwp_sidebar_content_sanitizer('swift-sidebar');
							$sidebar_output = '';
							if ( $sanitized_sidebar) {
								$sidebar_output = $sanitized_sidebar->get_amp_content();
								$sidebar_output = apply_filters('ampforwp_modify_sidebars_content',$sidebar_output);
							}
				            echo do_shortcode($sidebar_output); // amphtml content, no kses
						?>
					</div>
				<?php endif; ?>
				</div><!-- /.cntr -->

			</div>
			<?php if( amp_pb_compatibility_showhide_component('comment') && $showtitle ){ // Level up Condition starts ?>
				<div class="cmts">
					<?php amp_comments();?>
				</div>
			<?php } // Level up Condition ends  ?>
	        
		</div>
	</div>
</div>
<?php amp_footer()?>