<?php global $redux_builder_amp;

global $template_content; 
$shortcodes = ct_template_shortcodes();

$sanitized_content = '';
if ( $shortcodes ) {
	$content = ct_do_shortcode( $shortcodes );
	//$template_content;
 	$sanitize_content = new AMPFORWP_Content( $content, array(), 
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
					) 
			)
		);
	$sanitized_content = $sanitize_content->get_amp_content();
	$GLOBALS['ampPbcOxyInCss'] = $sanitize_content->get_amp_styles();
}

add_action("amp_post_template_css" , 'amp_pbc_oxy_inline_css');
function amp_pbc_oxy_inline_css(){
	  foreach ($GLOBALS['ampPbcOxyInCss'] as $key => $value) {
	  	echo $key.'{'.$value[0].'}';
	  }
}

if(isset($redux_builder_amp['amp-design-selector'])){
 $design_selected=$redux_builder_amp['amp-design-selector'];
}
if( 4 == $redux_builder_amp['amp-design-selector'] ) { 
 ?>
<?php
	$this->load_parts( array( 'header' ) ); 
	//do_action( 'ampforwp_after_header', $this );
} 
else { ?>
	<!doctype html>
	<html amp <?php echo AMP_HTML_Utils::build_attributes_string( $this->get( 'html_tag_attributes' ) ); ?>>
	<head>


		<meta charset="utf-8">
		<?php do_action( 'amp_post_template_head', $this ); ?>

		<style amp-custom>
			<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				  if ( ! is_plugin_active( 'amp-newspaper-theme/ampforwp-custom-theme.php' ) ) {  
							  $this->load_parts( array( 'style' ) ); 
					}
		  	?>
			<?php do_action( 'amp_post_template_css', $this ); ?>
			<?php do_action( 'amp_css', $this ); ?>
			<?php do_action( 'amp_post_wc_specific_template_css', $this ); ?>
		</style>

	</head>

	<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?>">
		<?php do_action('ampforwp_body_beginning', $this); ?>
		<?php $this->load_parts( array( 'header-bar' ) ); ?>
		<?php do_action( 'ampforwp_after_header', $this ); ?>
<?php } ?>

	<?php $this->load_parts( apply_filters( 'amp_post_article_header_meta', array( ) ) ); ?>
		
		<?php $this->load_parts( apply_filters( 'amp_wc_design_elements', array( 'empty-filter' ) ) ); ?>

		<?php echo $sanitized_content; ?>

	<?php do_action( 'amp_post_template_above_footer', $this ); ?>
	<?php $this->load_parts( array( 'footer' ) ); ?>
	<?php do_action( 'ampwcpro_post_template_footer', $this ); ?>

</body>
</html>