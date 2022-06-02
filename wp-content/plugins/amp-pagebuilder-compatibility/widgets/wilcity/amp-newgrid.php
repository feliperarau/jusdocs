<?php

namespace ElementorForAmp\Widgets\Wilcity;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use WILCITY_SC\SCHelpers;
use WILCITY_ELEMENTOR\Registers\Helpers;

class Amp_NewGrid extends Widget_Base
{
    use Helpers;
    
    public function get_name()
    {
        return apply_filters('wilcity/filter/id-prefix', 'wilcity-new-grid');
    }
    
   
    public function get_title()
    {
        return WILCITY_EL_PREFIX.'New Grid Layout';
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
          $wilcityScRepository->get('wilcity_kc_new_grid:wilcity_kc_new_grid', true)->sub('params')
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
        amp_wilcity_sc_render_new_grid($aSettings);
    }
}
