<?php
// Button
use Elementor\Controls_Manager;

add_action('elementor/element/button/section_button/after_section_end', function ($element, $args) {

    $element->update_control(
        'button_type',
        [
            'label'        => esc_html__('Type', 'printec'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'default',
            'options'      => [
                'default'   => esc_html__('Default', 'printec'),
                'outline' => esc_html__('OutLine', 'printec'),
                'info'    => esc_html__('Info', 'printec'),
                'success' => esc_html__('Success', 'printec'),
                'warning' => esc_html__('Warning', 'printec'),
                'danger'  => esc_html__('Danger', 'printec'),
                'link'  => esc_html__('Link', 'printec'),
            ],
            'prefix_class' => 'elementor-button-',
        ]
    );
}, 10, 2);

add_action('elementor/element/button/section_button/before_section_end', function ($element, $args) {
    $element->add_control(
        'effect_icon_hover',
        [
            'label' => esc_html__('Icon Effect Hover', 'printec'),
            'type' => Controls_Manager::SWITCHER,
            'condition' => [
                'selected_icon[value]!' => '',
            ],
            'prefix_class' => 'effect-icon-',
        ]
    );

    $element->add_control(
        'icon_rotate',
        [
            'label'     => esc_html__('Icon Rotate', 'printec'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => [ 'deg', 'grad', 'rad', 'turn' ],

            'selectors' => [
                '{{WRAPPER}}.effect-icon-yes .elementor-button .elementor-button-icon i' => '-ms-transform: rotate( {{SIZE}}{{UNIT}});-o-transform: rotate( {{SIZE}}{{UNIT}});-moz-transform: rotate( {{SIZE}}{{UNIT}});-webkit-transform: rotate( {{SIZE}}{{UNIT}});transform: rotate( {{SIZE}}{{UNIT}});',
                '{{WRAPPER}}.effect-icon-yes .elementor-button:hover .elementor-button-icon i' => '-ms-transform: rotate(0);-o-transform: rotate(0);-moz-transform: rotate(0);-webkit-transform: rotate(0);transform: rotate(0);',
            ],
            'condition' => [
                'selected_icon[value]!' => '',
                'effect_icon_hover' => 'yes',
            ],
        ]
    );

}, 10, 2);

add_action('elementor/element/button/section_style/after_section_end', function ($element, $args) {

    $element->update_control(
        'background_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-widget-button .elementor-button' => 'background-color: {{VALUE}};',
                '{{WRAPPER}}.elementor-widget-button.elementor-button-outline .elementor-button' => 'border-color: {{VALUE}};',
                //'{{WRAPPER}}.elementor-button-default .elementor-button' => 'background-color: transparent;',
            ],

        ]
    );

    $element->update_control(
        'button_text_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-link .elementor-button .elementor-button-text:after,{{WRAPPER}}.elementor-button-link .elementor-button .elementor-button-text:before' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $element->update_control(
        'hover_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-link .elementor-button:hover .elementor-button-text:after, {{WRAPPER}}.elementor-button-link .elementor-button:hover .elementor-button-text:before' => 'background-color: {{VALUE}};',
            ],
        ]
    );


}, 10, 2);

add_action('elementor/element/button/section_style/before_section_end', function ($element, $args) {

    $element->add_control(
        'icon_button_size',
        [
            'label'     => esc_html__('Icon Size', 'printec'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => 6,
                    'max' => 300,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button .elementor-button-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .elementor-button .elementor-button-icon'   => 'display: flex; align-items: center;',
            ],
            'condition' => [
                'selected_icon[value]!' => '',
            ],
        ]
    );
    $element->add_control(
        'button_icon_color',
        [
            'label'     => esc_html__('Icon Color', 'printec'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-button-icon' => 'color: {{VALUE}};',
            ],

        ]
    );

    $element->add_control(
        'button_icon_color_hover',
        [
            'label'     => esc_html__('Icon Color Hover', 'printec'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}} .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}};',
            ],

        ]
    );

}, 10, 2);




