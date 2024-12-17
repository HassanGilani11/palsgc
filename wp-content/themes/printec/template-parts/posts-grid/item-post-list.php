<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <?php printec_post_thumbnail('post-thumbnail', true); ?>
    <div class="post-content">
        <div class="entry-header">
            <?php
            $categories_list = get_the_category_list('<span class="dot"></span>');
            if ($categories_list) {
                echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'printec') . '</span>' . $categories_list . '</div>';
            }
            ?>
            <?php the_title('<h3 class="sigma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
        </div>
        <div class="entry-content">
            <?php
            the_excerpt();
            echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Read more', 'printec') . '<i class="printec-icon-right-arrow"></i></a></div>';
            ?>
        </div>

    </div>
</article><!-- #post-## -->