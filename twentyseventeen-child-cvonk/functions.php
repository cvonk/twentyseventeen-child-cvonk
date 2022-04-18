<?php

// disable heart beat to prevent CPU overusage on siteground
function stop_heartbeat() {
    wp_deregister_script('heartbeat');
}
add_action( 'init', 'stop_heartbeat', 1 );

// MathJax LaTeX configuration
function configure_mathjax() {
    if ( is_single() ) {
        echo "<!-- Added by functions.php -->\n";
        echo "<script>\n";
        echo "MathJax = {\n";
        echo "    loader: {\n";
        echo "        load: ['[tex]/cancel', '[tex]/physics']\n";
        echo "    },\n";
        echo "    tex: {\n";
        echo "        macros: {\n";
        echo "            shaded: ['\\\\bbox[5pt,background-color:##F7F7D2]{#1}', 1, null],\n";
//        echo "            shaded: ['{\\bbox[5pt,background-color:##F7F7D2]{#1}', 1, 'x'],\n";
        echo "        },\n";
        echo "        packages: {\n";
        echo "            '[+]': ['cancel',]\n";
        echo "        },\n";
        echo "        tags: 'all',\n";
        echo "    }\n";
        echo "};\n";
        echo "</script>" . "\n";
        echo "<!-- end added by functions.php -->\n";

        echo "<!-- Added by functions.php -->\n";
        echo "    <script type='text/javascript'>" . "\n";
        echo "        /* <![CDATA[ */" . "\n";
        echo "        document.querySelectorAll('ul.nav-menu').forEach(" . "\n";
        echo "            ulist => {" . "\n";
        echo "                if (ulist.querySelectorAll('li').length == 0) {" . "\n";
        echo "                    ulist.style.display = 'none';" . "\n";
        echo "                }" . "\n";
        echo "            }" . "\n";
        echo "        );" . "\n";
        echo "        /* ]]> */" . "\n";
        echo "    </script>" . "\n";
        // echo "    <script type='text/javascript' id='MathJax-script' async src='https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js'></script>" . "\n";
        echo "<!-- end added by functions.php -->\n";

    }
}
add_action ( 'wp_head', 'configure_mathjax' );

function cvonk_thumb_enqueue_parent_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}
add_action( 'wp_enqueue_scripts', 'cvonk_thumb_enqueue_parent_theme_styles');

/**
 * Disable jQuery Migrate in WordPress.
 *
 * @author Guy Dumais.
 * @link https://en.guydumais.digital/disable-jquery-migrate-in-wordpress/
 */
add_filter( 'wp_default_scripts', $af = static function( &$scripts) {
    if(!is_admin()) {
        $scripts->remove( 'jquery');
        $scripts->add( 'jquery', false, array( 'jquery-core' ), '1.12.4' );
    }    
}, PHP_INT_MAX );
unset( $af );

// disable emoji icons, to speed up loading, and minimize http requests
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// disable oEmbed, to speed up loading, and minimize http requests
function my_deregister_scripts(){
    wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'my_deregister_scripts' );


//Making jQuery to load from Google Library
// https://colorlib.com/wp/load-wordpress-jquery-from-google-library/
function replace_jquery() {
    if (!is_admin()) {
	// comment out the next two lines to load the local copy of jQuery
	wp_deregister_script('jquery');
	#wp_register_script('jquery', '//code.jquery.com/jquery-1.12.4.min.js', false, '1.12.4');
	wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js', false, '1.12.4');
	wp_enqueue_script('jquery');
    }
}
add_action('init', 'replace_jquery');

// Remove Query String from Static Resources
function _remove_script_version( $src ){
    $parts = explode( '?ver', $src );
    return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );

/**
 * Category Excerpt, outputs excerpt for current post
 * @link https://codepen.io/cvonk/full/KXGvRe/
 *
 * Called directly from category.php, or using AJAX as part of Load More
 */
function cvonk_thumb_category_excerpt() {
    $this_category = get_the_category()[0]->slug;

    $thumbnail_categories = explode(',', get_theme_mod('cvonk_thumb_categories'));
    $style = "style1";
    $style1 = true;
    foreach($thumbnail_categories as $thumb_category_id) {
	$thumb_category = &get_category((int)$thumb_category_id);
	if( !strcasecmp($thumb_category->slug, $this_category)) {
	    $style = "style2";
	    $style1 = false;
	}
    }
    echo '<article class="post ' . $style . ' mask-triangle zoom-rotate-photo">';
    echo '  <div class="picture-container">';
    echo '    <a href="' . get_the_permalink() . '" rel="bookmark" title="Permanent link">';
    if (has_post_thumbnail()) {
	  the_post_thumbnail($size="400 400");
    }
    echo '    <svg name="spinner" class="spinner" viewBox="0 0 66 66" xmlns="http://www.w3.org/2000/svg">';
    echo '      <circle class="path" fill="none" stroke-width="6" stroke-linecap="round" cx="33" cy="33" r="30"></circle>';
    echo '    </svg></a></div>';
    echo '  <div class="article-text">';
    echo '    <a class="text-title" href="' . get_the_permalink() . '" rel="bookmark" title="Permanent link">' . get_the_title() . '</a>';
    echo '    <span class="text-excerpt">' . preg_replace('|<a.*?/a>|', '', get_the_excerpt()) . '</span>';
    if ($style1) {
	echo '    <div class="text-footer">By ' . get_the_author() . '  |  ';
	edit_post_link();
	echo '</div>';
    }
    echo '  </div></article>';
}


/**
 * Load More, Asynchronous JavaScript
 * @link https://www.billerickson.net/infinite-scroll-in-wordpress/
 */
