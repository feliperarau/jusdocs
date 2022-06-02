<?php 
class pagebuilder_for_amp_utils{
	public static function get_setting( $opt_name='', $child_option='', $sanitize_method='' ){
		/*global $redux_builder_amp;
		if(empty($redux_builder_amp)){
			$redux_builder_amp = get_option('redux_builder_amp');
		}
		$opt_value = '';
		if ( isset($redux_builder_amp[$opt_name]) ) {
			$opt_value = $redux_builder_amp[$opt_name];
			if ( '' !== $child_option && isset($redux_builder_amp[$opt_name][$child_option]) ){
				$opt_value = $redux_builder_amp[$opt_name][$child_option];
			}
		}
		if ( '' !== $sanitize_method ){
			return $sanitize_method($opt_value);
		}*/
		$opt_value = self::get_setting_data( $opt_name );
		return $opt_value;
	}	
	
	public static function admin_extra_settings(){
		require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/admin-settings.php';
	}

	public function init(){
		add_action('amp_post_template_file', array($this, 'load_pagebuilder_templates'), 21, 3);
		if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-beaver-support')){
			add_action('amp_post_template_file', array($this, 'amp_pbc_load_beaver_template'), 21, 3);
		}
		add_action( 'wp_ajax_amp_pbc_clear_css_transient', array($this, 'amp_pbc_clear_css_transient') );
		add_action( 'redux/options/redux_builder_amp/saved', array($this, 'set_global_change_title_cooment'),10,2);
		add_action( 'save_post', array($this, 'amp_pbc_clear_css_transient_post') );


