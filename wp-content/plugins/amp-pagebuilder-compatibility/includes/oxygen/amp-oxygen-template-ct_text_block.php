<?php

function ct_text_block_amp_pb_compatibility_for_oxygen( $atts, $content, $name ) {
   $options = array( 
      'name'    => 'Text',
      'tag'     => 'ct_text_block',
      'params'  => array(
          array(
            "type"      => "content",
            "param_name"  => "ct_content",
            "value"     => "This is a block of text. Double-click this text to edit it.",
            "css"       => false
          ),
          array(
            "type"      => "font-family",
            "heading"     => __("Font Family", "oxygen"),
            "css"       => false,
          ),
          array(
            "type"      => "colorpicker",
            "heading"     => __("Text Color", "oxygen"),
            "param_name"  => "color",
            "value"     => "",
          ),
          array(
            "type"      => "slider-measurebox",
            "heading"     => __("Font Size", "oxygen"),
            "param_name"  => "font-size",
          ),
          array(
            "type"      => "dropdown",
            "heading"     => __("Font Weight", "oxygen"),
            "param_name"  => "font-weight",
            "value"     => array (
                      ""    => "&nbsp;",
                      "100" => "100",
                      "200" => "200",
                      "300" => "300",
                      "400" => "400",
                      "500" => "500",
                      "600" => "600",
                      "700" => "700",
                      "800" => "800",
                      "900" => "900",
                    ),
          ),
          array(
            "type"      => "tag",
            "heading"     => __("Tag", "oxygen"),
            "param_name"  => "tag",
            "value"     => array (
                      "div"       => "div",
                      "p"       => "p",
                      "figcaption"  => "figcaption",
                      "time"      => "time",
                      "article"     => "article",
                      "summary"     => "summary",
                      "details"     => "details",
                    ),

            "css"       => false,
          ),
        ),
      'advanced'  => array(
          'typography' => array(
            'values'  => array (
                'font-size'   => "",
                'font-weight'   => "",
                'text-align'  => ""
              )
          ),
          'allowed_html'      => 'post',
                    'allow_shortcodes'  => false,
      ),
      'content_editable' => true,
    ) ;

  $ct_text_block_amp = new CT_Text_Block($options);
  $ct_text_block_amp->init( $options );
    if ( ! $ct_text_block_amp->validate_shortcode( $atts, $content, $name ) ) {
      return '';
    }

    $options = $ct_text_block_amp->set_options( $atts );

    global $rendered_components;
    if( !$rendered_components ) $rendered_components = array();
    $rendered_components[ $options['id'] ] = json_decode( $atts['ct_options'] );

    $content = do_shortcode( $content );
    $content = oxygen_vsb_filter_shortcode_content_decode($content);

    ob_start();

    $editable_attribute = $content;

    if( class_exists( 'Oxygen_Gutenberg' ) ) $editable_attribute = Oxygen_Gutenberg::decorate_attribute( $options, $editable_attribute, 'string' );
    
    ?><<?php echo esc_attr($options['tag'])?> id="<?php echo esc_attr($options['selector']); ?>" class="<?php echo esc_attr($options['classes']); ?>" <?php do_action("oxygen_vsb_component_attr", $options, $ct_text_block_amp->options['tag']); ?>><?php echo $editable_attribute; ?></<?php echo esc_attr($options['tag'])?>><?php

    return ob_get_clean();
  }