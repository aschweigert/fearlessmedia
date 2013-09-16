<?php
/**
 * The template for the regular features section of the homepage
 */
$terms = get_the_terms( $post->id, 'features' );
if ( !empty( $terms ) ) {
	$features = array();
	$i = 0;
	foreach ( $terms as $term ) {
		$features[$i]['term_name'] = $term->name;
		$features[$i]['term_url'] = get_term_link($term->slug, 'features');
		$i++;
	}
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>

	<div>
		<?php if  ( !empty( $features ) ) : ?>
			<h5 class="top-tag">
					<?php echo '<a href="' . $features[0]['term_url'] . '">' . $features[0]['term_name'] . '</a>'; ?>
			</h5>
		<?php endif; ?>
		<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
		<h4 class="entry-title">
 			<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
 		</h4>
 		<p class="date"><?php largo_time(); ?></p>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->