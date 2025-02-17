<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package printec
 */

do_action('printec_loop_before');

$blog_style  = printec_get_theme_option('blog_style');
$columns     = printec_get_theme_option('blog_columns');

$check_style = $blog_style && $blog_style !== 'standard';



if ($check_style) {
    if ($blog_style == 'list') {
        echo '<div class="blog-style-list">';
    } else {
        echo '<div class="blog-style-grid row" data-elementor-columns="' . esc_attr($columns) . '" data-elementor-columns-tablet="2" data-elementor-columns-mobile="1">';
    }
}


while (have_posts()) :
    the_post();

    /**
     * Include the Post-Format-specific template for the content.
     * If you want to override this in a child theme, then include a file
     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
     */
    if ($check_style) {
        get_template_part('template-parts/posts-grid/item-post-'.$blog_style);
    } else {
        get_template_part('content', get_post_format());
    }

endwhile;

if ($check_style) {
    echo '</div>';
}


/**
 * Functions hooked in to printec_loop_after action
 *
 * @see printec_paging_nav - 10
 */
do_action('printec_loop_after');
