<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*******************************************************************/

// Actions
add_action('rev_slider_setting_page_below', 'display_rev_slider_setting_page_below', 2, 10);

//Functions

/**
 * @param $obj
 * @param $slideParams
 */
function display_rev_slider_setting_page_below($obj, $slideParams){
?>
    <!--Custom Pages dropdown menu-->
    <p>
        <?php $page_id = $obj->getVal($slideParams, 'page_id',''); ?>
        <label><?php _e("Pages:",'revslider'); ?></label>
        <?php
        $args = array('post_type'=> 'page','post_status'=> 'publish',);
        $posts_array = get_pages( $args );
        ?>
        <select id="page_id" name="page_id">
            <option value="none">Select</option>
            <?php foreach($posts_array as $page) { ?>
                <option value="<?php echo $page->ID; ?>"<?php if($page_id == $page->ID){ echo $selected = 'selected="selected"'; }?>><?php echo $page->post_title; ?></option>
            <?php } ?>
        </select>
        <span class="description"><?php _e("list of all pages.",'revslider'); ?></span>
    </p>
    <!--Custom field for Time from-->
    <p>
        <?php $hr1 = $obj->getVal($slideParams, 'hr1',''); ?>
        <label><?php _e("Time From:",'revslider'); ?></label>
        <select id="hr1" name="hr1">
            <option value="none">Hr</option>
            <?php for($i = 1; $i <= 24; $i++):  $i = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                <option value="<?php echo $i; ?>"<?php if($hr1 == $i){ echo $selected = 'selected="selected"'; }?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <?php $min1 = $obj->getVal($slideParams, 'min1',''); ?>
        <select id="min1" name="min1">
            <option value="none">Min</option>
            <?php for($i = 0; $i < 60; $i++): $i = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                <option value="<?php echo $i; ?>"<?php if($min1 == $i){ echo $selected = 'selected="selected"'; }?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <span class="description"><?php _e("Set the start time.",'revslider'); ?></span>
    </p>
    <!--Custom field for Time to-->
    <p>
        <?php $hr2 = $obj->getVal($slideParams, 'hr2',''); ?>
        <label><?php _e("Time To:",'revslider'); ?></label>
        <select id="hr2" name="hr2">
            <option value="none">Hr</option>
            <?php for($i = 1; $i <= 24; $i++):  $i = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                <option value="<?php echo $i; ?>"<?php if($hr2 == $i){ echo $selected = 'selected="selected"'; }?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <?php $min2 = $obj->getVal($slideParams, 'min2',''); ?>
        <select id="min2" name="min2">
            <option value="none">Min</option>
            <?php for($i = 0; $i < 60; $i++):  $i = str_pad($i, 2, "0", STR_PAD_LEFT);?>
                <option value="<?php echo $i; ?>"<?php if($min2 == $i){ echo $selected = 'selected="selected"'; }?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <span class="description"><?php _e("Set the end time.",'revslider'); ?></span>
    </p>
    <!--Custom field for Select Date for slide -->
    <p>
        <?php $date = $obj->getVal($slideParams, 'date',''); ?>
        <label><?php _e("Date:",'revslider'); ?></label>
        <input type="text" class="inputDatePicker" id="date" name="date" value="<?php echo $date; ?>">
        <span class="description"><?php _e("select date for displaying slides of slider.",'revslider'); ?></span>
    </p>

<?php
}
