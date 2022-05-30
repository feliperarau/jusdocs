<?php

if(!function_exists('jet_theme_core')){
    return;
}

if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_header')){
    add_action( 'ampforwp_after_header', 'amp_jet_themecore_get_header' );
}

function amp_jet_themecore_get_header() {
    ob_start();
    jet_theme_core()->locations->do_location('header');
    $this_is_jet_header = ob_get_contents();
    ob_clean();
    $mydom = new DomDocument();
    libxml_use_internal_errors(true);
    if( function_exists( 'mb_convert_encoding' ) ) {
        $this_is_jet_header = mb_convert_encoding($this_is_jet_header, 'HTML-ENTITIES', 'UTF-8');
    }
    @$mydom->loadHTML($this_is_jet_header);
    $finder = new DomXPath($mydom);

    $hambnodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' jet-hamburger-panel ')]");
    if(!empty ( $hambnodes->item(0) ) ){
    $hambnodesClass = $hambnodes->item(0)->getAttribute('class');
}
//return;
if( !empty( $hambnodes->item(0) ) ){
    $hambnodes->item(0)->setAttribute('class',$hambnodesClass.' open-state jet-menu-hide');
}

    $get_opntogl = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' jet-hamburger-panel__toggle ')]");
    $opn_jettogl = $get_opntogl->item(0);
    if(!empty($opn_jettogl)){
    $opn_jettogl->setAttribute('on','tap:AMP.setState({ jet_menu_hide: false })');
    $opn_jettogl->setAttribute('role','button');
    $opn_jettogl->setAttribute('tabindex','0');
}

    $get_clstogl = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' jet-hamburger-panel__close-button ')]");
    $cls_jettogl = $get_clstogl->item(0);
    if(!empty($cls_jettogl)){
    $cls_jettogl->setAttribute('on','tap:AMP.setState({ jet_menu_hide: true })');
    $cls_jettogl->setAttribute('role','button');
    $cls_jettogl->setAttribute('tabindex','0');
}

    $navmenu = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' jet-hamburger-panel__instance ')]");
    if(!empty($navmenu->item(0))){
    $navmenu->item(0)->setAttribute('hidden','');
    $navmenu->item(0)->setAttribute('::openbrack::hidden::closebrack::','jet_menu_hide');
    }   
    $this_is_jet_header = $mydom->savehtml();

    $this_is_jet_header = str_replace('::openbrack::', '[', $this_is_jet_header);
    $this_is_jet_header = str_replace('::closebrack::', ']', $this_is_jet_header);

    if(class_exists('\AMPFORWP_Content')){
        $sanitizer_obj = new \AMPFORWP_Content( $this_is_jet_header,
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
        echo $amp_sanitized_header_content;
    }
}

if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_footer')){
    add_action( 'amp_post_template_above_footer', 'amp_jet_themecore_get_footer' );
}

function amp_jet_themecore_get_footer() {
    ob_start();
    jet_theme_core()->locations->do_location('footer');
    $this_is_jet_footer = ob_get_contents();
    ob_clean();  
    if(class_exists('\AMPFORWP_Content')){
        $sanitizer_obj = new \AMPFORWP_Content( $this_is_jet_footer,
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
        echo $amp_sanitized_header_content;
    }
}

add_filter('amp_post_template_css', 'amp_jet_header_footer_css' ,999);

function amp_jet_header_footer_css(){
    echo '.jet-menu-hide.jet-hamburger-panel .jet-hamburger-panel__icon.icon-normal {
            display: block;
        }
        .jet-menu-hide.jet-hamburger-panel .jet-hamburger-panel__icon.icon-active {
            display: none;
        }';
}