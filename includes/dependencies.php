<?php

/**
 * Declares plugin dependencies
 */
 
require_once 'TGM-Plugin-Activation/tgm-plugin-activation/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', function() {
  $plugins = array(
    array(
      'name' => 'Infinite Scroll',
      'slug' => 'infinite-scroll',
      'required' => false,
    ),
    array(
      'name' => 'Menu Image',
      'slug' => 'menu-image',
      'required' => false,
    ),
    array(
      'name' => 'Portfolio Post Type',
      'slug' => 'portfolio-post-type',
      'required' => true,
    ),
    array(
      'name' => 'Simple Custom Post Order',
      'slug' => 'simple-custom-post-order',
      'required' => false,
    ),
    array(
      'name' => 'WP Session Manager',
      'slug' => 'wp-session-manager',
      'required' => true,
    ),
  );
  tgmpa( $plugins );
});
