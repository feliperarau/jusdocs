<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Counter extends Widget_Base {

	public function get_name() {
		return 'counter';
	}

	public function get_title() {
		return __( 'Amp Counter', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _content_template() {
		?>
		<div class="elementor-counter">
			<div class="elementor-counter-number-wrapper">
				<span class="elementor-counter-number-prefix">{{{ settings.prefix }}}</span>
				<span class="elementor-counter-number" data-duration="{{ settings.duration }}" data-to-value="{{ settings.ending_number }}" data-delimiter="{{ settings.thousand_separator ? settings.thousand_separator_char || ',' : '' }}">{{{ settings.starting_number }}}</span>
				<span class="elementor-counter-number-suffix">{{{ settings.suffix }}}</span>
			</div>
			<# if ( settings.title ) {
				#><div class="elementor-counter-title">{{{ settings.title }}}</div><#
			} #>
		</div>
		<?php
	}
	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		// print_r($settings);//number_color,title_color,
		// die;
		$settings['number_color'] = (!empty($settings['number_color']) ? $settings['number_color']:'#6ec1e4');
		$settings['title_color'] = (!empty($settings['title_color']) ? $settings['title_color']:'#54595f');
		$inline_styles = '
			.elementor-element-'.$this->get_id().' .elementor-counter{
				text-align:center;
				width:100%;
			}
			.elementor-element-'.$this->get_id().' .elementor-counter-number-wrapper{
				font-size: 69px;
				line-height: 1.2;
				font-weight:600;
				display: flex;
    			justify-content: center;
    			color:'.$settings['number_color'].';
			}
			.elementor-element-'.$this->get_id().' .elementor-counter-title{
				    font-size: 19px;
				    font-weight: 500;
				    color: '.$settings['title_color'].';
				    line-height: 1.5;
			}
		';
        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-counter'][$this->get_id()] = $inline_styles;
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$settings['ending_number'] = (!empty($settings['ending_number']) ? $settings['ending_number']:'100	');
		$this->amp_elementor_widget_styles();
		$this->add_render_attribute( 'counter', [
			'class' => 'elementor-counter-number',
			'data-duration' => @$settings['duration'],
			'data-to-value' => @$settings['ending_number'],
		] );

		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
		}
		?>
		<div class="elementor-counter">
			<div class="elementor-counter-number-wrapper">
				<span class="elementor-counter-number-prefix"><?php echo @$settings['prefix']; ?></span>
				<span <?php echo $this->get_render_attribute_string( 'counter' ); ?>><?php echo $settings['ending_number']; ?></span>
				<span class="elementor-counter-number-suffix"><?php echo @$settings['suffix']; ?></span>
			</div>
			<?php if ( $settings['title'] ) : ?>
				<div class="elementor-counter-title"><?php echo $settings['title']; ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
