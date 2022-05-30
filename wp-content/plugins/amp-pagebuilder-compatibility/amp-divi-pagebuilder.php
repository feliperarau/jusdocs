<?php
if ( ! defined( 'ABSPATH' ) ) exit;
//global $amp_diviCurrentStyleGlobal;
add_action( 'init', 'amp_add_blog_pagination_rewrite_rules', 25 );
function amp_add_blog_pagination_rewrite_rules(){
    global $redux_builder_amp, $wp_rewrite;
    add_rewrite_rule(
        '(.?.+?)/amp/page/?([0-9]{1,})/?$',
        'index.php?amp=1&paged=$matches[2]&pagename=$matches[1]',
        'top'
    );
}

class AMP_PC_Divi_Pagebuidler {

    public function __construct()
    {
        $this->load_dependencies();
        //$this->define_public_hooks();
    }
    
    private function load_dependencies(){
        if(pagebuilder_for_amp_utils::get_setting('pagebuilder-for-amp-divi-support') ){
            add_filter('amp_post_template_css', [$this,'amp_divi_custom_styles'],11);
            add_action('amp_post_template_footer',[$this,'get_amp_et_inline_css_file']);
            add_filter('ampforwp_body_class', [$this,'ampforwp_body_class_divi'],11);
            //font load 
            add_filter('amp_post_template_css', [$this, 'amp_pbc_load_fonts'],12);
            add_action('amp_post_template_head', [$this, 'amp_divi_pagebuilder_font_link']);
            add_filter('ampforwp_pagebuilder_status_modify', [$this, 'pagebuilder_status_reset_divi'], 10, 2);
            add_action( 'et_builder_ready', [$this,'amp_divi_pagebuidler_override'] );
            add_filter( 'et_module_classes', [$this,'amp_divi_pbc_replace_modules'] );
            add_filter('amp_content_sanitizers',[$this, 'ampforwp_gravity_blacklist_sanitizer'], 99);
            add_action('amp_post_template_above_footer',[$this,'amp_pc_divi_global_footer']);
            add_action('ampforwp_modify_the_content',[$this,'amp_pc_divi_global_body']);
            add_action("amp_post_template_css" , [$this,'amp_pc_divi_inline_css']);
            add_filter('amp_wc_modify_single_product_html',[$this,'amp_pc_divi_global_body']);
            add_action('ampforwp_after_header',[$this,'amp_pc_divi_global_header']);
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/parser/index.php';
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/divi/theme-builder/theme-builder.php';
        }
    }

