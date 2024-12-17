<?php

function printec_add_custom_content_meta_box($post) {
    $product = wc_get_product($post->ID);
    $content = $product->get_meta('_technical_specs');
    echo '<div class="product_technical_specs">';
    wp_editor(wp_specialchars_decode($content, ENT_QUOTES), '_technical_specs', ['textarea_rows' => 10]);
    echo '</div>';
}

add_action('add_meta_boxes', 'printec_create_product_technical_specs_meta_box');

function printec_create_product_technical_specs_meta_box() {
    add_meta_box(
        'custom_product_meta_box',
        esc_html__('Technical specs (The content will be replaced in the Additional information tab)', 'printec'),
        'printec_add_custom_content_meta_box',
        'product',
        'normal',
        'default'
    );
}

function printec_proccess_technical_specs_meta_box($post_id, $post ) {
    if (isset($_POST['_technical_specs'])) {
        update_post_meta($post_id, '_technical_specs', $_POST['_technical_specs']);
    }
}

add_action('woocommerce_process_product_meta', 'printec_proccess_technical_specs_meta_box', 50, 2);