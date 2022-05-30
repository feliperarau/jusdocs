<?php

//Fusion(Avada) builder
global $amp_avada_custom_css;
class AMP_PC_Avada_Pagebuidler
{
    public $fusionBuilderObj;
    public function __construct()
    {
        $this->load_dependencies();
        //$this->define_public_hooks();
    }

    public function load_dependencies(){
        if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-avada-support') ){
            //$this->fusionBuilderObj = FusionBuilder();
            add_filter('amp_post_template_css', [$this,'amp_divi_custom_styles'],7);
            add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_avada'],11);
            add_action('pre_amp_render_post', array($this, 'load_fusion_builder_shortcodes'), 999);//fusion_builder_shortcodes_init
            add_action('amp_post_template_head', array($this, 'ampforwp_booking_rel_avada'));
            
           require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
        }
    }
  
    public static function classesReplacements($completeContent){
        $completeContent = preg_replace("/var\(--(.*?)\)/", "", $completeContent);
        $completeContent = preg_replace("/:root{[^{}]*}/", "", $completeContent);
        
        $completeContent = preg_replace("/fusion-clearfix/", "fclfx", $completeContent);
        $completeContent = preg_replace("/fusion-layout-column/", "flco", $completeContent);
        $completeContent = preg_replace("/fusion_builder_column/", "fblco", $completeContent);
        
        $completeContent = preg_replace("/fusion-column-inner-bg-image/", "fucibimg", $completeContent);
        $completeContent = preg_replace("/fusion-column-inner-bg/", "fucoibg", $completeContent);
        $completeContent = preg_replace("/fusion-/", "fu-", $completeContent);
        $completeContent = preg_replace("/--fu-audio-(.*?);/", "    ", $completeContent);
        $completeContent = preg_replace("/fu-builder-column/", "fublcol", $completeContent);
        $completeContent = preg_replace("/content-box-column/", "conbxcol", $completeContent);
        $completeContent = preg_replace("/content-box-heading/", "con-bx-hg", $completeContent);
        $completeContent = preg_replace("/content-wrapper/", "con-wrp", $completeContent);
        $completeContent = preg_replace("/fu-content-boxes/", "fcnbx", $completeContent);
        $completeContent = preg_replace("/fu-li-item-content/", "fu-li-it-con", $completeContent);
        $completeContent = preg_replace("/content-boxes/", "con-bxs", $completeContent);
        $completeContent = preg_replace("/nonhundred-percent-fullwidth/", "nonhnd-pnt-flwd", $completeContent);
        $completeContent = preg_replace("/non-hundred-percent-height-scrolling/", "non-hnd-pnt-hgt-scrl", $completeContent);
        $completeContent = preg_replace("/fu-builder-row/", "fublr", $completeContent);
        //$completeContent = preg_replace("/reading-box/", "rd-bx", $completeContent);
        $completeContent = preg_replace("/fu-fullwidth/", "fu-flwd", $completeContent);
        $completeContent = preg_replace("/fu-reading-box-container/", "fu-rd-bx-con", $completeContent);
        $completeContent = preg_replace("/fu-google/", "fu-ggl", $completeContent);
        $completeContent = preg_replace("/fullwidth-box/", "fwdbx", $completeContent);
        $completeContent = preg_replace("/fu-button-text/", "fubt", $completeContent);
        $completeContent = preg_replace("/fu-carousel-nav/", "fucrn", $completeContent);
        $completeContent = preg_replace("/fu-image-wrapper/", "fuimwr", $completeContent);
        $completeContent = preg_replace("/fu-product-buttons/", "fuprb", $completeContent);
        $completeContent = preg_replace("/fu-carousel-title-below-image/", "fuctbim", $completeContent);
        //$completeContent = preg_replace("/reading-box/", "rd-bx", $completeContent);
        $completeContent = preg_replace("/fu-title/", "fu-t", $completeContent);
        $completeContent = preg_replace("/fu-one/", "fu-1", $completeContent);
        $completeContent = preg_replace("/title-heading/", "tl-hdg", $completeContent);
        $completeContent = preg_replace("/fu-page-title/", "fu-pg-tl", $completeContent);
        $completeContent = preg_replace("/fu-content-box-hover/", "fu-c-b-h", $completeContent);
        $completeContent = preg_replace("/icon-wrapper-hover-animation-pulsate/", "i-w-h-an", $completeContent);
        $completeContent = preg_replace("/fu-highlighted/", "fu-hl", $completeContent);
        $completeContent = preg_replace("/content-box-wrapper/", "c-bx-w", $completeContent);
        //$completeContent = preg_replace("/fontawesome-icon/", "ftawe-ic", $completeContent);
        $completeContent = preg_replace("/fu-button/", "fu-btn", $completeContent);
        //$completeContent = preg_replace("/fu-checklist/", "fu-ckli", $completeContent);
        $completeContent = preg_replace("/fu-column-wrapper/", "fucowr", $completeContent);
        $completeContent = preg_replace("/fu-rollover/", "furol", $completeContent);
        $completeContent = preg_replace("/fu-carousel/", "fucarl", $completeContent);
        $completeContent = preg_replace("/amp-wp-inline/", "amwpin", $completeContent);
        $completeContent = preg_replace('/.modal{display:none;/m', '.modal{display:block;' , $completeContent);
        $completeContent = preg_replace("/fu-columns/", "fu-cols", $completeContent);
        $completeContent = preg_replace("/fu-column/", "fu-col", $completeContent);
        $completeContent = preg_replace('/.fade{opacity:0;/m', '.fade{opacity:1;' , $completeContent);
        $completeContent = preg_replace('/fontawesome-icon/', 'fa-i' , $completeContent);        
        $completeContent = preg_replace('/fu-checklist/', 'fu-cl' , $completeContent);      
        $completeContent = preg_replace('/-o-background-size:cover;/', '' , $completeContent);      
        $completeContent = preg_replace('/-moz-background-size:cover;/', '' , $completeContent);      
        $completeContent = preg_replace('/-webkit-background-size:cover;/', '' , $completeContent);
        $completeContent = preg_replace("/fu-post-content/", "fu-pc" , $completeContent);  
        $completeContent = preg_replace("/fu-post-wrapper/", "fu-pw" , $completeContent);  
        $completeContent = preg_replace("/fu-blog-layout-grid/", "fu-blg" , $completeContent);  
        $completeContent = preg_replace("/fu-post-grid/", "fu-pgr" , $completeContent);  
        $completeContent = preg_replace("/furol-content/", "frolct" , $completeContent);  
        $completeContent = preg_replace("/furol-categories/", "fcgr" , $completeContent);
        $completeContent = preg_replace("/fu-testimonials/", "futml" , $completeContent);
        $completeContent = preg_replace("/fu-content-sep/", "fcsp" , $completeContent);
        $completeContent = preg_replace("/furol-gallery/", "frlglr" , $completeContent);
        $completeContent = preg_replace("/fu-blog-equal-heights/", "f-e-ht" , $completeContent);
        $completeContent = preg_replace("/avada-image-rollover/", "av-irl" , $completeContent);
        $completeContent = preg_replace("/flexslider/", "flx" , $completeContent);
        $completeContent = preg_replace("/fu-col-content-centered/", "fcnt" , $completeContent);
        $completeContent = preg_replace("/post-content/", "p-c" , $completeContent);
        $completeContent = preg_replace("/fu-meta-info/", "fmi" , $completeContent);
        $completeContent = preg_replace("/fu-post-slideshow/", "fpsl" , $completeContent);
        $completeContent = preg_replace("/fu-post-slideshow/", "fpsl" , $completeContent);
        $completeContent = preg_replace("/fu-post-con-wrp/", "fpcw" , $completeContent);
        $completeContent = preg_replace("/fu-blog-shortcode/", "fbsc" , $completeContent);
        $completeContent = preg_replace("/fu-read-more/", "frm" , $completeContent);
        //$completeContent = preg_replace("/slides/", "sl" , $completeContent);
        $completeContent = preg_replace("/furol-link/", "flk" , $completeContent);
        $completeContent = preg_replace("/fu-single-line-meta/", "fslm" , $completeContent);
        $completeContent = preg_replace("/heading-with-icon/", "hwi" , $completeContent);
        $completeContent = preg_replace("/con-bxs-icon-on-side/", "cbions" , $completeContent);
        $completeContent = preg_replace("/fu-image-hovers/", "fihv" , $completeContent);
        $completeContent = preg_replace("/hover-type-zoomin/", "htz" , $completeContent);
        $completeContent = preg_replace("/fu-alignleft/", "fal" , $completeContent);
        $completeContent = preg_replace("/fu-alignright/", "far" , $completeContent);
        $completeContent = preg_replace("/fu-aligncenter/", "fac" , $completeContent);
        $completeContent = preg_replace("/fusion-search-results/", "fsrs" , $completeContent);
        $completeContent = preg_replace("/fusion-search-result/", "fsr" , $completeContent);
        $completeContent = preg_replace("/fusion-main-menu-search-overlay/", "fmms" , $completeContent);
        $completeContent = preg_replace("/button-default/", "b-d" , $completeContent);
        $completeContent = preg_replace("/fu-section-separator/", "fusp" , $completeContent);
        $completeContent = preg_replace("/fu-waves-opacity-candy/", "fwoc" , $completeContent);
        $completeContent = preg_replace("/fu-pc-container/", "fupct" , $completeContent); 
        $completeContent = preg_replace("/pagination-next/", "fpn" , $completeContent);
        $completeContent = preg_replace("/fu-blog-pagination/", "fbg" , $completeContent);
        $completeContent = preg_replace("/hundred-percent-fullwidth/", "hpf" , $completeContent);
        $completeContent = preg_replace("/menu-item-has-children/", "mih" , $completeContent);
        $completeContent = preg_replace("/amp-menu/", "a-m" , $completeContent);
        $completeContent = preg_replace("/m-menu/", "m-m" , $completeContent);
        $completeContent = preg_replace("/av-irl-circle-yes/", "aicy" , $completeContent);
        $completeContent = preg_replace("/fu-show-pagination-text/", "fspt" , $completeContent);
        $completeContent = preg_replace("/fu-separator/", "fsp" , $completeContent);
        $completeContent = preg_replace("/sep-double/", "s-d" , $completeContent);
        $completeContent = preg_replace("/fu-body/", "fub" , $completeContent);
        $completeContent = preg_replace("/avada-header-top-bg-not-opaque/", "ahtno" , $completeContent);
        $completeContent = preg_replace("/avada-has-header-bg-full/", "ahbf" , $completeContent);
        $completeContent = preg_replace("/.fu-parallax-fixed{-webkit-backface-visibility:hidden;backface-visibility:hidden}/", "" , $completeContent);
        $completeContent = preg_replace("/padding:0px 0px 0px 0px/", "padding:0px" , $completeContent);
        $completeContent = preg_replace("/@media only screen and \(/", "@media(" , $completeContent);
        $completeContent = preg_replace("/.fwdbx{background-attachment:scroll}/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-box-sizing:border-box;/", "" , $completeContent);
        $completeContent = preg_replace("/-moz-box-sizing:border-box;/", "" , $completeContent);
        $completeContent = preg_replace("/-ms-box-sizing:border-box/", "" , $completeContent);
        $completeContent = preg_replace("/-o-box-sizing:border-box/", "" , $completeContent);
        $completeContent = preg_replace("/-ms-flex-positive:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-ms-flex-direction:column;/", "" , $completeContent);
        $completeContent = preg_replace("/display:-ms-(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-ms-flex-wrap:(wrap|nowrap);/", "" , $completeContent);
        $completeContent = preg_replace("/-ms-flex-pack:(center|end|distribute);/", "" , $completeContent);
       // css optimize
        $completeContent = preg_replace("/fu-pricing-table/", "fu-p-t" , $completeContent);
        $completeContent = preg_replace("/fu-social-networks/", "fu-so-n" , $completeContent);
        $completeContent = preg_replace("/sep-boxed-pricing/", "se-b-pr" , $completeContent);
        $completeContent = preg_replace("/panel-body/", "p-by" , $completeContent);
        $completeContent = preg_replace("/con-bxs-timeline-vertical/", "c-b-ti-v" , $completeContent);
        $completeContent = preg_replace("/circle-yes/", "ce-y" , $completeContent);
        $completeContent = preg_replace("/fu-filter/", "fu-fi" , $completeContent);
        $completeContent = preg_replace("/fu-toggle-no-divider/", "fu-t-n-dr" , $completeContent);
        $completeContent = preg_replace("/fu-toggle-boxed-mode/", "fu-t-b-mo" , $completeContent);
        $completeContent = preg_replace("/screen-reader-text/", "sc-re-t" , $completeContent);
        $completeContent = preg_replace("/-webkit-clip-path:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-font-smoothing:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-text-size-adjust:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-box-shadow:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-animation-duration:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/-webkit-transition-duration:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/reading-box/", "rea-b" , $completeContent);
        $completeContent = preg_replace("/fu-social-network-icon/", "fu-s-ne-i" , $completeContent);
        $completeContent = preg_replace("/modal-dialog/", "mo-di" , $completeContent);
        $completeContent = preg_replace("/fu-accordian/", "fu-acn" , $completeContent);
        $completeContent = preg_replace("/panel-title/", "pa-ti" , $completeContent);
        $completeContent = preg_replace("/fu-modal/", "fu-mol" , $completeContent); 
        $completeContent = preg_replace("/boxed-icons/", "bo-ics" , $completeContent);
        $completeContent = preg_replace("/modal-header/", "mo-hea" , $completeContent);
        $completeContent = preg_replace("/content-container/", "co-cont" , $completeContent);
        $completeContent = preg_replace("/fu-panel/", "fu-pl" , $completeContent);
        $completeContent = preg_replace("/fu-faq-shortcode/", "fu-f-sh" , $completeContent);
        $completeContent = preg_replace("/-moz-border-radius:(.*?);/", "" , $completeContent);
        $completeContent = preg_replace("/modal-content/", "ml-cot" , $completeContent);
        $completeContent = preg_replace("/title-sep/", "ti-sep" , $completeContent);
        $completeContent = preg_replace("/fu-has-button-gradient/", "fu-h-b-gr" , $completeContent);
        $completeContent = preg_replace("/con-bxs-icon-with-title/", "c-b-i-w-t" , $completeContent);
        $completeContent = preg_replace("/fu-two-fifth/", "fu-t-fi" , $completeContent);
        $completeContent = preg_replace("/fu-li-item/", "fu-l-i" , $completeContent);
        $completeContent = preg_replace("/con-bxs-icon-on-top/", "c-bs-in-n-to" , $completeContent);
        $completeContent = preg_replace("/content-icon-wrapper-yes/", "co-ic-wr-ys" , $completeContent);
        $completeContent = preg_replace("/modal-title/", "ml-tt" , $completeContent);
        $completeContent = preg_replace("/fu-toggle-heading/", "fu-te-heg" , $completeContent);
        $completeContent = preg_replace("/fu-imageframe/", "fu-imgf" , $completeContent);
        $completeContent = preg_replace("/fu-two-third/", "fu-to-td" , $completeContent);
        $completeContent = preg_replace('/background-image:url\("([\w\:\/\d\.\/\-]+\.jpg|png|gif|jpeg)"\);background-image:linear-gradient\(([\d\w\,\(\)\.\#\s\%\-]+)\);/', 'background-image: linear-gradient($2),url($1);', $completeContent);

        $completeContent = preg_replace('/<span\sclass="display-counter"\sdata-value="(.*?)"(.*?)>(.*?)<\/span>/','<span class="display-counter" data-value="$1"$2>$1</span>',$completeContent);
        $completeContent = preg_replace("/\.fu-col,\.fu-col:nth-child(.*?){margin-right:0};/", ".fu-col{margin-right:0}" , $completeContent);
        $completeContent = preg_replace('/<amp-script>/','<amp-script src="'.AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR_URI.'assets/amp-script_fusion.js">' , $completeContent);
        $completeContent = apply_filters("amp_pc_avada_css_sorting", $completeContent);
        return $completeContent;
    }
    function dirScan($avcssdir, $fullpath = false){
        $avcsspath = Avada::$template_dir_url . '/assets/css/media';
        $ignore = array(".","..");
        if (isset($avcssdir) && is_readable($avcssdir)){
            $dlist = array();
            $avcssdir = realpath($avcssdir);
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($avcssdir,RecursiveDirectoryIterator::KEY_AS_PATHNAME),RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
            foreach($objects as $entry){    
                    if(!in_array(basename($entry), $ignore)){
                        if (!$fullpath){
                            $entry = str_replace($avcssdir, '', $entry);
                        }           
                            $dlist[] = $avcsspath.$entry;
                    }                        
            }      
            return $dlist;
        }
    }
    function amp_divi_custom_styles(){
        global $amp_avada_custom_css;
        $postId = get_the_ID();
        if( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ){
            $postId = ampforwp_get_frontpage_id();
        }
        if ( 'active' !== get_post_meta( $postId, 'fusion_builder_status', true ) ) {
            return false;
        }
        $css = '';
        $allCss = '';
        $css .= apply_filters( 'fusion_dynamic_css', '' );

        $dynamic_css_obj = Fusion_Dynamic_CSS::get_instance();
        $mode = ( method_exists( $dynamic_css_obj, 'get_mode' ) ) ? $dynamic_css_obj->get_mode() : $dynamic_css_obj->mode;

        $min_version = ( true == FUSION_BUILDER_DEV_MODE ) ? '' : '.min';
        $avcssdir = get_stylesheet_directory() . '/assets/css/media';
        
        //$srcs = $this->dirScan($avcssdir);
        $srcs[] = Avada::$template_dir_url . '/assets/css/style.min.css';
        if ( is_rtl() && 'file' !== $this->compiler_mode ) {
			$srcs[] = Avada::$template_dir_url . '/assets/css/rtl.min.css';
        }
        if(class_exists('FusionSC_Portfolio')){
          $srcs[] = plugins_url().'/fusion-core/css/style.min.css'; 
        }
        if ( 'file' === $mode) {
            $Fusion_Dynamic_CSS_FileObj = new Fusion_Dynamic_CSS_File( $dynamic_css_obj );
            //$srcs[] = $Fusion_Dynamic_CSS_FileObj->file( 'uri' );
		}else{
            $css = $dynamic_css_obj->make_css();
        }

        $update_css = pagebuilder_for_amp_utils::get_setting_data('avadaCssKeys');
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

        //print_r($srcs);
        foreach ($srcs as $key => $urlValue) {
            $cssData = $this->ampforwp_remote_content($urlValue);
            $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
			$css .= preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($urlValue){
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
        if(!empty(pagebuilder_for_amp_utils::get_setting_data('avadaCss-custom') ) ) {
            $css .= pagebuilder_for_amp_utils::get_setting_data('avadaCss-custom');
        }
        //Customizer css
        if( function_exists('wp_get_custom_css') ){
			$css .= wp_get_custom_css();
        }
        $css = str_replace(array(" img", " video", "!important"), array(" amp-img", " amp-video", ""), $css);
        $allCss = $css .'
        body .hundred-percent-fullwidth .fu-row{
            max-width: none;
        }
		.fusion-image-hovers .hover-type-zoomin{
              overflow: visible;
         }
       .fusion-image-hovers .hover-type-zoomin:hover .fusion-column-inner-bg-image{
			transform: none;
       }
       fusion-column-inner-bg-image amp-img{
           width:100%;
           height:100%;
       }
       .modal-dialog {
        margin-top: 220px;
       }
       .modal{overflow:hidden;}
       .modal-dialog .toggle-alert {
        display: none;
       }
       .fu-flip-box .flip-box-back{ font-size:14px; }
       .fu-gallery .fu-gallery-column{display:block;}
       @media only screen and (max-width: 768px) {
            .fu-blog-archive .fusion-blog-layout-grid .fusion-post-grid{width:100%;}
            .fusion-post-grid .blog-shortcode-post-title{ font-size: 18px;}
            .fusion-blog-layout-grid .fusion-post-grid { padding: 10px;}
        }
        @media only screen and (min-width: 1025px){
            .fusion-no-large-visibility{
                display:none;
            }
        }
        @media only screen and (min-width: 640px) and (max-width: 1024px){
            .fusion-no-medium-visibility{
                display:none;
            }
        }
        @media only screen and (max-width: 640px){
            .fusion-no-small-visibility{
                display:none;
            }
        }
        .cntn-wrp .fu-faqs-wrapper {display:block;}
        .cntn-wrp .fu-portfolio-con-wrp{opacity:1;}
        @media only screen and (max-width: 712px){
            .fu-portfolio-three .fu-portfolio-post {
                width: 100%;
            }
        }
        .fcnbx.c-b-ti-v .conbxcol .icon {opacity: 1;}
        html:not(.avada-html-layout-boxed):not(.avada-html-layout-framed), html:not(.avada-html-layout-boxed):not(.avada-html-layout-framed) body {background-color: unset;}
        .fu-timeline-arrow, .fu-timeline-icon { color: #ebeaea; } .fu-icon-bubbles:before { content: "\e62a"; } .fu-blog-layout-timeline .fu-timeline-date, body { font-size: 16px; } @media (max-device-width: 640px){ .fu-blog-layout-timeline .fu-post-timeline { float: none; width: 100%; }} .fu-blog-layout-timeline .fu-timeline-arrow, .fu-blog-layout-timeline .fu-timeline-date, .fu-blog-layout-timeline .fu-timeline-line, .fu-blog-layout-timeline .post, .fu-blog-layout-timeline .post .flx { border-color: #ebeaea; } .fu-flx.fpsl .slides { float: none; } .fu-flx.fu-flx-loading .slides>li:first-child { display: block; opacity: 1; } .fu-blog-layout-timeline .fmi .fal { width: 50%; display: inline-block; margin: 0; } .fu-blog-layout-timeline .fmi .far { width: 50%; display: inline-block; text-align: right; margin: 0; } .far { font-family: "Font Awesome 5 Free"; font-weight: 400; } .fal, .far { -moz-osx-font-smoothing: grayscale; display: inline-block; font-style: normal; font-variant: normal; text-rendering: auto; line-height: 1; } .fbsc a { text-decoration: none; box-shadow: none; } [class*=" fu-icon-"], [class^=fu-icon-] { font-family: icomoon; speak: never; font-style: normal; font-weight: 400; font-variant: normal; text-transform: none; line-height: 1; -moz-osx-font-smoothing: grayscale; } @media (max-device-width: 640px){ .pagination { margin-top: 40px; }} @media (max-width: 800px){ .pagination { margin-top: 40px; }} .clearfix { clear: both; } .pagination, .pagination .fpn { font-size: 12px; } .fbg .pagination .current { background-color: #a0ce4e; } .fbg .pagination .current, .fbg .pagination a.inactive:hover { border-color: #a0ce4e; } .fbg .pagination .current { color: #fff; margin: 0 4px; } .avada-has-pagination-padding .pagination .current, .avada-has-pagination-padding .pagination .fpn, .avada-has-pagination-padding .pagination a.inactive { padding: 2px 6px 2px 6px; } .pagination .current, .pagination .fpn, .pagination a.inactive { border-radius: 0px; border-width: 1px; } body:not(.fu-hide-pagination-text) .pagination .fpn { line-height: 30px; } .fbg .pagination .fpn { padding: 0; position: relative; } .fbg .pagination .fpn:after { font-family: icomoon; content: "\f105"; } .fbg .pagination .fpn:after, .furol a, .pagination .fpn:after { color: #341bf7; } .fusp .fusp-svg { position: absolute; left: 0; right: 0; } .fusp .rounded-split.bottom::after, .fusp .rounded-split.bottom::before { content: ""; position: absolute; pointer-events: none; top: 0; left: 0; z-index: 10; width: 50%; height: 71px; background: inherit; border-radius: 0 80px 0 0; } .fusp .rounded-split.bottom::before { left: 0; border-radius: 0 80px 0 0; } .fusp .rounded-split.bottom::after { left: 50%; border-radius: 80px 0 0 0; } .fu-flwd { position: relative; } .fu-flwd .fu-row { position: relative; z-index: 10; } @media (max-width: 800px){ .fub .flco:not(.fu-flex-column) { width: 100%; }} @media (max-width: 800px){ .flco:not(.fu-flex-column) { margin-left: 0; margin-right: 0; width: 100%; }} .flco.fu-1-half { width: 50%; } .flco .fucowr { min-height: 1px; } @media (max-device-width: 640px){ .fu-timeline-arrow, .fu-timeline-circle, .fu-timeline-icon, .fu-timeline-line { display: none; }} .fuimwr .furol .frolct .fcgr, .fuimwr .furol .frolct .fcgr a, .fmi, .fslm { font-size: 12px; } .flco { position: relative; float: left; margin-bottom: 20px; } body:not(.avada-has-pagination-padding) .fusion-pagination .current, body:not(.avada-has-pagination-padding) .fusion-pagination .page-numbers:not(.prev):not(.next), body:not(.avada-has-pagination-padding) .page-links a, body:not(.avada-has-pagination-padding) .page-links>.page-number:not(.prev):not(.next), body:not(.avada-has-pagination-padding) .pagination .current, body:not(.avada-has-pagination-padding) .pagination .pagination-next, body:not(.avada-has-pagination-padding) .pagination a.inactive, body:not(.avada-has-pagination-padding).fusion-hide-pagination-text .fusion-pagination .next, body:not(.avada-has-pagination-padding).fusion-hide-pagination-text .fusion-pagination .prev, body:not(.avada-has-pagination-padding).fusion-hide-pagination-text .pagination .pagination-next, body:not(.avada-has-pagination-padding).fusion-hide-pagination-text .pagination .pagination-prev { width: 30px; height: 30px; margin-left: calc((30px)/ 10); margin-right: calc((30px)/ 10); } #nav ul li ul li a, #sticky-nav ul li ul li a, #wrapper #nav ul li ul li > a, #wrapper #sticky-nav ul li ul li > a, .avada-container h3, .comment-form input[type="submit"], .ei-title h3, .fusion-blog-shortcode .fusion-timeline-date, .fusion-body #main .tribe-common .tribe-events-c-day-marker__date, .fusion-body #main .tribe-events .datepicker, .fusion-body .fusion-wrapper #main .tribe-common .tribe-common-h6--min-medium, .fusion-body .tribe-common .tribe-common-b2, .fusion-body .tribe-common .tribe-common-b3, .fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-categories, .fusion-image-wrapper .fusion-rollover .fusion-rollover-content .fusion-rollover-title, .fusion-image-wrapper .fusion-rollover .fusion-rollover-content .price, .fusion-image-wrapper .fusion-rollover .fusion-rollover-content a, .fusion-load-more-button, .fusion-main-menu .sub-menu, .fusion-main-menu .sub-menu li a, .fusion-megamenu-widgets-container, .fusion-megamenu-wrapper .fusion-megamenu-submenu > a:hover, .fusion-megamenu-wrapper li .fusion-megamenu-title-disabled, .fusion-page-title-bar h3, .gform_page_footer input[type=button], .meta .fusion-date, .more, .post-content blockquote, .review blockquote div strong, .review blockquote q, .ticket-selector-submit-btn[type=submit], body { font-family: "Open Sans"; font-weight: 400; letter-spacing: 0px; font-style: normal; }';
        if(is_array($amp_avada_custom_css)){
            foreach ($amp_avada_custom_css as $key => $cssArray) {
                if(is_array($cssArray)){
                    foreach ($cssArray as $key => $css) {
                        $allCss .= $css;
                    }
                }else{
                    $allCss .= $cssArray;
                }
                
            }
        }
        
        $custom_css = '';
        if(class_exists('Fusion_Dynamic_CSS')){
            $dynamic_css         = Fusion_Dynamic_CSS::get_instance();
            $dynamic_css_helpers = $dynamic_css->get_helpers();
            for ( $i = 0; $i < 7; $i++ ) {
                if ( 0 === $i ) {
                    $selector           = '.block-editor .editor-styles-wrapper';
                    $typography_setting = 'body_typography';
                } else {
                    $selector           = '.pg h' . $i;
                    $typography_setting = 'h' . $i . '_typography';
                }

                $custom_css .= $selector . '{
                    font-family: ' . $dynamic_css_helpers->combined_font_family( Avada()->settings->get( $typography_setting ) ) . ';
                    font-weight: ' . intval( Avada()->settings->get( $typography_setting, 'font-weight' ) ) . ';
                    letter-spacing: ' . Fusion_Sanitize::size( Avada()->settings->get( $typography_setting, 'letter-spacing' ), 'px' ) . ';
                    font-style: ' . Avada()->settings->get( $typography_setting, 'font-style' ) . ';
                    line-height: ' . Fusion_Sanitize::size( Avada()->settings->get( $typography_setting, 'line-height' ) ) . ';
                    font-size: ' . Fusion_Sanitize::size( Avada()->settings->get( $typography_setting, 'font-size' ) ) . ';
                    color: ' . Fusion_Sanitize::color( Avada()->settings->get( $typography_setting, 'color' ) ) . ';
                }';
            }
        }
        echo $allCss.$custom_css;
    }

    //if ( Avada()->settings->get( 'status_fontawesome' ) ) {
		// 	if ( 'off' === Avada()->settings->get( 'css_cache_method' ) ) {
		// 		wp_enqueue_style( 'fontawesome', FUSION_LIBRARY_URL . '/assets/fonts/fontawesome/font-awesome.min.css', array(), self::$version );
		// 	}

		// 	wp_enqueue_style( 'avada-IE-fontawesome', FUSION_LIBRARY_URL . '/assets/fonts/fontawesome/font-awesome.min.css', array(), self::$version );
		// 	wp_style_add_data( 'avada-IE-fontawesome', 'conditional', 'lte IE 9' );
		// }

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

    public function ampforwp_body_class_avada($classes){
        $classes = apply_filters( 'body_class', $classes);
        $classes = FusionBuilder()->body_class_filter($classes);
        return $classes;
    }
    function load_fusion_builder_shortcodes(){
        $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH),'/' );
	  	$explode_path = explode('/', $url_path);
        if ( in_array('amp', $explode_path) || function_exists('ampforwp_is_amp_endpoint') && ampforwp_is_amp_endpoint() ) {
            if ( class_exists( 'FusionBuilder' ) ) {
                $filesArray = array('fusion-image-carousel.php', 'fusion-column.php', 'fusion-google-map.php', 'fusion-woo-product-slider.php','fusion-button.php','fusion-modal.php', 'fusion-content-boxes.php','fusion-title.php','fusion-tagline.php','fusion-checklist.php','fusion-tabs.php','fusion-toggle.php','fusion-text.php','fusion-progress.php','fusion-container.php','fusion-separator.php','fusion-testimonials.php','fusion-gallery.php','fusion-image-before-after.php','fusion-portfolio.php','fusion-flip-boxes.php');
                foreach ($filesArray as $key => $value) {
                    if(file_exists(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/avada/".$value)){
                        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/avada/".$value;
                    }
                }
            }
        }
    }

    function ampforwp_booking_rel_avada(){
    echo ' <meta name="amp-script-src" content="sha384-fake_hash_of_remote_js sha384-fake_hash_of_local_script">';
}

    /*function tester(){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/assets/amp-script_fusion.js';
    }*/

    function tester5(){
    $test = new FusionSC_Tabs();
    remove_filter( 'fusion_attr_tabs-shortcode-link', [ $test, 'link_attr' ] );
}

    public static function load_ajax_calls(){

    }
}

