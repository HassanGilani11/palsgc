<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!printec_is_woocommerce_activated() || !printec_is_dokan_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;


class Wpcstore_Elementor_Dokan_Stores extends Printec_Base_Widgets_Carousel {
    public function get_name() {
        return 'printec-dokan-stores';
    }

    public function get_script_depends() {
        return ['printec-elementor-dokan-store', 'slick'];
    }

    public function get_title() {
        return esc_html__('Printec Dokan Stores', 'printec');
    }

    public function get_categories() {
        return ['printec-addons'];
    }

    protected function register_controls() {


        $this->start_controls_section(
            'stores_config',
            [
                'label' => esc_html__('Settings', 'printec'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => esc_html__('Stores Per Page', 'printec'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'          => esc_html__('Columns', 'printec'),
                'type'           => \Elementor\Controls_Manager::SELECT,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options'        => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'printec'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => esc_html__('ASC', 'printec'),
                    'desc' => esc_html__('DESC', 'printec'),
                ],
            ]
        );

        $this->add_control(
            'featured',
            [
                'label' => __('Stores featured', 'printec'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'with_products_only',
            [
                'label' => __('Stores with products only', 'printec'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'store_id',
            [
                'label'       => __('Include', 'printec'),
                'type'        => Controls_Manager::TEXT,
                'description' => esc_html__('Include vendor by id separated by "," for example: (1,2,3)', 'printec')
            ]
        );

        $this->add_control(
            'style',
            [
                'label'        => esc_html__('Style', 'printec'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    'style-1' => esc_html__('Style 1', 'printec'),
                    'style-2' => esc_html__('Style 2', 'printec'),
                    'style-3' => esc_html__('Style 3', 'printec'),
                ],
                'default'      => 'style-1',
                'prefix_class' => 'elementor-store-'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'store-wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .store-wrapper',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'store-wrapper_radius',
            [
                'label'      => esc_html__('Border Radius', 'printec'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .store-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->add_control_carousel();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $seller_args = array(
            'number' => $settings['limit'],
            'order'  => 'DESC',
        );

        if ('yes' === $settings['featured']) {
            $seller_args['featured'] = 'yes';
        }

        if (!empty($settings['order'])) {
            $seller_args['order'] = $settings['order'];
        }

        if (!empty($settings['orderby'])) {
            $seller_args['orderby'] = $settings['orderby'];
        }

        if (!empty($settings['with_products_only']) && 'yes' === $settings['with_products_only']) {
            $seller_args['has_published_posts'] = ['product'];
        }

        if (!empty($settings['store_id'])) {
            $seller_args['include'] = $settings['store_id'];
        }

        $sellers = dokan_get_sellers($seller_args);

        $this->add_render_attribute('wrapper', 'class', 'elementor-store-wrapper');

        $this->get_data_elementor_columns();

        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok
        ?>>
            <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok
            ?>>
                <?php if ($sellers['users']) { ?>

                    <?php
                    foreach ($sellers['users'] as $key => $seller) {
                        $vendor       = dokan()->vendor->get($seller->ID);
                        $store_name   = $vendor->get_shop_name();
                        $store_url    = $vendor->get_shop_url();
                        $avatar_id    = $vendor->get_avatar_id();
                        $store_rating = $vendor->get_rating();
                        if (!$avatar_id && !empty($vendor->data->user_email)) {
                            $avata_url = get_avatar_url($vendor->data->user_email, 300);
                        } else {
                            $avata_url = wp_get_attachment_url($avatar_id);
                        }
                        $user_id        = (int)$vendor->data->ID;
                        $products_count = dokan_count_posts('product', $user_id);
                        $banner_id      = $vendor->get_banner_id();
                        $banner_url     = $banner_id ? wp_get_attachment_image_url($banner_id, 'medium') : '';
                        ?>

                        <div class="dokan-single-seller column-item">
                            <?php if ($settings['style'] == 'style-3'): ?>
                                <div class="product-wrapper">
                                    <?php
                                    $args = [
                                        'post_type'      => 'product',
                                        'posts_per_page' => 3,
                                        'orderby'        => 'rand',
                                        'author'         => $seller->ID,
                                    ];

                                    $products = new WP_Query($args);

                                    if ($products->have_posts()) {

                                        while ($products->have_posts()) {
                                            $products->the_post();
                                            global $product;
                                            ?>
                                            <div class="product-item">
                                                <a href="<?php echo esc_url($product->get_permalink()); ?>" title="<?php echo esc_attr($product->get_name()); ?>">
                                                    <?php printf('%s', $product->get_image()); ?>
                                                    <span class="screen-reader-text"><?php echo wp_kses_post($product->get_name()); ?></span>
                                                </a>
                                            </div>
                                            <?php
                                        }

                                    } else {
                                        esc_html_e('No product has been found!', 'printec');
                                    }

                                    wp_reset_postdata();
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="store-wrapper">
                                <span class="count"><?php echo str_pad($key + 1, 2, "0", STR_PAD_LEFT) . '.'; ?></span>
                                <?php if ($banner_url) { ?>
                                    <div class="profile-info-img">
                                        <img src="<?php echo esc_url($banner_url); ?>"
                                             alt="<?php echo esc_attr($vendor->get_shop_name()); ?>"
                                             title="<?php echo esc_attr($vendor->get_shop_name()); ?>">
                                    </div>
                                <?php } else { ?>
                                    <div class="profile-info-img dummy-image"></div>
                                <?php } ?>
                                <div class="seller-avatar">
                                    <a href="<?php echo esc_url($store_url); ?>">
                                        <img src="<?php echo esc_url($avata_url) ?>" alt="<?php echo esc_attr($vendor->get_shop_name()) ?>">
                                    </a>
                                </div>
                                <div class="store-data">
                                    <h3>
                                        <a href="<?php echo esc_attr($store_url); ?>"><?php echo esc_html($store_name); ?></a>
                                    </h3>
                                    <div class="product-count"><?php echo sprintf(_n('%d Product', '%d Products', $products_count->publish, 'printec'), $products_count->publish); ?></div>
                                    <?php if (!empty($store_rating['count'])): ?>
                                        <div class="dokan-seller-rating" title="<?php echo sprintf(esc_attr__('Rated %s out of 5', 'printec'), esc_attr($store_rating['rating'])) ?>">
                                            <?php echo wp_kses_post(dokan_generate_ratings($store_rating['rating'], 5)); ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                    <?php }

                } else { ?>
                    <div class="dokan-error"><?php esc_html_e('No vendor found!', 'printec'); ?></div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Wpcstore_Elementor_Dokan_Stores());
