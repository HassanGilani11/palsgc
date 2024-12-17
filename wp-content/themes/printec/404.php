<?php
get_header(); ?>

    <div id="primary" class="content">
        <main id="main" class="site-main">
            <div class="error-404 not-found">
                <div class="page-content">
                    <div class="error-img404">
                        <img src="<?php echo get_theme_file_uri('assets/images/404/404.png') ?>"
                             alt="<?php echo esc_attr__('404 Page', 'printec'); ?>">
                    </div>
                    <div class="error-content">
                        <header class="page-header">
                            <h1 class="title"><span><?php esc_html_e('Opps! ', 'printec'); ?></span><?php esc_html_e(' That Links Is Broken', 'printec'); ?></h1>
                        </header><!-- .page-header -->
                        <div class="error-text">
                            <span><?php esc_html_e('Page does not exist or some other error occured. Go to our Home Page', 'printec') ?></span>
                        </div>
                        <div class="button-error">
                            <a href="javascript: history.go(-1)" class="go-back button btn-theme"><?php esc_html_e('Back to homepage', 'printec'); ?><i aria-hidden="true" class="printec-icon- printec-icon-right-arrow"></i></a>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php

get_footer();
