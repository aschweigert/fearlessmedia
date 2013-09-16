<?php
/**
 * The template for displaying content in the homepage blog column
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'hnews item' ); ?>>

	<header>
 		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
 		<h5 class="byline"><?php largo_byline(); ?></h5>
	</header><!-- / entry header -->

	<div class="entry-content clearfix">
		<?php fm_homepage_blog_content( $post ); ?>
	</div><!-- .entry-content -->

	<footer class="post-meta bottom-meta">
        <?php if ( largo_has_categories_or_tags() && of_get_option( 'show_tags' ) ): ?>
    		<h5 class="tag-list"><strong><?php _e('Filed under:', 'largo'); ?></strong> <?php largo_categories_and_tags( 8 ); ?></h5>
    	<?php endif; ?>
    	<?php largo_post_social_links(); ?>
	</footer><!-- /.post-meta -->
</article><!-- #post-<?php the_ID(); ?> -->


