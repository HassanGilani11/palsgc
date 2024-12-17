<?php
/**
 * Printec WooCommerce hooks
 *
 * @package printec
 */

/**
 * Layout
 *
 * @see  printec_before_content()
 * @see  printec_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  printec_shop_messages()
 */

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_before_main_content', 'printec_before_content', 10);
add_action('woocommerce_after_main_content', 'printec_after_content', 10);


//Position label onsale
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);

//Wrapper content single
add_action('woocommerce_before_single_product_summary', 'printec_woocommerce_single_content_wrapper_start', 0);
add_action('woocommerce_single_product_summary', 'printec_woocommerce_single_content_wrapper_end', 99);

/**
 * Products
 *
 * @see printec_single_product_pagination()
 */

remove_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

add_action('woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 21);

add_action('woocommerce_single_product_summary', 'printec_single_product_summary_top', 1);
add_action('woocommerce_single_product_summary', 'printec_single_product_after_title', 7);
add_action('woocommerce_single_product_summary', 'printec_woocommerce_time_sale', 25);
add_action('woocommerce_single_product_summary', 'printec_woocommerce_deal_progress', 26);
add_action('woocommerce_single_product_summary', 'printec_single_product_extra', 35);

add_filter('woosc_button_position_single', '__return_false');
add_filter('woosw_button_position_single', '__return_false');

add_action('woocommerce_share', 'printec_social_share', 10);

add_action('woocommerce_after_add_to_cart_button', function () {
    ?>
    <div class="clear"></div>
    <?php
}, 30);

add_action('woocommerce_after_add_to_cart_button', 'printec_wishlist_button', 31);
add_action('woocommerce_after_add_to_cart_button', 'printec_compare_button', 32);

$product_single_style = printec_get_theme_option('single_product_gallery_layout', 'horizontal');

add_theme_support('wc-product-gallery-lightbox');

if ($product_single_style === 'horizontal' || $product_single_style === 'vertical') {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
}

if ($product_single_style === 'gallery') {
    add_filter('woocommerce_single_product_image_thumbnail_html', 'printec_woocommerce_single_product_image_thumbnail_html', 10, 2);
}

if ($product_single_style === 'sticky') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary', 'printec_output_product_data_accordion', 70);
    add_filter('woocommerce_single_product_image_thumbnail_html', 'printec_woocommerce_single_product_image_thumbnail_html', 10, 2);
}

add_action('printec_single_product_video_360', 'printec_single_product_video_360', 10);


/**
 * Cart fragment
 *
 * @see printec_cart_link_fragment()
 */
if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
    add_filter('woocommerce_add_to_cart_fragments', 'printec_cart_link_fragment');
} else {
    add_filter('add_to_cart_fragments', 'printec_cart_link_fragment');
}

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_start', 5);
add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_end', 15);

add_filter('woocommerce_get_script_data', function ($params, $handle) {
    if ($handle == "wc-add-to-cart") {
        $params['i18n_view_cart'] = '';
    }
    return $params;
}, 10, 2);

add_filter('woocommerce_gallery_thumbnail_size', function () {
    return array(150, 150);
});
/*
 *
 * Layout Product
 *
 * */

add_filter('woosc_button_position_archive', '__return_false');
add_filter('woosq_button_position', '__return_false');
add_filter('woosw_button_position_archive', '__return_false');

function printec_include_hooks_product_blocks() {

    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    // Remove product content link
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

}

if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
    return;
}

printec_include_hooks_product_blocks();

if( class_exists( 'WPCleverWoosc' )){
    remove_action('woocommerce_after_single_product_summary',[WPCleverWoosc::instance(),'show_quick_table'],19);
}



