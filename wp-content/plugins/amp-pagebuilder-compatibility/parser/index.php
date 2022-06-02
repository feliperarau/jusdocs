<?php
add_filter('ampforwp_the_content_last_filter','ampforwp_purify_amphtmls'); 
function ampforwp_purify_amphtmls($completeContent){
	global $post;
	if( pagebuilder_for_amp_utils::get_setting_data('debug-mode-pb-for-amp') ){
		return $completeContent;

	}
	if ( file_exists( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/parser/autoload.php' ) ) {
		require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/parser/autoload.php';
	}
	
	$white_lists = amp_pbc_white_list_selectors($completeContent);

    if(is_object($post)){
	$postID = $post->ID;
	}
	if (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
		$postID = ampforwp_get_frontpage_id();
	}
	//require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/autoload.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/class-amp-rule-spec.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/class-amp-dom-utils.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/class-amp-allowed-tags-generated.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/AMP_Base_Sanitizer.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/class-amp-style-sanitizer.php";
	require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/parser/parser-helper-function.php";
	/***Replacements***/
	$completeContent = preg_replace("/wpb_animate_when_almost_visible/", "", $completeContent);
	$completeContent = amp_pb_replacement($completeContent);
	$completeContent = str_replace(array('sizes="(min-width: 1000px) 1000px, 100vw"'), array('sizes="(min-width: 1000px) 100vw, 100vw"'), $completeContent);
	
	if(preg_match('/<amp-img(.?)layout="(.*?)"(.*)layout=[\'|"](.*?)[\'|"](.*?)/',$completeContent )){

	 $completeContent = preg_replace('/<amp-img(.?)layout="(.*?)"(.*)layout=[\'|"](.*?)[\'|"](.*?)/', '<amp-img$1$3layout="$4"', $completeContent);
     }
	$match = preg_match('/<amp-video(.*?)width=\"(.*?)\"(.*?)>/', $completeContent);
	if(!$match){
		$completeContent = preg_replace('/<amp-video/', '<amp-video width="720" layout="responsive"', $completeContent);
	}
	$completeContent = preg_replace("/<amp-video(.*?)layout=\"fixed-height\"(.*?)>/", "<amp-video$1 $2>", $completeContent);

	$update_class = amp_pb_coontainerClass();
	$vc_enabled = true;//get_post_meta($postID, '_wpb_vc_js_status');
	if($vc_enabled){
		$completeContent = preg_replace('/<div class="pg">/si', '<div class="pg'.$update_class.'">', $completeContent);
	}
	if( !function_exists('avia_lang_setup') && !class_exists('Jet_Elements') ) {
	//Remove breadcrumb
		$completeContent = preg_replace("/class=\"(.*?)animate(.*?)\"/", 'class="$1 $2"', $completeContent);
	}
	//removefooterCss
	$completeContent = preg_replace("/\.footer{(\s|)margin-top(\s|):(\s|)80px(;|)}/", "", $completeContent);
	$completeContent = preg_replace("/\.left{(\s|)float(\s|):(\s|)left(;|)}/", "", $completeContent);
	//for fonts
	$completeContent = str_replace(array('"\\', "'\\"), array('":backSlash:',"':backSlash:"), $completeContent);
	/***Replacements***/
	if(!empty($completeContent)){
		$tmpDoc = new DOMDocument();
		libxml_use_internal_errors(true);
		$tmpDoc->loadHTML($completeContent);
		//return json_encode(AMP_PB_Style_Sanitizer::has_required_php_css_parser());
		if(AMP_PB_Style_Sanitizer::has_required_php_css_parser()){ 

			$sheet = '';

			$arg['allow_dirty_styles'] = false;
			$obj = new AMP_PB_Style_Sanitizer($tmpDoc, $arg);
			$datatrack = $obj->sanitize();
			//return json_encode($datatrack);

			$data = $obj->get_stylesheets();
			foreach($data as $styles){
				$sheet .= $styles;
			}
			$sheet = stripcslashes($sheet);
			if(strpos($sheet, '-keyframes')!==false){
				$sheet = preg_replace("/@(-o-|-moz-|-webkit-|-ms-)*keyframes\s(.*?){([0-9%a-zA-Z,\s.]*{(.*?)})*[\s\n]*}/s", "", $sheet);
			}
			$sheet = apply_filters("amp_pc_add_custom_css", $sheet);
			$sheet = amp_pb_replacement($sheet);
			$sheet .= $white_lists;
			$completeContent = preg_replace("/<style\samp-custom>(.*)<\/style>/s", "<style amp-custom>".$sheet."</style>", $completeContent);
		}
	}
	//for fonts
	$completeContent = str_replace(array('":backSlash:', "':backSlash:"), array('"\\', "'\\"), $completeContent);
		
	return $completeContent;
}



function amp_pb_replacement($completeContent){
	global $post;
	$postID ='';
	if ( $post ){
		$postID = $post->ID;
         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }
    }
     if ( class_exists('\ElementorPro\Plugin') ) {
            if ( is_archive() || is_home()) {
            $location = 'archive';
            } elseif ( is_singular() || is_404() ) {
                $location = 'single';
            }
            $location_documents = ElementorPro\Modules\ThemeBuilder\Module::instance()->get_conditions_manager()->get_documents_for_location( $location );

            $first_key = key( $location_documents );
            if(isset($location_documents) && !empty($location_documents)){
             $postID = $first_key;
            }
        }


    global $vc_manager;
    if($vc_manager instanceof Vc_Manager && get_post_meta( $postID , '_wpb_vc_js_status', true ) ){
    	$completeContent = AmpWpbakeryPro::classesReplacements($completeContent);
    }
		$theme = wp_get_theme(); // gets the current theme
		$template_id = "";
	if(function_exists('amp_et_theme_builder_get_template_layouts')){
		$layouts = amp_et_theme_builder_get_template_layouts();
        $template_id = isset($layouts['et_body_layout']['id'])? $layouts['et_body_layout']['id']: '';
	}
	if ( (is_plugin_active( 'divi-builder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme) && 'on' === get_post_meta( $postID, '_et_pb_use_builder', true ) || 'et_body_layout' === get_post_type( $template_id )) {
		$completeContent = AMP_PC_Divi_Pagebuidler::classesReplacements($completeContent);

	}elseif(class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($postID) ){
		$completeContent = Elementor_For_Amp::classesReplacements($completeContent);

	}elseif( 'active' === get_post_meta( $postID, 'fusion_builder_status', true ) ){
		if(class_exists('AMP_PC_Avada_Pagebuidler')){
			$completeContent = AMP_PC_Avada_Pagebuidler::classesReplacements($completeContent);
		}
	}
	elseif( 'active' === get_post_meta( $postID, '_aviaLayoutBuilder_active', true ) ){
		$completeContent = AMP_PC_Avia_Pagebuidler::classesReplacements($completeContent);
	}
	elseif(  is_plugin_active('oxygen/functions.php') || function_exists('oxygen_vsb_register_condition')  ) {
		$completeContent = Oxygen_For_Amp::classesReplacements($completeContent);
	}
	elseif(class_exists('Brizy_Editor_Post') ||  function_exists('brizy_load') && Brizy_Editor_Post::get( $postID )->uses_editor() ){
		$completeContent = Brizy_For_Amp::classesReplacements($completeContent);
	}
	elseif (function_exists('flatsome_setup') ){ 
		$completeContent = UX_builder_For_Amp::classesReplacements($completeContent);
	}
	elseif (is_plugin_active('beaver-builder-lite-version/fl-builder.php') || is_plugin_active('bb-plugin/fl-builder.php')) {
		$completeContent = Beaver_For_Amp::classesReplacements($completeContent);
	}

	elseif( 'Betheme' == $theme->name || 'Betheme Child' == $theme ) {
		$completeContent = Muffin_For_Amp::classesReplacements($completeContent);
	}
	
	return $completeContent;
}

function amp_pb_coontainerClass(){
	global $post;
	$postID ='';
	if ( $post ){
		$postID = $post->ID;
         if ( function_exists('ampforwp_is_front_page') &&  ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }
    }
	$class="";
		$theme = wp_get_theme(); // gets the current theme
	if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ) {
		$class='" id="page-container';
	}else if ( class_exists("\Elementor\Plugin") ) {
		$class=" elementor elementor-".$postID;
	}elseif ( 'active' == get_post_meta( $postID, '_aviaLayoutBuilder_active', true ) ) {
		$class='" id="top';
	}
	return $class;
}

function amp_pbc_white_list_selectors($completeContent){
    $white_list = array();
    $white_list[] = '.hide';
    $white_list[] = 'amp-img.agi img';
    $white_list[] = '.toggled .ast-menu-svg';
    $white_list[] = '.toggled .ast-close-svg';
    $white_list[] = '.ast-main-header-bar-alignment.toggle-on .main-header-bar-navigation';
    $white_list = (array)apply_filters('ampforwp_tree_shaking_white_list_selector',$white_list);
    $w_l_str = '';
    for($i=0;$i<count($white_list);$i++){
        $f = $white_list[$i];
        preg_match_all('/'.$f.'[\s|\n]{(.*?)}/s', $completeContent, $matches);
        if(isset($matches[0][0])){
            $w_l_str .= $matches[0][0];
        }
    }
    return $w_l_str;
}