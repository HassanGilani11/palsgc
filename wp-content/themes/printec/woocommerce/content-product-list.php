<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-list', $product); ?>>
    <?php
    /**
     * Functions hooked in to printec_woocommerce_before_shop_loop_item action
     *
     */
    do_action('printec_woocommerce_before_shop_loop_item');


    ?>
    <div class="product-transition">
        <div class="product-image image-main">
            <?php
            /**
             * Functions hooked in to printec_woocommerce_before_shop_loop_item_title action
             *
             * @see woocommerce_template_loop_product_thumbnail - 15 - woo
             * @see woocommerce_show_product_loop_sale_flash - 20 - woo
             *
             */
            do_action('printec_woocommerce_before_shop_loop_item_title');
            ?>
            <div class="group-action">
                <div class="shop-action">
                    <?php printec_wishlist_button(); ?>
                    <?php printec_compare_button(); ?>
                    <?php printec_quickview_button(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="product-caption">
        <?php woocommerce_template_loop_product_title(); ?>
        <?php woocommerce_template_loop_price(); ?>
        <?php
        printec_woocommerce_get_product_description();
        ?>
        <div class="caption-bottom">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div>
    <?php
    /**
     * Functions hooked in to printec_woocommerce_after_shop_loop_item action
     *
     */
    do_action('printec_woocommerce_after_shop_loop_item');
    ?>
</li>
