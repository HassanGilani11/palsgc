<?php

// logincheck from tool
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/logincheck', array(
        'methods' => 'POST',
        'callback' => 'designo_login_check',
        'permission_callback' => function () {
            return true;
        },
    ));
});

// login from tool
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/loginfromtool', array(
        'methods' => 'POST',
        'callback' => 'designo_login_from_tool',
        'permission_callback' => function () {
            return true;
        },
    ));
});

// logout from tool
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/logoutfromtool', array(
        'methods' => 'POST',
        'callback' => 'designo_logout_from_tool',
        'permission_callback' => function () {
            return true;
        },
    ));
});

//getproduct 
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/getproduct', array(
        'methods' => 'POST',
        'callback' => 'designo_getproduct_from_data',
        'permission_callback' => function () {
            return true;
        },
    ));
});

/* Get Product Options */
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/getproductoptions', array(
        'methods' => 'POST',
        'callback' => 'designo_get_product_variations',
        'permission_callback' => function () {
            return true;
        },
    ));
});

/* Get Product Price */
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/getproductprice', array(
        'methods' => 'POST',
        'callback' => 'designo_get_product_price',
        'permission_callback' => function () {
            return true;
        },
    ));
});

// createfromtool from tool
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/createfromtool', array(
        'methods' => 'POST',
        'callback' => 'designo_create_from_tool',
        'permission_callback' => function () {
            return true;
        },
    ));
});

// addtocart from tool
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/addtocart', array(
        'methods' => 'POST',
        'callback' => 'designo_add_to_cart1',
        'permission_callback' => function () {
            return true;
        },
    ));
});


// get login user details
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/customer/details', array(
        'methods' => 'POST',
        'callback' => 'designo_user_details',
        'permission_callback' => function () {
            return true;
        },
    ));
});

// Fetch Orders
add_action('rest_api_init', function () {
    register_rest_route('designo/v1', '/fetchorders', array(
        'methods' => 'POST',
        'callback' => 'designo_fetchorders_from_orders',
        'permission_callback' => function () {
            return true;
        },
    ));
});

