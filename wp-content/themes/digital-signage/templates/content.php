<?php
/**
 * Template Name: Content Template
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		// while ( have_posts() ) : the_post();

		// 	// Include the page content template.
		// 	get_template_part( 'content', 'page' );

		// 	// If comments are open or we have at least one comment, load up the comment template.
		// 	if ( comments_open() || get_comments_number() ) :
		// 		comments_template();
		// 	endif;

		 // End the loop.
		// endwhile;
		
        /* for event schduling */
		$blogtime = current_time( 'mysql' ); 
		list( $today_year, $today_month, $today_Day, $hour, $minute, $second ) = preg_split( '([^0-9])', $blogtime );
		echo $minute;
		if($hour < 12) {
			// before noon
			echo do_shortcode('[rev_slider alias="slider1"]');
		} 
		elseif ($hour > 12 AND $hour < 14 ) {
			// between 12 PM and 2PM
			echo do_shortcode('[rev_slider alias="slider2"]');
		} 
		elseif ($hour > 14 ) {
			// evening or night slider
			echo do_shortcode('[rev_slider alias="slider3"]');
		}

		?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
