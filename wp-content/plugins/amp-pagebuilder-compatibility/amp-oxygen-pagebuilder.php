<?php 
final class Oxygen_For_Amp {


	public $postID;
	public $header_html;
	public $sanitizer_script;
	public $footer_html;

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){

		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-oxygen-support') ){
			add_filter('amp_post_template_css', [$this,'amp_oxygen_custom_styles'],11);
            add_filter('ampforwp_the_content_last_filter', [$this,'ampforwp_oxygen_class_replacements'],11);	
			add_action('ampforwp_before_head', [$this,'oxygen_amp_fonts']);
			add_action('ampforwp_before_head', [$this,'amp_pbc_oxy_fontawesome']);
			add_action('amp_post_template_css', [$this,'amp_pbc_oxy_linearicons']);
            add_action('pre_amp_render_post', [$this,'amp_pb_oxygen_compatibility_file_override'],999);
		}
	}

	
    public function ampforwp_oxygen_class_replacements($completeContent){

        $completeContent = preg_replace("/background-image:url\(C:(.*?)\)/s", "background-image:url(C$1)", $completeContent);
        $completeContent = preg_replace("/oxy-gallery/", "oxy-gl", $completeContent);
        $completeContent = preg_replace("/ct-section/", "ct-sc", $completeContent);
        $completeContent = preg_replace("/atomic-secondary-heading/", "ac-s-h", $completeContent);
        $completeContent = preg_replace("/oxy-testimonial/", "o-tl", $completeContent);
        $completeContent = preg_replace("/atomic-subheading/", "a-sg", $completeContent);
        $completeContent = preg_replace("/atomic-primary-button/", "am-p-btn", $completeContent);
        $completeContent = preg_replace("/atomic-medium-button/", "am-m-btn", $completeContent);
        $completeContent = preg_replace("/atomic-testimonial/", "a-tt", $completeContent);
        $completeContent = preg_replace("/atomic-iconblock/", "ac-ib", $completeContent);
        $completeContent = preg_replace("/atomic-outline-button/", "ac-o-bn", $completeContent);
        $completeContent = preg_replace("/atomic-pricing/", "ac-pr", $completeContent);
        $completeContent = preg_replace("/inner-wrap/", "ir-wp", $completeContent);
        $completeContent = preg_replace("/ct-columns/", "ct-cl", $completeContent);
        $completeContent = preg_replace("/oxy-nav-menu/", "ox-nv-m", $completeContent);
        $completeContent = preg_replace("/oxy-stock-content-styles/", "ox-s-ct-s", $completeContent);
        $completeContent = preg_replace("/oxy-comment-form/", "ox-c-f", $completeContent);
        $completeContent = preg_replace("/oxy-comments/", "ox-ct", $completeContent);
        $completeContent = preg_replace("/oxy-pricing-box/", "ox-pg-b", $completeContent);
        $completeContent = preg_replace("/new_columns/", "nw_cs", $completeContent);
        $completeContent = preg_replace("/text_block/", "tt_blk", $completeContent);
        $completeContent = preg_replace("/'\);\s+}\);\s+jQuery\(.*?\)\.each\(function\(\){.*?}\);/s", "", $completeContent);

        $completeContent = preg_replace('/format\("embedded-opentype"\),url\("data:application\/x-font-woff;charset=utf-8;base64,.*?"\)/', '', $completeContent);
		
		$completeContent = preg_replace('/oxy-pro-menu/', 'op-m', $completeContent);
		
		$completeContent = preg_replace('/div_block/', 'd_bl', $completeContent);
		
		$completeContent = preg_replace('/oxy-easy-posts/', 'o-ep', $completeContent);
		
		$completeContent = preg_replace('/menu-item/', 'm-it', $completeContent);

        $dom = new \DOMDocument();
        $dom->loadHTML($completeContent);
        $finder = new DomXPath($dom);
        $all_toggles = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' toggle-item ')]");

        for ($i=0; $i < $all_toggles->length ; $i++) {
        	if($all_toggles){
	        	$togl_hd = $all_toggles->item($i)->childNodes->item(0);
	        	$chck_toghd = $togl_hd->getAttribute('class');
	        	if(strpos($chck_toghd, 'oxy-toggle') !== false){
	        		$togl_hd->setAttribute('on', 'tap:AMP.setState({ visible'.$i.': !visible'.$i.' })');
	        		$togl_hd->setAttribute('tabindex', '0');
	        		$togl_hd->setAttribute('role', 'button');
	        		$togl_ic = $togl_hd->childNodes->item(1);
	        		$chck_togic = $togl_ic->getAttribute('class');
	        		if(strpos($chck_togic, 'oxy-expand-collapse-icon') !== false){
	        			$togl_ic->setAttribute('class', $chck_togic.' oxy-eci-collapsed');
	        			$togl_ic->setAttribute('::openbrack::class::closebrack::', "visible".$i." ? '".$chck_togic."' : '".$chck_togic.' oxy-eci-collapsed'."'");
	        		}	
	        	}
	        	$togl_dc = $all_toggles->item($i)->childNodes->item(1);
	        	$chck_togdc = $togl_dc->getAttribute('class');
	        	if(strpos($chck_togdc, 'ct-text-block') !== false){
	        		$togl_dc->setAttribute('class', $chck_togdc.' hide');
	        		$togl_dc->setAttribute('::openbrack::class::closebrack::', "visible".$i." ? '".$chck_togdc."' : '".$chck_togdc.' hide'."'");
	        	}
        	}
        }

        $completeContent = $dom->saveHTML();
        $completeContent = str_replace('::openbrack::', '[', $completeContent);
        $completeContent = str_replace('::closebrack::', ']', $completeContent);

        $completeContent = preg_replace('/<svg\sid="svg-fancy_icon-(\d+)-(\d+)"><use\sxlink:href="#FontAwesomeicon-(.*?)"><\/use><\/svg>/', '<i id="svg-fancy_icon-$1-$2" class="fa fa-$3" aria-hidden="true"></i>', $completeContent);

        $completeContent = preg_replace('/<svg\sid="svg-fancy_icon-(\d+)-(\d+)"><use\sxlink:href="#Lineariconsicon-(.*?)"><\/use><\/svg>/', '<i id="svg-fancy_icon-$1-$2" class="lnr lnr-$3" aria-hidden="true"></i>', $completeContent);

        return $completeContent;
    }

    public static function classesReplacements($completeContent){
        
        $completeContent = preg_replace("/background-image:url\(C:(.*?)\)/s", "background-image:url(C$1)", $completeContent);
        $completeContent = preg_replace("/amp-form/", "", $completeContent);
        $completeContent = preg_replace("/<style amp-custom>/", "<style amp-custombb>", $completeContent);
        $completeContent = preg_replace("/text_block/", "tt_blk", $completeContent);

    	return $completeContent;
    }

	public function amp_oxygen_custom_styles(){
		global $post;
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
    	//$post_slug = $post->post_name;
    	//$page = get_page_by_path($post_slug);
        //if ($page) {
            //$page_id =  $page->ID;
            //$final_slug = $post_slug.'-'.$page_id;
        //}

        $post_Id = get_the_ID();        
		if(function_exists('wp_upload_dir')){
			$uploadUrl = wp_upload_dir()['baseurl'];
            $uploads_dir = wp_upload_dir()['basedir'];
         	$srcs[] = $uploadUrl."/oxygen/css/universal.css";
         	//$srcs[] = $uploads_dir."/oxygen/css/".$final_slug.".css";
         	$srcs[] = $uploadUrl."/oxygen/css/".$post_Id.".css";
        }

        $plugins_url = plugins_url();
        $srcs[] = $plugins_url."/oxygen/component-framework/oxygen.css";
        $wp_get_canonical_url = wp_get_canonical_url();
        //Multiple css loading because of this.
        //$srcs[] = $wp_get_canonical_url."?post_id=".$post_Id."&xlink=css&nouniversal=true"  ;
        
		//Supported plugin css
		$plugin_css = $this->supported_plugin_css();
		if($plugin_css){
			$srcs = array_merge($srcs, $plugin_css);
		}
		$update_css = pagebuilder_for_amp_utils::get_setting_data('oxygenCssKeys');
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
		if(!empty(pagebuilder_for_amp_utils::get_setting_data('oxygenCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('oxygenCss-custom');
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

    
    function oxygen_amp_fonts(){
        echo '<link href="https://fonts.googleapis.com/css?family=Abel|Abril+Fatface|Acme|Alegreya|Alegreya+Sans|Anton|Archivo|Archivo+Black|Archivo+Narrow|Arimo|Arvo|Asap|Asap+Condensed|Bitter|Bowlby+One+SC|Bree+Serif|Cabin|Cairo|Catamaran|Crete+Round|Crimson+Text|Cuprum|Dancing+Script|Dosis|Droid+Sans|Droid+Serif|EB+Garamond|Exo|Exo+2|Faustina|Fira+Sans|Fjalla+One|Francois+One|Gloria+Hallelujah|Hind|Inconsolata|Indie+Flower|Josefin+Sans|Julee|Karla|Lato|Libre+Baskerville|Libre+Franklin|Lobster|Lora|Mada|Manuale|Maven+Pro|Merriweather|Merriweather+Sans|Montserrat|Montserrat+Subrayada|Mukta+Vaani|Muli|Noto+Sans|Noto+Serif|Nunito|Open+Sans|Open+Sans+Condensed:300|Oswald|Oxygen|PT+Sans|PT+Sans+Caption|PT+Sans+Narrow|PT+Serif|Pacifico|Passion+One|Pathway+Gothic+One|Play|Playfair+Display|Poppins|Questrial|Quicksand|Raleway|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab|Ropa+Sans|Rubik|Saira|Saira+Condensed|Saira+Extra+Condensed|Saira+Semi+Condensed|Sedgwick+Ave|Sedgwick+Ave+Display|Shadows+Into+Light|Signika|Slabo+27px|Source+Code+Pro|Source+Sans+Pro|Spectral|Titillium+Web|Ubuntu|Ubuntu+Condensed|Varela+Round|Vollkorn|Work+Sans|Yanone+Kaffeesatz|Zilla+Slab|Zilla+Slab+Highlight" rel="stylesheet">
' ;
        //$this->amp_fonts_oxygen();
    }

    function amp_pbc_oxy_fontawesome(){
    	echo "<link rel='stylesheet' id='font-awesome-css'  href='https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css' type='text/css' media='all' />";
    }

    function amp_pbc_oxy_linearicons(){
    	echo '@font-face{font-family:Linearicons-Free;src:url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.eot);src:url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.eot?#iefix) format("embedded-opentype"),url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.woff2) format("woff2"),url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.ttf) format("truetype"),url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.woff) format("woff"),url(https://cdn.linearicons.com/free/1.0.0/Linearicons-Free.svg#Linearicons-Free) format("svg");font-weight:400;font-style:normal}.ct-fancy-icon>i { font-size: 55px; }.lnr { font-family: Linearicons-Free; speak: none; font-style: normal; font-weight: 400; font-variant: normal; text-transform: none; line-height: 1; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }.lnr-laptop-phone:before { content: "\e83d"; }';
    }

    public function amp_fonts_oxygen(){
        global $post;
                
    }

    public function amp_pb_oxygen_compatibility_file_override(){
        //echo "stringw";die;
        if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
       $amp_pb_oxygen_template_overide = array( 'ct_text_block', 'ct_code_block', 'oxy_gallery' ) ;
       foreach ($amp_pb_oxygen_template_overide as $key => $value) {
                    if(!empty($value)){
                        if(file_exists(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/oxygen/amp-oxygen-template-".$value.'.php')){
                            //print_r($value); die;
                            remove_shortcode( $value );
                            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/oxygen/amp-oxygen-template-".$value.'.php';
                            add_shortcode( $value, $value.'_amp_pb_compatibility_for_oxygen' );
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
add_action('plugins_loaded', 'pagebuilder_for_amp_oxygen_option');
function pagebuilder_for_amp_oxygen_option(){
    //echo "stringasd";die;
	if(is_admin()){
		new pagebuilder_for_amp_oxygen_Admin();
	}else{
		// Instantiate Oxygen_For_Amp.
	}
	add_action( 'parse_query', 'set_oxygen_pbc_custom_frontpage' );
	new Oxygen_For_Amp();
}

Class pagebuilder_for_amp_oxygen_Admin{
	function __construct(){

		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_oxygen'),7,1 );
	}
	public static function get_admin_options($section = array()){
		$obj = new self();
		//print_r($obj);die;
		$section = $obj->add_options_for_oxygen($section);
		return $section;
	}
	function add_options_for_oxygen($sections){
        //echo "string";die;
		$desc = '';
        if(!function_exists('oxygen_vsb_register_condition')){
            $desc = 'Enable/Activate Oxygen plugin';
        }
		 
		$accordionArray = array();
		$sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
			if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
				foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
					if($fieldvalue['id'] == 'ampforwp-oxygen-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-oxygen-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
				}
				break;
			}
		}
		$sections[$sectionskey]['fields'][] = $accordionArray;
		$sections[$sectionskey]['fields'][] = array(
				               'id'       => 'pagebuilder-for-amp-oxygen-support',
				               'type'     => 'switch',
				               'title'    => esc_html__('AMP Oxygen Compatibility ','accelerated-mobile-pages'),
				               'tooltip-subtitle' => esc_html__('Enable or Disable the Oxygen for AMP', 'accelerated-mobile-pages'),
				               'desc'	  => $desc,
				               'section_id' => 'amp-content-builder',
				               'default'  => false
				            );
		foreach ($this->amp_oxygen_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }

		return $sections;

	}

	public function amp_oxygen_fields(){
        $contents[] = array(
                        'id'       => 'oxygenCssKeys',
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
                        'id'       => 'oxygenCss-custom',
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

if( is_plugin_active('add-search-to-menu/add-search-to-menu.php') ){
	add_filter('is_custom_search_form','amp_pbc_search_form_resolver',10, 1);
}
function amp_pbc_search_form_resolver($srch_form){
	if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
		$srch_form = preg_replace('/<form\s(.*?)\smethod="get"\srole="search" >/', '<form $1 method="get" role="search" target="_top">', $srch_form);
	}
	return $srch_form;
}

function set_oxygen_pbc_custom_frontpage( $query ) {
  if( isset($query->query['amp']) && $query->query['amp'] == 1 && isset($query->is_home) && $query->is_home == 1 && function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() && empty($query->query_vars['page_id']) ){
      $query->query_vars['page_id'] = ampforwp_get_frontpage_id();
      $query->is_page = 1;
      $query->is_home = 0;
      $query->is_singular = 1;
  }
}