function cvonk_thumb_loadmore_ajax() {
    check_ajax_referer( 'cvonk-thumb-loadmore-nonce', 'nonce' );

    $args = isset( $_POST['query'] ) ? $_POST['query'] : array();
    $args['post_type'] = isset( $args['post_type'] ) ? $args['post_type'] : 'post';
    $args['paged'] = $_POST['page'];
    $args['post_status'] = 'publish';

    $query = new WP_Query( $args );
    ob_start();
    if( $query->have_posts() ) {
	while( $query->have_posts() ) {
	    $query->the_post();
	    cvonk_thumb_category_excerpt();
	}
    }
    wp_reset_postdata();
    $data = ob_get_clean();
    wp_send_json_success( $data );
}
if ( get_theme_mod('cvonk_thumb_loadmore')) { // announce ourselves to AJAX query
    add_action( 'wp_ajax_cvonk_thumb_loadmore_ajax', 'cvonk_thumb_loadmore_ajax' );
    add_action( 'wp_ajax_nopriv_cvonk_thumb_loadmore_ajax', 'cvonk_thumb_loadmore_ajax' );
}


function cvonk_thumb_walk_category_tree( $cat, $lvl ) {
    $next = get_categories('hide_empty=false&orderby=name&order=ASC&parent=' . $cat);
    if( $next ) {
	foreach( $next as $cat ) {
	    echo '<label><input type="checkbox" name="category-' . $cat->term_id . 
		 '" id="category-' . $cat->term_id . '" class="cvonk-category-checkbox"> ' .
		 str_repeat('-&nbsp;', $lvl) . $cat->cat_name . '</label><br>';

	    cvonk_thumb_walk_category_tree( $cat->term_id, $lvl + 1 );
	}
    }
    echo "\n";
}

/**
 * Add theme options
 *
 * @link https://themefoundation.com/wordpress-theme-customizer/
 * @link https://themefoundation.com/customizer-multiple-category-control/
 * @link https://wordpress.stackexchange.com/questions/41548/get-categories-hierarchical-order-like-wp-list-categories-with-name-slug-li
 */
function cvonk_thumb_customize_register( $wp_customize ) {

    // Class adds multiple category selection support to the theme customizer via checkboxes
    // The category IDs are saved as a comma separated string.

    class MultipleCategories_Control extends WP_Customize_Control {

        public function render_content() {

            // Loads theme-customizer.js javascript file.
            echo '<script src="' . get_stylesheet_directory_uri() . '/js/multiple-categories.js"></script>';

            echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
            echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';

            cvonk_thumb_walk_category_tree( 0, 0 );  // 0 for all categories  

            // hidden input field stores the saved category list.
            echo '<input type="hidden" id="' . $this->id . '"';
            echo 'class="cvonk-hidden-categories"' . $this->link() .
            'value="' . sanitize_text_field( $this->value() ) . '">';	    
        }
    }

    // option to show category items as thumbnails    
    $wp_customize->add_setting( 'cvonk_thumb_categories' );
    $wp_customize->add_control( new MultipleCategories_Control( $wp_customize, 'cvonk_thumb_categories', array(
	'label' => __('Categories shown as thumbnails', 'twentyseventeen-child-cvonk' ),
	'description' => __( 'Selected categories will be shown as thumbnails instead of cards in the category view.', 'twentyseventeen-child-cvonk' ),
  	'section' => 'theme_options',
	'settings' => 'cvonk_thumb_categories' ) )
    );

    // option to categories as infinite page (AKA load-more)
    $wp_customize->add_setting( 'cvonk_thumb_loadmore', array(
	'default'           => true,
	'transport'         => 'postMessage',
    ) );
    $wp_customize->add_control( 'cvonk_thumb_loadmore', array(
	'label'       => __('All on one page', 'twentyseventeen-child-cvonk' ),
	'section'     => 'theme_options',
	'settings'    => 'cvonk_thumb_loadmore',
	'type'        => 'checkbox',
	'description' => __( 'Shows all the items on one page, loading them on demand.', 'twentyseventeen-child-cvonk' ),
	'active_callback' => 'twentyseventeen_is_view_with_layout_option',
    ) );
}
//add_action( 'customize_register', 'cvonk_thumb_customize_register' );

// from https://wpsites.net/wordpress-tips/reverse-post-order-for-category-archive/
function cvonk_change_category_order( $query ) {
    if ( $query->is_main_query() ) {
        $query->set( 'order', 'ASC' );
    }
}
//add_action( 'pre_get_posts', 'cvonk_change_category_order' );

//remove_filter( 'the_content', 'wpautop' );
//remove_filter( 'the_excerpt', 'wpautop' );


// do not show subcategory posts
function wpse_filter_child_cats( $query ) {
    if ( $query->is_category ) {
        $queried_object = get_queried_object();
        $child_cats = (array) get_term_children( $queried_object->term_id, 'category' );

        if ( ! $query->is_admin )
            //exclude the posts in child categories
            $query->set( 'category__not_in', array_merge( $child_cats ) );
        }
        return $query;
}
//add_filter( 'pre_get_posts', 'wpse_filter_child_cats' );

// if category has only one (direct) post, then redirect to that post
// https://www.isitwp.com/redirect-to-a-single-post-if-one-post-in-category-or-tag/
function redirect_to_post(){
    global $wp_query;
    if( is_archive() && $wp_query->post_count == 1 ){
        the_post();
        $post_url = get_permalink();
        wp_redirect( $post_url );
    }   
  
}
//add_action('template_redirect', 'redirect_to_post');


function filter_archive_title( $prefix ){
	// filter...
	return '';
}
add_filter( 'get_the_archive_title_prefix', 'filter_archive_title' );

?>
