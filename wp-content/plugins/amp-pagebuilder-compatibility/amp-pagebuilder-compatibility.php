<?php
/*
Plugin Name: AMP Pagebuilder Compatibility
Description: This is an AMP Compatibility extension for Pagebuilder like Divi and Elementor Pagebuilder.
Author: AMPforWP Team
Version: 1.9.82
Author URI: http://ampforwp.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: amp-pagebuilder-compatibility
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define('AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR_URI', plugin_dir_url(__FILE__));
define('AMP_PAGEBUILDER_COMPATIBILITY_IMAGE_DIR',plugin_dir_url(__FILE__).'assets/images');
define('AMP_PAGEBUILDER_COMPATIBILITY_MAIN_PLUGIN_DIR', plugin_dir_path( __DIR__ ) );
define('AMP_PAGEBUILDER_COMPATIBILITY_VERSION','1.9.82');
 

// this is the URL our updater / license checker pings. This should be the URL of the site with Page builder for AMP installed
define( 'AMP_PAGEBUILDER_COMPATIBILITY_STORE_URL', 'https://accounts.ampforwp.com/' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in Page builder for AMP exactly
define( 'AMP_PAGEBUILDER_COMPATIBILITY_ITEM_NAME', 'AMP Pagebuilder Compatibility' );

// the name of the settings page for the license input to be displayed
define( 'AMP_PAGEBUILDER_COMPATIBILITY_LICENSE_PAGE', 'amp-pagebuilder-compatibility' );



// the name of the settings page for the license input to be displayed
if(! defined('AMP_PAGEBUILDER_COMPATIBILITY_ITEM_FOLDER_NAME')){
    $folderName = basename(__DIR__);
    define( 'AMP_PAGEBUILDER_COMPATIBILITY_ITEM_FOLDER_NAME', $folderName );
}
global $anyPbActive;
function amp_pagebuilder_compatibility_init(){
  global $anyPbActive;
  $anyPbActive = false;
  if ( ! function_exists( 'is_plugin_active' ) ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
  }
  //Utility functions
  include_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . '/includes/utill-function.php' );

  $theme = wp_get_theme(); // gets the current theme
  if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme  || 'Extra' == $theme->name || 'Extra' == $theme->parent_theme ) {
    $anyPbActive = true;
  	 require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-divi-pagebuilder.php';
  }

  $curr_theme = wp_get_theme();
  if ( 'Extra' == $curr_theme->name || 'Extra' == $curr_theme->parent_theme ) {
    require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-extra-pagebuilder.php';
  }
  
  //Avia builder
  if ( 'Enfold' == $theme->name || 'Enfold' == $theme->parent_theme ) {
    $anyPbActive = true;
    require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-avia-pagebuilder.php';
  }

  if ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active( 'the-elementor/elementor.php' ) ) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-elementor-pagebuilder.php';
  }

  if (is_plugin_active('oxygen/functions.php')) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-oxygen-pagebuilder.php';
  }
  //ux builder(flatsome)
  $theme = wp_get_theme(); // gets the current theme
  if($theme->Name =='Flatsome' || $theme->Name =='Flatsome Child'){
     require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-ux-builder-pagebuilder.php';
  }

  if (is_plugin_active('beaver-builder-lite-version/fl-builder.php') || is_plugin_active('bb-plugin/fl-builder.php')) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-beaver-pagebuilder.php';
  }
  if ( 'Betheme' == $theme->name || 'Betheme Child' == $theme ) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-muffin-pagebuilder.php';
  }
  
}

amp_pagebuilder_compatibility_init();
add_action("plugins_loaded", "vcEnableEntrance");
function vcEnableEntrance(){
    global $vc_manager;
    global $anyPbActive;
    if($vc_manager instanceof Vc_Manager){
      $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'amp-vc-pagebuilder.php';
    }
    //Avada
    if( class_exists('FusionBuilder') ){
      $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-avada-pagebuilder.php';
    }

    if ( function_exists('brizy_load') ) {
      $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-brizy-pagebuilder.php';
    }

    if ( is_plugin_active('oxygen/functions.php') || function_exists('oxygen_vsb_register_condition')) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-oxygen-pagebuilder.php';
  }

  //ux builder(flatsome)
  $theme = wp_get_theme(); // gets the current theme
  if($theme->Name =='Flatsome' || $theme->Name =='Flatsome Child'){
    $anyPbActive = true;
     require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-ux-builder-pagebuilder.php';
  }

  if (is_plugin_active('beaver-builder-lite-version/fl-builder.php') || is_plugin_active('bb-plugin/fl-builder.php')) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-beaver-pagebuilder.php';
  }

  if ( 'Betheme' == $theme->name || 'Betheme Child' == $theme) {
    $anyPbActive = true;
      require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/amp-muffin-pagebuilder.php';
  }
  

    if($anyPbActive != true){
      add_filter( 'redux/options/redux_builder_amp/sections', 'add_options_for_fallback_compatibility',7,1 );
  }
}

function add_options_for_fallback_compatibility($sections){
          $pb_for_amp[] =  array(
                  'id' => 'ampforwp-pb-for-amp-accor',
                  'type' => 'section',
                  'title' => esc_html__('Page Builder Compatibility', 'accelerated-mobile-pages'),
                  'indent' => true,
                  'layout_type' => 'accordion',
                  'accordion-open'=> 1, 
              );
          $pb_for_amp[]= array(
                  'id'   => 'ampforwp-pb-for-amp-info',
                  'type' => 'info',
                   'desc' => '<div style="    background: #FFF9C4;padding: 12px;line-height: 1.6;margin:-45px -14px -18px -17px;">Currently there is no Page Builder Plugin / Theme active for AMP Pagebuilder Compatibility to work.</div>',               
                     );
  foreach ($sections as $sectionskey => $sectionsData) {
      if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
          
        $sections[$sectionskey]['fields'] = array_merge($sections[$sectionskey]['fields'], $pb_for_amp);
      }

    }

  return $sections;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'amp_pagebuilder_compatibility_add_action_links' );

function amp_pagebuilder_compatibility_add_action_links ( $links ) {
  if(!defined('AMPFORWP_PLUGIN_DIR') ){
      $mylinks = array('<a href="' . admin_url( 'admin.php?page=amp_pbc' ) . '">Settings</a>',
      );
  }else{
    $mylinks = array(
                '<a href="' . admin_url( 'admin.php?page=amp_options&tabid=amp-content-builder' ) . '">Settings</a>',
              );
  }
  return array_merge( $links, $mylinks );
}


add_action('plugins_loaded','amp_bunnyads_widget_hook');
function amp_bunnyads_widget_hook(){
    $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH),'/' );
        $explode_path = explode('/', $url_path);
    if ( in_array('amp', $explode_path)) {
        add_action( 'widgets_init', 'amp_override_bunnyads_widget', 15 );
    }
}
function amp_override_bunnyads_widget() {
  if ( class_exists( 'Bunyad_Ads_Widget' ) ) {
        unregister_widget( 'Bunyad_Ads_Widget' );
        register_widget( 'Amp_Bunyad_Ads_Widget' );
  }
}
if( pagebuilder_for_amp_utils::get_setting_data('clear-css-pb-on-post-update') ){
  add_action( 'save_post', 'amp_post_css_update_hook', 10,3 );
}
function amp_post_css_update_hook( $post_id, $post, $update ) {
    amp_pbc_clear_css_on_post_update();
}
function amp_pbc_clear_css_on_post_update() {
    if(current_user_can('manage_options') ){
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
      //return true;
      //echo json_encode(array("status"=>200, "message"=>"CSS Cache Cleared Successfully"));
    }else{
      //echo json_encode(array("status"=>400, "message"=>"User has unauthorized access"));
    }
}

Class Amp_Bunyad_Ads_Widget extends WP_Widget {
    public function __construct()
    {
        parent::__construct(
            'bunyad_ads_widget',
            'Bunyad - Advertisement',
            array('description' => __('Advertisements widget for all the ads supported.', 'bunyad-widgets'), 'classname' => 'code-widget')
        );
    }

    public function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);
        ?>
        <?php echo $before_widget; ?>
            <?php if ($title): ?>
                <?php echo $before_title . $title . $after_title; ?>
            <?php endif; ?>
            <div class="a-widget" style="float:none;text-align: center;">
                <?php 
                $ads_ouput = do_shortcode($instance['code']); 
                preg_match('/data-ad-client="(.*?)"(.*?)data-ad-slot="(.*?)"/s', $ads_ouput, $matches);
                if($matches){
                ?>
                <amp-ad type="adsense" width="300" height="600" data-ad-client="<?php echo $matches[1];?>" data-ad-slot="<?php echo $matches[3];?>" data-enable-refresh="10"></amp-ad>
              <?php } ?>
            </div>
        <?php echo $after_widget;?>
        <?php
    }
}

// CODE TO ADD LICENSE ACTIVATION FATURE FOR NON AMPFORWP
if(!defined('AMPFORWP_PLUGIN_DIR')){
  $redux_builder_amp = get_option('redux_builder_amp',true);
  add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'amp_pbc_ext_plugin_activation_link' );
  function amp_pbc_ext_plugin_activation_link( $links ) {
      $_link = '<a href="admin.php?page=amp-pbc-license-activation" target="_blank">License Activation</a>';
      $links[] = $_link;
      return $links;
  }
  if(file_exists(ABSPATH . 'wp-admin/includes/plugin.php')){
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
  add_action('init', 'amp_pbc_set_extension_license_key_page');
  function amp_pbc_set_extension_license_key_page(){
    require_once(dirname( __FILE__ ) . '/license/license-activation.php');
    if(function_exists('add_submenu_page')){
      add_submenu_page(
          '',
          'License Activation',
          'License Activation',
          'manage_options',
          'amp-pbc-license-activation',
          'amp_pbc_license_activation'
      );
      }
  }
  function amp_pbc_license_activation(){
    require_once(dirname( __FILE__ ) . '/license/license-activation-view.php');
  }
}

//***************************//
// Updater code Starts here //
//**************************//
  /*
  Plugin Update Method
 */
