<?php 
class UX_builder_For_Amp {

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){

		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-ux-support') ){
			add_filter('amp_post_template_css', [$this,'amp_ux_builder_custom_styles'],11);
			//add_action('ampforwp_before_head', [$this,'oxygen_amp_fonts']);
            add_action('pre_amp_render_post', [$this,'amp_pb_ux_compatibility_shortcode_override'],999);

            //for ampbyautomattic.
		    if( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
	           add_action('init',[$this,'amp_pb_ux_compatibility_shortcode_override'],999);
	           add_action( 'wp_head', [$this,'amp_ux_builder_add_custom_css'],99);
	           add_action('wp', function(){ ob_start([$this,'content_filter_for_modify_classesReplacements']); }, 999);
	        }
             require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
            
		}
	}
    // for ampbyautomattic.
	public function content_filter_for_modify_classesReplacements( $content_buffer ) {
            if(preg_match('/\.grid-col \.box-image amp-img{position:absolute;padding:0}/', $content_buffer)){
              $content_buffer = preg_replace('/\.grid-col \.box-image amp-img{position:absolute;padding:0}/', '' , $content_buffer);
            }
            $re = '/body{margin:0!important}/';
            $content_buffer = preg_replace($re, '' , $content_buffer);
            $re = '/body{margin:0}/';
            $content_buffer = preg_replace($re, '' , $content_buffer);

            preg_match_all('/<span class="banner-bg-hide">(.*?)<\/span>/s', $content_buffer, $matches);
            for ($i = 0; $i < count($matches[1]); $i++) {
                $key = $matches[1][$i];
                $value = $matches[2][$i];
                $re = '/<style amp-custom="">/s';
                $content_buffer = preg_replace($re, '<style amp-custom="">.banner-'.$i.'{background-image:url('.$key.')}' , $content_buffer);
                
                $content_buffer = preg_replace('/<div class="banner\s+(.*?)" id="(.*?)">/', '<div class="banner '.$i.' $1" id="$2">' , $content_buffer);
            }


            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-0 has-hover bg-zoom" id="$1">' , $content_buffer,1);

            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-1 has-hover bg-zoom" id="$1">' , $content_buffer,1);

            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-2 has-hover bg-zoom" id="$1">' , $content_buffer,1);

            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-3 has-hover bg-zoom" id="$1">' , $content_buffer,1);$content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-0 has-hover bg-zoom" id="$1">' , $content_buffer,1);

            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-4 has-hover bg-zoom" id="$1">' , $content_buffer,1);

            $content_buffer = preg_replace('/<div class="banner 5 4 3 2 1 0 has-hover bg-zoom" id="(.*?)">/', '<div class="banner banner-5 has-hover bg-zoom" id="$1">' , $content_buffer,1);
            
            return $content_buffer;
    }

    public static function classesReplacements($completeContent){
        $completeContent = preg_replace("//", "", $completeContent);
        $completeContent = preg_replace("//", "", $completeContent);
        $completeContent = preg_replace("//", "", $completeContent);

    	return $completeContent;
    }

    // Add Custom CSS for ampbyautomattic in template modes.
	public function amp_ux_builder_add_custom_css(){
	   if( (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
          wp_enqueue_style( 'amp_ux_builder_css', AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR_URI."includes/ux-builder/amp-ux-custom-style.css", false, '0.1' );
          $allCss = pagebuilder_for_amp_utils::get_setting_data('uxCss-custom');
          wp_add_inline_style( 'amp_ux_builder_css', $allCss );
          
	   }
	}



	public function amp_ux_builder_custom_styles(){
		global $post;
		global $amp_elemetor_custom_css;
		if ( $post ){
			$postID = $post->ID;
	         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
	            $postID = ampforwp_get_frontpage_id();
	        }
	        $this->postID = $postID;
		} 

        $content_post = get_post($postID);
        $post_content = $content_post->post_content;
        
		$allCss = '';       

		$srcs = array();
		$min_suffix =  '.min';
		//$get_permlink = get_permalink();
        global $post;
        $post_slug = $post->post_name;

        $page = get_page_by_path($post_slug);
            if ($page) {
                $page_id =  $page->ID;
                $final_slug = $post_slug.'-'.$page_id;
            }
		 
		// if(function_exists('wp_upload_dir')){
		// 	$uploadUrl = wp_upload_dir()['baseurl'];
  //           $uploads_dir = wp_upload_dir()['basedir'];
  //           if(file_exists($uploads_dir."/oxygen/css/")){
  //        $srcs[] = $uploads_dir."/oxygen/css/universal.css";
  //        $srcs[] = $uploads_dir."/oxygen/css/".$final_slug.".css";
  //        }
  //       } 
            
        $css = '';
        $srcs = array();
        $template_url 		= get_template_directory_uri();
		$child_theme_url 	= get_stylesheet_directory_uri();
        
        global $wp_styles;
        if(!(defined('AMP_WOOCOMMERCE_DATA_VERSION') && function_exists('is_product') && is_product())){
        	$srcs[] = $template_url.'/assets/css/flatsome.css';
        }

		//Supported plugin css
		$plugin_css = $this->supported_plugin_css();
		if($plugin_css){
			$srcs = array_merge($srcs, $plugin_css);
		}
		$update_css = pagebuilder_for_amp_utils::get_setting_data('UXbuilderCssKeys');
		if($update_css!='' && strpos($update_css, ',')!==false){
			$csslinks = explode(",", $update_css);
			$csslinks = array_filter(array_map('trim', $csslinks));
			$srcs = array_merge($srcs, $csslinks);
		}elseif(filter_var($update_css, FILTER_VALIDATE_URL)){
			$srcs = array_merge($srcs, array($update_css));
		}

		if(count($srcs)>0){
			$srcs = array_unique($srcs);
		}
		foreach ($srcs as $key => $urlValue) {
			$cssData = $this->ampforwp_remote_content($urlValue);
			$cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
			$allCss .= preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($urlValue){
                    $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
                        if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                            $urlExploded = explode("/", $urlValue);
                            $parentUrl = str_replace(end($urlExploded), "", $urlValue);
                            return 'url('.$parentUrl.$matches[1].")"; 
                        }else{
                            return $matches[0];
                        }
                    }, $cssData);
		}
		if(!empty(pagebuilder_for_amp_utils::get_setting_data('UXbuilderCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('UXbuilderCss-custom');
        }
		$allCss .= $this->supported_plugin_compatible_css();
		if( function_exists('wp_get_custom_css') ){
			$allCss .= wp_get_custom_css();
		}

	// for inline embedded css.
		$postID = $post->ID;
	        if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
	            $postID = ampforwp_get_frontpage_id();
	        }
		$allCss = preg_replace("/\/\*(.*?)\*\//si", "", $allCss);
		 $allCss = str_replace(array(" img", "!important"), array(" amp-img", ""), $allCss);
         
		if(is_array($amp_elemetor_custom_css)){
			foreach ($amp_elemetor_custom_css as $key => $cssArray) {
				if(is_array($cssArray)){
					foreach ($cssArray as $key => $css) {
						$allCss .= $css;
					}
				}else{
					$allCss .= $cssArray;
				}
				
			}
		}
        $allCss .= "input[type='checkbox']{display: none;}html {background-color: unset;}";
		echo $allCss;
		
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
               $data_dir = $src_dir = $contentData = '';
                $upload_dir = wp_upload_dir(); 
                if(isset($upload_dir['baseurl']) && isset($upload_dir['basedir']) ){
                $src_dir = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $src);
                 }
                if(file_exists($src_dir)){
				$contentData = file_get_contents( $src );
                }
				if(! $contentData ){
					$data = str_replace(get_site_url(), '', $src);//content_url()
					$data = getcwd().$data;
                     if(isset($upload_dir['baseurl']) && isset($upload_dir['basedir']) ){
                   $data_dir = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $data);
                    }

					if(file_exists($data_dir)){
						$contentData = file_get_contents($data);
					}
				}
				return $contentData;
			}

		}
        return '';
	}

    public function amp_pb_ux_compatibility_shortcode_override(){
        if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
               // Add module shortcode name in array to override template.
		       $amp_pb_ux_template_overide = array('ux_slider','ux_banner','button');
		        foreach ($amp_pb_ux_template_overide as $key => $shortcode_value) {
		                    if(!empty($shortcode_value)){
		                    	//add file name as per shortcode.
		                        if(file_exists(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."includes/ux-builder/".$shortcode_value.'.php')){
		                        	// remove non amp shortcode.
		                            remove_shortcode( $shortcode_value );
		                            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."includes/ux-builder/".$shortcode_value.'.php';
		                            /* 
		                            name shortcode callback in this format
		                            shortcode_value_amp
		                            example: ux_slider_amp
		                            */
		                            add_shortcode( $shortcode_value, $shortcode_value.'_amp');

		                        }

		                    }
                }
        }
    }

    function supported_plugin_css(){
		$cssUrl = array();
		
		return $cssUrl;
	}

	function supported_plugin_compatible_css(){
		$css = '';
		
		return $css;
	}	 

	function ampforwp_blacklist_sanitizer($data){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/class-amp-divi-blacklist.php';
        unset($data['AMP_Blacklist_Sanitizer']);
        unset($data['AMPFORWP_Blacklist_Sanitizer']);
        $data[ 'AMPFORWP_DIVI_Blacklist' ] = array();
        return $data;
    }

} 