function designo_fetchorders_from_orders(WP_REST_Request $request)
{
    $parm_order_id = $request->get_param('order_id');
    $parm_created_from = $request->get_param('created_from');
    $parm_created_to = $request->get_param('created_to');
    $flag = true;
    
    if (class_exists('WooCommerce')) {       
        // Add filters based on the provided parameters
        if ($parm_order_id != null) {
            $flag = false;
            $order = wc_get_order($parm_order_id);          
            if ($order) {
                // Get order details
                $order_details['order_number'] = $order->get_order_number();
                $order_details['order_status'] = $order->get_status();
                $order_details['order_date'] = $order->get_date_created()->format('Y-m-d H:i:s');
                $order_details['customer_id'] = $order->get_customer_id();
                $order_details['billing_address'] = $order->get_formatted_billing_address();
                $order_details['shipping_address'] = $order->get_formatted_shipping_address();
                
                // Get order items
                $order_items = $order->get_items();
                $order_details['order_items'] = array();
                foreach ($order_items as $item_id => $item) {
                    $product_name = $item->get_name();
                    $product_quantity = $item->get_quantity();
                    $product_price = $item->get_total();
                    $order_details['order_items'][] = array(
                        'product_name' => $product_name,
                        'quantity' => $product_quantity,
                        'price' => $product_price
                    );
                }
            } else {
                $order_details['error'] = 'Order not found.';
            }
        
            // Return the order details array
            // return $order_details;
            if($order){
                $orders = $order->get_id() ? [$order] : null;
            }else{
                return [];
            }

        }

        if ($parm_created_from != null && $parm_created_to != null) {
            $flag = false;
            $start_date = $parm_created_from;
            $end_date = $parm_created_to;
            $order_statuses = array('Completed');
            $args = array(
                'status' => $order_statuses,
                'date_query' => array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true, // Include orders on the start and end date
                ),
                'posts_per_page' => -1,

            );
            $orders = wc_get_orders($args);
        }

        if($flag){
            $order_statuses = array('Completed');
            $args = array(
                'status' => $order_statuses,
            );
            $orders = wc_get_orders($args);
            // $args = array('processing');
            // $orders = wc_get_orders($args);
        }           
                    
        // Prepare data
        $data = array();
        $orders_data = array();

        $orders_Ids = array();
        foreach ($orders as $order) {
            // foreach ($order->get_items() as $item_id => $item) {
            //     $orders_Ids[] = $item->get_name();
            // }    
            // $orders_Ids[] = $item->get_id();
        }
        // return $orders_Ids;
        try{         
            // Iterate over each order
            if (!empty($orders)) {
                foreach ($orders as $order) {

                    $order_data = array();
                    // Get customer details
                    $customer_details = array(
                        'ecom_cust_id' => $order->get_user_id(),
                        'name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                        'email' => $order->get_billing_email(),
                        'phone' => $order->get_billing_phone()
                    );

                    // Get address details
                    $address_details = array(
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
                    );

                    foreach ($address_details as $key => $value) {
                        if ($value === '') {
                            $address_details[$key] = null;
                        }
                    }

                    // Get order details
                    $order_details = array(
                        'order_id' => (string) $order->get_id(),
                        'order_status' => $order->get_status(),
                        'order_date' => $order->get_date_created() !== null ? $order->get_date_created()->format('Y-m-d H:i:s') : '',
                        'store_name' => 'WooCommerce',
                        'store_code' => sanitize_text_field($_SERVER['SERVER_NAME']),
                        'payment_mode' => $order->get_payment_method_title(),
                        'payment_status' => $order->get_status(),
                        'subtotal' => $order->get_total(),
                        'shipping_amount' => $order->get_shipping_total(),
                        'discount_amount' => $order->get_discount_total(),
                        'grand_total' => $order->get_total(),
                        'status' => '1',
                    );

                    // Initialize array for order items
                    $order_items = array();

                    // Get order items
                    foreach ($order->get_items() as $item_id => $item) {
                        // Get product
                        $product = $item->get_product();
                        
                        $product_image_id = $product->get_image_id(); // Get the attachment ID of the product image

                        // Get the image name based on the attachment ID
                        $image_name = '';
                        if ($product_image_id) {
                            $image_name = basename(get_attached_file($product_image_id)); // Get the image file name
                        }           
                        
                        // Item details
                        $item_details = array(
                            'name' => $item->get_name(),
                            'SKU' => $product->get_sku(),
                            // 'thumb_image' => wp_get_attachment_url($product->get_image_id()),
                            'thumb_image' => sanitize_url($_SESSION['designo_url'] . 'images/cart/' .$item->get_meta('new_png')),
                            // 'thumb_image' => sanitize_url($_SESSION['designo_url'] . 'images/cart/' . $image_name),
                            'qty' => $item->get_quantity(),
                            'price' => $product->get_price(),
                            'subtotal' => $item->get_subtotal(),
                            'tax' => $product->get_tax_class(),
                            'tax_amount' => $order->get_total_tax(),
                            'discount' => $order->get_total_discount(),
                            'total_amount' => $item->get_subtotal(),
                            'info_buyRequest' => wp_unslash($item->get_meta('info_buyRequest'))                        
                        );
                        
                        $item_details['info_buyRequest'] = preg_replace('~^[\'"]?(.*?)[\'"]?$~', '$1', $item_details['info_buyRequest']); // either ' or " whichever is found
                        $requestData = json_decode($item_details['info_buyRequest'],true);                    
                        if(!isset($requestData['product'])){
                            $requestData['product'] = $requestData['product_id'];
                        }
                        $item_details['info_buyRequest'] = json_encode($requestData);
                        if($item->get_meta('info_buyRequest') == ''){
                            $item_details['custom_options'] = json_encode($item->get_meta_data());
                        }
                        // Add item details to order items array
                        $order_items[] = $item_details;
                    }
                    // Add all order details to order data array
                    $order_data['customer_details'] = $customer_details;
                    $order_data['address'] = $address_details;
                    $order_data['order_details'] = $order_details;
                    $order_data['order_items'] = $order_items;

                    // Add order data to orders data array
                    $orders_data[] = $order_data;
                }
            }    
        }catch(Exception $e){
                    
        } 
        // Return orders data
        return $orders_data;

        // Return orders as JSON
        // wp_send_json($orders);
    } else {
        // WooCommerce is not active, return an error message
        wp_send_json_error('WooCommerce is not active.');
    }
}

