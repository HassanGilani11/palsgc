<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!printec_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Printec_Elementor_Widget_Products extends Printec_Base_Widgets_Carousel
{


    public function get_categories()
    {
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
    public function get_name()
    {
        return 'printec-products';
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
    public function get_title()
    {
        return esc_html__('Products', 'printec');
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
    public function get_icon()
    {
        return 'eicon-tabs';
    }


    public function get_script_depends()
    {
        return [
            'printec-elementor-products',
            'slick',
            'tooltipster'
        ];
    }

    public function on_export($element)
    {
        unset($element['settings']['categories']);
        unset($element['settings']['tag']);

        return $element;
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls()
    {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'printec'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'limit',
            [
                'label' => esc_html__('Posts Per Page', 'printec'),
                'type' => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label' => esc_html__('columns', 'printec'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options' => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            ]
        );


        $this->add_control(
            'advanced',
            [
                'label' => esc_html__('Advanced', 'printec'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => esc_html__('Order By', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date' => esc_html__('Date', 'printec'),
                    'id' => esc_html__('Post ID', 'printec'),
                    'menu_order' => esc_html__('Menu Order', 'printec'),
                    'popularity' => esc_html__('Number of purchases', 'printec'),
                    'rating' => esc_html__('Average Product Rating', 'printec'),
                    'title' => esc_html__('Product Title', 'printec'),
                    'rand' => esc_html__('Random', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Order', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc' => esc_html__('ASC', 'printec'),
                    'desc' => esc_html__('DESC', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label' => esc_html__('Categories', 'printec'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_product_categories(),
                'label_block' => true,
                'multiple' => true,
            ]
        );

        $this->add_control(
            'cat_operator',
            [
                'label' => esc_html__('Category Operator', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'IN',
                'options' => [
                    'AND' => esc_html__('AND', 'printec'),
                    'IN' => esc_html__('IN', 'printec'),
                    'NOT IN' => esc_html__('NOT IN', 'printec'),
                ],
                'condition' => [
                    'categories!' => ''
                ],
            ]
        );

        $this->add_control(
            'tag',
            [
                'label' => esc_html__('Tags', 'printec'),
                'type' => Controls_Manager::SELECT2,
                'label_block' => true,
                'options' => $this->get_product_tags(),
                'multiple' => true,
            ]
        );

        $this->add_control(
            'tag_operator',
            [
                'label' => esc_html__('Tag Operator', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'IN',
                'options' => [
                    'AND' => esc_html__('AND', 'printec'),
                    'IN' => esc_html__('IN', 'printec'),
                    'NOT IN' => esc_html__('NOT IN', 'printec'),
                ],
                'condition' => [
                    'tag!' => ''
                ],
            ]
        );

        $this->add_control(
            'product_type',
            [
                'label' => esc_html__('Product Type', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'newest',
                'options' => [
                    'newest' => esc_html__('Newest Products', 'printec'),
                    'on_sale' => esc_html__('On Sale Products', 'printec'),
                    'best_selling' => esc_html__('Best Selling', 'printec'),
                    'top_rated' => esc_html__('Top Rated', 'printec'),
                    'featured' => esc_html__('Featured Product', 'printec'),
                    'ids' => esc_html__('Product Name', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'product_ids',
            [
                'label' => esc_html__('Products name', 'printec'),
                'type' => 'products',
                'label_block' => true,
                'multiple' => true,
                'condition' => [
                    'product_type' => 'ids'
                ]
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label' => esc_html__('Paginate', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__('None', 'printec'),
                    'pagination' => esc_html__('Pagination', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'product_layout',
            [
                'label' => esc_html__('Product Layout', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'printec'),
                    'list' => esc_html__('List', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__('Block Style', 'printec'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__('Style 1', 'printec'),
                ],
                'condition' => [
                    'product_layout' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'list_layout',
            [
                'label' => esc_html__('List Layout', 'printec'),
                'type' => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'printec'),
                ],
                'condition' => [
                    'product_layout' => 'list'
                ]
            ]
        );

        $this->add_responsive_control(
            'product_gutter',
            [
                'label' => esc_html__('Gutter', 'printec'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} ul.products li.product' => 'padding-left: calc({{SIZE}}{{UNIT}} / 2); padding-right: calc({{SIZE}}{{UNIT}} / 2); margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} ul.products' => 'margin-left: calc({{SIZE}}{{UNIT}} / -2); margin-right: calc({{SIZE}}{{UNIT}} / -2);',
                ],
            ]
        );

        $this->add_control(
            'mobile_switcher',
            [
                'label' => esc_html__('Mobile Switcher', 'printec'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'prefix_class' => 'mobile-switcher-style-product-',
            ]
        );

        $this->end_controls_section();

        // Carousel Option
        $this->add_control_carousel();
    }


    protected function get_product_categories()
    {
        $categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            )
        );
        $results = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }

        return $results;
    }

    protected function get_product_tags()
    {
        $tags = get_terms(array(
                'taxonomy' => 'product_tag',
                'hide_empty' => false,
            )
        );
        $results = array();
        if (!is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $results[$tag->slug] = $tag->name;
            }
        }

        return $results;
    }

    protected function get_product_type($atts, $product_type)
    {
        switch ($product_type) {
            case 'featured':
                $atts['visibility'] = "featured";
                break;

            case 'on_sale':
                $atts['on_sale'] = true;
                break;

            case 'best_selling':
                $atts['best_selling'] = true;
                break;

            case 'top_rated':
                $atts['top_rated'] = true;
                break;

            default:
                break;
        }

        return $atts;
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->woocommerce_default($settings);
    }

    private function woocommerce_default($settings)
    {
        $type = 'products';
        $class = '';
        $atts = [
            'limit' => $settings['limit'],
            'columns' => $settings['enable_carousel'] === 'yes' ? 1 : $settings['column'],
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
            'product_layout' => $settings['product_layout'],
        ];

        if ($settings['product_layout'] == 'list') {
            $atts['style'] = 'list-' . $settings['list_layout'];
            $class .= ' woocommerce-product-list';
            $class .= ' woocommerce-product-list-' . $settings['list_layout'];
        } else {
            if (isset($settings['style']) && $settings['style'] !== '') {
                $atts['style'] = $settings['style'];
            }
        }

        $atts = $this->get_product_type($atts, $settings['product_type']);
        if (isset($atts['on_sale']) && wc_string_to_bool($atts['on_sale'])) {
            $type = 'sale_products';
        } elseif (isset($atts['best_selling']) && wc_string_to_bool($atts['best_selling'])) {
            $type = 'best_selling_products';
        } elseif (isset($atts['top_rated']) && wc_string_to_bool($atts['top_rated'])) {
            $type = 'top_rated_products';
        }

        if (isset($settings['product_ids']) && !empty($settings['product_ids']) && $settings['product_type'] == 'ids') {
            $atts['ids'] = implode(',', $settings['product_ids']);
        }

        if (!empty($settings['categories'])) {
            $atts['category'] = implode(',', $settings['categories']);
            $atts['cat_operator'] = $settings['cat_operator'];
        }

        if (!empty($settings['tag'])) {
            $atts['tag'] = implode(',', $settings['tag']);
            $atts['tag_operator'] = $settings['tag_operator'];
        }

        // Carousel
        if ($settings['enable_carousel'] === 'yes') {
            $atts['carousel_settings'] = json_encode(wp_slash($this->get_carousel_settings()));
            $atts['product_layout'] = 'carousel';
        } else {

            if (!empty($settings['column_widescreen'])) {
                $class .= ' columns-widescreen-' . $settings['column_widescreen'];
            }

            if (!empty($settings['column_laptop'])) {
                $class .= ' columns-laptop-' . $settings['column_laptop'];
            }

            if (!empty($settings['column_tablet_extra'])) {
                $class .= ' columns-tablet-extra-' . $settings['column_tablet_extra'];
            }

            if (!empty($settings['column_tablet'])) {
                $class .= ' columns-tablet-' . $settings['column_tablet'];
            } else {
                $class .= ' columns-tablet-2';
            }

            if (!empty($settings['column_mobile_extra'])) {
                $class .= ' columns-mobile-extra-' . $settings['column_mobile_extra'];
            }

            if (!empty($settings['column_mobile'])) {
                $class .= ' columns-mobile-' . $settings['column_mobile'];
            } else {
                $class .= ' columns-mobile-1';
            }
        }

        if ($settings['paginate'] === 'pagination') {
            $atts['paginate'] = 'true';
        }
        $atts['class'] = $class;

        echo (new Printec_WC_Shortcode_Products($atts, $type))->get_content(); // WPCS: XSS ok
    }
}

$widgets_manager->register(new Printec_Elementor_Widget_Products());
