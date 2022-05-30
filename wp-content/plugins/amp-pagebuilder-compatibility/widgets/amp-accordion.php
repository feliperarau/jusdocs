<?php

namespace ElementorForAmp\Widgets;



use Elementor\Widget_Base;

use Elementor\Controls_Manager;



if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly





class Amp_Accordion extends Widget_Base {



	

	public function get_name() {

		return 'accordion';

	}



	

	public function get_title() {

		return __( 'Amp Accordion', 'elementor-hello-world' );

	}



	

	public function get_icon() {

		return 'eicon-accordion';

	}



	

	public function get_categories() {

		return [ 'general' ];

	}



	public function amp_elementor_widget_styles(){

		$settings = $this->get_settings_for_display();

		// print_r($settings);//icon,icon_active,title_html_tag

		// die;

		

		$settings['border_color'] = (!empty($settings['border_color']) ? $settings['border_color']:'#ccc');

		

		$settings['title_background'] = (!empty($settings['title_background']) ? $settings['title_background']:'#fff');

		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'#6ec1e4');

		$settings['tab_active_color'] = (!empty($settings['tab_active_color']) ? $settings['tab_active_color']:'#61ce70');

		$settings['icon_color'] = (!empty($settings['icon_color']) ? $settings['icon_color']:'#6ec1e4');

		$settings['icon_active_color'] = (!empty($settings['icon_active_color']) ? $settings['icon_active_color']:'#61ce70');

		$settings['content_color'] = (!empty($settings['content_color']) ? $settings['content_color']:'#555');



		$settings['content_background_color'] = (!empty($settings['content_background_color']) ? $settings['content_background_color']:'#fff');

		

		$settings['border_width']['size'] = (!empty($settings['border_width']['size']) ? $settings['border_width']['size']:'1');

		$settings['border_width']['unit'] = (!empty($settings['border_width']['unit']) ? $settings['border_width']['unit']:'px');

		$settings['icon_space']['size'] = (!empty($settings['icon_space']['size']) ? $settings['icon_space']['size']:'20');

		$settings['icon_space']['unit'] = (!empty($settings['icon_space']['unit']) ? $settings['icon_space']['unit']:'px');

		$inline_styles = '

			.elementor-element-'.$this->get_id().' .elementor-accordion-item{

				border:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-item h4{

				background: '.$settings['title_background'].';

			    padding: 10px 20px;;

			    border: none;

			    font-size: 16px;

			    color: '.$settings['title_color'].';

			    font-weight: 600;

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-item elementor-tab-content{

				border-top:'.$settings['border_width']['size'].''.$settings['border_width']['unit'].' solid '.$settings['border_color'].';

				font-size:16px;

				line-height:1.5;

				padding:15px 20px;

				margin:0;

				color:'.$settings['content_color'].';

				background:'.$settings['content_background_color'].';

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion .elementor-accordion-item+.elementor-accordion-item {

			    border-top: none;

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-item[expanded] .elementor-accordion-icon-closed{

				display:none;

			}

			.elementor-element-'.$this->get_id().' amp-accordion section:not([expanded]) .elementor-accordion-icon-opened{

				display:none;

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-icon{

				width:'.$settings['icon_space']['size'].''.$settings['icon_space']['unit'].';

				color:'.$settings['icon_color'].';

				display: inline-block;

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-item[expanded] h4{

				color:'.$settings['tab_active_color'].';

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-item[expanded] .elementor-accordion-icon{

				color:'.$settings['icon_active_color'].';

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-icon-right{

				float:right;

			}

			.elementor-element-'.$this->get_id().' .elementor-accordion-icon-right.elementor-accordion-icon{
				width:auto;
			}
			 
			.elementor-element-'.$this->get_id().' .amp-accordion section[expanded] .elementor-accordion-icon .elementor-accordion-icon-closed{
				display:none;
			}.elementor-element-'.$this->get_id().' .amp-accordion section[expanded] .elementor-accordion-icon .elementor-accordion-icon-open{
				display:block;
			}
			
		';

        //echo $inline_styles;die;

        global $amp_elemetor_custom_css;

		$amp_elemetor_custom_css['amp-accordion'][$this->get_id()] = $inline_styles;

	}



	

	protected function render() {

		$settings = $this->get_settings_for_display();

		// if( $this->get_id() == '58df'){
		// 	print_r($settings['tabs']);
		// 	die;
		// }
		$settings['icon_align'] = (!empty($settings['icon_align']) && isset($settings['icon_align']) ? $settings['icon_align']:'left');
		$settings['icon'] = (!empty($settings['icon']) && isset($settings['icon']) ? $settings['icon']:'fa fa-plus');

		$settings['icon_active'] = (!empty($settings['icon_active']) ? $settings['icon_active']:'fa fa-minus');

		

		$this->amp_elementor_widget_styles();

		$id_int = substr( $this->get_id_int(), 0, 3 );

		?>

		<amp-accordion class="elementor-accordion" role="tablist" disable-session-states expand-single-section>

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

				?>

				<section class="elementor-accordion-item" <?php echo ($tab_count == 1)?'expanded':'';?>>

			        <h4>

			        	<?php if ( $settings['icon'] ) : ?>
			        	<span class="elementor-accordion-icon elementor-accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>" aria-hidden="true">

							<i class="elementor-accordion-icon-closed <?php echo esc_attr( $settings['icon'] ); ?>"></i>

							<i class="elementor-accordion-icon-opened <?php echo esc_attr( $settings['icon_active'] ); ?>"></i>

						</span>

						<?php endif; ?>

						<?php echo $item['tab_title']; ?></h4>

			        <?php 

			        	$str = $this->parse_text_editor( $item['tab_content'] );
			        	echo "<div class='elementor-tab-content elementor-clearfix'>".$str."</div>";
			        	/*$replace_div = preg_replace("/<div(.*?)>(.*?)<\/div>/", '<p>$2</p>', $str);

						$matches = preg_match_all ("/<p(.*?)>(.*)<\/p>/U", $replace_div, $pat_array);

						if($matches == 1){

							echo $str;

						}elseif( $matches > 1){

							$astr = implode(' <br/> ', $pat_array[1]);

							echo $str = '<p>'.$astr.'</p>';

						}else{

							echo $str = '<p>'.$str.'</p>';

						}*/

					

			          ?>

			      </section>

			      

			<?php endforeach; ?>

		</amp-accordion>

		<?php

	}



	protected function _content_template() {

		?>

		<div class="elementor-accordion" role="tablist">

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

					<div class="elementor-accordion-item">

						<{{{ settings.title_html_tag }}} {{{ view.getRenderAttributeString( tabTitleKey ) }}}>

							<# if ( settings.icon ) { #>

							<span class="elementor-accordion-icon elementor-accordion-icon-{{ settings.icon_align }}" aria-hidden="true">

								<i class="elementor-accordion-icon-closed {{ settings.icon }}"></i>

								<i class="elementor-accordion-icon-opened {{ settings.icon_active }}"></i>

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

