<?php

/**
 * Plugin Name: DesignO Woocommerce Product Designer 
 * Plugin URI: https://wordpress.org/plugins/designo
 * Description: Designo is a pure plug-n-play online editor powered with a centralized print order management software that works for all sorts of businesses be it print service providers, packaging manufacturers, garment decorators, promotional gift suppliers, brand management, marketing organizations, graphics designers, digital assets management, franchise businesses, in-plant printers, or a trade organization.
 * Version: 1.0.9
 * Author: DESIGNNBUY INC
 * Author URI: https://www.designnbuy.com/printcommerce-woocommerce-web2print-productdesign-tool.html
 * License: GPL2
 * Text Domain: designo
 * 
 * @package DesignO
 * @author DESIGNNBUY INC
 * @version 1.0.9
 */

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type,API-Key");

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Test to see if WooCommerce is active (including network activated).
add_action('plugins_loaded', 'designo_load_classes', 9);

function designo_is_woocommerce_activated()
{
    $blog_plugins = get_option('active_plugins', array());
    $site_plugins = is_multisite() ? (array) maybe_unserialize(get_site_option('active_sitewide_plugins')) : array();

    if (in_array('woocommerce/woocommerce.php', $blog_plugins) || isset($site_plugins['woocommerce/woocommerce.php'])) {
        return true;
    } else {
        return false;
    }
}

function designo_load_classes()
{
    if (designo_is_woocommerce_activated() === false) {
        add_action('admin_notices', 'designo_need_woocommerce');
        return;
    }
}


function designo_need_woocommerce()
{
    deactivate_plugins(plugin_basename(__FILE__));
    $error = sprintf(esc_html__('DesignO requires %1$sWooCommerce%2$s to be installed & activated!', 'woocommerce-pdf-invoices-packing-slips'), '<a href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>');
    $message = '<div class="error"><p>' . $error . '</p></div>';
    if (isset($_GET['activate'])) {
        unset($_GET['activate']);
    }
    echo esc_attr($message);
}

/**
 * Activate the plugin.
 */
function designo_pluginprefix_activate()
{
    if (!is_plugin_active('woocommerce/woocommerce.php')) {
        deactivate_plugins(plugin_basename(__FILE__));
    }
    designo_create_table();
    designo_getDesignoTbl();
    designo_create_page();
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'designo_pluginprefix_activate');
require_once(plugin_dir_path(__FILE__) . 'class.designo-rest-api.php');

/**
 * Deactivation hook.
 */
function designo_pluginprefix_deactivate()
{
    designo_delete_page();
    //designo_delete_table();
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'designo_pluginprefix_deactivate');
register_uninstall_hook(__FILE__, 'designo_pluginprefix_function_to_run');

function designo_create_page()
{
    $post_details = array(
        'post_title'    => 'w2p',
        'post_content'  => '',
        'post_status'   => 'publish',
        'post_author'   => 1,
        'post_type' => 'page'
    );
    wp_insert_post($post_details);
}

function designo_delete_page()
{
    $page_id = get_page_by_title('w2p');
    wp_delete_post($page_id->ID, true);
}

function designo_create_table()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "designo_tbl"; // do not forget about tables prefix
    $sql = "CREATE TABLE $table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      designo_url varchar(255) DEFAULT '' NOT NULL,
      designo_status ENUM('yes','no') DEFAULT 'yes' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function designo_delete_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "designo_tbl";
    $sql = $wpdb->prepare("DROP TABLE IF EXISTS $table_name");
    $wpdb->query($sql);
}


/* ================================= */
// Function for add my design and message page in my account page START
function designo_add_premium_support_endpoint()
{
    add_rewrite_endpoint('my-designs', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('my-messages', EP_ROOT | EP_PAGES);
    add_rewrite_endpoint('my-quotes', EP_ROOT | EP_PAGES);
    designo_getDesignoTbl();
}
add_action('init', 'designo_add_premium_support_endpoint');

// 2. Add new query var
function designo_premium_support_query_vars($vars)
{
    $vars[] = 'my-designs';
    $vars[] = 'my-messages';
    $vars[] = 'my-quotes';
    return $vars;
}
add_filter('query_vars', 'designo_premium_support_query_vars', 0);


// 3. Insert the new endpoint into the My Account menu
function designo_add_premium_support_link_my_account($items)
{
    $items['my-designs'] = 'My Designs';
    $items['my-messages'] = 'My Messages';
    $items['my-quotes'] = 'My Quotes';

    return $items;
}
add_filter('woocommerce_account_menu_items', 'designo_add_premium_support_link_my_account');

// 4. Add content to the new tab
function designo_my_designs_content()
{
    $user_id = get_current_user_id();
    $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
    $post_data = array();
    $response = designo_api_call($url, $post_data);
    $myDesignUrl = sanitize_url($_SESSION['designo_url'] . 'app/designs/my-designs/' . $user_id . '/' . sanitize_text_field($_SERVER['SERVER_NAME']) . '/' . $response['token']);
    echo '<iframe width="100%" height="600" style="display:block;" src="' . esc_url($myDesignUrl) . '" frameborder="0">';
}
add_action('woocommerce_account_my-designs_endpoint', 'designo_my_designs_content');


function designo_my_quotes_content()
{
    $user_id = get_current_user_id();
    $user = get_user_by('id', sanitize_text_field($user_id));
    $user_meta = get_user_meta(sanitize_text_field($user_id));

    $email = $user->user_email;
    $name = $user_meta['nickname'][0];

    $email = urlencode($email);
    $name  = urlencode($name);
    
    $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
    $post_data = array();
    $response  = designo_api_call($url, $post_data); 
    $myDesignUrl = sanitize_url($_SESSION['designo_url'] . 'app/quotes/my-quotes/' . $user_id . '/' . $email . '/' . $name . '/' . sanitize_text_field($_SERVER['SERVER_NAME']) . '/' . $response['token']);
    echo '<iframe width="100%" height="600" style="display:block;" src="' . esc_url($myDesignUrl) . '" frameborder="0">';
}
add_action('woocommerce_account_my-quotes_endpoint', 'designo_my_quotes_content');

function designo_my_messages_content()
{
    $user_id = get_current_user_id();
    $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
    $post_data = array();
    $response = designo_api_call($url, $post_data);
    $myDesignUrl = sanitize_url($_SESSION['designo_url'] . 'app/messages/my-messages/' . $user_id . '/' . sanitize_text_field($_SERVER['SERVER_NAME']) . '/' . $response['token']);
    echo '<iframe width="100%" height="500" style="display:block;" src="' . esc_url($myDesignUrl) . '" frameborder="0">';
}

add_action('woocommerce_account_my-messages_endpoint', 'designo_my_messages_content');

add_filter('the_title', 'designo_custom_account_endpoint_titles');
function designo_custom_account_endpoint_titles($title)
{
    global $wp_query;

    if (isset($wp_query->query_vars['my-designs']) && in_the_loop()) {
        return 'My Designs';
    }


    if (isset($wp_query->query_vars['my-quotes']) && in_the_loop()) {
        return 'Quotations';
    }

    if (isset($wp_query->query_vars['my-messages']) && in_the_loop()) {
        return 'My Messages';
    }

    return $title;
}
// Function for add my design and message page in my account page END
/* ================================= */


add_filter('page_template', 'designo_page_template');
function designo_page_template($page_template)
{
    if (is_page('w2p')) {
        add_action('wp_head', 'designo_header_metadata');
?>
        <style>
            header {
                display: none;
            }
        </style>
    <?php
        echo get_header();
        $page_template = dirname(__FILE__) . '/design-tool.php';
    }
    return $page_template;
}

add_action('admin_menu', 'designo_dashboard_page');
function designo_dashboard_page()
{
    add_menu_page(_('DesignO Dashboard'), _('DesignO Dashboard'), 'manage_options', 'dashboard-page', 'designo_welcome_page');
    $getDesignoTbl = designo_getDesignoTbl();
    if ($getDesignoTbl['designo_status'] == 'yes' && $getDesignoTbl['designo_url'] != '') {
        add_submenu_page('dashboard-page', _('DesignO Admin'), _('DesignO Admin'), 'manage_options', 'dashboard-admin', 'designo_add_iframe');
    }
}

function designo_add_iframe()
{
    echo '<iframe width="100%" height="900" style="display:block;" src="' . esc_url($_SESSION['designo_url']) . '" frameborder="0">';
}

function designo_getDesignoTbl()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "designo_tbl";
    $selectSql = $wpdb->get_results("SELECT * FROM $table_name LIMIT 1");
    if (!empty($selectSql)) {
        if (!session_id()) {
            session_start();
            $_SESSION['designo_url'] = sanitize_url($selectSql[0]->designo_url);
            $_SESSION['designo_status'] = sanitize_title($selectSql[0]->designo_status);
        }
        return array(
            'designo_url' => sanitize_url($selectSql[0]->designo_url),
            'designo_status' => sanitize_title($selectSql[0]->designo_status)
        );
    } else {
        return array(
            'designo_url' => '',
            'designo_status' => 'no'
        );
    }
}

