<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to printec_page action
	 *
	 * @see printec_page_header          - 10
	 * @see printec_page_content         - 20
	 *
	 */
	do_action( 'printec_page' );
	?>
</article><!-- #post-## -->
