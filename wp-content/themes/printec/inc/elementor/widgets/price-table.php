<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Icons_Manager;
class printec_Elementor_Price_Table extends Elementor\Widget_Base
{

    public function get_name()
    {
        return 'printec-price-table';
    }

    public function get_title()
    {
        return esc_html__('Printec Price Table', 'printec');
    }

    public function get_categories()
    {
        return array('printec-addons');
    }
    public function get_script_depends() {
        return ['printec-elementor-price-table', 'slick'];
    }

    public function get_icon()
    {
        return 'eicon-price-table';
    }

    protected function register_controls()
    {

        $this->start_controls_section(
            'section_header',
            [
                'label' => esc_html__('Wrap', 'printec'),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label' => esc_html__('Title', 'printec'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Pricing Table', 'printec'),
            ]
        );

        $this->add_control(
            'subheading',
            [
                'label' => esc_html__('Sub Title', 'printec'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => esc_html__('Sub Pricing Table', 'printec'),
            ]
        );

        $this->add_control('heading_pricing', [
            'label' => esc_html__('Pricing', 'printec'),
            'type' => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control(
            'currency',
            [
                'label' => esc_html__('Price', 'printec'),
                'type' => Controls_Manager::NUMBER,
                'default' => '39.99',
            ]
        );

        $this->add_control(
            'symbol',
            [
                'label' => esc_html__('symbol', 'printec'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('$', 'printec'),
                'placeholder' => esc_html__('symbol ...', 'printec'),
            ]
        );

        $this->add_control(
            'period',
            [
                'label' => esc_html__('Period', 'printec'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('per month', 'printec'),
                'placeholder' => esc_html__('Period ...', 'printec'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_features',
            [
                'label' => esc_html__('Features', 'printec'),
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'item_text',
            [
                'label' => esc_html__('Text', 'printec'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('List Item', 'printec'),
            ]
        );

        $this->add_control(
            'features_list',
            [
                'type' => Controls_Manager::REPEATER,
                'label' => esc_html__('Items List', 'printec'),
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_text' => esc_html__('List Item #1', 'printec'),
                    ],
                    [
                        'item_text' => esc_html__('List Item #2', 'printec'),
                    ],
                    [
                        'item_text' => esc_html__('List Item #3', 'printec'),
                    ],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'printec'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Click Here', 'printec'),
            ]
        );

        $this->add_control(
            'link',
            [
                'label' => esc_html__('Link', 'printec'),
                'type' => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'printec'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();


        // WRAPPER STYLE
        $this->start_controls_section(
            'section_style_price_wrapper',
            [
                'label' => esc_html__('Wrapper', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,

            ]
        );

        $this->add_responsive_control(
            'padding_price_wrapper',
            [
                'label'      => esc_html__('Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .price_table_inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'margin_price_wrapper',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .price_table_inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'color_price_wrapper',
            [
                'label'     => esc_html__('Background Color', 'printec'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .price_table_inner',
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
                    '{{WRAPPER}} .price_table_inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'wrapper_box_shadow',
                'selector' => '{{WRAPPER}} .price_table_inner',
            ]
        );

        $this->end_controls_section();

        // header style
        $this->start_controls_section(
            'section_header_style',
            [
                'label' => esc_html__('Header', 'printec'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'header_style_title',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Title', 'printec' ),

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .price_table_header .title',
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .price_table_header .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_style_subtitle',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'SubTitle', 'printec' ),
                'separator' => 'before',

            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'subtitle_typography',
                'selector' => '{{WRAPPER}} .price_table_header .subtitle',
            ]
        );

        $this->add_responsive_control(
            'subtitle_spacing',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .price_table_header .subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_price',
            [
                'label' => esc_html__('Price', 'printec'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .price_table_header .elementor-price-table__currency',
            ]
        );

        $this->add_responsive_control(
            'header_price_spacing',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .price_table_header .elementor-price-table__currency' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_symbol',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Symbol', 'printec'),
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'symbol_typography',
                'selector' => '{{WRAPPER}} .price_table_header .elementor-price-table__integer-part',
            ]
        );

        $this->add_responsive_control(
            'header_symbol_spacing',
            [
                'label' => esc_html__( 'Spacing', 'printec' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .price_table_header .elementor-price-table__integer-part' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'header_period',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__('Period', 'printec'),
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'period_typography',
                'selector' => '{{WRAPPER}} .price_table_header .elementor-price-table__period',
            ]
        );

        $this->add_responsive_control(
            'header_padding',
            [
                'label'      => esc_html__('Header Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .price_table_header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'heading_content_colors',
            [
                'type' => Controls_Manager::HEADING,
                'label' => esc_html__( 'Colors', 'printec' ),
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs( 'price_color_tabs' );

        $this->start_controls_tab( 'price_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'printec' ),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Title Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_color',
            [
                'label' => esc_html__( 'Sub Title Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__( 'Price Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-table__currency' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-price-table__integer-part' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'period_color',
            [
                'label' => esc_html__( 'Period Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-table__period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pricing_bgcolor',
            [
                'label' => esc_html__('Background Color', 'printec'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .price_table_header' => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'price_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'printec' ),
            ]
        );


        $this->add_control(
            'title_color_hover',
            [
                'label' => esc_html__( 'Title Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner:hover .title' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'subtitle_color_hover',
            [
                'label' => esc_html__( 'Sub Title Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner:hover .subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color_hover',
            [
                'label' => esc_html__( 'Price Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner:hover .elementor-price-table__currency' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .price_table_inner:hover .elementor-price-table__integer-part' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'period_color_hover',
            [
                'label' => esc_html__( 'Period Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner:hover .elementor-price-table__period' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pricing_bgcolor_hover',
            [
                'label' => esc_html__('Background Color Hover', 'printec'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .price_table_inner:hover .price_table_header' => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();


        $this->start_controls_section(
            'content_style',
            [
                'label' => esc_html__( 'Content', 'printec' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'selector' => '{{WRAPPER}} .elementor-price-table__features-list .elementor-price-table__feature-inner',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label' => esc_html__( ' Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-table__features-list' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_text_color',
            [
                'label' => esc_html__( 'Text Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elementor-price-table__features-list .elementor-price-table__feature-inner' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label'      => esc_html__('Margin', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elementor-price-table__features-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'button_footer_style',
            [
                'label' => esc_html__( 'Button', 'printec' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'button_typography',
                'selector' => '{{WRAPPER}} .elementor-price-table__button .button-more-link',
            ]
        );


        $this->start_controls_tabs( 'button_color_tabs' );

        $this->start_controls_tab( 'button_colors_normal',
            [
                'label' => esc_html__( 'Normal', 'printec' ),
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .button-more-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bgcolor',
            [
                'label' => esc_html__('Background Color', 'printec'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .button-more-link' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_colors_hover',
            [
                'label' => esc_html__( 'Hover', 'printec' ),
            ]
        );

        $this->add_control(
            'button_color_hover',
            [
                'label' => esc_html__( 'Color', 'printec' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .button-more-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bgcolor_hover',
            [
                'label' => esc_html__('Background Color', 'printec'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .button-more-link:hover' => 'background-color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            [
                'label'      => esc_html__('Button Padding', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .button-more-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_size',
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
                    '{{WRAPPER}} .button-more-link i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
            $this->add_render_attribute('wrapper', 'class', 'elementor-price-table-item-wrapper');
            ?>
                <?php
                $pricing_number = '';
                if (!empty($settings['price'])) {
                    $pricing_string = (string)$settings['price'];
                    $pricing_array  = explode('.', $pricing_string);
                    if (isset($pricing_array[1]) && strlen($pricing_array[1]) < 2) {
                        $decimals = 1;
                    } else {
                        $decimals = 2;
                    }

                    if (count($pricing_array) < 2) {
                        $decimals = 0;
                    }

                    if (empty($settings['currency_format'])) {
                        $dec_point     = '.';
                        $thousands_sep = ',';
                    } else {
                        $dec_point     = ',';
                        $thousands_sep = '.';
                    }
                    $pricing_number = number_format($settings['price'], $decimals, $dec_point, $thousands_sep);
                }
                ?>

            <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                <div class="price_table_inner">

                    <div class="price_table_header">
                        <?php if (!empty($settings['heading'])) : ?>
                            <div class="title"><?php echo esc_html($settings["heading"]) ?></div>
                        <?php endif; ?>


                        <div class="elementor-price-table__price">
                            <span class="elementor-price-table__integer-part"><?php echo esc_html($settings['symbol']); ?></span>
                            <span class="elementor-price-table__currency"><?php echo esc_html($settings['currency']); ?></span>
                            <span class="elementor-price-table__period"><?php echo esc_html($settings['period']); ?></span>
                        </div>
                        <?php if (!empty($settings['subheading'])) : ?>
                            <div class="subtitle"><?php echo esc_html($settings["subheading"]) ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($settings['features_list'])) :?>
                        <ul class="elementor-price-table__features-list">
                            <?php foreach ($settings['features_list'] as $index => $item) :?>
                                <li class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?>">
                                    <div class="elementor-price-table__feature-inner">
                                        <i aria-hidden="true" class="printec-icon-check-fill"></i>
                                        <span class="elementor-inline-editing">
                                            <?php if (!empty($item['item_text'])) : echo esc_html($item['item_text']); endif; ?>
                                        </span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <div class="elementor-price-table__button">
                        <a class="button-more-link elementor-button btn-theme" href="<?php echo esc_attr($settings['link']['url']) ?>">
                            <?php if (!empty($settings['button_text'])) : ?>
                                <span class="elementor-button-content-wrapper">
                                    <span><?php echo esc_html($settings['button_text']);?></span>
                                    <span class="elementor-button-icon elementor-align-icon-right">
                                        <i aria-hidden="true" class="printec-icon- printec-icon-right-arrow"></i>
                                    </span>
                                </span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php 
    }
}

$widgets_manager->register(new Printec_Elementor_Price_Table());
