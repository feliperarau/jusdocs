<?php 
final class Elementor_For_Amp {

	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	const MINIMUM_PHP_VERSION = '5.0';
	public $postID;
	public $header_html;
	public $sanitizer_script;
	public $footer_html;

	public function __construct() {
		// Init Plugin
		 $this->initialize();
	}
	function initialize(){
		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-elementor-support') ){
           add_filter('amp_post_template_css', [$this,'amp_elementor_custom_styles'],11);
			add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_elementor'],11);
			add_action('ampforwp_before_head', [$this,'elem_amp_fonts']);
			add_filter('ampforwp_pagebuilder_status_modify', [$this, 'pagebuilder_status_reset_elementor'], 10, 2);
			//blacklist sanitizer
			add_filter('ampforwp_content_sanitizers',[$this, 'ampforwp_blacklist_sanitizer'], 99);
			add_filter('amp_content_sanitizers',[$this, 'ampforwp_blacklist_sanitizer'], 99);

			//PDF embedder
			add_action('pre_amp_render_post' ,[$this, 'ampforwp_pdf_embedder_compatibility'] );
		// For Recaptcha Validation		
      if(function_exists('elementor_pro_load_plugin')){
      if (class_exists('\ElementorPro\Modules\Forms\Classes\Recaptcha_Handler') && class_exists('\ElementorPro\Modules\Forms\Classes') && \ElementorPro\Modules\Forms\Classes::is_enabled() ) {
      			$obj = new \ElementorPro\Modules\Forms\Classes\Recaptcha_Handler();
				remove_action( 'elementor_pro/forms/validation', [ $obj, 'validation' ], 10, 2 );
			}
			add_action('wp_loaded','amp_recaptcha_resolver', 10);	
			add_action( 'elementor_pro/forms/validation', [ $this, 'recaptcha_validation' ], 10, 2 );
			}
			add_action('wp_ajax_elementor_amp_cform_submission',[$this, 'elementor_amp_cform_submission']);
			add_action('wp_ajax_nopriv_elementor_amp_cform_submission',[$this,'elementor_amp_cform_submission']);

			add_action( 'wp_ajax_amp_elementor_ajax_comment',[$this,'amp_elementor_ajax_comment']); 
            add_action( 'wp_ajax_nopriv_amp_elementor_ajax_comment', [$this,'amp_elementor_ajax_comment'] ); 

            // Form submission code start here
            add_action('wp_ajax_jet_elements_subscribe_optin_form_submission',[$this,'jet_elements_subscribe_optin_form_submission']);
            add_action('wp_ajax_nopriv_jet_elements_subscribe_optin_form_submission',[$this,'jet_elements_subscribe_optin_form_submission']);
			//include files
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/load-elementor-widgets.php' );
            if(function_exists('elementor_pro_load_plugin')){
                require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/themebuilder/classes/locations-manager.php');
                require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/themebuilder/classes/theme-support.php');
                require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/themebuilder/documents/theme-document.php');
                require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/themebuilder/documents/theme-page-document.php');
                require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/themebuilder/documents/single.php');
            }
			require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/jet-themecore/jet-theme_single.php';
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/jet-themecore/jet-theme_header_footer.php';
            add_filter("ampforwp_the_content_last_filter", [$this,"amp_toc_content_adder"]);
            add_filter("ampforwp_the_content_last_filter", [$this,"amp_form_step_creater"]);
		}
	}
	

	function amp_toc_content_adder($content_buffer){
	    if(stripos($content_buffer, 'amp-toc-cmp') !== false){
	    	$occurence =0;
	    	preg_match_all('/<a\shref="#amp-elementor-toc-anchor-(\d+)"\sclass="elementor-toc__list-item-text\sampforwp-toc-tag-(h[0-9])">(.*?)<\/a>/s', $content_buffer, $chcktoc);
	        if(isset($chcktoc[2])){
	            foreach ($chcktoc[2] as $key => $tagsname) {
	            	$cContent = '';
	                $cKey = $chcktoc[1][$key];
	                $cContent = $chcktoc[3][$key];
	                $content_buffer = preg_replace_callback('/<'.$tagsname.'(.*?)>\s+<a(.*?)>\s+'.trim($cContent).'\s+<\/a>\s+<\/'.$tagsname.'>/', function($matches) use(&$tagsname, &$cKey, &$cContent){
	                    $a = '<span id="amp-elementor-toc-anchor-'.$cKey.'"></span><'.$tagsname.$matches[1].'><a'.$matches[2].'>'.trim($cContent).'</a></'.$tagsname.'>';
	                    return $a;

	                }, $content_buffer);
	                $content_buffer = preg_replace_callback('/<'.$tagsname.'(.*?)>\s+<span>\s+'.trim($cContent).'\s+<\/span>\s+<\/'.$tagsname.'>/', function($matches) use(&$tagsname, &$cKey, &$cContent){
	                    $a = '<span id="amp-elementor-toc-anchor-'.$cKey.'"></span><'.$tagsname.$matches[1].'><span>'.trim($cContent).'</span></'.$tagsname.'>';
	                    return $a;

	                }, $content_buffer);
	            }
	        }
	    }
	    return $content_buffer;
	}

    function amp_form_step_creater($content_buffer){
		preg_match_all('/<div\sclass="elementor-field-type-step\selementor-field-group(.*?)">\s+<div\shidden\sclass="aelform_btn_">(.*?)<\/div>\s+<\/div>/si', $content_buffer, $matches);
	 	for ($i=0; $i < count($matches[0]); $i++) {
	    	if($i > 0){
	      		$j = $i-1;
	    	}
		    $k = $i+1;
		    $hello = str_replace('">', '', $ex[1]);
		    $hide = 'hidden';
		    $button ='<div class="elementor-field-group ec elementor-field-type-submit ecol-30"><button id="valelform" on="tap:AMP.setState({ hidebutton'.$k.': false, hidebutton'.$i.': true})" [hidden]="hidebutton'.$i.'" class="ebtn prev-next" tabindex="0" role="button" disabled>'.$matches[2][$k].'</button></div>';
		    $prev_button = '<div class="elementor-field-group ec elementor-field-type-submit ecol-33"><button id="amp-submit-btn" on="tap:AMP.setState({ hidebutton'.$j.': false, hidebutton'.$i.': true})" [hidden]="hidebutton'.$i.'" tabindex="0" role="button" class="ebtn prev-next">Back</button></div>';
		    if($i == 0){
		        $hide = '';
		        $prev_button = '';
		    }else{
		    	$button = '';
		    }
		    $content_buffer = preg_replace('/<div\sclass="elementor-field-type-step\selementor-field-group(.*?)">\s+<div\shidden\sclass="aelform_btn_">(.*?)<\/div>\s+<\/div>/', '</div><div '.$hide.' [hidden]="hidebutton'.$i.'" class="elementor-field-type-step elementor-field-group$1">'.$prev_button.' ', $content_buffer,1); 
		    $content_buffer = preg_replace('/<div(.*?)\[hidden\]="hidebutton(\d)"\sclass="elementor-field-type-step\selementor-field-group(.*?)">(.*?)<\/div>/si', '<div$1[hidden]="hidebutton$2" class="elementor-field-type-step elementor-field-group$3">$4</div>'.$button.'', $content_buffer ); 
		}
		return $content_buffer;
    }

	function pagebuilder_status_reset_elementor($response, $postId ){
  		if(class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($postId) ){
  			//$response = true;
  		}
        return $response;
    }

    public static function classesReplacements($completeContent){
        if(pagebuilder_for_amp_utils::get_setting('elem-themebuilder_header')){
            $completeContent = preg_replace("/<header(.*?)class=\"header[-|0-9]* h_m h_m_1\">(.*?)<\/header>/s", "", $completeContent);
        }
        if(pagebuilder_for_amp_utils::get_setting('elem-themebuilder_footer')){
            $completeContent = preg_replace("/<footer\sclass=\"footer\">(.*?)<\/footer>/si", "", $completeContent);
        }
		$completeContent = preg_replace("/<form class=\"elementor-search-form\" role=\"search\" action=\"(.*?)\" method=\"get\">(.*?)<\/form>/s", "", $completeContent);
		$completeContent = preg_replace("/elementor-invisible/", "", $completeContent);
        $completeContent = preg_replace("/data-portfoliowork/", "[class]", $completeContent);

    	$completeContent = preg_replace("/elementor-element-populated/", "epo", $completeContent);
    	$completeContent = preg_replace("/elementor-element/", "ee", $completeContent);    	      
    	$completeContent = preg_replace("/elementor-container/", "econ", $completeContent);
        $completeContent = preg_replace('/\[data-ele-type=popup]:not\(.ele-edit-mode\){display:none}/m', '', $completeContent);
            	
    	$completeContent = preg_replace("/elementor-row/", "er", $completeContent);
    	$completeContent = preg_replace("/elementor-default/", "ed", $completeContent);
    	$completeContent = preg_replace("/elementor-column/", "ec", $completeContent);
    	$completeContent = preg_replace("/elementor-widget-container/", "ewcont", $completeContent);
    	$completeContent = preg_replace("/elementor-widget/", "ew", $completeContent);
    	$completeContent = preg_replace("/elementor-section/", "es", $completeContent);
    	$completeContent = preg_replace("/elementor-inner-column/", "eic", $completeContent);
    	$completeContent = preg_replace("/elementor-divider-separator/", "eds", $completeContent);
    	$completeContent = preg_replace("/elementor-inner/", "einn", $completeContent);
    	$completeContent = preg_replace("/elementor-heading-title/", "eht", $completeContent);
    	$completeContent = preg_replace("/elementor-spacer-inner/", "esi", $completeContent);
    	$completeContent = preg_replace("/elementor-background-overlay/", "e-bg-ov", $completeContent);
    	$completeContent = preg_replace("/elementor-icon/", "eic", $completeContent);
    	$completeContent = preg_replace("/elementor-icon-list-items/", "eilts", $completeContent);
    	$completeContent = preg_replace("/elementor-icon-list-item/", "eilt", $completeContent);
    	$completeContent = preg_replace("/elementor-button/", "ebtn", $completeContent);
    	$completeContent = preg_replace("/elementor-reverse-mobile/", "erev", $completeContent);	
    	//Gravity
    	$completeContent = preg_replace("/gform_fields/", "gff", $completeContent);
    	//$completeContent = preg_replace("/elementor-/", "ele-", $completeContent);
    	$completeContent = preg_replace("/elementor-invisible/s", "", $completeContent);
    	$completeContent = preg_replace("/elementor-col/s", "ecol", $completeContent);
    	//$completeContent = preg_replace("/elementor-/s", "ele-", $completeContent);

    	$completeContent = preg_replace("/ele-testimonial/", "etml", $completeContent);
		$completeContent = preg_replace("/etml-wrapper/", "etmlwr", $completeContent);
		$completeContent = preg_replace("/.ew-image .ele-image>a,/", "", $completeContent);
		//col_item
      $completeContent = preg_replace("/col_item/", "ci", $completeContent); 
      //col_wrap_fourth
      $completeContent = preg_replace("/col_wrap_fourth/", "cf", $completeContent);  
      //col-feat-grid 
      $completeContent = preg_replace("/col-feat-grid/", "cg", $completeContent);
      //deal_daywoo
      $completeContent = preg_replace("/deal_daywoo/", "dd", $completeContent);
      //categoriesbox
      $completeContent = preg_replace("/categoriesbox/", "cb", $completeContent);
      //e-tg-item
      $completeContent = preg_replace("/e-tg-item/", "ei", $completeContent);
      //button_action
      $completeContent = preg_replace("/button_action/", "ba", $completeContent);
		$completeContent = preg_replace("/ele-motion-effects-element-type-background/s", "ele-meetb", $completeContent);
		//CSS Optimization.
		$completeContent = preg_replace("/ele-share-buttons--skin-gradient/", "e-sbsg", $completeContent);
		$completeContent = preg_replace("/ele-share-buttons--skin-flat/", "e-sb-sf", $completeContent);
		$completeContent = preg_replace("/post__/", "pst_", $completeContent);
		$completeContent = preg_replace("/ele-post-navigation/", "e-pnvgn", $completeContent);
		$completeContent = preg_replace("/ele-pst_meta-data/", "e-p_m-d", $completeContent);
		$completeContent = preg_replace("/nav-menu/", "n-m", $completeContent);
		$completeContent = preg_replace("/ele-n-m--main/", "e-n-m-m", $completeContent);
		$completeContent = preg_replace("/post-navigation__prev--label/", "p-np-l", $completeContent);
		$completeContent = preg_replace("/ele-share-buttons--color-official/", "e-sb-cof", $completeContent);
		$completeContent = preg_replace("/ele-share-btn__text/", "e-s-b_te", $completeContent);
		$completeContent = preg_replace("/ele-share-btn__icon/", "e-s-bt_i", $completeContent);
		$completeContent = preg_replace("/ele-share-btn/", "e-se-bn", $completeContent);
		$completeContent = preg_replace("/ele-posts--skin-cards/", "e-p-scs", $completeContent);
		$completeContent = preg_replace("/ele-share-buttons--view-i/", "es-bv-i", $completeContent);
		$completeContent = preg_replace("/ele-share-buttons/", "e-s-bs", $completeContent);
		$completeContent = preg_replace("/ele-field-group/", "e-f-gp", $completeContent);
		$completeContent = preg_replace("/ele-field-textual/", "e-f-tl", $completeContent);
		$completeContent = preg_replace("/ele-field/", "e-fd", $completeContent);
		$completeContent = preg_replace("/ele-form-fields-wrapper/", "e-ff-w", $completeContent);
		$completeContent = preg_replace("/ele-grid/", "e-gr", $completeContent);
		$completeContent = preg_replace("/ele-n-m--dropdown/", "e-n-dn", $completeContent);
		$completeContent = preg_replace("/ele-posts--thumbnail-top/", "e-pt-t", $completeContent);
		$completeContent = preg_replace("/ele-sitemap-section/", "e-sm-s", $completeContent);
		$completeContent = preg_replace("/post-navigation__prev--title/", "p-n_p-t", $completeContent);
		$completeContent = preg_replace("/post-navigation__next--label/", "p-n_n-l", $completeContent);
		$completeContent = preg_replace("/-webkit-box-orient:(.*?);/", "", $completeContent);
		 $completeContent = preg_replace("/-webkit-box-pack:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-webkit-justify-content:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-webkit-align-items:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-ms-flex-align:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-ms-transform:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-ms-flex-item-align:(.*?);/", "", $completeContent);
		$completeContent = preg_replace("/-ms-flex-wrap:(.*?);/", "", $completeContent);
        $completeContent = preg_replace("/-webkit-transform:(.*?);/", "", $completeContent);
        $completeContent = preg_replace("/-webkit-flex-grow:(.*?);/", "", $completeContent);
        $completeContent = preg_replace("/display:-webkit-box;/", "", $completeContent);
        $completeContent = preg_replace("/-webkit-border-radius:(.*?);/", "", $completeContent);
        $completeContent = preg_replace("/-webkit-transition:(.*?);/", "", $completeContent);
        $completeContent = preg_replace("/display:-webkit-flex;/", "", $completeContent);
        $completeContent = preg_replace("/display:-ms-flexbox;/", "", $completeContent);
		$completeContent = preg_replace("/-select-wrapper/s", "-sewr", $completeContent);
		$completeContent = preg_replace("/-shape-bottom/s", "-shb", $completeContent);
		$completeContent = preg_replace("/-shape-fill/s", "-shf", $completeContent);
		$completeContent = preg_replace("/-icon-box-title/s", "-icbtit", $completeContent);
		$completeContent = preg_replace("/ele-star-rating/s", "esr", $completeContent);
		$completeContent = preg_replace("/ec-wrap/s", "ewr", $completeContent);
		$completeContent = preg_replace("/ew-wrap/s", "ew-r", $completeContent);
		$completeContent = preg_replace("/ew-heading/s", "ehd", $completeContent);
	    $completeContent = preg_replace("/ele-view-framed/s", "e-vf", $completeContent);
	    //$completeContent = preg_replace("/-icon/s", "-i", $completeContent);
	    $completeContent = preg_replace("/ele-toggle/s", "e-tg", $completeContent);
	    $completeContent = preg_replace("/ewcont/s", "ect", $completeContent);
	    $completeContent = preg_replace("/ele-image/s", "eim", $completeContent);
        $completeContent = preg_replace("/ele-i-list-/s", "eil-", $completeContent);
        //$completeContent = preg_replace("/ee-/s", "ee", $completeContent);
        $completeContent = preg_replace("/amp-menu/s", "a-m", $completeContent);
        $completeContent = preg_replace("/amp-category/s", "ampcat", $completeContent);
        $completeContent = preg_replace("/loop-category/s", "lpct", $completeContent);
       // $completeContent = preg_replace("/breadcrumb/s", "bdcr", $completeContent);
        $completeContent = preg_replace("/\.ele-([0-9]*)\s\.ee.ee([a-zA-Z0-9]*)\s>\s(\.epo|\.ect){padding:0px 0px 0px 0px}/s", "", $completeContent);
        $completeContent = preg_replace("/\.ele-([0-9]*)\s.ee.ee([a-zA-Z0-9]*)\.ec\s\.ewr{align-items:center}/s", ".ee.ee$2.ec .ewr{align-items:center}", $completeContent);
         $completeContent = preg_replace("/body:not\(.rtl\)\s\.ele-([0-9]*)\s\.ee.ee([a-zA-Z0-9]*)\s\.esr\si:not\(:last-of-type\){margin-right:[a-zA-Z0-9]*}/","", $completeContent);
         $completeContent = preg_replace("/.woocommerce amp-img,.w-pg amp-img{height:auto;max-width:100%}/",".woocommerce amp-img,.w-pg amp-img{height:auto;max-width:38%}", $completeContent);
         $completeContent = preg_replace("/ele-testimonial-wrapper/","ele-t-w", $completeContent);
         $completeContent = preg_replace("/ew-divider/","ew-dv", $completeContent);
         $completeContent = preg_replace("/ele-i-wrapper/","ele-i-w", $completeContent);
         $completeContent = preg_replace("/ele-view-default/","e-vd", $completeContent);
         $completeContent = preg_replace("/premium-button/","p-bt", $completeContent);
         $completeContent = preg_replace("/ew-premium-addon-button/","ew-p-a-b", $completeContent);
         $completeContent = preg_replace("/gform_wrapper/","g_w", $completeContent);
         $completeContent = preg_replace("/ginput_container/","g_c", $completeContent);
         $completeContent = preg_replace("/divider/","dd", $completeContent);
         $completeContent = preg_replace("/transform:rotate\(0deg\)(.*?)/","", $completeContent);
         $completeContent = preg_replace("/font-style:var\(--body_typography-(.*?)\);/","", $completeContent);
         $completeContent = preg_replace("/--body_typography-(.*?)/","", $completeContent);
         $completeContent = preg_replace("/gfield_label/","g_l", $completeContent);
         $completeContent = preg_replace("/text-i-wrapper/","t-i-w", $completeContent);
         $completeContent = preg_replace("/transition:background .3s,border .3s,border-radius .3s,box-shadow .3s/","", $completeContent);
         $completeContent = preg_replace("/ele-i-box-wrapper/","ele-i-b-w", $completeContent);
         $completeContent = preg_replace("/price-table/","p", $completeContent);
         $completeContent = preg_replace("/ele-position/","ele-po", $completeContent);
         $completeContent = preg_replace("/-boxed/","-bx", $completeContent);
         $completeContent = preg_replace("/box-content/","bc", $completeContent);
         $completeContent = preg_replace("/heading/","he", $completeContent);
         $completeContent = preg_replace("/es-height-default/","es-h-d", $completeContent);
         $completeContent = preg_replace("/e-animation/","e-an", $completeContent);
         $completeContent = preg_replace("/ele-p__/","ele-p_", $completeContent);
         $completeContent = preg_replace("/ele-p_features-list/","ele-p_f-l", $completeContent);
         $completeContent = preg_replace("/ele-p_feature-inner/","ele-p_f-i", $completeContent);
         $completeContent = preg_replace("/-webkit-box-sizing:border-box;/","", $completeContent);
         $completeContent = preg_replace("/-webkit-font-smoothing:antialiased;/","", $completeContent);
         $completeContent = preg_replace("/-moz-osx-font-smoothing:grayscale/","", $completeContent);
         $completeContent = preg_replace("/ele-vertical-align/","ele-v-a", $completeContent);
         $completeContent = preg_replace("/ele-repeater-item/","ele-r-i", $completeContent);
         $completeContent = preg_replace("/ele-size/","ele-s", $completeContent);
         $completeContent = preg_replace("/ele-top-section/","ele-t-s", $completeContent);
        // $completeContent = preg_replace("/testimonial/","tm", $completeContent);
         //$completeContent = preg_replace("/size-default/","s-d", $completeContent);
         $completeContent = preg_replace("/top-column/","t-c", $completeContent);
         //$completeContent = preg_replace("/-default/","df", $completeContent);
         $completeContent = preg_replace("/einn-section/","einn-s", $completeContent);
         $completeContent = preg_replace("/a-top/","a-t", $completeContent);
         $completeContent = preg_replace("/i-box/","i-b", $completeContent);
         $completeContent = preg_replace("/currency/","cy", $completeContent);
         $completeContent = preg_replace("/p_header/","p_h", $completeContent);
         $completeContent = preg_replace("/fractional-part/","f-p", $completeContent);
         $completeContent = preg_replace("/p_footer/","p_ft", $completeContent);
         $completeContent = preg_replace("/-an-grow/","-a-g", $completeContent);
         $completeContent = preg_replace("/po-top/","p-tp", $completeContent);
         $completeContent = preg_replace("/gapdf/","gd", $completeContent);
         $completeContent = preg_replace("/content-middle/","c-m", $completeContent);
         $completeContent = preg_replace("/ew-image/","ew-ig", $completeContent);
         $completeContent = preg_replace("/gap-no/","g-n", $completeContent);
         $completeContent = preg_replace("/_price/","_pr", $completeContent);
         $completeContent = preg_replace("/amp-post-title/","a-p-t", $completeContent);
         $completeContent = preg_replace("/ampforwp_contact_bar/","a_c_b", $completeContent);
         $completeContent = preg_replace("/ginput_complex/","gi_c", $completeContent);
         $completeContent = preg_replace("/etml-content/","el-c", $completeContent);
         $completeContent = preg_replace("/menu-main-menu/","m-m-m", $completeContent);
         $completeContent = preg_replace("/sub-menu/","s-m", $completeContent);
         $completeContent = preg_replace("/amp-search/","a-s", $completeContent);
         $completeContent = preg_replace("/s-submit/","s-s", $completeContent);
         $completeContent = preg_replace("/-wrapper/","-wr", $completeContent);
         $completeContent = preg_replace('/data-col="/','data-c="', $completeContent);
         $completeContent = preg_replace('/srch/','s', $completeContent);
         $completeContent = preg_replace('/ele-form/','ele-f', $completeContent);
         $completeContent = preg_replace('/screen-reader-text/','s-r-t', $completeContent);
         $completeContent = preg_replace('/p_button/','p_bt', $completeContent);
         $completeContent = preg_replace('/-text-editor/','-t-ed', $completeContent);
         $completeContent = preg_replace('/p_after-price/','p_a-p', $completeContent);
         $completeContent = preg_replace('/p_integer-part/','p_i-p', $completeContent);
         $completeContent = preg_replace('/eim-box-description/','eim-b-d', $completeContent);
         $completeContent = preg_replace('/ele-counter-number-wr/','ele-c-n-wr', $completeContent);
         $completeContent = preg_replace('/eim-box-title/','eim-b-t', $completeContent);
         $completeContent = preg_replace('/ele-social-icon/','ele-s-i', $completeContent);
         $completeContent = preg_replace('/eael-post-list-container/','eael-p-l-cn', $completeContent);
         $completeContent = preg_replace('/eael-post-list-title/','eael-p-l-t', $completeContent);
         $completeContent = preg_replace('/eael-post-list-header/','eael-p-lt-h', $completeContent);

        // add id to tags for elementor toc.
        /*if(class_exists("\ElementorPro\Plugin") && preg_match('/<span class="get_toc_tag">(.*?)<\/span>/', $completeContent)){
            $re = '/<span class="get_toc_tag">(.*?)<\/span>/';
            preg_match_all($re, $completeContent, $toc_tag);
            foreach($toc_tag[1] as $tag){
             $completeContent = preg_replace('/<'.$tag.'(.*?)>(.*?)<\/'.$tag.'>/','<'.$tag.' $1 id="amp-elementor-toc-anchor">$2</'.$tag.'>', $completeContent);
            }
            $toc_pattern = '/<(.*?)id="amp-elementor-toc-anchor"\s+(.*?)>(.*?)<\/(.*?)>/';
            $count = 0;
            $count = $count - 1;
            $completeContent = preg_replace_callback($toc_pattern,function ($m) use (&$count) { 
                $count++;
                return '<'.$m[1].' id="amp-elementor-toc-anchor-'.$count.'" '.$m[2].'>'.$m[3].'</'.$m[4].'>';
            },$completeContent);
        }*/
         //css optimization.
         $completeContent = preg_replace("/-webkit-background-size:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-box-sizing:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-flex:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-flex:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-shadow:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-flex-wrap:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-order:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-ordinal-group:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-transform-origin:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-flex-direction:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-align-content:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-flex-shrink:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-align:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-pack:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-direction:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-box-direction:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-line-pack:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-negative:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-positive:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/display:-webkit-inline-flex;/", "", $completeContent);
         $completeContent = preg_replace("/display:-ms-inline-flexbox;/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-flex:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-webkit-align-self:(.*?);/", "", $completeContent);
         $completeContent = preg_replace("/-ms-flex-order:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-webkit-transition-timing-function:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-moz-box-sizing:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-ms-transform-origin:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-webkit-hyphens:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-ms-hyphens:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-o-transition-timing-function:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-webkit-filter:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/display:-webkit-inline-box;/", "", $completeContent);
          $completeContent = preg_replace("/-webkit-transform-style:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-webkit-calc(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/ele-arrows-yes/", "e-a-ys", $completeContent);
          $completeContent = preg_replace("/ele-portfolio/", "e-pfio", $completeContent);
          $completeContent = preg_replace("/uael-pst_columns/", "u-p_cs", $completeContent);
          $completeContent = preg_replace("/ele-headline-text-wr/", "e-hl-tt-r", $completeContent);
          $completeContent = preg_replace("/uael-equal__height-yes/", "u-eq__he-y", $completeContent);
          $completeContent = preg_replace("/ele-headline-plain-text/", "el-ha-pa-te", $completeContent);
          $completeContent = preg_replace("/uael-grid-caption-text/", "ua-gd-ct-tx", $completeContent);
          $completeContent = preg_replace("/e-pfio-item__title/", "e-pfo-it__ti", $completeContent);
          $completeContent = preg_replace("/eicon-chevron-left/", "ein-che-le", $completeContent);
          $completeContent = preg_replace("/ul-i-gr-mo__cln-/", "ein-che-le", $completeContent);
         $completeContent = preg_replace("/eicon-chevron-right/", "ec-c-rt", $completeContent);
         $completeContent = preg_replace("/e-pfio-item__overlay/", "e-pf-i__ov", $completeContent);
         $completeContent = preg_replace("/swiper-container/", "s-cer", $completeContent);
         $completeContent = preg_replace("/e-sr-bon-next/", "e-s-b-nr", $completeContent);
         $completeContent = preg_replace("/uael-he-text/", "u-he-t", $completeContent);
         $completeContent = preg_replace("/uael-ins-target/", "u-is-tt", $completeContent);
         $completeContent = preg_replace("/ele-icbtit/", "e-ict", $completeContent);
         $completeContent = preg_replace("/ew-reviews/", "e-rs", $completeContent);
         $completeContent = preg_replace("/ele-shape-top/", "e-s-tp", $completeContent);
          $completeContent = preg_replace("/ele-swiper-button/", "e-sr-bon", $completeContent);
         $completeContent = preg_replace("/uael-img-gallery-wrap/", "ul-i-g-wp", $completeContent);
         $completeContent = preg_replace("/uael-grid-item/", "ul-gd-im", $completeContent);
         $completeContent = preg_replace("/uael-grid-gallery-img/", "u-g-g-i", $completeContent);
         $completeContent = preg_replace("/uael-grid-img-caption/", "u-g-i-cn", $completeContent);
         $completeContent = preg_replace("/uael-grid-img-overlay/", "u-g-ig-oy", $completeContent);
         $completeContent = preg_replace("/ew-uael-advanced-he/", "e-ul-ad-h", $completeContent);
         $completeContent = preg_replace("/ew-uael-cf7-style/", "ew-u-c7-s", $completeContent);
        $completeContent = preg_replace("/uael-cf7-style/", "u-f7-le", $completeContent);
        $completeContent = preg_replace("/uael-pst_meta-data/", "u-p_ma-da", $completeContent);
        $completeContent = preg_replace("/uael-pst_link-complete-yes/", "u-p_l-c-y", $completeContent);
        $completeContent = preg_replace("/uael-post-wr/", "u-p-w", $completeContent);
        $completeContent = preg_replace("/uael-pst_thumbnail/", "u-p_tl", $completeContent);
        $completeContent = preg_replace("/uael-pst_inner-wrap/", "u-p_in-wp", $completeContent);
        $completeContent = preg_replace("/uael-pst_complete-box-overlay/", "u-p_o-bv", $completeContent);
        $completeContent = preg_replace("/uael-pst_title/", "u-p_te", $completeContent);
        $completeContent = preg_replace("/uael-cf7-input-size-md/", "u-c-i-s-m", $completeContent);
        $completeContent = preg_replace("/uael-img-grid/", "ul-i-gr", $completeContent);
        $completeContent = preg_replace("/ele-pst_thumbnail__link/", "el-p_th__l", $completeContent);
        $completeContent = preg_replace("/uael-pst_content-wrap/", "u-p_ct-wp", $completeContent);
        $completeContent = preg_replace("/uael-pst_bg-wrap/", "u-p_b-wp", $completeContent);
         $completeContent = preg_replace("/ew-uael-posts/", "e-u-ps", $completeContent);
         $completeContent = preg_replace("/ele-posts-container/", "el-p-c", $completeContent);
         $completeContent = preg_replace("/uael-subhe/", "ua-se", $completeContent);
         $completeContent = preg_replace("/ele-portfolio-item/", "e-pio-im", $completeContent);
         $completeContent = preg_replace("/ele-pagination-type-bullets/", "e-pn-te-bs", $completeContent);
         $completeContent = preg_replace("/ele-pst_thumbnail/", "e-pt_tl", $completeContent);
         $completeContent = preg_replace("/ele-icon-box-description/", "e-in-b-de", $completeContent);
         $completeContent = preg_replace('/ele-headline--style-rotate/','e-h--st-ro', $completeContent);
         $completeContent = preg_replace('/ele-headline-dynamic-wr/','e-h-dy-w', $completeContent);
         $completeContent = preg_replace('/ele-headline-an-type-slide-down/','e-h-an-t-s-d', $completeContent);
         $completeContent = preg_replace('/ele-icon-list-text/','e-i-lt-tx', $completeContent);
         $completeContent = preg_replace('/ew-icon-list/','e-in-s', $completeContent);
         $completeContent = preg_replace('/uael-post-grid__inner/','u-p-gd__ir', $completeContent);
         $completeContent = preg_replace('/ele-main-swiper/','e-mn-sr', $completeContent);
         $completeContent = preg_replace('/ele-swiper-button-prev/','e-sr-bn-p', $completeContent);
         $completeContent = preg_replace('/ele-swiper-button-next/','e-swr-bn-n', $completeContent);
         $completeContent = preg_replace('/ele-align-righ/','e-a-rh', $completeContent);
         $completeContent = preg_replace('/ele-icon-list-icon/','e-i-l-in', $completeContent);
         $completeContent = preg_replace('/ew-icon-box/','e-in-bx', $completeContent);
         $completeContent = preg_replace('/ele-icon-box-icon/','e-in-b-ix', $completeContent);
         $completeContent = preg_replace('/ele-po-right/','e-p-rt', $completeContent);
         $completeContent = preg_replace('/ele-icon-box-wr/','e-i-bx-wr', $completeContent);
         $completeContent = preg_replace('/ele-tabs-view-horizontal/','etv-hrz', $completeContent);
        $completeContent = preg_replace('/ele-tabs-view-vertical/','etv-vrt', $completeContent);
        $completeContent = preg_replace('/-webkit-transition-property(.*?);/','', $completeContent);
        $completeContent = preg_replace('/uael-img-caption-valign-bottom/','u-i-c-v-b', $completeContent);
        $completeContent = preg_replace('/font-variant(.*?);/','',$completeContent);
        $completeContent = preg_replace('/\.ebgov{transition:(.*?)}/',' ',$completeContent);
        $completeContent = preg_replace('/border-radius:0px 0px 0px 0px/','border-radius:0px',$completeContent);
        $completeContent = preg_replace('/border-width:0px 0px 0px 0px/','border-width:0px',$completeContent);
        $completeContent = preg_replace('/margin:0px 0px 0px 0px/','margin:0px',$completeContent);
        $completeContent = preg_replace('/padding:0px 0px 0px 0px/','padding:0px',$completeContent);
        $completeContent = preg_replace('/border-radius:3px 3px 3px 3px/','border-radius: 3px',$completeContent);
        $completeContent = preg_replace('/border-width:2px 2px 2px 2px/','border-width:2px',$completeContent);
        $completeContent = preg_replace('/padding:5px 5px 5px 5px/','padding:5px',$completeContent);
        $completeContent = preg_replace('/\s>\s\.epo{}\.ele-(.*?)\s\.ee\.e(.*?)>\s\.epo\s>\s/',' ',$completeContent);
        $completeContent = str_replace('data-portfolioClass','[class]',$completeContent);
        $completeContent = preg_replace('/<span\sclass="ele-counter-number"(.*?)data-to-value="(.*?)"(.*?)>(.*?)<\/span>/','<span class="ele-counter-number"$1data-to-value="$2"$3>$2</span>', $completeContent);
        $completeContent = preg_replace('/@-ms-viewport{width:device-width}/','', $completeContent);
        $completeContent = preg_replace('/path\.acssd9975{fill-opacity:1;fill-rule:nonzero;fill:#646464;stroke:none}/','path.acssd9975{fill-opacity:1;fill-rule:nonzero;fill:#FFFFFF;stroke:none}', $completeContent);
        $theme = wp_get_theme(); // gets the current theme
          if ( $theme->name == "Hello Elementor Child" && is_single() && class_exists("\ElementorPro\Plugin")){   
            $completeContent = preg_replace('/<section class="ee(.*?)\s+es-full_width elementor-hidden-desktop(.*?)"(.*?)>/', '<section class="ee e6d3c $1 es-full_width elementor-hidden-desktop$2"$3>', $completeContent);
          }
        //css optimization
          $completeContent = preg_replace("/ele-column-wrap/", "el-c-wr", $completeContent);
          $completeContent = preg_replace("/elementor-align-cente/", "ele-al-c", $completeContent);
          $completeContent = preg_replace("/elementor-inline-item/", "el-in-i", $completeContent);
          $completeContent = preg_replace("/elementor-grid/", "el-gr", $completeContent);
          $completeContent = preg_replace("/elementor-toggle-icon/", "el-t-i", $completeContent);
          $completeContent = preg_replace("/elementor-share-btn/", "el-sh-b", $completeContent);
          $completeContent = preg_replace("/elementor-share-buttons--/", "el-s-b--", $completeContent);
          $completeContent = preg_replace("/elementor-icon-list-icon/", "el-i-l-i", $completeContent);
          $completeContent = preg_replace("/elementor-icon-list-text/", "el-i-li-t", $completeContent);
          $completeContent = preg_replace("/elementor-screen-only/", "el-s-o", $completeContent);
          $completeContent = preg_replace("/elementor-page/", "el-pa", $completeContent);
          $completeContent = preg_replace("/e-s-b--skin-flat/", "e-s-b--s-f", $completeContent);
          $completeContent = preg_replace("/elementor-image/", "ele-img", $completeContent);
          $completeContent = preg_replace("/-webkit-transition-duration:(.*?);/", "", $completeContent);
          $completeContent = preg_replace("/-o-transition-duration:(.*?);/", "", $completeContent);
          $completeContent = preg_replace('/<div\sclass="elementor-pst_thumbnail"><amp-img(.*?)layout="intrinsic">/s', '<div class="elementor-pst_thumbnail"><amp-img$1layout="fill">', $completeContent);
          $completeContent = preg_replace("/elementor-counter/", "elcuntr", $completeContent);
          $completeContent = preg_replace("/elementor-item/", "elitm", $completeContent);
          $completeContent = preg_replace("/elementor-testimonial/", "eltstm", $completeContent);
          $completeContent = preg_replace("/elementor-motion-effects-element-type-background/", "elmo-ef-eltpbg", $completeContent);
          $completeContent = preg_replace('/<amp-img id="lightbox-w-carousel" lightbox(.*?)><amp-img fallback id="lightbox-w-carousel" lightbox(.*?)><\/amp-img>/', '<amp-img id="lightbox-w-carousel" lightbox$1>', $completeContent);

          $completeContent = preg_replace("/\.penci-image-holder:not\(\[style\*='background-image'\]\){.*?}/", "", $completeContent);

          $completeContent = preg_replace("/ew-post-info/", "ew-p-in", $completeContent);
          $completeContent = preg_replace("/tabContent/", "tabCon", $completeContent);
          $completeContent = preg_replace("/elementor-align-icon-left/", "el-a-ic-l", $completeContent);
          $completeContent = preg_replace("/transition:background .3s,border-radius .3s,opacity .3s/", "trans:bg .3s,brdr-rad .3s, opa .3s", $completeContent);
          $completeContent = preg_replace("/border-width:2px 0px 2px 0px/", "border-width:2px 0px", $completeContent);
          $completeContent = preg_replace("/border-radius:3px 0px 3px 0px/", "border-width:3px 0px", $completeContent);
          $completeContent = preg_replace("/border-width:1px 0px 1px 0px/", "border-width:1px 0px", $completeContent);
          $completeContent = preg_replace("/padding:20px 0px 20px 0px/", "padding:20px 0px", $completeContent);
          if(preg_match('/\/wp-content\/themes\/connectSpos\/assets\/font\/connectspos\/\/wp-content\/themes\/connectSpos\/assets\/font\/connectspos\/fonts\//',$completeContent)){
          	$completeContent = preg_replace('/\/wp-content\/themes\/connectSpos\/assets\/font\/connectspos\/\/wp-content\/themes\/connectSpos\/assets\/font\/connectspos\/fonts\//','\/wp-content\/themes\/connectSpos\/assets\/font\/connectspos\/fonts\/',$completeContent);
          }
          if (function_exists('WP_RealEstate')) {
            if (preg_match('/<form action=(.*?) class="(.*?) filter-listing-form " method="GET">/', $completeContent)) {
              $completeContent = preg_replace('/<form action=(.*?) class="(.*?) filter-listing-form " method="GET">/', '<form action=$1 class="$2 filter-listing-form" target="_top" method="GET">', $completeContent);
            }
            if (preg_match('/<select class="select-field-region (.*?) (.*?) data-next="(.*?)" autocomplete="(.*?)" name="(.*?)" data-placeholder="Location" data-taxonomy="property_location">/', $completeContent)) {
              $completeContent = preg_replace('/<select class="select-field-region (.*?) (.*?) data-next="(.*?)" autocomplete="(.*?)" name="(.*?)" data-placeholder="Location" data-taxonomy="property_location">/', '<select class="select-field-region $1 $2 data-next="$3" name="$5" data-placeholder="Location" data-taxonomy="property_location">', $completeContent);
            }
          }

        // remove default transform css of jet elements animated box.
        if(class_exists('Jet_Elements')){
            $completeContent = preg_replace('/jet-animated-box__front{transform-style:preserve-3d;transform:rotateX\(0deg\)(.*?)}/m', 'jet-animated-box__front{transform-style:preserve-3d;$1}', $completeContent);
            $completeContent = preg_replace('/jet-animated-box__back{transform-style:preserve-3d;transform:rotateX\(-180deg\)(.*?)}/m', 'jet-animated-box__back{transform-style:preserve-3d;$1}', $completeContent);
        }
         $re = '/data-id="([a-z0-9]+)"/m';
         preg_match_all($re, $completeContent, $matches, PREG_SET_ORDER, 0);
         foreach ($matches as $key => $value) {
           $replacer = $value[1];
             $replacwith =  substr($value[1], -3);
             $completeContent = preg_replace("/ee".$replacer."/", "e".$replacwith, $completeContent);
         }
         foreach ($matches as $key => $value) {
           $replacer = $value[1];
             $replacwith =  substr($value[1], -4);
             $completeContent = preg_replace("/ee-".$replacer."/", "e".$replacwith, $completeContent);
         }

		//Code to remove unused CSS starts here
    	$swift_cntnWrp_remover = apply_filters("amp_pb_comp_swift_unused_remover", false);
    	if($swift_cntnWrp_remover==true){
	    	$completeContent = preg_replace("/\.cntn-wrp{\s*font-size\s*:\s*18px;\s*color\s*:\s*#000;line-height:\s*1\.7\s*;}/s", "", $completeContent);
	    }
	    //Code to remove unused CSS ends here
	    //Code to remove Header and Footer starts here
	    if(class_exists("\ElementorPro\Plugin")){
	    	
	    	if( pagebuilder_for_amp_utils::get_setting('elem-remove_header_footer' ) ) {
	    		$completeContent = preg_replace("/<header(.*?)class=\"header[-|0-9]* h_m h_m_1\">(.*?)<\/header>/s", "", $completeContent);
	    		$completeContent = preg_replace("/<footer(.*?)class=\"footer\">(.*?)<\/footer>/si", "", $completeContent);
	    	}
            $completeContent = preg_replace_callback('/<amp-img\slightbox=\"(.*?)\"(.*?)><amp-img\sfallback\slightbox=\"(.*?)\"(.*?)><\/amp-img><\/amp-img>/', function($m){
                $amp_img_lightbox = '<amp-img lightbox="'.$m[1].'"'.$m[2].'><amp-img fallback '.$m[4].'></amp-img></amp-img>';
                return $amp_img_lightbox;
            }, $completeContent);
            $completeContent = preg_replace_callback('/<amp-img fallback option=\"(.*?)\"(.*?)><\/amp-img>/', function($match){
                $amp_img_slideshow = '<amp-img fallback '.$match[2].'></amp-img>';
                return $amp_img_slideshow;
            }, $completeContent);
		}
        $completeContent = preg_replace('/<stream[^>]* src="(.*?)" controls poster="(.*?)"><\/stream>/', '<amp-iframe class="amp-stream-frame" width="175" height="100" sandbox="allow-scripts allow-same-origin" layout="responsive" allowfullscreen src="https://iframe.cloudflarestream.com/$1"></amp-iframe>', $completeContent);
        if(class_exists('Goog_Reviews_Widget')){   
             $pattern = '/<span\sclass="wp-more">(.*?)<\/span><span\sclass="wp-more-toggle">(.*?)<\/span>/';
             $count = 1;
             $completeContent = preg_replace_callback($pattern,
                                    function ($m) use (&$count) { 
                                        $content = '<span class="wp-more" hidden [hidden]="smrevg'.$count.'">'.$m[1].'</span><span class="wp-more-toggle" [hidden]="bmrevg'.$count.'" on="tap:AMP.setState({ smrevg'.$count.': false, bmrevg'.$count.':true  })" role="button" tabindex="0">'.$m[2].'</span>';
                                        ++$count;
                                        return $content; }, $completeContent);
            $completeContent = preg_replace('/<div\sclass="wp-google-review\swp-google-hide">/', '<div class="wp-google-review wp-google-hide" hidden [hidden]="vmrevg">', $completeContent);
            $completeContent = preg_replace('/<a\sclass="wp-google-url"\shref="#">(.*?)<\/a>/s', '<div class="wp-google-url" [hidden]="bvmrevg" on="tap:AMP.setState({ vmrevg: false, bvmrevg:true  })" role="button" tabindex="0">$1</div>', $completeContent);
        }
        if(preg_match('/<blockquote\sclass="twitter-tweet.*?><p(.*?)>(.*?)<\/p>(.*?)<a(.*?)<\/a><\/blockquote>/',$completeContent)){
            $pattern = '/<blockquote\sclass="twitter-tweet.*?><p(.*?)>(.*?)<\/p>(.*?)<a(.*?)<\/a><\/blockquote>/';
            $completeContent = preg_replace_callback(
            $pattern,
            function($m) {
                $matches = explode('/', $m[4]);
                $matches = explode('?', $matches[5]);
                $twitter_id = $matches[0];
            return '<amp-twitter  width="375" height="472" layout="responsive" data-tweetid="'.$twitter_id.'"></amp-twitter>';
                
            },
            $completeContent);
            return $completeContent;
       }
		$completeContent = apply_filters("amp_pc_elementor_css_sorting", $completeContent);
	    //Code to remove Header and Footer ends here
    	return $completeContent;
    }

	public function amp_elementor_custom_styles(){
		global $post;
		global $amp_elemetor_custom_css;
		if ( $post ){
			$postID = $post->ID;
	         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
	            $postID = ampforwp_get_frontpage_id();
	        }
	        $this->postID = $postID;
		}
         $t_builder_css = '';
        if ( class_exists('\ElementorPro\Plugin') ) {
            if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() || is_archive() || is_home() || is_search()) {
            $location = 'archive';
            } elseif ( is_singular() || is_404() ) {
                $location = 'single';
            }
            if(null !== ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( $location )){
            $location_documents = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( $location );
            $first_key = key( $location_documents );
            }
            $header_document = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( "header" );
            $header_id = $footer_id = '';
            if(isset($header_document) && !empty($header_document)){
            $header_id = key( $header_document );
            }
            $footer_document = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( "footer" );
            if(isset($footer_document) && !empty($footer_document)){
            $footer_id = key( $footer_document );
            }
            
            if(isset($location_documents) && !empty($location_documents)){
            $this->postID = $postID = $first_key;
            $t_builder_css = 'body .elementor-invisible{ visibility:visible;} 
                            .elementor .fa-whatsapp ,.elementor .fa-reddit ,.elementor .fa-linkedin ,.elementor .fa-pinterest ,.elementor .fa-twitter ,.elementor .fa-facebook{
                                font-family: "Font Awesome 5 Brands";
                            }
                            textarea#comment {
                                width: 100%;
                            }
                            @media(max-width:768px){
                            body .ele-'.$postID.' .ee.ew.ew-ig .eim amp-img{
                            width:auto;}
                            }
                            .elementor-'.$postID.' .elementor-shape svg{width: calc(100% + 1.3px);}
                            a:active, a:visited {color: unset;}
                           .elementor-'.$postID.' .elementor-share-btn__icon {width: 6.5em;height: 4.5em;}
                            body .elementor-'.$postID.'{line-height:1.5 }';
            if ( is_archive() || is_home() || is_search()) {
            $t_builder_css .= '.elementor-posts-container.elementor-has-item-ratio .elementor-post__thumbnail.elementor-fit-height amp-img {
                                height: 100%;
                                width: auto;
                                }
                                .elementor-posts-container .elementor-post__thumbnail 
                                amp-img{
                                height: 100%;
                                position: absolute;
                                top: calc(50% + 1px);
                                left: calc(50% + 1px);
                                -webkit-transform: scale(1.01) translate(-50%,-50%);
                                -ms-transform: scale(1.01) translate(-50%,-50%);
                                transform: scale(1.01) translate(-50%,-50%)}';
                 }
            }
        }
        $post_content = '';
        if(isset($this->postID)){
	        $content_post = get_post($this->postID);
	        $post_content = $content_post->post_content;
    	}
        if( class_exists('\Elementor\Plugin') && !( \Elementor\Plugin::$instance->db->is_built_with_elementor($postID) ) && !has_shortcode( $post_content, 'elementor-template' ) && !has_shortcode( $post_content, 'RH_ELEMENTOR' ) ){
            return ;
        }
        
		$allCss = '';       

		$srcs = array();
		$min_suffix =  '.min';
		$direction_suffix = is_rtl() ? '-rtl' : '';
		$frontend_file_name = 'frontend' . $direction_suffix . $min_suffix . '.css';
		$srcs[] = ELEMENTOR_ASSETS_URL . 'css/' . $frontend_file_name;
        //$srcs[] =  plugin_dir_url(__DIR__).'elementor/assets/lib/font-awesome/css/all.min.css';
        if(defined('WILCITY_EL_PREFIX')){
            $theme = wp_get_theme(); // gets the current theme
            if ( 'WilCity' == $theme->name || 'WilCity' == $theme->parent_theme ) {
                $srcs[] = get_template_directory_uri().'/assets/vendors/bootstrap/grid.css';
                $srcs[] = get_template_directory_uri().'/assets/production/css/app.min.css';
                $srcs[] = get_template_directory_uri().'/assets/fonts/line-awesome/line-awesome.css';
                $srcs[] =  $plugins_url.'/elementor/assets/css/frontend.min.css';
            }
        }
		if(is_plugin_active('amp-woocommerce-pro/amp-woocommerce.php')){
		$srcs[] =  plugin_dir_url(__DIR__).'amp-pagebuilder-compatibility/widgets/pro/styles.css';
		}
        $plugins_url = plugins_url();
        $ele_rev_col_css = $plugins_url.'/elementor/assets/css/frontend-legacy.min.css';
        $srcs[] = $ele_rev_col_css;
        if (is_plugin_active('raven/raven.php') ){
            $raven_css  = $plugins_url.'/raven/assets/css/frontend.min.css';
            $srcs[] = $raven_css;
        }
		if(function_exists('wp_upload_dir')){
			$uploadUrl = wp_upload_dir()['baseurl'];
            $uploads_dir = wp_upload_dir()['basedir'];
            if(file_exists($uploads_dir."/elementor/css/global.css")){
                $srcs[] = $uploadUrl."/elementor/css/global.css";
            }
            $srcs[] = $uploadUrl."/elementor/css/post-".get_the_ID().".css";
            /*$kit_id = get_option('elementor_active_kit');
            $el_kit_class = "";
            if ( $kit_id ) {
                $el_kit_id = $kit_id;
                $srcs[] = $uploadUrl."/elementor/css/post-". $el_kit_id.".css";
            }*/
            if ( class_exists('\ElementorPro\Plugin') ) {
                if(!empty($header_id)){
                $srcs[] = $uploadUrl."/elementor/css/post-". $header_id.".css";
                }
                if(!empty($footer_id)){
                $srcs[] = $uploadUrl."/elementor/css/post-". $footer_id.".css";
                }
            }
          //if(is_plugin_active('elementskit-lite/elementskit-lite.php')){
          	if(method_exists('\ElementsKit_Lite\Modules\Header_Footer\Activator', 'template_ids')){
    	        $elskit_temp_ids = \ElementsKit_Lite\Modules\Header_Footer\Activator::template_ids();
    	        if(is_array($elskit_temp_ids)){
    	          foreach ($elskit_temp_ids as $elskit_id) {
    	            if(!empty($elskit_id)){
    	              $srcs[] = $uploadUrl."/elementor/css/post-". $elskit_id.".css";
    	            }
    	          }
    	      	}
          // }
            $srcs[] = plugins_url()."/elementskit-lite/modules/controls/assets/css/ekiticons.css";
            $srcs[] = plugins_url()."/elementskit-lite/widgets/init/assets/css/widget-styles.css";
          }      
			if(class_exists('\ElementorPro\Plugin')){
			    $plugins_url = plugins_url();
				$srcs[] =  $plugins_url.'/elementor-pro/assets/css/frontend.min.css';

				$frontend_file_url = '';
				$frontend_file_name = 'frontend' . $direction_suffix . $min_suffix . '.css';
				if(method_exists('\Elementor\Core\Responsive\Responsive', 'has_custom_breakpoints')){
					$has_custom_file = \Elementor\Core\Responsive\Responsive::has_custom_breakpoints();
					if ( $has_custom_file ) {
						$frontend_file = new Elementor\Core\Responsive\Files\Frontend( 'custom-pro-' . $frontend_file_name, ELEMENTOR_PRO_ASSETS_PATH . 'css/templates/' . $frontend_file_name );
						if(is_object($frontend_file)){
							$time = $frontend_file->get_meta( 'time' );
							if ( ! $time ) {
								$frontend_file->update();
							}
							$frontend_file_url = $frontend_file->get_url();
						}
					}
				} elseif(defined(ELEMENTOR_PRO_ASSETS_URL)) {
					$frontend_file_url = ELEMENTOR_PRO_ASSETS_URL . 'css/' . $frontend_file_name;
				}
				if($frontend_file_url){
					$srcs[] = $frontend_file_url;
				}

		    }
		}
		
        if(class_exists('Jet_Elements')){
            $jet_elements_css = $plugins_url.'/jet-elements/assets/css/jet-elements-skin.css';
            $srcs[] = $jet_elements_css;
        }
        if (is_plugin_active('jet-blocks/jet-blocks.php') ){
            $jet_blocks_css  = $plugins_url.'/jet-blocks/assets/css/jet-blocks.css';
            $srcs[] = $jet_blocks_css;
        }
        if (is_plugin_active('elementskit/elementskit.php') ){
            $elementor_kit_css  = $plugins_url.'/elementskit/widgets/init/assets/css/style.css';
            $elementor_kit_icss =  $plugins_url.'/elementskit/modules/controls/assets/css/ekiticons.css';
            $srcs[] = $elementor_kit_css;
            $srcs[] = $elementor_kit_icss;
        }
        if(class_exists('Goog_Reviews_Widget')){   
            $srcs[] = $plugins_url.'/widget-google-reviews/static/css/google-review.css';
        }    
        if(defined('PREMIUM_ADDONS_BASENAME') && is_plugin_active('premium-addons-for-elementor/premium-addons-for-elementor.php')){
            $plugins_url = plugins_url();
            $elementor_premium_addon  = $plugins_url.'/premium-addons-for-elementor/assets/frontend/min-css/premium-addons.min.css';
            $srcs[] = $elementor_premium_addon;
        }
        
        if(function_exists('jet_theme_core') && jet_theme_core()){
        if (  jet_theme_core()->has_elementor() ) {
            $locationObj = jet_theme_core()->locations;
            $locations = $locationObj->get_locations();
            $current_post_id = get_the_ID();
            foreach ( $locations as $location => $structure ) {
                $template_id = jet_theme_core()->conditions->find_matched_conditions( $structure->get_id() );
                if ( $current_post_id !== $template_id ) {
                    $css_file = new Elementor\Core\Files\CSS\Post( $template_id );
                    $jet_theme_css = $css_file->get_post_id();
                    if(!empty($jet_theme_css))
                        $srcs[] = $uploadUrl."/elementor/css/post-".$jet_theme_css.".css";
                }
            }
        }
    }

		$srcs[] = ELEMENTOR_ASSETS_URL. '/lib/eicons/css/elementor-icons.css' ;
        $srcs[] = ELEMENTOR_ASSETS_URL. '/lib/eicons/css/elementor-icons.min.css' ;
		 //include rehub theme css for woocommerce grid module.
         $theme = wp_get_theme(); // gets the current theme
	           if ( 'Rehub theme' == $theme->name ) {
	           
	              if(is_child_theme()){
	                  $srcs[] = get_template_directory_uri() . '/style.css';
	              }
	                  $srcs[] = get_stylesheet_directory_uri() . '/style.css';
	                 
	           }
               if( 'TheSaaS' == $theme->name ){
                $thesaas_css = array();
                $thesaas_css[] = get_template_directory_uri().'/assets/css/page.min.css';
                $thesaas_css[] = get_template_directory_uri().'/assets/css/core.min.css';
                foreach ($thesaas_css as $key => $value) {
                    $urlValue = $value;
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
            }

            if ( 'Hello Elementor' == $theme->name ) {
            $srcs[] = get_template_directory_uri() . '/style.min.css';
        }
         if ($theme->name == 'Connect Spos') {
            $srcs[] = get_template_directory_uri() .'/assets/css/styles.css';
            $srcs[] = get_template_directory_uri() .'/assets/css/plugin/bootstrap.min.css';
            $srcs[] = get_template_directory_uri() .'/assets/font/connectspos/style.css';
        }
        if ( $theme->name == 'Neve' ) {
            $srcs[] = get_template_directory_uri() . '/style.min.css';
            $srcs[] = get_template_directory_uri() . '/assets/css/woocommerce.min.css';
        }
        $theme = wp_get_theme();        
        if ( $theme->name == 'Astra' || $theme->name === 'Astra Child' ) {
            $theme_uri = get_stylesheet_directory_uri();
            $parent = wp_get_theme()->parent();            
           if(isset($parent)){
            $srcs[] = get_template_directory_uri() . '/assets/css/minified/style.min.css';
            $srcs[] = get_template_directory_uri() . '/assets/css/minified/compatibility/woocommerce/woocommerce.min.css';
        }
        if(function_exists('wp_upload_dir')){
            $uploadUrl = wp_upload_dir()['baseurl'];
            $uploads_dir = wp_upload_dir()['basedir'];            
            $postID = $post->ID;
            $content_post = get_post($postID);
            $ampforwp_pb_build_astra_postID =  ampforwp_pb_build_astra_postID();
            $srcs[] = $uploadUrl."/elementor/css/post-". $ampforwp_pb_build_astra_postID.".css";
        }
    }

		//Supported plugin css
		$plugin_css = $this->supported_plugin_css();
		if($plugin_css){
			$srcs = array_merge($srcs, $plugin_css);
		}
		$update_css = pagebuilder_for_amp_utils::get_setting_data('elementorCssKeys');
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
		if(!empty(pagebuilder_for_amp_utils::get_setting_data('elementorCss-custom') ) ) {
            $allCss .= pagebuilder_for_amp_utils::get_setting_data('elementorCss-custom');
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
		$fontsList = get_option( '_elementor_global_css');
		$postFontsList = get_post_meta($postID, '_elementor_css');

        $status = $postFontsList[0]['status'];
        if($status && isset($postFontsList[0]['css'])){
            $inlineCss = $postFontsList[0]['css'];    
			$allCss .= $inlineCss;
        }
		$allCss .= $t_builder_css; // theme builde css
		$allCss = preg_replace("/\/\*(.*?)\*\//si", "", $allCss);
		 $allCss = str_replace(array(" img", "!important"), array(" amp-img", ""), $allCss);
         if ( in_array( 'content-egg/content-egg.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )   )  || wp_get_theme( get_template() )->get( 'Name' ) == 'Rehub theme' ) {
         $allCss .= '.wpsm_pros ul li span {top:1px;position: relative;}.wpsm_cons ul li span {top: 5px;position: relative;}.post ul li {list-style: disc outside none;}.wpsm_cons ul li:before {content: "\f056";color:#f24f4f;font: normal normal normal 14px/1 FontAwesome;font-size: 22px;}.artl-cnt ul li:before {display: inline-block;width: 0px;height: 0px;top: 9px;left: -23px;}.artl-cnt ul li {padding-left: 12px;list-style-type: disc;}';
        }
		$allCss .= 'header .cntr {max-width: 1100px;margin: 0 auto;width:100%;padding:0px 20px;}.elementor-testimonial-wrapper .elementor-testimonial-meta .elementor-testimonial-image amp-img { max-width: 60px; }amp-img{margin:0 auto;}
.footer{margin:0;}i.eicon-chevron-left {display: none;}i.eicon-chevron-right {display: none;}.ele-posts-container .ele-pst_thumbnail amp-img {max-width: 100%;}.cntr{max-width: 1100px;margin: 0 auto;width: 100%;padding: 0px 20px;} .elementor .carousel-preview amp-img{ height: 40px; width: 60px;position: relative;} .woocommerce .products li.product a.woocommerce-LoopProduct-link amp-img {margin: 0 auto;}@media(max-width:767px){.ele-background-video-container amp-youtube{ height:100vh;}}
.el-pcr h1 {font-weight: inherit;font-size: 40px;line-height: 1.2;}
 .el-pcr h2 {font-size: 30px;line-height: 1.3;font-weight: inherit;}
.el-pcr h3 {font-size: 25px;line-height: 1.4;font-weight: inherit;}
.el-pcr h4 {line-height: 1.5;font-size: 20px;font-weight: inherit;}
 .el-pcr h5 {line-height: 1.6;font-size: 18px;font-weight: inherit;}
.el-pcr h6 {line-height: 1.7;font-size: 15px;font-weight: inherit;}
.el-pcr p {margin-bottom: 1.75em;line-height: 1.85;
}.ele-shape svg { width: calc(100% + 1.3px);}
.woocommerce .elementor amp-img {max-width: 100%;}
.e-i-l-in{font-family: "Font Awesome 5 Free";}
prehidden {opacity: 1;visibility: visible;}
@media (max-width: 1024px){
    .rh-flex-center-align.mobileblockdisplay, .rh-flex-center-align.tabletblockdisplay { display: block; }
}.e-rs .carousel-img-media .swiper-slide {vertical-align: middle;}.e-rs .carousel-img-media .etml__content {white-space: normal;}
@media(max-width:500px){.e-rs .carousel-img-media .swiper-slide {margin: 0;}.e-rs .carousel-img-media .etml__content { overflow-y: scroll;height: 150px;}}a.lb.icon-src{color:#fff}.ea-post-crsl .eael-entry-content {white-space: normal;}.el-pcr .eael-grid-post-excerpt p{margin: 0;font-size: 14px;}.ea-post-crsl .swiper-slide{vertical-align: top;margin: 15px;}@media(min-width:1024px){.ea-post-crsl .swiper-slide {width: 30%;}}@media(min-width:600px) and (max-width:1024px){.ea-post-crsl .swiper-slide {width: 45%;}}.ea-post-crsl .eael-grid-post-holder-inner {text-align: center;white-space: normal;}.eael-post-grid .eael-entry-footer .eael-entry-meta {padding-left: 8px;text-align: left;}.eael-entry-footer .eael-author-avatar {width: 50px;}.eael-entry-footer>div {display: inline-block;float: left;}.eael-grid-post .eael-entry-wrapper, .eael-grid-post .eael-entry-footer {padding: 15px;}@media(min-width:500px){.ea-logo-crsl .swiper-slide {max-width: 250px;}}.fa.fab { font-family: "Font Awesome 5 Brands"; } i.fa { position: relative; right: 0; bottom: 0; } i.fa.fab,i.fas { color: inherit; background: transparent; font-weight: 900; } .el-s-b--skin-gradient .el-sh-b__text { padding: 0.9em; } .el-sh-b a { display: flex; } .el-s-b--skin-gradient .el-sh-b__text { padding: .9em; color: #fff; } .elementor-pst_excerpt p.amp_fcaet_glob { top: 0; }.wp-gr .wp-more,.wp-gr.wpac .wp-google-hide { display: block; } .wpac amp-img { display: inline-block; position: relative; }.ee table {width: 100%;}
    .es .econ {display: grid;}.es.es-bx > .econ {max-width: unset;}input.form-submit.jet-elements-s {
    padding: 0px;
    background-color: #d2d921;
}.ast-primary-header-bar { display: block; } .main-header-bar { background: none; padding: 1em 0; } .ast-header-button-1[data-section*="section-hb-button-"] .ast-builder-button-wrap .ast-custom-button { padding-top: 15px; padding-bottom: 15px; padding-left: 28px; padding-right: 28px; } .ast-primary-header .ast-custom-button { text-transform: capitalize; } .ast-header-button-1 .ast-custom-button { color: #ffffff; background: #44ca85; } [data-section*="section-hb-button-"] .menu-link { display: none; } @media (min-width: 768px){ #masthead { position: absolute; left: 0; right: 0; }} .site-header.header-main-layout-1 { position: absolute; left: 0; right: 0; } @media (min-width: 544px){ .ast-container { max-width: 100%; }} .ast-flex { -webkit-align-content: center; -ms-flex-line-pack: center; align-content: center; -webkit-box-align: center; -webkit-align-items: center; -moz-box-align: center; -ms-flex-align: center; align-items: center; } header .custom-logo-link amp-img { max-width: 180px; } .ast-builder-menu-1 { display: flex; } .ast-builder-menu-1 { font-family: inherit; font-weight: inherit; } .ast-builder-menu .main-header-menu,  .ast-builder-menu .main-header-menu .menu-link,  [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item > .menu-link,  .ast-masthead-custom-menu-items,  .ast-masthead-custom-menu-items a,  .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle,  .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle,  .ast-above-header-navigation a, .ast-header-break-point .ast-above-header-navigation a, .ast-header-break-point .ast-above-header-navigation > ul.ast-above-header-menu > .menu-item-has-children:not(.current-menu-item) > .ast-menu-toggle,  .ast-below-header-menu,  .ast-below-header-menu a, .ast-header-break-point .ast-below-header-menu a, .ast-header-break-point .ast-below-header-menu,  .main-header-menu .menu-link { color: #d0d8ea; } .main-header-menu .menu-link, .main-header-menu>a { margin: 0; } .ast-custom-button { line-height: 1; } .ee.es .econ { display: flex; } .ast-main-header-bar-alignment.toggle-on .main-header-bar-navigation { display: block; } @media (max-width: 768px){ .main-header-bar-navigation { width: 100%; margin: 0; } .ast-main-header-bar-alignment.toggle-on .main-header-bar-navigation { display: block; } .ast-builder-menu .main-header-menu,  .ast-builder-menu .main-header-menu .menu-link,  [CLASS*="ast-builder-menu-"] .main-header-menu .menu-item > .menu-link,  .ast-masthead-custom-menu-items,  .ast-masthead-custom-menu-items a,  .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle,  .ast-builder-menu .main-header-menu .menu-item > .ast-menu-toggle,  .ast-above-header-navigation a, .ast-header-break-point .ast-above-header-navigation a, .ast-header-break-point .ast-above-header-navigation > ul.ast-above-header-menu > .menu-item-has-children:not(.current-menu-item) > .ast-menu-toggle,  .ast-below-header-menu,  .ast-below-header-menu a, .ast-header-break-point .ast-below-header-menu a, .ast-header-break-point .ast-below-header-menu,  .main-header-menu .menu-link { color: #242a56; } } .ast-mobile-header-wrap .ast-mobile-header-content, .ast-desktop-header-content { background-color: #ffffff; } .main-navigation { display: block; width: 100%; } .ast-mobile-header-wrap .ast-flex.stack-on-mobile { flex-wrap: wrap; } .content-align-flex-start .main-header-menu { text-align: left; } .toggled .ast-menu-svg { display: none; } .toggled .ast-close-svg { display: block; } #ast-desktop-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"], #ast-mobile-header > [CLASS*="-header-wrap"]:nth-last-child(2) > [CLASS*="-header-bar"] { border-bottom-width: 0; border-bottom-style: solid; } @media (max-width: 768px){ #masthead .ast-mobile-header-wrap .ast-primary-header-bar, #masthead .ast-mobile-header-wrap .ast-below-header-bar { padding-left: 20px; padding-right: 20px; } .ast-primary-header-bar { display: grid; } } .ast-primary-header-bar { border-bottom-width: 0px; border-bottom-style: solid; } .ast-mobile-header-wrap .ast-primary-header-bar, .ast-primary-header-bar .site-primary-header-wrap { min-height: 70px; } .menu-toggle.main-header-menu-toggle { background: #44ca85; } #ast-mobile-header .ast-custom-button { display: none; } @media (min-width: 769px){ .ast-container { max-width: 1240px; }}';
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

	public function ampforwp_body_class_elementor($classes){
		if( class_exists('\Elementor\Plugin') ){
            $classes = \Elementor\Plugin::$instance->frontend->body_class($classes);
        }
        if( class_exists('\Elementor\Plugin') ){
            $ele_kit_id = \Elementor\Plugin::$instance->kits_manager->get_kit_for_frontend();
            if($ele_kit_id){
            	$classes[] = 'elementor-kit-' . $ele_kit_id->get_main_id();
        	}
        }
		return $classes;
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


	function elem_amp_fonts(){
		echo "<link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.12.1/css/all.css' type='text/css' media='all' />";
		$this->amp_fonts_elementor();
	}

	function amp_fonts_elementor(){
		global $post;
		$fontsList = get_option( '_elementor_global_css');
		$postFontsList = get_post_meta($post->ID, '_elementor_css');
		$listPostFonts = array();
    if(is_array($postFontsList) && !empty($postFontsList)){
  		foreach ($postFontsList as $key => $postFonts) {
  			$listPostFonts = array_merge( $listPostFonts, $postFonts['fonts']);
  		}
    }
		$fonts =  $listPostFonts;
		if($fontsList && isset($fontsList['fonts'])){
			$fonts = array_merge($fontsList['fonts'], $fonts);
		}
		$google_fonts = array();
		if($fonts){
			foreach ($fonts as $key => $font) {
				$font_type = \Elementor\Fonts::get_font_type( $font );
					switch ( $font_type ) {
						case \Elementor\Fonts::GOOGLE:
							$google_fonts['google'][] = $font;
							break;
						case \Elementor\Fonts::EARLYACCESS:
							$google_fonts['early'][] = $font;
							break;
						}
			}
		}
		$google_fonts_index = 1;
		if ( ! empty( $google_fonts['google'] ) ) {
				$google_fonts_index++;
				foreach ( $google_fonts['google'] as &$font ) {
					$font = str_replace( ' ', '+', $font ) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic';
				}
				$fonts_url = sprintf( 'https://fonts.googleapis.com/css?family=%s', implode( rawurlencode( '|' ), $google_fonts['google'] ) );
				$subsets = [
					'ru_RU' => 'cyrillic',
					'bg_BG' => 'cyrillic',
					'he_IL' => 'hebrew',
					'el' => 'greek',
					'vi' => 'vietnamese',
					'uk' => 'cyrillic',
					'cs_CZ' => 'latin-ext',
					'ro_RO' => 'latin-ext',
					'pl_PL' => 'latin-ext',
				];
				$locale = get_locale();
				if ( isset( $subsets[ $locale ] ) ) {
					$fonts_url .= '&subset=' . $subsets[ $locale ];
				}
				echo "<link rel='stylesheet' id='google-fonts'  href='$fonts_url' type='text/css' media='all' />";
			}
			if ( ! empty( $google_fonts['early'] ) ) {
				foreach ( $google_fonts['early'] as $current_font ) {
					$google_fonts_index++;
					
					$fonts_url = sprintf( 'https://fonts.googleapis.com/earlyaccess/%s.css', strtolower( str_replace( ' ', '', $current_font ) ) );
					
					echo "<link rel='stylesheet' id='google-fonts'  href='$fonts_url' type='text/css' media='all' />";
				}
			}
		
	}
	function supported_plugin_css(){
		$cssUrl = array();
		if(class_exists('Jet_Blog') && function_exists('jet_blog') ){
			$cssUrl[] = jet_blog()->plugin_url( 'assets/css/jet-blog.css' );
		}
		if(class_exists('Jet_Elements') && function_exists('jet_elements') ){
			$cssUrl[] = jet_elements()->plugin_url( 'assets/css/jet-elements.css' );
		}

		if(class_exists("\UltimateElementor\Classes\UAEL_Config")){
			$allCss = \UltimateElementor\Classes\UAEL_Config::get_widget_style();
			if($allCss){
				foreach ($allCss as $key => $css) {
					$cssUrl[] = UAEL_URL.$css['path'];
				}
			}
		}
		return $cssUrl;
	}
	function supported_plugin_compatible_css(){
		$css = '';
		if(class_exists('Jet_Blog') && function_exists('jet_blog') ){
			$css = '.jet-smart-listing__post{opacity:1;}';
		}
		if(class_exists("\UltimateElementor\Classes\UAEL_Config")){
			$css .= '.jet-smart-listing__featured{opacity:1;}';
		}
		return $css;
	}

	function ampforwp_pdf_embedder_compatibility(){
		if( ( function_exists('ampforwp_is_amp_endpoint')  && ampforwp_is_amp_endpoint() ) || ( function_exists('is_amp_endpoint')  && is_amp_endpoint() ) ) {
              remove_shortcode('pdf-embedder');
              add_shortcode('pdf-embedder',[$this, 'ampforwp_pdfemb_shortcode_display_pdf'] );
            if(class_exists('Social_Warfare')){
                add_action('amp_post_template_css',[$this,'amp_pc_social_warfare_icon_styles']);
                remove_shortcode('social_warfare');
                remove_shortcode('socialWarfare');
                add_shortcode('social_warfare',[$this,'amp_pc_buttons_shortcode']);
                add_shortcode('socialWarfare',[$this,'amp_pc_buttons_shortcode']);
            }
            remove_shortcode('massonary_workportfolio_post');
            add_shortcode('massonary_workportfolio_post',[$this,'amp_pc_massonary_workportfolio']);
            add_filter('theme_mod_penci_disable_lazyload_layout','amp_penci_disable_lazyload_layout');
        }
	}
    function amp_pc_massonary_workportfolio_styles(){
        ?>
        .workgallery .nav-item a{cursor: pointer;}
        .massonary-workportfolio .workgallery .nav-item a{ color: #a0b2bf; font-size: 24px; text-decoration: none; }
        .massonary-workportfolio .workgallery .nav-link { padding: 15px 15px; }
        .massonary-workportfolio .nav {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            padding-left: 0;
            margin-bottom: 0;
            list-style: none;
        }
        .massonary-workportfolio .text-uppercase { text-transform: uppercase!important; }
        .massonary-workportfolio .mb-4, .my-4 { margin-bottom: 1.5rem!important; }
        .massonary-workportfolio .justify-content-center { justify-content: center; }
        .massonary-workportfolio .hovereffect {
            width: 100%;
            height: 100%;
            float: left;
            overflow: hidden;
            position: relative;
            text-align: center;
            cursor: default;
        }
        .massonary-workportfolio .card-columns .card { margin-bottom: .75rem; }
        .massonary-workportfolio .tab-content .show{display:block;}
        .massonary-workportfolio .tab-content .hide{display:none;}
        
        @media (min-width: 992px){
            .massonary-workportfolio .mb-lg-5, .my-lg-5 { margin-bottom: 3rem; }
        }
        @media (min-width: 576px){
            .massonary-workportfolio .card-columns .card { display: inline-block; width: 100%; }
            .massonary-workportfolio .workgallery .card-columns .card { width: 100%; margin-bottom: -7px;}
            .massonary-workportfolio .card-columns{ column-count: 3; column-gap: 1.25rem; orphans: 1; widows: 1; }
        }
        @media (max-width: 768px){
            .massonary-workportfolio .workgallery .nav-item a{ font-size: 14px; }
        }
        <?php
    }
    function amp_pc_massonary_workportfolio(){
        add_action('amp_post_template_css',[$this,'amp_pc_massonary_workportfolio_styles']);
        $myvariable = "";
        ob_start();
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/massonary-portfolio-shortcode.php'; 
        $myvariable = ob_get_clean();
        return $myvariable;
    }

    function amp_pc_buttons_shortcode( $args ) {
        if( !is_array($args) ):
            $args = array();
        endif;
        $buttons_panel = new SWP_Buttons_Panel( $args, true );
        $social_warfare_icons = $buttons_panel->render_html();
        $social_warfare_icons = preg_replace('/<div(.*?)data-network="pinterest"><a(.*?)data-link="(.*?)">(.*?)<\/a><\/div>/', '<div$1data-network="pinterest"><a$2data-link="$3" href="$3" target="_blank">$4</a></div>', $social_warfare_icons);
        return $social_warfare_icons;
    }
	function ampforwp_pdfemb_shortcode_display_pdf($atts, $content=null) {
		$atts = apply_filters('pdfemb_filter_shortcode_attrs', $atts);

		if (!isset($atts['url'])) {
			return '<b>PDF Embedder requires a url attribute</b>';
		}
		$url = $atts['url'];
		add_filter('amp_post_template_data', array($this, 'amp_elementor_amp_google_document_embed_scripts'), 20);

		return '<amp-google-document-embed  src="'.$url.'" width="8.5"  height="11"
	      layout="responsive"></amp-google-document-embed>';
	}

	function amp_elementor_amp_google_document_embed_scripts($data){
		$data['amp_component_scripts']['amp-google-document-embed'] = 'https://cdn.ampproject.org/v0/amp-google-document-embed-0.1.js';
		return $data;
	}

	function ampforwp_blacklist_sanitizer($data){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/class-amp-divi-blacklist.php';
        unset($data['AMP_Blacklist_Sanitizer']);
        unset($data['AMPFORWP_Blacklist_Sanitizer']);
        $data[ 'AMPFORWP_DIVI_Blacklist' ] = array();
        return $data;
    }
    function recaptcha_validation( $record, $ajax_handler ){
    	$secretkey = pagebuilder_for_amp_utils::get_setting('elem-recaptcha-secretkey');
    	$fields = $record->get_field( [
			'type' => 'recaptcha',
		] );

		if ( empty( $fields ) ) {
			return;
		}

		$field = current( $fields );

		if ( empty( $_POST['g_recaptcha_response'] ) ) {
			$ajax_handler->add_error( $field['id'], __( 'The Captcha field cannot be blank. Please enter a value.', 'elementor-pro' ) );

			return;
		}

		$recaptcha_errors = [
			'missing-input-secret' => __( 'The secret parameter is missing.', 'elementor-pro' ),
			'invalid-input-secret' => __( 'The secret parameter is invalid or malformed.', 'elementor-pro' ),
			'missing-input-response' => __( 'The response parameter is missing.', 'elementor-pro' ),
			'invalid-input-response' => __( 'The response parameter is invalid or malformed.', 'elementor-pro' ),
		];

		$recaptcha_response = $_POST['g_recaptcha_response'];
		$recaptcha_secret = $secretkey;
		$client_ip = Utils::get_client_ip();

		$request = [
			'body' => [
				'secret' => $recaptcha_secret,
				'response' => $recaptcha_response,
				'remoteip' => $client_ip,
			],
		];

		$response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', $request );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== (int) $response_code ) {
			/* translators: %d: Response code. */
			$ajax_handler->add_error( $field['id'], sprintf( __( 'Can not connect to the reCAPTCHA server (%d).', 'elementor-pro' ), $response_code ) );

			return;
		}

		$body = wp_remote_retrieve_body( $response );

		$result = json_decode( $body, true );

		if ( ! $result['success'] ) {
			$message = __( 'Invalid Form.', 'elementor-pro' );

			$result_errors = array_flip( $result['error-codes'] );

			foreach ( $recaptcha_errors as $error_key => $error_desc ) {
				if ( isset( $result_errors[ $error_key ] ) ) {
					$message = $recaptcha_errors[ $error_key ];
					break;
				}
			}
			$ajax_handler->add_error( $field['id'], $message );
		}

		// If success - remove the field form list (don't send it in emails and etc )
		$record->remove_field( $field['id'] );
    }
	public function elementor_amp_cform_submission(){
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/amp-form-submit.php' );

	}

public function amp_elementor_ajax_comment(){
  global $redux_builder_amp;
  header("access-control-allow-credentials:true");
  header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
  header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
  $siteUrl = parse_url(  get_site_url() );
  header("AMP-Access-Control-Allow-Source-Origin:".$siteUrl['scheme'] . '://' . $siteUrl['host']);
  header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
  header("Content-Type:application/json;charset=utf-8");
  $comment = wp_handle_comment_submission( wp_unslash( $_POST ) );
  $text_data = 'Thank you for submitting comment, we will review it and will get back to you.';
  if ( is_wp_error( $comment ) ) {
    $error_data = intval( $comment->get_error_data() );
    if ( ! empty( $error_data ) ) {
      $comment_html = $comment->get_error_message();
      $comment_html = str_replace("&#8217;","'",$comment_html);
      $comment_html = str_replace(array('<strong>','</strong>'),array('',''),$comment_html);
      $comment_status = array('response' => $comment_html );
      header('HTTP/1.1 500 FORBIDDEN');
      echo json_encode($comment_status);
      die;
      // wp_die( '<p>' . $comment->get_error_message() . '</p>', __( 'Comment Submission Failure' ), array( 'response' => $error_data, 'back_link' => true ) );
    } else {
      wp_die( 'Unknown error' );
    }
  }

  $user = wp_get_current_user();
  do_action('set_comment_cookies', $comment, $user);
 
  $comment_depth = 1;
  $comment_parent = $comment->comment_parent;
  while( $comment_parent ){
    $comment_depth++;
    $parent_comment = get_comment( $comment_parent );
    $comment_parent = $parent_comment->comment_parent;
  }
 
  $GLOBALS['comment'] = $comment;
  $GLOBALS['comment_depth'] = $comment_depth;
  $comment_html = $text_data;
  $comment_status = array('response' => $comment_html );


  echo json_encode($comment_status);
  //echo $comment_html;
  die;
}

public function jet_elements_subscribe_optin_form_submission(){
        $_POST['EMAIL'] = $_POST['email'];
        $_POST['FNAME'] = $_POST['fname'];

        $memberId = md5(strtolower($_POST['EMAIL']));
        if(function_exists('jet_elements_settings')){
            $api_key = jet_elements_settings()->get( 'mailchimp-api-key' );
            $listId = jet_elements_settings()->get( 'mailchimp-list-id' );
        }
        
        $dataCenter = substr($api_key,strpos($api_key,'-')+1);
        $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

        $auth = base64_encode( 'user:' . $api_key );
        $json = array(
              'email_address' => $_POST['EMAIL'],
              'status'        => 'subscribed', // 
              'merge_fields'  => array(
                                  'FNAME'     => $_POST['FNAME'],
                                  //'LNAME'     => $_POST['LNAME'],
                                 ), 
        );
         
        $response = wp_remote_request( 
                                      $url, 
                                      array('headers' => array('Content-Type' => 'application/json','Authorization' => 'Basic '.$auth.''),
                                          'body'        => json_encode($json),
                                          'method'    => 'PUT',
                                    ));

          $response_code = wp_remote_retrieve_response_code($response);
          if ( is_wp_error( $response ) ) {
             wp_remote_retrieve_body( $response );
             echo json_encode(array('error_message'=>$response ));
          }else{

          // $redirect_check = get_post_meta($form_id, 'amp_optin_checkbox_redirect', true);
          // if($redirect_check == true){
          //    $redirect_url = get_post_meta( $form_id, 'ampforwp_optin_redirection', true );
          // } 
          // if( empty($redirect_url)){
          //   header('AMP-Access-Control-Allow-Source-Origin: '.$amp_optin_Site.'');
          //   // form was not working through cdn.
          //   header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
          //   header('Access-Control-Allow-Credentials: true');
          // }
          // else{
            header('AMP-Access-Control-Allow-Source-Origin: '.$amp_optin_Site.'');
            header("AMP-Redirect-To: ".esc_url($redirect_url));
            header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin"); 
          //}
            echo json_encode(array('successmsg'=>'E-mail '.$_POST['EMAIL'].' has been subscribed.'));
          die();
          }
}
	public function any_content_sanitizer($content){
		$sanitizer_obj = new AMPFORWP_Content( $content,
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
		$this->sanitizer_script = $sanitizer_obj->get_amp_scripts();
		$this->sanitizer_style = $sanitizer_obj->get_amp_styles();
		 return $sanitizer_obj->get_amp_content();
	}
}



/**
* Admin section portal Access
**/
add_action('plugins_loaded', 'pagebuilder_for_amp_elementor_option');
function pagebuilder_for_amp_elementor_option(){
	if(is_admin()){
		new pagebuilder_for_amp_elementor_Admin();
	}else{
		// Instantiate Elementor_For_Amp.
	}
  add_action( 'parse_query', 'set_pbc_custom_frontpage' );
  add_filter('redirect_canonical','redirect_pbc_custom_frontpage', 10, 2);
	new Elementor_For_Amp();
}
Class pagebuilder_for_amp_elementor_Admin{
	function __construct(){
		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_elementor'),7,1 );
	}
	public static function get_admin_options($section = array()){
		$obj = new self();
		//print_r($obj);die;
		$section = $obj->add_options_for_elementor($section);
		return $section;
	}
	function add_options_for_elementor($sections){
		$desc = '';
		if(!class_exists('\Elementor\Plugin')){
			$desc = 'Enable/Activate Elementor plugin';
		}
		$accordionArray = array();
		$sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
			if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
				foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
					if($fieldvalue['id'] == 'ampforwp-elementor-pb-for-amp-accor'){
                    	$accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                    	 unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-elementor-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
				}
				break;
			}
		}
		$sections[$sectionskey]['fields'][] = $accordionArray;
		$sections[$sectionskey]['fields'][] = array(
				               'id'       => 'pagebuilder-for-amp-elementor-support',
				               'type'     => 'switch',
				               'title'    => esc_html__('AMP Elementor Compatibility ','accelerated-mobile-pages'),
				               'tooltip-subtitle' => esc_html__('Enable or Disable the Elementor for AMP', 'accelerated-mobile-pages'),
				               'desc'	  => $desc,
				               'section_id' => 'amp-content-builder',
				               'default'  => false
				            );
		foreach ($this->amp_elementor_fields() as $key => $value) {
        	$sections[$sectionskey]['fields'][] = $value;
        }

		return $sections;

	}

	public function amp_elementor_fields(){
        $contents[] = array(
                        'id'       => 'elementorCssKeys',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter CSS URL', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your css url in comma separated', 'amp-pagebuilder-compatibility' ),
                       // 'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',

                    );
        $contents[] = array(
                        'id'       => 'elementorCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your Custom CSS code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'elem-recaptcha-sitekey',
                        'type'     => 'text',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__(' reCAPTCHA V3 site key', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Recaptcha V3 site key https://www.google.com/recaptcha/admin/create', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'elem-recaptcha-secretkey',
                        'type'     => 'text',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('reCAPTCHA V3 secret key', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Recaptcha V3 secret key https://www.google.com/recaptcha/admin/create', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );

        $contents[] = array(
                        'id'       => 'elem-themebuilder_header',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Elementor ThemeBuilder Header', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show Elementor themebuilder Header in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'elem-themebuilder_single',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Elementor ThemeBuilder Single', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show Elementor themebuilder single content in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'elem-themebuilder_footer',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Elementor ThemeBuilder Footer', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show Elementor themebuilder Footer in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'elem-remove_header_footer',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Remove Header & Footer', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Disbale if you want to remove default Elementor themebulider header & footer' ),
                         'section_id' => 'amp-content-builder',
                    );
        return $contents;
    }
}



//remove lazyload on woocommerce grid module of ruhub theme.
add_filter('rh_lazy_load', 'lazyLoad');
function lazyLoad($lazy = false){
$getAMPurl = filter_input(INPUT_SERVER, 'REQUEST_URI');

$AmpUrl  = explode('/', $getAMPurl);
  //print_r($test);die;


if(in_array('amp', $AmpUrl) || in_array('?amp', $AmpUrl)){
   
     $lazy = false;
}
 return $lazy;
}

add_action('pre_amp_render_post','amp_pb_rehub_pr_cs');
function amp_pb_rehub_pr_cs(){
    remove_filter('elementor/image_size/get_attachment_image_html', 'rh_el_add_lazy_load_images',10,4);
if ( in_array( 'content-egg/content-egg.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )   )  || wp_get_theme( get_template() )->get( 'Name' ) == 'Rehub theme' ) {
 add_action('amp_post_template_css','amp_pb_rehub_css',999);
}
}
function amp_pb_rehub_css(){
    echo '.wpsm_pros ul li:before {content: "\f058";font: normal normal normal 14px/1 FontAwesome;color: #58c649;font-size: 22px;font-weight: 900;left: -35px;
position: relative;top: 5px;}.wpsm_pros ul li span {top:1px;position:relative;}'; 
}

global $redux_builder_amp;
if( $redux_builder_amp['amp-design-selector'] == 3 ){
add_action('amp_post_template_css','custom_amp_css',999);
}

function custom_amp_css(){
    
echo 'header {
    padding-bottom: 100px;
}@media (max-width: 782px){
#wpadminbar~header #headerwrap {
    margin-top: 15px;
}}';

}

add_filter('amp_post_template_css','amp_astra_dynamic_css');
function amp_astra_dynamic_css(){
    $css = '';
    if(class_exists('Astra_Dynamic_CSS')){
        $css = Astra_Dynamic_CSS::return_output('');
    }
    echo $css;
}

if (class_exists( 'Astra_Ext_Advanced_Hooks_Markup' )) {
add_action('pre_amp_render_post', 'ampforwp_pb_build_astra_postID');
}
function ampforwp_pb_build_astra_postID(){
    global $post, $post_id, $result;
    $option = array(
                'location'  => 'ast-advanced-hook-location',
                'exclusion' => 'ast-advanced-hook-exclusion',
                'users'     => 'ast-advanced-hook-users',
            );
    if (class_exists( 'Astra_Ext_Advanced_Hooks_Markup' )) {
       $result = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( ASTRA_ADVANCED_HOOKS_POST_TYPE, $option );
}
    $header_counter     = 0;
    $footer_counter     = 0;
    $layout_404_counter = 0;

    foreach ( $result as $post_id => $post_data ) {
        $post_type = get_post_type();
        global $wp_query;
        return $post_id;
    }


    }

$theme = wp_get_theme(); // gets the current theme
if ( $theme->name == 'Astra'  || class_exists( 'Astra_Ext_Advanced_Hooks_Markup' ) ) {
add_filter('ampforwp_the_content_last_filter','ampforwp_carousel_builder_wooo',999);
}
function ampforwp_carousel_builder_wooo($cb){
$cb = preg_replace('/.woocommerce-products-header h1/', '.woocommerce-products-header_2 h1'  , $cb);
return $cb;
}

function set_pbc_custom_frontpage( $query ) {
  if( isset($query->query['amp']) && $query->query['amp'] == 1 && isset($query->is_home) && $query->is_home == 1 && function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() && empty($query->query_vars['page_id']) ){
      $query->query_vars['page_id'] = ampforwp_get_frontpage_id();
      $query->is_page = 1;
      $query->is_home = 0;
      $query->is_singular = 1;
  }
}

function redirect_pbc_custom_frontpage($redirect_url, $requested_url){
  global $wp;
  $current_url = user_trailingslashit(home_url( $wp->request ));
  if( isset($wp->query_vars['amp']) && $wp->query_vars['amp'] == 1 && $requested_url == $current_url ){
    $redirect_url = $current_url;
  }
  return $redirect_url;
}

function amp_penci_disable_lazyload_layout(){
  return true;
}

if(is_plugin_active('elementskit-lite/elementskit-lite.php')){
  if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_header')){
    add_action( 'ampforwp_after_header', "eleskit_header_amp" );
  }
  if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_footer')){
    add_action( 'amp_post_template_above_footer', "eleskit_footer_amp" );
  }
}
function eleskit_header_amp(){
  $template = \ElementsKit_Lite\Modules\Header_Footer\Activator::template_ids();
  $header_html = \ElementsKit_Lite\Utils::render_elementor_content($template[0]);
  if(class_exists('\AMPFORWP_Content')){
    $sanitizer_obj = new \AMPFORWP_Content( $header_html,
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
  $amp_sanitized_header_content = $sanitizer_obj->get_amp_content();
  global $redux_builder_amp;
  if(is_front_page() && function_exists('ampforwp_get_setting') && ampforwp_get_setting('ampforwp-auto-amp-menu-link') == 1){
    $amp_sanitized_header_content = preg_replace('/<a\s+href="(.*?)\/"(.*?)>(.*?)<\/a>/s', '<a href="'.ampforwp_url_controller('$1').'"$2>$3</a>', $amp_sanitized_header_content);
  }
  $amp_sanitized_header_content = preg_replace('/<i\saria-hidden="true"\sclass="icon\sicon-burger-menu"><\/i>/', '<i aria-hidden="true" class="icon icon-burger-menu" on="tap:AMP.setState({ hidehmenu: false })" role="button" tabindex=0></i>', $amp_sanitized_header_content);
  $amp_sanitized_header_content = preg_replace('/<div\sclass="ekit-sidebar-group\sinfo-group">/', '<div class="ekit-sidebar-group info-group ekit_isActive" hidden [hidden]="hidehmenu">', $amp_sanitized_header_content);
  $amp_sanitized_header_content = preg_replace('/<i\saria-hidden="true"\sclass="fas fa-times"><\/i>/', '<i aria-hidden="true" class="fas fa-times" on="tap:AMP.setState({ hidehmenu: true })" role="button" tabindex=0></i>', $amp_sanitized_header_content);
  $amp_query_variable = $redux_builder_amp['ampforwp-amp-takeover'] != 1 ? 'amp' : '';
  $amp_query_val = $redux_builder_amp['ampforwp-amp-takeover'] != 1 ? '1' : '';
  $srch_cont = '<div class="ekit-wid-con" hidden [hidden]="hideSearch"><div class="mfp-bg my-mfp-slide-bottom ekit-promo-popup mfp-ready"></div><div class="mfp-wrap mfp-auto-cursor my-mfp-slide-bottom ekit-promo-popup mfp-ready" style="overflow: hidden auto;"><div class="mfp-container mfp-s-ready mfp-inline-holder"><div class="mfp-content"><div class="zoom-anim-dialog ekit_modal-searchPanel"><div class="ekit-search-panel">
      <form role="search" method="get" class="ekit-search-group" action="'.esc_url( home_url( "/" ) ).'" target="_top">
          <input type="text" placeholder="AMP" value="'.$amp_query_val.'" name="'.$amp_query_variable.'" class="hidden"/>
          <input type="search" class="ekit_search-field" placeholder="Search..." value="'.get_search_query().'" name="s">
          <button type="submit" class="ekit_search-button">
              <i aria-hidden="true" class="icon icon-search"></i>                    </button>
      </form>
      </div></div></div></div></div>
      <button title="Close (Esc)" type="button" class="mfp-close" on="tap:AMP.setState({ hideSearch: true })">×</button>
      </div>';
  $amp_sanitized_header_content = preg_replace('/<i\saria-hidden="true"\sclass="icon\sicon-search"><\/i>/', '<i aria-hidden="true" class="icon icon-search" on="tap:AMP.setState({ hideSearch: false })" role="button" tabindex=0></i>', $amp_sanitized_header_content);
  $amp_sanitized_header_content = preg_replace('/<i\saria-hidden="true"\sclass="fas\sfa-search"><\/i>/', '<i aria-hidden="true" class="fas fa-search" on="tap:AMP.setState({ hideSearch: false })" role="button" tabindex=0></i>', $amp_sanitized_header_content);
  $amp_sanitized_header_content .=  $srch_cont;
  echo $amp_sanitized_header_content;
}

function eleskit_footer_amp(){
  global $elementskit_template_ids;
  $template = \ElementsKit_Lite\Modules\Header_Footer\Activator::template_ids();
  $footer_html = \ElementsKit_Lite\Utils::render_elementor_content($template[1]);
  if(class_exists('\AMPFORWP_Content')){
    $sanitizer_obj = new \AMPFORWP_Content( $footer_html,
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
  $amp_sanitized_footer_content = $sanitizer_obj->get_amp_content();
  echo $amp_sanitized_footer_content;
}

function amp_recaptcha_resolver(){
   	global $wp_filter;
   	foreach ($wp_filter as $handlekey => $handlevalue) {
   		if($handlekey=='elementor_pro/forms/validation'){
   			foreach ($handlevalue->callbacks as $prioritykey => $priorityvalue) {
   				foreach ($priorityvalue as $callbackkey => $callbackvalue) {
   					if($callbackvalue['function'][0] instanceof \ElementorPro\Modules\Forms\Classes\Recaptcha_Handler){
   						unset($wp_filter['elementor_pro/forms/validation']->callbacks[$prioritykey][$callbackkey]);
   					}
   				}
   			}
   		}
   }
}
$theme = wp_get_theme(); // gets the current theme
if ( $theme->name == "Hello Elementor Child"){  
    //Elementor theme comp.
    add_filter('hello_elementor_register_elementor_locations','amp_hello_elementor_comp');
    function amp_hello_elementor_comp(){
        if( ( function_exists('ampforwp_is_amp_endpoint')  && ampforwp_is_amp_endpoint() ) || ( function_exists('is_amp_endpoint')  && is_amp_endpoint() ) ) {
            $hook_result = false;
        }
        return $hook_result;

    }
}