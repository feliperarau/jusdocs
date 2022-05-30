<?php 
final class Beaver_For_Amp {


	public $postID;
	public $header_html;
	public $sanitizer_script;
	public $footer_html;

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){

		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-beaver-support') ){
			add_filter('amp_post_template_css', [$this,'amp_beaver_styles'],11);
            add_filter('ampforwp_the_content_last_filter', [$this,'ampforwp_beaver_class_replacements'],11);   
			add_action('ampforwp_before_head', [$this,'beaver_amp_fonts']);
			if(pagebuilder_for_amp_utils::get_setting('beaver-themebuilder_header')){
			    add_action('ampforwp_after_header', [$this,'amp_beaver_themebuilder_header']);
			}
			if(pagebuilder_for_amp_utils::get_setting('beaver-themebuilder_footer')){
			    add_action('amp_post_template_above_footer', [$this,'amp_beaver_themebuilder_footer']);
			}
			require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
			add_filter('ampforwp_tree_shaking_white_list_selector', [$this,'amp_beaver_white_lister',20]);
		}
	}

	
    public function ampforwp_beaver_class_replacements($completeContent){

		$completeContent = preg_replace('/! important/', "", $completeContent);
		$completeContent = preg_replace('/<img(.*?)src="(.*?)"(.*?)>/', "<amp-img src='$2' width='690' height='330' layout='responsive' > </amp-img>", $completeContent);

        return $completeContent;
    }

    public static function classesReplacements($completeContent){
		if(pagebuilder_for_amp_utils::get_setting('beaver-themebuilder_header')){
		    $completeContent = preg_replace("/<header\sclass=\"header[-|0-9]* h_m h_m_1\">(.*?)<\/header>/s", "", $completeContent);
		}
		if(pagebuilder_for_amp_utils::get_setting('beaver-themebuilder_footer')){
		    $completeContent = preg_replace("/<footer\sclass=\"footer\">(.*?)<\/footer>/si", "", $completeContent);
		}
        $completeContent = preg_replace("/text_block/", "tt_blk", $completeContent);

    	return $completeContent;
    }

	public function amp_beaver_styles(){
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
		 
		if(class_exists('FLBuilderAdminPointers')){
			$uploadUrl = wp_upload_dir()['baseurl'];
			// print_r($uploadUrl);die;

            $uploads_dir = wp_upload_dir()['basedir'];

         if(empty($page_id)){
      		$page_id = get_the_ID();
      	  }
             
         $srcs[] = $uploadUrl."/bb-plugin/cache/".$page_id."-layout.css";
         
        }
        
		//Supported plugin css
		$plugin_css = $this->supported_plugin_css();
		if($plugin_css){
			$srcs = array_merge($srcs, $plugin_css);
		}
		$update_css = pagebuilder_for_amp_utils::get_setting_data('beaverCssKeys');
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
		if(!empty(pagebuilder_for_amp_utils::get_setting_data('beaverCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('beaverCss-custom');
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

    
    function beaver_amp_fonts(){
        echo "<link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.12.1/css/all.css' type='text/css' media='all' />";
        //$this->amp_fonts_beaver();
    }



    public function amp_fonts_beaver(){
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

	function amp_beaver_themebuilder_header(){
		if(class_exists('FLThemeBuilderLayoutRenderer')){
			ob_start();
			FLThemeBuilderLayoutRenderer::render_header();
			$flbuilder_header = ob_get_contents();
			ob_clean();
		}
		if(class_exists('\AMPFORWP_Content')){
		  $sanitizer_obj = new \AMPFORWP_Content( $flbuilder_header,
		      apply_filters( 'amp_content_embed_handlers', array(
		          'AMP_Core_Block_Handler' => array(),
		          'AMP_Twitter_Embed_Handler' => array(),
		          'AMP_YouTube_Embed_Handler' => array(),
		          'AMP_DailyMotion_Embed_Handler' => array(),
		          'AMP_Vimeo_Embed_Handler' => array(),
		          'AMP_SoundCloud_Embed_Handler' => array(),
		          'AMP_Instagram_Embed_Handler' => array(),
		          'AMP_Vine_Embed_Handler' => array(),
		          'AMP_Facebook_Embed_Handler' => array(),
		          'AMP_Pinterest_Embed_Handler' => array(),
		          'AMP_Gallery_Embed_Handler' => array(),
		          'AMP_Playlist_Embed_Handler'    => array(),
		          'AMP_Wistia_Embed_Handler' => array(),
		      )),
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
		}
		$amp_flbuilder_header_sanitized = $sanitizer_obj->get_amp_content();
		$dom = new DomDocument();
		if(function_exists('mb_convert_encoding')){
		    @$dom->loadHTML(mb_convert_encoding($amp_flbuilder_header_sanitized, 'HTML-ENTITIES', 'UTF-8'));
		}else{
		    @$dom->loadHTML( $amp_flbuilder_header_sanitized );
		}
		$finder = new DomXPath($dom);
		$hamb_menu = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' uabb-creative-menu-mobile-toggle ')]");

		if($hamb_menu){
			foreach ($hamb_menu as $hamb ) {
				$hamb->setAttribute('on', 'tap:AMP.setState({ menuVisible: !menuVisible })');
				$hamb->setAttribute('role', 'button');
				$hamb->setAttribute('tabindex', '0');
			}
		}

		$menu_conts = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' uabb-creative-menu-accordion-collapse off-canvas ')]");
		if($menu_conts){
			foreach ($menu_conts as $menu_cont ) {
				$menu_cont->setAttribute('::openbrack::class::closebrack::', "menuVisible ? 'uabb-creative-menu uabb-creative-menu-accordion-collapse off-canvas menu-close menu-open' : 'uabb-creative-menu uabb-creative-menu-accordion-collapse off-canvas menu-close'");
			}
		}
		
		$close_menu = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' uabb-menu-close-btn ')]");
		if($close_menu){
			foreach ($close_menu as $close ) {
				$close->setAttribute('on', 'tap:AMP.setState({ menuVisible: !menuVisible })');
				$close->setAttribute('role', 'button');
				$close->setAttribute('tabindex', '0');
			}
		}

		$amp_flbuilder_header_sanitized = $dom->saveHTML();
		$amp_flbuilder_header_sanitized = str_replace(array('::openbrack::','::closebrack::'), array('[',']'), $amp_flbuilder_header_sanitized);	
		echo $amp_flbuilder_header_sanitized;
	}

	function amp_beaver_themebuilder_footer(){
		if(class_exists('FLThemeBuilderLayoutRenderer')){
			ob_start();
			FLThemeBuilderLayoutRenderer::render_footer();
			$flbuilder_footer = ob_get_contents();
			ob_clean();
		}
		if(class_exists('\AMPFORWP_Content')){
		  $sanitizer_obj = new \AMPFORWP_Content( $flbuilder_footer,
		      apply_filters( 'amp_content_embed_handlers', array(
		          'AMP_Core_Block_Handler' => array(),
		          'AMP_Twitter_Embed_Handler' => array(),
		          'AMP_YouTube_Embed_Handler' => array(),
		          'AMP_DailyMotion_Embed_Handler' => array(),
		          'AMP_Vimeo_Embed_Handler' => array(),
		          'AMP_SoundCloud_Embed_Handler' => array(),
		          'AMP_Instagram_Embed_Handler' => array(),
		          'AMP_Vine_Embed_Handler' => array(),
		          'AMP_Facebook_Embed_Handler' => array(),
		          'AMP_Pinterest_Embed_Handler' => array(),
		          'AMP_Gallery_Embed_Handler' => array(),
		          'AMP_Playlist_Embed_Handler'    => array(),
		          'AMP_Wistia_Embed_Handler' => array(),
		      )),
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
		}
		$amp_flbuilder_footer_sanitized = $sanitizer_obj->get_amp_content();
		echo $amp_flbuilder_footer_sanitized;
	}

	function amp_beaver_white_lister($white_list){
	  $white_list[] = '.uabb-creative-menu.off-canvas.menu-open .uabb-clear';
	  $white_list[] = '.menu-open.uabb-creative-menu .uabb-off-canvas-menu.uabb-menu-left';
	  return $white_list;
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
add_action('plugins_loaded', 'pagebuilder_for_amp_beaver_option');
function pagebuilder_for_amp_beaver_option(){
    //echo "stringasd";die;
	if(is_admin()){
		new pagebuilder_for_amp_beaver_Admin();
	}else{
		// Instantiate Beaver_For_Amp.
	}
	new Beaver_For_Amp();
}

Class pagebuilder_for_amp_beaver_Admin{
	function __construct(){

		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_beaver'),7,1 );
	}
	public static function get_admin_options($section = array()){
		$obj = new self();
		//print_r($obj);die;
		$section = $obj->add_options_for_beaver($section);
		return $section;
	}
	function add_options_for_beaver($sections){
        //echo "string";die;
		$desc = '';
        if(!class_exists('FLBuilderAdminPointers')){
            $desc = 'Enable/Activate Beaver Builder plugin';
        }
		 
		$accordionArray = array();
		$sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
			if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
				foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
					if($fieldvalue['id'] == 'ampforwp-beaver-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-beaver-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
				}
				break;
			}
		}
		$sections[$sectionskey]['fields'][] = $accordionArray;
		$sections[$sectionskey]['fields'][] = array(
				               'id'       => 'pagebuilder-for-amp-beaver-support',
				               'type'     => 'switch',
				               'title'    => esc_html__('AMP Beaver Compatibility ','accelerated-mobile-pages'),
				               'tooltip-subtitle' => esc_html__('Enable or Disable the Beaver for AMP', 'accelerated-mobile-pages'),
				               'desc'	  => $desc,
				               'section_id' => 'amp-content-builder',
				               'default'  => false
				            );
		foreach ($this->amp_beaver_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }

		return $sections;

	}

	public function amp_beaver_fields(){
        $contents[] = array(
                        'id'       => 'beaverCssKeys',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter CSS URL', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma seperated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
        $contents[] = array(
                        'id'       => 'beaverCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );        
        $contents[] = array(
                        'id'       => 'beaver-themebuilder_header',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Beaver ThemeBuilder Header', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show Beaver themebuilder Header in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'beaver-themebuilder_footer',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Beaver ThemeBuilder Footer', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show Beaver themebuilder Footer in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        return $contents;
    }
}