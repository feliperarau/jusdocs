<?php
namespace ElementorForAmp\Widgets\Pro;

use Elementor\Controls_Manager;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use ElementorPro\Modules\ThemeElements\Module;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeElements\Widgets\Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Amp_Post_Comments extends Base {


	public function get_name() {
		return 'post-comments';
	}

	public function get_title() {
		return __( 'Post Comments', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-comments';
	}

	public function get_categories() {
		return [ 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'comments', 'post', 'response', 'form' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Comments', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'_skin',
			[
				'type' => Controls_Manager::HIDDEN,
			]
		);

		$this->add_control(
			'skin_temp',
			[
				'label' => __( 'Skin', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Theme Comments', 'elementor-pro' ),
				],
				'description' => __( 'The Theme Comments skin uses the currently active theme comments design and layout to display the comment form and comments.', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'source_type',
			[
				'label' => __( 'Source', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::SOURCE_TYPE_CURRENT_POST => __( 'Current Post', 'elementor-pro' ),
					Module::SOURCE_TYPE_CUSTOM => __( 'Custom', 'elementor-pro' ),
				],
				'default' => Module::SOURCE_TYPE_CURRENT_POST,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'source_custom',
			[
				'label' => __( 'Search & Select', 'elementor-pro' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'label_block' => true,
				'filter_type' => 'by_id',
				'condition' => [
					'source_type' => Module::SOURCE_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			$post_id = (int) $settings['source_custom'];
			Plugin::elementor()->db->switch_to_post( $post_id );
		}

		if ( ! comments_open() && ( Plugin::elementor()->preview->is_preview_mode() || Plugin::elementor()->editor->is_edit_mode() ) ) :
			?>
			<div class="elementor-alert elementor-alert-danger" role="alert">
				<span class="elementor-alert-title">
					<?php esc_html_e( 'Comments are closed.', 'elementor-pro' ); ?>
				</span>
				<span class="elementor-alert-description">
					<?php esc_html_e( 'Switch on comments from either the discussion box on the WordPress post edit screen or from the WordPress discussion settings.', 'elementor-pro' ); ?>
				</span>
			</div>
			<?php
		else :
	    amp_elementor_comments_template();
		endif;

		if ( Module::SOURCE_TYPE_CUSTOM === $settings['source_type'] ) {
			Plugin::elementor()->db->restore_current_post();
		}
	}

}

function amp_elementor_comments_template(){
	if(class_exists('Disqus_Rest_Api')){
		$disqusScriptHostUrl = "https://ampforwp.appspot.com/?api=". AMPFORWP_DISQUS_URL;
        global $post; $postSlug = rawurlencode($post->post_name);
        $disqusShortname = get_option('disqus_forum_url');
        $disqusUrl = $disqusScriptHostUrl.'?disqus_title='.$postSlug.'&url='.rawurlencode(get_permalink()).'&disqus_name=https://'.$disqusShortname.'.disqus.com/embed.js"';
        $commentsHtml = '<amp-iframe width="420" height="320" sandbox="allow-forms allow-modals allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts" frameborder="0" layout="responsive" frameborder="0" src="'.$disqusUrl.'">
        </amp-iframe>';
        echo $commentsHtml;

	}else{
		ob_start();	
        comments_template();
        $comments_template = ob_get_contents();
        ob_get_clean();
	    $submit_url =  admin_url('admin-ajax.php?action=amp_elementor_ajax_comment'); 
	    $actionXhrUrl = preg_replace('#^https?:#', '', $submit_url);
        $comments_html = preg_replace('/<form action="(.*?)"(.*?)>/','<form action-xhr="'.$actionXhrUrl.'"$2>', $comments_template);
        if(preg_match('/<form(.*?)<\/form>/s', $comments_html)){
        $comments_html = preg_replace('/<form(.*?)<\/form>/s','<form$1<div submit-success>
				<template type="amp-mustache">
				 <div class="checkout_form_success" style="color:green;">{{response}}</div>
				</template>
			</div>					 
			<div submit-error>
				<template type="amp-mustache">
				 <div class="checkout_form_error" style="color:red;">{{response}}</div>
				</template>
			</div></form>',$comments_html);
        }
        echo $comments_html;
	}

}