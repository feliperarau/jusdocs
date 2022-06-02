<?php

use WilokeListingTools\Framework\Helpers\GetSettings;
use WilokeListingTools\Framework\Helpers\TermSetting;
use WILCITY_SC\SCHelpers;
use WilokeListingTools\Framework\Helpers\QueryHelper;

function amp_wilcityFallbackFindTermBySlug($aTermIds, $aTermsInfo, $taxonomy)
{
    $aTerms = [];
    foreach ($aTermIds as $termID) {
        $oTerm = get_term($termID, $taxonomy);
        if (empty($oTerm) || is_wp_error($oTerm)) {
            if (isset($aTermsInfo[$termID])) {
                $slug  = sanitize_title($aTermsInfo[$termID]);
                $oTerm = get_term_by('slug', $slug, $taxonomy);
                if (empty($oTerm) || is_wp_error($oTerm)) {
                    continue;
                }
                
                $aTerms[] = $oTerm->term_id;
            }
        } else {
            $aTerms[] = $termID;
        }
    }
    
    return $aTerms;
}

function amp_wilcityRenderListingsTabsSC($aAtts)
{

    $aParentTermIDs = SCHelpers::getAutoCompleteVal($aAtts[$aAtts['taxonomy'].'s']);
    $aParentTerms   = SCHelpers::getAutoCompleteVal($aAtts[$aAtts['taxonomy'].'s'], 'both');
    $aTabs          = [];
    if (empty($aParentTermIDs)) {
        return '';
    }
    $aTermChildren = [];
    $prefix        = 'term_tab';
    $aQueryArgs    = [
      'postsPerPage' => $aAtts['posts_per_page'],
      'post_status'  => 'publish',
      'orderby'      => $aAtts['orderby'],
      'order'        => $aAtts['order'],
      'page'         => 1
    ];
    
    $selected = '';
    
    if ($aAtts['taxonomy'] == 'custom') {
        if (empty($aAtts['custom_taxonomies_id']) || empty($aAtts['custom_taxonomy_key'])) {
            return '';
        }
        
        $aAtts['taxonomy'] = $aAtts['custom_taxonomy_key'];
        $aParentTermIDs    = explode(',', $aAtts['custom_taxonomies_id']);
    }
    
    if ($aAtts['get_term_type'] == 'term_children') {
        $aRawTermIDs = get_terms(
          [
            'hide_empty' => false,
            'parent'     => $aParentTermIDs[0],
            'taxonomy'   => $aAtts['taxonomy'],
            'count'      => $aAtts['number_of_term_children'],
            'orderby'    => $aAtts['navigation_orderby'],
            'order'      => $aAtts['navigation_order'],
          ]
        );
        
        if (empty($aRawTermIDs) || is_wp_error($aRawTermIDs)) {
            return '';
        }
        $oParentTerm                    = get_term($aParentTermIDs[0], $aAtts['taxonomy']);
        $aQueryArgs[$aAtts['taxonomy']] = $oParentTerm->term_id;
        
        $aTabs[$prefix.$oParentTerm->slug] = [
          'slug'     => $oParentTerm->slug,
          'query'    => $aQueryArgs,
          'name'     => esc_html__('All', 'wilcity-mobile-app'),
          'endpoint' => 'terms/'.$oParentTerm->slug
        ];
        $selected                          = $prefix.$oParentTerm->slug;
        $parentLink                        = get_term_link($oParentTerm->term_id);
        foreach ($aRawTermIDs as $oTerm) {
            $aTermChildren[] = $oTerm->term_id;
        }
    } else {
        if ($aAtts['navigation_orderby'] === 'include') {
            $aTermChildren = $aParentTermIDs;
        } else {
            $aRawTermIDs = get_terms(
              [
                'hide_empty' => false,
                'taxonomy'   => $aAtts['taxonomy'],
                'orderby'    => $aAtts['navigation_orderby'],
                'order'      => $aAtts['navigation_order'],
                'include'    => $aParentTermIDs
              ]
            );
            
            if (!empty($aRawTermIDs) && !is_wp_error($aRawTermIDs)) {
                foreach ($aRawTermIDs as $oNavigationItem) {
                    $aTermChildren[] = $oNavigationItem->term_id;
                }
            }
        }
    }
    $aParsedOrderBy =
      is_array($aAtts['orderby_options']) ? $aAtts['orderby_options'] : explode(',', $aAtts['orderby_options']);
    
    $aPostKeys = [];
    
    if (!empty($aAtts['post_types_filter'])) {
        if (is_array($aAtts['post_types_filter'])) {
            $aPostKeys = $aAtts['post_types_filter'];
        } else {
            $aPostKeys = explode(',', $aAtts['post_types_filter']);
        }
    }
    
    $defaultPostType = '';
    if (!empty($aPostKeys)) {
        $defaultPostType = $aPostKeys[0];
    }
    
    foreach ($aTermChildren as $termID) {
        $oTerm = get_term($termID, $aAtts['taxonomy']);
        
        if (empty($oTerm) || is_wp_error($oTerm)) {
            if (isset($aParentTerms[$termID])) {
                $slug  = sanitize_title($aParentTerms[$termID]);
                $oTerm = get_term_by('slug', $slug, $aAtts['taxonomy']);
                if (empty($oTerm) || is_wp_error($oTerm)) {
                    continue;
                }
            }
        }
        
        if (empty($defaultPostType)) {
            $defaultPostType = TermSetting::getDefaultPostType($oTerm->term_id, $oTerm->taxonomy);
        }
        
        if (empty($selected)) {
            $selected = $prefix.$oTerm->slug;
        }
        
        $aQueryArgs[$aAtts['taxonomy']] = $oTerm->term_id;
        
        $aTabs[$prefix.$oTerm->slug] = [
          'slug'     => $oTerm->slug,
          'query'    => $aQueryArgs,
          'name'     => $oTerm->name,
          'endpoint' => 'terms/'.$oTerm->slug
        ];
    }
    
    $aSCSettings = array_diff_assoc([
      'maximum_posts_on_lg_screen' => $aAtts['maximum_posts_on_lg_screen'],
      'maximum_posts_on_md_screen' => $aAtts['maximum_posts_on_md_screen'],
      'maximum_posts_on_sm_screen' => $aAtts['maximum_posts_on_sm_screen'],
      'img_size'                   => $aAtts['img_size']
    ], $aQueryArgs);
    
    unset($aSCSettings['heading_color']);
    unset($aSCSettings['description_color']);
    unset($aSCSettings['taxonomy']);
    unset($aSCSettings['get_term_type']);
    unset($aSCSettings['listing_cats']);
    unset($aSCSettings['terms_tab_id']);
    unset($aSCSettings['maximum_posts_on_lg_screen']);
    unset($aSCSettings['maximum_posts_on_md_screen']);
    unset($aSCSettings['maximum_posts_on_sm_screen']);
    $itemWrapperClass = $aAtts['maximum_posts_on_lg_screen'].' '.$aAtts['maximum_posts_on_md_screen'].' '.
                        $aAtts['maximum_posts_on_sm_screen'].' mb-30 col-xs-6';
    
    $aAtts['radius'] =
      empty($aAtts['radius']) ? WilokeThemeOptions::getOptionDetail('default_radius') : $aAtts['radius'];
    $aAtts['unit']   = WilokeThemeOptions::getOptionDetail('unit_of_distance');
    
    $searchURL = add_query_arg(
      [
        'orderby' => $aAtts['orderby'],
        'order'   => $aAtts['order']
      ],
      GetSettings::getSearchPage()
    );
    
    if ($aAtts['taxonomy'] === 'listing_location') {
        if (!empty($aAtts['listing_cat'])) {
            $aListingCatIds            = SCHelpers::getAutoCompleteVal($aAtts['listing_cat']);
            $aListingCats              = SCHelpers::getAutoCompleteVal($aAtts['listing_cat'], 'both');
            $aQueryArgs['listing_cat'] = amp_wilcityFallbackFindTermBySlug(
              $aListingCatIds,
              $aListingCats,
              'listing_cat'
            );
        }
    } elseif ($aAtts['taxonomy'] === 'listing_cat') {
        if (!empty($aAtts['listing_location'])) {
            $aQueryArgs['listing_location'] = SCHelpers::getAutoCompleteVal($aAtts['listing_location']);
            
            $aListingLocationIds            = SCHelpers::getAutoCompleteVal($aAtts['listing_location']);
            $aListingLocations              = SCHelpers::getAutoCompleteVal($aAtts['listing_location'], 'both');
            $aQueryArgs['listing_location'] = amp_wilcityFallbackFindTermBySlug(
              $aListingLocationIds,
              $aListingLocations,
              'listing_location'
            );
            
        }
    }
    
    $aQueryArgs['postType'] = $defaultPostType;
    $isAutoPostsPerPage     = !isset($aAtts['posts_per_page']) || empty($aAtts['posts_per_page']);
    
    $aQueryArgs = apply_filters(
      'wilcity/filter/wiloke-shortcodes/listings-tabs/query-args',
      $aQueryArgs,
      $aAtts
    );

    $aSettings    = $aAtts;
    $style = '';
    switch ($aAtts['style']) {
        case 'list':
            $style = 'js-listing-list listing_module__2EnGq wil-shadow js-listing-module';
            $width = '100%';
            break;
        case 'grid2':
            $style = 'listing_module__2EnGq wil-shadow listing_style3__2TXff mb-sm-20 js-listing-module js-grid-item';
            $width = '';
            break;
        default:
            $style = 'listing_module__2EnGq wil-shadow js-grid-item js-listing-module';
            $width = '';
            break;
    }
    $style .= ' wil-shadow wil-flex-column-between';
    $customQueryArgs = $aQueryArgs;
    ?>
    <div id="<?php echo uniqid('wil-listing-tabs'); ?>"
         class="wilcity-terms-tabs wilcity-listings-tabs"
         data-orderby="<?php echo esc_attr($aAtts['orderby']); ?>"
         data-order="<?php echo esc_attr($aAtts['order']); ?>"
         data-queryargs='<?php echo json_encode($aQueryArgs); ?>'
         data-searchurl="<?php echo esc_url($searchURL); ?>"
         data-radius="<?php echo esc_attr($aAtts['radius']); ?>"
         data-unit="<?php echo esc_attr($aAtts['unit']); ?>"
         style="min-height: 400px;"
         data-taxonomy="<?php echo esc_attr($aAtts['taxonomy']); ?>">
        <?php 
          
          $wrapperClass = 'wilTab_module__jlr12 wil-tab';
          if (isset($aSettings['tab_position']) && $aSettings['tab_position'] == 'vertical') {
              $wrapperClass .= ' wilTab_vertical__2iwYo';
          }
        ?>
        <div class="<?php echo esc_attr($wrapperClass) ?>">
            <ul class="wilTab_nav__1_kwb wil-tab__nav ignore-swipper wil-text-right">
                <?php if (!empty($aAtts['heading'])) : ?>
                    <li class="term-grid-title float-left">
                        <?php if (isset($parentLink)) : ?>
                            <a style="padding-left: 0; color: <?php echo esc_attr($aAtts['heading_color']); ?>"
                               class="ignore-lava"
                               href="<?php echo esc_url($parentLink); ?>"><?php echo esc_html($aAtts['heading']); ?></a>
                        <?php else : ?>
                            <a style="padding-left: 0; color: <?php echo esc_attr($aAtts['heading_color']); ?>"
                               class="ignore-lava" href="<?php echo get_permalink($post->ID); ?>"><?php echo esc_html($aAtts['heading']); ?></a>
                        <?php endif; ?>
                    </li>
                    <?php 
                    $i = 1;
                    $class_active =  '';
                    foreach ($aTabs as $tabID => $aTermInfo) : 
                      if($i == 1){
                        $class_active = 'active';
                      }else{
                        $class_active =  '';
                      }
                      ?>
                      <li id="<?php echo $tabID;?>">
                      <span class="<?php echo $class_active;?>" [class]="(listingsTab=='<?php echo $tabID;?>'?'active':'')" role="button" tabindex="<?php echo $i;?>" id="<?php echo $tabID;?>" on="tap:AMP.setState({ listingsTab: '<?php echo $tabID;?>' })"><?php echo $aTermInfo['name'];?></span>
                      </li>
                    <?php 
                    $i++;
                  endforeach; ?>
                <?php endif; ?>
            </ul>
            <div class="wilTab_content__2j_o5 wil-tab__content">
                <?php
                $i = 1;
                $default_class = '';
                foreach ($aTabs as $tabID => $aTermInfo) : 
                  if($i == 1){
                    $default_class = 'show';
                  }else{
                    $default_class = 'hide';
                  }
                  ?>
                  <div id="<?php echo $tabID;?>" class="<?php echo $tabID.' '.$default_class;?>" [class]="(listingsTab=='<?php echo $tabID;?>'?'<?php echo $tabID;?> show':'<?php echo $tabID;?> hide')">
                    <div class="w-100 pos-r float-left" radius="<?php echo abs($aAtts['radius']); ?>" unit="<?php echo abs($aAtts['unit']); ?>">
                      <div class="wil-grid-wrapper">
                        <div data-col-xs-gap="10" data-col-sm-gap="10" data-col-md-gap="15" class="wil-flex-wrap row js-listing-grid">
                          <?php
                          $customQueryArgs['listing_cat'] = $aTermInfo['query']['listing_cat'];
                          $aArgs = $customQueryArgs;
                          $aArgs = wp_parse_args(QueryHelper::buildQueryArgs($aArgs), $aArgs);
                          unset($aArgs['listing_cat']);
                          unset($aArgs['listing_location']);
                          $the_query = new WP_Query( $aArgs );
                            // The Loop
                            if ( $the_query->have_posts() ) {
                              while ( $the_query->have_posts() ) {
                                $the_query->the_post();
                                amp_wilcity_render_grid_item($the_query->post, $aAtts);
                              }
                            }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php 
                $i++;
                endforeach; ?>
                <span class="hide"></span>
                <span class="active"></span>
            </div>
        </div>
    </div>
    <?php
}
