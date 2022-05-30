<?php
namespace ElementorForAmp;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
global $amp_elemetor_custom_css;
class Amp_Elementor_Widgets_Loading {
	
	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function include_element_files(){
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'elements/amp-section.php' );
		//require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'elements/amp-column.php' );
	}
	private function include_widgets_files() {
		$theme = wp_get_theme(); // gets the current theme
        if ( 'soledad' == $theme->name || 'soledad' == $theme->parent_theme ) {
        	require_once(AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'soledad-slider-support.php');
        	require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/soledad/penci-popular-posts.php' );
        	require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/soledad/amp-penci-featured-sliders.php' );
        }
        //extended google map
        if (class_exists("EB_Elementor_Google_Map_Class")){
        require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-eb-google-map-extended-widget.php' );
        }
		if(defined('WILCITY_EL_PREFIX')){
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/amp-newgrid.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/wilcity_render_heading.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/wilcity_render_new_grid.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/wilcity_listing_slider_item.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/wilcity_render_listings_tabs.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/wilcity_render_grid_item.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/wilcity/ListingsTabs.php' );
		}
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-accordion.php' );
		if(defined('PREMIUM_ADDONS_BASENAME') && is_plugin_active('premium-addons-for-elementor/premium-addons-for-elementor.php')){
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-premium-grid.php' );
		}
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-tabs.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-counter.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-image-carousel.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-button.php' );

		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-progress.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-toggle.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-video.php' );
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-icon-list.php' );
		if( function_exists('bsf_core_load') || class_exists('Brainstorm_Update_UAEL')){
         require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-image-gallery-uea.php' );
         require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-offcanvas-uae.php' );
         require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-woo-products.php' );
         require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/ultimate-elementor/faq.php' );
        }
		
		require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-image-gallery.php' );
		if(class_exists('Jet_Blog')){
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-blog/jet-blog-smart-tiles.php' );
		}
		if(class_exists('Jet_Blocks')){
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-blocks/amp-jet-block-search.php' );
		}
		if(class_exists('Jet_Elements')){
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-image-comparison.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-table.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-element-slider.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-animated-box.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-testimonials.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-animated-text.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-elements-lottie.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-element-headline.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-element-button.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-element-services.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/jet-elements/jet-element-subscribe-form.php' );
		}
		if(class_exists("\ElementorPro\Plugin")){

			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-form.php' );
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-pricing-table.php' );
 			//Gallery Module
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-gallery.php' );
 			//carousel
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/carousel/amp-carousel-base.php' );
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/carousel/amp-media-carousel.php' );
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/carousel/amp-reviews.php' );
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/carousel/amp-testimonial-carousel.php' );

 			//Posts
            require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/posts/posts.php' );
            require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/posts/skins/skin-base.php' );
            require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/posts/skins/skin-classic.php' );
            require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/posts/skins/skin-cards.php' );
            if(is_plugin_active('ele-custom-skin/ele-custom-skin.php')){
            	require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/posts/skins/ecs-custom-skin.php' );
        	}
 			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-share-buttons.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/nav-menu.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-slides.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/animated-headline.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-facebook-comments.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-blockquote.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-post-comments.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-call-to-action.php' );
			if(function_exists('wc')) {
			   require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-products.php' );
			}
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/amp-template.php');
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-lottie.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-search-form.php' );
			if(class_exists('\Ampforwp_Walker_Nav_Menu') ){
				require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR.'/widgets/pro/amp-nav-menu.php');
			}
			
			if (is_plugin_active('raven/raven.php') ){
				require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-jx-nav-menu.php' );
				require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-site-logo.php' );
				require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/amp-raven-button.php' );
		    }  
		    require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-table-of-contents.php' );
		    require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/pro/amp-countdown.php' );
		}
		if(is_plugin_active('essential-addons-elementor/essential_adons_elementor.php')){
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/essential-addon/amp-ea-counter.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/essential-addon/amp-post-carousel.php' );
			require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/essential-addon/amp-logo-carousel.php' );			

		}
		if(is_plugin_active('essential-addons-for-elementor-lite/essential_adons_elementor.php')){
              require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/essential-addon/amp-data_table.php' );
        }
		if (is_plugin_active('elementskit/elementskit.php') ){
		  require_once( AMP_PAGEBUILDER_COMPATIBILITY_PLUGIN_DIR . 'widgets/elementor-kit/creative-button.php' );
	    }
	}
	public function register_elements(){
		// Register Widgets
		if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) ) {
			$this->include_element_files();
			
			\Elementor\Plugin::instance()->elements_manager->register_element_type( new Elements\Amp_Element_Section );
			//\Elementor\Plugin::instance()->elements_manager->register_element_type( new Elements\Amp_Element_Column );
		}
	}
	public function register_widgets($widgets_manager) {
		$theme = wp_get_theme();
		$rh_elementor_template_sc = $this->render_rehub_elementor_shotcode();
		// Register Widgets
		if ( (function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint()) ||  (function_exists( 'is_wp_amp' ) && is_wp_amp()) || (function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) || $rh_elementor_template_sc) {

			$this->include_widgets_files();
			if ( 'soledad' == $theme->name || 'soledad' == $theme->parent_theme ) {
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Soledad\Amp_PenciPopularPosts());
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Soledad\Amp_PenciFeaturedSliders() );
			}
			// extended google map 
			if (class_exists("EB_Elementor_Google_Map_Class")){
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_EB_Google_Map_Extended() );
            }
			if(defined('WILCITY_EL_PREFIX')){
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wilcity\Amp_NewGrid());
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Widgets\Wilcity\Amp_ListingsTabs());
			}
			if(defined('PREMIUM_ADDONS_BASENAME') && is_plugin_active('premium-addons-for-elementor/premium-addons-for-elementor.php')){
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Premium_Grid() );
		}
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Accordion() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Tabs() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Counter() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_Widget_Icon_List() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Image_Carousel() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\amp_Widget_Button() );
			
			if( function_exists('bsf_core_load') || class_exists('Brainstorm_Update_UAEL')){
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\amp_ue_Image_Gallery() );
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\amp_uae_Offcanvas() );         
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\amp_uae_Woo_Products() );            
            \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\UltimateElementor\amp_uae_FAQ() );
            }
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Progress() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Toggle() );
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Video() );
  			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Image_Gallery() );
  			if(class_exists('Jet_Blog')){
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetBlog\Amp_Jet_Blog_Smart_Tiles() );
  			}
  			if(class_exists('Jet_Blocks')){
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetBlocks\Amp_Jet_Blocks_Search() );
  			}
  			if(class_exists('Jet_Elements')){
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Image_Comparison() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Table() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\AMP_Jet_Elements_Slider() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\AMP_Jet_Elements_Animated_Box() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Testimonials() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Animated_Text() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\AMP_Jet_Elements_Lottie() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Headline() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Button() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Services() );
  				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\JetElements\Amp_Jet_Elements_Subscribe_Form() );
  			}
 			if(class_exists("\ElementorPro\Plugin")){
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Gallery() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Form() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Amp_Price_Table() );

               \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Posts\Posts() );
 				//Carousel
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Carousel\Amp_Media_Carousel() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Carousel\Reviews() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Carousel\Amp_Testimonial_Carousel() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Share_Buttons() );
				 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Nav_Menu() );
				 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Slides() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Animated_Headline() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\AMP_Facebook_Comments() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\AMP_Blockquote() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Post_Comments() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Call_To_Action() );
				if(function_exists('wc')) {
				  \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\AMP_Products() );
				}
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Template() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Lottie() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Search_Form() );
				if(class_exists('\Ampforwp_Walker_Nav_Menu') ){
					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Nav_Menu() );
				}
			
				if (is_plugin_active('raven/raven.php') ){

					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_jxNav_mn() );
					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\amp_raven_Button() );
					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\AMP_JxSt_lg() );
	            }
				
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Table_Of_Contents() );
				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Pro\Amp_Countdown() );
 			}
 			if(is_plugin_active('essential-addons-elementor/essential_adons_elementor.php')){
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\EssentialAddon\Amp_ea_Counter() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\EssentialAddon\Amp_ea_Post_Carousel() );
 				\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\EssentialAddon\Amp_ea_Logo_Carousel() ); 				
 			}
 			if(is_plugin_active('essential-addons-for-elementor-lite/essential_adons_elementor.php')){
               \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\EssentialAddon\AMP_Data_Table() );
 			}

            if (is_plugin_active('elementskit/elementskit.php') ){
 			 \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\ElementsKit\AMP_Elementskit_Widget_Creative_Button() );
 		    }
			
		}

	}

	public function __construct() {
		
		// Register widgets		
		add_action( 'elementor/elements/elements_registered', [ $this, 'register_elements' ], 999999);
		if( defined('ELEMENTOR_PRO_VERSION') && ELEMENTOR_PRO_VERSION > '3.2.1'){
			add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ], 999999 );
		}else{
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ], 999999 );
		}
	}
	public function render_rehub_elementor_shotcode(){
		global $redux_builder_amp;
	 	$theme = wp_get_theme();
	 	if ( 'Rehub theme' == $theme->name) {
	        $get_url = filter_input(INPUT_SERVER, 'REQUEST_URI');
	        $amp_url  = explode('/', $get_url);
	        if( isset($redux_builder_amp['ampforwp-amp-takeover']) && $redux_builder_amp['ampforwp-amp-takeover'] == true || in_array('amp', $amp_url ) || in_array('?amp', $amp_url)){
	            return true;
	        }
	    }
    }
}

// Instantiate Plugin Class
Amp_Elementor_Widgets_Loading::instance();
