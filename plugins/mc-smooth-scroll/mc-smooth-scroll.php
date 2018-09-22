<?php
/*Plugin Name: MC Smooth Scroll
Description: Smooth Scroll JS sur liens internes
Version: 1.0
License: GPLv2
Author: Magalie Castaing
*/

function mc_load_smooth_scroll_scripts() {
 
    wp_enqueue_script( 'smooth-scroll', plugins_url( 'mc-smooth-scroll.js', __FILE__ ), array('jquery'), true);
 
}
add_action('wp_enqueue_scripts', 'mc_load_smooth_scroll_scripts');