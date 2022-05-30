<?php global $redux_builder_amp, $post;
amp_header(); ?>
<div class="">
	<div class="">
	<?php
		
		$postId = (is_object($post)? $post->ID: '');
		if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
			$postId = ampforwp_get_frontpage_id();
		}		
		if( amp_pb_compatibility_showhide_component('title') && $showtitle ){
		 	amp_title(); 
		}
	?>

	<?php 
	add_filter( 'the_content','render_content', 10 ); 
	function render_content( $content ) {
		$FLBuilder = new FLBuilder();
		$post_id   = FLBuilderModel::get_post_id( true );
		$enabled   = FLBuilderModel::is_builder_enabled( $post_id );
		$rendering = $post_id === $FLBuilder::$post_rendering;
		$do_render = apply_filters( 'fl_builder_do_render_content', true, $post_id );
		$in_loop   = in_the_loop();
		$is_global = in_array( $post_id, FLBuilderModel::get_global_posts() );

		 

			// Set the post rendering ID.
			$FLBuilder::$post_rendering = $post_id;
			// print_r($post_id);die;
			// Try to enqueue here in case it didn't happen in the head for this layout.
			$FLBuilder::enqueue_layout_styles_scripts();

			// Render the content.
			ob_start();
			$content = $FLBuilder::render_content_by_id( $post_id ); 
			if ($content) {
		 $sanitized_excerpt = new AMPFORWP_Content( $content, array(), 
			apply_filters( 'ampforwp_content_sanitizers',
				array( 
					'AMP_Style_Sanitizer' 		=> array(),
					'AMP_Blacklist_Sanitizer' 	=> array(),
					'AMP_Img_Sanitizer' 		=> array(),
					'AMP_Video_Sanitizer' 		=> array(),
					'AMP_Audio_Sanitizer' 		=> array(),
					'AMP_Iframe_Sanitizer' 		=> array(
						'add_placeholder' 		=> true,
					)
				) ) );
		$sanitized_excerpt = $sanitized_excerpt->get_amp_content();

		echo $sanitized_excerpt;
	}

			$content = ob_get_clean();

			// Clear the post rendering ID.
			$FLBuilder::$post_rendering = null;
		 

		return $content;
	}

	?>
		<div class="pg">
       		<div id="page-container" class="ux-pcr"><?php //cntn-wrp ?>
				<div class="pgb">
					<div class="pg-lft entry-content">
						<?php  
						$content = the_content();
						 ?>
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
<?php do_action( 'amp_post_template_above_footer', $this ); ?>
	<?php $this->load_parts( array( 'footer' ) ); ?>
	<?php do_action( 'ampwcpro_post_template_footer', $this ); ?>