require_once dirname( __FILE__ ) . '/updater/EDD_SL_Plugin_Updater.php';

// Check for updates
function amp_pagebuilder_compatibility_plugin_updater() {

    // retrieve our license key from the DB
    $license_key = trim( get_option( 'amp_ads_license_key' ) );
    $selectedOption = get_option('redux_builder_amp',true);
    $license_key = '';//trim( get_option( 'amp_ads_license_key' ) );
    $pluginItemName = '';
    $pluginItemStoreUrl = '';
    $pluginstatus = '';
    if( isset($selectedOption['amp-license']) && "" != $selectedOption['amp-license'] && isset($selectedOption['amp-license'][AMP_PAGEBUILDER_COMPATIBILITY_ITEM_FOLDER_NAME])){

       $pluginsDetail = $selectedOption['amp-license'][AMP_PAGEBUILDER_COMPATIBILITY_ITEM_FOLDER_NAME];
       $license_key = $pluginsDetail['license'];
       //$pluginItemName = $pluginsDetail['item_name'];
       $pluginItemStoreUrl = $pluginsDetail['store_url'];
       $pluginstatus = $pluginsDetail['status'];
    }
    
    // setup the updater
    $edd_updater = new AMP_PAGEBUILDER_COMPATIBILITY_EDD_SL_Plugin_Updater( AMP_PAGEBUILDER_COMPATIBILITY_STORE_URL, __FILE__, array(
            'version'   => AMP_PAGEBUILDER_COMPATIBILITY_VERSION,                // current version number
            'license'   => $license_key,                        // license key (used get_option above to retrieve from DB)
           'license_status'=>$pluginstatus,
            'item_name' => AMP_PAGEBUILDER_COMPATIBILITY_ITEM_NAME,          // name of this plugin
            'author'    => 'Mohammed Kaludi',                   // author of this plugin
            'beta'      => false,
        )
    );
}
add_action( 'admin_init', 'amp_pagebuilder_compatibility_plugin_updater', 0 );

