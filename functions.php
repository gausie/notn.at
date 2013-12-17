<?php
/**
 * Notnat Functions
 * 
 * Adds hooks and configures Wordpress (and roots.io) to work for this theme
 * 
 */
 
 
/**
 * Theme initiation
 */
 
  // Enqueue scripts
  function theme_name_scripts() {
    wp_enqueue_style('notnat', get_stylesheet_uri() , false, null);
    wp_enqueue_script('next-prev-keyboard', get_stylesheet_directory_uri() . '/assets/js/next-previous-keyboard.js', array(
      'jquery'
    ) , false, true);
  }
  add_action('wp_enqueue_scripts', 'theme_name_scripts');

  // Add support for custom header
  function notnat_header_image() {
    echo "<img src=\"" . get_header_image() . "\" />";
  }
  add_theme_support('custom-header', array(
    'admin-preview-callback' => 'notnat_header_image',
  ));

  // A bunch of other stuff to do after setting up the theme
  function notnat_setup() {
    // Add theme support for post thumbnail
    add_theme_support('post-thumbnails');

    // Register menus
    register_nav_menus(array(
      'social_media' => 'Social Media',
      'categories' => 'Featured Categories'
    ));
    
    // And get rid of primary menu
    unregister_nav_menu('primary_navigation'); 
  }
  add_action('after_setup_theme', 'notnat_setup', 20); // higher priority to override primary_navigation being created by roots.io

/**
 * Configure portfolio-post-type plugin
 */

  // Front page should show portfolio items
  function portfolio_on_front_page($query) {
    if ($query->is_main_query() && is_home()) {
      $query->set('post_type', 'portfolio');
    }
  }
  add_action('pre_get_posts', 'portfolio_on_front_page');

  // Change portfolio category slug
  function change_portfolio_category_slug($args){
    $args['rewrite'] = array(
      'slug' => 'media'
    );
    return $args;
  }
  add_filter('portfolioposttype_category_args', 'change_portfolio_category_slug');

  // Remove slug from portfolio post type
  function remove_portfolio_slug($post_link, $post, $leavename){
    if (!in_array($post->post_type, array(
      'portfolio'
    )) || 'publish' != $post->post_status) return $post_link;
    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
    return $post_link;
  }
  add_filter('post_type_link', 'remove_portfolio_slug', 10, 3);

  // I don't really know what this is for but http://vip.wordpress.com/documentation/remove-the-slug-from-your-custom-post-type-permalinks/ insists it's important
  function parse_request_tricksy($query){
    
    // Only noop the main query
    if (!$query->is_main_query()) return;

    // Only noop our very specific rewrite rule match
    if (2 != count($query->query) || !isset($query->query['page'])) return;

    // 'name' will be set if post permalinks are just post_name, otherwise the page rule will match
    if (!empty($query->query['name'])) $query->set('post_type', array(
      'post',
      'portfolio',
      'page'
    ));
  }
  add_action('pre_get_posts', 'parse_request_tricksy');

/**
 * Functionality
 */

  // Set session for portfolio category navigation
  function set_category_session($query){
    if (!is_admin() && $query->is_main_query() && (is_home() || is_tax('portfolio_category'))) {
      $wp_session = WP_Session::get_instance();
      $post_type = $query->get('post_type');
      $portfolio_category = $query->get('portfolio_category');
      if ($post_type == 'portfolio' && empty($portfolio_category)) {
        $wp_session['portfolio_category'] = '';
      }
      elseif (!empty($portfolio_category)) {
        $wp_session['portfolio_category'] = $portfolio_category;
      }
    }
  }
  add_action('pre_get_posts', 'set_category_session');

  // Make single posts identifiable to CSS
  function add_class_to_single_post($classes) {
    if (is_single()) {
      array_push($classes, 'single-post');
    }

    return $classes;
  }
  add_filter('post_class', 'add_class_to_single_post');
  
?>