		//AMP Settings
		add_action('wp',array($this,'automattic_buffer'));
		add_action('admin_menu', array($this, 'menu_for_amp_pb') );
		add_action( 'wp_head', array($this,'amp_pbc_rel_canonical' ) );
		//for template mode Remove from hide Array/ Added in show array
		add_filter('amp_template_mode_hide_opt_array', array($this, 'amp_support_templatemode_remove'));
		add_filter('amp_template_mode_show_opt_array', array($this, 'amp_support_templatemode_add'));
	}

	function amp_support_templatemode_add($array){
		$array[] = 'amp-content-builder';
		return $array;
	}

	function amp_support_templatemode_remove($array){
		$key = array_search('amp-content-builder', $array);
		unset($array[$key]);
		return $array;
	}

	function automattic_buffer(){
		if(function_exists('amp_activate') && function_exists('is_amp_endpoint') && is_amp_endpoint()){
			add_action('wp', function(){ ob_start(array($this, 'ampforwp_the_content_filter_full')); }, 999);
		}
	}
	function ampforwp_the_content_filter_full($content_buffer){
		$content_buffer = apply_filters('ampforwp_the_content_last_filter', $content_buffer);
		return $content_buffer;
	}

	function set_global_change_title_cooment($options, $changed_values){
		if( !current_user_can( 'manage_options' ) ){
			return false;
		}
		$remove_amppbc_data = false;
		global $wpdb;
		if( isset($changed_values['single-show-title-pb-for-amp']) ){
			$remove_amppbc_data = true; 
		}
		if( isset($changed_values['single-show-comments-pb-for-amp']) ){
			$remove_amppbc_data = true;
		}
		if($remove_amppbc_data){
			$deletePostmetaQuery = $wpdb->prepare("DELETE FROM `{$wpdb->prefix}postmeta` WHERE `meta_key` LIKE '%s'", '%' . $wpdb->esc_like('amp_pb_compatibility') . '%');
			$wpdb->query($deletePostmetaQuery);
		}
		if($changed_values){
			$this->removeCssFiles();
		}
	}

	public function load_pagebuilder_templates( $file, $type, $post ){
		global $redux_builder_amp;
		$theme = wp_get_theme(); // gets the current theme
		if ( is_singular() || is_home() || is_archive() || (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) ) {
			if( 'single' === $type || $type== 'page' ) {
				$postId = (is_object($post)? $post->ID: '');
				$check_post = get_post_type();
				if( function_exists('ampforwp_is_front_page') &&ampforwp_is_front_page() ){
					$postId = ampforwp_get_frontpage_id();
				}
				if ( (function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE )) || 'on' === get_post_meta( $postId, '_et_pb_use_builder', true ) && !is_search() ) {
					$layout = get_post_meta( $postId, '_et_pb_page_layout', true );
					if( $layout=='et_full_width_page' ){						
						if(function_exists('amp_activate')){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template.php';
						}
					}
					if( is_page() || is_home() || ampforwp_is_front_page() ){
						$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template.php';
					}
					elseif(( is_single() || ( is_archive() && (defined('AMP_WOOCOMMERCE_DATA_VERSION') && function_exists('is_product_category') && !is_product_category() ) ) ) && function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE )){
						$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-posts-template.php';						
					}
				}elseif ( ( $check_post !=  'post' || $check_post !=  'page' ) && function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) ) {
					$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/custom-post-fullwidth-template.php';
				}elseif(class_exists('\Elementor\Plugin') && \Elementor\Plugin::$instance->db->is_built_with_elementor($postId) ){
					if( is_page() || ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() )) {
						global $redux_builder_amp;
                        if( $redux_builder_amp['amp-design-selector'] == 3 ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template-d3.php';
							$allowedPagetemplate = array('elementor_canvas', 'elementor_header_footer','');
						}else{
						$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template.php';					
						 $allowedPagetemplate = array('elementor_canvas', 'elementor_header_footer','');
						}
				    }
				    else{
				    	$allowedPagetemplate = array('elementor_canvas', 'elementor_header_footer');
				    }
					if( in_array(get_page_template_slug( $postId ), $allowedPagetemplate)  ){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}elseif ($redux_builder_amp['amp-design-selector'] == 3 ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template-d3.php'; 
						}
						else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template.php';
						}	
					}
				 }elseif( ( 'active' === get_post_meta( $postId, 'fusion_builder_status', true ) || 'yes' === get_post_meta( $postId, 'fusion_builder_converted', true ) )  ){
					if(get_page_template_slug( $postId )=='100-width.php' || $post->post_type=='avada_portfolio'){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-avada-template.php';
						}
					}
					
				}elseif( 'active' == get_post_meta( $postId, '_aviaLayoutBuilder_active', true) ){
					if( get_post_meta($postId, 'layout', true)=='fullsize'){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/single-fullwidth-template.php';
						}
					}
				}elseif(function_exists('brizy_load') || class_exists('Brizy_Editor_Post') && Brizy_Editor_Post::get( $postId )->uses_editor() ){
					 $allowedPagetemplate = array('brizy-blank-template.php');
					 if( in_array(get_page_template_slug( $postId ), $allowedPagetemplate)  ){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/brizy-single-fullwidth-template.php';
						}	
					}
				 }
				 elseif( function_exists('oxygen_vsb_register_condition') || (is_plugin_active('oxygen/functions.php') ) ) {
					$allowedPagetemplate = array('oxygen-vbs-template.php');
					//if( in_array(get_page_template_slug( $postId ), $allowedPagetemplate)  ){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/oxygen-single-fullwidth-template.php';
						
							
						}	
					//}
				 }
				 elseif($theme->Name =='Flatsome'){
					$allowedPagetemplate = array('page-blank.php');
					if( in_array(get_page_template_slug( $postId ), $allowedPagetemplate) && pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-ux-support')){
						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/ux-single-fullwidth.php';
						}	
					}
				 }
				 elseif( class_exists('FLBuilderAdminPointers') && pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-beaver-support') && is_page()  ) {

						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/beaver-single-fullwidth-template.php';
						
							
						}
				 }
				 elseif( is_page() && 'Betheme' == $theme->name || 'Betheme Child' == $theme && pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-muffin-support')  ) {

						if( function_exists('amp_activate') ){
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
						}else{
							$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/muffin-single-fullwidth-template.php';
						
							
						}
				 }
					
			
		 	}
		}

		return $file;
	}

	public function amp_pbc_load_beaver_template( $file, $type, $post ){
		global $redux_builder_amp;
		if ( is_singular() || is_home() || is_archive() || (function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) ) {
			if( 'single' === $type || $type== 'page' ) {
				if( class_exists('FLBuilderAdminPointers') && ( is_page() || function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) ) {
					if( function_exists('amp_activate') ){
						$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'includes/automattic-comp/amp-automattic-fullwidth-template.php';
					}else{
						$file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/beaver-single-fullwidth-template.php';
					}
				}
		 	}
		}
		return $file;
	}

	function amp_pbc_clear_css_transient() {
		$nonceCheck = wp_verify_nonce( $_GET['nonce'], 'amp_pbc_nonce' );
		if(current_user_can('manage_options') && $nonceCheck){
			$this->removeCssFiles();
			echo json_encode(array("status"=>200, "message"=>"CSS Cache Cleared Successfully"));
		}else{
			echo json_encode(array("status"=>400, "message"=>"User has unauthorized access"));
		}
		wp_die();
	}
	public function removeCssFiles(){
		if(current_user_can('manage_options')){
			$upload_dir = wp_upload_dir(); 
			$user_dirname = $upload_dir['basedir'] . '/' . 'pb_compatibility';
			if(file_exists($user_dirname)){
				$files = glob($user_dirname . '/*');
		
				//Loop through the file list.
				foreach($files as $file){
					//Make sure that this is a file and not a directory.
					if(is_file($file) && strpos($file, '_transient')!==false ){
						//Use the unlink function to delete the file.
						unlink($file);
					}
				}
			}
		}
		return true;
	}

	public function amp_pbc_clear_css_transient_post($post_id){
		if(!$post_id){return false; }
		if(current_user_can('edit_post', $post_id) || current_user_can('edit_page', $post_id)){
			$upload_dir = wp_upload_dir(); 
			$user_dirname = $upload_dir['basedir'] . '/' . 'pb_compatibility';
			$filename = $user_dirname."/_transient_post-{$post_id}.css";
			if(file_exists($filename)){
				
				unlink($filename);
			}
		}
	}

	public function menu_for_amp_pb() {
		if(!defined('AMPFORWP_PLUGIN_DIR')){
	   add_menu_page(' AMP Pagebuilder Compatibility ', 'AMP Pagebuilder Compatibility', 'add_users', 'amp_pbc', array($this, 'amp_pbc_for_automattic'), null, 100); 
		}
	}
	function amp_pbc_for_automattic(){
	   require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/automattic-comp/amp-automattic-settings.php';
	   
	}
	// To check whether AMPforwp / AmpbyAutomattic is active and give the Output
	public static function get_setting_data($dataindex) {
		global $redux_builder_amp;
		if( function_exists('ampforwp_is_amp_endpoint') ){
	 		if(isset($redux_builder_amp[$dataindex])){
				return $redux_builder_amp[$dataindex];
			}else{
				return false;
			}

 		}elseif(function_exists('amp_activate')){
	 		$amppbc_savedData = get_option('amp_pbc_automattic');
	 		if(isset($amppbc_savedData[$dataindex])){
		 		return $amppbc_savedData[$dataindex];
		 	}else{return false; }
	 	}
 	
  	}
  // Generating AMPHTML when only Automattic is activated with Elementor FullWidth/Canvas Mode.	
  public function amp_pbc_rel_canonical() {  	
    $amp_url = "";
    if(function_exists('amp_activate') && !function_exists('ampforwp_is_amp_endpoint')){
	 amp_add_amphtml_link();
		}
	}

}
pagebuilder_for_amp_utils::admin_extra_settings();
$utilityObj = new pagebuilder_for_amp_utils();
$utilityObj->init();
//print_r($utilityObj); die;



function amp_pb_compatibility_showhide_component($option){
	global $redux_builder_amp;
	
	$returnResponse = false;

	$postid = ampforwp_get_the_ID();
	$pb_value = get_post_meta( $postid, 'amp_pb_compatibility', true );
	if(isset($pb_value[$option])){
		$returnResponse = $pb_value[$option];
	}else{
		$default['title'] = $redux_builder_amp['single-show-title-pb-for-amp'];
        $default['comment'] = $redux_builder_amp['single-show-comments-pb-for-amp'];
        //$default['releted_post'] = $redux_builder_amp['single-show-related-pb-for-amp'];
        if(isset($default[$option])){
			$returnResponse = $default[$option];
		}
	}
	return $returnResponse;
}