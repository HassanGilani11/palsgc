<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Printec_Elementor_Button_Popup extends Elementor\Widget_Base {

    public function get_name() {
        return 'printec-button-popup';
    }

    public function get_title() {
        return esc_html__('Printec Button Popup', 'printec');
    }

    public function get_icon() {
        return 'eicon-button';
    }

    public function get_script_depends() {
        return ['printec-elementor-button-popup', 'magnific-popup'];
    }

    public function get_style_depends() {
        return ['magnific-popup'];
    }

    protected function register_controls() {
        $templates = \Elementor\Plugin::instance()->templates_manager->get_source('local')->get_items();
        $options   = [
            '0' => '— ' . esc_html__('Select', 'printec') . ' —',
        ];

        $types = [];

        foreach ($templates as $template) {
            $options[$template['template_id']] = $template['title'] . ' (' . $template['type'] . ')';
            $types[$template['template_id']]   = $template['type'];
        }
        $this->start_controls_section(
            'section_config',
            [
                'label' => esc_html__('Config', 'printec'),
            ]
        );

        $this->add_control(
            'text',
            [
                'label'       => esc_html__('Text', 'printec'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active' => true,
                ],
                'default'     => esc_html__('Click here', 'printec'),
                'placeholder' => esc_html__('Click here', 'printec'),
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label'            => esc_html__('Icon', 'printec'),
                'type'             => Controls_Manager::ICONS,
                'fa4compatibility' => 'icon',
                'skin'             => 'inline',
                'label_block'      => false,
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label'     => esc_html__('Icon Position', 'printec'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'left',
                'options'   => [
                    'left'  => esc_html__('Before', 'printec'),
                    'right' => esc_html__('After', 'printec'),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );


        $this->add_control(
            'icon_indent',
            [
                'label'     => esc_html__('Icon Spacing', 'printec'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-popup .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .button-popup .elementor-align-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'template_id',
            [
                'label'       => esc_html__('Choose Template', 'printec'),
                'default'     => 0,
                'type'        => Controls_Manager::SELECT,
                'options'     => $options,
                'types'       => $types,
                'label_block' => 'true',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => esc_html__('Button', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__('Icon Size', 'printec'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-popup .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'typography',
                'selector' => '{{WRAPPER}} .button-popup .elementor-button-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'     => 'text_shadow',
                'selector' => '{{WRAPPER}} .button-popup',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'printec'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label'     => esc_html__('Text Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .button-popup' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'background',
                'label'          => esc_html__('Background', 'printec'),
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .button-popup',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'printec'),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label'     => esc_html__('Text Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .button-popup:hover, {{WRAPPER}} .button-popup:focus'         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .button-popup:hover svg, {{WRAPPER}} .button-popup:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'           => 'button_background_hover',
                'label'          => esc_html__('Background', 'printec'),
                'types'          => ['classic', 'gradient'],
                'exclude'        => ['image'],
                'selector'       => '{{WRAPPER}} .button-popup:hover, {{WRAPPER}} .button-popup:focus',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .button-popup:hover, {{WRAPPER}} .button-popup:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__('Hover Animation', 'printec'),
                'type'  => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'border',
                'selector'  => '{{WRAPPER}} .button-popup',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label'      => esc_html__('Border Radius', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .button-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .button-popup',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .button-popup' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator'  => 'before',
            ]
        );

        $this->add_control(
            'btn_bgcolor_hover',
            [
                'label'     => esc_html__('Background hover Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .button-popup:hover:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');

        $this->add_render_attribute('button', 'class', 'button-popup');
        $this->add_render_attribute('button', 'role', 'button');

        $this->set_render_attribute('button', 'href', '#printec-button-popup-' . esc_attr($this->get_id()));
        $this->add_render_attribute('button', 'data-effect', 'mfp-zoom-in');
        $this->add_render_attribute('wrapper', 'class', 'printec-button-popup');
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <a <?php $this->print_render_attribute_string('button'); ?>>
                <?php $this->render_text(); ?>
            </a>
        </div>
        <div id="printec-button-popup-<?php echo esc_attr($this->get_id()); ?>" class="mfp-hide button-popup-content">
            <?php
            if (!empty($settings['template_id'])) {
                echo Plugin::instance()->frontend->get_builder_content_for_display($settings['template_id']);
            } else {
                echo esc_html__('No Templates', 'printec');
            }
            ?>
        </div>
        <?php

    }

    protected function render_text() {
        $settings = $this->get_settings_for_display();

        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new   = empty($settings['icon']) && Elementor\Icons_Manager::is_migration_allowed();

        if (!$is_new && empty($settings['icon_align'])) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings('icon_align');
        }

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align'      => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text'            => [
                'class' => 'elementor-button-text',
            ],
        ]);

        $this->add_inline_editing_attributes('text', 'none');
        ?>
        <span <?php echo printec_elementor_get_render_attribute_string('content-wrapper', $this); ?>>
			<?php if (!empty($settings['icon']) || !empty($settings['selected_icon']['value'])) : ?>
                <span <?php echo printec_elementor_get_render_attribute_string('icon-align', $this); ?>>
				<?php if ($is_new || $migrated) :
                    Elementor\Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                else : ?>
                    <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                <?php endif; ?>
			</span>
            <?php endif; ?>
            <div class="printec-icon">
                <span class="icon-1"></span>
                <span class="icon-2"></span>
                <span class="icon-3"></span>
            </div>
			<span <?php $this->print_render_attribute_string('text'); ?>><?php echo esc_html($settings['text']); ?></span>
		</span>
        <?php
    }

}

$widgets_manager->register(new Printec_Elementor_Button_Popup());
