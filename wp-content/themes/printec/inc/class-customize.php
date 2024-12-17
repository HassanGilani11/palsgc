<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Printec_Customize')) {

    class Printec_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            $this->init_printec_blog($wp_customize);

            $this->init_printec_social($wp_customize);

            if (printec_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('printec_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_printec_blog($wp_customize) {

            $wp_customize->add_section('printec_blog_archive', array(
                'title' => esc_html__('Blog', 'printec'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('printec_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_blog_style', array(
                'section' => 'printec_blog_archive',
                'label'   => esc_html__('Blog style', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'printec'),
                    //====start_premium
                    'style-1'  => esc_html__('Blog Grid', 'printec'),
                    'list'  => esc_html__('Blog List', 'printec'),
                    'modern'  => esc_html__('Blog modern', 'printec'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('printec_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_blog_columns', array(
                'section' => 'printec_blog_archive',
                'label'   => esc_html__('Colunms', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'printec'),
                    2 => esc_html__('2', 'printec'),
                    3 => esc_html__('3', 'printec'),
                    4 => esc_html__('4', 'printec'),
                ),
            ));

            $wp_customize->add_setting('printec_options_blog_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_blog_archive_sidebar', array(
                'section' => 'printec_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'printec'),
                    'right' => esc_html__('Right', 'printec'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_printec_social($wp_customize) {

            $wp_customize->add_section('printec_social', array(
                'title' => esc_html__('Socials', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Show Social Share', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Facebook', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Twitter', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Linkedin', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Google+', 'printec'),
            ));

            $wp_customize->add_setting('printec_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Pinterest', 'printec'),
            ));
            $wp_customize->add_setting('printec_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'printec_social',
                'label'   => esc_html__('Share on Email', 'printec'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'printec'),
            ));

            $wp_customize->add_section('printec_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'printec'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('printec_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_woocommerce_archive_layout', array(
                'section' => 'printec_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'printec'),
                    //====start_premium
                    'canvas'   => esc_html__('Canvas Filter', 'printec'),
                    'dropdown' => esc_html__('Dropdown Filter', 'printec'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('printec_options_woocommerce_archive_width', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_woocommerce_archive_width', array(
                'section' => 'printec_woocommerce_archive',
                'label'   => esc_html__('Layout Width', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'default' => esc_html__('Default', 'printec'),
                    'wide'    => esc_html__('Wide', 'printec'),
                ),
            ));

            $wp_customize->add_setting('printec_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_woocommerce_archive_sidebar', array(
                'section' => 'printec_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'printec'),
                    'right' => esc_html__('Right', 'printec'),

                ),
            ));

            $wp_customize->add_setting('printec_options_woocommerce_shop_pagination', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_shop_pagination', array(
                'section' => 'printec_woocommerce_archive',
                'label'   => esc_html__('Products pagination', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Pagination', 'printec'),
                    'more-btn' => esc_html__('Load More', 'printec'),
                    'infinit'  => esc_html__('Infinit Scroll', 'printec'),

                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('printec_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'printec'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('printec_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('printec_options_single_product_gallery_layout', array(
                'section' => 'printec_woocommerce_single',
                'label'   => esc_html__('Style', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal' => esc_html__('Horizontal', 'printec'),
                    //====start_premium
                    'vertical'   => esc_html__('Vertical', 'printec'),
                    'gallery'    => esc_html__('Gallery', 'printec'),
                    'sticky'     => esc_html__('Sticky', 'printec'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('printec_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'printec_sanitize_editor',
            ));

            $wp_customize->add_control(new Printec_Customize_Control_Editor($wp_customize, 'printec_options_single_product_content_meta', array(
                'section' => 'printec_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'printec'),
            )));


            // =========================================
            // Product
            // =========================================


            $wp_customize->add_section('printec_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'printec'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));
            $attribute_array      = [
                '' => esc_html__('None', 'printec')
            ];
            $attribute_taxonomies = wc_get_attribute_taxonomies();

            if (!empty($attribute_taxonomies)) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_array[$tax->attribute_name] = $tax->attribute_label;
                    }
                }
            }

            $wp_customize->add_setting('printec_options_wocommerce_attribute', array(
                'type'              => 'option',
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('printec_options_wocommerce_attribute', array(
                'section' => 'printec_woocommerce_product',
                'label'   => esc_html__('Attributes Swatches Image', 'printec'),
                'type'    => 'select',
                'choices' => $attribute_array,
            ));

            $wp_customize->add_setting('printec_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('printec_options_wocommerce_block_style', array(
                'section' => 'printec_woocommerce_product',
                'label'   => esc_html__('Style', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    '' => esc_html__('Style 1', 'printec'),
                ),
            ));

            $wp_customize->add_setting('printec_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('printec_options_woocommerce_product_hover', array(
                'section' => 'printec_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'printec'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'printec'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'printec'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'printec'),
                    'right-to-left' => esc_html__('Right to Left', 'printec'),
                    'left-to-right' => esc_html__('Left to Right', 'printec'),
                    'swap'          => esc_html__('Swap', 'printec'),
                    'fade'          => esc_html__('Fade', 'printec'),
                    'zoom-in'       => esc_html__('Zoom In', 'printec'),
                    'zoom-out'      => esc_html__('Zoom Out', 'printec'),
                ),
            ));

            $wp_customize->add_setting('printec_options_wocommerce_row_laptop', array(
                'type'              => 'option',
                'default'           => 3,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_wocommerce_row_laptop', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row Laptop', 'printec'),
                'type'    => 'number',
            ));

            $wp_customize->add_setting('printec_options_wocommerce_row_tablet', array(
                'type'              => 'option',
                'default'           => 2,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_wocommerce_row_tablet', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row tablet', 'printec'),
                'type'    => 'number',
            ));

            $wp_customize->add_setting('printec_options_wocommerce_row_mobile', array(
                'type'              => 'option',
                'default'           => 1,
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('printec_options_wocommerce_row_mobile', array(
                'section' => 'woocommerce_product_catalog',
                'label'   => esc_html__('Products per row mobile', 'printec'),
                'type'    => 'number',
            ));
        }
    }
}
return new Printec_Customize();
