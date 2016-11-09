<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*******************************************************************/

add_filter( 'the_content', 'digital_the_content_filter', 20 );
/**
 * Add slider in a page.
 *
 * @uses is_single()
 */
function digital_the_content_filter( $content ) {
    global $wpdb, $epic, $post;

    $contentdata = $content;
    if ( is_singular('page') ):
        $sql = "SELECT * FROM `{$wpdb->prefix}revslider_sliders`";
        $sliders = $wpdb->get_results($sql);
        if($sliders):
            $contentdata = "";
            foreach($sliders as $skey => $slide):
                $params = json_decode($slide->params);
                $short_code = stripslashes($params->shortcode);

                $slidedata = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}revslider_slides WHERE slider_id = $slide->id" );
                $slidedataparam = json_decode($slidedata->params);

                if($slidedataparam->page_id == $post->ID):
                    $from_hr = isset($slidedataparam->hr1) && $slidedataparam->hr1!="none"?$slidedataparam->hr1:'';
                    $from_min = isset($slidedataparam->min1) && $slidedataparam->min1!="none"?$slidedataparam->min1:'';

                    $to_hr = isset($slidedataparam->hr2) && $slidedataparam->hr2!="none"?$slidedataparam->hr2:'';
                    $to_min = isset($slidedataparam->min2) && $slidedataparam->min2!="none"?$slidedataparam->min2:'';

                    $date = isset($slidedataparam->date)?$slidedataparam->date:'';

                    $style = "display:none;";
                    $current = false;
                    if(count($sliders) == 1):
                        $style = "";
                        $current = true;
                    endif;

                    if(!$from_hr && !$from_min && !$to_hr && !$to_min && !$date):
                        $style = "";
                        $current = true;
                    endif;

                    if( (date('d-m-Y H:i') >= $date) && (date('Hi') >= $from_hr.$from_min && date('Hi') <= $to_hr.$to_min) ):
                        $style = "";
                        $current = true;
                    endif;

                    if( !$date && (date('Hi') >= $from_hr.$from_min && date('Hi') <= $to_hr.$to_min) ):
                        $style = "";
                        $current = true;
                    endif;

                    $contentdata .= "<div data-from-hour='{$from_hr}' data-from-min='{$from_min}' data-to-hour='{$to_hr}' data-to-min='{$to_min}' data-date='{$date}' class='loop-slide' id='{$slide->alias}' style='{$style}' data-current='{$current}'>
                                ".do_shortcode($short_code)."
                                </div>";
                endif;
            endforeach;
        endif;
    endif;

    // Returns the content.
    return $contentdata;
}