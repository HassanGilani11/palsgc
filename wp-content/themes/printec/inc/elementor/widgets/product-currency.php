<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!printec_is_woocommerce_activated()) {
    return;
}

if(!class_exists('WOOCS_STARTER')){
    return;
}

/**
 * Elementor Products Currency
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Printec_Elementor_Products_Currenry extends Elementor\Widget_Base {

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
        return 'printec-product-currency';
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
        return esc_html__('Product Currency', 'printec');
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
        return 'eicon-product-price';
    }

    public function get_script_depends()
    {
        return ['printec-elementor-product-currency'];
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_currency',
            [
                'label' => esc_html__('Currency', 'printec'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'selector' => '{{WRAPPER}} .printec-woocs-select span',
            ]
        );

        $this->start_controls_tabs('style_color');

        $this->start_controls_tab('typo_normal',
            [
                'label' => esc_html__('Normal', 'printec'),
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Label Color', 'printec'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .printec-woocs-select' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('typo_hover',
            [
                'label' => esc_html__('Hover', 'printec'),
            ]
        );

        $this->add_control(
            'label_color_hover',
            [
                'label' => esc_html__('Label Color', 'printec'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .printec-woocs-select:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'trigger',
            [
                'label'        => esc_html__('Dropdown action', 'printec'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'hover' => esc_html__('Hover', 'printec'),
                    'click' => esc_html__('Click', 'printec'),
                ],
                'default'      => 'hover',
                'prefix_class' => 'printec-woocs-action-',
            ]
        );

        $this->add_control(
            'dropdown_position',
            [
                'label'        => esc_html__('Dropdown position', 'printec'),
                'type'         => Controls_Manager::SELECT,
                'options'      => [
                    'bottom_left'   => esc_html__('Bottom Left', 'printec'),
                    'bottom_center' => esc_html__('Bottom Center', 'printec'),
                    'bottom_right'  => esc_html__('Bottom Right', 'printec'),
                    'top_left'      => esc_html__('Top Left', 'printec'),
                    'top_center'    => esc_html__('Top Center', 'printec'),
                    'top_right'     => esc_html__('Top Right', 'printec'),
                ],
                'default'      => 'bottom_left',
                'prefix_class' => 'printec-woocs-dropdown-position-',
            ]
        );

        $this->end_controls_section();

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
        global $WOOCS;

        $all_currencies = apply_filters('woocs_currency_manipulation_before_show', $WOOCS->get_currencies());
        $show_money_signs = get_option('woocs_show_money_signs', 1);

        ?>
        <div class="printec-woocs-dropdown">

            <?php
            $options = [];
            foreach ($all_currencies as $key => $currency) {

                if (isset($currency['hide_on_front']) AND $currency['hide_on_front']) {
                    continue;
                }

                $option_txt = apply_filters('woocs_currname_in_option', $currency['name']);

                if ($show_money_signs) {
                    if (!empty($option_txt)) {
                        $option_txt = $currency['symbol'] . ' '. $option_txt;
                    } else {
                        $option_txt = $currency['symbol'];
                    }
                }

                if (isset($txt_type)) {
                    if ($txt_type == 'desc') {
                        if (!empty($currency['description'])) {
                            $option_txt = $currency['description'];
                        }
                    }
                }

                $options[$currency['name']] = $option_txt;
            }
            ?>

            <div class="printec-woocs-select">
                <span><?php echo esc_html($options[$WOOCS->current_currency]); ?></span><i class="printec-icon-angle-down"></i>
            </div>
            <ul class="printec-woocs-dropdown-menu">
                <?php foreach ($options as $key => $value) : ?>
                    <?php if ($key === $WOOCS->current_currency AND !$WOOCS->shop_is_cached) continue; ?>
                    <li data-currency="<?php echo esc_attr($key); ?>"><?php  echo esc_html($value); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}

$widgets_manager->register(new Printec_Elementor_Products_Currenry());
