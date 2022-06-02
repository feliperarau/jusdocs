<?php
namespace ElementorPro\Modules\ThemeBuilder\Classes;

use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Modules\ThemeBuilder\ThemeSupport\GeneratePress_Theme_Support;
use ElementorPro\Modules\ThemeBuilder\ThemeSupport\Safe_Mode_Theme_Support;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Theme_Support {

	public function __construct() {
		add_action( 'init', [ $this, 'init' ] );
	}

	public function init() {
		$theme = wp_get_theme();

		switch ( $theme->get_template() ) {
			case 'generatepress':
				new GeneratePress_Theme_Support();
				break;
			case 'elementor-safe':
				new Safe_Mode_Theme_Support();
				break;
		}

		add_action( 'elementor/theme/register_locations', [$this, 'after_register_locations'], 99);
	}

	/**
	 * @param Locations_Manager $location_manager
	 */
	public function after_register_locations( $location_manager ) {
		$core_locations = $location_manager->get_core_locations();
		$overwrite_header_location = false;
		$overwrite_footer_location = false;

		foreach ( $core_locations as $location => $settings ) {
			if ( ! $location_manager->get_location( $location ) ) {
				if ( 'header' === $location ) {
					$overwrite_header_location = true;
				} elseif ( 'footer' === $location ) {
					$overwrite_footer_location = true;
				}
				$location_manager->register_core_location( $location, [
					'overwrite' => true,
				] );
			}
		}

		if ( $overwrite_header_location || $overwrite_footer_location ) {

			/** @var Module $theme_builder_module */
			$theme_builder_module = Module::instance();

			$conditions_manager = $theme_builder_module->get_conditions_manager();

			$single = $conditions_manager->get_documents_for_location( 'single' );
			$headers = $conditions_manager->get_documents_for_location( 'header' );
			$footers = $conditions_manager->get_documents_for_location( 'footer' );
			$product_archive = $conditions_manager->get_documents_for_location( 'archive' );
			
			if( !empty( $single ) ){
				if( ( ( function_exists('ampforwp_is_amp_endpoint')  && ampforwp_is_amp_endpoint() ) || ( function_exists('is_amp_endpoint')  && is_amp_endpoint() ) ) && \pagebuilder_for_amp_utils::get_setting('elem-themebuilder_single') ) {
					add_filter('ampforwp_modify_the_content', array( $this, 'amp_pbc_theme_single') );
				}
			}

			if ( ! empty( $headers ) || ! empty( $footers ) ) {
				if( ( function_exists('ampforwp_is_amp_endpoint')  && ampforwp_is_amp_endpoint() ) || ( function_exists('is_amp_endpoint')  && is_amp_endpoint() ) ) {
					if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_header')){
						add_action( 'ampforwp_after_header', [ $this, 'amp_pc_get_header' ] );
					}
					if(function_exists('is_shop')){
						if(is_shop() || is_product_category() || is_product_tag()){
							if(! empty($product_archive)){
							remove_filter( 'amp_post_template_file', 'ampwc_archive_template', 11, 3 );
							add_filter( 'amp_post_template_file', [ $this, 'amp_pc_themeb_archive_product_template' ], 11, 3 );
						     }
						}
					}
					if(\pagebuilder_for_amp_utils::get_setting('elem-themebuilder_footer')){
						add_action( 'amp_post_template_above_footer', [ $this,'amp_pc_get_footer'], 1 );
					}
				}
				else{
					add_action( 'get_header', [ $this, 'get_header' ] );
					add_action( 'get_footer', [ $this, 'get_footer' ] );
				}
				add_filter( 'show_admin_bar', [ $this, 'filter_admin_bar_from_body_open' ] );
			}
		}
	}
	public function get_header( $name ) {
		require __DIR__ . '/../views/theme-support-header.php';

		$templates = [];
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "header-{$name}.php";
		}

		$templates[] = 'header.php';

		// Avoid running wp_head hooks again
		remove_all_actions( 'wp_head' );
		ob_start();
		// It cause a `require_once` so, in the get_header it self it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}
	public function get_footer( $name ) {
		require __DIR__ . '/../views/theme-support-footer.php';

		$templates = [];
		$name = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "footer-{$name}.php";
		}

		$templates[] = 'footer.php';

		ob_start();
		// It cause a `require_once` so, in the get_header it self it will not be required again.
		locate_template( $templates, true );
		ob_get_clean();
	}

	public function amp_pc_get_header() {
		$getTheme = function_exists('wp_get_theme') ? wp_get_theme() : '';
		$location_manager = Module::instance()->get_locations_manager();
		ob_start();
		// It cause a `require_once` so, in the get_header it self it will not be required again.
		$location_manager->do_location( 'header' );
		if($getTheme && ($getTheme->name == "Astra" || $getTheme->name == "Astra Child") && empty($location_manager->do_location( 'header' ))){
			if(function_exists('astra_header')){
				astra_header();
			}
		}
		$header_html = ob_get_contents();
		ob_get_clean();
		if(class_exists('\AMPFORWP_Content')){
            $sanitizer_obj = new \AMPFORWP_Content( $header_html,
                apply_filters( 'amp_content_embed_handlers', array(
                    'AMP_Core_Block_Handler' => array(),
                    'AMP_Twitter_Embed_Handler' => array(),
                    'AMP_YouTube_Embed_Handler' => array(),
                    'AMP_DailyMotion_Embed_Handler' => array(),
                    'AMP_Vimeo_Embed_Handler' => array(),
                    'AMP_SoundCloud_Embed_Handler' => array(),
                    'AMP_Instagram_Embed_Handler' => array(),
                    'AMP_Vine_Embed_Handler' => array(),
                    'AMP_Facebook_Embed_Handler' => array(),
                    'AMP_Pinterest_Embed_Handler' => array(),
                    'AMP_Gallery_Embed_Handler' => array(),
                    'AMP_Playlist_Embed_Handler'    => array(),
                    'AMP_Wistia_Embed_Handler' => array(),
                )),
                apply_filters( 'amp_content_sanitizers', 
                    array( 'AMP_Img_Sanitizer' => array(), 
                    'AMP_Blacklist_Sanitizer' => array(),
                    'AMP_Style_Sanitizer' => array(), 
                    'AMP_Video_Sanitizer' => array(),
                    'AMP_Audio_Sanitizer' => array(),
                    'AMP_Iframe_Sanitizer' => array(
                         'add_placeholder' => true,
                     ),
                    ) 
                ) 
            );
            $amp_sanitized_header_content = $sanitizer_obj->get_amp_content();
     		global $redux_builder_amp;
    		$amp_query_variable = $redux_builder_amp['ampforwp-amp-takeover'] != 1 ? 'amp' : '';
    		$amp_query_val = $redux_builder_amp['ampforwp-amp-takeover'] != 1 ? '1' : '';
	        $srch_cont = '<div class="elementor-element elementor-search-form" hidden [hidden]="hideSearch">
	        	<form role="search" method="get" class="elementor-search-form" action="'.esc_url( home_url( "/" ) ).'" target="_top" style="width:100%">
	        		<div class="elementor-search-form__container">
	        			<input type="text" placeholder="AMP" value="'.$amp_query_val.'" name="'.$amp_query_variable.'" class="hidden"/>
	        			<input type="search" class="el-searchs" value="'.get_search_query().'" name="s" />
	        			<button type="submit" class="el-search-sub">
	        				<span class="search__submit-icon"><i class="fa fa-search" aria-hidden="true"></i></span>
	        			</button>
	        		</div>
	        	</form>
	        	<i aria-hidden="true" class="fas fa-times" on="tap:AMP.setState({ hideSearch: true })" role="button" tabindex=0 style="margin: 20px 0 0 23px; font-size: 22px;"></i>
	        	</div>';
            $amp_sanitized_header_content = preg_replace('/<i\saria-hidden="true"\sclass="fas\sfa-search"><\/i>/', '<i aria-hidden="true" class="fas fa-search" on="tap:AMP.setState({ hideSearch: false })" role="button" tabindex=0></i>', $amp_sanitized_header_content);
            $amp_sanitized_header_content .=  $srch_cont;
    		$menu_args = array(
                            'theme_location' => 'amp-menu' ,
                            'link_before'     => '<span>',
                            'link_after'     => '</span>',
                            'menu'=>'ul',
                            'menu_class'=> 'elementor-nav-menu',
                            'echo' => false,
                            'menu_class' => 'amp-menu elementor-nav-menu',
                            'walker' => new \Ampforwp_Walker_Nav_Menu()
                        );
           	$amp_menu = amp_menu_html(true, $menu_args, 'header'); 
            $menu_hamb = '<div class="el-hmnu" hidden [hidden]="hidehmenu" ><nav class="m-menu" role="navigation" aria-hidden="true">'.$amp_menu.'</nav><span class="el-hmnu-x"><i aria-hidden="true" class="fas fa-times" on="tap:AMP.setState({ hidehmenu: true })" role="button" tabindex=0></i></div>';
            $amp_sanitized_header_content = preg_replace('/<svg\sxmlns="(.*?)"\sviewbox="0\s0\s84\.95\s53\.77">/', '<span class="mnu-hwrpr" on="tap:AMP.setState({ hidehmenu: false })" role="button" tabindex=0><svg xmlns="$1" viewbox="0 0 84.95 53.77">', $amp_sanitized_header_content);
            $amp_sanitized_header_content = preg_replace('/<span\sclass="jet-button__icon\sjet-elements-icon"><i\saria-hidden="true"\sclass="fas\sfa-bars"><\/i><\/span>/', '<span class="jet-button__icon jet-elements-icon mnu-hwrpr" on="tap:AMP.setState({ hidehmenu: false })" role="button" tabindex=0><i aria-hidden="true" class="fas fa-bars"></i></span>', $amp_sanitized_header_content);
            $amp_sanitized_header_content .=  $menu_hamb;
            echo $amp_sanitized_header_content;
        }
	}

	// Archive Template Files
	public function amp_pc_themeb_archive_product_template( $file, $type, $post ) { 
	global $redux_builder_amp;

	// Archive
	if ( (is_archive() && function_exists('is_product_category') && is_product_category()) || (is_archive() && function_exists('is_product_tag') && is_product_tag())) {
        if ( 'single' === $type ) {
            $file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'widgets/pro/themebuilder/templates/pbc-ele-product-archive.php';
        }
    }
	if(function_exists('is_shop')){
		if ((is_shop() && 'page' === $type) || (is_shop() && 'single' === $type)) {
		    $file = AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'widgets/pro/themebuilder/templates/pbc-ele-product-archive.php';
		}
	}
    return $file;
	}

	/**
	 * Don't show admin bar on `wp_body_open` because the theme header HTML is ignored via `$this->get_header()`.
	 *
	 * @param bool $show_admin_bar
	 *
	 * @return bool
	 */
	public function filter_admin_bar_from_body_open( $show_admin_bar ) {
		global $wp_current_filter;

		// A flag to mark if $show_admin_bar is switched to false during this filter,
		// if so, it needed to switch back on the next filter (wp_footer).
		static $switched = false;

		if ( $show_admin_bar && in_array( 'wp_body_open', $wp_current_filter ) ) {
			$show_admin_bar = false;
			$switched = true;
		} elseif ( $switched ) {
			$show_admin_bar = true;
		}

		return $show_admin_bar;
	}

	public function amp_pbc_theme_single($content){
		$location_manager = Module::instance()->get_locations_manager();
		ob_start();
		$location_manager->do_location( 'single' );
		$single_content = ob_get_contents();
		ob_clean();
		if(class_exists('\AMPFORWP_Content')){
		    $sanitizer_obj = new \AMPFORWP_Content( $single_content,
		        apply_filters( 'amp_content_embed_handlers', array(
		            'AMP_Core_Block_Handler' => array(),
		            'AMP_Twitter_Embed_Handler' => array(),
		            'AMP_YouTube_Embed_Handler' => array(),
		            'AMP_DailyMotion_Embed_Handler' => array(),
		            'AMP_Vimeo_Embed_Handler' => array(),
		            'AMP_SoundCloud_Embed_Handler' => array(),
		            'AMP_Instagram_Embed_Handler' => array(),
		            'AMP_Vine_Embed_Handler' => array(),
		            'AMP_Facebook_Embed_Handler' => array(),
		            'AMP_Pinterest_Embed_Handler' => array(),
		            'AMP_Gallery_Embed_Handler' => array(),
		            'AMP_Playlist_Embed_Handler'    => array(),
		            'AMP_Wistia_Embed_Handler' => array(),
		        )),
		        apply_filters( 'amp_content_sanitizers', 
		            array( 'AMP_Img_Sanitizer' => array(), 
		            'AMP_Blacklist_Sanitizer' => array(),
		            'AMP_Style_Sanitizer' => array(), 
		            'AMP_Video_Sanitizer' => array(),
		            'AMP_Audio_Sanitizer' => array(),
		            'AMP_Iframe_Sanitizer' => array(
		                 'add_placeholder' => true,
		             ),
		            ) 
		        ) 
		    );
		    $content = $sanitizer_obj->get_amp_content();
		}
		return $content;
	}

	public function amp_pc_get_footer( $name ) {
		$location_manager = Module::instance()->get_locations_manager();
		ob_start();
		// It cause a `require_once` so, in the get_header it self it will not be required again.
		$location_manager->do_location( 'footer' );
		$footer_html = ob_get_contents();
		ob_get_clean();
		if(class_exists('\AMPFORWP_Content')){
		    $sanitizer_obj = new \AMPFORWP_Content( $footer_html,
		        apply_filters( 'amp_content_embed_handlers', array(
		            'AMP_Core_Block_Handler' => array(),
		            'AMP_Twitter_Embed_Handler' => array(),
		            'AMP_YouTube_Embed_Handler' => array(),
		            'AMP_DailyMotion_Embed_Handler' => array(),
		            'AMP_Vimeo_Embed_Handler' => array(),
		            'AMP_SoundCloud_Embed_Handler' => array(),
		            'AMP_Instagram_Embed_Handler' => array(),
		            'AMP_Vine_Embed_Handler' => array(),
		            'AMP_Facebook_Embed_Handler' => array(),
		            'AMP_Pinterest_Embed_Handler' => array(),
		            'AMP_Gallery_Embed_Handler' => array(),
		            'AMP_Playlist_Embed_Handler'    => array(),
		            'AMP_Wistia_Embed_Handler' => array(),
		        )),
		        apply_filters( 'amp_content_sanitizers', 
		            array( 'AMP_Img_Sanitizer' => array(), 
		            'AMP_Blacklist_Sanitizer' => array(),
		            'AMP_Style_Sanitizer' => array(), 
		            'AMP_Video_Sanitizer' => array(),
		            'AMP_Audio_Sanitizer' => array(),
		            'AMP_Iframe_Sanitizer' => array(
		                 'add_placeholder' => true,
		             ),
		            ) 
		        ) 
		    );
		    $amp_sanitized_footer_content = $sanitizer_obj->get_amp_content();
		    echo $amp_sanitized_footer_content;
		}
	}
}
