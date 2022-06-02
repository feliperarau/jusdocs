<?php


if (! function_exists('divider_amp_pb_compatibility_for_muffin')) {
  function divider_amp_pb_compatibility_for_muffin($attr, $content = null)
  {
    
    extract(shortcode_atts(array(
    'height'      => 0,
    'style'       => '',  // default, dots, zigzag
    'line'        => '',  // default, narrow, wide, 0 = no_line
    'color'       => '',
    'themecolor'  => '',
  ), $attr));

    // classes

    $class = '';

    if ($themecolor) {
      $class .= ' hr_color';
      $color = false; // theme color overwrites
    }

    // color

    if( $color ){
      if( 'zigzag' == $style ){
        $color = 'color:'. $color;
      } else {
        $color = 'background-color:'. $color;
      }
    }

    switch ($style) {

      case 'dots':

      // This variable has been safely escaped above in this function
        $output = '<div class="hr_dots" style="margin:0 auto '. intval($height, 10) .'px"><span style="'. esc_attr($color) .'"></span><span style="'. esc_attr($color) .'"></span><span style="'. esc_attr($color) .'"></span></div>'."\n";
        break;

      case 'zigzag':

      // This variable has been safely escaped above in this function
        $output = '<div class="hr_zigzag" style="margin:0 auto '. intval($height, 10) .'px"><i class="icon-down-open" style="'. esc_attr($color) .'"></i><i class="icon-down-open" style="'. esc_attr($color) .'"></i><i class="icon-down-open" style="'. esc_attr($color) .'"></i></div>'."\n";
        break;

      default:

        if ($line == 'narrow') {

        // This variable has been safely escaped above in this function
          $output = '<hr class="hr_narrow '. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px;'. esc_attr($color) .'"/>'."\n";

        } elseif ($line == 'wide') {

        // This variable has been safely escaped above in this function
          $output = '<div class="hr_wide '. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px"><hr style="'. esc_attr($color) .'"/></div>'."\n";

        } elseif ($line) {

        // This variable has been safely escaped above in this function
          $output = '<hr class="'. esc_attr($class) .'" style="margin:0 auto '. intval($height, 10) .'px;'. esc_attr($color) .'"/>'."\n";

        } else {

        // This variable has been safely escaped above in this function
          $output = '<hr class="no_line" style="margin:0 auto '. intval($height, 10) .'px"/>'."\n";

        }

    }

    return $output;
  }
}