function designo_user_details(WP_REST_Request $request)
{
    $user = get_user_by('id', sanitize_text_field($_SESSION['user_id']));
    //get user meta
    $user_meta = get_user_meta(sanitize_text_field($_SESSION['user_id']));
    //print_r($user); die;
    if ($user && $user->ID != 0) {
        $response = [
            "error" => "false",
            "success" => "true",
            "id" => $user->ID,
            "data" => [
                "email" => $user->user_email,
                "prefix" => NULL,
                "suffix" => NULL,
                "dob" => "",
                "firstname" => $user_meta['first_name'][0],
                "middlename" => "",
                "lastname" => $user_meta['last_name'][0],
                "company" => "",
                "street" => "",
                "city" => "",
                "region" => "",
                "country" => "",
                "postcode" => "",
                "telephone" => "",
                "vat" => "",
                "profile_image" => "",
                "corporate_logo" => "",
            ]
        ];
    } else {
        $response = array('error' => ['message' => 'user not logged in.'], 'data' => ['success' => "false"]);
    }
    return $response;
}


function designo_login_check(WP_REST_Request $request)
{
    $user = wp_get_current_user();
    if ($user && $user->ID != 0) {
        $response = [
            "error" => [
                "message" => "",
            ],
            "data" => [
                "success" => "true",
                "data" => [
                    "id" => $user->ID,
                    "user_name" => $user->user_login
                ]
            ]
        ];
    } else {
        $response = array('error' => ['message' => 'user not logged in.'], 'data' => ['success' => "false"]);
    }
    return $response;
}

function designo_login_from_tool($request)
{
    $username = $request->get_param('email_id');
    $password = $request->get_param('password');
    $form_key = $request->get_param('form_key');
    $user = wp_signon(array('user_login' => $username, 'user_password' => $password));
    if (is_wp_error($user)) {
        $response = array('error' => ['message' => 'Invalid login or password.'], 'data' => ['success' => "false"]);
        return $response;
    }
    $successArr = [
        "error" => [
            "message" => "",
        ],
        "data" => [
            "success" => "true",
            "data" => [
                'id' => $user->ID,
                'user_name' => $user->user_login,
                'form_key' => $form_key,
            ]
        ]
    ];
    $_SESSION['user_id'] = $user->ID;
    $response = $successArr;
    return $response;
}

function designo_logout_from_tool($request)
{
    $user = wp_logout();
    $successArr = [
        "data" => [
            "success" => "true",
        ],
        "error" => [
            "message" => "logout successfully",
        ]
    ];
    /* } else {
        $successArr = [
            "data" => [
                "success" => "false",
            ],
            "error" => [
                "message" => "Issue in Logging Out",
            ]
        ];
    } */
    $response = $successArr;
    return $response;
}

