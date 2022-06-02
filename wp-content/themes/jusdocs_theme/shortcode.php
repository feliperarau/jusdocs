<?php
function miro_link_att($atts) {
    $default = array(
        'link' => '#',
    );
    $a = shortcode_atts($default, $atts);
    return '<iframe style="margin-bottom: 1rem" width="768" height="432" src="https://miro.com/app/live-embed/' . $a['link'] . '/?embedAutoplay=true" frameBorder="0" scrolling="no" allowFullScreen></iframe>';
;
}
add_shortcode('miro', 'miro_link_att');
?>