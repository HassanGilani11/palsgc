<?php
/**
 * Checks if the current page is a product archive
 *
 * @return boolean
 */
function printec_is_product_archive() {
    if (is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag()) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param $product WC_Product
 */
function printec_product_get_image($product) {
    return $product->get_image();
}

/**
 * @param $product WC_Product
 */
function printec_product_get_price_html($product) {
    return $product->get_price_html();
}

/**
 * Retrieves the previous product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function printec_get_previous_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Printec_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy, true);
    return $product->get_product();
}

/**
 * Retrieves the next product.
 *
 * @param bool $in_same_term Optional. Whether post should be in a same taxonomy term. Default false.
 * @param array|string $excluded_terms Optional. Comma-separated list of excluded term IDs. Default empty.
 * @param string $taxonomy Optional. Taxonomy, if $in_same_term is true. Default 'product_cat'.
 * @return WC_Product|false Product object if successful. False if no valid product is found.
 * @since 2.4.3
 *
 */
function printec_get_next_product($in_same_term = false, $excluded_terms = '', $taxonomy = 'product_cat') {
    $product = new Printec_WooCommerce_Adjacent_Products($in_same_term, $excluded_terms, $taxonomy);
    return $product->get_product();
}


function printec_is_woocommerce_extension_activated($extension = 'WC_Bookings') {
    if ($extension == 'YITH_WCQV') {
        return class_exists($extension) && class_exists('YITH_WCQV_Frontend') ? true : false;
    }

    return class_exists($extension) ? true : false;
}

function printec_woocommerce_pagination_args($args) {
    $args['prev_text'] = '<i class="printec-icon-chevron-double-left"></i><span>' . esc_html__('Previons', 'printec') . '</span>';
    $args['next_text'] = '<span>' . esc_html__('Next', 'printec') . '</span><i class="printec-icon-chevron-double-right"></i>';
    return $args;
}

add_filter('woocommerce_pagination_args', 'printec_woocommerce_pagination_args', 10, 1);

/**
 * Check if a product is a deal
 *
 * @param int|object $product
 *
 * @return bool
 */
function printec_woocommerce_is_deal_product($product) {
    $product = is_numeric($product) ? wc_get_product($product) : $product;

    // It must be a sale product first
    if (!$product->is_on_sale()) {
        return false;
    }

    if (!$product->is_in_stock()) {
        return false;
    }

    // Only support product type "simple" and "external"
    if (!$product->is_type('simple') && !$product->is_type('external')) {
        return false;
    }

    $deal_quantity = get_post_meta($product->get_id(), '_deal_quantity', true);

    if ($deal_quantity > 0) {
        return true;
    }

    return false;
}

function woocommerce_template_loop_rating() {
    global $product;

    if (!wc_review_ratings_enabled()) {
        return;
    }

    if ($rating_html = wc_get_rating_html($product->get_average_rating())) {
        echo apply_filters('printec_woocommerce_rating_html', '<div class="count-review">' . $rating_html . '</div>');
    } else {
        echo '<div class="count-review"><div class="star-rating"></div></div>';
    }
}

if (!function_exists('printec_check_quantity_product')) {
    function printec_check_quantity_product() {
        global $product;
        $quantity = get_post_meta($product->get_id(), '_sold_individually', true);
        if ($quantity == 'yes' || $product->get_stock_status() == 'outofstock' || $product->is_type('variable') || $product->is_type('grouped')) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('printec_ajax_search_products')) {
    function printec_ajax_search_products() {
        global $woocommerce;
        $search_keyword = $_REQUEST['query'];

        check_ajax_referer( 'ajax-printec-search-nonce', 'security_search' );

        $ordering_args = $woocommerce->query->get_catalog_ordering_args('date', 'desc');
        $suggestions   = array();

        $args = array(
            's'                   => apply_filters('printec_ajax_search_products_search_query', $search_keyword),
            'post_type'           => 'product',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'orderby'             => $ordering_args['orderby'],
            'order'               => $ordering_args['order'],
            'posts_per_page'      => apply_filters('printec_ajax_search_products_posts_per_page', 8),

        );

        $args['tax_query']['relation'] = 'AND';

        if (!empty($_REQUEST['product_cat'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => strip_tags($_REQUEST['product_cat']),
                'operator' => 'IN'
            );
        }

        $products = get_posts($args);

        if (!empty($products)) {
            foreach ($products as $post) {
                $product       = wc_get_product($post);
                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()));

                $suggestions[] = apply_filters('printec_suggestion', array(
                    'id'    => $product->get_id(),
                    'value' => strip_tags($product->get_title()),
                    'url'   => $product->get_permalink(),
                    'img'   => esc_url($product_image[0]),
                    'price' => $product->get_price_html(),
                ), $product);
            }
        } else {
            $suggestions[] = array(
                'id'    => -1,
                'value' => esc_html__('No results', 'printec'),
                'url'   => '',
            );
        }
        wp_reset_postdata();

        echo json_encode($suggestions);
        die();
    }
}

add_action('wp_ajax_printec_ajax_search_products', 'printec_ajax_search_products');
add_action('wp_ajax_nopriv_printec_ajax_search_products', 'printec_ajax_search_products');