function designo_getproduct_from_data(WP_REST_REQUEST $req)
{
    $sku = $req->get_param('sku');
    $product = new WC_Product(wc_get_product_id_by_sku($sku));

    $itemArray = [];
    if (wc_get_product_id_by_sku($sku) > 0) {
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
            $response['var'] = $attributes;
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
    $res = new WP_REST_Response($response);
    $res->set_status(200);

    return ['data' => $response];
}

function designo_get_product_variations(WP_REST_REQUEST $request)
{
    $productid = $request->get_param('id');
    $handle = new WC_Product_Variable($productid);
    $product_attributes = $handle->get_attributes();

    // echo "<pre/>";
    // print_r($product_attributes);
    // die;
    $response_array = array();
    $index = 1;
    foreach ($product_attributes as $key => $val) {
        if ($key != 'pa_color' && $key != 'pa_size') {
            $isInt = 0;
            foreach ($val["options"] as $key1 => $value) {
                if (is_numeric($value)) {
                    $isInt++;
                }
            }
            $option_array = array();
            if ($isInt == 0) {
                $option_array["id"] = $key;
                $option_array["type"] = "drop_down";
                $option_array["title"] = $val['name'];
                $option_array["is_require"] = "1";
                $option_array["disabled"] = false;
                $option_array["product_attributes"] = $product_attributes;
                foreach ($val["options"] as $optkey => $optval) {
                    if (!is_int($optval)) {
                        $option_name = $optval;
                        $option_id = $key;
                        $option_type_id = $optval;
                        $default = get_term_meta($optval, 'default', true);

                        $inner_option = array();
                        $inner_option["option_type_id"] = strval($option_type_id);
                        $inner_option["option_id"] = $option_id;
                        $inner_option["default_title"] = $option_name;
                        $inner_option["default"] = false;
                        $option_array["values"][] = $inner_option;
                    }
                }
                $index++;
                $response_array[] = $option_array;
            } else {
                $product = get_product($productid);
                if ($product->is_type('variable')) {
                    $attribute_name = preg_replace('/attribute_pa_/', '', $key);
                    $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);
                    $option_array["id"] = strval($attribute_name);
                    $option_array["type"] = "drop_down";
                    $option_array["title"] = $val['name'];
                    $option_array["is_require"] = "1";
                    $option_array["disabled"] = false;
                    $terms = get_terms($key);
                    if (!empty($terms)) {
                        foreach ($terms as $term) {
                            $option_name = $term->name;
                            $option_id = preg_replace('/pa_/', '', $key);
                            $option_type_id = $term->slug;
                            $inner_option = array();
                            $inner_option["option_type_id"] = strval($option_type_id);
                            $inner_option["option_id"] = strval($attribute_name);
                            $inner_option["default_title"] = $option_name;
                            $inner_option["default"] = false;
                            $option_array["values"][] = $inner_option;
                        }
                    }
                    $response_array[] = $option_array;
                }
            }
        }
    }
    $qtyData = array(
        "id" => 'quantityBox',
        "type" => 'text',
        "title" => __('Quantity'),
        "label" => 'QuantityBox',
        "is_require" => '',
        "sort_order" => '',
        "value" => 1,
    );
    array_push($response_array, $qtyData);

    $response['dnbProductOptions']['options'] = $response_array;
    $res_set = new WP_REST_Response($response);
    $res_set->set_status(200);

    return ['data' => $response];
}

function designo_get_product_price(WP_REST_REQUEST $requestprice)
{
    $params = $requestprice->get_param('params');
    $store = $requestprice->get_param('store');
    $paramsArr = json_decode(urldecode($params));
    $prod_id = $paramsArr->prod_id;
    $quantity = $paramsArr->qty;
    $options = $paramsArr->options;

    $variation_id = 0;
    if (isset($options) && !empty($options)) {
        $newOptArr = array();
        foreach ($options as $key => $value) {
            $newOptArr['attribute_' . $key] = $value;
        }
        $variation_id = find_matching_product_variation_id($prod_id, $newOptArr);
        if ($variation_id == 0) {
            foreach ($options as $key => $value) {
                $newOptArr['attribute_pa_' . $key] = $value;
            }
            $variation_id = find_matching_product_variation_id($prod_id, $newOptArr);
        }
    }

    $price = 0;
    if ($variation_id > 0) {
        $variable_product = wc_get_product($variation_id);
        $price = $variable_product->get_price();
    } else {
        $product = wc_get_product($prod_id);
        $price = $product->get_price();
    }

    $response_arr = array();
    $final_price = $price;
    $response_arr["price"] = $final_price;
    $response_arr["newOptArr"] = $newOptArr;

    $res_set = new WP_REST_Response($response_arr);
    $res_set->set_status(200);

    return ['success' => $res_set];
}