    public function amp_pc_divi_inline_css(){
            $layouts = amp_et_theme_builder_get_template_layouts();
        if( function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) && $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['override'] && pagebuilder_for_amp_utils::get_setting('divi-themebuilder-global-body') ){
            ob_start();
            et_theme_builder_frontend_render_body(
                $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['id'],
                $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['enabled'],
                $layouts[ ET_THEME_BUILDER_TEMPLATE_POST_TYPE ]
            );
            $body_html = ob_get_contents();
            ob_get_clean();
             if(class_exists('AMPFORWP_Content')){
                    $sanitizer_obj = new AMPFORWP_Content( $body_html,
                        apply_filters( 'amp_content_embed_handlers', array()),
                        apply_filters( 'amp_content_sanitizers', 
                            array(
                            'AMP_Style_Sanitizer' => array(), 
                            ) 
                        ) 
                    );

                $inlineCss = $sanitizer_obj->get_amp_styles();

            }
        }
      if(isset($inlineCss)){  
      foreach ($inlineCss as $key => $value) {
        echo $key.'{'.$value[0].'}';
      }
    }
     
}

    public function amp_pc_divi_global_header(){
        if( pagebuilder_for_amp_utils::get_setting('divi-themebuilder_header') ){
            $layouts = amp_et_theme_builder_get_template_layouts();
            ob_start();
                if( $layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['enabled'] ){
                    et_theme_builder_frontend_render_header(
                        $layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['id'],
                        $layouts[ ET_THEME_BUILDER_HEADER_LAYOUT_POST_TYPE ]['enabled'],
                        $layouts[ ET_THEME_BUILDER_TEMPLATE_POST_TYPE ]
                    );
                }
                else{ 
                    if( et_core_is_builder_used_on_current_request() || ampforwp_is_front_page() ){ ?>
                    <header id="main-header" data-height-onload="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>">
                        <div class="container clearfix et_menu_container">
                        <?php
                            $logo = ( $user_logo = et_get_option( 'divi_logo' ) ) && ! empty( $user_logo )
                                ? $user_logo
                                : get_template_directory_uri() . '/images/logo.png';
                        ?>
                            <div class="logo_container">
                                <span class="logo_helper"></span>
                                <a href="<?php echo esc_url( ampforwp_url_controller( home_url( '/' ) ) ); ?>">
                                    <img src="<?php echo esc_attr( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" id="logo" data-height-percentage="<?php echo esc_attr( et_get_option( 'logo_height', '54' ) ); ?>" />
                                </a>
                            </div>
                        <?php

                            echo et_core_intentionally_unescaped( apply_filters( 'et_html_logo_container', $logo_container ), 'html' );
                        ?>
                            <div id="et-top-navigation" data-height="<?php echo esc_attr( et_get_option( 'menu_height', '66' ) ); ?>" data-fixed-height="<?php echo esc_attr( et_get_option( 'minimized_menu_height', '40' ) ); ?>">
                                <?php if ( ! $et_slide_header || is_customize_preview() ) : ?>
                                    <nav id="top-menu-nav">
                                    <?php
                                        $menuClass = 'nav';
                                        if ( 'on' === et_get_option( 'divi_disable_toptier' ) ) $menuClass .= ' et_disable_top_tier';
                                        $primaryNav = '';

                                        $primaryNav = wp_nav_menu( array( 'theme_location' => 'primary-menu', 'container' => '', 'fallback_cb' => '', 'menu_class' => $menuClass, 'menu_id' => 'top-menu', 'echo' => false ) );
                                        if ( empty( $primaryNav ) ) :
                                    ?>
                                        <ul id="top-menu" class="<?php echo esc_attr( $menuClass ); ?>">
                                            <?php if ( 'on' === et_get_option( 'divi_home_link' ) ) { ?>
                                                <li <?php if ( is_home() ) echo( 'class="current_page_item"' ); ?>><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'Divi' ); ?></a></li>
                                            <?php }; ?>

                                            <?php show_page_menu( $menuClass, false, false ); ?>
                                            <?php show_categories_menu( $menuClass, false ); ?>
                                        </ul>
                                    <?php
                                        else :
                                            echo et_core_esc_wp( $primaryNav );
                                        endif;
                                    ?>
                                    </nav>
                                <?php endif; ?>

                                <?php
                                if ( ! $et_top_info_defined && ( ! $et_slide_header || is_customize_preview() ) ) {
                                    et_show_cart_total( array(
                                        'no_text' => true,
                                    ) );
                                }
                                ?>

                                <?php if ( $et_slide_header || is_customize_preview() ) : ?>
                                    <span class="mobile_menu_bar et_pb_header_toggle et_toggle_<?php echo esc_attr( et_get_option( 'header_style', 'left' ) ); ?>_menu"></span>
                                <?php endif; ?>

                                <?php if ( ( false !== et_get_option( 'show_search_icon', true ) && ! $et_slide_header ) || is_customize_preview() ) : ?>
                                <div id="et_top_search">
                                    <a id="et_search_icon" title="search" href="#ampdivi-search"></a>
                                </div>
                                <?php endif; // true === et_get_option( 'show_search_icon', false ) ?>
                                <?php
                                do_action( 'et_header_top' );

                                ?>
                                <div class="lb-btn"> <div class="lb-t" id="ampdivi-search"> <form role="search" method="get" class="amp-search" target="_top" action="//localhost/wp" novalidate=""> <div class="amp-search-wrapper"> <label aria-label="Type your query" class="screen-reader-text" for="s">Type your search query and hit enter: </label> <input type="text" placeholder="AMP" value="1" name="amp" class="hidden"> <label aria-label="search text" for="search-text-59"></label> <input id="search-text-59" type="text" placeholder="Type Here" value="" name="s" class="s"> <label aria-label="Submit amp search" for="amp-search-submit"> <input type="submit" class="icon-search" value="Search"> </label> <div class="overlay-search"> </div> </div> </form> <a title="close" class="lb-x" href="#"></a> </div> </div>
                            </div> <!-- #et-top-navigation -->
                        </div> <!-- .container -->
                    </header> <!-- #main-header -->
                    <?php 
                    }
                }
            $header_html = ob_get_contents();
            ob_get_clean();
            if(class_exists('AMPFORWP_Content')){
                    $sanitizer_obj = new AMPFORWP_Content( $header_html,
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

                $amp_sanitized_header_content = $sanitizer_obj->get_amp_content();
                $menu_args = array(
                                'theme_location' => 'amp-menu' ,
                                'link_before'     => '<span>',
                                'link_after'     => '</span>',
                                'menu'=>'ul',
                                'menu_class'=> 'et_mobile_menu',
                                'echo' => false,
                                'menu_class' => 'et_mobile_menu',
                                'walker' => new \Ampforwp_Walker_Nav_Menu()
                            );
                $amp_menu = amp_menu_html(true, $menu_args, 'header'); 
                $menu_hamb = '<div class="et-hmnu" hidden data-amp-bind-hidden="hidehmenu" ><nav class="m-menu" role="navigation" aria-hidden="true">'.$amp_menu.'</nav></div>';
                $amp_sanitized_header_content = preg_replace('/<span\sclass="mobile_menu_bar(.*?)"><\/span>/', '<span class="mobile_menu_bar$1" data-amp-bind-hidden="hidehmbgr" on="tap:AMP.setState({ hidehmenu: false, hidehmbgr: true, hidenhmbgr: false })" role="button" tabindex=0></span><span class="mobile_menu_bar" hidden data-amp-bind-hidden="hidenhmbgr" on="tap:AMP.setState({ hidehmenu: true, hidehmbgr: false, hidenhmbgr: true })" role="button" tabindex=0></span>', $amp_sanitized_header_content);
                $amp_sanitized_header_content .=  $menu_hamb;
                echo $amp_sanitized_header_content;
            }
        }    
    }
    public function amp_pc_divi_global_body($content){
        $layouts = amp_et_theme_builder_get_template_layouts();
        if( function_exists('et_theme_builder_overrides_layout') && et_theme_builder_overrides_layout( ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ) && $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['override'] && pagebuilder_for_amp_utils::get_setting('divi-themebuilder-global-body') ){
            ob_start();
            et_theme_builder_frontend_render_body(
                $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['id'],
                $layouts[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['enabled'],
                $layouts[ ET_THEME_BUILDER_TEMPLATE_POST_TYPE ]
            );
            $body_html = ob_get_contents();
            ob_get_clean();
            if(class_exists('AMPFORWP_Content')){
                    $sanitizer_obj = new AMPFORWP_Content( $body_html,
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

                $content = $sanitizer_obj->get_amp_content();
            }
        }
        return $content;
    }
    public function amp_pc_divi_global_footer(){
        if(pagebuilder_for_amp_utils::get_setting('divi-themebuilder_footer')){
            $layouts = amp_et_theme_builder_get_template_layouts();
            ob_start();
            et_theme_builder_frontend_render_footer(
                $layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['id'],
                $layouts[ ET_THEME_BUILDER_FOOTER_LAYOUT_POST_TYPE ]['enabled'],
                $layouts[ ET_THEME_BUILDER_TEMPLATE_POST_TYPE ]
            );
            if ( et_core_is_fb_enabled() && et_theme_builder_is_layout_post_type( get_post_type() ) ) {
                // Hide the footer when we are editing a TB layout.
                ?>
                <div class="et-tb-fb-footer" style="display: none;">
                    <?php wp_footer(); ?>
                </div>
            <?php }else{
                wp_footer();
            } 
            if( ! et_is_builder_plugin_active() && 'on' === et_get_option( 'divi_back_to_top', 'false' ) ) { ?>
                <span class="et_pb_scroll_top et-pb-icon"></span>
            <?php }

            $footer_html = ob_get_contents();
            ob_get_clean();
            if(class_exists('AMPFORWP_Content')){
                    $sanitizer_obj = new AMPFORWP_Content( $footer_html,
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

                $amp_sanitized_footer_content = $sanitizer_obj->get_amp_content();
                echo $amp_sanitized_footer_content;
            }
        }
    
    }

    public static function load_ajax_calls(){
        add_action('wp_ajax_divi_contact_form_submission',['AMP_PC_Divi_Pagebuidler','divi_contact_form_submission']);
        add_action('wp_ajax_nopriv_divi_contact_form_submission',['AMP_PC_Divi_Pagebuidler','divi_contact_form_submission']);
        add_action( 'wp_ajax_amp_divi_optin_newsletter_submit', array('AMP_PC_Divi_Pagebuidler', 'amp_divi_optin_newsletter_submit') );
        add_action( 'wp_ajax_nopriv_amp_divi_optin_newsletter_submit', array('AMP_PC_Divi_Pagebuidler', 'amp_divi_optin_newsletter_submit') );
    }
    public static function amp_divi_optin_newsletter_submit() {
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/divi-newsletter-form-submission.php';
    }
    public static function divi_contact_form_submission(){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/divi-form-submission.php';
    }
    function ampforwp_gravity_blacklist_sanitizer($data){
     if(function_exists('ampforwp_is_front_page') && ampforwp_is_front_page()){
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/class-amp-divi-blacklist.php';
            unset($data['AMP_Blacklist_Sanitizer']);
            unset($data['AMPFORWP_Blacklist_Sanitizer']);
            $data[ 'AMPFORWP_DIVI_Blacklist' ] = array();

     }else{
            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/class-amp-divi-blacklist.php';
            unset($data['AMP_Blacklist_Sanitizer']);
            unset($data['AMPFORWP_Blacklist_Sanitizer']);
            $data[ 'AMPFORWP_DIVI_Blacklist' ] = array();
        }
        return $data;
    }

    function pagebuilder_status_reset_divi($response, $postId ){
        if ( pagebuilder_for_amp_utils::get_setting('divi-themebuilder-global-body') && 'on' === get_post_meta( $postId, '_et_pb_use_builder', true ) ) {
            $response = true;
        }
        return $response;
    }
    public static function classesReplacements($completeContent){
        $completeContent = preg_replace("/wpb_animate_when_almost_visible/", "", $completeContent);
        $completeContent = preg_replace("/et_animated\s/", "", $completeContent);
        $completeContent = preg_replace("/et-waypoint/", "", $completeContent);
        //
        $completeContent = preg_replace("/et_pb_module_/", "em_", $completeContent);
        $completeContent = preg_replace("/et_pb_module/", "em", $completeContent);

        $completeContent = preg_replace("/et_pb_column_/", "ec_", $completeContent);
        $completeContent = preg_replace("/et_pb_column/", "ec", $completeContent);

        $completeContent = preg_replace("/et_pb_gutters/", "egs", $completeContent);
        $completeContent = preg_replace("/et_pb_gutter/", "eg_", $completeContent);

        $completeContent = preg_replace("/et_pb_blurb_/", "eb_", $completeContent);
        $completeContent = preg_replace("/et_pb_blurb/", "eb", $completeContent);
        
        $completeContent = preg_replace("/et_pb_section_/", "es_", $completeContent);
        $completeContent = preg_replace("/et_pb_section/", "es", $completeContent);

        $completeContent = preg_replace("/et_pb_row_/", "er_", $completeContent);
        $completeContent = preg_replace("/et_pb_row/", "er", $completeContent);
        
        $completeContent = preg_replace("/et_pb_preload/", "", $completeContent);
        $completeContent = preg_replace("/data-columns>/", ">", $completeContent);
        $completeContent = preg_replace("/et_pb_/", "e_", $completeContent);
        $completeContent = preg_replace("/em_header/", "em_hd", $completeContent);
        $completeContent = preg_replace("/e_toggle_title/", "e_tg_t", $completeContent);
        $completeContent = preg_replace("/_toggle/", "_tg", $completeContent);
        $completeContent = preg_replace("/_fullwidth_header/", "fh", $completeContent);
        $completeContent = preg_replace("/_testimonial_author/", "tauth", $completeContent);
        $completeContent = preg_replace("/eb_content/", "ecnt", $completeContent);
        $completeContent = preg_replace("/eb_container/", "econt", $completeContent);
        $completeContent = preg_replace("/_text_align_left/", "txtal", $completeContent);
        $completeContent = preg_replace("/_bg_layout_dark/", "bglaydark", $completeContent);
        $completeContent = preg_replace("/e_contact_field/", "ecfield", $completeContent);
        $completeContent = preg_replace("/e_main_blurb_image/", "embimg", $completeContent);
        $completeContent = preg_replace("/e_image_wrap/", "eimgwrap", $completeContent);
        $completeContent = preg_replace("/e_number_counter/", "enumcounter", $completeContent);
        $completeContent = preg_replace("/e_button/", "ebtn", $completeContent);
        $completeContent = preg_replace("/e_contact/", "ecntct", $completeContent);
        $completeContent = preg_replace("/e_divider_position_center/", "edpcen", $completeContent);

        $completeContent = preg_replace("/e_image_/", "eim_", $completeContent);
        $completeContent = preg_replace("/e_image/", "eim", $completeContent);
        $completeContent = preg_replace("/e_text_/", "etx_", $completeContent);
        $completeContent = preg_replace("/e_text/", "etx", $completeContent);
        $completeContent = preg_replace("/menu-item/", "mit", $completeContent);
        $completeContent = preg_replace("/page-container/", "p-c", $completeContent);
        $completeContent = preg_replace("/_wrapper/", "wpr", $completeContent);
        $completeContent = preg_replace("/e_equal_columns/", "eecl", $completeContent);
        if(preg_match('/<form\sclass="(.*?)"\smethod="post"\saction="(.*?)"(.*?)>/', $completeContent)){
            $completeContent = preg_replace('/<form\sclass="(.*?)"\smethod="post"\saction="(.*?)"(.*?)>/', '<form class="$1" method="post" action-xhr="$2"$3>', $completeContent);
        }
        if(preg_match('/<div class="ebtn(.*?)e_bg_layout_light(.*?)>/', $completeContent)){
        $completeContent = preg_replace('/<div class="ebtn(.*?)e_bg_layout_light(.*?)>/', '<div class="">', $completeContent);
        }
        if(preg_match('/<div class="ebtn(.*?)ebglaydark(.*?)>/', $completeContent)){
        $completeContent = preg_replace('/<div class="ebtn(.*?)ebglaydark(.*?)>/', '<div class="">', $completeContent);
        }
        //$completeContent = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(\d%{(.*?)}\d+%{(.*?))+}}/", "", $completeContent);
        $completeContent = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(.*?)}{2,}/", "", $completeContent);
        $completeContent = preg_replace("/\.cntn-wrp p,/s", "", $completeContent);

        $completeContent = preg_replace("/.e_slide:first-child .e_slideim amp-img{opacity:0}/", ".e_slide:first-child .e_slideim amp-img{opacity:1}", $completeContent);
        $completeContent = preg_replace("/.e_media_alignment_center .e_slideim{top:50%;bottom:auto}/", ".e_media_alignment_center .e_slideim{top:0;bottom:auto}", $completeContent);
        $completeContent = preg_replace("/.es_7{border-radius:0 0 0 16vw;overflow:hidden}/", ".es_7{border-radius:0 0 0 16vw;overflow:unset}", $completeContent);
//divi CSS optimization.
    $completeContent = preg_replace("/e_slide_description/", "e_sd", $completeContent);
    $completeContent = preg_replace("/slider_/", "sl_", $completeContent);
        
    $completeContent = preg_replace("/e_newsletter/", "e_nsl", $completeContent);
    $completeContent = preg_replace("/e_fullwidth/", "e_fw", $completeContent);
    $completeContent = preg_replace("/e_promo_button/", "e_pbtn", $completeContent);
    $completeContent = preg_replace("/e_promo_description/", "e_pdi", $completeContent);
    $completeContent = preg_replace("/e_media_alignment_center/", "e_alimnt", $completeContent);
    $completeContent = preg_replace("/e_css_mix_blend_mode_passthrough/", "e_cssmix", $completeContent);
    $completeContent = preg_replace("/et_section_regular/", "e_sere", $completeContent);
    $completeContent = preg_replace("/e_animation_to/", "e_anit", $completeContent);
    $completeContent = preg_replace("/e_with_background/", "e_wibd", $completeContent);
    $completeContent = preg_replace("/e_bg_layout_light/", "e_bg_ll", $completeContent);
    $completeContent = preg_replace("/box-shadow-overlay/", "bx-shol", $completeContent);
    $completeContent = preg_replace("/_description/", "_des", $completeContent);
    $completeContent = preg_replace("/e_social_media_follow/", "e_smf", $completeContent);
    $completeContent = preg_replace("/e_audio_module_content/", "e_admc", $completeContent);
    $completeContent = preg_replace("/e_audio_cover_art/", "e_adca", $completeContent);
    $completeContent = preg_replace("/e_post_slider/", "e_ps", $completeContent);
    $completeContent = preg_replace("/custom-row/", "c-rw", $completeContent);
    $completeContent = preg_replace("/e_subscribe/", "e-sbc", $completeContent);
    $completeContent = preg_replace("/e_slide_title/", "e_s_t", $completeContent);
    $completeContent = preg_replace("/e_slider/", "e_sl", $completeContent);
    $completeContent = preg_replace("/e_signup_/", "e_su_", $completeContent);
    $completeContent = preg_replace("/e_slide_content/", "e_s_c", $completeContent);
    $completeContent = preg_replace("/e_divider/", "e_dr", $completeContent);
    $completeContent = preg_replace("/es_parallax/", "e_plx", $completeContent);
    
    $completeContent = preg_replace("/e_smf_network_/", "e_m_n_", $completeContent);
    $completeContent = preg_replace("/e_testimonial/", "e_tmn", $completeContent);
    $completeContent = preg_replace("/efh_container/", "eh_cr", $completeContent);
   // $completeContent = preg_replace("/-webkit-box-sizing:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/display:-webkit-box;/", "", $completeContent);
    $completeContent = preg_replace("/display:-moz-box;/", "", $completeContent);
    $completeContent = preg_replace("/display:-ms-flexbox;/", "", $completeContent);
    $completeContent = preg_replace("/display:-webkit-flex;/", "", $completeContent);
    $completeContent = preg_replace("/display:-moz-flex;/", "", $completeContent);
    $completeContent = preg_replace("/display:-ms-flex;/", "", $completeContent);

    $completeContent = preg_replace("/-o-animation-timing-function:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-ms-animation-timing-function(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-animation-name:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-animation-name:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-ms-animation-name:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-o-animation-name:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-transition:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-transition:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-font-smoothing:(.*?);/", "", $completeContent);
    //$completeContent = preg_replace("/-moz-osx-font-smoothing:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-box-sizing:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-border-radius:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-border-radius:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-background-size:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-background-size:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-animation-timing-function:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-animation-timing-function:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-animation-duration:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-animation-duration:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-o-animation-duration:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-animation-delay:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-animation-delay:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-ms-animation-delay:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-o-animation-delay:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-animation-fill-mode:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-animation-fill-mode:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-ms-animation-fill-mode:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-o-animation-fill-mode:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-justify-content:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/webkit-align-self:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-flex-flow:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-webkit-flex-flow:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-ms-justify-content:(.*?);/", "", $completeContent);
    $completeContent = preg_replace("/-moz-justify-content:(.*?);/", "", $completeContent);
    $completeContent = preg_replace('/\.ecnt{max-width\:1100px}/', '' , $completeContent);
    if(preg_match('/<div class="em_inner">\s+<div class="em eb eb_(.*?) et_clickable(.*?)>/', $completeContent)){
    $completeContent = preg_replace('/<div class="em_inner">\s+<div class="em eb eb_(.*?) et_clickable(.*?)>/', '<div class="em_inner"> <div class="em eb et_clickable$2>' , $completeContent);
    }
    if(preg_match('/<amp-iframe(.*?)height="400" (.*?)layout="fixed-height"(.*?)>/', $completeContent)){
        $completeContent = preg_replace('/<amp-iframe(.*?)height="400" (.*?)layout="fixed-height"(.*?)>/', '<amp-iframe$1height="600" $2layout="fixed-height"$3>' , $completeContent);
    }

    if(preg_match('/<(.*?)class="em efh(.*?)ebglaydark e_fullscreen">/', $completeContent)){
        $completeContent = preg_replace('/<(.*?)class="em efh(.*?)ebglaydark e_fullscreen">/', '<$1 class="em efh ebglaydark e_fullscreen">' , $completeContent,1);
    }

    if(preg_match('/<div class="(.*?)\s+er_fullwidth">/', $completeContent)){
        $completeContent = preg_replace('/<div class="(.*?)\s+er_fullwidth">/', '<div class="$1 er_fullwidth" id="scrollElement">' , $completeContent,1);
    }
    if(function_exists('is_product') && is_product() && preg_match('/<div class="e_code_inner">/s', $completeContent) && preg_match('/<div class="btn_open">/', $completeContent)){
        require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/divi/amp-custom-product-tabs.php";
    }

    //$completeContent = preg_replace('/layout="intrinsic"/', 'layout="responsive"' , $completeContent);
    //$completeContent = preg_replace("/z-index:9;position:relative/", "", $completeContent);
//end here
    $completeContent = preg_replace('/<amp-img(.*?)srcset=""(.*?)><\/amp-img>/', '<amp-img$1$2></amp-img>' , $completeContent);
        if(pagebuilder_for_amp_utils::get_setting('divi-themebuilder_header')){
            $completeContent = preg_replace("/<header(.*?)class=\"header[-|0-9]* h_m h_m_1\">(.*?)<\/header>/s", "", $completeContent);
        }
        if(pagebuilder_for_amp_utils::get_setting('divi-themebuilder_footer')){
            $completeContent = preg_replace("/<footer\sclass=\"footer\">(.*?)<\/footer>/si", "", $completeContent);
        }
        if( pagebuilder_for_amp_utils::get_setting('divi-remove_header_footer' ) ) {
            $completeContent = preg_replace("/<header(.*?)class=\"header[-|0-9]* h_m h_m_1\">(.*?)<\/header>/s", "", $completeContent);
            $completeContent = preg_replace("/<footer\sclass=\"footer\">(.*?)<\/footer>/si", "", $completeContent);
        }
    $completeContent = preg_replace('/<form\srole="search"(.*?)class="et-search-form(.*?)"(.*?)>/','<form role="search"$1class="et-search-form$2"$3 target="_top">', $completeContent);
    $completeContent = apply_filters("amp_pc_divi_css_sorting", $completeContent);
    if ( class_exists ( 'ET_Builder_Plugin' ) ) {
        $completeContent = preg_replace("/\.et-db #et-boc \.et-l/", "", $completeContent);
        $completeContent = preg_replace('/<div\s+id="et_builder_outer_content"\s+class="et_builder_outer_content">/', "", $completeContent);
        $divi_buil_url = plugins_url('divi-builder');
    $completeContent = preg_replace('/font-display:swap;src:url\("(.*?)wp-content\/themes\/(.*?)\/core\/admin\/fonts\/(.*?)"\);/', 'font-display:swap;src:url("'.$divi_buil_url.'/core/admin/fonts/$3");' , $completeContent);
    $completeContent = preg_replace('/format\("embedded-opentype"\),url\("(.*?)core\/admin\/fonts\/(.*?)"\)/', 'format("embedded-opentype"),url("'.$divi_buil_url.'/core/admin/fonts/$2")' , $completeContent);
    $completeContent = preg_replace('/<\/div>\s+<div class="amp-author ">/', ' <div class="amp-author ">' , $completeContent);
    }   
    // if( is_plugin_active('divi-upload-icons-awb/divi-upload-icons-awb.php')){
    //   $completeContent = preg_replace('/<span data-icon=(.*?)icon~\|(.*?)" class="(.*?)et-pb-icon">(.*?)<\/span>/', '<span data-icon=$1icon~|$2" class="$3et-pb-icon">$2</span>' , $completeContent);
    // }
    if( function_exists('custom_class_diui_awb')){
    $completeContent = preg_replace('/<span data-icon="(.*?)\|spacewell-custom-icons-outlined~\|(.*?)\|(.*?)"\s+class=" e_anitp et-pb-icon">(.*?)\|spacewell-custom-icons-outlined~(.*?)|<\/span>/', '<span data-icon="$3" class="et-pb-icon diui_awb_icon--spacewell-custom-icons-outlined">$3', $completeContent);
    $completeContent = preg_replace('/<span data-icon="(.*?)" class="et-pb-icon diui_awb_icon--spacewell-custom-icons-outlined">(.*?)\|(.*?)<\/span>/', '<span data-icon="$1" class="et-pb-icon diui_awb_icon--spacewell-custom-icons-outlined">$2</span>', $completeContent);

        if(preg_match('/data-icon="~\|elegant-themes~\|elegant-themes-icon~\|(.*?)"/', $completeContent)){
            $completeContent = preg_replace('/data-icon="~\|elegant-themes~\|elegant-themes-icon~\|(.*?)"/', 'data-icon="$1"', $completeContent);
        }
    }
    $completeContent = preg_replace('/<div\sclass="er\ser_(\d+)\seecl\segs(\d+)">(.*?)<div\sclass="ec\sec_(\d+)_(\d+)\sec_(.*?)e_cssmix">/s', '<div class="er er_$1 eecl egs$2 er_$5col">$3<div class="ec ec_$4_$5 ec_$6e_cssmix">', $completeContent);

    if (class_exists('DGWT_WC_Ajax_Search')) {
        if (preg_match('/<form class="dgwt-wcas-search-form(.*?)"(.*?)>/', $completeContent)) {
            $completeContent = preg_replace('/<form class="dgwt-wcas-search-form(.*?)"(.*?)>/', '<form class="dgwt-wcas-search-form$1"$2 target="_top">', $completeContent);
        }
    }

           //$completeContent = preg_replace("/\.clearfix\{ clear:both \}/", "", $completeContent);
        
        return $completeContent;
    }


    function ampforwp_body_class_divi($classes){
        $curr_theme = wp_get_theme();
        if ( 'Extra' == $curr_theme->name || 'Extra' == $curr_theme->parent_theme ) {
        if(function_exists('et_layout_body_class') && function_exists('et_divi_theme_body_class') && function_exists('et_add_wp_version')) {
        $classes = et_layout_body_class($classes);
        $classes = et_divi_theme_body_class($classes);
        $classes = et_add_wp_version($classes);
        }
    }
    else{
        if(function_exists('et_layout_body_class') || function_exists('et_divi_theme_body_class') || function_exists('et_add_wp_version')) {
        $classes = et_layout_body_class($classes);
        $classes = et_divi_theme_body_class($classes);
        $classes = et_add_wp_version($classes);
        }   
    }
        $key = array_search('et_fixed_nav', $classes);
        $classes = array_filter($classes);
        $classes = array_unique($classes);
        if(isset($classes[$key])){
            unset($classes[$key]);
        }
        $key = array_search('single-post', $classes);
        if(isset($classes[$key])){
            unset($classes[$key]);
        }
        if(ampforwp_is_front_page()){
            $classes[] = 'home';
        }
        if(is_archive()){
            array_push($classes,'category');
        }
        $classes[] = 'et-db';
        $classes[] = 'et_button_no_icon ';
        return $classes;
    }
    
    function amp_pbc_load_fonts(){
        if(defined('AMP_WC_PLUGIN_URI')){?>
            @font-face{font-family:star;src:url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/star.eot';?>");src:url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/star.eot?#iefix';?>") format('embedded-opentype'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/star.woff';?>") format('woff'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/star.ttf';?>") format('truetype'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/star.svg#star';?>") format('svg');font-weight:400;font-style:normal}
            @font-face{font-family:WooCommerce;src:url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/WooCommerce.eot';?>");src:url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/WooCommerce.eot?#iefix';?>") format('embedded-opentype'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/WooCommerce.woff';?>") format('woff'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/WooCommerce.ttf';?>") format('truetype'),url("<?php echo AMP_WC_PLUGIN_URI.'/assets/fonts/WooCommerce.svg#WooCommerce';?>") format('svg');font-weight:400;font-style:normal};
    <?php
        }
    }

    public function amp_divi_custom_styles(){
        global $post, $wp_styles;
        //From DIVI
        global $shortname;
        if(is_object($post)){
        $postID = $post->ID;
        }
         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }

        
        $layouts = amp_et_theme_builder_get_template_layouts();
        $template_id = isset($layouts['et_body_layout']['id'])? $layouts['et_body_layout']['id']: '';
        if ( ('on' !== get_post_meta( $postID, '_et_pb_use_builder', true ) ) && !is_archive() && 'et_body_layout' !== get_post_type( $template_id )) {
            return ;
        }
        //require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'amp_vc_shortcode_styles.php';
        $css = '';
        $srcs = array();
        $style_suffix  = function_exists('et_load_unminified_styles') && et_load_unminified_styles() && ! is_child_theme() ? '.dev' : '';
        if(is_child_theme()){
            $parent_theme_css = get_template_directory_uri() . '/style' . $style_suffix . '.css';
            if (function_exists('et_is_builder_plugin_active') &&
                ! et_is_builder_plugin_active() && 
                is_child_theme() 
                ){
                $parent_theme_css = get_template_directory_uri() . '/style.dev.css';
            }
            $srcs[] = $parent_theme_css;
        }
        $srcs[] = get_template_directory_uri().'/style-static.min.css';
        $theme = wp_get_theme();
        if ( 'Divi' == $theme->name || 'Divi' == $theme->parent_theme ) {
        $srcs[] = get_stylesheet_directory_uri() . '/style' . $style_suffix . '.css';        
        }
        if ( 'Extra' == $theme->name || 'Extra' == $theme->parent_theme ) {
        $srcs[] = get_stylesheet_directory_uri() . '/style' . $style_suffix . '.css';
        }
        if ( 'Success Minds' == $theme->name || 'Success Minds' == $theme->parent_theme ) {
            $srcs[] = get_stylesheet_directory_uri() . '/style.css';
        }
        if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || ( class_exists ( 'ET_Builder_Plugin' ) ) ) {
        $srcs[] = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/includes/divi/divi-builder-style.css';
        }
        if( function_exists('ampwcpro_layout_selector') && ampwcpro_layout_selector() == 'v2layout' ){
        if ( !defined('DIR') ) { define('DIR', dirname(__FILE__)); }
        $srcs[] =  plugin_dir_url(__DIR__).'woocommerce/assets/css/woocommerce-layout.css';
        $srcs[] =  plugin_dir_url(__DIR__).'woocommerce/assets/css/woocommerce.css?ver=3.7.0';
    }        
    if(class_exists('AMP_ET_Builder_Module_Countdown_Timer')){
        $srcs[] =  plugin_dir_url(__DIR__).'amp-pagebuilder-compatibility/assets/style.css';
    }
    if( is_plugin_active('divi-upload-icons-awb/divi-upload-icons-awb.php')){
        $get_s_u = get_site_url( );
    $srcs[] =  $get_s_u.'/wp-content/uploads/divi-uploaded-icons-diui-awb/style.css';
    }
    if (is_plugin_active('dp-divi-filtergrid/dp-divi-filtergrid.php')) {
        $get_s_u = get_site_url( );
        $srcs[] = $get_s_u.'/wp-content/plugins/dp-divi-filtergrid/styles/style.min.css';
    }
        if(!empty(pagebuilder_for_amp_utils::get_setting_data('diviCssKeys') ) ){
            
            $update_css = pagebuilder_for_amp_utils::get_setting_data('diviCssKeys');
            $csslinks = explode(",", $update_css);
            if(count($csslinks)){
                $csslinks = array_filter($csslinks);
                $srcs = array_merge($srcs, $csslinks);
            }
        }
        $srcs[] = $this->getPostPageSpecificCss($post, $postID);

    // get inline css if et_pb_css_in_footer is on.
    if(class_exists('ET_Builder_Element')){
        $builder_body_layout = amp_et_theme_builder_get_template_layouts();
        $builder_body_layout_id = isset($builder_body_layout[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ])? $builder_body_layout[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['id']: '';
        if ( is_singular() && ! et_core_is_fb_enabled() ) {
                $result = ET_Builder_Element::setup_advanced_styles_manager( ET_Post_Stack::get_main_post_id() );
        }else{
                $result = ET_Builder_Element::setup_advanced_styles_manager( $builder_body_layout_id );
        }
        $builder_in_footer = et_get_option( 'et_pb_css_in_footer', 'off' );
        $disabled_global = et_get_option( 'et_pb_static_css_file', 'on' );
        $manager = $result['manager'];
        if(!$manager->has_file() && $builder_in_footer == 'on' && $disabled_global == 'on'){
                $upload_folder_path = wp_get_upload_dir();
                $srcs[] = $upload_folder_path['baseurl'].'/pb_compatibility/amp-et-builder-inline-'.get_the_ID().'-'.get_post_type().'-style.css';
        }
    }

        if(is_object($wp_styles)){
            foreach( $wp_styles->queue as $style ) :
                $src = $wp_styles->registered[$style]->src;
                if(filter_var($src, FILTER_VALIDATE_URL) === FALSE){
                    continue;
                }
                $srcs[$style] = $src;
            endforeach;
        }

        if(count($srcs)>0){
            $srcs = array_unique($srcs);
        }

        if(is_array($srcs) && count($srcs)){
            foreach ($srcs as $key => $valuesrc) {
                $valuesrc = trim($valuesrc);
                // if( filter_var($valuesrc, FILTER_VALIDATE_URL) === FALSE ){
                //     continue;
                // }
                $cssData = '';    
                $cssData = $this->ampforwp_remote_content($valuesrc);              
                
                $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
                $css .= preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($valuesrc){
                    $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
                        if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                            $urlExploded = explode("/", $valuesrc);
                            $parentUrl = str_replace(end($urlExploded), "", $valuesrc);
                            return 'url('.$parentUrl."/".$matches[1].")"; 
                        }else{
                            return $matches[0];
                        }
                    }, $cssData);
            }
        }
        $custom_css = et_get_option( "{$shortname}_custom_css" );
        if ( !empty( $custom_css ) ){
            $css .=  $custom_css;
        }
        $curr_theme = wp_get_theme();
        if ( $curr_theme->name !== 'Extra') {
        $stylesContent = ET_Builder_Element::get_style() . ET_Builder_Element::get_style( true );
        }
        if ( et_core_is_builder_used_on_current_request() ) {
            $stylesContent .= et_pb_get_page_custom_css();
        }
        $css .= $stylesContent;
        if(!empty(pagebuilder_for_amp_utils::get_setting_data('diviCss-custom') ) ){
            $css .= pagebuilder_for_amp_utils::get_setting_data('diviCss-custom');
        }
        if( function_exists('wp_get_custom_css') ){
            $css .= wp_get_custom_css();
        }

        $css = str_replace(array(" img", " video", "!important"), array(" amp-img", " amp-video", ""), $css);

        //For et module amp
        $css = preg_replace(
                            array(
                                '/url\(core\/admin\/fonts\/modules.eot\)/si',
                                '/url\(core\/admin\/fonts\/modules.eot\?#iefix\)/si',
                                '/url\(core\/admin\/fonts\/modules.woff\)/si',
                                '/url\(core\/admin\/fonts\/modules.svg#ETmodules\)/si',

                                ), 
                            array(
                                'url('.get_template_directory_uri().'/core/admin/fonts/modules.eot'.')',
                                'url('.get_template_directory_uri().'/core/admin/fonts/modules.eot?#iefix'.')',
                                'url('.get_template_directory_uri().'/core/admin/fonts/modules.woff'.')',
                                'url('.get_template_directory_uri().'/core/admin/fonts/modules.svg#ETmodules'.')',
                            ), $css);
        $css = preg_replace_callback('/url[(](.*?)[)]/', function($matches){
            $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
            if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                return 'url('.get_template_directory_uri()."/".$matches[1].")"; 
            }else{
                return $matches[0];
            }
        }, $css);
        if (class_exists('AGS_Divi_Icons_Pro')) {
            $upload_dir = wp_upload_dir();
            $css .= '*[data-icon^=\'agsdix-smc-\'][data-icon$=\'-1\']:before {
                        background-image: url('.$upload_dir['baseurl'].'/aspengrove-icons/multicolor-1.svg);
                    }
                    body *[data-icon^=\'agsdix-smc\']:before, body .ebtn[data-icon^=\'agsdix-smc\']:after {
                        background-size: 52em;
                        background-repeat: no-repeat;
                        color: rgba(0,0,0,0);
                    }';
        }
        echo $css.'.e_gallery_fullwidth .e_gallery_item:first-child {position: absolute;
}.e_gallery_0 {width: 83%;max-width: 72%;}.e_gallery_0.e_gallery amp-carousel {max-width: 88%;margin: 0 auto;}@media screen and (max-width: 600px) {.e_gallery .e_gallery_items {max-width: 145%;}.e_gallery_0 {width: 100%;max-width: 100%;}.e_slide_with_image .e_sd{width:100%;}}.e_sl .e_slide{margin-right:0%;}.em.e_video_slider.e_video_sl_0 {margin: -20.25% 0px -20.25% 0px;}
@media only screen and (min-width: 767px) {.es_video_bg amp-video {left: 50%;width: 1701.33px;height: 957px;min-width: 0px;max-width: none;margin: 0px 0px 0px -865.5px;}}
@media only screen and (max-width: 767px) {.es_video_bg amp-video {max-width: none;width: 881px;height: 500px;min-width: 0px;margin: 0px 0px 0 -450px;left: 50%;position: absolute;max-width: none;}}
body .et-boc .er .star-rating::before { content: "\73ssss"; color: #d3ced2; float: left; top: 0; left: 0; position: absolute; font-family: star; } body .product .star-rating span::before { content: "\53SSSS"; top: 0; position: absolute; left: 0; font-family: star; } body .product .star-rating span::before{ color: #f29c2a; } body .product .star-rating span { width: inherit; overflow: hidden; float: left; top: 0; left: 0; position: absolute; padding-top: 1.5em; } body .star-rating { float: right; overflow: hidden; position: relative; height: 1em; line-height: 1; font-size: 1em; width: 5.4em; font-family: star; } body .content-wrapper .e_wc_reviews.em .s-r:before { color: #ccc; }
.ebtn.e_custom_button_icon:after {
   line-height: inherit;
    font-size: inherit;
    margin-left: -1em;
    left: auto;
    display: inline-block;
    opacity: 0;
    content: attr(data-icon);
    font-family: "ETmodules";}
    .embimg {
        display: block!important;
    }';
    }

    public function get_amp_et_inline_css_file(){
        if(class_exists('ET_Builder_Element')){
            $builder_body_layout = amp_et_theme_builder_get_template_layouts();
            $builder_body_layout_id = isset($builder_body_layout[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ])?$builder_body_layout[ ET_THEME_BUILDER_BODY_LAYOUT_POST_TYPE ]['id']: '';
            $builder_in_footer = et_get_option( 'et_pb_css_in_footer', 'off' );
            $disabled_global = et_get_option( 'et_pb_static_css_file', 'on' );
            if($builder_in_footer == 'on' && $disabled_global == 'on'){

            if ( is_singular() && function_exists('et_core_is_builder_used_on_current_request') && et_core_is_builder_used_on_current_request() ) {
                    $result = ET_Builder_Element::setup_advanced_styles_manager( ET_Post_Stack::get_main_post_id() );
                }else{
                    $result = ET_Builder_Element::setup_advanced_styles_manager( $builder_body_layout_id );
                }

                $manager = $result['manager'];
                if(!$manager->has_file()){
                  $styles = et_pb_get_page_custom_css( $builder_body_layout_id ) . ET_Builder_Element::get_style( false, $builder_body_layout_id ) . ET_Builder_Element::get_style( true, $builder_body_layout_id );
                }

                if ( $styles && !$manager->has_file()){
                    $upload_dir = wp_upload_dir(); 
                    $base_dir = wp_get_upload_dir(); 
                    $file = file_put_contents($upload_dir['basedir']."/pb_compatibility/amp-et-builder-inline-".get_the_ID()."-".get_post_type()."-style.css", strip_tags( $styles ) );
                        move_uploaded_file($file, $upload_dir['baseurl']);
                }
            }
        }
    }

    public function ampforwp_remote_content($src){
        if($src){
            $arg = array( "sslverify" => false, "timeout" => 60 ) ;
            $response = wp_remote_get( $src, $arg );
            if ( wp_remote_retrieve_response_code($response) == 200 && is_array( $response ) ) {
              $header = wp_remote_retrieve_headers($response); // array of http header lines
              $contentData =  wp_remote_retrieve_body($response); // use the content
              return $contentData;
            }elseif(!(($contentData = @file_get_contents( $src )) === false)){
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

    public function getPostPageSpecificCss($post, $post_id){
        //From DIVI
        global $shortname;
        ET_Core_PageResource::startup();

        $post_id  = $post_id ? $post_id : et_core_page_resource_get_the_ID();

        $is_preview     = is_preview() || isset( $_GET['et_pb_preview_nonce'] ) || is_customize_preview(); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
        $is_singular    = function_exists('et_core_page_resource_is_singular')? et_core_page_resource_is_singular() : false;

        $disabled_global = 'off' === et_get_option( 'et_pb_static_css_file', 'on' );
        $disabled_post   = $disabled_global || ( $is_singular && 'off' === get_post_meta( $post_id, '_et_pb_static_css_file', true ) );

        $forced_inline     = $is_preview || $disabled_global || $disabled_post || post_password_required();
        $builder_in_footer = 'on' === et_get_option( 'et_pb_css_in_footer', 'off' );

        $unified_styles = $is_singular && ! $forced_inline && ! $builder_in_footer && et_core_is_builder_used_on_current_request();
        $unified_styles = true;
        $owner = $unified_styles ? 'core' : $shortname;
        $slug  = $unified_styles ? 'unified' : 'customizer';
        if ( function_exists( 'et_fb_is_enabled' ) && et_fb_is_enabled() ) {
            $slug .= '-vb';
        }


        $slug           = sanitize_text_field( $slug );
        $global         = 'global' === $post_id ? '-global' : '';
        $filename = "et-{$owner}-{$slug}{$global}";

        $file_extension = '.min.css';
        $absolute_path  = ET_Core_PageResource::get_cache_directory();
        $relative_path  = ET_Core_PageResource::get_cache_directory( 'relative' );
        $files = glob( $absolute_path . "/{$post_id}/{$filename}-[0-9]*{$file_extension}" );
        $cache_dir = $absolute_path;
        $files = array_merge(
            (array) glob( "{$cache_dir}/et-{$owner}-*" ),
            (array) glob( "{$cache_dir}/{$post_id}/et-{$owner}-*" ),
            (array) glob( "{$cache_dir}/*/et-{$owner}-*-tb-{$post_id}-*" ),
            (array) glob( "{$cache_dir}/*/et-{$owner}-*-tb-for-{$post_id}-*" )
        );

        if ( $files ) {
            // Static resource file exists
            $file           = array_pop( $files );
            $PATH     = ET_Core_PageResource::$data_utils->normalize_path( $file );
            $BASE_DIR = dirname( $PATH );

            $start     = strpos( $PATH, 'cache/et' );
            $URL = $PATH;//content_url( substr( $PATH, $start ) );

            if ( $files ) {
                // Somehow there are multiple files for this resource. Let's delete the extras.
                foreach ( $files as $extra_file ) {
                    ET_Core_Logger::debug( 'Removing extra page resource file: ' . $extra_file );
                    @ET_Core_PageResource::$wpfs->delete( $extra_file );
                }
            }

        } else {
            // Static resource file doesn't exist
            $time = (string) microtime( true );
            $time = str_replace( '.', '', $time );

            $relative_path .= "/{$post_id}/{$filename}-{$time}{$file_extension}";
            $absolute_path .= "/{$post_id}/{$filename}-{$time}{$file_extension}";

            $BASE_DIR = ET_Core_PageResource::$data_utils->normalize_path( dirname( $absolute_path ) );
            $TEMP_DIR = $BASE_DIR . "/{$slug}~";
            $PATH     = $absolute_path;
            $URL      = is_plugin_active( 'all-404-redirect-to-homepage/all-404-redirect-to-homepage.php' ) ? $relative_path : content_url( $relative_path );
        }
        return $URL;
    }

    public function amp_divi_pagebuilder_font_link(){
        global $post;
        if(is_object($post)){
        $postID = $post->ID;
        }
         if ( function_exists('ampforwp_is_front_page') && ampforwp_is_front_page() ) {
            $postID = ampforwp_get_frontpage_id();
        }
        $divi_enabled = false;
        if ( 'on' === get_post_meta( $postID, '_et_pb_use_builder', true ) ) {
            $divi_enabled = true;
        }
        if($divi_enabled && pagebuilder_for_amp_utils::get_setting_data('divi_fontawesome_support')==1 ){ ?>

        <link rel='stylesheet' id='font-awesome-css'  href='https://use.fontawesome.com/releases/v5.8.1/css/all.css' type='text/css' media='all' />
        <?php }    
        $this->et_builder_preprint_font();
        if (function_exists('et_divi_fonts_url')){
        $fontUrl = et_divi_fonts_url();
        }
        elseif (function_exists('et_core_get_main_fonts') || class_exists('ET_Builder_Plugin')) {
            $fontUrl = et_core_get_main_fonts();
        }
        else{
           $fontUrl= '';   
        }
        if($fontUrl || class_exists ( 'ET_Builder_Plugin' ) && !class_exists('\ElementorPro\Plugin')){
        echo '<link rel="stylesheet" id="divi-fonts-css" href="'.esc_url( str_replace("http:", "https:", $fontUrl).'&display=swap').'">';
        }
    }
    
    public function et_builder_preprint_font() {
        // Return if this is not a post or a page
        if(function_exists('et_core_use_google_fonts')){
            if ( ! is_singular() || ! et_core_use_google_fonts() ) {
                return;
            }   
        }else{
            if ( ! is_singular() ){
                return;
            }
        }

        $post_id = get_the_ID();

        $post_fonts_data = get_post_meta( $post_id, 'et_enqueued_post_fonts', true );

        // No need to proceed if the proper data is missing from the cache
        if ( ! is_array( $post_fonts_data ) || ! isset( $post_fonts_data['family'], $post_fonts_data['subset'] ) ) {
            return;
        }

        $fonts = $post_fonts_data[ 'family'];

        if ( ! $fonts ) {
            return;
        }

        $unique_subsets = $post_fonts_data[ 'subset'];
        $protocol       = 'https';

        echo '<link rel="stylesheet" id="et-builder-googlefonts-cached" href="'.esc_url( add_query_arg( array(
            'family' => implode( '|', $fonts ) ,
            'subset' => implode( ',', $unique_subsets ).'&display=swap',
        ), "$protocol://fonts.googleapis.com/css" ) ).'">';
    }

    public function amp_divi_pagebuidler_override(){
        if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
            $dsm_supreme_modules = '';
            if(function_exists('run_dsm_supreme_modules_for_divi')){
                $dsm_supreme_modules = 'TypingEffect.php';
            }
            if ( class_exists( 'ET_Builder_Module' ) ) {
                $filesArray = array('Accordion.php', 'AmpNumberCounter.php','BarCounters.php', 'BarCountersItem.php', 'CountdownTimer.php',  'Map.php', 'FullwidthMap.php' , 'Video.php', 'ContactFormItem.php', 'ContactForm.php', 'Tabs.php', 'TabsItem.php', 'FilterablePortfolio.php','Toggle.php','Code.php','NumberCounters.php', 'signup.php', 'slider.php', 'fullwidthpostslider.php','Button.php','shop.php','Gallery.php','Blog.php','Fullwidthslideramp.php','Blurb.php','CircleCounter.php','VideoSlider.php','VideoSliderItem.php','PostSlider.php','Search.php', 'fullwidthHeader.php',$dsm_supreme_modules,'Text.php', 'Testimonial.php');//'MapItem.php',
                foreach ($filesArray as $key => $value) {
                    if(!empty($value)){
                        if(file_exists(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/divi/".$value)){
                            require_once AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR."/includes/divi/".$value;
                        }
                    }
                }
            }
        }
    }

    public function amp_divi_pbc_replace_modules($classlist){
        if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
            $classlist['et_pb_accordion'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Accordion',);
            $classlist['et_pb_number_counter'] = array( 'classname' => 'AMP_ET_Builder_Module_Number_Counter',);
            $classlist['et_pb_counters'] = array( 'classname' => 'AMP_ET_Builder_Module_Bar_Counters',);
            $classlist['et_pb_counter'] = array( 'classname' => 'AMP_ET_Builder_Module_Bar_Counters_Item',);
            $classlist['et_pb_blog'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Blog',);
            $classlist['et_pb_blurb'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Blurb',);
            $classlist['et_pb_button'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Button',);
            $classlist['et_pb_circle_counter'] = array( 'classname' => 'AMP_ET_Builder_Module_Circle_Counter',);
            $classlist['et_pb_code'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Code',);
            $classlist['et_pb_contact_form'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Contact_Form',);
            $classlist['et_pb_contact_field'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Contact_Form_Item',);
            $classlist['et_pb_countdown_timer'] = array( 'classname' => 'AMP_ET_Builder_Module_Countdown_Timer',);
            $classlist['et_pb_filterable_portfolio'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Filterable_Portfolio',);
            $classlist['et_pb_fullwidth_header'] = array( 'classname' => 'AMP_ET_Builder_Module_Fullwidth_Header',);
            $classlist['et_pb_fullwidth_map'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Fullwidth_Map',);
            $classlist['et_pb_fullwidth_post_slider'] = array( 'classname' => 'AMP_ET_Builder_Module_Fullwidth_Post_Slider',);
            $classlist['et_pb_fullwidth_slider'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Fullwidth_Slider',);
            $classlist['et_pb_gallery'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Gallery',);
            if(class_exists('AMP_PC_ET_Builder_Module_Image')){
                $classlist['et_pb_image'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Image',);
            }
            $classlist['et_pb_map'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Map',);
            $classlist['et_pb_map_pin'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Map_Item',);
            $classlist['et_pb_number_counter'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Number_Counter',);
            $classlist['et_pb_post_slider'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Post_Slider',);
            $classlist['et_pb_search'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Search',);
            $classlist['et_pb_shop'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Shop',);
            $classlist['et_pb_signup'] = array( 'classname' => 'AMP_ET_Builder_Module_Signup',);
            $classlist['et_pb_slider'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Slider',);
            $classlist['et_pb_tabs'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Tabs',);
            $classlist['et_pb_tab'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Tabs_Item',);
            $classlist['et_pb_testimonial'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Testimonial',);
            $classlist['et_pb_text'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Text',);
            $classlist['et_pb_toggle'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Toggle',);
            $classlist['dsm_typing_effect'] = array( 'classname' => 'AMP_PC_ET_DSM_TypingEffect',);
            $classlist['et_pb_video'] = array( 'classname' => 'AMP_ET_Builder_Module_Video',);
            $classlist['et_pb_video_slider'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Video_Slider',);
            $classlist['et_pb_video_slider_item'] = array( 'classname' => 'AMP_PC_ET_Builder_Module_Video_Slider_Item',);
        }
           return $classlist;
    }

    public static function et_builder_convert_line_breaks( $content, $line_breaks_format = "\n"  ) {

        // before we swap out the placeholders,
        // remove all the <p> tags and \n that wpautop added!
        $content = preg_replace( '/\n/smi', '', $content );
        $content = preg_replace( '/<p>/smi', '', $content );
        $content = preg_replace( '/<\/p>/smi', '', $content );

        $content = str_replace( array( '<!– [e_line_break_holder] –>', '<!-- [e_line_break_holder] -->', '||e_line_break_holder||' ), $line_breaks_format, $content );
        $content = str_replace( '<!–- [e_br_holder] -–>', '<br />', $content );

        // convert the <pee tags back to <p
        // see et_pb_prep_code_module_for_wpautop()
        $content = str_replace( '<pee', '<p', $content );
        $content = str_replace( '</pee>', '</p> ', $content );

        return $content;
    }
    
}


/**
* Admin section portal Access
**/
add_action('plugins_loaded', 'pagebuilder_for_amp_divi_option');
function pagebuilder_for_amp_divi_option(){
    if(is_admin()){
        new pagebuilder_for_amp_divi_Admin();
    }else{
        // Instantiate AMP_PC_Divi_Pagebuidler.
        $diviAmpBuilder = new AMP_PC_Divi_Pagebuidler();
    }
    if ( defined( 'DOING_AJAX' ) ) {
        AMP_PC_Divi_Pagebuidler::load_ajax_calls();
    }
    
}
Class pagebuilder_for_amp_divi_Admin{
    function __construct(){
        add_filter( 'redux/options/redux_builder_amp/sections', array($this, 'add_options_for_divi'),7,1 );
    }
        public static function get_admin_options_divi($section = array()){
        $obj = new self();
        //print_r($obj);die;
        $section = $obj->add_options_for_divi($section);
        return $section;
    }
    function add_options_for_divi($sections){
        $desc = 'Enable/Activate Divi pagebuilder';
        $theme = wp_get_theme(); // gets the current theme
        if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || 'Divi' == $theme->name || 'Divi' == $theme->parent_theme || 'Extra' == $theme->name || 'Extra' == $theme->parent_theme ) {//Extra
            $desc = '';
        }
       // print_r( $sections[3]['fields']);die;
        $accordionArray = array();
        $sectionskey = 0;
        foreach ($sections as $sectionskey => $sectionsData) {
            if($sectionsData['id']=='amp-content-builder' &&  count($sectionsData['fields'])>0 ){
                foreach ($sectionsData['fields'] as $fieldkey => $fieldvalue) {
                    if($fieldvalue['id'] == 'ampforwp-divi-pb-for-amp-accor'){
                        $accordionArray = $sections[$sectionskey]['fields'][$fieldkey];
                         unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                    if($fieldvalue['id'] == 'ampforwp-divi-pb-for-amp'){
                        unset($sections[$sectionskey]['fields'][$fieldkey]);
                    }
                }
                break;
            }
        }
        $sections[$sectionskey]['fields'][] = $accordionArray;
        $sections[$sectionskey]['fields'][] = array(
                               'id'       => 'pagebuilder-for-amp-divi-support',
                               'type'     => 'switch',
                               'title'    => esc_html__('AMP Divi Compatibility ','accelerated-mobile-pages'),
                               'tooltip-subtitle' => esc_html__('Enable or Disable the Divi for AMP', 'accelerated-mobile-pages'),
                               'desc'     => $desc,
                               'section_id' => 'amp-content-builder',
                               'default'  => false
                            );
        foreach ($this->amp_divi_fields() as $key => $value) {
            $sections[$sectionskey]['fields'][] = $value;
        }
        

        return $sections;

    }

    public function amp_divi_fields(){
        $contents[] = array(
                        'id'       => 'diviCssKeys',
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
                        'id'       => 'diviCss-custom',
                        'type'     => 'textarea',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Enter Custom CSS', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Add your custom css code', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );

        $contents[] = array(
                        'id'       => 'divi_fontawesome_support',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Load fontawesome', 'amp-pagebuilder-compatibility'),
                        'desc'      => esc_html__( 'Load fontawesome library from CDN', 'amp-pagebuilder-compatibility' ),
                        'default'  => 0,
                        //'required'=> array(array('pagebuilder-for-amp-wpbakery-support','==', 1)),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'divi-themebuilder_header',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Divi Theme Builder Header', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( ' if you want to Show  themebuilder Header in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'divi-themebuilder-global-body',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Divi ThemeBuilder Body Content', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show themebuilder global body in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'divi-themebuilder_footer',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Divi Theme Builder Footer', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'tooltip-subtitle'      => esc_html__( 'Enable if you want to Show themebuilder Footer in AMP', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        $contents[] = array(
                        'id'       => 'divi-remove_header_footer',
                        'type'     => 'switch',
                        'class'    => 'child_opt child_opt_arrow',
                        'title'    => esc_html__('Remove Header & Footer', 'amp-pagebuilder-compatibility'),
                        'subtitle'  => esc_html__('', 'amp-pagebuilder-compatibility'),
                        'default'  => '',
                        'desc'      => esc_html__( 'Tick if you want to remove default header & footer', 'amp-pagebuilder-compatibility' ),
                         'section_id' => 'amp-content-builder',
                    );
        return $contents;
    }
}

add_filter('ampforwp_the_content_last_filter', 'amp_pbc_divi_wc_gallery', 11);
function amp_pbc_divi_wc_gallery($completeContent){
        $dom = new \DOMDocument();
        $dom->loadHTML($completeContent);
        $finder = new DomXPath($dom);
        $wc_gallery = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' e_wc_gallery ')]");
        $wc_gallery = $finder->query("//*[contains(@class, 'e_wc_gallery')]");
        for ($i=0; $i < $wc_gallery->length ; $i++) {
            if($wc_gallery){
                $elmnts = $wc_gallery->item($i)->childNodes->item(1);
                $chck_elmnts = $elmnts->getAttribute('class');
                if( $elmnts->hasChildNodes() && $elmnts->parentNode && strpos($chck_elmnts, 'e_gallery_items') !== false){
                    $amp_crsl = $dom->createElement('amp-carousel');
                    $amp_crsl->setAttribute('height','400px');
                    $amp_crsl->setAttribute('layout','fixed-height');
                    $amp_crsl->setAttribute('type','slides');
                    $elmnts->parentNode->insertBefore($amp_crsl, $elmnts);
                    $amp_crsl->appendChild($elmnts);
                        foreach ( $elmnts->childNodes as $child_node ) {
                            $new_child = $child_node->cloneNode( true );
                            $elmnts->parentNode->insertBefore( $new_child, $elmnts );
                        }
                        if ( $elmnts->parentNode ) {
                            $elmnts->parentNode->removeChild( $elmnts );
                        }
                }
            }
        }
    $completeContent = $dom->saveHTML();
    return $completeContent;
}