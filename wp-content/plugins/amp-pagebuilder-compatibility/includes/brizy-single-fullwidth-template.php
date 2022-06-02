<?php global $redux_builder_amp, $post;
amp_header(); ?>
<div>
	<div>
	<?php
		
		$postId = (is_object($post)? $post->ID: '');
		if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
			$postId = ampforwp_get_frontpage_id();
		}		
		if( amp_pb_compatibility_showhide_component('title') && $showtitle ){
		 	amp_title(); 
		}
	?>
		<div class="pg">
       		<div id="page-container" class="brizy-pcr"><?php //cntn-wrp ?>
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