function designo_create_from_tool($request)
{
    // check validation for register user
    $username = sanitize_text_field($request['firstname']);
    $email = sanitize_text_field($request['email']);
    $password = sanitize_text_field($request['password']);

    $error = new WP_Error();
    if (empty($username)) {
        $error->add(400, __("Username field 'username' is required.", 'wp-rest-user'), array('status' => 400));
        $response = [
            "error" => array("message" => $error . ">click here</a> to get your password and access your account."),
            "data" => array('success' => "false", "data" => array("form_key" => $request['form_key'])),
        ];
    }
    if (empty($email)) {
        $error->add(401, __("Email field 'email' is required.", 'wp-rest-user'), array('status' => 400));
        $response = [
            "error" => array("message" => $error . ">click here</a> to get your password and access your account."),
            "data" => array('success' => "false", "data" => array("form_key" => $request['form_key'])),
        ];
    }
    if (empty($password)) {
        $error->add(404, __("Password field 'password' is required.", 'wp-rest-user'), array('status' => 400));
        $response = [
            "error" => array("message" => $error . ">click here</a> to get your password and access your account."),
            "data" => array('success' => "false", "data" => array("form_key" => $request['form_key'])),
        ];
    }

    $user_id = username_exists($username);
    if (!$user_id && email_exists($email) == false) {
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            wp_signon(array('user_login' => $username, 'user_password' => $password));
            $user = get_user_by('id', $user_id);
            $user->set_role('subscriber');
            if (class_exists('WooCommerce')) {
                $user->set_role('customer');
            }


            $response = [
                "error" => array("message" => ""),
                "data" => array(
                            'success' => "true", 
                            'message' => "Thank you for registering with Main Website Store.", 
                            "data" => array("form_key" => $request['form_key'], "id" => $user_id, "user_name" => $username)
                          ),
            ];
        } else {
            return $user_id;
        }
    } else {
        $response = [
            "error" => array("message" => "There is already an account with this email address. If you are sure that it is your email address, <a href=\"https://wordpress.designo.software/my-account/lost-password/\" target='_blank'>click here</a> to get your password and access your account."),
            "data" => array('success' => "false", "data" => array("form_key" => $request['form_key'])),
        ];
    }
    return new WP_REST_Response($response, 123);
}

function designo_add_to_cart1($request)
{
    global $product;
    defined('WC_ABSPATH') || exit;

    // Load cart functions which are loaded only on the front-end.
    include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    include_once WC_ABSPATH . 'includes/class-wc-cart.php';

    if (is_null(WC()->cart)) {
        wc_load_cart();
    }

    $product_id = $request['product'];
    $quantity = $request['qty'];
    $variation_id = 0;
    $variations = array();
    $cart_item_data = array();
     

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
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

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', false, $product_id, $quantity, $variation_id, $variations, $cart_item_data);

    // if (!isset($_SESSION['cart_count'])) {
    //     $_SESSION['cart_count'] = $_GET['cart_count'];
    // } else {
    //     $_SESSION['cart_count'] = $_SESSION['cart_count'] + 1;
    // }
    // $cart_count = $_SESSION['cart_count'];
    // echo 'cart count :: ' . $variation_id . ' - ' . sizeof(WC()->cart->get_cart()) . ' = ' . $cart_count;
    // //add to cart
    // if ($cart_count > 0) {
    //     WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
    //     do_action('woocommerce_ajax_added_to_cart', $product_id);
    //     return array(
    //         'data' => array('success' => "true"),
    //         'error' => array('message' => "You added test canvas to your shopping cart1.")
    //     );
    // }

    //add to cart
    if (sizeof(WC()->cart->get_cart()) > 0) {
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        return array(
            'data' => array('success' => "true"),
            'error' => array('message' => "You added test canvas to your shopping cart.")
        );
    } else {
        if ($variation_id != 0) {
            WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
            $product_item_key = WC()->session->get('product_item_key');
            do_action('woocommerce_ajax_added_to_cart', $product_id);
            return array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
        } elseif ($variation_id == 0) {
            WC()->cart->add_to_cart($product_id, $quantity, '', '', $cart_item_data);
            $product_item_key = WC()->session->get('product_item_key');
            return array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
        } else {
            return array(
                'data' => array('success' => "false"),
                'error' => array('message' => "Product is not available for purchase.")
            );
        }
    }
}
//add by gm


