<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Printec_Dokan')) :
    class Printec_Dokan {
        static $instance;

        public static function getInstance() {
            if (!isset(self::$instance) && !(self::$instance instanceof Printec_Dokan)) {
                self::$instance = new Printec_Dokan();
            }

            return self::$instance;
        }

        public function __construct() {

            // Store List
            add_filter('dokan_store_listing_per_page', array($this, 'store_list_config_default'));

            add_action('wp_enqueue_scripts', array($this, 'dokan_scripts'), 10);

            add_filter('body_class', array($this, 'body_classes'));
            add_filter('dokan_store_sidebar_args', array($this, 'store_sidebar_args'));
            add_filter('dokan_store_widget_args', array($this, 'store_widget_args'));
            add_action('woocommerce_before_single_product', [$this, 'store_hook']);
        }

        public function store_list_config_default($atts) {
            $atts['per_page'] = get_theme_mod('printec_dokan_store_list_vendor_number', 9);
            $atts['per_row']  = get_theme_mod('printec_dokan_store_list_vendor_columns', 3);
            return $atts;
        }

        public function store_sidebar_args($args) {
            $args = [
                'before_widget' => '<div class="widget dokan-store-widget %s">',
                'after_widget'  => '</div></div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3><div class="widget-content">',
            ];
            return $args;
        }

        public function store_widget_args($args) {
            $args = [
                'name'          => __('Dokan Store Sidebar', 'printec'),
                'id'            => 'sidebar-store',
                'before_widget' => '<div id="%1$s" class="widget dokan-store-widget %2$s">',
                'after_widget'  => '</div>',
                'before_title'  => '<h3 class="widget-title">',
                'after_title'   => '</h3>',
            ];
            return $args;
        }

        public function store_hook() {
            global $product;
            $vendor = dokan_get_vendor_by_product($product);
            if (!$vendor->id) {
                remove_action('woocommerce_product_tabs', 'dokan_seller_product_tab');
            }

        }

        public function body_classes($classes) {
            if (dokan_is_store_page()) {
                $layout = get_theme_mod('store_layout', 'left');

                if ('left' === $layout) {
                    if (dokan_get_option('enable_theme_store_sidebar', 'dokan_appearance', 'off') === 'off') {
                        $classes[] = 'printec-sidebar-left';
                    } else {
                        if (is_active_sidebar('sidebar-store')) {
                            $classes[] = 'printec-sidebar-left';
                        } else {
                            $classes[] = 'printec-full-width-content';
                        }
                    }
                } elseif ('right' === $layout) {
                    if (dokan_get_option('enable_theme_store_sidebar', 'dokan_appearance', 'off') === 'off') {
                        $classes[] = 'printec-sidebar-right';
                    } else {
                        if (is_active_sidebar('sidebar-store')) {
                            $classes[] = 'printec-sidebar-rifht';
                        } else {
                            $classes[] = 'printec-full-width-content';
                        }
                    }
                } else {
                    $classes[] = 'printec-full-width-content';
                }

            }

            return $classes;
        }

        public function dokan_scripts() {
            global $printec_version;
            wp_enqueue_style('printec-dokan-style', get_template_directory_uri() . '/assets/css/dokan/dokan.css', array(), $printec_version);
            wp_style_add_data('printec-dokan-style', 'rtl', 'replace');
//
            wp_deregister_style('dokan-style');
            wp_deregister_style('dokan-fontawesome');

        }
    }
endif;

Printec_Dokan::getInstance();