function designo_welcome_page()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "designo_tbl";
    $selectSql = $wpdb->get_results("SELECT * FROM $table_name LIMIT 1");
    if ((isset($_REQUEST['designo_url']) && $_REQUEST['designo_url']) != '' && (isset($_REQUEST['designo_status']) && $_REQUEST['designo_status'] != '')) {
        if (count($selectSql) > 0) {
            $updateSql = $wpdb->update($table_name, array('designo_url' => sanitize_url($_REQUEST['designo_url']), 'designo_status' => sanitize_text_field($_REQUEST['designo_status'])), array('id' => $selectSql[0]->id));
            header('Location: admin.php?page=dashboard-page');
            exit;
        } else {
            $args = array(
                'time' => date('Y-m-d H:i:s'),
                'designo_url' => sanitize_url($_REQUEST['designo_url']),
                'designo_status' => sanitize_text_field($_REQUEST['designo_status'])
            );
            $sql = $wpdb->prepare("INSERT INTO $table_name (`time`, `designo_url`, `designo_status`) VALUES (%s, %s, %s)", $args);
            $wpdb->query($sql);
            header('Location: admin.php?page=dashboard-page');
            exit;
        }
    }
    $selectSql = $wpdb->get_results("SELECT * FROM $table_name LIMIT 1");
    ?>
    <!-- center the form html -->
    <style>
        .center {
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }

        .form-table th {
            width: 110px;
        }
    </style>
    <?php
    if (isset($selectSql[0]) && $selectSql[0]->designo_status == 'yes') { ?>
        <style>
            .algin-block {
                top: 50%;
                left: 50%;
                transform: translate3d(-50%, 35%, 0);
                position: absolute;
                width: 100%;
            }
        </style>
    <?php } elseif (isset($selectSql[0]) && $selectSql[0]->designo_status == 'no') { ?>
        <style>
            .algin-block {
                top: 50%;
                left: 50%;
                transform: translate3d(-50%, 35%, 0);
                position: absolute;
                width: 100%;
            }
        </style>
    <?php } else { ?>
        <style>
            .algin-block {
                top: 50%;
                left: 50%;
                transform: translate3d(-50%, 35%, 0);
                position: absolute;
                width: 100%;
            }
        </style>
    <?php } ?>
    <center class="algin-block">
        <div class="wrap">
            <h1><img src="<?php echo esc_url(plugin_dir_url(__FILE__) . 'assets/images/logo-design.jpeg'); ?>" class="img-fluid" alt="site-logo" width="300" height="100"></h1>
            <p>Welcome to DesignO</p>
            <form method="post" action="" class="wrap">
                <table class="form-table center">
                    <tr valign="top">
                        <th scope="row">Enable DesignO</th>
                        <td>
                            <select name="designo_status" style="width: 500px;">
                                <option value="yes" <?php if (isset($selectSql[0])) {
                                    if ($selectSql[0]->designo_status == 'yes') {
                                        echo 'selected';
                                    }
                                } ?>>Yes</option>
                                <option value="no" <?php if (isset($selectSql[0])) {
                                    if ($selectSql[0]->designo_status == 'no') {
                                        echo 'selected';
                                    }
                                } ?>>No</option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">DesignO URL</th>
                        <td>
                            <input style="width: 402px;" type="text" name="designo_url" value="<?php if (isset($selectSql[0])) { echo esc_url($selectSql[0]->designo_url); } ?>" />
                            <p>Ask for 14 days <a href="https://www.designnbuy.com/freetrial.html" target="_blank">Free Trial</a> if you don't have DESIGNO access</p>
                        </td>
                    </tr>

                </table>
                <div class="submit wrap"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></div>
            </form>
        </div>
    </center>
<?php
}

//add js
add_action('wp_enqueue_scripts', 'designo_custom_script_load');
function designo_custom_script_load()
{
    wp_enqueue_script('my-designo-script', plugins_url('/assets/js/designo.js', __FILE__));
    wp_enqueue_script('popup-js', plugins_url('/assets/js/popup.js', __FILE__));
}

// add css
add_action('wp_enqueue_scripts', 'designo_custom_style_load');
function designo_custom_style_load()
{
    wp_enqueue_style('my-designo-style', plugins_url('/assets/css/designo.css', __FILE__));
    wp_enqueue_style('modal_popup', plugins_url('/assets/css/modal_popup.css', __FILE__));
    wp_enqueue_style('dnbcutom-option-style', plugins_url('/assets/css/dnbcustom_options.css', __FILE__));
}


// post call
function designo_api_call($url, $postArray, $header = null)
{

    $url = $url;
    $post_data['body'] = wp_json_encode($postArray);
    $post_data['headers'] = array('Content-Type' => 'application/json', 'Content-Length' => strlen($post_data['body']), 'Authorization' => $header);
    $args = array(
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'sslverify' => false,
        'blocking' => true,
        'headers' => $post_data['headers'],
        'body' => $post_data['body'],
        'cookies' => array()
    );

    $response = wp_remote_post($url, $args);
    // echo "<pre>";  print_r($response);
    return $response = wp_parse_args(json_decode($response['body']));
}

function designo_api_get_call($url, $postArray, $header = null)
{
    $url = $url;
    $postArray['sslverify'] = false;
    if(is_array($postArray)){
        $response = wp_remote_get($url, $postArray);
        return $response['body'];
    }
}


function designo_header_metadata()
{
?>
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=device-dpi">
<?php
}

add_action('wp_head', 'designo_variable_simple_conditions');
function designo_variable_simple_conditions()
{

    if (is_user_logged_in()) {
        $_SESSION['user_id'] = get_current_user_id();
    }
?>
    <style>
        a[href^="<?php echo get_site_url(); ?>/w2p/"] {
            display: none;
        }
    </style>
<?php
    if (!class_exists('WooCommerce')) return; // add this line
    if (is_product()) {
        global $post;
        $post_id = $post->ID;
        $product = wc_get_product($post_id);
        $sku = $product->get_sku();
        $id = $product->get_id();
        $params = array();
        $params['studio_url'] = esc_url(get_site_url()).'/w2p/';
    }

    global $wp;
    if (is_checkout() && !empty($wp->query_vars['order-received'])) {
        $order_id = $wp->query_vars['order-received'];
        $order = wc_get_order($order_id); 
        $items = $order->get_items();

        $items_array = array();
        foreach ($items as $key => $item) {
            // echo "<pre>";
            // print_r($item); die;

            //if(isset($item['cart_design_id']) && $item['cart_design_id'] != ''){
                $temp = array();
                $product = wc_get_product($item->get_product_id());
                $temp['name'] = $item->get_name();
                $temp['SKU'] = $product->get_sku();
                $temp['thumb_image'] = sanitize_url($_SESSION['designo_url'] . 'images/cart/' . $item->get_meta('new_png'));
                $temp['qty'] = $item->get_quantity();
                $temp['price'] = $product->get_price();
                $temp['subtotal'] = $order->get_subtotal();
                $temp['tax'] = $product->get_tax_class();
                $temp['tax_amount'] = $item->get_total_tax();
                $temp['discount'] = 0;
                // $temp['discount'] = $item->get_total_discount() !== null ? $item->get_total_discount() : 0;
                $temp['total_amount'] = $item->get_total();
                $temp['info_buyRequest'] = wp_unslash($item->get_meta('info_buyRequest'));
                $temp['info_buyRequest'] = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $temp['info_buyRequest']); // either ' or " whichever is found
                $requestData = json_decode($temp['info_buyRequest'],true);
                if(!isset($requestData['product'])){
                    $requestData['product'] = $requestData['product_id'];
                }

                if(isset($requestData['imageCode'])){
                        
                    $postFields = array(
                        'w2pdomain' => $requestData['w2p_domain'] ?? 'default',
                        'store_domain' => $requestData['store_domain'] ?? 'default',
                        'dir' => $requestData['imageCode'] ?? 'temp',
                        'images' => $requestData['images'] ?? [],
                    );
                    
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://demoupload.designo.software/savetos3',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => json_encode($postFields),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                    ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);

                    if($response){
                        
                        $curl_remove = curl_init();
                        curl_setopt_array($curl_remove, array(
                        CURLOPT_URL => 'https://demoupload.designo.software/delete-dir',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>json_encode(['dir' => $requestData['imageCode']]),
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                        ));

                        $response = curl_exec($curl_remove);

                        curl_close($curl_remove);
                        
                    }
                }
                $temp['info_buyRequest'] = json_encode($requestData);

                if($item->get_meta('info_buyRequest') == ''){
                    $temp['custom_options'] = json_encode($item->get_meta_data());
                }
                array_push($items_array, $temp);
            //}
        }

   

        $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
        $post_data = array();
        $response = designo_api_call($url, $post_data);

        $url = sanitize_url($_SESSION['designo_url'] . "api/studio/add-order");

       
        $post_data = array('order_array' =>
        array(array(
            'customer_details' => array(
                'ecom_cust_id' => $order->get_user_id() ? $order->get_user_id() : $order->get_billing_email(),
                'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
            ),
            'address' => array(
                'shipping_address' => $order->get_shipping_address_1(),
                'shipping_country' => $order->get_shipping_country(),
                'shipping_state' => $order->get_shipping_state(),
                'shipping_city' => $order->get_shipping_city(),
                'shipping_zip' => $order->get_shipping_postcode(),
                'shipping_contact' => $order->get_billing_phone(),
                'billing_address' => $order->get_billing_address_1(),
                'billing_country' => $order->get_billing_country(),
                'billing_state' => $order->get_billing_state(),
                'billing_city' => $order->get_billing_city(),
                'billing_zip' => $order->get_billing_postcode(),
                'billing_contact' => $order->get_billing_phone(),
            ),
            'order_details' => array(
                'order_id' => (string) $order_id,
                'order_status' => $order->get_status(),
                'order_date' => $order->get_date_created()->date('Y-m-d H:i:s'),
                'store_name' => 'WooCommerce',
                'store_code' => sanitize_text_field($_SERVER['SERVER_NAME']),
                'payment_mode' => $order->get_payment_method_title(),
                'payment_status' => $order->get_status(),
                'subtotal' => $order->get_subtotal(),
                'shipping_amount' => $order->get_shipping_total(),
                'discount_amount' => $order->get_discount_total(),
                'grand_total' => $order->get_total(),
                'status' => '1',
            ),
            'order_items' => $items_array,
        )));

        if(count($items_array) > 0){
            $response = designo_api_call($url, $post_data, $response['token']);
        }
    }
}

