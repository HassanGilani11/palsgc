<?php
/**
 * =================================================
 * Hook printec_page
 * =================================================
 */
add_action('printec_page', 'printec_page_header', 10);
add_action('printec_page', 'printec_page_content', 20);

/**
 * =================================================
 * Hook printec_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook printec_single_post
 * =================================================
 */
add_action('printec_single_post', 'printec_post_header', 10);
add_action('printec_single_post', 'printec_post_thumbnail', 20);
add_action('printec_single_post', 'printec_post_content', 30);

/**
 * =================================================
 * Hook printec_single_post_bottom
 * =================================================
 */
add_action('printec_single_post_bottom', 'printec_post_taxonomy', 5);
add_action('printec_single_post_bottom', 'printec_single_author', 10);
add_action('printec_single_post_bottom', 'printec_post_nav', 15);
add_action('printec_single_post_bottom', 'printec_display_comments', 20);

/**
 * =================================================
 * Hook printec_loop_post
 * =================================================
 */
add_action('printec_loop_post', 'printec_post_header', 15);
add_action('printec_loop_post', 'printec_post_content', 30);

/**
 * =================================================
 * Hook printec_footer
 * =================================================
 */
add_action('printec_footer', 'printec_footer_default', 20);

/**
 * =================================================
 * Hook printec_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'printec_template_account_dropdown', 1);
add_action('wp_footer', 'printec_mobile_nav', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'printec_pingback_header', 1);

/**
 * =================================================
 * Hook printec_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook printec_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook printec_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook printec_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook printec_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook printec_sidebar
 * =================================================
 */
add_action('printec_sidebar', 'printec_get_sidebar', 10);

/**
 * =================================================
 * Hook printec_loop_after
 * =================================================
 */
add_action('printec_loop_after', 'printec_paging_nav', 10);

/**
 * =================================================
 * Hook printec_page_after
 * =================================================
 */
add_action('printec_page_after', 'printec_display_comments', 10);

/**
 * =================================================
 * Hook printec_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook printec_woocommerce_before_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook printec_woocommerce_after_shop_loop_item
 * =================================================
 */
