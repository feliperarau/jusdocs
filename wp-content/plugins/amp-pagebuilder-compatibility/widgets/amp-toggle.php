<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Toggle extends Widget_Base {

	public function get_name() {
		return 'toggle';
	}

	public function get_title() {
		return __( 'Amp Toggle', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		
		$settings['border_color'] = (!empty($settings['border_color']) ? $settings['border_color']:'#ccc');
		$settings['title_background'] = (!empty($settings['title_background']) ? $settings['title_background']:'#fff');
		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'#6ec1e4');
		$settings['tab_active_color'] = (!empty($settings['tab_active_color']) ? $settings['tab_active_color']:'#61ce70');
		$settings['icon'] = (!empty($settings['icon']) || isset($settings['icon']) ? $settings['icon']:'fa fa-caret-right');
		$settings['icon_align'] = (!empty($settings['icon_align']) ? $settings['icon_align']:'left');
		$settings['icon_color'] = (!empty($settings['icon_color']) ? $settings['icon_color']:'#6ec1e4');
		$settings['icon_active_color'] = (!empty($settings['icon_active_color']) ? $settings['icon_active_color']:'#61ce70');

		$settings['icon_space']['size'] = (!empty($settings['icon_space']['size']) ? $settings['icon_space']['size']:'20');
		$settings['icon_space']['unit'] = (!empty($settings['icon_space']['unit']) ? $settings['icon_space']['unit']:'px');
		$settings['content_background_color'] = (!empty($settings['content_background_color']) ? $settings['content_background_color']:'#fff');
		$settings['content_color'] = (!empty($settings['content_color']) ? $settings['content_color']:'#555');

		$settings['border_width']['size'] = (!empty($settings['border_width']['size']) ? $settings['border_width']['size']:'1');
		$settings['border_width']['unit'] = (!empty($settings['border_width']['unit']) ? $settings['border_width']['unit']:'px');
		$settings['space_between']['size'] = (!empty($settings['space_between']['size']) ? $settings['space_between']['size']:'0');
		$settings['space_between']['unit'] = (!empty($settings['space_between']['unit']) ? $settings['space_between']['unit']:'px');
	global $amp_elemetor_custom_css;
    
		$inline_styles = '
			.elementor-element-'.$this->get_id().' .tgl h4{
				background: '.$settings['title_background'].';
			    color: '.$settings['title_color'].';
			    border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
                }
			.elementor-element-'.$this->get_id().' .tgl p{
				color:'.$settings['content_color'].';
			    border-bottom: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';
			    background:'.$settings['content_background_color'] .';
			}
			.elementor-element-'.$this->get_id().' .elementor-toggle-icon{
				width:'.$settings['icon_space']['size'].''.$settings['icon_space']['unit'].';
				color:'.$settings['icon_color'].';
				display: inline-block;
			}';
        if(defined('AMP_CS_OPT')){
        	$amp_elemetor_custom_css['amp-toggle'][0] = '.tgl h4[expanded] {  border-bottom: none; }
			.tgl[expanded] .elementor-toggle-icon-closed{ display:none; }
			.tgl:not([expanded]) .elementor-toggle-icon-opened{	display:none;}
			.elementor-toggle-icon-right.elementor-toggle-icon{	width:auto;	}
			.elementor-toggle-icon-right{ float:right; }
			.tgl[expanded] h4{	color:'.$settings['tab_active_color'].';background: '.$settings['title_background'].';color: '.$settings['title_color'].';}
			.tgl[expanded] .elementor-toggle-icon{	color:'.$settings['icon_active_color'].';}
			.tgl h4{padding: 10px 20px; border: none; font-size: 16px; font-weight: 600;}
			.tgl p{ font-size:16px;	line-height:1.5; margin:0;padding: 15px;}
			.tgl{margin-bottom:'.$settings['space_between']['size'].''.$settings['space_between']['unit'].';}
			.elementor-toggle-icon{	width:'.$settings['icon_space']['size'].''.$settings['icon_space']['unit'].';display: inline-block;color:'.$settings['icon_color'].';}
			.tgl h4{border-bottom:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';}
			.tgl p{ border-bottom: '.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';color:'.$settings['content_color'].';background:'.$settings['content_background_color'] .';}';
        }else{
    		$amp_elemetor_custom_css['amp-toggle'][$this->get_id()] = $inline_styles;
			$amp_elemetor_custom_css['amp-toggle'][0] = '.tgl h4[expanded] {  border-bottom: none; }
			.tgl[expanded] .elementor-toggle-icon-closed{ display:none; }
			.tgl:not([expanded]) .elementor-toggle-icon-opened{	display:none;}
			.elementor-toggle-icon-right.elementor-toggle-icon{	width:auto;	}
			.elementor-toggle-icon-right{ float:right; }
			.tgl[expanded] h4{	color:'.$settings['tab_active_color'].';}
			.tgl[expanded] .elementor-toggle-icon{	color:'.$settings['icon_active_color'].';}
			.tgl h4{padding: 10px 20px; border: none; font-size: 16px; font-weight: 600;}
			.tgl p{ font-size:16px;	line-height:1.5; margin:0;padding: 15px;}
			.tgl{margin-bottom:'.$settings['space_between']['size'].''.$settings['space_between']['unit'].';}';
        }
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings['icon'] = (!empty($settings['icon']) || isset($settings['icon']) ? $settings['icon']:'fa fa-caret-right');
		$settings['icon_active'] = (!empty($settings['icon_active']) ? $settings['icon_active']:'fa fa-caret-up');
		$this->amp_elementor_widget_styles();
		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<amp-accordion class="elementor-toggle" role="tablist">
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;

				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

				$this->add_render_attribute( $tab_title_setting_key, [
					'id' => 'elementor-tab-title-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-title' ],
					'tabindex' => $id_int . $tab_count,
					'data-tab' => $tab_count,
					'role' => 'tab',
					'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
				] );

				$this->add_render_attribute( $tab_content_setting_key, [
					'id' => 'elementor-tab-content-' . $id_int . $tab_count,
					'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
					'data-tab' => $tab_count,
					'role' => 'tabpanel',
					'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
				] );

				$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
				$string = $this->parse_text_editor( $item['tab_content'] );
				if($string != strip_tags($string)) {
					$tab_content = $this->parse_text_editor( $item['tab_content'] );
				}else{
					$tab_content = '<p>'.$this->parse_text_editor( $item['tab_content'] ).'</p>';
				}
				?>
				<?php 
				$toggle_icons = '';
				if(!isset($settings['icon_align'])){
					$settings['icon_align'] = "";

				}
				if ( $settings['icon'] ){
						$toggle_icons = '<span class="elementor-toggle-icon elementor-toggle-icon-'.esc_attr( $settings['icon_align'] ).'" aria-hidden="true">
							<i class="elementor-toggle-icon-closed '.esc_attr( $settings['icon'] ).'"></i>
							<i class="elementor-toggle-icon-opened '.esc_attr( $settings['icon_active'] ).'"></i>
						</span>';
				}?>
				<section class="tgl">
			      	<h4><?php echo $toggle_icons;?><?php echo $item['tab_title']; ?></h4>
			      	<span><?php echo $tab_content; ?></span>
			    </section>
				
			<?php endforeach; ?>
		</amp-accordion>
		<?php
	}

	protected function _content_template() {
		?>
		<div class="elementor-toggle" role="tablist">
			<#
			if ( settings.tabs ) {
				var tabindex = view.getIDInt().toString().substr( 0, 3 );

				_.each( settings.tabs, function( item, index ) {
					var tabCount = index + 1,
						tabTitleKey = view.getRepeaterSettingKey( 'tab_title', 'tabs', index ),
						tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs', index );

					view.addRenderAttribute( tabTitleKey, {
						'id': 'elementor-tab-title-' + tabindex + tabCount,
						'class': [ 'elementor-tab-title' ],
						'tabindex': tabindex + tabCount,
						'data-tab': tabCount,
						'role': 'tab',
						'aria-controls': 'elementor-tab-content-' + tabindex + tabCount
					} );

					view.addRenderAttribute( tabContentKey, {
						'id': 'elementor-tab-content-' + tabindex + tabCount,
						'class': [ 'elementor-tab-content', 'elementor-clearfix' ],
						'data-tab': tabCount,
						'role': 'tabpanel',
						'aria-labelledby': 'elementor-tab-title-' + tabindex + tabCount
					} );

					view.addInlineEditingAttributes( tabContentKey, 'advanced' );
					#>
					<div class="elementor-toggle-item">
						<{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>
							<# if ( settings.icon ) { #>
							<span class="elementor-toggle-icon elementor-toggle-icon-{{ settings.icon_align }}" aria-hidden="true">
								<i class="elementor-toggle-icon-closed {{ settings.icon }}"></i>
								<i class="elementor-toggle-icon-opened {{ settings.icon_active }}"></i>
							</span>
							<# } #>
							{{{ item.tab_title }}}
						</{{{ settings.title_html_tag }}}>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					</div>
					<#
				} );
			} #>
		</div>
		<?php
	}
}