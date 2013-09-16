<?php
/**
 * The default template for displaying content
 */
global $tags;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<header>
 		<h2 class="entry-title">
 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
 		</h2>

 		<h5 class="byline"><?php largo_byline(); ?></h5>

	</header><!-- / entry header -->

	<div class="entry-content">
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>

		<?php largo_excerpt( $post, 5, true, __('Continue&nbsp;Reading&nbsp;&rarr;', 'largo') ); ?>

	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->