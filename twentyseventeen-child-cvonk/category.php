<?php

/**
 * The template for displaying categories
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

// shows a lesson card listing the subcategories

function get_category_link_($category) {

    return sprintf( 
        '<a href="%1$s" alt="%2$s">%3$s</a>',
        esc_url( get_category_link( $category->term_id ) ),
        esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), $category->name ) ),
        esc_html( $category->name )
    );
}

function get_post_link_($post) {

	return sprintf( 
		'<a href="%1$s" alt="%2$s">%3$s</a>',
		esc_url( get_permalink($post) ),
		esc_attr( sprintf( __( 'View all posts in %s', 'textdomain' ), get_the_title($post) ) ),
		esc_html( get_the_title($post) )
	);
}

function get_postcard($post) {

    setup_postdata($post);
    {
		$post_link = get_post_link_($post);
        $edit_link = get_edit_post_link($post);

        $html  = '<div class="lesson-card fixed-size">';
        $html .= '<div class="lc_title">' . $post_link . '</div>'; 
        $html .= '<div class="lc_descr">';
        $html .= get_the_post_thumbnail($post, 'thumbnail', array( 'class' => 'lc_thumbnail alignright' ));
        $html .= '<div class="lc_excerpt">' . get_the_excerpt($post) . '</div>';
        if (current_user_can( 'edit_post', $post->ID )) {
            $html.= '<div class="lc_edit"><a href="' . esc_url($edit_link) . '"></a></div>';
        }
        $html .= '</div><!-- .lc_desc -->';
        $html .= '</div><!-- .lesson-card -->';
    }
    wp_reset_postdata();
    return $html;
}

function get_categorycard($category) {

    $sub_categories = get_categories( array(
        'parent' => $category->cat_ID,
        'hide_empty' => true,
        'orderby' => 'name',
        'order' => 'ASC' ));
 
    $html = '<div class="lesson-card">';
    $html .= '<div class="lc_title">' . get_category_link_($category) . '</div>'; 
    $html .= '<div class="lc_descr">' . $category->description;
    if (current_user_can('edit_categories')) {
        $html.= '<div class="lc_edit"><a href="' . esc_url(get_edit_term_link($category, 'category')) . '"></a></div>';
    }
    $html .= '</div><!-- .lc_descr -->';
    $html .= '<div class="lc_topics">Learn about</div>';

    if (Count($sub_categories) > 0) {

        $html .= '<div class="lcp dense"><ul class="lcp_catlist">';
        foreach( $sub_categories as $sub_category ) {
            $html .= '<li>' . get_category_link_($sub_category) . ' (' . $sub_category->count . ')</li>';
        }
        $html .= '</ul></div>';

    } else {

        $sub_posts = get_posts( array(
            'category' => $category->term_id,
            'orderby' => 'date',
            'order' => 'ASC',
            'numberposts' => -1 ));

        if (Count($sub_posts) > 0) {
            setup_postdata($post);
            {
                $html .= '<div class="lcp dense connect"><ul class="lcp_catlist">';
                foreach( $sub_posts as $post ) {
                    $html .= '<li>' . get_post_link_($post) . '</li>';
                }
                $html .= '</ul></div>';
            }
            wp_reset_postdata();
        }
    }
    $html .= '</div><!-- .lesson-card -->';
    return $html;
}

function get_category_tree($category) {

    $html = '';
    $elements = [];

    do {
        array_push($elements, '"' . $category->name . '"(' . $category->cat_ID . ')');
        $category = get_category($category->parent);
    } while($category->cat_ID);

    foreach(array_reverse($elements) as $element) {
        $html .= '<li>'. $element . '</li>';
    }
    return '<ul>' . $html . '</ul>';
}

get_header();
?>
<?php

echo '<div class="wrap">';

if ( have_posts() ) {
    echo '<header class="page-header">';
    echo the_archive_title( '<h1 class="page-title">', '</h1>' );
    the_archive_description( '<div class="taxonomy-description">', '</div>' );
    echo "<div class='topics'>Topics available:</div>";
    echo '</header>';

    echo '<div id="primary" class="content-area">';
    echo '  <main id="main" class="site-main" role="main">'; // 
    echo '    <div class="flex-container">';
    echo '      <section>';
    echo '        <div class="article-container category">';

  	$category = $wp_query->get_queried_object();
    $category_id = $category->cat_ID;

    $categories = get_categories( array(
        'parent' => $category_id,
        'hide_empty' => 0,
        'orderby' => 'name',
        'order' => 'ASC' ));

    foreach( $categories as $category ) {
        echo get_categorycard($category);
    }
    $posts = get_posts( array(
        'category__in' => $category_id,  // only direct decsendants
        'orderby' => 'date',
        'order' => 'ASC',
        'numberposts' => -1 ));

    foreach( $posts as $post_ ) {
        echo get_postcard($post_);
    }
} else {
		auth_redirect();
		get_template_part( 'template-parts/post/content', 'none' );
}

echo '</div><!-- .article-container -->';
echo '</section>';
echo '</div><!-- .flex-container -->';
echo '</main><!-- .site-main -->';
echo '</div><!-- .content-area -->';

get_sidebar();

echo '</div><!-- .wrap -->';

get_footer();

?>
