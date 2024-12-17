<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Printec_Elementor')) :

    /**
     * The Printec Elementor Integration class
     */
    class Printec_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('wp', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/init', array($this, 'add_category'));
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);

            // Elementor Fix Noitice WooCommerce
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'woocommerce_fix_notice'));

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/controls_registered', [$this, 'add_icons']);

            // Add Breakpoints
            add_action('wp_enqueue_scripts', 'printec_elementor_breakpoints', 9999);

            if (!printec_is_elementor_pro_activated()) {
                require trailingslashit(get_template_directory()) . 'inc/elementor/custom-css.php';
                require trailingslashit(get_template_directory()) . 'inc/elementor/sticky-section.php';
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }
            }

//            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('printec-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts["printec"]     = 'system';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'printec');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {
            global $printec_version;
            wp_enqueue_script('printec-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], $printec_version);
        }

        public function add_style_editor() {
            global $printec_version;
            wp_enqueue_style('printec-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], $printec_version);
        }

        public function add_scripts() {
            global $printec_version;
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('printec-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', $printec_version);
            wp_style_add_data('printec-elementor', 'rtl', 'replace');

            // Add Scripts
            wp_register_script('tweenmax', get_theme_file_uri('/assets/js/vendor/TweenMax.min.js'), array('jquery'), '1.11.1');
            wp_register_script('parallaxmouse', get_theme_file_uri('/assets/js/vendor/jquery-parallax.js'), array('jquery'), $printec_version);

            if (printec_elementor_check_type('animated-bg-parallax')) {
                wp_enqueue_script('tweenmax');
                wp_enqueue_script('jquery-panr', get_theme_file_uri('/assets/js/vendor/jquery-panr' . $suffix . '.js'), array('jquery'), '0.0.1');
            }
        }


        public function register_auto_scripts_frontend() {
            global $printec_version;
            wp_register_script('printec-elementor-box-icon', get_theme_file_uri('/assets/js/elementor/box-icon.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-brand', get_theme_file_uri('/assets/js/elementor/brand.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-button-popup', get_theme_file_uri('/assets/js/elementor/button-popup.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-call-to-action', get_theme_file_uri('/assets/js/elementor/call-to-action.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-countdown', get_theme_file_uri('/assets/js/elementor/countdown.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-dokan-store', get_theme_file_uri('/assets/js/elementor/dokan-store.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-image-box', get_theme_file_uri('/assets/js/elementor/image-box.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-image-gallery', get_theme_file_uri('/assets/js/elementor/image-gallery.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-image-hotspots', get_theme_file_uri('/assets/js/elementor/image-hotspots.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-language-switcher', get_theme_file_uri('/assets/js/elementor/language-switcher.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-posts-grid', get_theme_file_uri('/assets/js/elementor/posts-grid.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-price-table', get_theme_file_uri('/assets/js/elementor/price-table.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-product-categories', get_theme_file_uri('/assets/js/elementor/product-categories.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-product-currency', get_theme_file_uri('/assets/js/elementor/product-currency.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-product-tab', get_theme_file_uri('/assets/js/elementor/product-tab.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-products', get_theme_file_uri('/assets/js/elementor/products.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-tabs', get_theme_file_uri('/assets/js/elementor/tabs.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-team-box', get_theme_file_uri('/assets/js/elementor/team-box.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-testimonial', get_theme_file_uri('/assets/js/elementor/testimonial.js'), array('jquery','elementor-frontend'), $printec_version, true);
            wp_register_script('printec-elementor-video', get_theme_file_uri('/assets/js/elementor/video.js'), array('jquery','elementor-frontend'), $printec_version, true);
           
        }

        public function add_category() {
            Elementor\Plugin::instance()->elements_manager->add_category(
                'printec-addons',
                array(
                    'title' => esc_html__('Printec Addons', 'printec'),
                    'icon'  => 'fa fa-plug',
                ),
                1);
        }

        public function add_animations_scroll($animations) {
            $animations['Printec Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            require 'base-carousel-widget.php';
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function woocommerce_fix_notice() {
            if (printec_is_woocommerce_activated()) {
                remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
                remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
            }
        }

        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"printec-icon-account":"account","printec-icon-address":"address","printec-icon-angle-down":"angle-down","printec-icon-angle-left":"angle-left","printec-icon-angle-right":"angle-right","printec-icon-angle-up":"angle-up","printec-icon-arrow-down":"arrow-down","printec-icon-arrow-drop-down-fill":"arrow-drop-down-fill","printec-icon-arrow-left":"arrow-left","printec-icon-arrow-repeat":"arrow-repeat","printec-icon-arrow-right":"arrow-right","printec-icon-arrow-up-right":"arrow-up-right","printec-icon-arrow-up":"arrow-up","printec-icon-calendar":"calendar","printec-icon-calling":"calling","printec-icon-cart":"cart","printec-icon-check-fill":"check-fill","printec-icon-check-square-solid":"check-square-solid","printec-icon-checked":"checked","printec-icon-chevron-double-left":"chevron-double-left","printec-icon-chevron-double-right":"chevron-double-right","printec-icon-clock":"clock","printec-icon-close":"close","printec-icon-compare":"compare","printec-icon-config":"config","printec-icon-delivery":"delivery","printec-icon-eye":"eye","printec-icon-featured":"featured","printec-icon-heart-1":"heart-1","printec-icon-left-arrow":"left-arrow","printec-icon-locator":"locator","printec-icon-long-arrow-left":"long-arrow-left","printec-icon-long-arrow-right":"long-arrow-right","printec-icon-mail":"mail","printec-icon-map-marker-alt":"map-marker-alt","printec-icon-message-square":"message-square","printec-icon-money-back":"money-back","printec-icon-money":"money","printec-icon-one-click":"one-click","printec-icon-opinion":"opinion","printec-icon-pen":"pen","printec-icon-phone":"phone","printec-icon-plane":"plane","printec-icon-play-1":"play-1","printec-icon-play-circle":"play-circle","printec-icon-popular":"popular","printec-icon-prime_paperclip":"prime_paperclip","printec-icon-quote":"quote","printec-icon-return":"return","printec-icon-right-arrow-cicrle":"right-arrow-cicrle","printec-icon-right-arrow":"right-arrow","printec-icon-rocket":"rocket","printec-icon-search2":"search2","printec-icon-secure":"secure","printec-icon-shopping-bag":"shopping-bag","printec-icon-sliders-v":"sliders-v","printec-icon-star-alt":"star-alt","printec-icon-support":"support","printec-icon-up-circle":"up-circle","printec-icon-upload":"upload","printec-icon-360":"360","printec-icon-bars":"bars","printec-icon-cart-empty":"cart-empty","printec-icon-check-square":"check-square","printec-icon-circle":"circle","printec-icon-cloud-download-alt":"cloud-download-alt","printec-icon-comment":"comment","printec-icon-comments":"comments","printec-icon-contact":"contact","printec-icon-credit-card":"credit-card","printec-icon-dot-circle":"dot-circle","printec-icon-edit":"edit","printec-icon-envelope":"envelope","printec-icon-expand-alt":"expand-alt","printec-icon-external-link-alt":"external-link-alt","printec-icon-file-alt":"file-alt","printec-icon-file-archive":"file-archive","printec-icon-filter":"filter","printec-icon-folder-open":"folder-open","printec-icon-folder":"folder","printec-icon-frown":"frown","printec-icon-gift":"gift","printec-icon-grid":"grid","printec-icon-grip-horizontal":"grip-horizontal","printec-icon-heart-fill":"heart-fill","printec-icon-heart":"heart","printec-icon-history":"history","printec-icon-home":"home","printec-icon-info-circle":"info-circle","printec-icon-instagram":"instagram","printec-icon-level-up-alt":"level-up-alt","printec-icon-list":"list","printec-icon-map-marker-check":"map-marker-check","printec-icon-meh":"meh","printec-icon-minus-circle":"minus-circle","printec-icon-minus":"minus","printec-icon-mobile-android-alt":"mobile-android-alt","printec-icon-money-bill":"money-bill","printec-icon-pencil-alt":"pencil-alt","printec-icon-plus-circle":"plus-circle","printec-icon-plus":"plus","printec-icon-random":"random","printec-icon-reply-all":"reply-all","printec-icon-reply":"reply","printec-icon-search-plus":"search-plus","printec-icon-search":"search","printec-icon-shield-check":"shield-check","printec-icon-shopping-basket":"shopping-basket","printec-icon-shopping-cart":"shopping-cart","printec-icon-sign-out-alt":"sign-out-alt","printec-icon-smile":"smile","printec-icon-spinner":"spinner","printec-icon-square":"square","printec-icon-star":"star","printec-icon-sync":"sync","printec-icon-tachometer-alt":"tachometer-alt","printec-icon-thumbtack":"thumbtack","printec-icon-ticket":"ticket","printec-icon-times-circle":"times-circle","printec-icon-times-square":"times-square","printec-icon-times":"times","printec-icon-trophy-alt":"trophy-alt","printec-icon-user":"user","printec-icon-video":"video","printec-icon-wishlist-empty":"wishlist-empty","printec-icon-adobe":"adobe","printec-icon-amazon":"amazon","printec-icon-android":"android","printec-icon-angular":"angular","printec-icon-apper":"apper","printec-icon-apple":"apple","printec-icon-atlassian":"atlassian","printec-icon-behance":"behance","printec-icon-bitbucket":"bitbucket","printec-icon-bitcoin":"bitcoin","printec-icon-bity":"bity","printec-icon-bluetooth":"bluetooth","printec-icon-btc":"btc","printec-icon-centos":"centos","printec-icon-chrome":"chrome","printec-icon-codepen":"codepen","printec-icon-cpanel":"cpanel","printec-icon-discord":"discord","printec-icon-dochub":"dochub","printec-icon-docker":"docker","printec-icon-dribbble":"dribbble","printec-icon-dropbox":"dropbox","printec-icon-drupal":"drupal","printec-icon-ebay":"ebay","printec-icon-facebook-f":"facebook-f","printec-icon-facebook":"facebook","printec-icon-figma":"figma","printec-icon-firefox":"firefox","printec-icon-google-plus":"google-plus","printec-icon-google":"google","printec-icon-grunt":"grunt","printec-icon-gulp":"gulp","printec-icon-html5":"html5","printec-icon-joomla":"joomla","printec-icon-link-brand":"link-brand","printec-icon-linkedin":"linkedin","printec-icon-mailchimp":"mailchimp","printec-icon-opencart":"opencart","printec-icon-paypal":"paypal","printec-icon-pinterest-p":"pinterest-p","printec-icon-reddit":"reddit","printec-icon-skype":"skype","printec-icon-slack":"slack","printec-icon-snapchat":"snapchat","printec-icon-spotify":"spotify","printec-icon-trello":"trello","printec-icon-twitter":"twitter","printec-icon-vimeo":"vimeo","printec-icon-whatsapp":"whatsapp","printec-icon-wordpress":"wordpress","printec-icon-yoast":"yoast","printec-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {
            global $printec_version;
            $tabs['opal-custom'] = [
                'name'          => 'printec-icon',
                'label'         => esc_html__('Printec Icon', 'printec'),
                'prefix'        => 'printec-icon-',
                'displayPrefix' => 'printec-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => $printec_version,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Printec_Elementor();
