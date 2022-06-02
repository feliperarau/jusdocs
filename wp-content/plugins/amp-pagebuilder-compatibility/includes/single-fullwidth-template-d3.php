<?php use AMPforWP\AMPVendor\AMP_HTML_Utils;
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php global $redux_builder_amp;?>
<!doctype html>
<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
<head>
	<meta charset="utf-8">
	<?php do_action('amp_experiment_meta', $this); ?>
    <link rel="dns-prefetch" href="//cdn.ampproject.org">
	<?php do_action( 'amp_post_template_head', $this ); ?>
	<style amp-custom>
		
		<?php $this->load_parts( array( 'style' ) ); ?>
		<?php do_action( 'amp_post_template_css', $this ); ?>
	</style>
</head>
<body <?php ampforwp_body_class('design_3_wrapper');?> >
 <?php $this->load_parts( array( 'header-bar' ) );?>
<div>
	<div>
	<?php
		$post ='';
		$postId = (is_object($post)? $post->ID: '');
		if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
			$postId = ampforwp_get_frontpage_id();
		}
		if ( 'on' === get_post_meta( $postId, '_et_pb_use_builder', true ) ) {
			$layout = get_post_meta( $postId, '_et_pb_page_layout', true );
			$showtitle = ($layout=='et_full_width_page');
		}elseif( class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($postId) ){
			$layout = get_page_template_slug( $postId );
			$showtitle = ($layout=='elementor_header_footer' || $layout == 'elementor_canvas' || $layout == '');
			
		}
		if( amp_pb_compatibility_showhide_component('title') && $showtitle ){
		 	amp_title(); 
		}
	?>
		<div class="pg">
       		<div id="page-container" class="el-pcr"><?php //cntn-wrp ?>
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
<?php do_action( 'amp_post_template_above_footer', $this ); ?>
<?php $this->load_parts( array( 'footer' ) ); ?>
<?php do_action( 'amp_post_template_footer', $this ); ?>
</body>
</html>

