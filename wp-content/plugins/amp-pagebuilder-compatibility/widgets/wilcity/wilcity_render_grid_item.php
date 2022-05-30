<?php

use WilokeListingTools\Controllers\ReviewController;
use WilokeListingTools\Framework\Helpers\GetSettings;
use WilokeListingTools\Frontend\BusinessHours;
use WilokeListingTools\Models\ReviewMetaModel;
use WilokeListingTools\Models\UserModel;
use WILCITY_SC\SCHelpers;

function amp_wilcity_render_grid_item($post, $aAtts)
{
    global $wiloke;
    if (empty($wiloke)) {
        $wiloke = Wiloke::getThemeOptions();
    }
    
    $aAtts['img_size'] = SCHelpers::parseImgSize($aAtts['img_size']);
    $imgUrl            = GetSettings::getFeaturedImg($post->ID, $aAtts['img_size']);
    $logo              = GetSettings::getLogo($post->ID);
    $itemClass         = '';
    
    if (!isset($aAtts['isSlider'])) {
        $itemClass = $aAtts['maximum_posts_on_lg_screen'].' '.$aAtts['maximum_posts_on_md_screen'].' '.$aAtts['maximum_posts_on_sm_screen'].' mb-30 col-xs-6';
    }
    
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
    
    if (isset($aAtts['border'])) {
        $style .= ' '.$aAtts['border'];
    }
    
    $style = apply_filters('wilcity/article-class', $style, $aAtts);
    
    if (isset($aAtts['item_class'])) {
        $style .= ' '.$aAtts['item_class'];
    }
    
    $belongsToPlanClass = GetSettings::getSingleListingBelongsToPlanClass($post->ID);
    if (!empty($belongsToPlanClass)) {
        $style .= ' '.$belongsToPlanClass;
    }
    
    $style .= ' wil-shadow wil-flex-column-between';
    
    $aAtts['TYPE'] = isset($aAtts['TYPE']) ? $aAtts['TYPE'] : '';
    ?>
    <?php if (!isset($aAtts['isSlider'])) : ?>
    <?php if (empty($width)) : ?>
        <div class="<?php echo esc_attr($itemClass); ?>">
    <?php else: ?>
        <div class="<?php echo esc_attr($itemClass); ?>" style="width: <?php echo esc_attr($width); ?>">
    <?php endif; ?>
<?php endif; ?>
    <article class="<?php echo esc_attr($style); ?>" data-id="<?php echo esc_attr($post->ID); ?>">
        <div class="listing_firstWrap__36UOZ">
            <div class="wil-grid-header-wrapper">
                <?php
                if ($post->post_type == 'event') {
                    $favoriteIconClass = UserModel::isMyFavorite($post->ID) ? 'la la-star color-primary' : 'la la-star-o';
                } else {
                    $favoriteIconClass = UserModel::isMyFavorite($post->ID) ? 'la la-heart color-primary' : 'la la-heart-o';
                }
                ?>
                <?php SCHelpers::renderCardHeaderButtonAction($post); ?>
                <a class="<?php echo esc_attr(apply_filters('wilcity/filter/class-prefix',
          'wilcity-js-favorite')); ?> utility-meta_module__mfOnV utility-meta_primary__2xTvX utility-meta_border__3O9g6  mb-10 mr-5"
           data-post-id="<?php echo esc_attr($post->ID); ?>" href="<?php echo get_permalink($post->ID); ?>"
           data-tooltip="<?php esc_html_e('Save to my favorites', 'wilcity-shortcodes'); ?>"
           data-tooltip-placement="top"><i class="<?php echo esc_attr($favoriteIconClass); ?>"></i></a>
            </div>
            <header class="listing_header__2pt4D">
                
                <?php do_action('wilcity/listing-grid/before-card-header', $post, $aAtts) ?>
                
                <?php SCHelpers::renderAds($post, $aAtts['TYPE']); ?>
                <a href="<?php echo get_permalink($post); ?>">
                    <?php SCHelpers::renderLazyLoad($imgUrl, [
                      'divClass' => 'listing_img__3pwlB pos-a-full bg-cover',
                      'imgClass' => 'hidden',
                      'alt'      => $post->post_title
                    ]); ?>
                    <?php SCHelpers::renderAverageReview($post); ?>
                </a>
                <?php SCHelpers::renderClaimedBadge($post->ID); ?>
                
                <?php do_action('wilcity/listing-grid/after-card-header', $post, $aAtts) ?>

            </header>
            <div class="listing_body__31ndf js-grid-item-body">
                <?php if (!empty($logo)) : ?>
                    <a class="listing_goo__3r7Tj" href="<?php echo get_permalink($post); ?>">
                        <div class="listing_logo__PIZwf bg-cover"
                             style="background-image: url(<?php echo esc_url($logo); ?>);"></div>
                    </a>
                <?php endif; ?>
                <?php SCHelpers::renderTitle($post); ?>
                <?php SCHelpers::renderExcerpt($post, $aAtts); ?>
                <div class="listing_meta__6BbCG vertical">
                    <?php do_action('wilcity/listing-slider/meta-data', $post, $aAtts); ?>
                </div>
            </div>
        </div>
        <footer class="js-grid-item-footer listing_footer__1PzMC">
            <div class="text-ellipsis">
                <div class="icon-box-1_module__uyg5F two-text-ellipsis icon-box-1_style2__1EMOP">
                    <?php
                    do_action('wilcity-shortcode/grid-item/after-icon-box-1-open', $post, $aAtts);
                    SCHelpers::renderFooterTaxonomy($post, $aAtts);
                    SCHelpers::renderBusinessStatus($post, [], true);
                    do_action('wilcity-shortcode/grid-item/before-icon-box-1-open', $post, $aAtts);
                    ?>
                </div>
            </div>
            <div class="listing_footerRight__2398w">
                <?php
                SCHelpers::renderGallery($post);
                SCHelpers::renderFavorite($post);
                ?>
            </div>
        </footer>
    </article>
    <?php if (!isset($aAtts['isSlider'])) : ?>
    </div>
<?php endif; ?>
    <?php
}