// Notice to enter license key once activate the plugin

$path = plugin_basename( __FILE__ );
    add_action("after_plugin_row_{$path}", function( $plugin_file, $plugin_data, $status ) {
        global $redux_builder_amp;
        if(! defined('PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME')){
        $folderName = basename(__DIR__);
            define( 'PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME', $folderName );
        }
        $pluginstatus = '';
        if( isset( $redux_builder_amp['amp-license'][PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME] ) ){
          $pluginsDetail = $redux_builder_amp['amp-license'][PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME];
          $pluginstatus = $pluginsDetail['status'];
        }

           $activation_link = 'amp_options&tabid=opt-go-premium';
          if(!defined('AMPFORWP_PLUGIN_DIR')){
            $activation_link = 'amp-pbc-license-activation';
          }

       if(empty($redux_builder_amp['amp-license'][PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME]['license'])){
            echo "<tr class='active'><td>&nbsp;</td><td colspan='2'><a href='".esc_url(  self_admin_url( 'admin.php?page='.$activation_link )  )."'>Please enter the license key</a> to get the <strong>latest features</strong> and <strong>stable updates</strong></td></tr>";
               }elseif($pluginstatus=="valid"){
                $update_cache = get_site_transient( 'update_plugins' );
            $update_cache = is_object( $update_cache ) ? $update_cache : new stdClass();
            if(isset($update_cache->response[ PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME ]) 
                && empty($update_cache->response[ PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME ]->download_link) 
             ){
               unset($update_cache->response[ PAGEBUILDER_AMP_COMPATIBILITY_ITEM_FOLDER_NAME ]);
            set_site_transient( 'update_plugins', $update_cache );
            }
            
        }
    }, 10, 3 );

//***************************//
// Updater code ends here //
//**************************//