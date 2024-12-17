<?php
$designo_url = sanitize_url($_SESSION['designo_url']);
$site_url = get_site_url();
global $post;


$template_id = isset($_REQUEST['template_id']) ? $_REQUEST['template_id'] : '';

if(isset($_REQUEST['product_sku'])){
    $_REQUEST['product_id'] = wc_get_product_id_by_sku( $_REQUEST['product_sku'] );
}



$post_id = (isset($_REQUEST['design_id'])) ? sanitize_text_field($_REQUEST['ecom_prod_id']) : sanitize_text_field($_REQUEST['product_id']);
$product = wc_get_product($post_id);
$sku = $product->get_sku();

$options_array = isset($_REQUEST['options']) ? $_REQUEST['options'] : array();


if (isset($_REQUEST['variation_id']) && sanitize_text_field($_REQUEST['variation_id']) != '' && sanitize_text_field($_REQUEST['variation_id']) != 'undefined') {
    $_product = new WC_Product_Variation(sanitize_text_field($_REQUEST['variation_id']));
    $variation_data = $_product->get_variation_attributes();
    $variations = $variation_data;
    $super_attribute = array();
    $options_array = array();
    foreach ($variations as $key => $value) {
        $attribute_name = preg_replace('/attribute_pa_/', '', $key);
        $with_pa_name = preg_replace('/attribute_/', '', $key);
        $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);
        if ($attribute_id != 0) {
            if ($with_pa_name == 'pa_color' || $with_pa_name == 'pa_size') {
                $sizes = get_the_terms($product->get_id(), $with_pa_name);
                $term_id = 0;
                if (isset($sizes) && !empty($sizes)) {
                    foreach ($sizes as $size) {
                        if ($value == $size->slug) {
                            $term_id = $size->term_id;
                            $super_attribute[$attribute_id] = $term_id;
                        }
                    }
                }
            } else {
                $terms = get_terms($with_pa_name);
                if (isset($terms) && !empty($terms)) {
                    foreach ($terms as $term) {
                        if ($value == $term->slug) {
                            $term_name = $term->slug;
                            $options_array[$with_pa_name] = $term_name;
                        }
                    }
                }
            }
        } else {
            $current_products = $product->get_children();
            $attribute_name = preg_replace('/attribute_/', '', $key);
            $array = wc_get_product_term_ids($product->get_id(), $attribute_name);
            $options_array[$attribute_name] = $value;
        }
    }
} else {
    $super_attribute = array();
}

        

