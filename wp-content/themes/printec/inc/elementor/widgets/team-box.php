<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;

class Printec_Elementor_Team_Box extends Printec_Base_Widgets_Carousel
{

    /**
     * Get widget name.
     *
     * Retrieve teambox widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'printec-team-box';
    }

    /**
     * Get widget title.
     *
     * Retrieve teambox widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return esc_html__('Team Box', 'printec');
    }

    /**
     * Get widget icon.
     *
     * Retrieve teambox widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'eicon-person';
    }

    public function get_script_depends()
    {
        return ['printec-elementor-team-box', 'slick'];
    }

    public function get_categories()
    {
        return array('printec-addons');
    }

    /**
     * Register teambox widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_team',
            [
                'label' => esc_html__('Team', 'printec'),
            ]
        );
        $repeater = new Repeater();


        $repeater->add_control(
            'teambox_image',
            [
                'label' => esc_html__('Choose Image', 'printec'),
                'default' => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'type' => Controls_Manager::MEDIA,
                'show_label' => false,
            ]
        );

        $repeater->add_control(
            'teambox_name',
            [
                'label' => esc_html__('Name', 'printec'),
                'default' => 'John Doe',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_job',
            [
                'label' => esc_html__('Job', 'printec'),
                'default' => 'Designer',
                'type' => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'teambox_link',
            [
                'label' => esc_html__('Link to', 'printec'),
                'placeholder' => esc_html__('https://your-link.com', 'printec'),
                'type' => Controls_Manager::URL,
            ]
        );


        $repeater->add_control(
            'facebook',
            [
                'label'       => esc_html__('Facebook', 'printec'),
                'placeholder' => esc_html__('https://www.facebook.com/opalwordpress', 'printec'),
                'default'     => 'https://www.facebook.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $repeater->add_control(
            'twitter',
            [
                'label'       => esc_html__('Twitter', 'printec'),
                'placeholder' => esc_html__('https://twitter.com/opalwordpress', 'printec'),
                'default'     => 'https://twitter.com/opalwordpress',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'instagram',
            [
                'label'       => esc_html__('Instagram', 'printec'),
                'placeholder' => esc_html__('https://www.instagram.com/user/WPOpalTheme', 'printec'),
                'default'     => 'https://www.instagram.com/user/WPOpalTheme',
                'type'        => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'pinterest',
            [
                'label'       => esc_html__('Pinterest', 'printec'),
                'placeholder' => esc_html__('https://plus.pinterest.com/u/0/+WPOpal', 'printec'),
                'default'     => 'https://plus.pinterest.com/u/0/+WPOpal',
                'type'        => Controls_Manager::TEXT,
            ]
        );
        $this->add_control(
            'teambox',
            [
                'label' => esc_html__('Items', 'printec'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name' => 'teambox_image',
                'default' => 'full',
                'separator' => 'none',
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

        $this->start_controls_section(
            'teambox_style_image',
            [
                'label' => esc_html__( 'Image', 'printec' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_space',
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
                    '{{WRAPPER}} .team-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .team-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label' => esc_html__( 'Opacity', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1,
                        'min' => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .team-image img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'teambox_style_content',
            [
                'label' => esc_html__( 'Content', 'printec' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_align',
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
                    '{{WRAPPER}} .team-content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'heading_title',
            [
                'label' => esc_html__( 'Name', 'printec' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'name_bottom_space',
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
                    '{{WRAPPER}} .team-content .teambox-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'name_color',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .teambox-name' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typography',
                'selector' => '{{WRAPPER}} .teambox-name',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
            ]
        );

        $this->add_control(
            'heading_job',
            [
                'label' => esc_html__( 'Job', 'printec' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'job_bottom_space',
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
                    '{{WRAPPER}} .team-content .teambox-job' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'job_color',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .teambox-job' => 'color: {{VALUE}};',
                ],
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'job_typography',
                'selector' => '{{WRAPPER}} .teambox-job',
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
            ]
        );


        $this->end_controls_section();

        $this->add_control_carousel();

    }

    /**
     * Render teambox widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['teambox']) && is_array($settings['teambox'])) {

            $this->add_render_attribute('wrapper', 'class', 'elementor-teambox-item-wrapper');

            $this->get_data_elementor_columns();
        }

            // Item
            $this->add_render_attribute('item', 'class', 'column-item elementor-teambox-item');
            $this->add_render_attribute('details', 'class', 'details');
            ?>

        <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
            <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                <?php foreach ($settings['teambox'] as $teambox): ?>
                    <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                        <div class="team-top">
                           <?php $this->render_image($settings, $teambox); ?>
                            <div class="team-icon-socials">
                                <ul>
                                    <?php foreach ($this->get_socials() as $key => $social): ?>
                                        <?php if (!empty($teambox[$key])) : ?>
                                            <li class="social">
                                                <a href="<?php echo esc_url($teambox[$key]) ?>">
                                                    <i class="printec-icon-<?php echo esc_attr($social); ?>"></i>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="team-content">
                            <?php if (!empty($teambox['team_content'])) { ?>
                                <div class="content"><?php echo sprintf('%s', $teambox['team_content']); ?></div>
                            <?php } ?>
                            <div class="details">
                                <?php $teambox_name_html = $teambox['teambox_name'];

                                if (!empty($teambox['teambox_link']['url'])) {
                                    $teambox_name_html = '<a href="' . esc_url($teambox['teambox_link']['url']) . '">' . esc_html($teambox_name_html) . '</a>';
                                }

                                printf('<span class="teambox-name">%s</span>', $teambox_name_html);
                                ?>
                                <?php if ($teambox['teambox_job']) { ?>
                                    <span class="teambox-job"><?php echo esc_html($teambox['teambox_job']); ?></span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    <?php }
    private function render_image($settings, $teambox)
    {
        if (!empty($teambox['teambox_image']['url'])) :
            ?>
            <div class="team-image">
                <?php
                $teambox['teambox_image_size'] = $settings['teambox_image_size'];
                $teambox['teambox_image_custom_dimension'] = $settings['teambox_image_custom_dimension'];
                echo Group_Control_Image_Size::get_attachment_image_html($teambox, 'teambox_image');
                ?>
            </div>
        <?php
        endif;
    }

    private function get_socials() {
        return array(
            'facebook' => 'facebook-f',
            'twitter'  => 'twitter',
            'instagram'  => 'instagram',
            'pinterest'   => 'pinterest-p',
        );
    }

}

$widgets_manager->register(new Printec_Elementor_Team_Box());
