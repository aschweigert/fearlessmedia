<?php
/*
 * Largo Recent Posts
 */

class fm_hot_topics_widget extends WP_Widget {

	function fm_hot_topics_widget() {
		$widget_ops = array(
			'classname' => 'fm-hot-topics',
			'description' => __('Show your most recent posts with thumbnails and excerpts', 'largo')
		);
		$this->WP_Widget( 'fm-hot-topics-widget', __('Hot Topics Widget', 'largo'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $ids; // an array of post IDs already on a page so we can avoid duplicating posts

		extract( $args );

		$instance['image_attachment_id'] = apply_filters( 'image_attachment_id', $instance['image_attachment_id'] );
		$term = get_term_by( 'id', $instance['cat'], 'category');
		$title = '<a href="/category/' . $term->slug . '">' . $term->name . '</a>';

		echo $before_widget;

		if ( $title )

			echo $before_title . $title . $after_title; ?>

			<?php

			$query_args = array (
				'showposts' 	=> 1,
				'post_status'	=> 'publish',
				'cat'			=> $instance['cat']
			);
			$my_query = new WP_Query( $query_args );
          		if ( $my_query->have_posts() ) :

          			$output = '';

          			while ( $my_query->have_posts() ) : $my_query->the_post(); $ids[] = get_the_ID(); ?>

          				<div class="post-lead clearfix">
			  				<?php
				  				$output .= '<a href="' . get_permalink() . '">' . wp_get_attachment_image( $instance['image_attachment_id'], 'medium' ) . '</a>';
		          				$output .= '<h4 class="entry-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4>';
		          				echo $output;
	          				?>
	          				<p class="date"><?php largo_time(); ?></p>
						</div>

	               <?php endwhile;
	            else:
	    			printf(__('<p class="error"><strong>You don\'t have any recent %s.</strong></p>', 'largo'), strtolower( $posts_term ) );
	    		endif; // end more featured posts

	    	echo $after_widget;
		wp_reset_postdata();
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['cat'] = $new_instance['cat'];
		$instance['image_uri'] = $new_instance['image_uri'];
		$instance['image_attachment_id'] = $new_instance['image_attachment_id'];
		return $instance;
	}

	function form( $instance ) {
		$defaults = array(
			'cat' 					=> 0,
			'image_uri'				=> 'http://',
			'image_attachment_id' 	=> ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e('Category to use: ', 'fm-hot-topics'); ?>
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => __('None (all categories)', 'fm-hot-topics'), 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>


		<p>
	      <label for="<?php echo $this->get_field_id('image_uri'); ?>">Image</label><br />
	        <img class="custom_media_image" src="<?php if(!empty($instance['image_uri'])){echo $instance['image_uri'];} ?>" style="margin:0;padding:0;max-width:100px;float:left;display:inline-block" />
	        <input type="text" class="widefat custom_media_url" name="<?php echo $this->get_field_name('image_uri'); ?>" id="<?php echo $this->get_field_id('image_uri'); ?>" value="<?php echo $instance['image_uri']; ?>">
	        <input type="hidden" class="custom_media_id" name="<?php echo $this->get_field_name('image_attachment_id'); ?>" id="<?php echo $this->get_field_id('image_attachment_id'); ?>" value="<?php echo $instance['image_attachment_id']; ?>">
	        <input type="button" value="<?php _e('Upload', 'largo'); ?>" class="button custom_media_upload" id="custom_image_uploader" />
	    </p>

	<?php
	}
}