<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;

class Printec_Elementor_Icon_Box extends Printec_Base_Widgets_Carousel
{

    /**
     * Get widget name.
     *
     * Retrieve icon_box widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'printec-box-icon';
    }

    /**
     * Get widget title.
     *
     * Retrieve icon_box widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return esc_html__('Printec Icon Box', 'printec');
    }

    /**
     * Get widget icon.
     *
     * Retrieve icon_box widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_script_depends()
    {
        return ['printec-elementor-box-icon', 'slick'];
    }

    public function get_categories()
    {
        return array('printec-addons');
    }

    /**
     * Register icon_box widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_icon_box',
            [
                'label' => esc_html__('Icon Box', 'printec'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'printec' ),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'default' => [
                    'value' => 'fas fa-star',
                    'library' => 'fa-solid',
                ],
            ]
        );


        $repeater->add_control(
            'title_text',
            [
                'label' => esc_html__( 'Title & Description', 'printec' ),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your title', 'printec' ),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'description_text',
            [
                'label' => esc_html__( 'Content', 'printec' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'Enter your description', 'printec' ),
                'separator' => 'none',
                'rows' => 10,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'printec' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'printec' ),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_box',
            [
                'label' => esc_html__('Items', 'printec'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );


        $this->add_control(
            'view',
            [
                'label' => esc_html__( 'View', 'printec' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'printec' ),
                    'stacked' => esc_html__( 'Stacked', 'printec' ),
                    'framed' => esc_html__( 'Framed', 'printec' ),
                ],
                'default' => 'default',
                'prefix_class' => 'elementor-view-',

            ]
        );
        $this->add_control(
            'shape',
            [
                'label' => esc_html__( 'Shape', 'printec' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'circle' => esc_html__( 'Circle', 'printec' ),
                    'square' => esc_html__( 'Square', 'printec' ),
                ],
                'default' => 'circle',
                'prefix_class' => 'elementor-shape-',
            ]
        );
        $this->add_responsive_control(
            'position',
            [
                'label' => esc_html__( 'Icon Position', 'printec' ),
                'type' => Controls_Manager::CHOOSE,
                'mobile_default' => 'top',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'printec' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'top' => [
                        'title' => esc_html__( 'Top', 'printec' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'printec' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class' => 'elementor%s-position-',

            ]
        );
        $this->add_responsive_control(
            'column',
            [
                'label' => esc_html__('Columns', 'printec'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 6 => 6],
            ]
        );

        $this->add_responsive_control(
            'gutter',
            [
                'label'      => esc_html__('Gutter', 'printec'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 60,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .column-item' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2); padding-bottom: calc({{SIZE}}{{UNIT}})',
                    '{{WRAPPER}} .row'         => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
                ],
            ]
        );

        $this->end_controls_section();

        //Wrapper

        $this->start_controls_section(
            'section_style_wrapper',
            [
                'label' => esc_html__( 'Wrapper', 'printec' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs( 'wrapper_colors' );

        $this->start_controls_tab(
            'wrapper_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'printec' ),
            ]
        );

        $this->add_control(
            'color_wrapper',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'background_type',
                'label'          => esc_html__('Background', 'printec'),
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .elementor-icon-box-item-inner, {{WRAPPER}} .elementor-icon-box-item-inner:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-box-item-inner',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'wrapper_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'printec' ),
            ]
        );
        $this->add_control(
            'color_hover_wrapper',
            [
                'label'     => esc_html__('Background Color Hover ', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'background_hover',
                'label'          => esc_html__('Background Hover', 'printec'),
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .elementor-icon-box-item-inner:hover, {{WRAPPER}} .elementor-icon-box-item-inner:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );
        $this->add_control(
            'border_hover_wrapper',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow_hover',
                'selector' => '{{WRAPPER}} .elementor-icon-box-item-inner:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'padding_wrapper',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_wrapper',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .elementor-icon-box-item-inner',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'wrapper_radius',
            [
                'label'      => esc_html__('Border Radius', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-icon-box-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

// Icon
        $this->start_controls_section(
            'section_style_icon',
            [
                'label' => esc_html__( 'Icon', 'printec' ),
                'tab'   => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->start_controls_tabs( 'icon_colors' );

        $this->start_controls_tab(
            'icon_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'printec' ),
            ]
        );

        $this->add_control(
            'primary_color',
            [
                'label' => esc_html__( 'Primary Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'secondary_color',
            [
                'label' => esc_html__( 'Secondary Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'icon_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'printec' ),
            ]
        );

        $this->add_control(
            'hover_primary_color',
            [
                'label' => esc_html__( 'Primary Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon-box-item-inner:hover .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon-box-item-inner:hover .elementor-icon, {{WRAPPER}}.elementor-view-default .elementor-icon-box-item-inner:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}}; border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_secondary_color',
            [
                'label' => esc_html__( 'Secondary Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'condition' => [
                    'view!' => 'default',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-view-framed .elementor-icon-box-item-inner:hover .elementor-icon' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}}.elementor-view-stacked .elementor-icon-box-item-inner:hover .elementor-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'printec' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'icon_space',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-icon' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_icon_size',
            [
                'label' => esc_html__( 'Size', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 6,
                        'max' => 300,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'range' => [
                    'em' => [
                        'min' => 0,
                        'max' => 5,
                    ],
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

        $rotate_device_args = [];

        $rotate_device_settings = [
            'default' => [
                'unit' => 'deg',
            ],
        ];

        foreach ( $active_breakpoints as $breakpoint_name => $breakpoint ) {
            $rotate_device_args[ $breakpoint_name ] = $rotate_device_settings;
        }

        $this->add_responsive_control(
            'rotate',
            [
                'label' => esc_html__( 'Rotate', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg', 'grad', 'rad', 'turn' ],
                'default' => [
                    'unit' => 'deg',
                ],
                'tablet_default' => [
                    'unit' => 'deg',
                ],
                'mobile_default' => [
                    'unit' => 'deg',
                ],
                'device_args' => $rotate_device_args,
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon i' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label' => esc_html__( 'Border Width', 'printec' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'view' => 'framed',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'printec' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'view!' => 'default',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'icon_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__( 'Content', 'printec' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'text_align',
            [
                'label' => esc_html__( 'Alignment', 'printec' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'printec' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'printec' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'printec' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justified', 'printec' ),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'printec' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__( 'Top', 'printec' ),
                    'middle' => esc_html__( 'Middle', 'printec' ),
                    'bottom' => esc_html__( 'Bottom', 'printec' ),
                ],
                'default' => 'top',
                'prefix_class' => 'elementor-vertical-align-',
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__( 'Title', 'printec' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'title_bottom_space',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-title' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Title Color Hover', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-box-icon-wrapper:hover .elementor-icon-box-title' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title, {{WRAPPER}} .elementor-icon-box-title a',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Stroke::get_type(),
            [
                'name' => 'text_stroke',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-box-title',
            ]
        );

        $this->add_control(
            'heading_description',
            [
                'label' => esc_html__( 'Description', 'printec' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-box-description' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );
        $this->add_control(
            'description_color_hover',
            [
                'label' => esc_html__( 'Description Color Hover', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-box-icon-wrapper:hover .elementor-icon-box-description' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .elementor-icon-box-description',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'description_shadow',
                'selector' => '{{WRAPPER}} .elementor-icon-box-description',
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel();

    }

    /**
     * Render icon_box widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();


        $this->add_render_attribute( 'icon', 'class', [ 'elementor-icon', 'elementor-animation-' . $settings['hover_animation'] ] );

        if (!empty($settings['icon_box']) && is_array($settings['icon_box'])) {

            $this->add_render_attribute('wrapper', 'class', 'elementor-box-icon-wrapper');

            $this->get_data_elementor_columns();
        }

            // Item
            $this->add_render_attribute('item', 'class', 'column-item elementor-icon-box-item');
            ?>

        <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
            <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                <?php foreach ($settings['icon_box'] as $icon_box): ?>

        <?php if ( ! isset( $icon_box['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
                // add old default
            $icon_box['icon'] = 'fa fa-star';
                }

                $has_icon = ! empty( $icon_box['icon'] );
                $icon_tag = 'span';
                if ( ! empty( $icon_box['link']['url'] ) ) {
                $icon_tag = 'a';

                $this->add_link_attributes( 'link', $icon_box['link'] );
                }

                if ( $has_icon ) {
                $this->add_render_attribute( 'i', 'class', $icon_box['icon'] );
                $this->add_render_attribute( 'i', 'aria-hidden', 'true' );
                }
                if ( ! $has_icon && ! empty( $icon_box['selected_icon']['value'] ) ) {
                $has_icon = true;
                }
                $migrated = isset( $icon_box['__fa4_migrated']['selected_icon'] );
                $is_new = ! isset( $icon_box['icon'] ) && Icons_Manager::is_migration_allowed();
        ?>
                    <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                    <div class="elementor-icon-box-item-inner">
                        <?php if ( $has_icon ) : ?>
                        <div class="elementor-icon-box-icon">
                                <<?php Utils::print_validated_html_tag( $icon_tag ); ?> <?php $this->print_render_attribute_string( 'icon' ); ?> <?php $this->print_render_attribute_string( 'link' ); ?>>
                                <?php
                                if ( $is_new || $migrated ) {
                                    Icons_Manager::render_icon( $icon_box['selected_icon'], [ 'aria-hidden' => 'true' ] );
                                } elseif ( ! empty( $icon_box['icon'] ) ) {
                                    ?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
                                }
                                ?>
                            </<?php Utils::print_validated_html_tag( $icon_tag ); ?>>
                        </div>
                        <?php endif; ?>

                        <div class="elementor-icon-box-content">
                            <h3 class="elementor-icon-box-title"><?php echo esc_html($icon_box['title_text']);?></h3>
                            <p class="elementor-icon-box-description"><?php echo esc_html($icon_box['description_text']);?></p>
                        </div>
                    </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php }

    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon', '', true );
    }

}

$widgets_manager->register(new Printec_Elementor_Icon_Box());
