<?php
namespace ElementorForAmp\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Amp_Image_Gallery extends Widget_Base {

	public function get_name() {
		return 'image-gallery';
	}

	public function get_title() {
		return __( 'Amp Image Gallery', 'elementor-hello-world' );
	}

	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function add_lightbox_data_to_image_link( $link_html, $id ) {
		return preg_replace( '/^<a/', '<a ' . $this->get_render_attribute_string( 'link' ), $link_html );
	}

	public function amp_elementor_widget_styles(){
		$settings = $this->get_settings_for_display();
		//print_r($settings['gallery_columns']);
		$no_of_columns = 4;
		$columns_arr = array();
		if(3 == ampforwp_get_setting('ampforwp-gallery-design-type')){
			if(isset($settings['gallery_columns']) && !empty($settings['gallery_columns'])){
				$no_of_columns = $settings['gallery_columns'];
			}
			for($i=0;$i<$no_of_columns;$i++){
				$columns_arr[] = '1fr'; 
			}
		}
	if(isset($settings['thumbnail_size'])){
		if($settings['thumbnail_size'] == 'custom'){
			$height = $settings['thumbnail_custom_dimension']['height'];
		}elseif($settings['thumbnail_size'] == 'thumbnail'){
			$width = 150; $height = 150;
		}elseif($settings['thumbnail_size'] == 'medium'){
			$height = 200;
		}elseif($settings['thumbnail_size'] == 'medium_large'){
			$height = 300;
		}elseif($settings['thumbnail_size'] == 'large'){
			$height = 350;
		}elseif($settings['thumbnail_size'] == 'full'){
			$height = 400;
		}else{
			$width = 150; $height = 150;
		}
	}
		//die;
		$inline_styles = '.overlay-text {
				      position: absolute;
				      bottom: 16px;
				      left: 16px;
				      z-index: 1;
				      pointer-events: none;
				      background-color: #494A4D;
				      color: white;
				      padding: 2px 6px 2px 6px;
				      border-radius: 2px;
				      opacity: 0.7;
				      font-family: Roboto, sans-serif;
				      font-size: 1em;
				    }
				    .ec-wrap.eepop{
				    	width:100%;
				    	display:inline-block;
				    }
				    .ec-wrap.eepop .ew-wrap{
				    	display:block;
				    }
				    .ewcont amp-carousel{
				    	max-width:55%;
				    	margin: 0 auto;
				    	height:395px;
				    }
				    .elementor .gal_w amp-img{ 
				     	background: #f1f1f1; 
				     	height: '.isset($height).'px; 
				     	width: 100%; 
				     	position: relative;
				     	float: none;
				    }
				    .elementor-element-'.$this->get_id().' .gal-cols-'.$no_of_columns.' .gal_w{
				    	width: 100%;
				    	display: inline-grid; 
				    	flex-wrap: wrap; 
				    	grid-template-columns: '.implode(' ', $columns_arr).';
				    	grid-gap: 10px 20px;
				    }
				    @media(max-width:767px){
					.elementor-element-'.$this->get_id().' .gal-cols-'.$no_of_columns.' .gal_w{grid-template-columns: 1fr;}
					}';

        global $amp_elemetor_custom_css;
		$amp_elemetor_custom_css['amp-image-gallery'][$this->get_id()] = $inline_styles;
	}
	
	public function ampforwp_lightbox_scripts($data){
      if ( empty( $data['amp_component_scripts']['amp-lightbox-gallery'] ) ) {
            $data['amp_component_scripts']['amp-lightbox-gallery'] = 'https://cdn.ampproject.org/v0/amp-lightbox-gallery-0.1.js';
          }
          return $data;
    }

	protected function render() {
		add_filter('ampforwp_post_template_data', [$this, 'ampforwp_lightbox_scripts']);
		$settings = $this->get_settings_for_display();
		$no_of_columns = 4;
		$columns_arr = array();
		//if(3 == ampforwp_get_setting('ampforwp-gallery-design-type')){
			if(isset($settings['gallery_columns']) && !empty($settings['gallery_columns'])){
				$no_of_columns = $settings['gallery_columns'];
			}
			for($i=0;$i<$no_of_columns;$i++){
				$columns_arr[] = '1fr'; 
			}
		//}
		$this->amp_elementor_widget_styles();
		if ( ! $settings['wp_gallery'] ) {
			return;
		}

		$ids = wp_list_pluck( $settings['wp_gallery'], 'id' );

		$this->add_render_attribute( 'shortcode', 'ids', implode( ',', $ids ) );
		$this->add_render_attribute( 'shortcode', 'size', isset($settings['thumbnail_size']) );
		
		  
		if ( isset($settings['gallery_columns']) && $settings['gallery_columns'] ) {
			$this->add_render_attribute( 'shortcode', 'columns', $settings['gallery_columns'] );
		}

		if (!empty($settings['gallery_link'] )) {
			$this->add_render_attribute( 'shortcode', 'link', $settings['gallery_link'] );
		}

		if ( ! empty( $settings['gallery_rand'] ) ) {
			$this->add_render_attribute( 'shortcode', 'orderby', $settings['gallery_rand'] );
		}
		?>
		<div class="elementor-image-gallery gal-cols-<?php echo $no_of_columns;?>">
			<?php
			$this->add_render_attribute( 'link', [
				'data-elementor-open-lightbox' => isset($settings['open_lightbox']),
				'data-elementor-lightbox-slideshow' => $this->get_id(),
			] );

			// if ( Plugin::$instance->editor->is_edit_mode() ) {
			// 	$this->add_render_attribute( 'link', [
			// 		'class' => 'elementor-clickable',
			// 	] );
			// }

			add_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );
			$width='';$height='';
 		if(isset($settings['thumbnail_size'])){	 
			if($settings['thumbnail_size'] == 'custom'){
				$height = $settings['thumbnail_custom_dimension']['height'];
			}elseif($settings['thumbnail_size'] == 'thumbnail'){
				$width = 150; $height = 150;
			}elseif($settings['thumbnail_size'] == 'medium'){
				$height = 200;
			}elseif($settings['thumbnail_size'] == 'medium_large'){
				$height = 300;
			}elseif($settings['thumbnail_size'] == 'large'){
				$height = 350;
			}elseif($settings['thumbnail_size'] == 'full'){
				$height = 400;
			}else{
				$width = 150; $height = 150;
			}
		}

		$gal_cols= isset($settings['gallery_columns']) ? $settings['gallery_columns'] : "4";
	?>

	    <div id="gallery-1" class="gallery  gallery-columns-<?php echo $gal_cols; ?> gallery-size-thumbnail">
		<?php
	      foreach ($settings['wp_gallery'] as $key => $value) {
			$basic_gal_img= $value['url'];	
			?>
			<figure class="gallery-item">
				<div class="gallery-icon portrait">
					<amp-img id="lightbox-w-carousel" lightbox src="<?php echo esc_url($basic_gal_img); ?>" class="attachment-thumbnail size-thumbnail " alt="" width="50" height="50" layout="responsive"></amp-img>					
				</div>
		      </figure>		
			<?php 
		   }
		  ?> 
		</div>

	<?php
 		// $gallery_markup = do_shortcode( '[gallery ' . $this->get_render_attribute_string( 'shortcode' ) . ']' );
 		// echo $gallery_markup;
		remove_filter( 'wp_get_attachment_link', [ $this, 'add_lightbox_data_to_image_link' ] );
		?>
		</div>
		<?php
	}
}
?>
