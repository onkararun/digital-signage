<?php 

// creating a function to add stylesheet
function DigitalSignage_add_parent_style() {
	wp_enqueue_style( 'twentyfifteen-style', get_template_directory_uri() . '/style.css', array() );
}

// add action name, function name
add_action( 'wp_enqueue_scripts', 'DigitalSignage_add_parent_style' );

function my_acf_load_field( $field ) {

    $field['choices'] = array(
        [rev_slider alias="slider1"];
    );

    return $field;

}

add_filter('acf/load_field/type=select', 'my_acf_load_field');