/**
* Admin section portal Access
**/
//add_action('plugins_loaded', 'pagebuilder_for_amp_avada_option');
pagebuilder_for_amp_avada_option();
function pagebuilder_for_amp_avada_option(){
    if(is_admin()){
        new pagebuilder_for_amp_avada_Admin();
    }else{
        // Instantiate AMP_PC_Avada_Pagebuidler.
        $diviAmpBuilder = new AMP_PC_Avada_Pagebuidler();
    }
    if ( defined( 'DOING_AJAX' ) ) {
        AMP_PC_Avada_Pagebuidler::load_ajax_calls();
    }
    
}
Class pagebuilder_for_amp_avada_Admin{
    function __construct(){
        add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_avada'),7,1 );
    }
    public static function get_admin_options_avada($section = array()){
        $obj = new self();
        $section = $obj->add_options_for_avada($section);
        return $section;
    }
    function add_options_for_avada($sections){
        $desc = 'Enable/Activate Avada pagebuilder';
        $theme = wp_get_theme(); // gets the current theme
        if ( class_exists( 'FusionBuilder' ) ) {
            $desc = '';
        }
       // print_r( $sections[3]['fields']);die;
        $accordionArray = array();
        $sectionskey = 0;
        foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
                foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
                    if($fieldvalue['id'] == 'ampforwp-avada-pb-for-amp-accor'){
                        $accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                         unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-avada-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                }
                break;
            }
        }
        $sections[$sectionskey]['fields'][] = $accordionArray;
        $sections[$sectionskey]['fields'][] = array(
                               'id'       => 'pagebuilder-for-amp-avada-support',
                               'type'     => 'switch',
                               'title'    => esc_html__('AMP Avada (Fusion builder) (BETA)','accelerated-mobile-pages'),
                               'tooltip-subtitle' => esc_html__('Enable or Disable the avada for AMP', 'accelerated-mobile-pages'),
                               'desc'     => $desc,
                               'section_id' => 'amp-content-builder',
                               'default'  => false
                            );
        if( function_exists('amp_activate') ){
            foreach (pagebuilder_for_amp_avada_Admin::amp_divi_fields() as $key => $value) {
                $sections[$sectionskey]['fields'][] = $value;
            }
        }else{
            foreach ($this->amp_divi_fields() as $key => $value) {
                $sections[$sectionskey]['fields'][] = $value;
            }
        }
        

        return $sections;

    }

    public function amp_divi_fields(){
        $contents[] = array(
                        'id'       => 'avadaCssKeys',
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
                        'id'       => 'avadaCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );

        /*$contents[] = array(
                        'id'       => 'avada_fontawesome_support',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Load fontawesome', 'amp-pagebuilder-compatibility'),
                        'desc'      => esc_html__( 'Load fontawesome library from CDN', 'amp-pagebuilder-compatibility' ),
                        'default'  => 0,
                        //'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',
                    );*/
        return $contents;
    }
}

