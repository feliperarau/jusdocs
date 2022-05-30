<?php
if(!class_exists('AMP_PC_ET_Builder_Module_Tabs')){
class AMP_PC_ET_Builder_Module_Tabs extends ET_Builder_Module {
	function init() {
		$this->name            = esc_html__( 'Tabs', 'et_builder' );
		$this->plural          = esc_html__( 'Tabs', 'et_builder' );
		$this->slug            = 'et_pb_tabs';
		$this->vb_support      = 'on';
		$this->child_slug      = 'et_pb_tab';
		$this->child_item_text = esc_html__( 'Tab', 'et_builder' );
		$this->main_css_element = '%%order_class%%.et_pb_tabs';

		$this->advanced_fields = array(
			'borders'               => array(
				'default' => array(
					'css'      => array(
						'main' => array(
							'border_radii'  => $this->main_css_element,
							'border_styles' => $this->main_css_element,
						),
					),
					'defaults' => array(
						'border_radii'  => 'on||||',
						'border_styles' => array(
							'width' => '1px',
							'color' => '#d9d9d9',
							'style' => 'solid',
						),
					),
				),
			),
			'fonts'                 => array(
				'tab' => array(
					'label'    => esc_html__( 'Tab', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_tabs_controls li, {$this->main_css_element} .et_pb_tabs_controls li a",
						'color' => "{$this->main_css_element} .et_pb_tabs_controls li a",
					),
					'hide_text_align' => true,
				),
				'body'   => array(
					'label'    => esc_html__( 'Body', 'et_builder' ),
					'css'      => array(
						'main' => "{$this->main_css_element} .et_pb_all_tabs .et_pb_tab",
						'limited_main' => "{$this->main_css_element} .et_pb_all_tabs .et_pb_tab, {$this->main_css_element} .et_pb_all_tabs .et_pb_tab p",
						'line_height' => "{$this->main_css_element} .et_pb_tab p",
					),
				),
			),
			'background'            => array(
				'css' => array(
					'main' => "{$this->main_css_element} .et_pb_all_tabs",
				),
				'settings' => array(
					'color' => 'alpha',
				),
			),
			'margin_padding' => array(
				'css' => array(
					'padding' => '%%order_class%% .et_pb_tab',
					'important' => array( 'custom_margin' ), // needed to overwrite last module margin-bottom styling
				),
			),
			'text'                  => false,
			'button'                => false,
		);

		$this->custom_css_fields = array(
			'tabs_controls' => array(
				'label'    => esc_html__( 'Tabs Controls', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls',
			),
			'tab' => array(
				'label'    => esc_html__( 'Tab', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls li',
			),
			'active_tab' => array(
				'label'    => esc_html__( 'Active Tab', 'et_builder' ),
				'selector' => '.et_pb_tabs_controls li.et_pb_tab_active',
			),
			'tabs_content' => array(
				'label'    => esc_html__( 'Tabs Content', 'et_builder' ),
				'selector' => '.et_pb_tab',
			),
		);

		$this->help_videos = array(
			array(
				'id'   => esc_html( 'xk2Ite-oFhg' ),
				'name' => esc_html__( 'An introduction to the Tabs module', 'et_builder' ),
			),
		);
	}

	function get_fields() {
		$fields = array(
			'active_tab_background_color' => array(
				'label'             => esc_html__( 'Active Tab Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'tab',
				'hover'             => 'tabs',
			),
			'inactive_tab_background_color' => array(
				'label'             => esc_html__( 'Inactive Tab Background Color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'tab',
				'hover'             => 'tabs',
			),
		);
		return $fields;
	}

	public function get_transition_fields_css_props() {
		$fields = parent::get_transition_fields_css_props();

		$fields['inactive_tab_background_color'] = array( 'background-color' => '%%order_class%% .et_pb_tabs_controls li' );
		$fields['active_tab_background_color'] = array( 'background-color' => '%%order_class%% .et_pb_tabs_controls li' );

		return $fields;
	}
	public function amp_divi_inline_styles(){
    	$standard_styles = '/* Tabs Module */
			.et_pb_tabs {
				border: 1px solid #d9d9d9;
			}
			ul.et_pb_tabs_controls {
				background-color: #f4f4f4;
			}
			ul.et_pb_tabs_controls:after {
				display: block;
				visibility: visible;
				position: relative;
				z-index: 9;
				top: -1px;
				border-top: 1px solid #d9d9d9;
				content: "";
			}
			.et_pb_tabs_controls li {
				display: table;
				float: left;
				position: relative;
				z-index: 11;
				max-width: 100%;
				height: 100%;
				border-right: 1px solid #d9d9d9;
				font-weight: 600;
				line-height: 1.7em;
				cursor: pointer;
			}
			.et_pb_tabs_controls li:not(.et_pb_tab_active):last-child {
				border-right: none;
			}
			.et_pb_tabs_controls li a {
				display: table-cell;
				padding: 4px 30px 4px;
				color: #666;
				line-height: inherit;
				vertical-align: middle;
				text-decoration: none;
			}
			.et_pb_tabs_controls li.et_pb_tab_active {
				background-color: #fff;
			}
			.et_pb_tab_active a {
				color: #333!important;
			}
			.et_pb_tab p:last-of-type {
				padding-bottom: 0;
			}
			.et_pb_all_tabs {
				background-color: #fff;
			}
			.et_pb_all_tabs > div,
			.et_pb_toggle_close .et_pb_toggle_content {
				display: none;
			}
			.et_pb_all_tabs .et_pb_active_content {
				display: block;
			}
			.et_pb_tab {
				padding: 24px 30px;
			}
			.et_pb_tab_content {
				position: relative;
			}
			/* Column Adjustments */
			.et_pb_column_1_3 .et_pb_tabs_controls,
			.et_pb_column_1_4 .et_pb_tabs_controls {
				border-bottom: none;
			}
			.et_pb_column_1_3 .et_pb_tabs_controls li,
			.et_pb_column_1_4 .et_pb_tabs_controls li,
			.et_pb_column_3_8 .et_pb_tabs_controls li {
				float: none;
				border-right: none;
				border-bottom: 1px solid #d9d9d9;
			}
			.et_pb_column_1_3 .et_pb_tabs_vertically_stacked .et_pb_tabs_controls li,
			.et_pb_column_1_4 .et_pb_tabs_vertically_stacked .et_pb_tabs_controls li,
			.et_pb_column_3_8 .et_pb_tabs_vertically_stacked .et_pb_tabs_controls li {
				width: 100%;
			}
			';
		$inline_styles = '.ampTabContainer {
			    display: flex;
			    flex-wrap: wrap;
			}
			.tabButton[selected] {
			    outline: none;
			    background: #ccc;
			}
			.tabButton {
			    list-style: none;
			    flex-grow: 1;
			    text-align: center;
			    cursor: pointer;
			}
			.tabContent {
			    display: none;
			    width: 100%;
			    order: 1; /* must be greater than the order of the tab buttons to flex to the next line */
			    border: 1px solid #ccc;
			}
			.tabButton[selected]+.tabContent {
			    display: block;
			}
			amp-selector {
			          padding: 1rem;
			          margin: 1rem;
			}
			.et_pb_tabs .tabButton[selected]{
			      background:#fff;
			      border-bottom: 1px solid #f4f4f4;
			      color:#333;
			    }
			    .et_pb_tabs amp-selector [option][selected]{
			      outline:none;
			    }
			    .et_pb_tabs .tabButton {
			      font-size: 16px;
			      font-weight: 600;
			      padding: 10px;
			      background: #f4f4f4;
			      color: #666;
			      border-right: 1px solid #d9d9d9;
			      border-bottom: 1px solid #d9d9d9;
			    }
			    .et_pb_tabs .tabContent {
			      padding: 25px;
			      font-size: 16px;
			      line-height: 1.3;
			      font-weight: 400;
			      color: #797f7f;
			      border:none;
			    }
			    .et_pb_tabs .ampTabContainer{
			      border:1px solid #d9d9d9;
			      padding:0;
			    }';
        echo $standard_styles.''.$inline_styles;
  	}
  	function amp_divi_pagebuilder_scripts($data){
  		$data['amp_component_scripts']['amp-selector'] = 'https://cdn.ampproject.org/v0/amp-selector-0.1.js';
  		return $data;
  	}

	function render( $attrs, $content = null, $render_slug='' ) {
		add_filter('amp_post_template_data', [$this, 'amp_divi_pagebuilder_scripts']);
		add_action('amp_post_template_css',array($this,'amp_divi_inline_styles'));
		$active_tab_background_color       = $this->props['active_tab_background_color'];
		$inactive_tab_background_color     = $this->props['inactive_tab_background_color'];
		$all_tabs_content = $this->content;

		preg_match_all("/<div class=\"amp_et_pb_tab_content(.*?)\"(.*?)>(.*?)<\/div><!--et_pb_tab_end-->/si", $all_tabs_content, $matchesContent);
		$all_tabs_contents = $matchesContent[0];
		global $et_pb_tab_titles;
		global $et_pb_tab_classes;
		if ( '' !== $inactive_tab_background_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .et_pb_tabs_controls li',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $inactive_tab_background_color )
				),
			) );
		}
		if ( '' !== $active_tab_background_color ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .et_pb_tabs_controls li.et_pb_tab_active',
				'declaration' => sprintf(
					'background-color: %1$s;',
					esc_html( $active_tab_background_color )
				),
			) );
		}
		$tabs = '';
		$i = 0;
		if ( ! empty( $et_pb_tab_titles ) ) {
			foreach ( $et_pb_tab_titles as $tab_title ){
				++$i;
				$tabs .= sprintf( '<div role="tab" class="tabButton" '.( $i==1 ?'selected':'').' option="'.$i.'">%2$s</div>
					<div role="tabpanel" class="tabContent">%4$s</div>',
					( 1 == $i ? ' et_pb_tab_active' : '' ),
					esc_html( $tab_title ),
					esc_attr( ltrim( $et_pb_tab_classes[ $i-1 ] ) ),
					$all_tabs_contents[$i-1]
				);
			}
		}
		$video_background = $this->video_background();
		$parallax_image_background = $this->get_parallax_image_background();
		$et_pb_tab_titles = $et_pb_tab_classes = array();
		// Module classnames
		$this->add_classname( array(
			$this->get_text_orientation_classname(),
		) );
		$output = sprintf(
			'<amp-selector role="tablist" layout="container" %3$s class="%4$s ampTabContainer">
				%6$s
				%5$s
					%1$s
			</amp-selector> <!-- .et_pb_tabs -->',
			$tabs,
			$all_tabs_content,
			$this->module_id(),
			$this->module_classname( $render_slug ),
			$video_background,
			$parallax_image_background
 		);
		return $output;
	}

	/* public function process_box_shadow( $function_name ) {
		$boxShadow = ET_Builder_Module_Fields_Factory::get( 'BoxShadow' );
		$style     = $boxShadow->get_value( $this->props );

		if ( $boxShadow->is_inset( $style ) ) {
			$this->advanced_fields['box_shadow'] = array(
				'default' => array(
					'css' => array(
						'main' => '%%order_class%% .et-pb-active-slide',
					),
				),
			);


		}

		parent::process_box_shadow( $function_name );
	} */
}

$accordianItemObj = new AMP_PC_ET_Builder_Module_Tabs();//et_pb_accordion_item
remove_shortcode( 'et_pb_tabs' );
add_shortcode( 'et_pb_tabs', array($accordianItemObj, '_render'));
}