add_action('woocommerce_add_order_item_meta', 'designo_add_values_to_order_item_meta', 1, 2);
if (!function_exists('designo_add_values_to_order_item_meta')) {
    function designo_add_values_to_order_item_meta($item_id, $values)
    {
        global $woocommerce, $wpdb;
        $user_custom_values = $values['new_png'];
        $info_buyRequest = $values['info_buyRequest'];
        $cart_design_id = $values['cart_design_id'];
        if (!empty($cart_design_id)) {
            wc_add_order_item_meta($item_id, 'new_png', $user_custom_values);
            wc_add_order_item_meta($item_id, 'info_buyRequest', json_encode($info_buyRequest));
            wc_add_order_item_meta($item_id, 'cart_design_id', json_encode($cart_design_id));
        }
    }
}

//hide custome meta in order details
add_filter('woocommerce_order_item_get_formatted_meta_data', 'designo_change_formatted_meta_data', 20, 2);
function designo_change_formatted_meta_data($meta_data, $item)
{
    $new_meta = array();
    foreach ($meta_data as $id => $meta_array) {
        if ('new_png' === $meta_array->key || 'info_buyRequest' === $meta_array->key) {
            continue;
        }
        $new_meta[$id] = $meta_array;
    }
    return $new_meta;
}

add_action('woocommerce_cart_calculate_fees', 'designo_woo_add_cart_fee');
function designo_woo_add_cart_fee($cart_object)
{
    global $woocommerce;
    $artWorkSetupTotalPrice = 0;
    foreach ($cart_object->cart_contents as $key => $value) {
        //echo $value['reorder']; die;
        if(!isset($value['reorder'])) {
            if (isset($value['cart_design_id']) && $value['cart_design_id'] != '') {
                $artWorkSetupTotalPrice = $artWorkSetupTotalPrice + $value['fix_price'];
            }
        }
    }
    //new change
    $artwork_setup_charge_label = $_SESSION['artwork_setup_charge_label'] ?? 'ArtWork Setup Price';
    $woocommerce->cart->add_fee($artwork_setup_charge_label, $artWorkSetupTotalPrice);
};

add_action('woocommerce_before_calculate_totals', 'designo_before_calculate_totals', 10, 1);
function designo_before_calculate_totals($cart) {
    if ((is_admin() && !defined('DOING_AJAX')) || !isset($_SESSION['designo_url'])) {
        return;
    }
    $url = sanitize_url($_SESSION['designo_url'] . "api/getProductPrice");
    foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        // Get product object
        $product = $cart_item['data'];
        $basePrice = 0;
        if(isset($cart_item['selection_option']) && !empty($cart_item['selection_option'])){
            $post_data = json_decode(stripslashes($cart_item['selection_option']),true);
            $response = optionprice_api_call($url, $post_data);
            
            if(isset($cart_item_data['info_buyRequest'])){
                if(isset($response['totalprice'])){
                    if($response['totalprice'] >= $cart_item['p_type']){
                        $basePrice = $response['totalprice'] / $cart_item['quantity'];
                        $product->set_price($basePrice);
                    }
                }
            } else if(isset($response['totalprice'])) {
                $basePrice = $response['totalprice'] / $cart_item['quantity'];;
                $product->set_price($basePrice);
            }
        } else if(isset($cart_item['p_type']) && $cart_item['p_type']!='') {
            $basePrice = $cart_item['p_type'] / $cart_item['quantity'];
            $product->set_price($basePrice);
        } 


        if (isset($cart_item['cart_design_id']) && $cart_item['cart_design_id'] != '') {
            if (isset($cart_item['variable_price'])) {
                if($basePrice!=0){
                    $base_price = $basePrice;
                } else {
                    $base_price = $product->get_price();
                }
                $price = $base_price + $cart_item['variable_price'];
                $product->set_price(($price));
            }
        }
    }
}

add_action('woocommerce_cart_item_thumbnail', 'designo_cart_thumbnail', 10, 3);
function designo_cart_thumbnail($get_image, $cart_item, $cart_item_key)
{

    if ((isset($cart_item['cart_design_id']) && $cart_item['cart_design_id'] != '') && (isset($cart_item['new_png']))) {
        $cartimages = array();
        if (isset($cart_item['new_png']) && $cart_item['new_png'] != '') {
            $cartimages['side1_image'] = sanitize_url($_SESSION['designo_url'] . 'images/cart/' . $cart_item['new_png']);
            foreach ($cartimages as $image) {
                echo '<a href="' . esc_url(get_permalink($cart_item['product_id'])) . '"><img width="450" height="450" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" src="' . esc_url($image) . '" /></a>';
            }
        }
    } else {
        if(isset($cart_item['info_buyRequest'])){
            $ImaegeDetail = json_decode($cart_item['info_buyRequest'],true);
            if(isset($ImaegeDetail['imageCode'])){
                echo '<a href="' . esc_url(get_permalink($cart_item['product_id'])) . '"><img width="450" height="450" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" src="' . esc_url(plugin_dir_url(__FILE__) . 'assets/images/uploaded_artwork.png') . '" /></a>';
            }
        } else {
            return $get_image;    
        }
    }
}

//get updated order status function for woocommerce
function designo_status_change($post_id)
{
    if (get_post_type($post_id) == 'shop_order') {
        $woo_order = wc_get_order($post_id);
        $woo_order_status = $woo_order->get_status();
        $woo_order_id = $woo_order->get_id();
        $store_code = sanitize_text_field($_SERVER['SERVER_NAME']);

        // get token api
        $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
        $post_data = array();
        $response = designo_api_call($url, $post_data);

        // update order api
        $url = sanitize_url($_SESSION['designo_url'] . "api/studio/update-order");
        $post_data = array('order_array' =>
        array(
            array(
                'order_details' => array(
                    'order_id' => (string) $woo_order_id,
                    'order_status' => $woo_order_status,
                    'store_code' => $store_code
                ),
            )
        ));
        $response = designo_api_call($url, $post_data, $response['token']);
        if ($response['success'] == '1') {
            return array(
                'success' => 'true',
                'messages' => 'The order has been updated successfully'
            );
        } else {
            return array(
                'success' => 'false',
                'messages' => 'Store/Order not exist'
            );
        }
    }

    if (get_post_type($post_id) == 'product') {
        $product_id = $post_id;
        $product_sku = get_post_meta($product_id, '_sku', true);
        if ($product_sku) {
            $product = new WC_Product(wc_get_product_id_by_sku($product_sku));
            $store_code = sanitize_text_field($_SERVER['SERVER_NAME']);

            $itemArray = [];
            if (wc_get_product_id_by_sku($product_sku) > 0) {
                $item["name"] = $product->get_name();
                $item["sku"] = $product->get_sku();
                $categories = $product->get_category_ids();
                $cat_info = array();
                foreach ($categories as $key => $value) {
                    $cat_finalname = '';
                    if ($cat_name = get_term_by('id', $value, 'product_cat'))
                        $cat_finalname = $cat_name->name;

                    $cat_info[] = array('id' => $value, "name" => $cat_finalname);
                }
                $item["short_description"]["html"] = $product->get_short_description();
                $item["image"]["url"] = wp_get_attachment_url($product->get_image_id());
                $item["categories"] = $cat_info;

                // for configurable_options
                $attributes = $product->get_attributes();

                $configurable_options = [];
                if (count($attributes) > 0) {
                    foreach ($attributes as $key => $value) {
                        $temp['attribute_code'] = $attribute_name = preg_replace('/pa_/', '', $key);
                        $temp['attribute_id'] = wc_attribute_taxonomy_id_by_name($attribute_name);
                        $configurable_options[] = $temp;
                    }
                    $item["configurable_options"] = $configurable_options;
                    // for variants
                    foreach ($attributes as $key => $value) {
                        $attribute_name = preg_replace('/pa_/', '', $key);
                        $attribute_terms = wc_get_product_terms($product->get_id(), $key);
                        $attribute_slug = wc_get_product_terms($product->get_id(), $key, array('fields' => 'slugs'));
                        for ($i = 0; $i < count($attribute_terms); $i++) {
                            $slug = array_slice($attribute_slug, $i, 1);
                            $attri[$attribute_name][] = array_slice($attribute_terms, $i, 1);
                        }
                    }

                    $variants = [];
                    $finalVariants = [];
                    foreach ($attri as $key => $value) {
                        if (count($attri[$key]) > 0) {
                            foreach ($attri[$key] as $key1 => $value1) {
                                $tempVariants['label'] = $value1[0]->name;
                                $tempVariants['code'] = preg_replace('/pa_/', '', $value1[0]->taxonomy);
                                $tempVariants['value_index'] = $value1[0]->term_id;
                                $variants['attributes'][] = $tempVariants;
                            }
                        }
                    }
                    $finalVariants = $variants;
                    $item["variants"][] = $finalVariants;
                }
                $response["products"]["total_count"] = 1;
                $itemArray[] = $item;
                $response["products"]["items"] = $itemArray;
            } else {
                $response["products"]["total_count"] = 0;
                $response["success"] = "false";
                $response["error"]["message"] = "Product does not exist";
            }

            // update order api
            $url = sanitize_url($_SESSION['designo_url'] . "api/update-product");
            $post_data = array('store_code' => $store_code, 'SKU' => $product_sku, 'params' => json_encode(array('data' => $response)));
            $response = designo_api_call($url, $post_data);

            if ($response['success'] == '1') {
                return array(
                    'success' => 'true',
                    'messages' => 'The product has been updated successfully'
                );
            } else {
                return array(
                    'success' => 'false',
                    'messages' => 'Product not exist'
                );
            }
        }
    }
}
add_action('save_post', 'designo_status_change');


