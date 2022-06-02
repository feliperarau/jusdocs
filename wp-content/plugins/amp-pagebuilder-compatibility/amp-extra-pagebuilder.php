<?php

add_filter('ampforwp_the_content_last_filter', 'ampforwp_classes_modification');
function ampforwp_classes_modification($content_buff){
    $content_buff = preg_replace('/page-container/', 'p-c', $content_buff);
    $content_buff = preg_replace("/et_animated\s/", "", $content_buff);
    $content_buff = preg_replace("/et-waypoint/", "", $content_buff);
    $content_buff = preg_replace("/et_pb_module_/", "em_", $content_buff);
    $content_buff = preg_replace("/et_pb_module/", "em", $content_buff);
    $content_buff = preg_replace("/et_pb_column_/", "ec_", $content_buff);
    $content_buff = preg_replace("/et_pb_column/", "ec", $content_buff);
    $content_buff = preg_replace("/et_pb_gutters/", "egs", $content_buff);
    $content_buff = preg_replace("/et_pb_gutter/", "eg_", $content_buff);
    $content_buff = preg_replace("/et_pb_blurb_/", "eb_", $content_buff);
    $content_buff = preg_replace("/et_pb_blurb/", "eb", $content_buff);
    
    $content_buff = preg_replace("/et_pb_section_/", "es_", $content_buff);
    $content_buff = preg_replace("/et_pb_section/", "es", $content_buff);

    $content_buff = preg_replace("/et_pb_row_/", "er_", $content_buff);
    $content_buff = preg_replace("/et_pb_row/", "er", $content_buff);
    
    $content_buff = preg_replace("/et_pb_preload/", "", $content_buff);
    $content_buff = preg_replace("/data-columns>/", ">", $content_buff);
    $content_buff = preg_replace("/et_pb_/", "e_", $content_buff);
    $content_buff = preg_replace("/em_header/", "em_hd", $content_buff);       
    $content_buff = preg_replace("/eb_content/", "ecnt", $content_buff);
    $content_buff = preg_replace("/eb_container/", "econt", $content_buff);
    $content_buff = preg_replace("/_text_align_left/", "txtal", $content_buff);
    $content_buff = preg_replace("/_bg_layout_dark/", "bglaydark", $content_buff);
    $content_buff = preg_replace("/e_contact_field/", "ecfield", $content_buff);
    $content_buff = preg_replace("/e_main_blurb_image/", "embimg", $content_buff);
    $content_buff = preg_replace("/e_image_wrap/", "eimgwrap", $content_buff);
    $content_buff = preg_replace("/e_button/", "ebtn", $content_buff);
    $content_buff = preg_replace("/e_contact/", "ecntct", $content_buff);
    $content_buff = preg_replace("/e_divider_position_center/", "edpcen", $content_buff);

    $content_buff = preg_replace("/e_image_/", "eim_", $content_buff);
    $content_buff = preg_replace("/e_image/", "eim", $content_buff);
    $content_buff = preg_replace("/e_text_/", "etx_", $content_buff);
    $content_buff = preg_replace("/e_text/", "etx", $content_buff);
    $content_buff = preg_replace("/menu-item/", "mit", $content_buff);
    $content_buff = preg_replace("/_wrapper/", "wpr", $content_buff);
    $content_buff = preg_replace("/e_equal_columns/", "eecl", $content_buff);
    if(preg_match('/<div class="ebtn(.*?)e_bg_layout_light(.*?)>/', $content_buff)){
    $content_buff = preg_replace('/<div class="ebtn(.*?)e_bg_layout_light(.*?)>/', '<div class="">', $content_buff);
    }
    if(preg_match('/<div class="ebtn(.*?)ebglaydark(.*?)>/', $content_buff)){
    $content_buff = preg_replace('/<div class="ebtn(.*?)ebglaydark(.*?)>/', '<div class="">', $content_buff);
    }
    //$content_buff = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(\d%{(.*?)}\d+%{(.*?))+}}/", "", $content_buff);
    $content_buff = preg_replace("/@(-moz-|-webkit-|-ms-)*keyframes\s\w+{(.*?)}{2,}/", "", $content_buff);
    $content_buff = preg_replace("/\.cntn-wrp p,/s", "", $content_buff);

    $content_buff = preg_replace("/.e_slide:first-child .e_slideim amp-img{opacity:0}/", ".e_slide:first-child .e_slideim amp-img{opacity:1}", $content_buff);
    $content_buff = preg_replace("/.e_media_alignment_center .e_slideim{top:50%;bottom:auto}/", ".e_media_alignment_center .e_slideim{top:0;bottom:auto}", $content_buff);
    $content_buff = preg_replace("/.es_7{border-radius:0 0 0 16vw;overflow:hidden}/", ".es_7{border-radius:0 0 0 16vw;overflow:unset}", $content_buff);
	//divi CSS optimization.
    $content_buff = preg_replace("/e_slide_description/", "e_sd", $content_buff);
    $content_buff = preg_replace("/slider_/", "sl_", $content_buff);
    
    $content_buff = preg_replace("/e_newsletter/", "e_nsl", $content_buff);
    $content_buff = preg_replace("/e_fullwidth/", "e_fw", $content_buff);
    $content_buff = preg_replace("/e_promo_button/", "e_pbtn", $content_buff);
    $content_buff = preg_replace("/e_promo_description/", "e_pdi", $content_buff);
    $content_buff = preg_replace("/e_media_alignment_center/", "e_alimnt", $content_buff);
    $content_buff = preg_replace("/e_css_mix_blend_mode_passthrough/", "e_cssmix", $content_buff);
    $content_buff = preg_replace("/et_section_regular/", "e_sere", $content_buff);
    $content_buff = preg_replace("/e_animation_to/", "e_anit", $content_buff);
    $content_buff = preg_replace("/e_with_background/", "e_wibd", $content_buff);
    $content_buff = preg_replace("/e_bg_layout_light/", "e_bg_ll", $content_buff);
    $content_buff = preg_replace("/box-shadow-overlay/", "bx-shol", $content_buff);
    $content_buff = preg_replace("/_description/", "_des", $content_buff);
    $content_buff = preg_replace("/e_social_media_follow/", "e_smf", $content_buff);
    $content_buff = preg_replace("/e_audio_module_content/", "e_admc", $content_buff);
    $content_buff = preg_replace("/e_audio_cover_art/", "e_adca", $content_buff);
    $content_buff = preg_replace("/e_post_slider/", "e_ps", $content_buff);
    $content_buff = preg_replace("/custom-row/", "c-rw", $content_buff);
    $content_buff = preg_replace("/e_subscribe/", "e-sbc", $content_buff);
    $content_buff = preg_replace("/e_slide_title/", "e_s_t", $content_buff);
    $content_buff = preg_replace("/e_slider/", "e_sl", $content_buff);
    $content_buff = preg_replace("/e_signup_/", "e_su_", $content_buff);
    $content_buff = preg_replace("/e_slide_content/", "e_s_c", $content_buff);
    $content_buff = preg_replace("/e_divider/", "e_dr", $content_buff);
    $content_buff = preg_replace("/e_smf_network_/", "e_m_n_", $content_buff);
    $content_buff = preg_replace("/e_testimonial/", "e_tmn", $content_buff);
    $content_buff = preg_replace("/efh_container/", "eh_cr", $content_buff);
    if(preg_match('/<div class="em_inner">\s+<div class="em eb eb_(.*?) et_clickable(.*?)>/', $content_buff)){
    $content_buff = preg_replace('/<div class="em_inner">\s+<div class="em eb eb_(.*?) et_clickable(.*?)>/', '<div class="em_inner"> <div class="em eb et_clickable$2>' , $content_buff);
    }
    $content_buff = apply_filters("amp_pc_divi_css_sorting", $content_buff);
    
    return $content_buff;
}