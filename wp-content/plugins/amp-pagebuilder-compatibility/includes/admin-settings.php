<?php
class pb_compatibility_admin_settings_extra{
	function init(){
        //if ( !is_plugin_active( 'js_composer/js_composer.php' ) ) {
		add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_common'),8,1 );
   // }
		add_action( 'add_meta_boxes', array($this, 'adding_meta_boxes'), 10, 2 );
        add_action( 'admin_enqueue_scripts', array($this, 'load_custom_wp_admin_style'), 10 );

        add_action( 'save_post', array($this, 'wpt_save_events_meta'), 1, 2 );
        /*add_action('ampforwp_below_title', array($this, 'show_title'));
        add_action('ampforwp_after_cooments', array($this, 'show_comments'));
        add_action('ampforwp_below_recent_posts', array($this, 'show_recent_posts'));*/
	}
    function load_custom_wp_admin_style($hook) {
        // Load only on ?page=mypluginname
        if(!in_array($hook, array('toplevel_page_amp_options', 'toplevel_page_amp_pbc')) ) {
            return;
        }
            wp_register_script( 'amp-pbc-js', AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR_URI . 'assets/amp-pbc-main.js', array('jquery'), AMP_PAGEBUILDER_COMPATIBILITY_VERSION );
            wp_enqueue_script( 'amp-pbc-js' );
    }
    public static function get_admin_options_other($section = array()){
        $obj = new self();
        //print_r($obj);die;
        $section = $obj->add_options_common($section);
        return $section;
    }
	function add_options_common($sections){
        $sectionskey = 0;
		foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
            	 break;
            }
        }
        $sections[$sectionskey]['fields'][] = array(
                'id' => 'extra-single-pb-for-amp-accor',
                'type' => 'section',
                'title' => esc_html__('Single page Settings', 'amp-pagebuilder-compatibility'),
                'indent' => true,
                'layout_type' => 'accordion',
                'section_id' => 'amp-content-builder',
                'accordion-open'=> 1, 
            );

        $sections[$sectionskey]['fields'][] = array(
            'id'        => 'amppb-clear-cached-css-pb-for-amp',
            'type'      => 'raw',
            'class'     => 'child_opt child_opt_arrow',
            'title'     => esc_html__('Clear AMP CSS cache', 'amp-pagebuilder-compatibility'),
            'indent'    => true,
            'content'   => "<span class='button button-primary button-small' id='amp-pbc-clearcss-data' target='_blank'  data-nonce='".wp_create_nonce( 'amp_pbc_nonce')."'></i> Clear CSS</span><span id='amp-pbc-clcss-msg'></span>",
            'tooltip-subtitle' => esc_html__('To clear cached css after cleaning css', 'amp-pagebuilder-compatibility'),
            'full_width' => false,
            'section_id'=>'amp-content-builder',
            'accordion-open'=> 1, 
        );
        $sections[$sectionskey]['fields'][] = array(
            'id' => 'clear-css-pb-on-post-update',
            'type'     => 'switch',
            'class'    => 'child_opt child_opt_arrow',
            'title' => esc_html__('Clear AMP CSS on Post Update', 'amp-pagebuilder-compatibility'),
            'tooltip-subtitle' => esc_html__('Clears the pagebuilder css on every post update','amp-pagebuilder-compatibility'),
            'indent' => true,
            'default'=>0,
            'section_id'=>'amp-content-builder',
        );
        $sections[$sectionskey]['fields'][] = array(
            'id' => 'debug-mode-pb-for-amp',
            'type'     => 'switch',
            'class'    => 'child_opt child_opt_arrow',
            'title' => esc_html__('Debug mode', 'amp-pagebuilder-compatibility'),
            'tooltip-subtitle' => esc_html__('To Debug css removed after css cleaning', 'amp-pagebuilder-compatibility'),
            'indent' => true,
            'default'=>0,
            'section_id'=>'amp-content-builder',
        );
        $sections[$sectionskey]['fields'][] = array(
            'id' => 'single-show-title-pb-for-amp',
            'type'     => 'switch',
            'class'    => 'child_opt child_opt_arrow',
            'title' => esc_html__('Title', 'amp-pagebuilder-compatibility'),
            'tooltip-subtitle' => esc_html__('Show post/page title on full width template', 'amp-pagebuilder-compatibility'),
            'indent' => true,
            'default'=>0,
            'section_id'=>'amp-content-builder',
            'accordion-open'=> 1, 
        );
 if(function_exists('ampforwp_is_amp_endpoint')){
        $sections[$sectionskey]['fields'][] = array(
                'id' => 'single-show-comments-pb-for-amp',
                'type'     => 'switch',
                'class'    => 'child_opt child_opt_arrow',
                'title' => esc_html__('Comments', 'amp-pagebuilder-compatibility'),
                'tooltip-subtitle' => esc_html__('Show post/page Comments on full width template', 'amp-pagebuilder-compatibility'),
                'indent' => true,
                'default'=>0,
                'section_id'=>'amp-content-builder',
                'accordion-open'=> 1, 
            );
        }
        /*$sections[$sectionskey]['fields'][] = array(
                'id' => 'single-show-related-pb-for-amp',
                'type'     => 'switch',
                'class'    => 'child_opt child_opt_arrow',
                'title' => esc_html__('Recent/Related posts', 'amp-pagebuilder-compatibility'),
                'indent' => true,
                'default'=>0,
                'section_id'=>'amp-content-builder',
                'accordion-open'=> 1, 
            );*/

       return $sections;
	}

	function adding_meta_boxes($post_type, $post){
		$allposttype = function_exists('ampforwp_get_all_post_types')? ampforwp_get_all_post_types(): array();
        foreach ($allposttype as $key => $post_types) {
		 add_meta_box( 'amp-pagebuilder-compatibility-metabox', 'AMP PageBuilder Compatibility', array($this, 'metabox_fields'), $post_types, 'side', 'default' );
		}

	}
	function metabox_fields(){
        global $post, $redux_builder_amp;
        $pb_value = get_post_meta( $post->ID, 'amp_pb_compatibility', true );
        if(!$pb_value){
            $default['title'] = $redux_builder_amp['single-show-title-pb-for-amp'];
            $default['comment'] = $redux_builder_amp['single-show-comments-pb-for-amp'];
           /* $default['releted_post'] = $redux_builder_amp['single-show-related-pb-for-amp'];*/
            $pb_value = wp_parse_args( $pb_value, $default );
        }

        // Nonce field to validate form request came from current site
        wp_nonce_field( 'amp_pb_compatibility_nonce', 'amp_pb_compatibility_nonce' );
		echo "
            <div>
                <input type='checkbox' id='amp_pb_compatibility_title' name='amp_pb_compatibility[title]' class='post_format' ".($pb_value['title']? 'checked':'')." value='1'/>
                <label for='amp_pb_compatibility_title'>Title</label>
            </div>
            <div>
                <input type='checkbox' id='amp_pb_compatibility_comment' name='amp_pb_compatibility[comment]' class='post_format' ".($pb_value['comment']? 'checked':'')." value='1'/>
                <label for='amp_pb_compatibility_comment'>Comments</label>
            </div>
            ";
            /**/
            
	}
    function wpt_save_events_meta( $post_id, $post ) {
        // Return if the user doesn't have edit permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }
        // Verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times.
        if ( ! isset( $_POST['amp_pb_compatibility_nonce'] ) || ! wp_verify_nonce( $_POST['amp_pb_compatibility_nonce'], 'amp_pb_compatibility_nonce' ) ) {
            return $post_id;
        }
        $pb_compatibility =  isset($_POST['amp_pb_compatibility'])? $_POST['amp_pb_compatibility'] : array();
        if(!isset($pb_compatibility['title'])){
            $pb_compatibility['title'] = 0;
        }
        if(!isset($pb_compatibility['comment'])){
            $pb_compatibility['comment'] = 0;
        }
        /*if(!isset($pb_compatibility['releted_post'])){
            $pb_compatibility['releted_post'] = 0;
        }*/
        update_post_meta( $post->ID, 'amp_pb_compatibility',  $pb_compatibility );
    }

    
}
if(is_admin()){
	$pb_compatibility_admin_extra = new pb_compatibility_admin_settings_extra();
	$pb_compatibility_admin_extra->init();
}