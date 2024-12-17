<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;

add_action( 'elementor/element/image-box/section_image/before_section_end', function ($element, $args ) {
    $element->add_control(
        'image_box_hover_effects',
        [
            'label'        => esc_html__('Hover Effects', 'printec'),
            'type'         => Controls_Manager::SWITCHER,

            'prefix_class' => 'elementor-image-box-hover-effects-'
        ]
    );
	$element->add_control(
        'transformation',
        [
            'label' => esc_html__('Hover Animation', 'printec'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                'none' => 'None',
                'zoom-in' => 'Zoom In',
                'zoom-out' => 'Zoom Out',
                'move-up-custom' => 'Move Up',
                'move-down-custom' => 'Move Down',
                'move-left-custom' => 'Move Left',
                'move-right-custom' => 'Move Right',
            ],
            'default' => 'zoom-in',
            'prefix_class' => 'elementor-transform-',
            'condition' => [
                'image_box_hover_effects' => 'yes',
            ],
        ]
	);

    $element->add_responsive_control(
        'image_box_spacing',
        [
            'label' => esc_html__('Spacing hover effects', 'printec'),
            'type' => Controls_Manager::SLIDER,
            'range'     => [
                'px' => [
                    'min' => -100,
                    'max' => 100,
                ],
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-transform-move-down-custom .elementor-image-box-img img' => '-ms-transform: translateY({{SIZE}}{{UNIT}});-o-transform: translateY({{SIZE}}{{UNIT}});-moz-transform: translateY({{SIZE}}{{UNIT}});-webkit-transform: translateY({{SIZE}}{{UNIT}});transform: translateY({{SIZE}}{{UNIT}});',
                '{{WRAPPER}}.elementor-transform-move-up-custom:hover .elementor-image-box-img img' => '-ms-transform: translateY({{SIZE}}{{UNIT}});-o-transform: translateY({{SIZE}}{{UNIT}});-moz-transform: translateY({{SIZE}}{{UNIT}});-webkit-transform: translateY({{SIZE}}{{UNIT}});transform: translateY({{SIZE}}{{UNIT}});',
                '{{WRAPPER}}.elementor-transform-move-left-custom:hover .elementor-image-box-img img' => '-ms-transform: translateX({{SIZE}}{{UNIT}});-o-transform: translateX({{SIZE}}{{UNIT}});-moz-transform: translateX({{SIZE}}{{UNIT}});-webkit-transform: translateX({{SIZE}}{{UNIT}});transform: translateX({{SIZE}}{{UNIT}});',
                '{{WRAPPER}}.elementor-transform-move-right-custom .elementor-image-box-img img' => '-ms-transform: translateX({{SIZE}}{{UNIT}});-o-transform: translateX({{SIZE}}{{UNIT}});-moz-transform: translateX({{SIZE}}{{UNIT}});-webkit-transform: translateX({{SIZE}}{{UNIT}});transform: translateX({{SIZE}}{{UNIT}});',
            ],

            'conditions' => [
                'relation' => 'and',
                'terms'    => [
                    [
                        'name'     => 'image_box_hover_effects',
                        'operator' => '==',
                        'value'    => 'yes',
                    ],
                    [
                        'name'     => 'transformation',
                        'operator' => '!==',
                        'value'    => 'none',
                    ],
                     [
                        'name'     => 'transformation',
                        'operator' => '!==',
                        'value'    => 'zoom-in',
                    ],
                     [
                        'name'     => 'transformation',
                        'operator' => '!==',
                        'value'    => 'zoom-out',
                    ],
                ],
            ],
        ]
    );
}, 10, 2 );

add_action( 'elementor/element/image-box/section_style_image/before_section_end', function ($element, $args ) {
    $element->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'image_box-shadow',
            'selector' => '{{WRAPPER}} img',
        ]
    );

}, 10, 2 );