/**
* Admin section portal Access
**/
add_action('plugins_loaded', 'pagebuilder_for_amp_ux_option');
function pagebuilder_for_amp_ux_option(){
    if(is_admin()){
        new pagebuilder_for_amp_ux_Admin();
    }else{
        // Instantiate AMP_PC_Divi_Pagebuidler.
        $diviAmpBuilder = new UX_builder_For_Amp();
    }
    
    
}
Class pagebuilder_for_amp_ux_Admin{
    function __construct(){
        add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_ux_builder'),7,1 );
    }
        public static function get_admin_options_ux($section = array()){
        $obj = new self();
        //print_r($obj);die;
        $section = $obj->add_options_for_ux_builder($section);
        return $section;
    }
    function add_options_for_ux_builder($sections){
        $theme = wp_get_theme(); // gets the current theme
        if($theme->Name ='Flatsome'){
            $desc = 'Enable/Activate UX builder';
        }else{
            $desc = '';
        }
        
       // print_r( $sections[3]['fields']);die;
        $accordionArray = array();
        $sectionskey = 0;
        foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
                foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
                    if($fieldvalue['id'] == 'ampforwp-ux-pb-for-amp-accor'){
                        $accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                         unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-ux-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                }
                break;
            }
        }
        $sections[$sectionskey]['fields'][] = $accordionArray;
        $sections[$sectionskey]['fields'][] = array(
                               'id'       => 'pagebuilder-for-amp-ux-support',
                               'type'     => 'switch',
                               'title'    => esc_html__('AMP UX Builder Compatibility ','accelerated-mobile-pages'),
                               'tooltip-subtitle' => esc_html__('Enable or Disable the UX Builder for AMP', 'accelerated-mobile-pages'),
                               'desc'     => $desc,
                               'section_id' => 'amp-content-builder',
                               'default'  => false
                            );
        foreach ($this->amp_ux_fields() as $key => $value) {
            $sections[$sectionskey]['fields'][] = $value;
        }
        

        return $sections;

    }

    public function amp_ux_fields(){
        $contents[] = array(
                        'id'       => 'uxCssKeys',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter css url', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma saperated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
        $contents[] = array(
                        'id'       => 'uxCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );

        return $contents;
    }
}


