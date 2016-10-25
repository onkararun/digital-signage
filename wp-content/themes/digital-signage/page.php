<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			// Include the page content template.
			get_template_part( 'content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		// End the loop.
		endwhile;
		 $current_id = get_the_ID();
		 if($current_id) {
			 	if ( class_exists( 'RevSlider' ) ) {
				    $rev_slider = new RevSlider();
				    $sliders = $rev_slider->getAllSliderAliases();
				   // echo $sliders[0];
				    global $wpdb;
                    $query = "SELECT id FROM wp_revslider_sliders WHERE title = '$sliders[0]'";
                   // echo $query;
                    $slidersdata = $wpdb->get_results($query,OBJECT);
                    //echo $slidersdata[0]->id ;
                    $query1 = "SELECT params FROM wp_revslider_slides WHERE slider_id =". $slidersdata[0]->id;
                     //echo $query1;
                    $slides = $wpdb->get_results($query1,OBJECT);
                    $slides = json_decode($slides[0]->params);
                   //echo"<pre>"; print_r($slides); die;
                     echo $slides->page_id; die;

                } 
                else {
				    $sliders = array();
				}
		 }	
		?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>
