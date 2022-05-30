<?php // [ux_slider]
function ux_slider_amp($atts, $content=null) {

    extract( shortcode_atts( array(
        '_id' => 'slider-'.rand(),
        'timer' => '6000',
        'bullets' => 'true',
        'visibility' => '',
        'class' => '',
        'type' => 'slide',
        'bullet_style' => '',
        'auto_slide' => 'true',
        'auto_height' => 'true',
        'bg_color' => '',
        'slide_align' => 'center',
        'style' => 'normal',
        'slide_width' => '',
        'arrows' => 'true',
        'pause_hover' => 'true',
        'hide_nav' => '',
        'nav_style' => 'circle',
        'nav_color' => 'light',
        'nav_size' => 'large',
        'nav_pos' => '',
        'infinitive' => 'true',
        'freescroll' => 'false',
        'parallax' => '0',
        'margin' => '',
        'columns' => '1',
        'height' => '',
        'rtl' => 'false',
        'draggable' => 'true',
        'friction' => '0.6',
        'selectedattraction' => '0.1',
        'threshold' => '10',

        // Derpicated
        'mobile' => 'true',

    ), $atts ) );

    // Stop if visibility is hidden
    if($visibility == 'hidden') return;
    if($mobile !==  'true' && !$visibility) {$visibility = 'hide-for-small';}

    ob_start();

    $wrapper_classes = array('slider-wrapper', 'relative');
    if( $class ) $wrapper_classes[] = $class;
    if( $visibility ) $wrapper_classes[] = $visibility;
    $wrapper_classes = implode(" ", $wrapper_classes);

    $classes = array('slider');

    if ($type == 'fade') $classes[] = 'slider-type-'.$type;

    // Bullet style
    if($bullet_style) $classes[] = 'slider-nav-dots-'.$bullet_style;

    // Nav style
    if($nav_style) $classes[] = 'slider-nav-'.$nav_style;

    // Nav size
    if($nav_size) $classes[] = 'slider-nav-'.$nav_size;

    // Nav Color
    if($nav_color) $classes[] = 'slider-nav-'.$nav_color;

    // Nav Position
    if($nav_pos) $classes[] = 'slider-nav-'.$nav_pos;

    // Add timer
    $amp_autoPlay = '';
    if($auto_slide == 'true'){
        $amp_autoPlay = 'autoplay delay="'.$timer.'"';
    }
    if($auto_slide == 'true') $auto_slide = $timer;

    // Add Slider style
    if($style) $classes[] = 'slider-style-'.$style;

    // Always show Nav if set
    if($hide_nav ==  'true') {$classes[] = 'slider-show-nav';}

    // Slider Nav visebility
    $is_arrows = 'true';
    $is_bullets = 'true';

    if($arrows == 'false') $is_arrows = 'false';
    if($bullets == 'false') $is_bullets = 'false';

    if(is_rtl()) $rtl = 'true';

    $classes = implode(" ", $classes);

    // Inline CSS
    $css_args = array(
        'bg_color' => array(
          'attribute' => 'background-color',
          'value' => $bg_color,
        ),
        'margin' => array(
          'attribute' => 'margin-bottom',
          'value' => $margin,
        )
    );
    
?>
<div class="<?php echo $wrapper_classes; ?>" id="<?php echo $_id; ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
    
    <div class="<?php echo $classes; ?>"
        data-flickity-options='{
            "cellAlign": "<?php echo $slide_align; ?>",
            "imagesLoaded": false,
            "lazyLoad": 0,
            "freeScroll": <?php echo $freescroll; ?>,
            "wrapAround": <?php echo $infinitive; ?>,
            "autoPlay": <?php echo $auto_slide;?>,
            "pauseAutoPlayOnHover" : <?php echo $pause_hover; ?>,
            "prevNextButtons": <?php echo $is_arrows; ?>,
            "contain" : true,
            "adaptiveHeight" : <?php echo $auto_height;?>,
            "dragThreshold" : <?php echo $threshold ;?>,
            "percentPosition": true,
            "pageDots": <?php echo $is_bullets; ?>,
            "rightToLeft": <?php echo $rtl; ?>,
            "draggable": <?php echo $draggable; ?>,
            "selectedAttraction": <?php echo $selectedattraction; ?>,
            "parallax" : <?php echo $parallax; ?>,
            "friction": <?php echo $friction; ?>
        }'
        >
<amp-carousel width="400"
  height="250"
  layout="responsive"
  type="slides"
  role="region"
  aria-label="type='slides' carousel" <?php echo $amp_autoPlay;?>>
        <?php echo flatsome_contentfix($content); ?>
</amp-carousel>
     </div>

 
     <?php if($slide_width) { ?>
     <style>
            #<?php echo $_id; ?> .flickity-slider > *{ max-width: <?php echo $slide_width; ?>!important;
     </style>
     <?php } ?>
</div>

<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_action('amp_post_template_css','amp_ux_slider_custom_css',999);
function amp_ux_slider_custom_css(){
  echo ".slider:not(.flickity-enabled)>* {
          display: block;
        }
        [data-animate], .slider [data-animate] {
            opacity: 1;
        }
        .grid-col .slider:not(.flickity-enabled), .grid-col>.col-inner>.img, .grid-col>.col-inner>.img div, .grid-col .slider>.img, .grid-col .col-inner>.img, .grid-col .slider-wrapper, .grid-col .slider, .grid-col .banner {
           position: relative;  
        }";
}

add_filter('ampforwp_the_content_last_filter','ampforwp_purify_amp_ux_slider'); 
function ampforwp_purify_amp_ux_slider($completeContent){
    $re = '/ \.banner{-o-object-fit:cover;(.*?);padding:0}/';
    $completeContent = preg_replace($re, "banner{-o-object-fit:cover;$1;}", $completeContent);
    return $completeContent;
}