<?php
/**
* Template Name: Blank Page
*
*/
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<!-- page title, displayed in your browser bar -->
<title><?php bloginfo('name'); ?> | <?php is_home() ? bloginfo('description') : wp_title(''); ?></title>

<!-- add feeds, pingback and stuff-->
<?php
	if(is_singular('page') )
	{
	echo '<META HTTP-EQUIV="REFRESH" CONTENT="3600">' ;
	}
?>
<?php wp_head(); ?>
</head>

<body id="top">
<div class='container_wrap' id='main'>
<div class='container'>
<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile; ?>
<?php endif; ?>
</div>
</div>
<?php wp_footer(); ?>
</body>
</html>