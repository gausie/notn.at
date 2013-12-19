<?php
/**
 * Notnat Functions
 * 
 * Adds hooks and configures Wordpress (and roots.io) to work for this theme
 * 
 */

/**
 * Plugin dependencies
 */
 
  require_once 'includes/TGM-Plugin-Activation/tgm-plugin-activation/class-tgm-plugin-activation.php';
  
  function notnat_register_dependencies() {
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
  }
  add_action( 'tgmpa_register', 'notnat_register_dependencies' );
 
/**
 * Theme initiation
 */
 
  // Enqueue scripts
  function notnat_scripts() {
    wp_enqueue_style('notnat', get_stylesheet_uri() , false, null);
    wp_enqueue_script('jquery-mobile-touch', get_stylesheet_directory_uri() . '/assets/js/jquery.mobile.touch.js', array(
      'jquery'
    ) , false, true);
    wp_enqueue_script('next-prev-keyboard', get_stylesheet_directory_uri() . '/assets/js/next-previous-keyboard.js', array(
      'jquery-mobile-touch',
      'jquery'
    ) , false, true);
  }
  add_action('wp_enqueue_scripts', 'notnat_scripts');

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
    
    // Register image size for icon menu
    add_image_size('icon-menu', 9999, 48 ); // 48 pixels high and unlimited width
    
    // Register image size for portfolio images
    add_image_size('portfolio-711', 711, 9999 ); // 711 pixels wide and unlimited height
    
  }
  add_action('after_setup_theme', 'notnat_setup', 20); // higher priority to override primary_navigation being created by roots.io

/**
 * Configure portfolio-post-type plugin
 */

  // Front page should show portfolio items
  function notnat_portfolio_on_front_page($query) {
    if ($query->is_main_query() && is_home()) {
      $query->set('post_type', 'portfolio');
    }
  }
  add_action('pre_get_posts', 'notnat_portfolio_on_front_page');

  // Change portfolio category slug
  function notnat_change_portfolio_category_slug($args){
    $args['rewrite'] = array(
      'slug' => 'media'
    );
    return $args;
  }
  add_filter('portfolioposttype_category_args', 'notnat_change_portfolio_category_slug');

  // Remove slug from portfolio post type
  function notnat_remove_portfolio_slug($post_link, $post, $leavename){
    if (!in_array($post->post_type, array(
      'portfolio'
    )) || 'publish' != $post->post_status) return $post_link;
    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
    return $post_link;
  }
  add_filter('post_type_link', 'notnat_remove_portfolio_slug', 10, 3);

  // I don't really know what this is for but http://vip.wordpress.com/documentation/remove-the-slug-from-your-custom-post-type-permalinks/ insists it's important
  function notnat_parse_request_tricksy($query){
    
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
  add_action('pre_get_posts', 'notnat_parse_request_tricksy');

/**
 * Functionality
 */

  // Set session for portfolio category navigation
  if(class_exists("WP_Session")){
    function notnat_set_category_session($query){
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
    add_action('pre_get_posts', 'notnat_set_category_session');
  }
  
  // Only show the portfolio category currently being traversed as active
  function notnat_highlight_session_category_only( $classes, $item ){
    if(is_single() && ($item->object == "portfolio_category" || $item->post_name == "everything") ){
      $wp_session = WP_Session::get_instance();
      $slug = sanitize_title($item->title);
      // If there is no session, then we can highlight whatever menu item has the title "everything"
      if(empty($wp_session['portfolio_category']) && $item->post_name == "everything"){
        $classes[] = "current-page-parent";
      // Otherwise, we will remove the highlight from any menu item that is not currently being traversed
      }elseif($wp_session['portfolio_category'] != $slug){
        $classes = preg_replace('/(current(-menu-|[-_]page[-_])(item|parent|ancestor))/', '', $classes);
      }
    }
    return $classes;
  }
  add_filter( 'nav_menu_css_class', 'notnat_highlight_session_category_only', 9, 2 );
  
  // Make single posts identifiable to CSS
  function notnat_add_class_to_single_post($classes) {
    if (is_single()) {
      array_push($classes, 'single-post');
    }

    return $classes;
  }
  add_filter('post_class', 'notnat_add_class_to_single_post');
  
  // Rename uploaded images to add "-original" so we can stop users seeing them
  function notnat_add_or_remove_original_where_appropriate($id) {
      // determine if attachment has portfolio as a parent
      $attachment = get_post($id);
      $parent_id = $attachment->post_parent;
      $parent_type = get_post_type($parent_id);
      // prep attached file data
      $file = get_attached_file($id);
      $path = pathinfo($file);
      $filename = $path['filename'];
      $has_original = preg_match("/(-original)$/",$filename);
      if(!$parent_type || $parent_type != "portfolio"){
        // parent is not a porfolio -remove -original if present
        if($has_original){
          $filename = substr($filename,0,-9);
        }
      }else{
        // parent is a portfolio - add -original if not present
        if(!$has_original){
          $filename .= "-original";
        }
      }
      // rename if necessary
      if($filename != $path['filename']){
          $new_file = $path['dirname']."/".$filename.".".$path['extension'];
          rename($file,$new_file);
          update_attached_file($id,$new_file);
      }
  }
  add_action('add_attachment', 'notnat_add_or_remove_original_where_appropriate');
  add_action('edit_attachment', 'notnat_add_or_remove_original_where_appropriate');
  
?>
