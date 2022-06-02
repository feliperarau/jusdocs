<?php global $redux_builder_amp, $post;
amp_header(); ?>
<div>
	<div>
	<?php
		
        $el_kit_class = "";
		$postId = (is_object($post)? $post->ID: '');
		if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
			$postId = ampforwp_get_frontpage_id();
		}
		if ( function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) || 'on' === get_post_meta( $postId, '_et_pb_use_builder', true ) ) {
			$layout = get_post_meta( $postId, '_et_pb_page_layout', true );
			$showtitle = ($layout=='et_full_width_page');
		}elseif( class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($postId) ){
			$layout = get_page_template_slug( $postId );
			$showtitle = ($layout=='elementor_header_footer' || $layout == 'elementor_canvas' || $layout == '');			
	        $id = get_option('elementor_active_kit');
			if ( $id ) {
				$el_kit_class = 'elementor-kit-'.$id;
			}
		}
		if( amp_pb_compatibility_showhide_component('title') && $showtitle ){
		 	amp_title(); 
		}
		?>
		<div class="pg">
       		<div id="page-container" class="amp-pbc <?php echo $el_kit_class;?>"><?php //cntn-wrp ?>
				<div class="pgb">
					<div class="pg-lft entry-content">
						<?php amp_content(); ?>
					</div>
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