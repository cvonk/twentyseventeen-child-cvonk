<script>
 var videoPlayer = {};
 (function() {
     var videoPlayButton;
     var container;
     var video;
     
     this.renderPlayButton = function() {
	 container.insertAdjacentHTML('beforeend', '<svg class="video-play-button" viewBox="0 0 200 200" alt="Play video"><circle cx="100" cy="100" r="90" fill="none" stroke-width="15" stroke="#fff"/><polygon points="70, 55 70, 145 145, 100" fill="#fff"/></svg>');
	 video.classList.add('has-media-controls-hidden');
	 videoPlayButton = document.getElementsByClassName('video-play-button')[0];
	 videoPlayButton.addEventListener('click', this.videoStarted, false);
	 video.addEventListener('ended', this.videoEnded, false);
     }

     this.videoStarted = function() {
	 video.setAttribute('tv', "on");
	 video.play();
	 videoPlayButton.classList.add('is-hidden');
	 video.classList.remove('has-media-controls-hidden');
	 video.setAttribute('controls', 'controls');
     }

     this.videoEnded = function(obj) {
	 video.setAttribute('tv', "off");
	 video.pause();
	 videoPlayButton.classList.remove('is-hidden');
	 video.classList.add('has-media-controls-hidden');
	 video.setAttribute('controls', 'none');
     }

     this.init = function() {
	 console.log("init");
	 container = document.getElementsByClassName('video-container')[0];
	 video = document.getElementsByTagName('video')[0];
	 this.renderPlayButton();
     }
 }.apply(videoPlayer));

 //window.addEventListener("load", videoPlayer.init, false);

 console.log("js");
 window.addEventListener("load", function() {
     videoPlayer.init();
 }, false);
</script>
<style>
 .video-container {
     display: block;
     margin: 0 0 0 0;
     position: relative;
     max-height: 394px;
     max-width: 700px;
     background: #202020;
     margin: auto;
     overflow: hidden;
 }
 .video-container > video {
     width: 100%;
     border: none;
 }
 .video-container > video[tv="off"] {
     animation: turn-off 0.55s cubic-bezier(0.23, 1, 0.32, 1);
     animation-fill-mode: forwards;
 }
 .video-container > video[tv="on"] {
     animation: turn-on 2s linear;
     animation-fill-mode: forwards;
 }

 .video-container > video.has-media-controls-hidden::-webkit-media-controls {
     display: none;
 }

 .video-play-button {
     position: absolute;
     top: 0;
     left: 0;
     box-sizing: border-box;
     width: 100%;
     height: 100%;
     padding: 10px calc(50% - 50px);
     display: block;
     opacity: 0.95;
     cursor: pointer;
     background-image: linear-gradient(transparent, #000);
     transition: opacity 150ms;
 }

 .video-play-button:hover {
     opacity: 1;
 }

 .video-play-button.is-hidden {
     display: none;
 }

 @keyframes turn-on {
     0% {
	 transform: scale(1, 0.8) translate3d(0, 0, 0);
	 -webkit-filter: brightness(30);
	 filter: brightness(30);
	 opacity: 1;
     }
     3.5% {
	 transform: scale(1, 0.8) translate3d(0, 100%, 0);
     }
     3.6% {
	 transform: scale(1, 0.8) translate3d(0, -100%, 0);
	 opacity: 1;
     }
     9% {
	 transform: scale(1.3, 0.6) translate3d(0, 100%, 0);
	 -webkit-filter: brightness(30);
	 filter: brightness(30);
	 opacity: 0;
     }
     11% {
	 transform: scale(1, 1) translate3d(0, 0, 0);
	 -webkit-filter: contrast(0) brightness(0);
	 filter: contrast(0) brightness(0);
	 opacity: 0;
     }
     100% {
	 transform: scale(1, 1) translate3d(0, 0, 0);
	 -webkit-filter: contrast(1) brightness(1.2) saturate(1.3);
	 filter: contrast(1) brightness(1.2) saturate(1.3);
	 opacity: 1;
     }
 }

 @keyframes turn-off {
     0% {
	 transform: scale(1, 1.3) translate3d(0, 0, 0);
	 -webkit-filter: brightness(1);
	 filter: brightness(1);
	 opacity: 1;
     }
     60% {
	 transform: scale(1.3, 0.001) translate3d(0, 0, 0);
	 -webkit-filter: brightness(10);
	 filter: brightness(10);
     }
     100% {
	 animation-timing-function: cubic-bezier(0.755, 0.05, 0.855, 0.06);
	 transform: scale(0, 0.0001) translate3d(0, 0, 0);
	 -webkit-filter: brightness(50);
	 filter: brightness(50);
     }
 }
</style>
<?php
/**
 * Template part for displaying video posts
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
	if ( is_sticky() && is_home() ) {
	    echo twentyseventeen_get_svg( array( 'icon' => 'thumb-tack' ) );
	}
	?>
    <header class="entry-header">
		<?php
		if ( 'post' === get_post_type() && !is_single() ) {
		    echo '<div class="entry-meta">';
		    echo twentyseventeen_time_link();
		    twentyseventeen_edit_link();
		    echo '</div><!-- .entry-meta -->';
		};

		if ( is_single() ) {
		    the_title( '<h1 class="entry-title">', '</h1>' );
		} elseif ( is_front_page() && is_home() ) {
		    the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
		} else {
		    the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}
		if ( 'post' === get_post_type() && is_single() ) {
		    echo '<div class="entry-meta"><center>';
		    echo __( '<span class="byline">by ', 'twentyseventeen-child-cvonk' ) .
			 get_the_author() . '</span><br>';
		    echo '<span class="posted-on">' .
			 preg_replace('/<\/?a[^>]*>/','', twentyseventeen_time_link()) . '</span> ';
		    echo '</center></div><!-- .entry-meta -->';
		}		    
		?>
    </header><!-- .entry-header -->

	<?php
	$content = apply_filters( 'the_content', get_the_content() );
	$video = false;

	// Only get video from the content if a playlist isn't present.
	if ( false === strpos( $content, 'wp-playlist-script' ) ) {
	    $video = get_media_embedded_in_content( $content, array( 'video', 'object', 'embed', 'iframe' ) );
	}
	?>

	<?php if ( '' !== get_the_post_thumbnail() && ! is_single() && empty( $video ) ) : ?>
	    <div class="post-thumbnail">
		<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'twentyseventeen-featured-image' ); ?>
		</a>
	    </div><!-- .post-thumbnail -->
	<?php endif; ?>

	<div class="entry-content">
		<?php
		if ( ! is_single() ) {

		    // If not a single post, highlight the video file.
		    if ( ! empty( $video ) ) {
			foreach ( $video as $video_html ) {
			    echo '<div class="entry-video">';
			    echo $video_html;
			    echo '</div>';
			}
		    };

		};

		if ( is_single() || empty( $video ) ) {

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
		};
		?>

	</div><!-- .entry-content -->

	<?php
	if ( is_single() ) {
		twentyseventeen_entry_footer();
	}
	?>

</article><!-- #post-## -->