if ($sku && $post_id) {
    //get token
    $url = $designo_url . "api/studio/ecomm-token";
    $post_data = array();
    $response = designo_api_call($url, $post_data);

    if(isset($_REQUEST['upload_dpi'])){

        $_REQUEST['images'] = stripslashes($_REQUEST['images']);
        $url = sanitize_url($_SESSION['designo_url'].'api/uploadArtworkData');
        $post_data['sku'] = $sku;
        $post_data['store_id'] = sanitize_text_field($_SERVER['SERVER_NAME']);
        $uploadArtworkData = designo_api_call($url, $post_data, $response['token']);

        $area = array();
        if(isset($uploadArtworkData['success']) && $uploadArtworkData['success'] == true)
        {
            $uploadConfigData = $uploadArtworkData['data'] ?? array();
            if(!empty($uploadConfigData)){
                $area = [
                    $uploadConfigData->upload_configure_x,
                    $uploadConfigData->upload_configure_y,
                    $uploadConfigData->upload_configure_width,
                    $uploadConfigData->upload_configure_height
                ];
            }
        }
        
        $_REQUEST['overlay'] = $uploadConfigData->upload_bg_image ?? sanitize_url($_SESSION['designo_url'].'dnb_upload/images/image.png');
        $_REQUEST['area'] = (!empty($area)) ? implode(",",$area) : "167,82,262,230";
        $_REQUEST['margin'] = 5;
        $_REQUEST['webpath'] = esc_url($site_url);

        $urlParm = http_build_query($_REQUEST);
        $url = $designo_url.'dnb_upload/upload.html?'.$urlParm;

    } else {

        if(isset($_REQUEST['quickedit'])){
            $url = $designo_url . "api/studio/is-product-quickedit";
        } else {
            $url = $designo_url . "api/studio/is-product-customized";
        }

        $qty = isset($_REQUEST['quantity']) ? intval($_REQUEST['quantity']) : 1;
        $post_data = array('SKU' => $sku, 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']), 'id' => $post_id,  'super_attribute' => $super_attribute, 'options' => $options_array, 'qty' => $qty);
        if (isset($_REQUEST['design_id'])) {
            $post_data['design_id'] = sanitize_text_field($_REQUEST['design_id']);
        }
        if (isset($_REQUEST['template_id'])) {
            $post_data['template_id'] = sanitize_text_field($_REQUEST['template_id']);
        }
        $response = designo_api_call($url, $post_data, $response['token']);

        if ($response['success'] == true) {
            $var_id = isset($_REQUEST['variation_id']) ? $_REQUEST['variation_id'] : "";
		    $response['url'] = $response['url']."&variation_id=".$var_id;
            $url = $response['url'];
        }
    }


    if (isset($url)) {
        
?>
        <iframe id="designtool_iframe" name="Design N Buy" src="<?php echo esc_url($url); ?>" frameborder="0" scrolling="yes"></iframe>

        <script>
            debugger;
            var w2pDomain = '<?php echo $designo_url; ?>';
            var baseUrl = '<?php echo $site_url ?>';
            var templateid = '<?php echo $template_id ?>';
            
            window.addEventListener("message", function(event) {
                if (event.origin + '/' !== w2pDomain || !event.data.action) return;
                
                debugger;
                if (event.data.action === "add_to_cart") {

                    var data2 = {
                            action: 'designnbuy_ajax_add_to_cart',
                            actionFrom:'upload',
                            data : event.data.postData
                        };

                        jQuery.ajax({
                            type: 'post',
                            url: '<?php echo esc_url($site_url); ?>/wp-admin/admin-ajax.php',
                            data: data2,
                            success: function (response) {
                                console.log(response);
                                if (response.error && response.product_url) {
                                    window.location = response.product_url;
                                    return;
                                } else {
                                    //$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, $thisbutton]);
                                    if (response.data.success === "true") {
                                        window.location = '<?php echo esc_url($site_url); ?>' + '/checkout/cart/';
                                    } else {
                                        alert('Error into add to cart');
                                    }
                                }
                            },
                        });  
 
                } if (event.data.action === "add_cart") {
                    var _nonce = "<?php echo wp_create_nonce('wc_store_api'); ?>";
                    event.data.cartList.forEach(function(cart_data){

                        cart_data.cartData.action = 'designnbuy_ajax_add_to_cart'; 
                        fetch('<?php echo esc_url($site_url); ?>/wp-admin/admin-ajax.php' , {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-WC-Store-API-Nonce': _nonce
                                },
                                body: ObjectToURLParams(cart_data.cartData)
                            })
                            .then(
                                response => {
                                    if (response.status === 200 || response.status === 302) {
                                        return response.json()
                                    } else {
                                        throw new Error(url + " returned with " + response.status);
                                    }
                                })
                            .then(data => {
                                if (data.errors) {
                                    throw new Error(data.errors[0].message);
                                } else {
                                    return data;
                                }
                            })
                            .catch(error => {
                                throw error;
                            })
                            .then(cart_res => {
                                if (cart_res.data.success === "true") {
                                    console.log('here')
                                    if (cart_data.redirect) window.location = '<?php echo esc_url($site_url); ?>' + '/checkout/cart/';
                                } else {
                                    alert(cart_res.error.message)
                                }
                            })
                    });
                } else if (event.data.action === "login_check") {
                    const nonce = '<?php echo wp_create_nonce('wp_rest'); ?>'
                    fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/logincheck')); ?>' , {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-WP-Nonce': `${nonce}`
                            },
                            body: ObjectToURLParams(event.data.data)
                        })
                        .then(res => res.json())
                        .then(res => {
                            event.source.postMessage({
                                res,
                                action: "login_check"
                            }, event.origin);
                        })
                        .catch(error => {
                            console.log("logincheck error", error)
                        });
                } else if (event.data.action === "login") {
                    fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/loginfromtool')); ?>' , {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: ObjectToURLParams(event.data.data)
                        })
                        .then(res => res.json())
                        .then(res => {
                            event.source.postMessage({
                                res,
                                action: "login"
                            }, event.origin);
                        })
                        .catch(error => {
                            console.log("login from tool error", error)
                        });
                } else if (event.data.action === "logout") {
                    fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/logoutfromtool')); ?>' , {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                        })
                        .then(res => res.json())
                        .then(res => {
                            event.source.postMessage({
                                res,
                                action: "logout"
                            }, event.origin);
                        })
                        .catch(error => {
                            console.log("logout from tool error", error)
                        });
                } else if (event.data.action === "register") {
                    const nonce = '<?php echo wp_create_nonce('wp_rest'); ?>'
                    fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/createfromtool')); ?>' , {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-WP-Nonce': `${nonce}`
                            },
                            body: ObjectToURLParams(event.data.data)
                        })
                        .then(res => res.json())
                        .then(res => {
                            event.source.postMessage({
                                res,
                                action: "register"
                            }, event.origin);
                        })
                        .catch(error => {
                            console.log("register from tool error", error)
                        });
                } else if (event.data.action === "user_details") {
                    fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/customer/details')); ?>' , {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: ObjectToURLParams(event.data.data)
                        })
                        .then(res => res.json())
                        .then(res => {
                            event.source.postMessage({
                                res,
                                action: "user_details"
                            }, event.origin);
                        })
                        .catch(error => {
                            console.log("User details from tool error", error)
                        });
                } else if (event.data.action === "back") {

                    window.location.href = document.referrer;
                    
                    
                } else if (event.data.action === "home") {
                    window.location.href = "/index.php";
                } else if (event.data.action === "mydesign") {
                    window.open(event.data.url, '_blank')
                } else if (event.data.action === "go_back") {
                    let go_back = confirm("You’ll loose all uploaded files. Do you want to go back?")
                    if(go_back) window.top.history.back()
                } else if(event.data.action === "forgot_password") {
                    window.location.href = baseUrl + '/my-account/lost-password/';
                }
            })

            function ObjectToURLParams(obj) {
                debugger;
                const formBody = [];
                for (const key in obj) {
                    let encodedKey, encodedValue;
                    if (Array.isArray(obj[key])) {
                        obj[key].forEach(val => {
                            encodedKey = encodeURIComponent(key + "[]");
                            //encodedValue = encodeURIComponent(val);
                            encodedValue = encodeURIComponent(typeof val == "object"?JSON.stringify(val):val);
                            formBody.push(encodedKey + "=" + encodedValue);
                        })
                    } else {
                        encodedKey = encodeURIComponent(key);
                        encodedValue = encodeURIComponent(obj[key]);
                        formBody.push(encodedKey + "=" + encodedValue);
                    }
                }
                return formBody.join("&");
            }
        </script>

<?php
    }
}
?>
<style>
    body {
        margin: 0px;
        padding: 0px;
    }

    #designtool_iframe {
        width: 100vw;
        height: 100vh;
    }
</style>