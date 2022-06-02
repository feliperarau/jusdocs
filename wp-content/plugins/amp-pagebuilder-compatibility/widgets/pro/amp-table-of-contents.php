<?php
namespace ElementorForAmp\Widgets\Pro;

use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;
use Elementor\Icons_Manager;
use ElementorPro\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Amp_Table_Of_Contents extends Base_Widget {

	public function get_name() {
		return 'table-of-contents';
	}

	public function get_title() {
		return __( 'Table of Contents', 'elementor-pro' );
	}

	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	public function get_categories() {
		return [ 'pro-elements', 'theme-elements-single' ];
	}

	public function get_keywords() {
		return [ 'toc' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'table_of_contents',
			[
				'label' => __( 'Table of Contents', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'label_block' => true,
				'default' => __( 'Table of Contents', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'html_tag',
			[
				'label' => __( 'HTML Tag', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
				],
				'default' => 'h4',
			]
		);

		$this->start_controls_tabs( 'include_exclude_tags', [ 'separator' => 'before' ] );

		$this->start_controls_tab( 'include',
			[
				'label' => __( 'Include', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'headings_by_tags',
			[
				'label' => __( 'Anchors By Tags', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'h2', 'h3', 'h4', 'h5', 'h6' ],
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'container',
			[
				'label' => __( 'Container', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'This control confines the Table of Contents to heading elements under a specific container', 'elementor-pro' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // include

		$this->start_controls_tab( 'exclude',
			[
				'label' => __( 'Exclude', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'exclude_headings_by_selector',
			[
				'label' => __( 'Anchors By Selector', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'CSS selectors, in a comma-separated list', 'elementor-pro' ),
				'default' => [],
				'label_block' => true,
				'frontend_available' => true,
			]
		);

		$this->end_controls_tab(); // exclude

		$this->end_controls_tabs(); // include_exclude_tags

		$this->add_control(
			'marker_view',
			[
				'label' => __( 'Marker View', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'numbers',
				'options' => [
					'numbers' => __( 'Numbers', 'elementor-pro' ),
					'bullets' => __( 'Bullets', 'elementor-pro' ),
				],
				'separator' => 'before',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'circle',
						'dot-circle',
						'square-full',
					],
					'fa-regular' => [
						'circle',
						'dot-circle',
						'square-full',
					],
				],
				'condition' => [
					'marker_view' => 'bullets',
				],
				'skin' => 'inline',
				'label_block' => false,
				'exclude_inline_options' => [ 'svg' ],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // table_of_contents

		$this->start_controls_section(
			'additional_options',
			[
				'label' => __( 'Additional Options', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'word_wrap',
			[
				'label' => __( 'Word Wrap', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'return_value' => 'ellipsis',
				'prefix_class' => 'elementor-toc--content-',
			]
		);

		$this->add_control(
			'minimize_box',
			[
				'label' => __( 'Minimize Box', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'expand_icon',
			[
				'label' => __( 'Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$this->add_control(
			'collapse_icon',
			[
				'label' => __( 'Minimize Icon', 'elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-chevron-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'minimize_box' => 'yes',
				],
			]
		);

		$breakpoints = Responsive::get_breakpoints();

		$this->add_control(
			'minimized_on',
			[
				'label' => __( 'Minimized On', 'elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'tablet',
				'options' => [
					/* translators: %d: Breakpoint number. */
					'mobile' => sprintf( __( 'Mobile (< %dpx)', 'elementor-pro' ), $breakpoints['md'] ),
					/* translators: %d: Breakpoint number. */
					'tablet' => sprintf( __( 'Tablet (< %dpx)', 'elementor-pro' ), $breakpoints['lg'] ),
					'none' => __( 'None', 'elementor-pro' ),
				],
				'prefix_class' => 'elementor-toc--minimized-on-',
				'condition' => [
					'minimize_box!' => '',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hierarchical_view',
			[
				'label' => __( 'Hierarchical View', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'collapse_subitems',
			[
				'label' => __( 'Collapse Subitems', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'The "Collapse" option should only be used if the Table of Contents is made sticky', 'elementor-pro' ),
				'condition' => [
					'hierarchical_view' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // settings

		$this->start_controls_section(
			'box_style',
			[
				'label' => __( 'Box', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => '--box-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--box-padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'min_height',
			[
				'label' => __( 'Min Height', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => '--box-min-height: {{SIZE}}{{UNIT}}',
				],
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->end_controls_section(); // box_style

		$this->start_controls_section(
			'header_style',
			[
				'label' => __( 'Header', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'header_background_color',
			[
				'label' => __( 'Background Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--header-background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_text_color',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--header-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'header_typography',
				'selector' => '{{WRAPPER}} .elementor-toc__header, {{WRAPPER}} .elementor-toc__header-title',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
			]
		);

		$this->add_control(
			'toggle_button_color',
			[
				'label' => __( 'Icon Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'minimize_box' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--toggle-button-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'header_separator_width',
			[
				'label' => __( 'Separator Width', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--separator-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // header_style

		$this->start_controls_section(
			'list_style',
			[
				'label' => __( 'List', 'elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'list_typography',
				'selector' => '{{WRAPPER}} .elementor-toc__list-item',
				'scheme' => Schemes\Typography::TYPOGRAPHY_3,
			]
		);

		$this->add_control(
			'list_indent',
			[
				'label' => __( 'Indent', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'em',
				],
				'selectors' => [
					'{{WRAPPER}}' => '--nested-list-indent: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'item_text_style' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'item_text_color_normal',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_normal',
			[
				'label' => __( 'Underline', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // normal

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'item_text_color_hover',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_hover',
			[
				'label' => __( 'Underline', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-hover-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // hover

		$this->start_controls_tab( 'active',
			[
				'label' => __( 'Active', 'elementor-pro' ),
			]
		);

		$this->add_control(
			'item_text_color_active',
			[
				'label' => __( 'Text Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_text_underline_active',
			[
				'label' => __( 'Underline', 'elementor-pro' ),
				'type' => Controls_Manager::SWITCHER,
				'selectors' => [
					'{{WRAPPER}}' => '--item-text-active-decoration: underline',
				],
			]
		);

		$this->end_controls_tab(); // active

		$this->end_controls_tabs(); // item_text_style

		$this->add_control(
			'heading_marker',
			[
				'label' => __( 'Marker', 'elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'marker_color',
			[
				'label' => __( 'Color', 'elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_3,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--marker-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'marker_size',
			[
				'label' => __( 'Size', 'elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}}' => '--marker-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // list_style
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
        add_filter('amp_post_template_css', [$this,'amp_elementor_toc_custom_css']);
		$this->add_render_attribute( 'body', 'class', 'elementor-toc__body' );

		if ( $settings['collapse_subitems'] ) {
			$this->add_render_attribute( 'body', 'class', 'elementor-toc__list-items--collapsible' );
		}
		$toc_content = get_post_meta( get_the_ID(), '_elementor_data', true );
		$meta_data = json_decode($toc_content, true);
        $datamain = array();
        $toc_content_filtered = '';
        $i = 0;
        
        $get_headers_htmls = $settings['headings_by_tags'];
        $get_headers_htmls = array_filter($get_headers_htmls);

		if(isset($meta_data[0])){
            foreach($meta_data as $key => $data){
                $datamainheaders[] = recursive_meta_get_headers($data['elements']);
            }
        }
        $datamainheaders = array_filter($datamainheaders);
        foreach($datamainheaders as $data){
			$toc_tag = isset($data['settings']['header_size']) ? $data['settings']['header_size']: 'h2';
        	if($data['widgetType'] != 'table-of-contents' && in_array($toc_tag, $get_headers_htmls)){
        		$toc_content_filtered .= '<li class="elementor-toc__list-item"><div class="elementor-toc__list-item-text-wrapper"><a href="#elementor-toc__heading-anchor-'.$i.'" class="elementor-toc__list-item-text ampforwp-toc-tag-'.$toc_tag.'">'.$data['settings']['title'].'</a></div></li>';
        		$i++;
        	}       	
        }

        foreach ($get_headers_htmls as $key => $heading_tag) {
        	$toc_content = get_the_content();
            preg_match_all('/<'.$heading_tag.'>(.*?)<\/'.$heading_tag.'>/s', $toc_content, $get_editor_headers);
	        foreach($get_editor_headers[1] as $data){
	        	$toc_tag = $heading_tag;
        		if(preg_match('/<a\s(.*?)>(.*?)<\/a>/s', $data)){
        			preg_match_all('/<a\s(.*?)>(.*?)<\/a>/s', $data, $matches);	
        			$toc_content_filtered .= '<li class="elementor-toc__list-item"><div class="elementor-toc__list-item-text-wrapper"><i class="fas fa-circle"></i><a href="#amp-elementor-toc-anchor-'.$i.'" class="elementor-toc__list-item-text ampforwp-toc-tag-'.$toc_tag.'">'.$matches[2][0].'</a></div></li><span class="get_toc_tag">'.$toc_tag.'</span>';
        		}else{
        			$toc_content_filtered .= '<li class="elementor-toc__list-item"><div class="elementor-toc__list-item-text-wrapper"><i class="fas fa-circle"></i><a href="#amp-elementor-toc-anchor-'.$i.'" class="elementor-toc__list-item-text ampforwp-toc-tag-'.$toc_tag.'">'.$data.'</a></div></li><span class="get_toc_tag">'.$toc_tag.'</span>';
        		}
        		$i++;  	
            }
        }
		?>
		<div class="elementor-toc__header amp-toc-cmp" on="tap:AMP.setState({ showToc: false })" tabindex="0" role="button"><?php echo '<' . $settings['html_tag'] . ' class="elementor-toc__header-title">' . $settings['title'] . '</' . $settings['html_tag'] . '>'; ?><span class="fas"><i class="fas fa-chevron-down" hidden [hidden]="testing" on="tap:AMP.setState({ showToc: false, test: false, testing: true})" tabindex="0" role="button"></i><i class="fas fa-chevron-up" [hidden]="test" on="tap:AMP.setState({ showToc: true, test: true, testing: false})" tabindex="0" role="button"></i></span></div>
		<div 
  		[hidden]="showToc" class="ele-toc__body"><ol class="elementor-toc__list-wrapper" ><?php echo $toc_content_filtered; ?><ol></div>
		<?php
	}

	public function amp_elementor_toc_custom_css(){
       echo "html {scroll-behavior: smooth;}.get_toc_tag{display:none;}";
    }
}

function recursive_meta_get_headers($elements){

    if(is_array($elements)){
        foreach($elements as $data){
            if($data['elType']=='column' && count($data['elements'])>0){

                return recursive_meta_get_headers($data['elements']);

            }elseif($data['elType']=='widget' && (isset($data['settings']['title']) && $data['settings']['title'])){
            	
                return $data;
            }
        }
    }
    return false;
}