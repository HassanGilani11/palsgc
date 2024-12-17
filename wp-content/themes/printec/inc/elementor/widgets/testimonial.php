<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Printec\Elementor\Printec_Base_Widgets;

class Printec_Elementor_Testimonials extends Printec_Base_Widgets_Carousel {
    /**
     * Get widget name.
     *
     * Retrieve testimonial widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'printec-testimonials';
    }

    /**
     * Get widget title.
     *
     * Retrieve testimonial widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Printec Testimonials', 'printec');
    }

    /**
     * Get widget icon.
     *
     * Retrieve testimonial widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-testimonial';
    }

    public function get_script_depends() {
        return ['printec-elementor-testimonial', 'slick'];
    }

    public function get_categories() {
        return array('printec-addons');
    }

    /**
     * Register testimonial widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_testimonial',
            [
                'label' => esc_html__('Testimonials', 'printec'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'testimonial_rating',
            [
                'label'   => esc_html__('Rating', 'printec'),
                'default' => 5,
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    0 => esc_html__('Hidden', 'printec'),
                    1 => esc_html__('Very poor', 'printec'),
                    2 => esc_html__('Not that bad', 'printec'),
                    3 => esc_html__('Average', 'printec'),
                    4 => esc_html__('Good', 'printec'),
                    5 => esc_html__('Perfect', 'printec'),
                ]
            ]
        );

        $repeater->add_control(
            'testimonial_content',
            [
                'label'       => esc_html__('Content', 'printec'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
                'label_block' => true,
                'rows'        => '10',
            ]
        );

        $repeater->add_control(
            'testimonial_name',
            [
                'label'   => esc_html__('Name', 'printec'),
                'default' => 'John Doe',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'testimonial_job',
            [
                'label'   => esc_html__('Job', 'printec'),
                'default' => 'Design',
                'type'    => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'testimonial_link',
            [
                'label'       => esc_html__('Link to', 'printec'),
                'placeholder' => esc_html__('https://your-link.com', 'printec'),
                'type'        => Controls_Manager::URL,
            ]
        );
        $repeater->add_control(
            'testimonial_image',
            [
                'label'      => esc_html__('Choose Image', 'printec'),
                'type'       => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'testimonials',
            [
                'label'       => esc_html__('Items', 'printec'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ testimonial_name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'testimonial_image',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_control(
            'testimonial_layout',
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

        $this->add_responsive_control(
            'column',
            [
                'label'        => esc_html__('Columns', 'printec'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'default'      => 1,
                'options'      => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'prefix_class' => 'testimonial-column-',
            ]
        );

        $this->add_responsive_control(
            'testimonial_alignment',
            [
                'label'       => esc_html__('Alignment', 'printec'),
                'type'        => Controls_Manager::CHOOSE,
                'options'     => [
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
                'label_block' => false,
                'selectors'   => [
                    '{{WRAPPER}} .elementor-testimonial-item' => 'text-align: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'view',
            [
                'label'   => esc_html__('View', 'printec'),
                'type'    => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );
        $this->end_controls_section();


        // WRAPPER STYLE
        $this->start_controls_section(
            'section_style_testimonial_wrapper',
            [
                'label' => esc_html__('Wrapper', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'testimonial_width',
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
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'padding_testimonial_wrapper',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_testimonial_wrapper',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_testimonial_wrapper',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .elementor-testimonial-item-wrapper',
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
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-testimonial-item-wrapper',
            ]
        );

        $this->end_controls_section();

        // Content style
        $this->start_controls_section(
            'section_style_testimonial_style',
            [
                'label' => esc_html__('Content', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_content_color',
            [
                'label'     => esc_html__('Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_content_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper:hover .content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .content',
            ]
        );

        $this->add_responsive_control(
            'content_spacing',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();


        // Name.
        $this->start_controls_section(
            'section_style_testimonial_name',
            [
                'label' => esc_html__('Name', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'name_text_color',
            [
                'label'     => esc_html__('Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .name, {{WRAPPER}} .name a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'name_text_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .name:hover, {{WRAPPER}} .name a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'name_typography',
                'selector' => '{{WRAPPER}} .name',
            ]
        );

        $this->add_responsive_control(
            'name_padding',
            [
                'size_units' => ['px', 'em', '%'],
                'label'      => esc_html__('Spacing', 'printec'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Job.
        $this->start_controls_section(
            'section_style_testimonial_job',
            [
                'label' => esc_html__('Job', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'job_text_color',
            [
                'label'     => esc_html__('Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .job' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'job_text_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-testimonial-item-wrapper:hover .job' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'job_typography',
                'selector' => '{{WRAPPER}} .job',
            ]
        );

        $this->add_responsive_control(
            'job_spacing',
            [
                'size_units' => ['px', 'em', '%'],
                'label'      => esc_html__('Spacing', 'printec'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .job' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel();

    }

    /**
     * Render testimonial widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['testimonials']) && is_array($settings['testimonials'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-testimonial-item-wrapper');
            // Row
            $this->add_render_attribute('row', 'class', 'row');
            $this->add_render_attribute('row', 'class', 'layout-' . esc_attr($settings['testimonial_layout']));
            $this->add_render_attribute('row', 'class', 'alignment-' . esc_attr($settings['testimonial_alignment']));
            // Carousel
            $class_nav = $settings['testimonial_layout'] === '3' || $settings['testimonial_layout'] === '4' ? 'layout-nav' : '';
            if ($settings['enable_carousel'] === 'yes') {
                $this->add_render_attribute('row', 'class', 'printec-carousel ' . $class_nav);
                $carousel_settings = $this->get_carousel_settings();
                $this->add_render_attribute('row', 'data-settings', wp_json_encode($carousel_settings));

            } else {

                if (!empty($settings['column_widescreen'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-widescreen', $settings['column_widescreen']);
                }

                if (!empty($settings['column'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns', $settings['column']);
                } else {
                    $this->add_render_attribute('row', 'data-elementor-columns', 5);
                }

                if (!empty($settings['column_laptop'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-laptop', $settings['column_laptop']);
                }

                if (!empty($settings['column_tablet_extra'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-tablet-extra', $settings['column_tablet_extra']);
                }

                if (!empty($settings['column_tablet'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-tablet', $settings['column_tablet']);
                } else {
                    $this->add_render_attribute('row', 'data-elementor-columns-tablet', 2);
                }

                if (!empty($settings['column_mobile_extra'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-mobile-extra', $settings['column_mobile_extra']);
                }

                if (!empty($settings['column_mobile'])) {
                    $this->add_render_attribute('row', 'data-elementor-columns-mobile', $settings['column_mobile']);
                } else {
                    $this->add_render_attribute('row', 'data-elementor-columns-mobile', 1);
                }
            }
            // Item
            $this->add_render_attribute('item', 'class', 'column-item elementor-testimonial-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>

                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['testimonials'] as $testimonial): ?>
                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <?php
                            if ($settings['testimonial_layout'] === '4') {
                                $this->render_image($settings, $testimonial);
                            }
                            ?>
                            <div class="inner">
                                <?php if ($testimonial['testimonial_rating'] && $testimonial['testimonial_rating'] > 0) {
                                    echo '<div class="elementor-testimonial-rating">';
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $testimonial['testimonial_rating']) {
                                            echo '<i class="printec-icon-star active" aria-hidden="true"></i>';
                                        }
                                    }
                                    echo '</div>';
                                }
                                ?>
                                <div class="testimonial-content">
                                    <?php if (!empty($testimonial['testimonial_content'])) { ?>
                                        <div class="content"><?php echo sprintf('%s', $testimonial['testimonial_content']); ?></div>
                                    <?php } ?>
                                    <div class="details">

                                        <?php if ($settings['testimonial_layout'] === '1' || $settings['testimonial_layout'] === '2') {
                                            $this->render_image($settings, $testimonial);
                                        } ?>
                                        <div class="details-info">
                                            <?php $testimonial_name_html = $testimonial['testimonial_name'];

                                            if (!empty($testimonial['testimonial_link']['url'])) {
                                                $testimonial_name_html = '<a href="' . esc_url($testimonial['testimonial_link']['url']) . '">' . esc_html($testimonial_name_html) . '</a>';
                                            }

                                            printf('<span class="name">%s</span>', $testimonial_name_html);
                                            ?>
                                            <?php if ($testimonial['testimonial_job']) { ?>
                                                <span class="job"><?php echo esc_html($testimonial['testimonial_job']); ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($settings['enable_carousel'] == 'yes' && $class_nav == 'layout-nav') { ?>
                    <div class="testimonial-image-wrap">
                        <div class="testimonial-image-style">
                            <?php foreach ($settings['testimonials'] as $testimonial): ?>
                                <?php $this->render_image($settings, $testimonial); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
        }
    }

    private function render_image($settings, $testimonial) {
        if (!empty($testimonial['testimonial_image']['url'])) :
            ?>
            <div class="elementor-testimonial-image">
                <?php
                $testimonial['testimonial_image_size']             = $settings['testimonial_image_size'];
                $testimonial['testimonial_image_custom_dimension'] = $settings['testimonial_image_custom_dimension'];
                echo Group_Control_Image_Size::get_attachment_image_html($testimonial, 'testimonial_image');
                ?>
            </div>
        <?php
        endif;
    }
}

$widgets_manager->register(new Printec_Elementor_Testimonials());

