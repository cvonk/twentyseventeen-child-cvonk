<?php

/**
 * The template for displaying author page (identical to category.php)
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

function cvonk_thumb_enqueue_category_style() {
    wp_enqueue_style( 'category-style', get_stylesheet_directory_uri().'/css/category.css' );
}
add_action( 'wp_enqueue_scripts', 'cvonk_thumb_enqueue_category_style');

function cvonk_thumb_enqueue_category_script() {
    wp_enqueue_script( 'category-script', get_stylesheet_directory_uri().'/js/category.js' );
}
add_action( 'wp_enqueue_scripts', 'cvonk_thumb_enqueue_category_script');

{
    get_header();

    // Load More, JavaScript
    // @link https://www.billerickson.net/infinite-scroll-in-wordpress/
    if ( get_theme_mod('cvonk_thumb_loadmore')) {
	global $wp_query;
	$args = array(
	    'nonce' => wp_create_nonce( 'cvonk-thumb-loadmore-nonce' ),
	    'url'   => admin_url( 'admin-ajax.php' ),
	    'query' => $wp_query->query,
	);

	wp_enqueue_script( 'cvonk-thumb-loadmore', get_stylesheet_directory_uri() . '/js/load-more.js', array( 'jquery' ), '1.0', true );
	wp_localize_script( 'cvonk-thumb-loadmore', 'cvonk_thumb_loadmore', $args );
    }
    
    echo '<div class="wrap">';
    
    if ( have_posts() ) {
	echo '<header class="page-header">';
	the_archive_title( '<h1 class="page-title">', '</h1>' );
	the_archive_description( '<div class="taxonomy-description">', '</div>' );
	echo '</header>';
    }
    
    echo '<div id="primary" class="content-area">';
    echo '  <main id="main" class="site-main" role="main">';
    echo '    <div class="flex-container">';
    echo '      <section>';
    echo '        <div class="article-container category">';
    
    if ( have_posts() ) {
	while ( have_posts() ) {
            the_post();
            cvonk_thumb_category_excerpt(); // in functions.php
	}
	echo '</div></section></div>';
	
   	if (!get_theme_mod('cvonk_thumb_loadmore')) {
	    the_posts_pagination( array(
		'prev_text' => twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'twentyseventeen' ) . '</span>',
		'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'twentyseventeen' ) . '</span>' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ),
		'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyseventeen' ) . ' </span>',
	    ) );
	}
    } else {
        auth_redirect();
        get_template_part( 'template-parts/post/content', 'none' );
    }
    echo '</main></div>';
    get_sidebar();
    echo '</div><!-- .wrap -->';

    get_footer();
}

?>
