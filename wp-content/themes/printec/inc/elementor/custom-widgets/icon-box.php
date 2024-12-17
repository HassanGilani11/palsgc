<?php
use Elementor\Controls_Manager;

add_action('elementor/element/icon-box/section_style_content/before_section_end', function ($element, $args) {
	$element->add_control(
		'icon_box_title_hover',
		[
			'label'     => esc_html__('Color Title Hover', 'printec'),
			'type'      => Controls_Manager::COLOR,
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .elementor-icon-box-wrapper:hover .elementor-icon-box-content .elementor-icon-box-title' => 'color: {{VALUE}};',
			],
		]
	);
}, 10, 2);

add_action('elementor/element/icon-box/section_style_icon/before_section_end', function ($element, $args) {
    $element->add_control(
        'icon_box_border_1',
        [
            'label'     => esc_html__('Color Border', 'printec'),
            'type'      => Controls_Manager::COLOR,
            'default'   => '',
            'selectors' => [
                '{{WRAPPER}}.elementor-view-framed .elementor-icon' => 'border-color: {{VALUE}};',
            ],
        ]
    );

    $element->add_control(
            'icon_style_theme',
            [
                'label'        => esc_html__('Theme Style', 'printec'),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => '',
                'prefix_class' => 'icon-style-',
            ]
    );

    $element->add_group_control(\Elementor\Group_Control_Background::get_type(),[
        'name'           => 'background_iconbox',
        'label'          => esc_html__('Background', 'printec'),
        'types'          => ['gradient'],
        'exclude'        => ['image'],
        'condition' => [
            'view' => 'stacked',
            'shape' => 'circle',
        ],
        'selector'       => '{{WRAPPER}} .elementor-icon',
    ]);

    $element->add_group_control(\Elementor\Group_Control_Box_Shadow::get_type(),[
        'name'      => 'icon_box_box_shadow',
        'label'     => esc_html__('Box Shadow', 'printec'),
        'condition' => [
                    'view' => 'stacked',
                    'shape' => 'circle',
        ],
        'selector'       => '{{WRAPPER}} .elementor-icon',
    ]);

}, 10, 2);

