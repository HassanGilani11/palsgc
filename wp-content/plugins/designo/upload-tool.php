<?php

parse_str($_SESSION['cart_query'], $_GET);

$designo_url = sanitize_url($_SESSION['designo_url']);
$site_url = get_site_url();

$product = wc_get_product( $_GET['product_id'] ); 
$qa_url = $designo_url;
$sku = $product->get_sku();

// echo $sku;exit;
$get_data = array('sku' => $sku, 'store_id' => sanitize_text_field($_SERVER['SERVER_NAME']));
$url = $qa_url . "api/uploadArtworkData?" . http_build_query($get_data);


$post_data = array();
$response = designo_api_call($url, $post_data); 


if (isset($response['success']) && $response['success'] == true) {
    $data = $response['data']; 
    $iframeURL = getIframeUrl($data);
 
?> 
<iframe id="designtool_iframe" name="Design N Buy" src="<?php echo esc_url($iframeURL); ?>" frameborder="0" scrolling="auto"></iframe>
<script>
    var w2pDomain = '<?php echo $designo_url; ?>';
    window.addEventListener("message", function(event) {
        // console.log("gm data1", event)
        // console.log(event.origin + '/' + w2pDomain )
        // if (event.origin + '/' !== w2pDomain  ) return;
        // if (event.origin + '/' !== w2pDomain || !event.data.action) return;
		

        if (event.data.action === "add_to_cart") {
            var _nonce = "<?php echo wp_create_nonce('wc_store_api'); ?>"; 

                console.log("cart_data") 
                console.log(event.data.postData) 
                fetch('<?php echo esc_url(get_rest_url(null, 'designo/v1/addtocart2')); ?>' , {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-WC-Store-API-Nonce': _nonce
                        },
                        body: ObjectToURLParams(event.data.postData)
                    })
                    .then(
                        response => { 
                            if (response.status === 200 || response.status === 302) {
                                return response.json()
                                window.location = '<?php echo esc_url($site_url); ?>' + '/checkout/cart/';
                            } else {
                                throw new Error( response.status);
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
                            window.location = '<?php echo esc_url($site_url); ?>' + '/checkout/cart/';
                        } else {
                            alert(cart_res.error.message)
                        }
                    })
             
        }
    })

    function ObjectToURLParams(obj) {
        const formBody = [];
        for (const key in obj) {
            let encodedKey, encodedValue;
            if (Array.isArray(obj[key])) {
                obj[key].forEach(val => {
                    encodedKey = encodeURIComponent(key + "[]");
                    encodedValue = encodeURIComponent(val);
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
 
?>
<style>
     

    #designtool_iframe {
        width: 100%;
        height: 100vh;
    }
</style>