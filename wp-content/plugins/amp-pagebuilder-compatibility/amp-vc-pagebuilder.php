<?php
if ( ! defined( 'ABSPATH' ) ) exit;


class AmpWpbakeryPro{

	public function __construct() {
		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-wpbakery-support') ){
			add_filter('amp_post_template_css', [$this,'amp_vc_custom_styles'],11);
			add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_for_vc'],11);
			add_filter('amp_post_template_head', [$this,'ampforwp_fontawesome_for_vc'],11);
			add_filter('ampforwp_pagebuilder_status_modify', [$this, 'pagebuilder_status_reset_vc'], 10, 2);
			add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_divi'],11);
			add_action('pre_amp_render_post', [$this,'vc_before_init_actions']);
			require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
		}

	}

	function pagebuilder_status_reset_vc($response, $postId ){
  		global $post;
		$postID = $post->ID;
		if (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
			$postID = ampforwp_get_frontpage_id();
		}
  		if(get_post_meta( $postID , '_wpb_vc_js_status', true ) ){
  			//$response = true;  		
		}
        return $response;
    }
    public static function classesReplacements($completeContent){
    	$completeContent = preg_replace("/wpb_animate_when_almost_visible/", "", $completeContent);
    	$completeContent = preg_replace("/nectar-button/", "nctbtn", $completeContent);
    	$completeContent = preg_replace("/nectar_image_with_hotspots/", "nctimgh", $completeContent);
    	$completeContent = preg_replace("/nectar/", "nct", $completeContent);
    	$completeContent = preg_replace("/vc_row-fluid/", "vrfl", $completeContent);
    	$completeContent = preg_replace("/vc_custom_heading/", "vch", $completeContent);
    	$completeContent = preg_replace("/vc_column_container/", "vcc", $completeContent);
    	$completeContent = preg_replace("/vc_row-flex/", "vrfx", $completeContent);
    	$completeContent = preg_replace("/blog-recent/", "blr", $completeContent);
    	$completeContent = preg_replace("/centered-container/", "cen_contr", $completeContent);
    	$completeContent = preg_replace("/wpb_wrapper/", "wpbwrap", $completeContent);
    	$completeContent = preg_replace("/clearfix/", "clrfx", $completeContent);
    	$completeContent = preg_replace("/vc_general/", "vcgen", $completeContent);
    	$completeContent = preg_replace("/vc_column-inner/", "vcinr", $completeContent);
    	$completeContent = preg_replace("/vc_row/", "vcr", $completeContent);
    	$completeContent = preg_replace("/rate_bar_wrap/", "vcrbwp", $completeContent);
    	$completeContent = preg_replace("/review-criteria/", "rvwcri", $completeContent);
    	$completeContent = preg_replace("/rate-bar-bar/", "ratbb", $completeContent);
    	$completeContent = preg_replace("/vc_custom_/", "vccm_", $completeContent);
    	$completeContent = preg_replace("/vc_section/", "vsec", $completeContent);
    	$completeContent = preg_replace("/add_user_review_link/", "aurlnk", $completeContent);
    	$completeContent = preg_replace("/amp-wp-inline/", "ampinl", $completeContent);
    	$completeContent = preg_replace("/amp-menu/", "amen", $completeContent);
    	$completeContent = preg_replace("/content-wrapper/", "cnttwr", $completeContent);
    	$completeContent = preg_replace("/comment-meta/", "cmtmta", $completeContent);
    	$completeContent = preg_replace("/comment-content/", "cmtcnt", $completeContent);
    	$completeContent = preg_replace("/pagination/", "pgntn", $completeContent);
    	$completeContent = preg_replace("/amp-comment/", "acmt", $completeContent);
    	$completeContent = preg_replace("/amp-tag/", "atag", $completeContent);
    	$completeContent = preg_replace("/.lazy{background-image:none}/", "", $completeContent);
    	$completeContent = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(\d%{(.*?)}\d+%{(.*?))+}}/", "", $completeContent);
        $completeContent = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(.*?)}{2,}/", "", $completeContent);
        $completeContent = preg_replace('/<div class="wpb_gallery(.*?)><ul class="slides">(.*?)<\/ul><\/div>/', '<div class="wpb_gallery$1><ul class="slides"><amp-carousel width="50" height="50" 
                layout="responsive" type="slides" 
                autoplay delay="2000">$2</amp-carousel></ul></div>' , $completeContent);


        /*$completeContent = preg_replace("/@[a-z-]*keyframes\s+\w+\s*\{\s*(\d+%\{[^}]+\}\s*)+\}/", "", $completeContent);
        $completeContent = preg_replace("/@(-o-|-moz-|-webkit-|-ms-)*keyframes\s(.*?){([0-9%a-zA-Z,\s.]*{(.*?)})*[\s\n]*}/s", "", $completeContent);*/
	   //$completeContent = preg_replace("/}(.*?)body\[data-(.*?)=\"(.*?)\"\](.*?){(.*?)}/", "", $completeContent);
	   $completeContent = apply_filters("amp_pc_vc_css_sorting", $completeContent);
    	return $completeContent;
    }
    function ampforwp_body_class_divi($classes){
    	return js_composer_body_class( $classes );
    }

	/**
	* create vc font support
	**/
	public function ampforwp_fontawesome_for_vc(){
		global $post;
		$postID = $post->ID;
		if (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
			$postID = ampforwp_get_frontpage_id();
		}
		$vc_enabled = get_post_meta($postID, '_wpb_vc_js_status');
		if($vc_enabled && pagebuilder_for_amp_utils::get_setting('vc_fontawesome_support')==1){
			echo '<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" crossorigin="anonymous">';
		}
	}
	public function ampforwp_body_class_for_vc($classes){
		global $post;
		$postID = $post->ID;
		if (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
			$postID = ampforwp_get_frontpage_id();
		}
		$vc_enabled = get_post_meta($postID, '_wpb_vc_js_status');
		if($vc_enabled){
			$classes[] = 'amp_vc';
		}
		return $classes;
	}
	public function vc_before_init_actions() {
	     
	    // Link your VC elements's folder
	    if( function_exists('vc_set_shortcodes_templates_dir') ){ 
	     	$amp_vc_template_dir = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/vc_templates/';
			vc_set_shortcodes_templates_dir( $amp_vc_template_dir );
	    }
	}

	public function amp_vc_custom_styles(){
		global $post, $wp_styles;
		$postID = $post->ID;
		 if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
			$postID = ampforwp_get_frontpage_id();
		}
		if(function_exists('vc_path_dir')){
			require_once vc_path_dir( 'PARAMS_DIR', 'vc_grid_item/class-vc-grid-item.php' );
		}
		//require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'amp_vc_shortcode_styles.php';
		$css = '';
		$srcs = array();

		if(!get_theme_support('amp-template-mode')){
			$srcs['theme_style'] = get_stylesheet_uri();
			if(is_child_theme()){
	            $srcs[] = get_template_directory_uri() . '/style.css';
	        }
	        $srcs[] = get_stylesheet_directory_uri() . '/style.css';
	    }
		foreach( $wp_styles->queue as $style ) :
			$src = $wp_styles->registered[$style]->src;
			if($style=='animate-css' || filter_var($src, FILTER_VALIDATE_URL) === FALSE){
				continue;
			}
			$srcs[$style] = $src;
		endforeach;

		$update_css = pagebuilder_for_amp_utils::get_setting('vcCssKeys');
		$csslinks = explode(",", $update_css);
		$csslinks = array_filter($csslinks);
		$srcs = array_merge($srcs, $csslinks);

		if(count($srcs)>0){
			$srcs = array_unique($srcs);
		}
		
		if(is_array($srcs) && count($srcs)){
			foreach ($srcs as $key => $valuesrc) {
				$valuesrc = trim($valuesrc);
				if( filter_var($valuesrc, FILTER_VALIDATE_URL) === FALSE ){
					continue;
				}
				$cssData = '';
				$cssData = $this->ampforwp_remote_content($valuesrc);
		  		$cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
          $css .= preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($valuesrc){
                    $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
                        if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                            $urlExploded = explode("/", $valuesrc);
                            $parentUrl = str_replace(end($urlExploded), "", $valuesrc);
                            return 'url('.$parentUrl.$matches[1].")"; 
                        }else{
                            return $matches[0];
                        }
                    }, $cssData);
			}
		}
		if( function_exists('wp_get_custom_css') ){
			$css .= wp_get_custom_css();
		}
		
		
		//Post Editor global CSS
		$postCutomCss =  get_post_meta( $postID, '_wpb_post_custom_css', true );
		$css .= strip_tags( $postCutomCss );
		
		//shortcode specific css
		$shortcodes_custom_css = get_post_meta( $postID, '_wpb_shortcodes_custom_css', true );
		echo $shortcodes_custom_css;
		$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( vc_frontend_editor()->getTemplateContent() );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$css .= strip_tags( $shortcodes_custom_css );
		}
		
		if ( preg_match( '/^\d+$/', $postID ) ) {
			$shortcodes_custom_css = get_post_meta( $postID, '_wpb_shortcodes_custom_css', true );
		} elseif (method_exists('Vc_Grid_Item', 'predefinedTemplate') && false !== ( $predefined_template = Vc_Grid_Item::predefinedTemplate( $postID ) ) ) {
			$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( $predefined_template['template'] );
		}
		if ( ! empty( $shortcodes_custom_css ) ) {
			$css .= strip_tags( $shortcodes_custom_css );
		}
		
		//amp custom Css
		$css .= pagebuilder_for_amp_utils::get_setting('vcCss-custom');
		echo $css.'.flexslider {border: 0px solid #fff;box-shadow: 0 0px 0px rgba(0,0,0,.2);}@media screen and (min-width: 800px) {.amp-carousel-button-next {right: 44%;}}amp-carousel{width:115%;}.wpb_gallery_slides.wpb_flexslider.flexslider_fade.flexslider{width: 46%;}.amp-carousel-button{top: 12%;position:absolute}';
	}

	public function ampforwp_remote_content($src){
		if($src){
			$arg = array( "sslverify" => false, "timeout" => 60 ) ;
			$response = wp_remote_get( $src, $arg );
	        if ( wp_remote_retrieve_response_code($response) == 200 && is_array( $response ) ) {
	          $header = wp_remote_retrieve_headers($response); // array of http header lines
	          $contentData =  wp_remote_retrieve_body($response); // use the content
	          return $contentData;
	        }else{
				$contentData = file_get_contents( $src );
				if(! $contentData ){
					$data = str_replace(get_site_url(), '', $src);//content_url()
					$data = getcwd().$data;
					if(file_exists($data)){
						$contentData = file_get_contents($data);
					}
				}
				return $contentData;
			}

		}
        return '';
	}

	public function amp_vc_shortcodes_canonical_link(){
		if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {	?>
	<link rel='stylesheet' id='font-awesome-css'  href='https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css?ver=4.6.3' type='text/css' media='all' />
		<?php }
	}
	public function amp_vc_shortcode_scripts($data){
		if ( is_plugin_active( 'js_composer/js_composer.php' ) ) {
			$data['amp_component_scripts']['amp-selector'] = 'https://cdn.ampproject.org/v0/amp-selector-0.1.js';
			$data['amp_component_scripts']['amp-bind'] = 'https://cdn.ampproject.org/v0/amp-bind-0.1.js';
			$data['amp_component_scripts']['amp-accordion'] = 'https://cdn.ampproject.org/v0/amp-accordion-0.1.js';
			$data['amp_component_scripts']['amp-lightbox'] = 'https://cdn.ampproject.org/v0/amp-lightbox-0.1.js';
			$data['amp_component_scripts']['amp-audio'] = 'https://cdn.ampproject.org/v0/amp-audio-0.1.js';
			$data['amp_component_scripts']['amp-video'] = 'https://cdn.ampproject.org/v0/amp-video-0.1.js';
			$data['amp_component_scripts']['amp-iframe'] = 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js';
			$data['amp_component_scripts']['amp-image-lightbox'] = 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js';
			$data['amp_component_scripts']['amp-carousel'] = 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js';
			$data['amp_component_scripts']['amp-fit-text'] = 'https://cdn.ampproject.org/v0/amp-fit-text-0.1.js';
			$data['amp_component_scripts']['amp-youtube'] = 'https://cdn.ampproject.org/v0/amp-youtube-0.1.js';
			$data['amp_component_scripts']['amp-lightbox-gallery'] = 'https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js';
		}
		return $data;
	}

	
			
}