// define the woocommerce_order_again_cart_item_data callback 
function filter_woocommerce_order_again_cart_item_data( $array, $item, $order ) { 
    if(isset($item['cart_design_id']) && $item['cart_design_id'] != '') {
        $array['reorder'] = 1;
        $array['cart_design_id'] = $item['cart_design_id'];
        $array['variable_price'] = json_decode($item['info_buyRequest'])->variable_price;
        $array['fix_price'] = json_decode($item['info_buyRequest'])->fix_price;
        $array['new_png'] = $item['new_png'];
    }
    return $array; 
}; 
         
// add the filter 
add_filter( 'woocommerce_order_again_cart_item_data', 'filter_woocommerce_order_again_cart_item_data', 10, 3 );

//add to cart functionality with woocommerce 7.4.0 compatible
function woocommerce_ajax_add_to_cart_global() {
    global $wpdb;
    global $product;

    $cart_item_data = array();
    $variations = array();

    $request = $_REQUEST;
    $product_id = $request['product'];
    $quantity = $request['qty'];
    $variation_id = 0;
    $variations = array();
    $cart_item_data = array();

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', $product_id);
    $product_status = wc_get_product($product_id)->get_status();
    if ($product_status == 'draft' || $product_status == 'pending' || $product_status == 'auto-draft') {
        return new WP_Error('product_invalid', __('Product is not available for purchase.', 'woocommerce'), array('status' => 400));
    }

    //get variation id from product id for super attribute
    $variationArray = array();
    if (isset($request['super_attribute']) && !empty($request['super_attribute'])) {
        foreach ($request['super_attribute'] as $key => $term) {
            $attribute = wc_get_attribute($key);
            $variationArray["attribute_" . $attribute->slug] = get_term($term)->slug;
        }
    }

    // for options
    $optionsArray = array();
    if (isset($request['options']) && !empty($request['options'])) {
        foreach ($request['options'] as $key => $term) {
            $optionsArray["attribute_" . $key] = $term;
        }
    }

    $pass_attri_array = (isset($optionsArray) && !empty($optionsArray)) ? $optionsArray : $variationArray;

    $variation_id = find_matching_product_variation_id($product_id, $pass_attri_array);

    if ($variation_id != 0) {
        $product = wc_get_product($product_id);
        $_product = new WC_Product_Variation($variation_id);
        $variation_data = $_product->get_variation_attributes();
        $variations = $variation_data;
    }

    $cart_item_data['cart_design_id'] = $request['current_time'];
    $cart_item_data['fix_price'] = $request['fix_price'];
    $cart_item_data['variable_price'] = $request['variable_price'];
    $cart_item_data['new_png'] = $request['png'];
    $cart_item_data['info_buyRequest'] = map_deep( wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    //add to cart

   if (sizeof(WC()->cart->get_cart()) > 0) {
        print_r('1');
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
        do_action('woocommerce_ajax_added_to_cart', $product_id);
       
        $data =  array(
            'data' => array('success' => "true"),
            'error' => array('message' => "You added test canvas to your shopping cart.")
        );
    } else {
        if ($variation_id != 0) {
            WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
            do_action('woocommerce_ajax_added_to_cart', $product_id);
            $data = array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
            print_r( WC()->cart);
        } elseif ($variation_id == 0) {
            WC()->cart->add_to_cart($product_id, $quantity, '', $cart_item_data);
            $product_item_key = WC()->session->get('product_item_key');
            do_action('woocommerce_ajax_added_to_cart', $product_id);
            $data = array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
            print_r('3');
        } else {
            $data = array(
                'data' => array('success' => "false"),
                'error' => array('message' => "Product is not available for purchase.")
            );
        }
    }

   
   
    do_action('woocommerce_ajax_added_to_cart', $product_id);
    WC_AJAX :: get_refreshed_fragments();
    //wp_send_json( $data );
    

    //wp_die();
}

function designnbuy_ajax_add_to_cart() {
    global $wpdb;
    global $product;

    $cart_item_data = array();
    $variations = array();

    if(isset($_REQUEST['actionFrom'])){
        $request =  $_REQUEST['data'];
    } else {
        $request = $_REQUEST;
    }
 
    $product_id = isset($request['product']) ? $request['product'] :  $request['product_id'];
    $quantity = isset($request['qty']) ? $request['qty'] : $request['quantity'];
    $variation_id = $request['variation_id'];

    $variationArray = array();
    $color_id = $request['color_id'];
    $size_id = $request['size_id'];
    $selected_color_id =  $request["super_attribute[$color_id"];
    $selected_size_id = $request["super_attribute[$size_id"];
    
    $request['super_attribute'][$request['color_id']] = $selected_color_id;
    $request['super_attribute'][$request['size_id']] = $selected_size_id;
    

    if (isset($request['super_attribute']) && !empty($request['super_attribute'])) {
        foreach ($request['super_attribute'] as $key => $term) {
            $attribute = wc_get_attribute($key);
            $variationArray["attribute_" . $attribute->slug] = get_term($term)->slug;
        }
    }

    // for options
    $optionsArray = array();
    if (isset($request['options']) && !empty($request['options'])) {
        foreach ($request['options'] as $key => $term) {
            $optionsArray["attribute_" . $key] = $term;
        }
    }
   
    $pass_attri_array = (isset($optionsArray) && !empty($optionsArray)) ? $optionsArray : $variationArray;

    if(isset($request['variation_id'])){
        $variation_id = $request['variation_id'];
    } else {
        $variation_id = find_matching_product_variation_id($product_id, $pass_attri_array);
    }


    if ($variation_id != 0) {
        $product = wc_get_product($product_id);
        $_product = new WC_Product_Variation($variation_id);
        $variation_data = $_product->get_variation_attributes();
        $variations = $variation_data;
    }

    //info buy request array
    //$info_buy_request = $request;

    // $info_buy_request['options'] = $request['options'];
    // $info_buy_request['svg'] = $request['svg'];
    // $info_buy_request['csvfiledata'] = $request['csvfiledata'];
    // $info_buy_request['csvheaderdata'] = $request['csvheaderdata'];
    // $info_buy_request['current_time'] = $request['current_time'];
    // $info_buy_request['product'] = $request['product'];
    // $info_buy_request['qty'] = $request['qty'];
    // $info_buy_request['form_key'] = $request['form_key'];
    // $info_buy_request['comment'] = $request['comment'];
    // $info_buy_request['BgId'] = $request['BgId'];
    // $info_buy_request['cartId'] = $request['cartId'];
    // $info_buy_request['quoteId'] = $request['quoteId'];
    // $info_buy_request['toolType'] = $request['toolType'];
    // $info_buy_request['pagename'] = $request['pagename'];
    // $info_buy_request['size'] = $request['size'];
    // $info_buy_request['extra_pages'] = $request['extra_pages'];
    // $info_buy_request['addon_price'] = $request['addon_price'];
    // $info_buy_request['variable_price'] = $request['variable_price'];
    // $info_buy_request['fix_price'] = $request['fix_price'];
    // $info_buy_request['total_price'] = $request['total_price'];
    // $info_buy_request['customOptionData'] = $request['customOptionData'];
    // $info_buy_request['png'] = $request['png'];

    $info_buy_request = $request;
  
    //custom cart data

    $cart_item_data['cart_design_id'] = $request['current_time'];
    $cart_item_data['fix_price'] = $request['fix_price'];
    $cart_item_data['variable_price'] = $request['variable_price'];
    $cart_item_data['new_png'] = $request['png'];
    $cart_item_data['info_buyRequest'] = json_encode($info_buy_request);

    $quantity          = empty( $quantity ) ? 1 : wc_stock_amount( $quantity );
    $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );

    if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity,$variation_id,$variations,$cart_item_data) ) {
       
        //wc_add_to_cart_message( array( $product_id => $quantity ), true );
        $data = array(
            'data' => array('success' => "true"),
            'error' => array('message' => "You added test canvas to your shopping cart.")
        );
    }
    
    wp_send_json( $data );
}

