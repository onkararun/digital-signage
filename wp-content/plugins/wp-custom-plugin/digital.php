<?php
/**
 * Plugin Name: DIGITAL CUSTOM PLUGIN
 * Plugin URI:  http://gai.co.in
 * Description: Adds custom code.
 * Author:      Arun.
 * Author URI:  http://gai.co.in
 * Version:     1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit; 

/*******************************************************************/

define('DIGITAL_CUSTOM_VERSION', '1.0');
define('DIGITAL_CUSTOM_PATH', WP_PLUGIN_DIR . '/wp-custom-plugin');
define('DIGITAL_CUSTOM_URL', plugins_url() . '/wp-custom-plugin');

/*Require the hub loader*/
require_once( DIGITAL_CUSTOM_PATH.'/digital-loader.php' );

?>