amp_vc_shortcode_override();
function amp_vc_shortcode_override(){
	if(is_admin()){
		new AmpVCAdminSettings();
	}else{
		if(get_theme_support('amp-template-mode')){
		add_action("pre_amp_render_post", 'amp_pc_vc_load_frontend_work');
		}else{
			$url_path = $_SERVER['REQUEST_URI'];
		  	$explode_path = explode('/', $url_path);
			if(in_array('amp', $explode_path) || in_array('?amp', $explode_path)){
					global $ampwpbakery;
		$ampwpbakery = new AmpWpbakeryPro();
			}
		}
	}
}
function amp_pc_vc_load_frontend_work(){
	global $ampwpbakery;
	$ampwpbakery = new AmpWpbakeryPro();
}



/**
 * Admin settings
 **/
class AmpVCAdminSettings{
	public function __construct() {
		add_filter("redux/options/redux_builder_amp/sections", array($this,'ampforwp_settings_vc_settings'));
	}

	public static function get_admin_options_wp_bakery($section = array()){
        $obj = new self();
        $section = $obj->ampforwp_settings_vc_settings($section);
        return $section;
    }

	public function ampforwp_settings_vc_settings($sections){
		$desc = 'Enable/Activate wpbakery pagebuilder';
        global $vc_manager;
    	if($vc_manager instanceof Vc_Manager){
            $desc = '';
        }
        $accordionArray = array();
        $sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
                foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
                    if($fieldvalue['id'] == 'ampforwp-wpbakery-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-wpbakery-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                }
                break;
            }
        }
        $sections[$sectionskey]['fields'][] = $accordionArray;
        $sections[$sectionskey]['fields'][] = array(
                               'id'       => 'pagebuilder-for-amp-wpbakery-support',
                               'type'     => 'switch',
                               //'title'    => esc_html__('AMP WPBakery Compatibility (BETA)','amp-pagebuilder-compatibility'),
                              // 'tooltip-subtitle' => esc_html__('Enable or Disable the WPBakery for AMP', 'amp-pagebuilder-compatibility'),
                               'desc'     => $desc,
                               'section_id' => 'amp-content-builder',
                               'class'     =>( !is_plugin_active( 'js_composer/js_composer.php')? '': 'hide'),
                               'default'  => true
                            );
        /*foreach ($this->amp_wpbakery_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }*/
        //print_r($sections[$sectionskey]['fields']);die;
       // print_r($sections[$sectionskey]['fields']);die;
        //print_r($sections[$sectionskey]['fields']);die;
		/*$sections[] = array(
            'title'      => esc_html__( 'AMP WPBakery', 'accelerated-mobile-pages' ),
            'icon'       => 'el el-forward',
            'subsection' => false,
            'id'         => 'opt-amp-pagebuilder-wpbakery',
            'fields'     => $this->amp_wpbakery_fields(),
                        );*/
        return $sections;
	}

	public function amp_wpbakery_fields(){
		$contents[] = array(
                        'id'       => 'vcCssKeys',
                        'type'     => 'textarea',
                        'class'	   => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter CSS URL', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma saperated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
		$contents[] = array(
                        'id'       => 'vcCss-custom',
                        'type'     => 'textarea',
                        'class'	   => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                        //'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',
                    );

		$contents[] = array(
                        'id'       => 'vc_fontawesome_support',
                        'type'     => 'switch',
                        'class'	   => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Load fontawesome', 'amp-pagebuilder-compatibility'),
                        'desc'  => esc_html__('Increase the performance with compression mode', 'amp-pagebuilder-compatibility'),
                        'default'  => 0,
                        //'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',
                    );
		return $contents;
	}
}
//publisher theme images.
add_action('pre_amp_render_post', 'publisher_theme_images_in_amp');

function publisher_theme_images_in_amp(){
    $getTheme = wp_get_theme(); // gets the current theme
	if ('Publisher' == $getTheme->name ){ 
		
        add_filter( 'the_content', 'get_publisher_theme_inline_image', 99,1);
    }
}

function get_publisher_theme_inline_image( $content ) {
	if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
				
		$output = preg_replace_callback('/<a(\s)*(\s)*(.+)data-src="(.*?)"(\s)*class="img-cont"(.*?)>/', 
						function($matches){
							
			           return '<a '. $matches[6].'><img src = "'. $matches[4].'" width="100vw" alt="amp-publisher-image" layout="responsive"/></a>';
			                
						}
						, $content);
		$output = preg_replace_callback('/<a(\s)*(\s)*(.+)data-src="(.*?)"(\s)*class="img-holder"(.*?)>/', 
						function($matches){
						
			           return '<a '. $matches[6].'><img src = "'. $matches[4].'" width="100vw" alt="amp-publisher-image" layout="responsive"/></a>';
			                
						}
						, $output);

		return $output;
	  }
}