<?php

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Printec_Elementor_Widget_Heading extends Elementor\Widget_Base {

    public function get_name() {
        return 'printec_animated-headline';
    }

    public function get_title() {
        return esc_html__('Printec Headline', 'printec');
    }

    public function get_icon() {
        return 'eicon-animated-headline';
    }

    public function get_keywords() {
        return ['headline', 'heading', 'animation', 'title', 'text'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'text_elements',
            [
                'label' => esc_html__('Headline', 'printec'),
            ]
        );

        $this->add_control(
            'before_text',
            [
                'label'       => esc_html__('Before Text', 'printec'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::TEXT_CATEGORY,
                    ],
                ],
                'default'     => esc_html__('This page is', 'printec'),
                'placeholder' => esc_html__('Enter your headline', 'printec'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'highlighted_text',
            [
                'label'       => esc_html__('Highlighted Text', 'printec'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::TEXT_CATEGORY,
                    ],
                ],
                'default'     => esc_html__('Amazing', 'printec'),
                'label_block' => true,
                'separator'   => 'none',
            ]
        );

        $this->add_control(
            'after_text',
            [
                'label'       => esc_html__('After Text', 'printec'),
                'type'        => Controls_Manager::TEXT,
                'dynamic'     => [
                    'active'     => true,
                    'categories' => [
                        TagsModule::TEXT_CATEGORY,
                    ],
                ],
                'placeholder' => esc_html__('Enter your headline', 'printec'),
                'label_block' => true,
                'separator'   => 'none',
            ]
        );

        $this->add_control(
            'link',
            [
                'label'     => esc_html__('Link', 'printec'),
                'type'      => Controls_Manager::URL,
                'dynamic'   => [
                    'active' => true,
                ],
                'separator' => 'before',
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
                    '{{WRAPPER}} .elementor-headline' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'tag',
            [
                'label'   => esc_html__('HTML Tag', 'printec'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'h1'   => 'H1',
                    'h2'   => 'H2',
                    'h3'   => 'H3',
                    'h4'   => 'H4',
                    'h5'   => 'H5',
                    'h6'   => 'H6',
                    'div'  => 'div',
                    'span' => 'span',
                    'p'    => 'p',
                ],
                'default' => 'h2',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__('Headline', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Text Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-headline-plain-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-headline',
            ]
        );

        $this->add_control(
            'heading_words_style',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => esc_html__('Highlighted Text', 'printec'),
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'words_color',
            [
                'label'     => esc_html__('Text Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-headline-dynamic-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'words_typography',
                'global'   => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .elementor-headline-dynamic-text',
                'exclude'  => ['font_size'],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag      = Utils::validate_html_tag($settings['tag']);
        $this->add_render_attribute('headline', 'class', 'elementor-headline');
        if (!empty($settings['link']['url'])) {
            $this->add_link_attributes('url', $settings['link']);
            ?>
            <a <?php $this->print_render_attribute_string('url'); ?>>

            <?php
        }
        ?>
        <<?php Utils::print_validated_html_tag($tag); ?>  <?php $this->print_render_attribute_string('headline'); ?>>
        <?php if (!empty($settings['before_text'])) : ?>
            <span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php $this->print_unescaped_setting('before_text'); ?></span>
        <?php endif; ?>
        <span class="elementor-headline-dynamic-wrapper elementor-headline-text-wrapper">
        <?php if (!empty($settings['highlighted_text'])) : ?>
            <span class="elementor-headline-dynamic-text elementor-headline-text-active"><?php $this->print_unescaped_setting('highlighted_text'); ?></span>
        <?php endif ?>
		</span>
        <?php if (!empty($settings['after_text'])) : ?>
            <span class="elementor-headline-plain-text elementor-headline-text-wrapper"><?php $this->print_unescaped_setting('after_text'); ?></span>
        <?php endif; ?>
        </<?php Utils::print_validated_html_tag($tag); ?>>
        <?php

        if (!empty($settings['link']['url'])) {
            echo '</a>';
        }
    }

}

$widgets_manager->register(new Printec_Elementor_Widget_Heading());