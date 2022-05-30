<?php

namespace ElementorForAmp\Widgets\Wilcity;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use WILCITY_SC\SCHelpers;
use WILCITY_ELEMENTOR\Registers\Helpers;

class Amp_ListingsTabs extends Widget_Base
{
    use Helpers;
    
    public function get_name()
    {
        return WILCITY_WHITE_LABEL.'-listings-tabs';
    }
    
    public function get_title()
    {
        return WILCITY_EL_PREFIX.'Listings Tabs (New)';
    }
    
    public function get_icon()
    {
        return 'fa fa-picture-o';
    }
    
    public function get_categories()
    {
        return ['theme-elements'];
    }
    
    protected function _register_controls()
    {
        /** @var \WilcityShortcodeRepository $wilcityScRepository */
        global $wilcityScRepository;
        $this->convertKCToEl(
          $wilcityScRepository->get('wilcity_kc_listings_tabs:wilcity_kc_listings_tabs', true)->sub('params')
        )->registerShortcode()
        ;
    }
    
    protected function render()
    {
        /** @var \WilcityShortcodeRepository $wilcityScAttsRepository */
        global $wilcityScAttsRepository;
        $aSettings = wp_parse_args(
          $this->get_settings(),
          $wilcityScAttsRepository->get($this->findScAttributesFileName(basename(__FILE__)))
        );
        
        amp_wilcityRenderListingsTabsSC($aSettings);
    }
}
