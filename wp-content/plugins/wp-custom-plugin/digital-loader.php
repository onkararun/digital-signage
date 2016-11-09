<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

/*******************************************************************/


require_once( DIGITAL_CUSTOM_PATH . '/templates/functions.php' );

// Load the core files for the nicknice plugin
add_action("plugins_loaded", "digital_custom_load_files", 20);
function digital_custom_load_files(){
    $files = array(
        'data-functions' => DIGITAL_CUSTOM_PATH.'/functions/data.php', //core data functions for incoming email actions, etc
        'uri-functions' => DIGITAL_CUSTOM_PATH.'/functions/extensions/uri/functions.php',
		'slider-display' => DIGITAL_CUSTOM_PATH.'/modules/slide/display.php',
		'slider-function' => DIGITAL_CUSTOM_PATH.'/modules/slide/functions.php'
    );
    // require_once the files
    epic_core_files_require( apply_filters("digital_custom_core_files", $files) );
}




// digital init function
add_action('init', 'digital_custom_init', 2);
function digital_custom_init(){
	global $epic, $blog_id;

	/*Remove the WP admin bar*/
	add_filter( 'show_admin_bar', '__return_false' );

}


add_action("init", "digital_custom_ajax_hook_check", 12);
function digital_custom_ajax_hook_check(){
	global $epic;
	if( !isset($_REQUEST['digital_ajax_hook']) || !$_REQUEST['digital_ajax_hook'] )
		return true;
		
	if( isset($_REQUEST['digital_ajax_hook']) &&  ($epic->uri[0] == "wp-load.php" || isset($_REQUEST['_wpnonce'])) ):
		$sanitized_hook = epic_data_escape($_REQUEST['digital_ajax_hook'], "strip");
		do_action("digital_custom_".$sanitized_hook."_processing");
	endif;
}


/*enqueue styles & javascript files for use in the community extension*/
add_action("wp_enqueue_scripts", "digital_custom_scripts", 9);
function digital_custom_scripts(){
	global $post, $wp_rewrite;

	// adding scripts only when user is not logged in. excluding EpicBeat since login page for EpicBeat is shown on site 1

	/*JAVASCRIPT*/
	wp_enqueue_script( 'digital-custom-js', DIGITAL_CUSTOM_URL . '/_inc/js/custom.js', array( 'jquery' ), filemtime( DIGITAL_CUSTOM_PATH . '/_inc/js/custom.js' ), true );
}

add_action('wp_head', 'digital_custom_custom_jsvariables', 20);
function digital_custom_custom_jsvariables(){
    global $blog_id;
    ?>
    <script type="text/javascript">
        var etajaxurl = "<?php echo site_url( 'wp-load.php' ); ?>";
    </script><?php
}

