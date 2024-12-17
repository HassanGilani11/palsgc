<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="container header-main">
            <div class="header-left">
                <?php
                printec_site_branding();
                if (printec_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php printec_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php printec_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php printec_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    printec_header_account();
                    if (printec_is_woocommerce_activated()) {
                        printec_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
