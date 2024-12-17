<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to printec_single_post_top action
         *
         */
        do_action('printec_single_post_top');

        /**
         * Functions hooked in to printec_single_post action
         * @see printec_post_header         - 10
         * @see printec_post_thumbnail - 20
         * @see printec_post_content         - 30
         */
        do_action('printec_single_post');

        /**
         * Functions hooked in to printec_single_post_bottom action
         *
         * @see printec_post_taxonomy      - 5
         * @see printec_single_author      - 10
         * @see printec_post_nav            - 15
         * @see printec_display_comments    - 20
         */
        do_action('printec_single_post_bottom');
        ?>

    </div>

</article><!-- #post-## -->
