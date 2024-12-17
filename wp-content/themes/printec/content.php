<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner">
        <?php printec_post_thumbnail('post-thumbnail', false); ?>
        <div class="post-content">
            <?php
            /**
             * Functions hooked in to printec_loop_post action.
             *
             * @see printec_post_header          - 15
             * @see printec_post_content         - 30
             */
            do_action('printec_loop_post');
            ?>
        </div>
    </div>
</article><!-- #post-## -->