//blog pagination.
add_action( 'init', 'amp_avada_blog_blog_pagination_rewrite_rules', 25 );
function amp_avada_blog_blog_pagination_rewrite_rules(){
    global $redux_builder_amp, $wp_rewrite;
    add_rewrite_rule(
        '(.?.+?)/amp/page/?([0-9]{1,})/?$',
        'index.php?amp=1&paged=$matches[2]&pagename=$matches[1]',
        'top'
    );
}

add_filter('ampforwp_modify_the_content','amp_fusion_slider');
function amp_fusion_slider($content){

    $chck_pass = preg_match('/<div\sclass="tfs-slider\sflexslider\smain-flex\s.*?".*?>/', $content);

    if( $chck_pass == 1 ){

    $dom = new DomDocument();
    if(function_exists('mb_convert_encoding')){
        $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    }else{
        $dom->loadHTML( $content );
    }
    $finder = new DomXPath($dom);
    $elements = $finder->query('//li[@data-loop="yes"]');

    $wrapper = $dom->createElement('amp-carousel');
    $wrapper->setAttribute('height','400px');
    $wrapper->setAttribute('layout','fixed-height');
    $wrapper->setAttribute('type','slides');
    $elements->item(0)->parentNode->insertBefore(
        $wrapper, $elements->item(0)
    );

    foreach($elements as $child) {
        $wrapper->appendChild($child);
    }

    $content = $dom->savehtml();

    }

    return $content;

}