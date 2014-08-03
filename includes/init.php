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
  /*wp_enqueue_script('jquery-mobile-touch', get_stylesheet_directory_uri() . '/assets/js/jquery.mobile.touch.js', array(
    'jquery'
  ) , false, true);
  wp_enqueue_script('next-prev-keyboard', get_stylesheet_directory_uri() . '/assets/js/next-previous-keyboard.js', array(
    'jquery-mobile-touch',
    'jquery'
  ) , false, true);*/
});

/**
 * Favicons
 */
 
add_action('wp_head', 'notnat_favicons');
add_action('admin_head', 'notnat_favicons');
function notnat_favicons(){
?>
  <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicon.ico">
  <link rel="icon" type="image/png" sizes="64x64" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/favicon.png">
  <link rel="apple-touch-icon" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon.png">
  <link rel="apple-touch-icon-precomposed" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-76x76-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-120x120-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-144x144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/apple-touch-icon-152x152-precomposed.png">
  <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/windows-tile-144x144.png">
  <meta name="msapplication-TileColor" content="#515151">
<?php
}

/**
 * Add support for custom header
 */

add_theme_support('custom-header', array(
  'admin-preview-callback' => function() {
    echo "<img src=\"" . get_header_image() . "\" />";
  }
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
  add_image_size('portfolio', 650, 9999 ); // 711 pixels wide and unlimited height
  
  // Name the new image sizes for the interface
  add_filter('image_size_names_choose', function($sizes) {
    return array_merge($sizes, array(
      'icon-menu' => __('Menu Icon'),
      'portfolio' => __('Portfolio'),
    ));
  });
  
}, 20); // higher priority to override primary_navigation being created by roots.io

/**
 * Remove unnecessary menu icon thumbnails from menu-image plugin
 */
add_filter('intermediate_image_sizes_advanced', function($sizes){

  unset( $sizes['menu-24x24']);
  unset( $sizes['menu-36x36']);
  unset( $sizes['menu-48x48']);
 
  return $sizes;
  
});