function designo_add_to_cart2($request)
{
    global $product;
    defined('WC_ABSPATH') || exit; 

    
    // Load cart functions which are loaded only on the front-end.
    include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
    include_once WC_ABSPATH . 'includes/class-wc-cart.php';

    if (is_null(WC()->cart)) {
        wc_load_cart();
    }
 

    $product_id = $_REQUEST['product_id'];
    $product = wc_get_product($product_id);
    $quantity = $_REQUEST['quantity'];
    $variation_id = 0;
    $variations = array();
    $cart_item_data = array();
     

    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($product_id));
    $product_status = wc_get_product($product_id)->get_status();
    if ($product_status == 'draft' || $product_status == 'pending' || $product_status == 'auto-draft') {
        return new WP_Error('product_invalid', __('Product is not available for purchase.', 'woocommerce'), array('status' => 400));
    } 
 
 
    $variation_id = $_POST['variation_id'] ?? 0;

    if ($variation_id != 0) {
        $product = wc_get_product($product_id);
        $_product = new WC_Product_Variation($variation_id);
        $variation_data = $_product->get_variation_attributes();
        $variations = $variation_data;
    }


    $cart_item_data['cart_design_id'] = $request['current_time'];
    $cart_item_data['nodewebpath'] = $_POST['nodewebpath'] ?? ''; 
    $cart_item_data['uploadedPath'] = $_POST['uploadedPath'] ?? ''; 
    $cart_item_data['file'] = $_POST['file'] ?? ''; 
    $cart_item_data['store_domain'] = $_POST['store_domain'] ?? ''; 
    $cart_item_data['w2p_domain'] = $_POST['w2p_domain'] ?? ''; 
    $cart_item_data['overlay'] = $_POST['overlay'] ?? ''; 
    $cart_item_data['area'] = json_encode($_POST['area']);   
    $cart_item_data['margin'] = $_POST['margin'] ?? '';   
    $cart_item_data['webpath'] = $_POST['webpath'] ?? '';   
    $cart_item_data['multiplier'] = $_POST['multiplier'] ?? '';   
    $cart_item_data['imageCode'] = $_POST['imageCode'] ?? '';   
    $cart_item_data['comment'] = $_POST['comment'] ?? '';  
    $cart_item_data['images'] = json_encode($_POST['images']) ?? '[]';  
    $req = $_REQUEST;
    $req['product'] = $_REQUEST['product_id'];
    $req['qty'] = $_REQUEST['quantity'];
    
    $cart_item_data['info_buyRequest'] = map_deep( wp_unslash( $req ), 'sanitize_text_field' );

 

 
    $cart_item_data['fix_price'] = $request['fix_price'] ?? 0;
    $cart_item_data['variable_price'] = $request['variable_price'] ?? 0; 
 

    // $cart_item_data['new_png'] = 'https://www.shutterstock.com/image-photo/surreal-image-african-elephant-wearing-260nw-1365289022.jpg';

    //$cart_item_data['info_buyRequest'] = NULL;

    // $cart_item_data['info_buyRequest'] = map_deep( wp_unslash( $_REQUEST ), 'sanitize_text_field' );

    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', false, $product_id, $quantity, $variation_id, $variations, $cart_item_data); 
 
 

    //add to cart
    if (sizeof(WC()->cart->get_cart()) > 0) {
        WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        return array(
            'data' => array('success' => "true"),
            'error' => array('message' => "You added test canvas to your shopping cart.")
        );
    } else {
        if ($variation_id != 0) {
            WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variations, $cart_item_data);
            $product_item_key = WC()->session->get('product_item_key');
            do_action('woocommerce_ajax_added_to_cart', $product_id);
            return array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
        } elseif ($variation_id == 0) {
            WC()->cart->add_to_cart($product_id, $quantity, '', '', $cart_item_data);
            $product_item_key = WC()->session->get('product_item_key');
            return array(
                'data' => array('success' => "true"),
                'error' => array('message' => "You added test canvas to your shopping cart.")
            );
        } else {
            return array(
                'data' => array('success' => "false"),
                'error' => array('message' => "Product is not available for purchase.")
            );
        }
    } 

}

function find_matching_product_variation_id($product_id, $attributes)
{
    return (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(
        new \WC_Product($product_id),
        wp_unslash($attributes)
    );
}
