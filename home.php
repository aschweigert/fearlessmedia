<?php
/**
 * The homepage template
 */
get_header();

/*
 * Collect post IDs in each loop so we can avoid duplicating posts
 * and get the theme option to determine if this is a two column or three column layout
 */
$ids = array();
?>

<div id="content" class="span9" role="main">

	<div id="content-main" class="span8 stories">
		<h2 class="column-title">The Blog</h2>
		<?php
			$args = array(
				'paged'			=> $paged,
				'post_status'	=> 'publish',
				'posts_per_page'=> 10,
				'post__not_in' 	=> $ids,
				'features' => 'Blog Post'
			);
			if ( of_get_option('num_posts_home') )
				$args['posts_per_page'] = of_get_option('num_posts_home');
			if ( of_get_option('cats_home') )
				$args['cat'] = of_get_option('cats_home');
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();
					//if the post is in the array of post IDs already on this page, skip it
					if ( in_array( get_the_ID(), $ids ) ) {
						continue;
					} else {
						$ids[] = get_the_ID();
						get_template_part( 'content', 'home-blog' );
					}
				endwhile;
				largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'content', 'not-found' );
			}
		?>
	</div>
	<div id="left-rail" class="span4">
		<?php
			if ( is_active_sidebar( 'sidebar-home-top-left' ) ) {
				echo '<div class="sidebar-home-top-left">';
				dynamic_sidebar( 'sidebar-home-top-left' );
				echo '</div>';
			}
		?>
		<h2 class="column-title">Regular Features<span class="more"><a href="/features/">view&nbsp;all&nbsp;&raquo;</a></span></h2>
		<?php
			$args = array(
				'paged'			=> $paged,
				'post_status'	=> 'publish',
				'posts_per_page'=> 10,
				'post__not_in' 	=> $ids,
				'tax_query' => array(
					array(
						'taxonomy' => 'features',
						'field' => 'id',
						'terms' => 44,
						'operator' => "NOT IN"
					)
				)
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();

					if ( in_array( get_the_ID(), $ids ) ) {
						continue;
					} else {
						$ids[] = get_the_ID();
						get_template_part( 'content', 'home-features' );
					}
				endwhile;
				largo_content_nav( 'nav-below' );
			} else {
				get_template_part( 'content', 'not-found' );
			}
		?>
	</div>

</div><!-- #content-->

<aside id="sidebar" class="span3">
	<?php
		if ( is_active_sidebar( 'sidebar-home-top-right' ) ) {
			echo '<div class="sidebar-home-top-right">';
			dynamic_sidebar( 'sidebar-home-top-right' );
			echo '</div>';
		}
	?>
	<h2 class="column-title">Hot Topics<span class="more"><a href="/topics/">view&nbsp;all&nbsp;&raquo;</a></span></h2>
	<?php dynamic_sidebar( 'sidebar-home' ); ?>
</aside>
<?php get_footer(); ?>