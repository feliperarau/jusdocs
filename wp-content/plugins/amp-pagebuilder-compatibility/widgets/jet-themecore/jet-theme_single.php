<?php 

add_filter('ampforwp_modify_the_content','amp_jet_themecore_get_single');

function amp_jet_themecore_get_single($content) {
    // condition
    if(class_exists('Jet_Theme_Core_Structure_Single')){
        $instance = new Jet_Theme_Core_Structure_Single();
    }
    if(class_exists( 'Jet_Theme_Core' ) && is_object($instance) && true === $instance->is_location()){
        ob_start();
        jet_theme_core()->locations->do_location('single');
        $this_is_jet_single = ob_get_contents();
        ob_clean();  
        if(!empty($this_is_jet_single) && class_exists('\AMPFORWP_Content')){
            $sanitizer_obj = new \AMPFORWP_Content( $this_is_jet_single,
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