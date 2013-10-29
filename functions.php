<?php
function fm_typekit() { ?>
	<script type="text/javascript" src="//use.typekit.net/eyw1gea.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
<?php
}
add_action( 'wp_head', 'fm_typekit' );

function fm_taxonomies() {
	if ( ! taxonomy_exists( 'features' ) ) {
        register_taxonomy( 'features', 'post', array(
            'hierarchical' 	=> true,
            'label' 		=> __('Regular Features', 'largo'),
            'query_var' 	=> true,
            'rewrite' 		=> true,
        ) );
	}
}
add_action( 'init', 'fm_taxonomies' );

require_once( get_stylesheet_directory() . '/fm-hot-topics-widget.php' );
require_once( get_stylesheet_directory() . '/fm-single-topics-widget.php' );
function fm_widgets() {
	register_widget( 'fm_hot_topics_widget' );
	register_widget( 'fm_single_related_topics_widget' );
}
add_action( 'widgets_init', 'fm_widgets' );

function fm_sidebars() {
	register_sidebar(
	  array(
		'name' => 'Homepage Hot Topics',
		'id' => 'sidebar-home',
		'description' => 'The right column on the homepage',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>'
	  )
	);
	register_sidebar(
	  array(
		'name' => 'Homepage Left Column - Top',
		'id' => 'sidebar-home-top-left',
		'description' => 'Optional widget area above the Regular Features area on the homepage.',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>'
	  )
	);
	register_sidebar(
	  array(
		'name' => 'Homepage Right Column - Top',
		'id' => 'sidebar-home-top-right',
		'description' => 'Optional widget area above the Hot Topics area on the homepage.',
		'before_widget' => '<aside id="%1$s" class="%2$s clearfix">',
		'after_widget' 	=> "</aside>",
		'before_title' 	=> '<h3 class="widgettitle">',
		'after_title' 	=> '</h3>'
	  )
	);
	unregister_sidebar( 'homepage-left-rail' );
}
add_action( 'widgets_init', 'fm_sidebars', 11 );

function fm_homepage_blog_content( $post ) {
	if ( strpos( $post->post_content, '<!--more-->' ) ) {
		$content = get_the_content('<p class="more">Continue Reading &raquo;</p>');
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		echo $content;
	} else {
		largo_entry_content( $post );
	}
}

function fm_enqueue() {
	if ( is_home() )
		wp_enqueue_script( 'sharethis', get_template_directory_uri() . '/js/st_buttons.js', array( 'jquery' ), '1.0', true );
	if ( !is_admin() )
		wp_enqueue_script( 'fm-tools', get_stylesheet_directory_uri() . '/js/fm.js', array('jquery'), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'fm_enqueue' );

function enqueue_fm_admin_scripts(){
	wp_enqueue_media();
	wp_enqueue_script('fm-admin-scripts', get_stylesheet_directory_uri() . '/js/fm-admin.js');
}
add_action('admin_enqueue_scripts', 'enqueue_fm_admin_scripts');

function fm_get_related_topics_for_category( $obj, $echo = true ) {
	    $cat_id = $obj->cat_ID;

	    // spit out the subcategories
	    $cats = _subcategories_for_category( $cat_id );

		$output = array();
	    foreach ( $cats as $c ) {
	        $output[] = sprintf( '<a href="%s">%s</a>',
	            get_category_link( $c->term_id ), $c->name
	        );
	    }

	    if ( count( $cats ) < 5 ) {
	        $tags = _tags_associated_with_category( $cat_id,
	            5 - count( $cats ) );

	        foreach ( $tags as $t ) {
	            $output[] = sprintf( '<a href="%s">%s</a>',
	                get_tag_link( $t->term_id ), $t->name
	            );
	        }
	    }
		if ( $echo )
	    	echo implode( ', ', array_slice( $output, 0, 8 ) );
	    return $output;
}
function fm_has_related_topics( $obj ) {
	$cat_id = $obj->cat_ID;
	$cats = _subcategories_for_category( $cat_id );
	$tags = _tags_associated_with_category( $cat_id );
	if ( $cats || $tags ) {
		return TRUE;
	}
	return FALSE;
}