<?php 
final class Muffin_For_Amp {


	public $postID;
	public $header_html;
	public $sanitizer_script;
	public $footer_html;

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){

		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-muffin-support') ){
			add_filter('amp_post_template_css', [$this,'amp_muffin_styles'],11);
			add_filter('amp_post_template_css', [$this,'amp_muffin_inline_styles'],999);
            add_filter('ampforwp_the_content_last_filter', [$this,'ampforwp_muffin_class_replacements'],11);   
			add_action('ampforwp_before_head', [$this,'muffin_amp_fonts']);
			add_action('pre_amp_render_post', [$this,'amp_pb_muffin_compatibility_file_override'],999);
			add_action('pre_amp_render_post', [$this,'amp_pb_muffin_compatibility_file_loader']);
		}
	}

	
    public function ampforwp_muffin_class_replacements($completeContent){

		$completeContent = preg_replace('/! important/', "", $completeContent);
		$completeContent = preg_replace('/<amp-img(.*?)loading="lazy"(.*?)>/', "<amp-img$1$2>", $completeContent);

        return $completeContent;
    }

    public static function classesReplacements($completeContent){
        $completeContent = preg_replace("/text_block/", "tt_blk", $completeContent);

    	return $completeContent;
    }

	public function amp_muffin_styles(){
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
		 
		$theme = wp_get_theme();
        if ( 'Betheme' == $theme->name || 'Betheme Child' == $theme ) {
        $srcs[] = get_theme_root_uri() . '/betheme/css/base.css';
        $srcs[] = get_theme_root_uri() . '/betheme/css/layout.css';
        $srcs[] = get_theme_root_uri() . '/betheme/css/shortcodes.css'; 
        $srcs[] = get_theme_root_uri() . '/betheme/css/responsive.css'; 
        }
        
		//Supported plugin css
		$plugin_css = $this->supported_plugin_css();
		if($plugin_css){
			$srcs = array_merge($srcs, $plugin_css);
		}
		$update_css = pagebuilder_for_amp_utils::get_setting_data('muffinCssKeys');
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
		if(!empty(pagebuilder_for_amp_utils::get_setting_data('muffinCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('muffinCss-custom');
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

		$allCss .= '.pg tr:nth-child(odd) td {
    background: unset;}table td:first-child {
    padding-left: unset;}.pg td{padding:10px;border-width:1px;border-style:solid;vertical-align:middle}.clearfix{clear:unset}.icon-src:before{content:"\e8b6";font-family:\'icomoon\';font-size:23px}#offcanvas-menu{display:none}input.hidden{display:none}.offer_li .title h3,.timeline_items li h3 span,body,button,input[type=button],input[type=email],input[type=password],input[type=reset],input[type=submit],input[type=tel],input[type=text],select,span.date_label,textarea{font-family:Roboto,Helvetica,Arial,sans-serif}h2{font-size:30px;line-height:34px;font-weight:300;letter-spacing:0}h3{font-size:25px;line-height:29px;font-weight:300;letter-spacing:0}';

		echo $allCss;
		
	}

	public function amp_muffin_inline_styles(){
		ob_start();

		// style

		include_once get_theme_file_path( '/style.php' );

		// responsive

		if ( mfn_opts_get( 'responsive' ) ) {
			include_once get_theme_file_path( '/style-responsive.php' );
		}

		// colors

		if ( $layoutID = mfn_layout_ID() ) {
			$skin = get_post_meta( $layoutID, 'mfn-post-skin', true );
		} else {
			$skin = mfn_opts_get( 'skin', 'custom' );
		}	

		if ( 'custom' == $skin ) {
			include_once get_theme_file_path( '/style-colors.php' );
		} elseif ( 'one' == $skin ) {
			include_once get_theme_file_path( '/style-one.php' );
		}

		$css = ob_get_clean();
		// print_r($css); die;
		return $css;
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

    
    function muffin_amp_fonts(){
        echo "<link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.12.1/css/all.css' type='text/css' media='all' />";
        //$this->amp_fonts_muffin();
    }

    public function amp_fonts_muffin(){
        global $post;
                
    }


    function supported_plugin_css(){
		$cssUrl = array();
		
		return $cssUrl;
	}

	function supported_plugin_compatible_css(){

		$css = '';
		
		return $css;
	}	 

	// amp_pb_muffin_compatibility_file_override

	public function amp_pb_muffin_compatibility_file_override(){
        // echo "stringw";die;
        if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
       $amp_pb_muffin_template_overide = array( 'divider', 'button' ) ;
       // remove_shortcode('divider');
       foreach ($amp_pb_muffin_template_overide as $key => $value) {
                    if(!empty($value)){
                        if(file_exists(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/muffin/amp-muffin-template-".$value.'.php')){
                            //print_r($value); die;
                            remove_shortcode( $value );
                            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/muffin/amp-muffin-template-".$value.'.php';
                            add_shortcode( $value, $value.'_amp_pb_compatibility_for_muffin' );
                        }
                    }
                }
            }
       
    }

    public function amp_pb_muffin_compatibility_file_loader( ){
	if ( shortcode_exists( 'blog_slider' ) ) {
		require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR .'/includes/muffin/amp-muffin-template-blog-slider.php';
	}
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
add_action('plugins_loaded', 'pagebuilder_for_amp_muffin_option');
function pagebuilder_for_amp_muffin_option(){
    //echo "stringasd";die;
	if(is_admin()){
		new pagebuilder_for_amp_muffin_Admin();
	}else{
		// Instantiate Muffin_For_Amp.
	}
	new Muffin_For_Amp();
}

Class pagebuilder_for_amp_muffin_Admin{
	function __construct(){

		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_muffin'),7,1 );
	}
	public static function get_admin_options($section = array()){
		$obj = new self();
		//print_r($obj);die;
		$section = $obj->add_options_for_muffin($section);
		return $section;
	}
	function add_options_for_muffin($sections){
        //echo "string";die;
		$desc = '';
        $theme = wp_get_theme();
		if('Betheme' ==! $theme->name || 'Betheme Child' ==! $theme  ) {
            $desc = 'Enable/Activate muffin Builder plugin';
        }
		 
		$accordionArray = array();
		$sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
			if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
				foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
					if($fieldvalue['id'] == 'ampforwp-muffin-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-muffin-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
				}
				break;
			}
		}
		$sections[$sectionskey]['fields'][] = $accordionArray;
		$sections[$sectionskey]['fields'][] = array(
				               'id'       => 'pagebuilder-for-amp-muffin-support',
				               'type'     => 'switch',
				               'title'    => esc_html__('AMP Muffin Compatibility ','accelerated-mobile-pages'),
				               'tooltip-subtitle' => esc_html__('Enable or Disable the Muffin for AMP', 'accelerated-mobile-pages'),
				               'desc'	  => $desc,
				               'section_id' => 'amp-content-builder',
				               'default'  => false
				            );
		foreach ($this->amp_muffin_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }

		return $sections;

	}

	public function amp_muffin_fields(){
        $contents[] = array(
                        'id'       => 'muffinCssKeys',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter css url', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma seperated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
        $contents[] = array(
                        'id'       => 'muffinCss-custom',
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