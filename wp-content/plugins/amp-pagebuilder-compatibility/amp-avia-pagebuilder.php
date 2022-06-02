<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class AMP_PC_Avia_Pagebuidler {

    public function __construct()
    {
        $this->load_dependencies();
        add_action('init', array($this, 'add_class_responsive_enfold_theme'));
        //$this->define_public_hooks();
    }
    
    private function load_dependencies(){
        if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-avia-support') ){
             //Remove ampforwp
            add_action("after_setup_theme", function(){
                Avia_Builder()->wp_head_done = true;
                // Load Css
                add_filter('amp_post_template_css', [$this,'amp_avia_custom_styles'],11);
            });
            add_filter('ampforwp_content_sanitizers',[$this, 'ampforwp_avia_blacklist_sanitizer'], 99);
            add_filter('amp_content_sanitizers',[$this, 'ampforwp_avia_blacklist_sanitizer'], 99);
            add_action('pre_amp_render_post',[$this,'ampforwp_remove_compatibility']);
            remove_action('init','ampforwp_enfold_theme_compatibility',2);
            
            //body class
            add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_avia'],11);  
            add_action('amp_post_template_head',[$this, 'amp_avia_pagebuilder_font_link']);
           
            //Shortcode Replacement
            add_filter('avia_load_shortcodes', [$this, 'avia_include_shortcode_template'], 15, 1);
            
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
        }
    }
    //add filter with condition/
    public function add_class_responsive_enfold_theme(){
        if( function_exists('avia_lang_setup') ) {
            add_filter('ampforwp_modify_html_attributes',[$this,'html_attribute_class']);
            add_action('ampforwp_before_post_content',[$this,'add_div_to_body']); 
            add_action('ampforwp_after_post_content',[$this,'close_div_afc']);
        }
    }
    public static function load_ajax_calls(){
        
    }

    public static function classesReplacements($completeContent){
        $completeContent = preg_replace("/units/", "", $completeContent);
        $completeContent = preg_replace("/av-horizontal-gallery-/", "av-hrz-gal-", $completeContent);
        $completeContent = preg_replace("/av-countdown-/", "av-ctdn-", $completeContent);
        $completeContent = preg_replace("/av-special-heading/", "av-sp-hdg-", $completeContent);
        $completeContent = preg_replace("/avia-content-slider/", "av-cnt-sld", $completeContent);
        $completeContent = preg_replace("/avia-testimonial/", "av-tstm", $completeContent);
        $completeContent = preg_replace("/avia-slider-testimonials/", "av-sld-tstm", $completeContent);
        $completeContent = preg_replace("/avia-animated-number/", "av-anm-no", $completeContent);
        $completeContent = preg_replace("/avia-icon-list/", "av-ic-lst", $completeContent);
        $completeContent = preg_replace("/avia-smallarrow-slider/", "av-sma-sld", $completeContent);
        $completeContent = preg_replace("/avia-logo-element-container/", "av-logo-ele-cntr", $completeContent);
        $completeContent = preg_replace("/avia-image-container/", "av-img-cntr", $completeContent);
        $completeContent = preg_replace("/#top .av_inherit_color/", "", $completeContent);
        $completeContent = apply_filters("amp_pc_avia_css_sorting", $completeContent);
        
        return $completeContent;
    }
