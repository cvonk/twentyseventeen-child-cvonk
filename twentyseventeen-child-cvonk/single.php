<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

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

get_header(); ?>

<script>function cvonk_loadbox() {
    jQuery(".tabs-container a").click(function(event) {
	event.preventDefault();
	jQuery(this)
	    .parents(".authorbox")
	    .find(".tabs-container a")
	    .removeClass("active");
	jQuery(this).addClass("active");
	jQuery(this)
	    .parents(".authorbox")
	    .find("article.about")
	    .hide();
  jQuery(this)
	    .parents(".authorbox")
	    .find("article.posts")
	    .hide();

	var selected_tab = jQuery(this)
	    .attr("href");
  console.log(selected_tab);
	jQuery(this)
	    .parents(".authorbox")
	    .find(selected_tab.replace("#", "."))
	    .fadeIn();
	jQuery(this)
	    .parents(".authorbox")
	    .find(selected_tab.replace("#", "."))
	    .parents(".authorbox")
	    .find(selected_tab.replace("#", "."))
	    .addClass("active");
	return false;
    });
}
jQuery(document).ready(function() {
    cvonk_loadbox();
});
</script>
<style>
	div.wrap.container {
		display: flex;
		justify-content: space-around;
		align-items: flex-start;
		flex-wrap: wrap;
	}
	div.content-area {
		align-self: center;
	}
</style>
<div class="wrap container">
    <div id="secondary" class="category-toc">
		<div class="lcp no-print connect fixed-size">
        <?php
          /*
          $categories = get_the_category();
          if ( ! empty( $categories ) ) {
            echo '<div class="lcp_catname">' . esc_html( $categories[0]->name ) . '</div>';
          }
          */
          echo do_shortcode('[catlist categorypage=yes catlink=yes catlink_class="lcp_catlink]');
        ?>
		</div>
    </div>
    <div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">

			<?php
			/* Start the Loop */
			while ( have_posts() ) {
			    the_post();

			    get_template_part( 'template-parts/post/content', get_post_format() );

				the_post_navigation( array(
					'in_same_term' => true,
					'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'twentyseventeen' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'twentyseventeen' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'twentyseventeen' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . twentyseventeen_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
				) );

/*
			    // Get Author Data
			    $author             = get_the_author();
			    $author_job         = get_the_author_meta( 'yim' );  // reuse Yahoo IM field from Profile
			    $author_description = get_the_author_meta( 'description' );
			    $author_url         = esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );
			    $author_avatar      = get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'wpex_author_bio_avatar_size', 75 ) );

			    // Only display if author has a description
			    if ( $author_description ) {
				
                    $html = $html . '<div class="authorbox">' . "\n";
                    $html = $html . '' . "\n";
                    if ( $author_avatar ) {
                        $html = $html . '  <article class="image"><a href="' . esc_url($author_url) . '" class="url" target="_blank" title="' . esc_html($author) .'">' . $author_avatar . '</a></article>'  . "\n";
                    }
                    $html = $html . '  </article>' . "\n";
                    $html = $html . '  ' . "\n";
                    $html = $html . '  <article class="about" style="display:inline-block;">' . "\n";
                    $html = $html . '      <div class="title"><a href="' . esc_url($author_url) . '" class="url" target="_blank">' . esc_html($author) . '</a></div>' . "\n";

                    $html = $html . '      <div class="job">' . wp_kses_post( $author_job ) . '</div>' . "\n";
                    $html = $html . '      <div class="description">' . wp_kses_post( $author_description ) . '</div>' . "\n";
                    $html = $html . '  </article>' . "\n";
                    $html = $html . '' . "\n";
                    $html = $html . '  <article class="posts">' . "\n";
                    $html = $html . '      <div class="title"><a href="' . esc_url($author_url) . '">' . esc_html( $author ) . '</a></div>' . "\n";
                    $html = $html . '      <div class="description">' . "\n";

                    $args = array( 'numberposts' => '3' );
                    $recent_posts = wp_get_recent_posts( $args );
                    foreach( $recent_posts as $recent ){
                        $html = $html . '          <a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a>';
                    }
                    wp_reset_query();

                    $html = $html . '      </div>' . "\n";
                    $html = $html . '  </article>' . "\n";
                    $html = $html . '' . "\n";
                    $html = $html . '  <article class="tabs-container">' . "\n";
                    $html = $html . '    <a class="about active" href="#about">About</a>' . "\n";
                    $html = $html . '    <a class="posts" href="#posts">Latest</a>' . "\n";
                    $html = $html . '  </article>' . "\n";
                    $html = $html . '</div>' . "\n";
                    echo $html;

                    // this gets the avatar from Google+ profile
                    // $data = file_get_contents('http://picasaweb.google.com/data/entry/api/user/' . "user@gmail.com" . '?alt=json');
                    // $d = json_decode($data);
                    // $avatar = $d->{'entry'}->{'gphoto$thumbnail'}->{'$t'};
                }			    
*/            
                    
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) {
                    comments_template();
                }
			}
			?>

	</main><!-- #main -->
    </div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .wrap -->

<?php get_footer(); ?>
