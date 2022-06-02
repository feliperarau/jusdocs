<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Progress extends Widget_Base {

	public function get_name() {
		return 'progress';
	}

	public function get_title() {
		return __( 'Amp Progress Bar', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		// print_r($settings);
		// die;//progress_type,percent,display_percentage,bar_color,bar_bg_color,bar_inline_color,title_color,
		$settings['progress_type'] = (!empty($settings['progress_type']) ? $settings['progress_type']:'default');
		$settings['display_percentage'] = (!empty($settings['display_percentage']) ? $settings['display_percentage']:'show');
		$settings['bar_color'] = (!empty($settings['bar_color']) ? $settings['bar_color']:'');
		$settings['bar_bg_color'] = (!empty($settings['bar_bg_color']) ? $settings['bar_bg_color']:'#eee');
		$settings['bar_inline_color'] = (!empty($settings['bar_inline_color']) ? $settings['bar_inline_color']:'#fff');
		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'#6ec1e4');

		$settings['percent']['size'] = (!empty($settings['percent']['size'] ) ? $settings['percent']['size']:'50');
		$settings['percent']['unit'] = (!empty($settings['percent']['unit']) ? $settings['percent']['unit']:'%');
		if( !empty($settings['bar_color'])){
			$default_bar_colors = '.elementor-element-'.$this->get_id().' .progress-danger .elementor-progress-bar{
						background:'.$settings['bar_color'].';
					}
					.elementor-element-'.$this->get_id().' .progress-info .elementor-progress-bar{
						background:'.$settings['bar_color'].';
					}
					.elementor-element-'.$this->get_id().' .progress-success .elementor-progress-bar{
						background:'.$settings['bar_color'].';
					}
					.elementor-element-'.$this->get_id().' .progress-warning .elementor-progress-bar{
						background:'.$settings['bar_color'].';
					}';
		}else{
			$default_bar_colors = '.elementor-element-'.$this->get_id().' .progress-danger .elementor-progress-bar{
						background:#d9534f;
					}
					.elementor-element-'.$this->get_id().' .progress-info .elementor-progress-bar{
						background:#5bc0de;
					}
					.elementor-element-'.$this->get_id().' .progress-success .elementor-progress-bar{
						background:#5cb85c;
					}
					.elementor-element-'.$this->get_id().' .progress-warning .elementor-progress-bar{
						background:#f0ad4e;
					}';
		}
		$inline_styles = '
		.elementor-element-'.$this->get_id().' .elementor-title{
			color:'.$settings['title_color'].';
			font-size:18px;
			font-weight:400;
		}
		.elementor-element-'.$this->get_id().' .elementor-progress-wrapper{
			background:'.$settings['bar_bg_color'].';
			width:100%;
			line-height:1.6;
		}
		.elementor-element-'.$this->get_id().' .elementor-progress-bar{
			background: '.(!empty($settings['bar_color'])?$settings['bar_color']:'#6ec1e4').';
		    font-size: 11px;
		    border-radius: 2px;
		    color: '.$settings['bar_inline_color'].';
		    padding: 6px 15px;
		    display: flex;
		}
		.elementor-element-'.$this->get_id().' .elementor-progress-text{
			   flex-grow: 1;
		}
		.elementor-element-'.$this->get_id().' .percentage-'.$this->get_id().'{
			width:'.$settings['percent']['size'].''.$settings['percent']['unit'].';
		}
		'.$default_bar_colors
		;
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-progress'][$this->get_id()] = $inline_styles;
        //echo $inline_styles;
	}
	
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->amp_elementor_widget_styles();
		$settings['progress_type'] = (!empty($settings['progress_type']) ? $settings['progress_type']:'default');
		$settings['percent']['size'] = (!empty($settings['percent']['size'] ) ? $settings['percent']['size']:'50');
		$settings['percent']['unit'] = (!empty($settings['percent']['unit']) ? $settings['percent']['unit']:'%');
		$this->add_render_attribute( 'wrapper', [
			'class' => 'elementor-progress-wrapper progress-'.$settings['progress_type'],
			'role' => 'progressbar',
			'aria-valuemin' => '0',
			'aria-valuemax' => '100',
			'aria-valuenow' => $settings['percent']['size'],
			'aria-valuetext' => $settings['inner_text'],
		] );

		if ( ! empty( $settings['progress_type'] ) ) {
			$this->add_render_attribute( 'wrapper', 'class', 'progress-' . $settings['progress_type'] );
		}

		$this->add_render_attribute( 'progress-bar', [
			'class' => 'elementor-progress-bar percentage-'.$this->get_id(),
			'data-max' => $settings['percent']['size'],
		] );

		$this->add_render_attribute( 'inner_text', [
			'class' => 'elementor-progress-text',
		] );

		$this->add_inline_editing_attributes( 'inner_text' );

		if ( ! empty( $settings['title'] ) ) { ?>
			<span class="elementor-title"><?php echo $settings['title']; ?></span>
		<?php } ?>

		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div <?php echo $this->get_render_attribute_string( 'progress-bar' ); ?> >
				<span <?php echo $this->get_render_attribute_string( 'inner_text' ); ?>><?php echo $settings['inner_text']; ?></span>
				<?php if ( 'hide' !== $settings['display_percentage'] ) { ?>
					<span class="elementor-progress-percentage"><?php echo $settings['percent']['size']; ?>%</span>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	protected function _content_template() {
		?>
		<#
		view.addRenderAttribute( 'progressWrapper', {
			'class': [ 'elementor-progress-wrapper', 'progress-' + settings.progress_type ],
			'role': 'progressbar',
			'aria-valuemin': '0',
			'aria-valuemax': '100',
			'aria-valuenow': settings.percent.size,
			'aria-valuetext': settings.inner_text
		} );

		view.addRenderAttribute( 'inner_text', {
			'class': 'elementor-progress-text'
		} );

		view.addInlineEditingAttributes( 'inner_text' );
		#>
		<# if ( settings.title ) { #>
			<span class="elementor-title">{{{ settings.title }}}</span><#
		} #>
		<div {{{ view.getRenderAttributeString( 'progressWrapper' ) }}}>
			<div class="elementor-progress-bar" data-max="{{ settings.percent.size }}">
				<span {{{ view.getRenderAttributeString( 'inner_text' ) }}}>{{{ settings.inner_text }}}</span>
				<# if ( 'hide' !== settings.display_percentage ) { #>
					<span class="elementor-progress-percentage">{{{ settings.percent.size }}}%</span>
				<# } #>
			</div>
		</div>
		<?php
	}
}
