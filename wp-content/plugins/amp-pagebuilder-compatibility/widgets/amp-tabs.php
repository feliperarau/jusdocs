<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Tabs extends Widget_Base {

	public function get_name() {
		return 'tabs';
	}

	public function get_title() {
		return __( 'Amp Tabs', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display( );
		// print_r($settings);
		// die;
		$settings['type'] = (!empty($settings['type']) ? $settings['type']:'horizontal');
		$settings['navigation_width']['size'] = (!empty($settings['navigation_width']['size']) ? $settings['navigation_width']['size']:'25');
		$settings['navigation_width']['unit'] = (!empty($settings['navigation_width']['unit']) ? $settings['navigation_width']['unit']:'%');
		$settings['border_width']['size'] = (!empty($settings['border_width']['size']) ? $settings['border_width']['size']:'1');
		$settings['border_width']['unit'] = (!empty($settings['border_width']['unit']) ? $settings['border_width']['unit']:'px');
		$settings['border_color'] = (!empty($settings['border_color']) ? $settings['border_color']:'#d4d4d4');
		$settings['background_color'] = (!empty($settings['background_color']) ? $settings['background_color']:'#fff');
		$settings['tab_color'] = (!empty($settings['tab_color']) ? $settings['tab_color']:'#6ec1e4');
		$settings['tab_active_color'] = (!empty($settings['tab_active_color']) ? $settings['tab_active_color']:'#61ce70');
		$settings['content_color'] = (!empty($settings['content_color']) ? $settings['content_color']:'#7a7a7a');
		$settings['background_color'] = (!empty($settings['background_color']) ? $settings['background_color']:'#fff');
		$inline_styles = '
        .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton.active,  .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton[selected] {
        	border-right:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
        	border-bottom: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid #fff;
			position: relative;
			top: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].';
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs-view-vertical .tabButton.active,  .elementor-element-'.$this->get_id().' .elementor-tabs-view-vertical .tabButton[selected] {
        	border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
        	border-right: none;
        }
         @media(min-width:768px){
	        .elementor-element-'.$this->get_id().' .elementor-tabs-view-vertical{
	        	display: inline-grid;
	    		grid-template-columns: '.$settings['navigation_width']['size'].''.$settings['navigation_width']['unit'].' auto;
	        }
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton{
        	border-top:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid #fff;
            border-left:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid #fff;
            border-right:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid #fff;
            border-bottom:none;
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs-view-vertical .tabButton{
        	border-top:none;
            border-left:none;
            border-bottom:none;
            border-right:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
            text-align:left;
        }
        
        .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .elementor-tabs-wrapper{
        	border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs .tabButton {
		    color:'.$settings['tab_color'].';
		    outline: none;
		   /* top: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].';*/
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs .tabButton.active, .elementor-element-'.$this->get_id().' .elementor-tabs .tabButton[selected] {
            outline: none;
            border-top:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
            border-left:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
            background:'.$settings['background_color'].';
		    border-width:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].';
		    color:'.$settings['tab_color'].';
        }
        .elementor-element-'.$this->get_id().' .elementor-tabs .tabContent {
    		border-bottom: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
    		border-top: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
    		border-right: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
    		color:'.$settings['content_color'].';
    		background:'.$settings['background_color'].';
        }
        @media(max-width:767px){
	        .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton{
				border-top:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
				border-left:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
				border-right:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
        	}
        	
        	.elementor-element-'.$this->get_id().' .elementor-tabs amp-selector{
        		border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
        	}
	        .elementor-element-'.$this->get_id().' .elementor-tabs-view-vertical .tabButton{
	        	border-top:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
	            border-left:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
	            border-bottom:none;
	            border-right:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
	            background:#fff;
	        }
	        .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton.active,  .elementor-element-'.$this->get_id().' .elementor-tabs-view-horizontal .tabButton[selected] {
	        	border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
				position: relative;
				top: 0px;
	        }
        }
        ';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-tabs'][$this->get_id()] = $inline_styles;
		$amp_elemetor_custom_css['amp-tabs'][0] = '
        .elementor-tabs-view-vertical .elementor-tabs .tabButton{display:flex;padding: 20px 25px;
        }
        .hidetabs{display:none;}
        .elementor-tabs .ampTabContainer{ display: flex; flex-wrap: wrap; }
        .elementor-tabs .tabButton[selected]+.tabContent{ display: block;}
        .elementor-tabs .tabContent p{margin:0;}
       #page-container .elementor-tabs-wrapper{width:100%;	max-width:100%; }
        .elementor-tabs.elementor-tabs-view-vertical .tabButton {
    width: 100%;
}
        .elementor-tabs .tabButton { position: relative; padding: 20px 25px;
		    font-weight: 700; line-height: 1; background:#fff;font-size: 17px;
        }
        .elementor-tabs .tabContent {display: none;	border-top-style: none;	padding: 20px;
    		width:100%;	font-size:17px;	font-weight:400;}
        @media(max-width:767px){
			.elementor-tabs-view-vertical { display: grid; grid-template-columns: 100%;}
        	.elementor-tabs-wrapper{display:none;}
        	.hidetabs{display:block;}
	        .elementor-tabs .tabButton{width:100%;top:0px;}
	        .elementor-tabs .tabContent{border-bottom:none;}
	        .elementor-tabs-view-vertical .elementor-tabs-wrapper{display:none; }
	        .elementor-tabs-view-vertical .tabButton.active, .elementor-tabs-view-vertical .tabButton[selected] {
	        	border-bottom:none;
	        }
		}
		@media(min-width:768px){
		 .elementor-tabs-view-vertical .tabContent {border-left:none;}
		}';
	}

	protected function render() {
		$settings = $this->get_settings_for_display( );
		$this->amp_elementor_widget_styles();
		$tabs = $this->get_settings_for_display( 'tabs' );

		$id_int = substr( $this->get_id_int(), 0, 3 );
		$settings['type'] = (!empty($settings['type']) ? $settings['type']:'horizontal');
		?>
		<div class="elementor-tabs elementor-tabs-view-<?php echo $settings['type'];?>" role="tablist">
			<div class="elementor-tabs-wrapper">
				<?php
				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;

					$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

					$this->add_render_attribute( $tab_title_setting_key, [
						'id' => 'elementor-tab-title-' . $id_int . $tab_count,
						'class' => [ 'elementor-tab-title', 'elementor-tab-desktop-title', 'tabButton' ],
						'data-tab' => $tab_count,
						'tabindex' => $id_int . $tab_count,
						'role' => 'tab',
						'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
					] );
					?>
					
					<button on="tap:AMP.setState({selectedTab: '<?php echo $tab_count;?>'})" [class]="selectedTab == <?php echo $tab_count;?> ? 'tabButton active' : 'tabButton'" class="tabButton <?php echo ($tab_count == '1'?'active':'');?>"><?php echo $item['tab_title']; ?></button>
				<?php endforeach; ?>
			</div>
			<amp-selector on="select:AMP.setState({selectedTab: event.targetOption})" [selected]="selectedTab" role="tablist" layout="container" class="ampTabContainer" >
				<?php

				foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;

					$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

					$tab_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $tab_count );

					$this->add_render_attribute( $tab_content_setting_key, [
						'id' => 'elementor-tab-content-' . $id_int . $tab_count,
						'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab' => $tab_count,
						'role' => 'tabpanel',
						'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
					] );

					$this->add_render_attribute( $tab_title_mobile_setting_key, [
						'class' => [ 'elementor-tab-title', 'elementor-tab-mobile-title' ],
						'tabindex' => $id_int . $tab_count,
						'data-tab' => $tab_count,
						'role' => 'tab',
					] );

					$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					?>
					<div role="tab" class="tabButton hidetabs" option="<?php echo $tab_count;?>" <?php echo ($tab_count == 1)?'selected':'';?>><?php echo $item['tab_title']; ?></div>
					<div role="tabpanel" class="tabContent" ><?php echo $this->parse_text_editor( $item['tab_content'] ); ?></div>
				<?php endforeach; ?>
			</amp-selector>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="elementor-tabs" role="tablist">
			<#
			if ( settings.tabs ) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 );
				#>
				<div class="elementor-tabs-wrapper">
					<#
					_.each( settings.tabs, function( item, index ) {
						var tabCount = index + 1;
						#>
						<div id="elementor-tab-title-{{ tabindex + tabCount }}" class="elementor-tab-title elementor-tab-desktop-title" tabindex="{{ tabindex + tabCount }}" data-tab="{{ tabCount }}" role="tab" aria-controls="elementor-tab-content-{{ tabindex + tabCount }}">{{{ item.tab_title }}}</div>
					<# } ); #>
				</div>
				<div class="elementor-tabs-content-wrapper">
					<#
					_.each( settings.tabs, function( item, index ) {
						var tabCount = index + 1,
							tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs',index );

						view.addRenderAttribute( tabContentKey, {
							'id': 'elementor-tab-content-' + tabindex + tabCount,
							'class': [ 'elementor-tab-content', 'elementor-clearfix', 'elementor-repeater-item-' + item._id ],
							'data-tab': tabCount,
							'role' : 'tabpanel',
							'aria-labelledby' : 'elementor-tab-title-' + tabindex + tabCount
						} );

						view.addInlineEditingAttributes( tabContentKey, 'advanced' );
						#>
						<div class="elementor-tab-title elementor-tab-mobile-title" data-tab="{{ tabCount }}" role="tab">{{{ item.tab_title }}}</div>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					<# } ); #>
				</div>
			<# } #>
		</div>
		<?php
	}
}