//add responsive class in html.
function html_attribute_class($attributes){
     $attributes['class'] = 'responsive';
    return $attributes;
}
//add div element.
function add_div_to_body(){
    //echo "string";die;
    $content = '';
    $content .= '<div id="top">';
    $content .= '<div id="wrap_all">';
    echo $content;

}
//close div element.
function close_div_afc(){
    $content = '</div></div>';
    echo $content;
}

    function ampforwp_body_class_avia($classes){
        global $avia_config;
        $classes[]				= 'html_'.$avia_config['box_class'];
        $classes[]				= avia_get_option('responsive_active') != "disabled" ? "responsive" : "fixed_layout";
        $classes[]				= isset($avia_config['template']) ? $avia_config['template'] : "";	
	$av_lightbox			= isset($avia_config['use_standard_lightbox']) && $avia_config['use_standard_lightbox'] != "disabled" ? 'av-default-lightbox' : 'av-custom-lightbox';
        $classes[]				= avia_get_option('preloader') == "preloader" ? 'av-preloader-active av-preloader-enabled' : 'av-preloader-disabled';
        $classes[] = apply_filters( 'avf_custom_body_classes', '' );
        $classes = array_filter($classes);
        return $classes;
    }
    public function amp_avia_custom_styles(){
        global $post, $wp_styles;
        //From avia
        global $shortname;
        $postID = $post->ID;
         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }
        if ( 'active' !== get_post_meta( $postID, '_aviaLayoutBuilder_active', true ) ) {
            return ;
        }
        $css = '';
        $srcs = array();
        $template_url 		= get_template_directory_uri();
		$child_theme_url 	= get_stylesheet_directory_uri();
        
        global $wp_styles;
        $srcs[] = $template_url."/css/custom.css";
        $srcs[] = $template_url."/css/grid.css";
        $srcs[] = $template_url."/css/base.css";
        $srcs[] = $template_url."/css/layout.css";
        
        
        if($wp_styles->registered){
            foreach($wp_styles->queue as $csshabdle){
                $skipCss = array('admin-bar');//array('avia-module-menu', 'admin-bar', 'avia-siteloader');
                if( in_array($csshabdle, $skipCss) ){
                    continue;
                }
                if( isset( $wp_styles->registered[$csshabdle] ) ) {
                    $srcs[] = $wp_styles->registered[$csshabdle]->src;
                }
            }
        }
      
        $srcs[] = $template_url."/css/shortcodes.css";
        if(is_child_theme()){
            $srcs[] = $child_theme_url . '/style.css';
        }
        $srcs[] = Avia_Builder()->asset_manager()->get_file_url(array('hash'=>'enfold'), 'css');

        if( function_exists('avia_lang_setup') ) {
          $srcs[] =  plugin_dir_url(__DIR__).'amp-pagebuilder-compatibility/assets/style.css';
        }
        
        
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
                if(strpos($valuesrc, '/layout.css')!==false){
                    $cssData = str_replace('div .logo{ float:left; position: absolute; left:0; z-index: 1;}', '' , $cssData);
                    $cssData = str_replace('.logo, .logo a', '', $cssData);
                }
                
                $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
                $css .= preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($valuesrc){
                    $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
                        if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                            $urlExploded = explode("/", $valuesrc);
                            $parentUrl = str_replace(end($urlExploded), "", $valuesrc);
                            return 'url('.$parentUrl."/".$matches[1].")"; 
                        }else{
                            return $matches[0];
                        }
                    }, $cssData);
            }
        }

        if(!empty(pagebuilder_for_amp_utils::get_setting_data('aviaCss-custom') ) ){
            $css .= pagebuilder_for_amp_utils::get_setting_data('aviaCss-custom');
        }
        //Customizer css
        if( function_exists('wp_get_custom_css') ){
			$css .= wp_get_custom_css();
		}

        $css = str_replace(array(" img", " video", "!important"), array(" amp-img", " amp-video", ""), $css);

        $css = preg_replace_callback('/url[(](.*?)[)]/', function($matches){
            $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
            if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                return 'url('.get_template_directory_uri()."/".$matches[1].")"; 
            }else{
                return $matches[0];
            }
        }, $css);

      echo $css.".content{border:none;}
            @font-face {font-family: 'entypo-fontello'; font-weight: normal; font-style: normal;
            src: url('".get_template_directory_uri()."/config-templatebuilder/avia-template-builder/assets/fonts/entypo-fontello.eot');
            src: url('".get_template_directory_uri()."/config-templatebuilder/avia-template-builder/assets/fonts/entypo-fontello.eot?#iefix') format('embedded-opentype'), 
            url('".get_template_directory_uri()."/config-templatebuilder/avia-template-builder/assets/fonts/entypo-fontello.woff') format('woff'), 
            url('".get_template_directory_uri()."/config-templatebuilder/avia-template-builder/assets/fonts/entypo-fontello.ttf') format('truetype'), 
            url('".get_template_directory_uri()."/config-templatebuilder/avia-template-builder/assets/fonts/entypo-fontello.svg#entypo-fontello') format('svg');
            } #top .avia-font-entypo-fontello, body .avia-font-entypo-fontello, html body [data-av_iconfont='entypo-fontello']:before{ font-family: 'entypo-fontello'; }body amp-img.aligncenter{display:table;padding:0px;}
            .av-parallax-section.av-minimum-height-100{height:100vh;}
             .av-parallax-section.av-minimum-height-75{height:75vh;}
             .av-parallax-section.av-minimum-height-50{height:50vh;}
             .av-parallax-section.av-minimum-height-25{height:25vh;}
            .av-parallax-section .av-parallax{height:100%;}
            @media(max-width:768px){
            .av-parallax-section.av-minimum-height .container {height:100%;}
            .av-parallax-section.av-minimum-height-100,.av-parallax-section.av-minimum-height-75,.av-parallax-section.av-minimum-height-50,.av-parallax-section.av-minimum-height-25{height:100%;}
            }
            #top .av-default-height-applied .avia-slideshow-inner{height:auto;}
            .av-horizontal-gallery-slider{position:relative;}
                @media only screen and (min-width: 990px){
                .av-desktop-hide, .av-desktop-font-size-hidden,.av-desktop-font-size-title-hidden {
                        display: none; }
                }
                @media only screen and (max-width: 479px){
                .av-mini-hide, .av-mini-font-size-hidden, .av-mini-font-size-title-hidden {
                    display: none;
                }
            }
            @media only screen and (min-width: 990px){
             #wrap_all .av-desktop-hide{display:none;}
            }
            @media only screen and (min-width: 768px) and (max-width: 989px) {
             #wrap_all .av-medium-hide {display:none;}
            }

            @media only screen and (min-width: 480px) and (max-width: 767px) {
             #wrap_all .av-small-hide{display:none;}   
            }
            @media only screen and (max-width: 479px){
             #wrap_all .av-mini-hide{display:none;}
            }
            @media(max-width:600px){
                .av-slideshow-caption{ bottom:0;left:0; padding:0;}
                .av-slideshow-caption .avia-caption-content {
                    line-height: 1.3em;
                    font-size: 13px;
                }
                .av-slideshow-caption .avia-caption-title{font-size:20px;}
                .av_fullscreen amp-carousel{height:100vh;}
            }
            @media only screen and (max-width: 767px){.responsive #top .av-menu-mobile-active .av-subnav-menu { display: block;}
            }
            @media(min-width:768px){#sh-dtop-amp {display: block;}
            }
            .responsive #top .av-submenu-container {z-index: 1;}";

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

    
    public function amp_avia_pagebuilder_font_link(){
        global $post;
        $postID = $post->ID;
         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }
        $avia_enabled = false;
        if( 'active' == get_post_meta( $postID, '_aviaLayoutBuilder_active', true ) ){
            $avia_enabled = true;
        }
        if( $avia_enabled && pagebuilder_for_amp_utils::get_setting_data('avia_fontawesome_support')==1 ) { ?>

        <link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.8.1/css/all.css' type='text/css' media='all' />
        <?php }    
    }
    
    function ampforwp_avia_blacklist_sanitizer($data){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/class-amp-divi-blacklist.php';
        unset($data['AMP_Blacklist_Sanitizer']);
        unset($data['AMPFORWP_Blacklist_Sanitizer']);
        $data[ 'AMPFORWP_DIVI_Blacklist' ] = array();
        return $data;
    }
    
    public function avia_include_shortcode_template($paths){

        $getAmpUrl = $_SERVER['REQUEST_URI'];
        $ampUrl  = explode('/', $getAmpUrl);
        global $redux_builder_amp;
        if(in_array(AMPFORWP_AMP_QUERY_VAR, $ampUrl) || in_array('?'.AMPFORWP_AMP_QUERY_VAR, $ampUrl) || isset($redux_builder_amp['ampforwp-amp-takeover']) && ($redux_builder_amp['ampforwp-amp-takeover'])){
        
            $template_url = get_stylesheet_directory();
            //array_unshift($paths, AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'includes/avia-shortcodes/');
            $paths[] = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'includes/avia-shortcodes/';
        }
        return $paths;
    }

    function ampforwp_remove_compatibility(){
        remove_filter('the_content','ampforwp_remove_enfold_theme_shortcodes_tags');
    }
    
}



