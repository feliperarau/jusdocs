<?php

use WILCITY_SC\SCHelpers;
use \WilokeListingTools\Framework\Helpers\GetSettings;
use WILCITY_SC\ParseShortcodeAtts\ParseShortcodeAtts;
use WilokeListingTools\Framework\Helpers\QueryHelper;

function amp_wilcity_sc_render_new_grid($aAtts)
{
    $oParseSC = new ParseShortcodeAtts($aAtts);
    $aAtts    = $oParseSC->parse();
    $aArgs    = [
      'posts_per_page' => $aAtts['posts_per_page'],
      'post_type'      => $aAtts['post_type'],
      'order'          => $aAtts['order'],
      'orderby'        => $aAtts['orderby']
    ];
    
    if (!empty($aAtts['terms_in_sc'])) {
        foreach ($aAtts['terms_in_sc'] as $taxonomy => $aTerms) {
            $aArgs['tax_query'][] = [
              'taxonomy' => $taxonomy,
              'terms'    => $aTerms,
              'field'    => 'term_id'
            ];
        }
    }
    if (wp_is_mobile() && isset($aAtts['mobile_img_size']) && !empty($aAtts['mobile_img_size'])) {
        $imgSize = $aAtts['mobile_img_size'];
    } else {
        if (!empty($aAtts['desktop_image_size'])) {
            $imgSize = $aAtts['desktop_image_size'];
        } else if (!empty($aAtts['image_size'])) {
            $imgSize = $aAtts['image_size'];
        } else {
            if ($aAtts['maximum_posts_on_lg_screen'] <= 2) {
                $imgSize = 'large';
            } else if ($aAtts['maximum_posts_on_lg_screen'] == 3) {
                $imgSize = 'medium';
            } else {
                $imgSize = 'wilcity_380x215';
            }
        }
    }
    $query = new WP_Query($aArgs);
    if (!$query->have_posts()) {
        return '';
    }
    
    $wrap_class            = apply_filters('wilcity-el-class', $aAtts);
    $wrap_class            = implode(' ', $wrap_class).'  '.$aAtts['extra_class'];
    $wrap_class            .= apply_filters('wilcity/filter/class-prefix', 'wil-new-grid-wrapper');
    $columnClasses         = $aAtts['maximum_posts_on_lg_screen'].' '.$aAtts['maximum_posts_on_md_screen'].' '.
                             $aAtts['maximum_posts_on_sm_screen'].' col-xs-6';
    $aArgs['postsPerPage'] = abs($aAtts['posts_per_page']);
    $headingJSON           = SCHelpers::parseHeading($aAtts);
    $aArgs['postsPerPage'] = $aArgs['posts_per_page'];
    unset($aArgs['posts_per_page']);
    amp_wilcity_render_heading($aAtts);
    ?>
    <div id="<?php echo esc_attr(uniqid('wil-new-grid-')); ?>"
         class="<?php echo esc_attr($wrap_class); ?>"
         data-orderby="<?php echo esc_attr($aAtts['orderby']); ?>">
    <div class="wil-flex-wrap row js-listing-grid">
      <?php
    while ($query->have_posts()) {
        $query->the_post();
        if ($query->post->post_type == 'event') {
            wilcity_event_slider_item($query->post, $imgSize, $aAtts);
        } else {
            amp_wilcity_feature_slider_item($query->post, $imgSize, $aAtts);
        }
    }
    wp_reset_postdata();
    ?>
    </div>
    </div>
    <?php
}
