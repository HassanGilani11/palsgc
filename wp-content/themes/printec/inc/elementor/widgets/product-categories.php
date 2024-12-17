<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!printec_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/**
 * Elementor Printec_Elementor_Products_Categories
 * @since 1.0.0
 */
class Printec_Elementor_Products_Categories extends Printec_Base_Widgets_Carousel {

    public function get_categories() {
        return array('printec-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'printec-product-categories';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Product Categories', 'printec');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_script_depends() {
        return ['printec-elementor-product-categories', 'slick'];
    }

    public function on_export($element) {
        unset($element['settings']['category_image']['id']);

        return $element;
    }

    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Categories', 'printec'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $repeater = new Repeater();

        $repeater->add_control(
            'categories_name',
            [
                'label' => esc_html__('Alternate Name', 'printec'),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'categories_description',
            [
                'label' => esc_html__('Description', 'printec'),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'categories',
            [
                'label'       => esc_html__('Categories', 'printec'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_categories(),
                'multiple'    => false,
            ]
        );

        $repeater->add_control(
            'categories_type',
            [
                'label'   => esc_html__('Type', 'printec'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'cate_image',
                'options' => [
                    'cate_image' => esc_html__('Image', 'printec'),
                    'cate_icon'  => esc_html__('Icon', 'printec'),
                ]
            ]
        );

        $repeater->add_control(
            'category_icon',
            [
                'label'     => esc_html__('Icon', 'printec'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
                'condition' => [
                    'categories_type' => 'cate_icon'
                ]
            ]
        );

        $repeater->add_control(
            'category_image',
            [
                'label'      => esc_html__('Choose Image', 'printec'),
                'default'    => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
                'condition'  => [
                    'categories_type' => 'cate_image'
                ]
            ]

        );

        $repeater->add_control(
            'tab_color_item',
            [
                'label'     => __('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.product-cat .product-cat-link' => 'background-color: {{VALUE}}',
                ],
            ]
        );


        $this->add_control(
            'categories_list',
            [
                'label'       => esc_html__('Items', 'printec'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ categories }}}',
            ]
        );
        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `brand_image_size` and `brand_image_custom_dimension`.
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label'     => esc_html__('Alignment', 'printec'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => esc_html__('Left', 'printec'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'printec'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => esc_html__('Right', 'printec'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'default'   => 'center',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-caption' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'product_cate_layout',
            [
                'label'   => esc_html__('Layout', 'printec'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Layout 1', 'printec'),
                    '2' => esc_html__('Layout 2', 'printec'),
                    '3' => esc_html__('Layout 3', 'printec'),
                    '4' => esc_html__('Layout 4', 'printec'),
                ]
            ]
        );

        $this->add_control(
            'layout_special',
            array(
                'label'        => esc_html__('Layout Special', 'printec'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'layout-special-',
                'condition'    => [
                    'product_cate_layout' => '1'
                ],
            )
        );


        $this->add_control(
            'layout_style',
            [
                'label'        => esc_html__('Style Special', 'printec'),
                'type'         => Controls_Manager::SELECT,
                'default'      => '1',
                'options'      => [
                    '1' => 'Style 1',
                    '2' => 'Style 2',
                ],
                'prefix_class' => 'style-special-',

                'condition' => [
                    'layout_special'      => 'yes',
                    'product_cate_layout' => '1'
                ],
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'printec'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 1,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8],
            ]
        );
        $this->add_responsive_control(
            'product_gutter',
            [
                'label'      => esc_html__('Gutter', 'printec'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .column-item'                   => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .printec-carousel .column-item' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .row'                           => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_cate_style',
            [
                'label' => esc_html__('Box', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'box_bg',
            [
                'label'     => __('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-cat' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .layout-2 .product-cat, {{WRAPPER}} .layout-3 .product-cat-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                    '{{WRAPPER}} .layout-1 .product-cat-link .product-cat-caption'                => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'min-height',
            [
                'label'      => esc_html__('Height', 'printec'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 1000,
                    ],
                    'vh' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'vertical_position',
            [
                'label'        => esc_html__('Vertical Position', 'printec'),
                'type'         => Controls_Manager::CHOOSE,
                'options'      => [
                    'top'    => [
                        'title' => esc_html__('Top', 'printec'),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'middle' => [
                        'title' => esc_html__('Middle', 'printec'),
                        'icon'  => 'eicon-v-align-middle',
                    ],
                    'bottom' => [
                        'title' => esc_html__('Bottom', 'printec'),
                        'icon'  => 'eicon-v-align-bottom',
                    ],
                ],
                'prefix_class' => 'product-cat-valign-',
                'separator'    => 'none',
            ]
        );

        $this->add_control(
            'box_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_box_shadow',
                'selector' => '{{WRAPPER}} .product-cat',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'caption_style',
            [
                'label' => esc_html__('Caption', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'caption_padding',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-cat-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
            [
                'label' => esc_html__('Image', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_shadow',
                'selector' => '{{WRAPPER}} .category-product-img img',
            ]
        );


        $this->add_responsive_control(
            'image_width',
            [
                'label'          => esc_html__('Width', 'printec'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_max_width',
            [
                'label'          => esc_html__('Max Width', 'printec'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => '%',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['%', 'px', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_height',
            [
                'label'          => esc_html__('Height', 'printec'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'unit' => 'px',
                ],
                'size_units'     => ['px', 'vh'],
                'range'          => [
                    'px' => [
                        'min' => 1,
                        'max' => 500,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .category-product-img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .category-product-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_hover_effects',
            [
                'label' => esc_html__('Hover Effects', 'printec'),
                'type'  => Controls_Manager::SWITCHER,

                'prefix_class' => 'elementor-title-hover-effects-'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tilte_typography',
                'selector' => '{{WRAPPER}} .cat-title',
            ]
        );


        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .cat-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .cat-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tab_title');
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => esc_html__('Normal', 'printec'),
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'tab_title_bgcolor',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => esc_html__('Hover', 'printec'),
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__('Hover Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .cat-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tab_title_bgcolor_hover',
            [
                'label'     => esc_html__('Background Hover Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .cat-title' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'total_style',
            [
                'label' => esc_html__('Total', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'total_typography',
                'selector' => '{{WRAPPER}} .cat-title span',
            ]
        );
        $this->add_control(
            'total_color_hover',
            [
                'label'     => esc_html__('Color Count', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .cat-title span' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'icon_style',
            [
                'label' => esc_html__('Icon', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_space',
            [
                'label'     => esc_html__('Spacing', 'printec'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size_1',
            [
                'label'     => esc_html__('Size', 'printec'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 6,
                        'max' => 200,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tab_icon');
        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label' => esc_html__('Normal', 'printec'),
            ]
        );
        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_background_color',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .category-product-icon'                  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .layout-2 .category-product-icon:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label' => esc_html__('Hover', 'printec'),
            ]
        );
        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .category-product-icon i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'icon_background_color_hover',
            [
                'label'     => esc_html__('Background Color Hover', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-cat-link:hover .category-product-icon'                  => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .layout-2 .product-cat-link:hover .category-product-icon:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel();
    }

    protected function get_product_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }
        return $results;
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['categories_list']) && is_array($settings['categories_list'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-categories-item-wrapper');
            $this->add_render_attribute('row', 'class', 'layout-' . esc_attr($settings['product_cate_layout']));

            // Carousel
            $this->get_data_elementor_columns();

            // Item
            $this->add_render_attribute('item', 'class', 'column-item elementor-categories-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['categories_list'] as $index => $item) { ?>

                        <?php
                        $class_item            = 'elementor-repeater-item-' . $item['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['product-cat', $class_item],]); ?>

                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <?php if (empty($item['categories'])) {
                                echo esc_html__('Choose Category', 'printec');
                                return;
                            }
                            $category = get_term_by('slug', $item['categories'], 'product_cat');
                            if ($category && !is_wp_error($category)) {
                                $query = new WP_Query(array(
                                    'tax_query' => array(
                                        array(
                                            'taxonomy'         => 'product_cat',
                                            'field'            => 'id',
                                            'terms'            => $category->term_id,
                                            'include_children' => true,
                                        ),
                                    ),
                                    'nopaging'  => true,
                                    'fields'    => 'ids',
                                ));

                                if (!empty($item['category_image']['id'])) {
                                    $image = Group_Control_Image_Size::get_attachment_image_src($item['category_image']['id'], 'image', $settings);
                                } else {
                                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                    if (!empty($thumbnail_id)) {
                                        $image = wp_get_attachment_url($thumbnail_id);
                                    } else {
                                        $image = wc_placeholder_img_src();
                                    }
                                } ?>

                                <div <?php echo printec_elementor_get_render_attribute_string($tab_title_setting_key, $this); ?>>
                                    <a class="product-cat-link" href="<?php echo esc_url(get_term_link($category)); ?>" title="<?php echo esc_attr($category->name); ?>">
                                        <?php
                                        if ($settings['product_cate_layout'] !== '4') {
                                            if ($item['categories_type'] == 'cate_image') { ?>
                                                <div class="category-product-img">
                                                    <img src="<?php echo esc_url_raw($image); ?>"
                                                         alt="<?php echo esc_attr($category->name); ?>">
                                                </div>
                                            <?php } ?>
                                            <?php if ($item['categories_type'] == 'cate_icon') { ?>
                                                <?php if (!empty($item['category_icon']['value'])) { ?>
                                                    <div class="category-product-icon"><?php \Elementor\Icons_Manager::render_icon($item['category_icon'], ['aria-hidden' => 'true']); ?></div>
                                                <?php } ?>
                                            <?php }
                                        }
                                        ?>
                                        <div class="product-cat-caption">
                                            <div class="cat-title">
                                                <?php echo empty($item['categories_name']) ? esc_html($category->name) : sprintf('%s', $item['categories_name']); ?>
                                                <?php
                                                if ($settings['product_cate_layout'] == '3') {
                                                    ?>
                                                    <div class="cat-total">
                                                        <?php echo esc_html($query->post_count); ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($settings['product_cate_layout'] == '1' || $settings['product_cate_layout'] == '3') {
                                                ?>
                                                <?php if (!empty($item['categories_description'])) { ?>
                                                    <div class="cat-des"><?php echo sprintf('%s', $item['categories_description']); ?></div>
                                                <?php } ?>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </a>
                                </div>
                                <?php
                                wp_reset_postdata();
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php
        }
    }

}

$widgets_manager->register(new Printec_Elementor_Products_Categories());