add_action('wp_ajax_designnbuy_ajax_add_to_cart','designnbuy_ajax_add_to_cart', 11, 3);
add_action('wp_ajax_nopriv_designnbuy_ajax_add_to_cart','designnbuy_ajax_add_to_cart', 11, 3);



add_action( 'woocommerce_after_add_to_cart_button', 'add_custom_button');
function add_custom_button(){
   if (is_product()) {
        global $post;
        $post_id = $post->ID;
        $product = wc_get_product($post_id);
        $sku = $product->get_sku();
        $id = $product->get_id();
        $params = array();
        $params['studio_url'] = esc_url(get_site_url()).'/w2p/';

        $jk = '';
        if ($sku && $id && (isset($_SESSION['designo_url']) && $_SESSION['designo_url'] != '') && (isset($_SESSION['designo_status']) && $_SESSION['designo_status'] != 'no')) {
            //get token
            $url = sanitize_url($_SESSION['designo_url'] . "api/studio/ecomm-token");
            $post_data = array();
            $response = designo_api_call($url, $post_data);
            $token = $response['token'];
            //echo "<pre>"; print_r($response['token']); die;
            $url = sanitize_url($_SESSION['designo_url'] . "api/studio/is-product-customized");
            $post_data = array('SKU' => $sku, 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']), 'id' => $id);

            $response = designo_api_call($url, $post_data, $token);
            //echo "<pre>"; print_r($response); die;
            if (isset($response['success']) && $response['success'] == true) {
                $url = $response['url'];
                $label = $response['personalize_button_label'];
                //new change
                $_SESSION['artwork_setup_charge_label'] = $response['artwork_setup_charge_label'];
                
                    $jk .= "<button type='button' id='personalize_btn' class='single_add_to_cart_button button alt wp-element-button' onclick='goToPersonalizePage(&#39;variable&#39;,&#39;" . esc_attr($id) . "&#39;,&#39;" .  esc_url(get_site_url()) . "&#39;);'>" . $label . "</button>";
            }

            $url = sanitize_url($_SESSION['designo_url'] . "api/studio/is-product-quickedit");
            $quickeditData = designo_api_call($url, $post_data, $token);
            //echo "<pre>quickedit"; print_r($response); die;
            if (isset($quickeditData['success']) && $quickeditData['success'] == true) {
                $url = $quickeditData['url'];
                $quickeditlabel = $quickeditData['quickedit_label'];
                
                    $jk .= "<button type='button' id='quickedit_btn' class='single_add_to_cart_button button alt wp-element-button' onclick='goToQuickeditPage(&#39;variable&#39;,&#39;" . esc_attr($id) . "&#39;,&#39;" .  esc_url(get_site_url()) . "&#39;);'>" . $quickeditlabel . "</button>";
                
            }

            $url = sanitize_url($_SESSION['designo_url'] . "api/studio/browse-template-api");
            $templateData = designo_api_call($url, $post_data, $token);
            if (isset($templateData['success']) && $templateData['success'] == true) {
                
                /* set as per need of template with respect to single code for all e-commerce. */
                $params['SKU'] = $sku;
                $params['product'] = $id;
                $params['store_id'] = sanitize_text_field($_SERVER['SERVER_NAME']);
                $url = sanitize_url($_SESSION['designo_url']) .'api/browseTemplates?'.http_build_query($params);
                $url = str_replace(" ", '%20', $url);
                
                $browseTemplateLabel = ($templateData['browse_button_label'] && $templateData['browse_button_label'] != '') ? $templateData['browse_button_label'] : 'Browse Templates';
                
                    $jk .= "<button type='button' id='browsetemplate_btn' class='single_add_to_cart_button button alt wp-element-button' onclick='openTemplate(&#39;variable&#39;,&#39;" . esc_attr($id) . "&#39;,&#39;" .  esc_url($url) . "&#39;);'>" . $browseTemplateLabel . "</button>";
                
            }

           
            $url = sanitize_url($_SESSION['designo_url'] . "api/detailPageHtml/".$sku.'/'.sanitize_text_field($_SERVER['SERVER_NAME'].'/false'));
            $url = str_replace(" ", '%20', $url);
            
            $UploadFileResponce =  designo_api_get_call($url, $post_data, $token);
            if($UploadFileResponce != ''){
                
                        $jk .= '<button type="button" class="single_add_to_cart_button button alt wp-element-button" title="Upload Files" id="uploadfiles" onclick="openFileUpload()" data-nexturl="'.$params['studio_url'].'" class="personaliseit action tocart primary">
                        <span>Upload Files</span>
                    </button>'.$UploadFileResponce;
                
            }
            
        }
    }

    echo $jk;
}



/* DesignO price & Option */
/* For custom option , Qty update disable  */
add_filter('woocommerce_cart_item_quantity', 'disable_qty_update', 10, 3);
function disable_qty_update($product_quantity, $cart_item_key, $cart_item) {

    if(isset($cart_item['custom_option_list'])){
        return $cart_item['quantity'];   
    }
    return $product_quantity;
}

add_action('woocommerce_checkout_create_order_line_item', 'store_custom_option_in_order', 10, 4);
function store_custom_option_in_order($item, $cart_item_key, $values, $order) {
    if($values['custom_option_list']!=''){
        $customOptionData = explode(',', $values['custom_option_list']);
        foreach($customOptionData as $labelName){
            $item->add_meta_data(__($labelName), $values[$labelName]);
        }
   } 
}


// Display custom field values on the cart page
add_filter('woocommerce_get_item_data', 'display_custom_field_in_cart', 10, 2);
function display_custom_field_in_cart($cart_data, $cart_item) {
    if(isset($cart_item['custom_option_list'])){
        if($cart_item['custom_option_list']!=''){
            $customOptionData = explode(',', $cart_item['custom_option_list']);
            foreach($customOptionData as $labelName){
                $cart_data[] = array(
                    'key'   => __($labelName),
                    'value' => $cart_item[$labelName]
                );
            }
       } 
    }
   return $cart_data;
}


add_filter('woocommerce_add_cart_item_data', 'store_custom_option_in_cart', 10, 2);
function store_custom_option_in_cart($cart_item_data, $product_id) {

    if(!isset($_SESSION['designo_url'])){
        return;
    }

    if(isset($_POST['actionFrom'])){
        /* Post from File upload */
        $data = $_POST['data']['form_selection'];
        while (strpos($data, '\\') !== false) {
            $data = stripslashes($data);
        }
        $_POST['form_selection'] = $data;

        $dataVal = json_decode($data,true);
        
        $optiosData = array();
        foreach($dataVal['options'] as $dataKey=>$dataValDetail){
            $optiosData[$dataValDetail['option_id']] = $dataValDetail['value_id'];
        }

        $_POST['options'] = $optiosData;
    }

    $product = wc_get_product($product_id);
    $sku = $product->get_sku();
    $productId = $product->get_id();

    $url = sanitize_url($_SESSION['designo_url'] . "api/getProductOptions");
    $post_data = array('product' => urlencode($sku), 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']));
    $options = optionprice_api_call($url, $post_data);

   
    if(isset($_POST['options'])){
       $prepartedOptions = [];
       if(isset($options['options']) && !empty($options['options']) && $_POST['options']){
            foreach($_POST['options'] as $key => $opt){
                foreach($options['options'] as $optItem){
                    if($optItem['option_id'] == $key){
                        if($optItem['type'] == 'text_box' || $optItem['type'] == 'text_field' || $optItem['type'] == 'hidden_field'){
                            array_push($prepartedOptions,['label' => $optItem['title'] , 'value' => $opt]);
                        } else {
                            if(isset($optItem['values'])){
                                foreach($optItem['values'] as $vkey => $val){
                                    if($val['value_id'] == $opt){
                                        if($optItem['option_id'] == '65c5cc61a212f'){

                                            if(isset($val['length'])){
                                                array_push($prepartedOptions,['label' => $optItem['title'] ?? $optItem['webtoprint'], 'value' => $val['width'] .'x'. $val['height'] .'x'. $val['length']]);
                                            } else {
                                                array_push($prepartedOptions,['label' => $optItem['title'] ?? $optItem['webtoprint'], 'value' => $val['width'] .'x'. $val['height']]);
                                            }
                                            
                                        } else {
                                            array_push($prepartedOptions,['label' => $optItem['display_name'] ?? $optItem['title'] ?? $optItem['webtoprint'], 'value' => $val['value_name'] ?? $val['title'] ?? '']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $optionList = array();
        foreach($prepartedOptions as $data){
            $cart_item_data[$data['label']] = sanitize_text_field($data['value']);
            $optionList[] = $data['label'];
        }

        $cart_item_data['custom_option_list'] = sanitize_text_field(implode(',', $optionList));
        $cart_item_data['selection_option'] = $_POST['form_selection'];

        if(isset($cart_item_data['info_buyRequest'])){
            /* post from design tool */
            if(isset($_POST['actionFrom'])){
                $pk = json_decode($_POST['form_selection'],true);
            } else {
                $data = json_decode($cart_item_data['info_buyRequest'],true);
                $pk['options'] = array();
                $mk = 0;
                foreach($data['options'] as $optionKey=>$optionVal){
                    if(!is_string($optionKey)){
                        $pk['options'][$mk]['option_id'] = $optionKey;
                        $pk['options'][$mk]['value_id'] = $optionVal;
                        $mk++;
                    } else {
                        if($optionKey == '65c5cc61a212f-width'){
                            $pk['width']  = $optionVal;
                        } else if($optionKey == '65c5cc61a212f-height'){
                            $pk['height']  = $optionVal;
                        } else if($optionKey == '65c5cc61a212f-length'){
                            $pk['length']  = $optionVal;
                        }

                    }
                }
                $pk['qty']  = $data['qty'];

                if(!isset($pk['width'])){
                    $sizeArray = explode('x',$cart_item_data['sizing']);
                    $pk['width']  = $sizeArray[0];
                    $pk['height']  = $sizeArray[1];
                    if(isset($sizeArray[1])){
                        $pk['length']  = $sizeArray[2];
                    }
                }

                $pk['store_id']  = sanitize_text_field($_SERVER['SERVER_NAME']);
                $pk['product_id']  = $sku;


            }

            $url = sanitize_url($_SESSION['designo_url'] . "api/getProductPrice");
            $response = optionprice_api_call($url, $pk);
            $cart_item_data['p_type'] = $response['totalprice'];
        } else {
            $cart_item_data['p_type'] = base64_decode($_POST['p_type']);
        }

    }

    return $cart_item_data;
}


add_action('woocommerce_add_to_cart_validation', 'custom_product_validation', 10, 3);

function custom_product_validation($passed, $product_id, $quantity) {
    // Get the product object
    $product = wc_get_product($product_id);


    // Check if the product is valid for adding to the cart
    //if (/* Your validation condition */) {
        // If the product is not valid, set $passed to false
        
        // Optionally, add an error message

        if(!empty($_POST['options'])){
            if(isset($_POST['options']['65c5cc61a212f-width'])){
                if($_POST['options']['65c5cc61a212f-width'] <= 0 || $_POST['options']['65c5cc61a212f-width'] == '' ){
                    $passed = false;
                    $message = 'Width : This field is required.';
                    wc_add_notice(__($message, 'woocommerce'), 'notice');    
                }

                if($_POST['options']['65c5cc61a212f-height'] <= 0 || $_POST['options']['65c5cc61a212f-height'] == '' ){
                    $passed = false;
                    $message = 'Height : This field is required.';
                    wc_add_notice(__($message, 'woocommerce'), 'notice');    
                }

                if(isset($_POST['options']['65c5cc61a212f-length'])){
                    if($_POST['options']['65c5cc61a212f-length'] <= 0 || $_POST['options']['65c5cc61a212f-length'] == '' ){
                        $passed = false;
                        $message = 'Length : This field is required.';
                        wc_add_notice(__($message, 'woocommerce'), 'notice');    
                    }

                }
            }
        }
        
    //}

    // Return the validation result
    return $passed;
}


add_action( 'woocommerce_before_add_to_cart_button', 'custom_field_before_add_to_cart_button' );
function custom_field_before_add_to_cart_button() {
    global $post;
    $post_id = $post->ID;
    $product = wc_get_product($post_id);
    $sku = $product->get_sku();

    ?>
    
    <input type="hidden" id="product_sku" name="product_sku" value="<?php echo $sku; ?>" />
    <input type="hidden" id="p_type" name="p_type" />
    <input type="hidden" id="price_error" value="" name="price_error">
    <input type="hidden" id="form_selection" value="" name="form_selection">

    <script>


jQuery(document).ready(function($) {
    // Handle swatch selection
    // $(".product-custom-option, #qty, .qty").on("change", function() {
    //     var formDataArray = $(".cart").serializeArray();
    //     getProductPrice(formDataArray);
    // });

    $("input.product-custom-option, #qty, .qty").on("input", function() {
        var formDataArray = $(".cart").serializeArray();
        getProductPrice(formDataArray);
    });

    $("select.product-custom-option").on("change", function() {
        var formDataArray = $(".cart").serializeArray();
        getProductPrice(formDataArray);
    });

    function validateInput(inputElement,fieldName) {
        var value = $(inputElement).val(); // Get the value of the input field
        var minValue = parseInt($(inputElement).attr('min'));
        var maxValue = parseInt($(inputElement).attr('max'));

        // Perform validation
        if (value < minValue || value > maxValue) {
            // If value is not within the specified range, display an error message
            var errorMessage = 'Please enter a value between ' + minValue + ' and ' + maxValue;
            $('.validation-message-' + fieldName).remove();
            $(inputElement).parent().append('<p class="validation-message-' + fieldName + '">' + errorMessage + '</p>');
            $('.validation-message-' + fieldName).focus();
        } else {
            $('.validation-message-' + fieldName).remove();
        }
    }

    $('#options_65c5cc61a212f-width_text').on('keyup', function() {
        validateInput(this,'width');
    });

    $('#options_65c5cc61a212f-height_text').on('keyup', function() {
        validateInput(this,'height');
    });


    // When the "Add to Cart" button is clicked
    $('.single_add_to_cart_button').on('click', function(e) {
        if ($('#options_65c5cc61a212f-width_text').length) {
            var validateField = function(fieldId, validationClass) {
                var enteredValue = parseInt($(fieldId).val());
                var minValue = parseInt($(fieldId).attr('min'));
                var maxValue = parseInt($(fieldId).attr('max'));
                debugger;
                var validationMessageField = $(`.validation-message-${validationClass}`);
                
                validationMessageField.remove();

                if (isNaN(enteredValue) || enteredValue < minValue || enteredValue > maxValue) {
                    e.preventDefault();
                    $(fieldId).parent().append(`<p class="validation-message-${validationClass}"></p>`);
                    $(`.validation-message-${validationClass}`).text(`Please enter a value between ${minValue} and ${maxValue}.`);
                    $(fieldId).focus();
                    return false;
                }
                
                return true;
            };

            if (!validateField('#options_65c5cc61a212f-width_text', 'width') || !validateField('#options_65c5cc61a212f-height_text', 'height')) {
                return;
            }
        }
    });

    var formDataArray = jQuery(".cart").serializeArray();
    getProductPrice(formDataArray);

    jQuery(window).load(function() {
        setTimeout(() => {
            var formDataArray = jQuery(".cart").serializeArray();
            console.log('after load');
            getProductPrice(formDataArray);
            debugger;
            jQuery(".custom-image-dropdown .selected-option").text(jQuery(".custom-image-dropdown .selected-option").parent().next().find(":selected").text()); //image box selected text
        }, 500);

        if (jQuery('select[name="options[65c5cc61a211e]"]').length) {
            console.log('change Qty');
            var updateQuantity = function() {
                jQuery('input[name="quantity"]').val(parseInt(jQuery("select[name='options[65c5cc61a211e]']").find("option:selected").text()));
            };
            updateQuantity();
            jQuery("select[name='options[65c5cc61a211e]']").on("change", updateQuantity);
        }
    });

    jQuery(".custom-image-dropdown .selected-option").click(function() {
        jQuery(".custom-image-dropdown .options-list").toggle();
    });

    jQuery(".custom-image-dropdown .options-list li").click(function() {
        var selectedValue = jQuery(this).attr("data-value");
        jQuery(this).parents('.custom-image-dropdown').next().val(selectedValue).trigger('change');
        jQuery(".custom-image-dropdown .selected-option").text(jQuery(this).text());
        jQuery(".custom-image-dropdown .options-list").hide();
        // Perform any additional actions based on the selected value
    });

    var myException = JSON.parse(exception);
    if(myException.length){
        jQuery.each(myException, function (key,val) {
            debugger;
            if(jQuery(`[name="options[${val.option_id}]"]`).length){
                jQuery(`[name="options[${val.option_id}]"]`).change(function(){
                    
                    debugger;
                    if(jQuery(this).val() == val.value_id){
                        if(val.all_selected){
                            jQuery(`[id="options_${val.dont_show_option_id}_field"]`).parent().parent().hide();
                        } else {
                            jQuery.each(val.dont_show_value_id, function(key,v){
                                jQuery(`[name="options[${val.dont_show_option_id}]"] option[value="${v}"]`).hide(); //for select box
                                if(jQuery(`[name="options[${val.dont_show_option_id}]"]`).val() == v){
                                    jQuery(`[name="options[${val.dont_show_option_id}]`).val("");
                                }
                            })
                        }
                        //jQuery(`[name="options[${val.dont_show_option_id}]"]`).hide();
                    } else {
                        //jQuery(`[name="options[${val.dont_show_option_id}]"]`).show();
                        if(val.all_selected){
                            jQuery(`[id="options_${val.dont_show_option_id}_field"]`).parent().parent().show();
                        } else {
                            jQuery.each(val.dont_show_value_id, function(key,v){
                                jQuery(`[name="options[${val.dont_show_option_id}]"] option[value="${v}"]`).show(); //for select box
                            })
                        }
                    }
                    
                });
            }
            jQuery(`[name="options[${val.option_id}]"]`).trigger("change");
        });
    }

    async function getProductPrice(formDataArray) {
        jQuery(".custom-image-dropdown .selected-option").text(jQuery(".custom-image-dropdown .selected-option").parent().next().find(":selected").text()); //image box 
        jQuery('.cart').block({
            message: null,
            overlayCSS: {
                cursor: 'none',
                background: '#fff',
                opacity: 0.6
            }
        });

        var filteredDataArray = $.grep(formDataArray, function(field) {
            return field.name.indexOf('option') === 0;
        });
        var formData = { options: [] };
        formData["qty"] = jQuery('input[name="quantity"]').val();    
        $.each(filteredDataArray, function(i, field) {
            if (field.name == "options[65c5cc61a211e]") {

                if(jQuery('select[name="options[65c5cc61a211e]"]').length > 0 ){
                    formData["qty"] = $('select[name="options[65c5cc61a211e]"]').find(":selected").text().trim();    
                }
                //jQuery('input[name="quantity"]').val(parseInt(jQuery("select[name='options[65c5cc61a211e]']").find("option:selected").text()));
            } else if (field.name == "options[65c5cc61a212f]") {
                var sizes = $('select[name="options[65c5cc61a212f]"]').find(":selected").text().trim().split("x");
                console.log(sizes);
                if (sizes.length >= 2) {
                    formData["width"] = sizes[0];
                    formData["height"] = sizes[1];
                    if(sizes.length == 3){
                        formData["length"] = sizes[2];    
                    }
                } else {
                    var sizes = jQuery('select[name="options[65c5cc61a212f]"]').find(":selected").text().trim().split('×')
                    formData["width"] = sizes[0];
                    formData["height"] = sizes[1];
                    if(sizes.length == 3){
                        formData["length"] = sizes[2];    
                    }
                }
            } else {
                switch (field.name) {
                    case 'options[65c5cc61a212f-width]':
                        formData["width"] = field.value;
                        break;
                    case 'options[65c5cc61a212f-height]':
                        formData["height"] = field.value;
                        break;
                    case 'options[65c5cc61a212f-length]':
                        formData["length"] = field.value;
                        break;
                    default:
                        var match = field.name.match(/\[(.*?)\]/);
                        if (match && match.length > 1) {
                            var optionObject = { option_id: match[1], value_id: field.value };
                            formData.options.push(optionObject);
                        }
                        break;
                }
            }
        });

        formData["store_id"] = '<?php echo sanitize_text_field($_SERVER['SERVER_NAME']); ?>';
        formData["product_id"] = $('#product_sku').val();

        console.log('form data', JSON.stringify(formData));
        var w2p_domain = "<?=  sanitize_url($_SESSION['designo_url']); ?>";
        console.log('entered', w2p_domain);
        var settings = {
            "url": w2p_domain + "api/getProductPrice",
            "method": "POST",
            "timeout": 0,
            "headers": {
                "Content-Type": "application/json",
            },
            "data": JSON.stringify(formData),
        };

        $.ajax(settings)
        .done(function(response) {
            console.log('success', response);
            if (response.success) {

                jQuery("#p_type").val(btoa(response.totalprice));
                
                jQuery('.woocommerce-Price-amount').html(response.displayPrice);
                jQuery('.woocommerce-Price-currencySymbol').parent().html(response.displayPrice);
                
                jQuery("#form_selection").val(JSON.stringify(formData));
            } else {
                jQuery("#price_error").val(response.message);
            }
            jQuery('.cart').unblock();
        })
        .fail(function(xhr, status, error) {
             console.log('Fail Section', status, error);
            console.error("Request failed:", status, error);
            if (xhr && xhr.responseText) {
                console.error("xhr.responseText" + xhr.responseText);
            }
            jQuery('.cart').unblock();
        });
    }

});


</script>

    <script>
    jQuery(function($) {
        //for validation
        jQuery('.cart').block({
            message: 'Wait! we are fetching Options',
            overlayCSS: {
                cursor: 'none',
                background: '#fff',
                opacity: 0.6
            }
        });
        var element = $("#options_65c5cc61a211e");
        if (element.length > 0) {
            var tagName = element.prop("tagName"); 
            console.log(tagName);
            if(tagName == 'SELECT'){
                jQuery('input[name="quantity"]').hide();
                jQuery('.quantity').hide();
            } else {
              // jQuery('p[id="options_65c5cc61a211e_field"]').parent().parent().remove();
            }
        }
        jQuery('.product-custom-option').eq(0).trigger("change");
        jQuery('.cart').unblock();

        
    });
    </script>
    <?php
}



function custom_content_before_quantity_field() {
    if(!isset($_SESSION['designo_url'])){
        return;
    }

    global $post;
    $post_id = $post->ID;
    $product = wc_get_product($post_id);
    $sku = $product->get_sku();
    $productId = $product->get_id();

    $url = sanitize_url($_SESSION['designo_url'] . "api/getProductOptions");
    $post_data = array('product' => urlencode($sku), 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']));
    $response = optionprice_api_call($url, $post_data);

    $custom_attributes = array();    
    $customOptionForImage = []; 
    $qtyCustomInput = array();
    if($response){
        
        if(isset($response['use_eCommerce']) && $response['use_eCommerce'] == false){
            usort($response['options'], function ($item1, $item2) {
                if(isset($item1['sort_order']) && isset($item2['sort_order'])){
                    return $item1['sort_order'] <=> $item2['sort_order'];
                }
            });
                ?>
                <script>
                    window.exception = '<?= json_encode($response['exceptions'] ?? ''); ?>';
                </script>
                <?php

                echo '<table class="variations dnb-custom-table-variation">';
                 
                // Iterate through options
                foreach ($response['options'] as $_option) {

                    $optionstitle =  $_option['display_name'] ?? $_option['title'] ?? $_option['webtoprint'];
                    $optionDescription = $_option['description'] ?? '';

                    if($optionstitle == 'quantity' && $_option['type'] == 'input'){

                        $qtyCustomInput = $_option; 
                        continue;
                    }

                    echo '<tr><td>';
                    
                    $custom_attributes = array();
                    if ($_option['type'] == 'attribute') {

                        if(isset($_option['values']['default_value']) && $_option['values']['default_value']!=''){
                            echo '<p class="form-row" id="options_' . $_option['option_id'] . '">
                                    <label>'.$optionstitle.'</label>
                                    <span class="woocommerce-input-wrapper"><label>'.$_option['values']['default_value'].'</label></span>
                                 </p>';
                        }

                    } else if ($_option['type'] == 'hidden_field') {


                        $field_args = array(
                            'type' => 'hidden',
                            'id' => 'options_' . $_option['option_id'] . '',
                            'label' => '', // No label for hidden field
                            'required' => false, // Optional
                            'description' => $optionDescription,
                            'class' => array('hidden-field-class'), // Optional
                            'value' => $_option['values']['default_value'], // Optional, set the default value
                        );
                        woocommerce_form_field('options[' . $_option['option_id'] . ']', $field_args);
                    } elseif ($_option['type'] == 'input' || $_option['type'] == 'text_box' || $_option['type'] == 'text_field') {

                        if($_option['webtoprint'] == 'sizing'){

                            woocommerce_form_field('options['.$_option['option_id'].'-width]', array(
                                'type'          => 'number',
                                'id' => 'options_'.$_option['option_id'].'-width_text',
                                'input_class'         => array('product-custom-option'), 
                                'label'         => __('Width('.$response['unit'].')'),
                                'placeholder'   =>__('Please enter …'),
                                'required'      => $_option['is_require'],
                                'description' => $optionDescription,
                                'validate' => array('email' ), 
                                'custom_attributes' => $custom_attributes,
                            ) );
                            
                            woocommerce_form_field('options['.$_option['option_id'].'-height]', array(
                                'type'          => 'number',
                                'id' => 'options_'.$_option['option_id'].'-height_text',
                                'input_class'         => array('product-custom-option'), // CSS classes for styling
                                'label'         => __('Height('.$response['unit'].')'),
                                'placeholder'   =>__('Please enter …'),
                                'description' => $optionDescription,
                                'required'      => $_option['is_require'],
                                'custom_attributes' => $custom_attributes,
                            ));

                            if(isset($_option['default_length']) && $_option['default_length'] != ''){

                                woocommerce_form_field('options['.$_option['option_id'].'-length]', array(
                                    'type'          => 'number',
                                    'id' => 'options_'.$_option['option_id'].'-length_text',
                                    'input_class'         => array('product-custom-option'), // CSS classes for styling
                                    'label'         => __('Length('.$response['unit'].')'),
                                    'placeholder'   =>__('Please enter …'),
                                    'description' => $optionDescription,
                                    'required'      => $_option['is_require'],
                                    'custom_attributes' => $custom_attributes,
                                ));
                            }

                            ?>
                                <script>
                                    window.onload = function(e){
                                        document.getElementById("options_65c5cc61a212f-width_text").value = "<?= $_option['default_width'] ?? 1; ?>";
                                        document.getElementById("options_65c5cc61a212f-width_text").min = "<?= $_option['min_value'] ?? 1; ?>";
                                        document.getElementById("options_65c5cc61a212f-width_text").max = "<?= $_option['max_value'] ?? 1; ?>";
                                        
                                        document.getElementById("options_65c5cc61a212f-height_text").value = "<?= $_option['default_height'] ?? 1; ?>";
                                        document.getElementById("options_65c5cc61a212f-height_text").min = "<?= $_option['min_value'] ?? 1; ?>";
                                        document.getElementById("options_65c5cc61a212f-height_text").max = "<?= $_option['max_value'] ?? 1; ?>";

                                        if(document.getElementById("options_65c5cc61a212f-length_text")){
                                            document.getElementById("options_65c5cc61a212f-length_text").value = "<?= $_option['default_length'] ?? 1; ?>";
                                            document.getElementById("options_65c5cc61a212f-length_text").min = "<?= $_option['min_value'] ?? 1; ?>";
                                            document.getElementById("options_65c5cc61a212f-length_text").max = "<?= $_option['max_value'] ?? 1; ?>";
                                        }
                                        
                                    }
                                </script>
                            <?php


                        } else {

                            woocommerce_form_field('options['.$_option['option_id'].']', array(
                                'type'          => 'text',
                                'id' => 'options_'.$_option['option_id'].'',
                                'input_class'         => array('product-custom-option'), // CSS classes for styling
                                'label'         => ucfirst($optionstitle),
                                'required'      => $_option['is_require'],
                                'description' => $optionDescription,
                                'custom_attributes' => $custom_attributes,
                            ) );

                        }

                    } else {
                        // Handle other field types as needed
                        // This example assumes text input fields

                        if($_option['type'] == 'checkbox'){
                           
                            $optionType = 'checkbox';
                            $mandate = '';
                            if(isset($_option['is_require']) && $_option['is_require'] == 1){
                                $mandate ='<abbr class="required" title="required">*</abbr>';
                            }
                            echo '<p class="form-row"><label>'.$optionstitle.$mandate.'</label>';
                            foreach ($_option['values'] as $subOptionVal) {
                                $title = ($_option['webtoprint'] == 'sizing') ? "{$subOptionVal['width']}x{$subOptionVal['height']}" : ($subOptionVal['value_name'] ?? $subOptionVal['title'] ?? 'default');
                                $checked = isset($subOptionVal['default']) && $subOptionVal['default'] == 1 ? 'checked' : '';

                                echo ' <label class="checkbox">';
                                echo '<input type="checkbox" class="input-checkbox product-custom-option" name="options[' . $_option['option_id'] . ']" value="' . $subOptionVal['value_id'] . '" ' . $checked . ' />';
                                echo ucfirst($title);
                                echo '</label>';
                            }
                            echo '</p>';



                        } else if($_option['type'] == 'image_dropdown'){

                            //$customOptionForImage[$optionstitle] = $_option;
                            if(!empty($_option['values'])){
                                
                                $custHtml = '<label>' . ucfirst($optionstitle) . '</label><div class="custom-image-dropdown">
                                    <span class="selected-option">-- Please Select --</span>
                                    <span class="arrow">&#x25BC;</span>
                                    <ul class="options-list">';

                                foreach ($_option['values'] as $value) {
                                    $OptionValueName = $value['value_name'] ?? $value['title'] ?? 'default';
                                    $imagePath = isset($value['image']) ? sanitize_url($_SESSION['designo_url']) . 'images/product/product_config/' . $value['image'] : '';

                                    $custHtml .= '<li data-value="' . $value['value_id'] . '"> <img src="' . $imagePath . '" width="50px" style="margin-right: 15px;" /><span>' . $OptionValueName . '</span></li>';
                                }

                                $custHtml .= '</ul></div><select style="display:none" name="options[' . $_option['option_id'] . ']" id="options_' . $_option['option_id'] . '" class="required mageworx-swatch hidden product-custom-option multiselect admin__control-multiselect" title="" data-selector="options[7][]" aria-required="true">';

                                if (empty($_option['is_require'])) {
                                    $custHtml .= '<option value="" selected>-- Please Select --</option>';
                                }

                                foreach ($_option['values'] as $value) {
                                    $OptionValueName = $value['value_name'] ?? $value['title'] ?? 'default';
                                    $selected = isset($value['default']) && $value['default'] == 1 ? 'selected' : '';
                                    $custHtml .= '<option value="' . $value['value_id'] . '" data-option_type_id="' . $value['value_id'] . '" ' . $selected . '>' . $OptionValueName . '</option>';
                                }

                                $custHtml .= '</select>';

                                echo $custHtml;
                            }

                        } else {
                            $optionsValues = array();
                            $setDefaultkey = 0; 
                            if(isset($_option['values']) && !empty($_option['values'])) {
                                usort($_option['values'], function ($item1, $item2) {
                                    if(isset($item1['sort_order']) && isset($item2['sort_order'])){
                                        return $item1['sort_order'] <=> $item2['sort_order'];
                                    }
                                });


                                foreach($_option['values'] as $subOptionVal){

                                    $optionsValues[$subOptionVal['value_id']] = ($_option['webtoprint'] == 'sizing') ? $subOptionVal['width'] .'x'. $subOptionVal['height'] : $subOptionVal['value_name'] ?? $subOptionVal['title'] ?? 'default';

                                    if(isset($subOptionVal['length'])){
                                        $optionsValues[$subOptionVal['value_id']] = $optionsValues[$subOptionVal['value_id']].'x'.$subOptionVal['length'];
                                    }

                                    if(isset($subOptionVal['default']) && $subOptionVal['default']==1) {
                                        $setDefaultkey = $subOptionVal['value_id'];    
                                    }
                                }
                            }

                            if($_option['type'] == 'radio'){
                                $optionType = 'radio';
                                echo '<div class="dnb_custom_radiobtn">';
                            } else {
                                $optionType = 'select';
                            }

                            woocommerce_form_field('options[' . $_option['option_id'] . ']', array(
                                'type' => $optionType,
                                'id' => 'options_' . $_option['option_id'] . '',
                                'input_class' => array('product-custom-option'), // CSS classes for styling
                                'label'         => ucfirst($optionstitle),
                                'options'       => $optionsValues,
                                'default' => $setDefaultkey,
                                'required' => $_option['is_require'],
                                'description' => $optionDescription,
                                'custom_attributes' => $custom_attributes,
                            ));

                            if($_option['type'] == 'radio'){
                                echo '</div>';
                            }

                        }
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                // End table
                echo '</table>';


        }

        if(!empty($qtyCustomInput)){

            woocommerce_quantity_input(
             array(
                 'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $qtyCustomInput['min_value'], $product ),
                 'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $qtyCustomInput['max_value'], $product ),
                 'input_value' => isset( $qtyCustomInput['default'] ) ? wc_stock_amount( wp_unslash( $qtyCustomInput['default'] ) ) : $qtyCustomInput['min_value'], // WPCS: 
             )
            );
        }
    }
}

add_action( 'woocommerce_before_add_to_cart_quantity', 'custom_content_before_quantity_field' );



function optionprice_api_call($url, $postArray, $header = null)
{

    $url = $url;
    $post_data['body'] = wp_json_encode($postArray);
    $post_data['headers'] = array('Content-Type' => 'application/json', 'Content-Length' => strlen($post_data['body']), 'Authorization' => $header);
    $args = array(
        'timeout' => 45,
        'redirection' => 5,
        'httpversion' => '1.0',
        'sslverify' => false,
        'blocking' => true,
        'headers' => $post_data['headers'],
        'body' => $post_data['body'],
        'cookies' => array()
    );

    $response = wp_remote_post($url, $args);
    return $response = wp_parse_args(json_decode($response['body'],true));
}


function cma_get_template( $located, $template_name, $args, $template_path, $default_path ) {    

    global $post;
    $post_id = $post->ID;
    $product = wc_get_product($post_id);
    if($product){
        $sku = $product->get_sku();
        $productId = $product->get_id();

        $url = sanitize_url($_SESSION['designo_url'] . "api/getProductStatus");
        $post_data = array('product' => urlencode($sku), 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']));
        $response = optionprice_api_call($url, $post_data);

        if(isset($response['use_eCommerce']) && $response['use_eCommerce'] == false){
            if ( 'single-product/add-to-cart/simple.php' == $template_name ) {
                $located = plugin_dir_path( __FILE__ ) . 'templates/single-product/add-to-cart/simple.php';
            }
        }
    }
    return $located;
}
add_filter( 'wc_get_template', 'cma_get_template', 10, 5 );