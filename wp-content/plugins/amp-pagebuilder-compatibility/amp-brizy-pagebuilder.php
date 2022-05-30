<?php 
class Brizy_For_Amp {
	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){
		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-brizy-support') ){
			add_filter('amp_post_template_css', [$this,'amp_brizy_custom_styles'],11);
			add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_brizy'],11);
			add_action('ampforwp_before_head', [$this,'brizy_amp_fonts']);
			add_action('amp_post_template_head', [$this,'ampforwp_pb_fonts_output'],11);
			
			//parser
			require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
		}
	}
	

	public static function classesReplacements($completeContent){
		
		$completeContent = preg_replace('/<amp-img class="brz-img brz-p-absolute amp-wp-enforced-sizes"(.*?)>/', '<amp-img class="amp-wp-enforced-sizes"$1>', $completeContent) ;
		$completeContent = preg_replace('/-ms-flex-direction/', '', $completeContent) ;
		$completeContent = preg_replace('/-ms-flex-wrap:wrap;/', '', $completeContent) ;
		$completeContent = preg_replace('/-webkit-box-sizing:(.*?);/', '', $completeContent) ;
		$completeContent = preg_replace('/-ms-box-sizing:border-box;/', '', $completeContent) ;
		$completeContent = preg_replace('/-o-box-sizing:border-box/', '', $completeContent) ;
		$completeContent = preg_replace('/\.brz \.brz-reset-all{(.*?)}/', '', $completeContent) ;
		$completeContent = preg_replace('/-ms-flex:1 1 auto/', '', $completeContent) ;
		$completeContent = preg_replace('/display:-ms-flexbox/', '', $completeContent) ;
		$completeContent = preg_replace('/-ms-flex-preferred-size:100%/', '', $completeContent) ;
		$completeContent = preg_replace('/-ms-flex-pack:center/', '', $completeContent) ;
		$completeContent = preg_replace('/-webkit-box-direction:normal/', '', $completeContent) ;
		$completeContent = preg_replace('/-moz-osx-font-smoothing:grayscale/', '', $completeContent) ;
		$completeContent = apply_filters("amp_pc_brizy_css_sorting", $completeContent);
	    //Code to remove Header and Footer ends here
    	return $completeContent;
    }

	public function amp_brizy_custom_styles(){

		global $post;
		global $amp_elemetor_custom_css;
		if ( $post ){
			$postID = $post->ID;
	         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
	            $postID = ampforwp_get_frontpage_id();
	        }
	        $this->postID = $postID;
		}
		$brizypostObj = Brizy_Editor_Post::get( $postID );

		if(class_exists('Brizy_Editor_Post') && !( $brizypostObj->uses_editor() ) ){
			return ;
		}
		$allCss = '';

		$srcs = array();
		$urlBuilder = new Brizy_Editor_UrlBuilder( Brizy_Editor_Project::get() );
		$assets_url = $urlBuilder->editor_build_url();
		$srcs[] = "${assets_url}/editor/css/preview.css";
		
		$update_css = pagebuilder_for_amp_utils::get_setting_data('brizyCssKeys');
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
		//global $post;
		$pid  = Brizy_Editor::get()->currentPostId();
		$post = null;
		try {
			// do not delete this line
			$user = Brizy_Editor_User::get();

			if ( $pid ) {
				$post = Brizy_Editor_Post::get( $pid );
			}
		} catch ( Exception $e ) {
			//return;
		}
		if( $post ){		
		$params = array( 'content' => '' );		
		if (   !$post->get_compiled_html() ) {
			$compiled_html_head = $post->get_compiled_html_head();
			$compiled_html_head = Brizy_SiteUrlReplacer::restoreSiteUrl( $compiled_html_head );
			$post->set_needs_compile( true )
			           ->saveStorage();

			$params['content'] = $compiled_html_head;
		} else {
			$compiled_page     = $post->get_compiled_page();
			//echo "amperr"; print_r($compiled_page); die;			
			$head              = $compiled_page->get_head();
			$params['content'] = $head;
		}

		$params['content'] = apply_filters( 'brizy_content', $params['content'], Brizy_Editor_Project::get(), $post->getWpPost(), 'head' );
		//echo "came";print_r($params['content']); die;
		$compiledCSS = Brizy_TwigEngine::instance( Brizy_Public_Main::path( 'views' ) )->render( 'head-partial.html.twig', $params );
		preg_match_all('/<style(.*?)>(.*?)<\/style>/si', $compiledCSS, $matches);
		//echo $compiledCSS;
		//print_r($matches);die;
		if(isset($matches[2])){
			$matches[2] = array_filter($matches[2]);
			foreach ($matches[2] as $key => $value) {
				$allCss .= $value;
			}
		}		
	}


		if(!empty(pagebuilder_for_amp_utils::get_setting_data('brizyCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('brizyCss-custom');
        }
		
		if( function_exists('wp_get_custom_css') ){
			$allCss .= wp_get_custom_css();
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

	public function ampforwp_body_class_brizy($classes){
		global $post;
		if ( $post ){
			$postID = $post->ID;
			//print_r($postID); die;
	         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
	            $postID = ampforwp_get_frontpage_id();
	        }
		}
		$postID  = Brizy_Editor::get()->currentPostId();
		

		if(!$postID){ return  $classes; }
		try {
			$is_using_brizy = Brizy_Editor_Post::get( $postID )->uses_editor();
		} catch ( Exception $e ) {
			$is_using_brizy = false;
		}
		if($is_using_brizy){
			$classes[] = 'brz';
			$classes[] = ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) ? 'brz-is-mobile' : '';
		}
		//print_r($classes); die;
		return $classes;
	}

	public function ampforwp_pb_fonts_output(){
		echo '<link href="https://fonts.googleapis.com/css?family=Barlow+Semi+Condensed:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Lato:100,100italic,300,300italic,regular,italic,700,700italic,900,900italic|Overpass:100,100italic,200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic|Red+Hat+Text:regular,italic,500,500italic,700,700italic|DM+Serif+Text:regular,italic|Blinker:100,200,300,regular,600,700,800,900|Aleo:300,300italic,regular,italic,700,700italic|Nunito:200,200italic,300,300italic,regular,italic,600,600italic,700,700italic,800,800italic,900,900italic|Knewave:regular|Palanquin:100,200,300,regular,500,600,700|Palanquin+Dark:regular,500,600,700|Roboto:100,100italic,300,300italic,regular,italic,500,500italic,700,700italic,900,900italic|Oswald:200,300,regular,500,600,700|Oxygen:300,regular,700|Playfair+Display:regular,italic,700,700italic,900,900italic|Fira+Sans:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic|Abril+Fatface:regular|Comfortaa:300,regular,500,600,700|Kaushan+Script:regular|Noto+Serif:regular,italic,700,700italic|Montserrat:100,100italic,200,200italic,300,300italic,regular,italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&subset=arabic,bengali,cyrillic,cyrillic-ext,devanagari,greek,greek-ext,gujarati,hebrew,khmer,korean,latin-ext,tamil,telugu,thai,vietnamese" rel="stylesheet">';

	}

	public static function load_ajax_calls(){

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


	function brizy_amp_fonts(){
		if( pagebuilder_for_amp_utils::get_setting_data('brizy_fontawesome_support') ){
			echo '<link href="https://use.fontawesome.com/releases/v5.0.8/css/all.css" rel="stylesheet">
			<link href="https://use.fontawesome.com/releases/v5.0.8/css/fontawesome.css" rel="stylesheet">
			<link href="https://use.fontawesome.com/releases/v5.0.8/css/brands.css" rel="stylesheet">
			<link href="https://use.fontawesome.com/releases/v5.0.8/css/solid.css" rel="stylesheet">';
		}
	}
}



/**
* Admin section portal Access
**/
pagebuilder_for_amp_brizy_option();
function pagebuilder_for_amp_brizy_option(){

	if(is_admin()){
		new pagebuilder_for_amp_brizy_Admin();
	}else{
		// Instantiate Brizy_For_Amp.
		new Brizy_For_Amp();
	}
	if ( defined( 'DOING_AJAX' ) ) {
        Brizy_For_Amp::load_ajax_calls();
    }
	
}
Class pagebuilder_for_amp_brizy_Admin{
	function __construct(){
		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_brizy'),7,1 );
	}
	public static function get_admin_options($section = array()){
		$obj = new self();
		$section = $obj->add_options_for_brizy($section);
		return $section;
	}
	function add_options_for_brizy($sections){
		$desc = '';
		if(!function_exists('brizy_load')){
			$desc = 'Enable/Activate brizy plugin';
		}
		$accordionArray = array();
		$sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
			if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
				foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
					if($fieldvalue['id'] == 'ampforwp-brizy-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-brizy-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
				}
				break;
			}
		}
		$sections[$sectionskey]['fields'][] = $accordionArray;
		$sections[$sectionskey]['fields'][] = array(
				               'id'       => 'pagebuilder-for-amp-brizy-support',
				               'type'     => 'switch',
				               'title'    => esc_html__('AMP Brizy Compatibility ','accelerated-mobile-pages'),
				               'tooltip-subtitle' => esc_html__('Enable or Disable the Brizy for AMP', 'accelerated-mobile-pages'),
				               'desc'	  => $desc,
				               'section_id' => 'amp-content-builder',
				               'default'  => false
				            );
		foreach ($this->amp_brizy_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }

		return $sections;

	}

	public function amp_brizy_fields(){
        $contents[] = array(
                        'id'       => 'brizyCssKeys',
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
                        'id'       => 'brizyCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
		$contents[] = array(
                        'id'       => 'brizy_fontawesome_support',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Load fontawesome', 'amp-pagebuilder-compatibility'),
                        'desc'      => esc_html__( 'Load fontawesome library from CDN', 'amp-pagebuilder-compatibility' ),
                        'default'  => 0,
                        'section_id' => 'amp-content-builder',
                    );
        return $contents;
    }
}