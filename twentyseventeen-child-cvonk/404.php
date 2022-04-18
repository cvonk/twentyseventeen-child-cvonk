<?php
header("HTTP/1.0 404 Not Found");
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

function endsWith($haystack, $needle) {
    $length = strlen($needle);
    return $length === 0 || (substr($haystack, -$length) === $needle);
}

$current_url = home_url(add_query_arg(array($_GET),$wp->request));
if( endsWith($current_url, 'video') || endsWith($current_url, 'johan') ) :
	       auth_redirect();
endif;

get_header(); ?>

<div class="wrap">
    <div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

	    <section class="error-404 not-found">
		<header class="page-header">
		    <h1 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'twentyseventeen' ); ?></h1>
		</header><!-- .page-header -->
		<div class="page-content">
		    <p><?php _e( 'Maybe try a search?', 'twentyseventeen-child-cvonk' ); ?></p>

					<?php get_search_form(); ?>

		</div><!-- .page-content -->
	    </section><!-- .error-404 -->
	</main><!-- #main -->
    </div><!-- #primary -->
</div><!-- .wrap -->

<?php get_footer(); ?>
