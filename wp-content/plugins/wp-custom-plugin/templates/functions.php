<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*******************************************************************/

// Filters
add_filter('single_template', 'epic_custom_single_templates', 1);

/*
********************************
FUNCTIONS
********************************
*/

/**
* Loads a custom single template based on post type
* @param string $template The path to the template file
* @uses epic_custom_template()
* @return string The updated template path
*/
function epic_custom_single_templates($template){
	global $post;

	switch($post->post_type){		
		case 'concertold':
			$template = epic_custom_template("single-concert", "/");
			$template = apply_filters("epic_custom_group_single_template", $template );
			break;
	}

	return $template;
}


/**
* Path to the specified custom module template
* @param string $template The template file name
* @param string $folder_prefix The path to the template file
* @return string The updated template path
*/
function epic_custom_template( $template, $folder_prefix = "/" ){
	return apply_filters("epic_custom_template_{$template}", NICKNICE_CUSTOM_PATH."/templates/autoload{$folder_prefix}{$template}.php");
}

/**
* Loads a specific custom template file
* @param string $template The template file name
* @param string $folder_prefix The path to the template file
* @uses ets_template_load() 
* @return bool Whether or not the file was loaded
*/
function epic_custom_load_template( $filename, $folder_prefix = "/" ){

	if( ets_template_load($filename, $folder_prefix, NICKNICE_CUSTOM_PATH."/templates/autoload") )
		return true;

	return false;
}


/**
* Takes an array of files to require & processes them
* @param Array $files The files to be required
* @uses epic_core_file_require_once() 
*/
function epic_core_files_require( $files ){
	if( !$files )
		return false;
		
	foreach( $files as $key => $f ){
		if( is_array($f) ){
			foreach( $f as $subkey => $subfile ){
				if( is_array($subfile) ){
					foreach( $subfile as $sub_sub_key => $sub_sub_file ){
						epic_core_file_require_once( $sub_sub_file );
					}
				}
				else{
					epic_core_file_require_once( $subfile );
				}
			}
		}
		else{
			epic_core_file_require_once( $f );
		}
	}
}

/**
* Checks to make sure the file to require exists and, if so, fire it up!
* @param string $file The file to be required
*/
function epic_core_file_require_once( $file ){
	if( file_exists($file) ){
		require_once( $file );
	}
}

/**
* Returns the path to the invalid request template
* @param string $template The template name
* @param string $folder_prefix The folder prefix
* @param string $path_begin The route to the file
* @return string The complete path to the file
*/
function epic_file_template_path( $template, $folder_prefix = "/", $path_begin = false ){
	return "{$path_begin}/templates/autoload{$folder_prefix}{$template}.php";
}

/**
* Load a template based on passed parameters
* @param string $template The template name
* @param string $folder_prefix The folder prefix
* @param string $path_begin The route to the file
* @uses epic_file_template_path() 
* @return bool Whether or not the file was loaded
*/
function epic_file_template_load( $filename, $folder_prefix = "/", $path_begin = false ){
	$template = epic_file_template_path( $filename, $folder_prefix, $path_begin );
	if( file_exists($template) ){
		include( apply_filters("epic_file_template_load_{$filename}", $template ) );
		return true;
	}
	return false;
}

?>
