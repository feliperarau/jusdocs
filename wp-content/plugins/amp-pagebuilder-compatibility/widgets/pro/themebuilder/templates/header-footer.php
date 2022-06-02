<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $redux_builder_amp;

\Elementor\Plugin::$instance->frontend->add_body_class( 'elementor-template-full-width' );
/**
 * Before Header-Footer page template content.
 *
 * Fires before the content of Elementor Header-Footer page template.
 *
 * @since 2.0.0
 */
do_action( 'elementor/page_templates/header-footer/before_content' );
$sanitized_header_content =  $sanitized_footer = '';

    ob_start();
	get_header();

	$header  = ob_get_contents();

	ob_get_clean();

	if( isset($redux_builder_amp['elem-themebuilder_header']) && $redux_builder_amp['elem-themebuilder_header'] == 1  && preg_match('/(.*?)<div data-elementor-type="header"(.*)/s', $header)){
		$header_content = preg_replace('/(.*?)<div data-elementor-type="header"(.*)/s', '<div data-elementor-type="header"$2', $header);
       
        if(preg_match('/<form/s', $header_content)){           
		$header_content = preg_replace('/<form/s', '<form target="_top"', $header_content);
			$action_url = esc_url( get_bloginfo('url') );
			$action_url = preg_replace('#^http?:#', '', $action_url);
        $header_content = preg_replace('/<div class="(.*?)elementor-widget-search-form(.*?)"(.*?)<div(.*?)<form(.*?)<\/form>\s+<\/div>\s+<\/div>/s','<div class="$1elementor-widget-search-form$2"><div$4
                        <a title="search" class="lb icon-src" href="#search"></a>
                        <div class="lb-btn"> 
                            <div class="lb-t" id="search">
                               <form role="search" method="get" class="amp-search" target="_top" action="'.$action_url.'">
			<div class="amp-search-wrapper">
				<label aria-label="Type your query" class="screen-reader-text" for="s">Type your search query and hit enter: </label>
				<input type="text" placeholder="AMP" value="1" name="amp" class="hidden"/>
				<input type="text" placeholder="Type Here" value="" name="s" class="s" />
				<label aria-label="Submit amp search" for="amp-search-submit" >
					<input type="submit" class="icon-search" value="Search" />
				</label>
				<div class="overlay-search">
				</div>
			</div>
			</form>                                   <a title="close" class="lb-x" href="#"></a>
                            </div> 
                        </div>
                  </div></div> ', $header_content);

	    }

		$sanitizer_header = new AMPFORWP_Content( $header_content,
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
		$sanitized_header_content = $sanitizer_header->get_amp_content();
        $GLOBALS['sanitized_hd_styles'] = $sanitizer_header->get_amp_styles();
	}

	if(preg_match("/(.*?)id='elementor-frontend-inline-css'(.*?)>(.*?)}(.*)/s", $header)){
	$background_url = preg_replace("/(.*?)id='elementor-frontend-inline-css'(.*?)>(.*?)}(.*)/s", "$3}", $header);
	$GLOBALS['section_bgd_url'] = $background_url;

     }

ob_start();


\Elementor\Plugin::$instance->modules_manager->get_modules( 'page-templates' )->print_content();
$sanitize_content  = ob_get_contents();

ob_get_clean();
$sanitizer_obj = new AMPFORWP_Content( $sanitize_content,
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
$GLOBALS['sanitized_tb_styles'] = $sanitizer_obj->get_amp_styles();
$sanitizer_content = $sanitizer_obj->get_amp_content();


ob_start();
get_footer();

$footer  = ob_get_contents();

ob_get_clean();

if( isset($redux_builder_amp['elem-themebuilder_footer']) && ($redux_builder_amp['elem-themebuilder_footer'] == 1) && preg_match('/(.*?)<div data-elementor-type="footer"(.*?)<\/body>(.*?)<\/html>/s', $footer)){

	$footer_content = preg_replace('/(.*?)<div data-elementor-type="footer"(.*?)<\/body>(.*?)<\/html>/s', '<div data-elementor-type="footer"$2', $footer);

	$sanitizer_footer = new AMPFORWP_Content( $footer_content,
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
$GLOBALS['sanitized_footer_styles'] = $sanitizer_footer->get_amp_styles();
$sanitized_footer = $sanitizer_footer->get_amp_content();
}

add_action("amp_post_template_css" , 'amp_location_manager_theme_css');
function amp_location_manager_theme_css(){
		if(isset($GLOBALS['sanitized_tb_styles'])){
		  foreach ($GLOBALS['sanitized_tb_styles'] as $key => $value) {
		  echo $key.'{'.$value[0].'}';
		  }
		}	
		 if(isset($GLOBALS['sanitized_footer_styles'])){
		  foreach ($GLOBALS['sanitized_footer_styles'] as $keys => $values) {
		  echo $keys.'{'.$values[0].'}';
		  }
		  echo 'footer p{line-height:1.5;}';
		}
	    if(isset($GLOBALS['section_bgd_url'])){
		 	echo $GLOBALS['section_bgd_url'];
		}
	}



$pluginsData = array();
$pluginsData = get_transient( 'ampforwp_themeframework_active_plugins' );
if(isset($redux_builder_amp['amp-design-selector'])){
 $design_selected=$redux_builder_amp['amp-design-selector'];
}
if( 4 == $redux_builder_amp['amp-design-selector'] ) { 
     ob_start();	
	$this->load_parts( array( 'header' ) );
	$swift_header = ob_get_contents();
	ob_get_clean();
	if(!empty($sanitized_header_content) && preg_match('/(.*?)<header(.*?)<\/header>/s', $swift_header)){
		$swift_header = preg_replace('/(.*?)<header(.*?)<\/header>/s', '$1'.$sanitized_header_content.'', $swift_header);
	}
	echo $swift_header;
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
		<?php
          ob_start();	
		 do_action( 'amp_post_template_head', $this );
		 $amp_theme_header = ob_get_contents();
	     ob_get_clean();
		 if(!empty($sanitized_header_content) && preg_match('/(.*?)<header(.*?)<\/header>/s', $swift_header)){
		$amp_theme_header = preg_replace('/(.*?)<header(.*?)<\/header>/s', '$1'.$sanitized_header_content.'', $amp_theme_header);
	    }
	    echo $amp_theme_header;

		  ?>
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
<?php if(function_exists('is_product') && is_product() && function_exists('ampforwp_product_details_generator')){?>
	<amp-state id="product">
	  <script type="application/json"><?php echo ampforwp_product_details_generator();
	   ?>
	  </script>
	</amp-state>
	<amp-state id="wc_product_cart">
	  <script type="application/json"><?php echo json_encode(array("cartvalue"=>1));
	   ?>
	  </script>
	</amp-state>
<?php }?>
<?php echo  $sanitizer_content; ?>

	<?php do_action( 'elementor/page_templates/header-footer/after_content' ); ?>
	<?php do_action( 'amp_post_template_above_footer', $this ); ?>
	<?php
	 ob_start();
	 $this->load_parts( array( 'footer' ) ); 
	$amp_footer = ob_get_contents();
	ob_get_clean();
	if(!empty($sanitized_footer) && preg_match('/(.*?)<footer(.*?)<\/footer>(.*)/s', $amp_footer)){
     $amp_footer = preg_replace('/(.*?)<footer(.*?)<\/footer>(.*)/s', '$1'.$sanitized_footer.'$3', $amp_footer);
	}
	echo $amp_footer;
	 ?>
	<?php do_action( 'ampwcpro_post_template_footer', $this ); ?>

</body>
</html>

