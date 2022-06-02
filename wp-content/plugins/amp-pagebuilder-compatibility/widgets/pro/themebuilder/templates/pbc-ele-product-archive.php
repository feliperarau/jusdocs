<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

// theme
$location_manager = \ElementorPro\Modules\ThemeBuilder\Module::instance()->get_locations_manager();

ob_start();
// It cause a `require_once` so, in the get_header it self it will not be required again.
$location_manager->do_location( 'archive' );
$archive_shop_html = ob_get_contents();

ob_get_clean();

$sanitizer_obj = new AMPFORWP_Content( $archive_shop_html,
						array(), 
						apply_filters( 'amp_content_sanitizers', 
							array( 'AMP_Img_Sanitizer' => array(), 
								'AMP_Blacklist_Sanitizer' => array(),
								'AMP_Style_Sanitizer' => array(), 
								'AMP_Video_Sanitizer' => array(),
		 						'AMP_Audio_Sanitizer' => array(),
		 						'AMP_Iframe_Sanitizer' => array(
									 'add_placeholder' => true,
								 ),
							) 
						) 
					);
$sanitizer_script = $sanitizer_obj->get_amp_scripts();
$GLOBALS['sanitized_wc_styles_arch'] = $sanitizer_obj->get_amp_styles();
$sanitizer_content = $sanitizer_obj->get_amp_content();
// function test(){
//echo $GLOBALS['a'];
// }
//print_r($GLOBALS['sanitized_wc_styles']);die;

add_action("amp_post_template_css" , 'amp_pbc_wc_archive_shop_inline_css');
function amp_pbc_wc_archive_shop_inline_css(){
	global $redux_builder_amp;
	foreach ($GLOBALS['sanitized_wc_styles_arch'] as $key => $value) {
		echo $key.'{'.$value[0].'}';
	}//die;
	if(true == $redux_builder_amp['ampforwp-wcp-infinite-scroll-archive'] && true == $redux_builder_amp['ampforwp-wcp-infinite-scroll']){
		echo '.ampwoocommerce nav.woocommerce-pagination ul{display:none;}';
	}
}


global $redux_builder_amp;
$pluginsData = array();
$pluginsData = get_transient( 'ampforwp_themeframework_active_plugins' );
if(isset($redux_builder_amp['amp-design-selector'])){
 $design_selected=$redux_builder_amp['amp-design-selector'];
}
if( 4 == $redux_builder_amp['amp-design-selector'] ) { 

	$this->load_parts( array( 'header' ) );
	do_action( 'ampforwp_after_header', $this );
}
elseif( isset($pluginsData[$design_selected]['value']) && isset($redux_builder_amp['amp-design-selector']) && $pluginsData[$design_selected]['value'] === $redux_builder_amp['amp-design-selector']){
if(function_exists('amp_header')){
	amp_header();
}
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

	<body class="<?php echo esc_attr( $this->get( 'body_class' ) ); ?> woocommerce  woocommerce-page ">
		<?php do_action('ampforwp_body_beginning', $this); ?>
		<?php $this->load_parts( array( 'header-bar' ) ); ?>
		<?php do_action( 'ampforwp_after_header', $this ); ?>
<?php } ?>
<?php //$allStaticData = ampforwp_product_details_generator('array'); 
global $post, $woocommerce, $wp, $redux_builder_amp; ?>
<!-- <amp-state id="product">
  <script type="application/json"><?php //echo ampforwp_product_details_generator();
   ?>
  </script>
</amp-state>
 --><amp-state id="wc_product_cart">
  <script type="application/json"><?php echo json_encode(array("cartvalue"=>1));
   ?>
  </script>
</amp-state>
<div class="ampwoocommerce">
<div id="content" class="v3_wc_content_wrap">
	<?php
	 echo  $sanitizer_content;
	
	if(true == $redux_builder_amp['ampforwp-wcp-infinite-scroll-archive'] && true == $redux_builder_amp['ampforwp-wcp-infinite-scroll']){
		do_action('ampforwp_loop_before_pagination'); 
	}
	?>
</div>
</div>
	<?php do_action( 'amp_post_template_above_footer', $this ); ?>
	<?php $this->load_parts( array( 'footer' ) ); ?>
	<?php do_action( 'ampwcpro_post_template_footer', $this ); ?>


</body>
</html>