/**
* Admin section portal Access
**/
add_action('plugins_loaded', 'pagebuilder_for_amp_avia_option');
function pagebuilder_for_amp_avia_option(){
    if(is_admin()){
        new AMP_PC_Avia_Admin();
    }else{
        // Instantiate AMP_PC_Avia_Pagebuidler.
        $diviAmpBuilder = new AMP_PC_Avia_Pagebuidler();
    }
    if ( defined( 'DOING_AJAX' ) ) {
        AMP_PC_Avia_Pagebuidler::load_ajax_calls();
    }
    
}
Class AMP_PC_Avia_Admin{
    function __construct(){
        add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_avia'),7,1 );
    }
    public static function get_admin_options_divi($section = array()){
        $obj = new self();
        $section = $obj->add_options_for_avia($section);
        return $section;
    }
    function add_options_for_avia($sections){
        $desc = 'Enable/Activate Avia pagebuilder';
        $theme = wp_get_theme(); // gets the current theme
        if ( 'Enfold' == $theme->name || 'Enfold' == $theme->parent_theme ) {
            $desc = '';
        }
       // print_r( $sections[3]['fields']);die;
        $accordionArray = array();
        $sectionskey = 0;
        foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
                foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
                    if($fieldvalue['id'] == 'ampforwp-avia-pb-for-amp-accor'){
                        $accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                         unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-avia-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                }
                break;
            }
        }
        $sections[$sectionskey]['fields'][] = $accordionArray;
        $sections[$sectionskey]['fields'][] = array(
                               'id'       => 'pagebuilder-for-amp-avia-support',
                               'type'     => 'switch',
                               'title'    => esc_html__('AMP Avia Compatibility (
                                BETA) ','accelerated-mobile-pages'),
                               'tooltip-subtitle' => esc_html__('Enable or Disable the Avia for AMP', 'accelerated-mobile-pages'),
                               'desc'     => $desc,
                               'section_id' => 'amp-content-builder',
                               'default'  => false
                            );
        foreach ($this->amp_avia_fields() as $key => $value) {
            $sections[$sectionskey]['fields'][] = $value;
        }
        

        return $sections;

    }

    public function amp_avia_fields(){
        $contents[] = array(
                        'id'       => 'aviaCssKeys',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter CSS URL', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma saperated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
        $contents[] = array(
                        'id'       => 'aviaCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );

        $contents[] = array(
                        'id'       => 'avia_fontawesome_support',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Load fontawesome', 'amp-pagebuilder-compatibility'),
                        'desc'      => esc_html__( 'Load fontawesome library from CDN', 'amp-pagebuilder-compatibility' ),
                        'default'  => 0,
                        //'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',
                    );
        return $contents;
    }
}