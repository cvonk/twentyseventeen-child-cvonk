<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	
	if ( is_sticky() && is_home() ) :
	echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	endif;
	?>
    <header class="entry-header">
		<?php
/*
		function display_last_updated_date() {
		    $original_time = get_the_time('U');
		    $modified_time = get_the_modified_time('U');
		    if ($modified_time >= $original_time + 86400) {
			$updated_time = get_the_modified_time('h:i a');
			$updated_day = get_the_modified_time('Y-m-d');
			return 'Last update <span class="last-modified">'. $updated_day .'</span>';
		    }
		    return "";
		}
*/		
		if ( is_single() ) {
		    the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
		    the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
		    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		if ( 'post' === get_post_type() ) {
		    echo '<div class="entry-meta"><center>';
		    if ( is_single() ) {
				echo '<span class="vcard author post-author author_name"><span class="fn">' . get_the_author() . '</span></span><br>';
				//echo '<span class="posted-on">' . preg_replace('/<\/?a[^>]*>/','', twentyseventeen_time_link()) . '</span>';
				//echo '<span class="post-date updated">' . get_the_modified_time('Y-m-d') . '</span>';
		    } else {
				echo twentyseventeen_time_link();
				twentyseventeen_edit_link();
		    };
		    echo '</center></div><!-- .entry-meta -->';
		};

		?>
    </header><!-- .entry-header -->

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() ) : ?>
	    <div class="post-thumbnail">
		<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
		</a>
	    </div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(
		    __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'twentyseventeen' ),
		    get_the_title()
		) );

		wp_link_pages( array(
		    'before'      => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
		    'after'       => '</div>',
		    'link_before' => '<span class="page-number">',
		    'link_after'  => '</span>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->
