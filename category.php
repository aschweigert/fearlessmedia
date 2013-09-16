<?php

//template for the topic pages

	get_header();
	$ids = array();
	$cat_id = get_cat_id( single_cat_title( '', false ) ); //get the id of the category we're viewing
	$term = get_category( $cat_id ); //then get the full term object
	//print_r($term);
?>
<div id="content" class="stories span8" role="main">
	<header class="archive-background category-header span12">
	<?php
		echo '<h1 class="page-title">' . $term->name . '</h1>';

		if ( $term->description )
			echo '<p class="description">' . $term->description . '</p>';

		if ( fm_has_related_topics( $term ) ) ?>
			<h5 class="related-topics"><span class="heading">Related Topics: </span><?php fm_get_related_topics_for_category( $term, TRUE ); ?></h5>

	<?php
		$args = array(
			'post_status'	=> 'publish',
			'posts_per_page'=> 1,
			'cat'			=> $cat_id,
			'features' 		=> 'Primer'
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$post = get_post( get_the_id() );

				if ( in_array( get_the_ID(), $ids ) ) {
					continue;
				} else {
					$ids[] = get_the_ID();
				} ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix primer'); ?>>
					<h5 class="top-tag">Start Here</h5>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
					<h4 class="entry-title"><a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h4>
					<p><?php echo $post->post_excerpt; ?></p>
					<p class="more"><a href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>
				</article>
			<?php endwhile;
		}
	?>
	</header>

	<h3 class="recent-posts">Recent Posts<a class="rss-link" href="<?php echo get_category_feed_link( get_queried_object_id() ); ?>"><i class="icon-rss"></i></a></h3>
		<?php
			$args = array(
				'paged'			=> $paged,
				'post_status'	=> 'publish',
				'cat'			=> $cat_id,
				'post__not_in' 	=> $ids,
				'prominence' => 'Featured in Category'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();

					if ( in_array( get_the_ID(), $ids ) ) {
						continue;
					} else {
						$ids[] = get_the_ID();
					}
					echo '<h5 class="top-tag">Featured</h5>';
					get_template_part( 'content', 'category' );

				endwhile;
				largo_content_nav( 'nav-below' );
			}
		?>
		<?php
			$args = array(
				'paged'			=> $paged,
				'post_status'	=> 'publish',
				'cat'			=> $cat_id,
				'posts_per_page'=> 10,
				'post__not_in' 	=> $ids,
				'features' => 'Blog Post'
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) : $query->the_post();

					if ( in_array( get_the_ID(), $ids ) ) {
						continue;
					} else {
						$ids[] = get_the_ID();
					}
					get_template_part( 'content', 'category' );

				endwhile;
				largo_content_nav( 'nav-below' );
			}
		?>
</div>

	<aside id="sidebar" class="span4">
		<?php
			$args = array(
				'post_status'	=> 'publish',
				'posts_per_page'=> 10,
				'post__not_in' 	=> $ids,
				'cat'			=> $cat_id,
				'tax_query' => array(
					array(
						'taxonomy' => 'features',
						'field' => 'id',
						'terms' => array(44,16),
						'operator' => "NOT IN"
						)
					)
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ) {
					//echo '<div class="features">';
					while ( $query->have_posts() ) : $query->the_post();
						$post = get_post( get_the_id() );
						if ( in_array( get_the_ID(), $ids ) ) {
							continue;
						} else {
							$ids[] = get_the_ID();
						}

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
							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix feature'); ?>>
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
					<?php endwhile;
							//echo '</div>';
				} ?>
	</aside>

<?php get_footer(); ?>