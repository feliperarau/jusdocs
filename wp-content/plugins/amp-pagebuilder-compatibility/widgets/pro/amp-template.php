<?php
namespace ElementorForAmp\Widgets\Pro;

use Elementor\Core\Base\Document;
use ElementorPro\Base\Base_Widget;
use ElementorPro\Modules\QueryControl\Module as QueryControlModule;
use ElementorPro\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Amp_Template extends Base_Widget {

	public function get_name() {
		return 'template';
	}

	public function get_title() {
		return __( 'Template', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-document-file';
	}

	public function get_keywords() {
		return [ 'elementor', 'template', 'library', 'block', 'page' ];
	}

	public function is_reload_preview_required() {
		return false;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_template',
			[
				'label' => __( 'Template', 'elementor-pro' ),
			]
		);

		$document_types = Plugin::elementor()->documents->get_document_types( [
			'show_in_library' => true,
		] );

		$this->add_control(
			'template_id',
			[
				'label' => __( 'Choose Template', 'elementor-pro' ),
				'type' => QueryControlModule::QUERY_CONTROL_ID,
				'label_block' => true,
				'autocomplete' => [
					'object' => QueryControlModule::QUERY_OBJECT_LIBRARY_TEMPLATE,
					'query' => [
						'meta_query' => [
							[
								'key' => Document::TYPE_META_KEY,
								'value' => array_keys( $document_types ),
								'compare' => 'IN',
							],
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}
	public function ampforwp_template_remote_content($src){
		if($src){
			$arg = array( "sslverify" => false, "timeout" => 60 ) ;
			$response = wp_remote_get( $src, $arg );
	        if ( wp_remote_retrieve_response_code($response) == 200 && is_array( $response ) ) {
	          $header = wp_remote_retrieve_headers($response); // array of http header lines
	          $contentData =  wp_remote_retrieve_body($response); // use the content
	          return $contentData;
	        }else{
				$contentData = file_get_contents( $src );
				if(! $contentData ){
					$data = str_replace(get_site_url(), '', $src);//content_url()
					$data = getcwd().$data;
					if(file_exists($data)){
						$contentData = file_get_contents($data);
					}
				}
				return $contentData;
			}

		}
        return '';
	}
	public function amp_pc_template_module_styles(){
		$allCss = '';
		$template_id = $this->get_settings( 'template_id' );
		if(function_exists('wp_upload_dir')){
			$uploadUrl = wp_upload_dir()['baseurl'];
			$uploadBasedir = wp_upload_dir()['basedir'];
			if(file_exists($uploadBasedir.'/elementor/css/post-'.$template_id.'.css')){
                $template_css_url = $uploadUrl.'/elementor/css/post-'.$template_id.'.css';
                $cssData = $this->ampforwp_template_remote_content($template_css_url);
                $cssData = preg_replace("/\/\*(.*?)\*\//si", "", $cssData);
                $allCss = preg_replace_callback('/url[(](.*?)[)]/', function($matches)use($template_css_url){
                        $matches[1] = str_replace(array('"', "'"), array('', ''), $matches[1]);
                            if(!wp_http_validate_url($matches[1]) && strpos($matches[1],"data:")===false){
                                $urlExploded = explode("/", $template_css_url);
                                $parentUrl = str_replace(end($urlExploded), "", $template_css_url);
                                return 'url('.$parentUrl.$matches[1].")"; 
                            }else{
                                return $matches[0];
                            }
                        }, $cssData);
            }
		}
		echo $allCss;
	}
	protected function render() {
		add_filter('amp_post_template_css', [$this, 'amp_pc_template_module_styles'] );
		$template_id = $this->get_settings( 'template_id' );
		
		if ( 'publish' !== get_post_status( $template_id ) ) {
			return;
		}

		?>
		<div class="elementor-template">
			<?php
			echo Plugin::elementor()->frontend->get_builder_content_for_display( $template_id );
			?>
		</div>
		<?php
	}

	public function render_plain_content() {}
}
