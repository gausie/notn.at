<?php
/**
 * 
 * Theme initialisation
 * 
 */

/**
 * Enqueue scripts
 */
add_action('wp_enqueue_scripts', function() {
  wp_enqueue_style('notnat', get_stylesheet_uri() , false, null);
  wp_enqueue_script('jquery-mobile-touch', get_stylesheet_directory_uri() . '/assets/js/jquery.mobile.touch.js', array(
    'jquery'
  ) , false, true);
  wp_enqueue_script('next-prev-keyboard', get_stylesheet_directory_uri() . '/assets/js/next-previous-keyboard.js', array(
    'jquery-mobile-touch',
    'jquery'
  ) , false, true);
});

/**
 * Add support for custom header
 */
function notnat_header_image() {
  echo "<img src=\"" . get_header_image() . "\" />";
}
add_theme_support('custom-header', array(
  'admin-preview-callback' => 'notnat_header_image',
));

/**
 * A bunch of stuff for setting up the theme
 */
add_action('after_setup_theme', function() {
  // Add theme support for post thumbnail
  add_theme_support('post-thumbnails');

  // Register menus
  register_nav_menus(array(
    'social_media' => 'Social Media',
    'categories' => 'Featured Categories'
  ));
  
  // And get rid of primary menu
  unregister_nav_menu('primary_navigation'); 
  
  // Register image size for icon menu
  add_image_size('icon-menu', 9999, 48 ); // 48 pixels high and unlimited width
  
  // Register image size for portfolio images
  add_image_size('portfolio-711', 711, 9999 ); // 711 pixels wide and unlimited height
  
}, 20); // higher priority to override primary_navigation being created by roots.io
