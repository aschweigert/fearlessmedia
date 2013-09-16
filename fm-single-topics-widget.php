<?php
/*
 * Largo Sidebar Featured Posts
 */
class fm_single_related_topics_widget extends WP_Widget {

	function fm_single_related_topics_widget() {
		$widget_ops = array(
			'classname' 	=> 'fm_single_related_topics',
			'description' 	=> __('Show relevant topics/features on single article pages', 'largo')
		);
		$this->WP_Widget( 'fm_single_related_topics', __('Single Post Related Topics Widget', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		$ids = array();

        while ( have_posts() ) : the_post();
			$ids[] = get_the_ID(); //collect post IDs so we can avoid duplicating posts
			$terms = get_the_terms( $post->ID, 'category');
			foreach ( $terms as $term ) {
			 	 //if it's this post is uncategorized OR the post we're looking at is the only post in the category then bail
			 	 if ($term->name == 'Uncategorized' || $term->category_count == 1) continue; ?>

			 	 	<aside class="widget magic-widget-single">
			 	 		<h3 class="widgettitle"><span>More About</span><br /><a href="/category/<?php echo $term->slug; ?>"><?php echo $term->name; ?></a></h3>
			 	 		<?php if ( fm_has_related_topics( $term ) ) { ?>
			 	 			<h5 class="topics-list"><span class="heading">Related Topics: </span><?php fm_get_related_topics_for_category( $term, TRUE ); ?></h5>
			 	 		<?php }
							$args = array(
								'post_status'	=> 'publish',
								'posts_per_page'=> 1,
								'cat'			=> $term->term_id,
								'post__not_in' 	=> $ids,
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
										<div>
											<h5 class="top-tag">Start Here</h5>
											<h4 class="entry-title">
										 		<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
										 	</h4>
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
											<p><?php echo $post->post_excerpt; ?></p>
										 	<p class="more"><a href="<?php the_permalink(); ?>">Continue Reading &raquo;</a></p>
										</div>
									</article>
								<?php endwhile;
							}
							$args = array(
								'post_status'	=> 'publish',
								'posts_per_page'=> 5,
								'cat'			=> $term->term_id,
								'post__not_in' 	=> $ids,
								'features' 		=> 'Blog Post'
							);
							$query = new WP_Query( $args );
							if ( $query->have_posts() ) {
								echo '<h5 class="top-tag">Recently in ' . $term->name . '</h5>';
								while ( $query->have_posts() ) : $query->the_post();

									if ( in_array( get_the_ID(), $ids ) ) {
										continue;
									} else {
										$ids[] = get_the_ID();
									}
								?>
									<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix blog-post'); ?>>
										<div>
											<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium'); ?></a>
											<h4 class="entry-title">
										 		<a href="<?php the_permalink(); ?>" title="Permalink to <?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a>
										 	</h4>
										 	<p class="date"><?php largo_time(); ?></p>
										</div>
									</article>
								<?php
								endwhile;
							}

							$args = array(
								'post_status'	=> 'publish',
								'posts_per_page'=> 5,
								'post__not_in' 	=> $ids,
								'cat'			=> $term->term_id,
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
								echo '<div class="features">';
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
								echo '</div>';
							} ?>
			 	 		</aside>
             <?php }
		endwhile;
	}
}