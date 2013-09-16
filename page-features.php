<?php
/*
Template Name: Topics/Features Pages
*/
?>

<?php
	get_header();
	the_post();
?>

<div id="content" class="span8" role="main">
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<header class="entry-header">
			<h1 class="entry-title"><?php the_title(); ?></h1>
			<?php edit_post_link(__('Edit This Page', 'largo'), '<h5 class="byline"><span class="edit-link">', '</span></h5>'); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
	<?php
		if ( is_page('Features') ) {
			$terms = get_terms( 'features' ) ;
		} else if ( is_page('Topics') ) {
			$terms = get_terms( 'category' );
		}
		foreach ($terms as $term) {

			//skip the uncategorized category and the blog posts feature
			if ( ($term->taxonomy == 'category' && $term->term_id == 1) || ($term->taxonomy == 'features' && $term->name == 'Blog Post'))
				continue;
			else if ($term->taxonomy == 'category')
				$url = '/category/' . $term->slug;
			else
				$url = '/feature/' . $term->slug;
			?>
			<div class="item">
				<h3><a href="<?php echo $url; ?>"><?php echo $term->name; ?></a></h3>
				<?php if ($term->description) { ?>
					<p class="description"><?php echo $term->description; ?></p>
				<?php } ?>
			</div>
	<?php
		} //foreach
	?>

</div><!--#content -->

<aside id="sidebar" class="span4">
	<div class="widget-area" role="complementary">
		<?php dynamic_sidebar( 'sidebar-main' ); ?>
	</div>
</aside>

<?php get_footer(); ?>