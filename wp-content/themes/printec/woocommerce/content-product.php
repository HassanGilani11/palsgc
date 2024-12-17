<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-style-default', $product); ?>>
    <div class="product-block">
        <div class="content-product-imagin"></div>
        <div class="product-transition">
            <?php printec_template_loop_product_thumbnail(); ?>
            <?php woocommerce_show_product_loop_sale_flash(); ?>
            <?php printec_woocommerce_get_product_label_stock(); ?>
            <?php printec_woocommerce_time_sale(true); ?>
            <div class="group-action">
                <div class="shop-action vertical">
                    <?php
                    printec_wishlist_button();
                    printec_quickview_button();
                    printec_compare_button();
                    ?>
                </div>
            </div>
            <?php woocommerce_template_loop_product_link_open(); ?>
            <?php woocommerce_template_loop_product_link_close(); ?>
        </div>
        <div class="product-caption">
            <?php woocommerce_template_loop_product_title(); ?>
            <?php woocommerce_template_loop_price(); ?>
            <div class="product-caption-bottom">
                <?php
                woocommerce_template_loop_add_to_cart();
                ?>
            </div>
        </div>
    </div>
</li>
