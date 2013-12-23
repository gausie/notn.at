<?php
/**
 * 
 * Configure portfolio-post-type
 * 
 */

/**
 * Front page and feed should show portfolio items
 */
add_action('pre_get_posts', function($query) {
  if (($query->is_main_query() && is_home()) || is_feed()) {
    $query->set('post_type', 'portfolio');
  }
});

/**
 * Change portfolio language
 */
add_filter('portfolioposttype_args', function($args){  
  $labels = array(
      'name'               => __( 'Portfolio', 'portfolio-post-type' ),
      'singular_name'      => __( 'Piece', 'portfolio-post-type' ),
      'add_new'            => __( 'Add New Piece', 'portfolio-post-type' ),
      'add_new_item'       => __( 'Add New Piece', 'portfolio-post-type' ),
      'edit_item'          => __( 'Edit Piece', 'portfolio-post-type' ),
      'new_item'           => __( 'Add New Piece', 'portfolio-post-type' ),
      'view_item'          => __( 'View Piece', 'portfolio-post-type' ),
      'search_items'       => __( 'Search Portfolio', 'portfolio-post-type' ),
      'not_found'          => __( 'No pieces found', 'portfolio-post-type' ),
      'not_found_in_trash' => __( 'No pieces found in trash', 'portfolio-post-type' ),
  );
  $args['labels'] = $labels;

  return $args;
});

/**
 * Change portfolio category language and slug
 */
add_filter('portfolioposttype_category_args', function($args){  
  $labels = array(
    'name'                       => __( 'Portfolio Media', 'portfolio-post-type' ),
    'singular_name'              => __( 'Portfolio Medium', 'portfolio-post-type' ),
    'menu_name'                  => __( 'Portfolio Media', 'portfolio-post-type' ),
    'edit_item'                  => __( 'Edit Portfolio Medium', 'portfolio-post-type' ),
    'update_item'                => __( 'Update Portfolio Medium', 'portfolio-post-type' ),
    'add_new_item'               => __( 'Add New Portfolio Medium', 'portfolio-post-type' ),
    'new_item_name'              => __( 'New Portfolio Medium Name', 'portfolio-post-type' ),
    'parent_item'                => __( 'Parent Portfolio Medium', 'portfolio-post-type' ),
    'parent_item_colon'          => __( 'Parent Portfolio Medium:', 'portfolio-post-type' ),
    'all_items'                  => __( 'All Portfolio Media', 'portfolio-post-type' ),
    'search_items'               => __( 'Search Portfolio Media', 'portfolio-post-type' ),
    'popular_items'              => __( 'Popular Portfolio Media', 'portfolio-post-type' ),
    'separate_items_with_commas' => __( 'Separate portfolio media with commas', 'portfolio-post-type' ),
    'add_or_remove_items'        => __( 'Add or remove portfolio media', 'portfolio-post-type' ),
    'choose_from_most_used'      => __( 'Choose from the most used portfolio media', 'portfolio-post-type' ),
    'not_found'                  => __( 'No portfolio media found.', 'portfolio-post-type' ),
  );

  $args['labels'] = $labels;

  $args['rewrite'] = array(
    'slug' => 'media'
  );
  return $args;
});

/**
 * Remove slug from portfolio post type
 */
add_filter('post_type_link', function($post_link, $post, $leavename){
  if (!in_array($post->post_type, array(
    'portfolio'
  )) || 'publish' != $post->post_status) return $post_link;
  $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);
  return $post_link;
}, 10, 3);

/** 
 * I don't really know what this is for but 
 * http://vip.wordpress.com/documentation/remove-the-slug-from-your-custom-post-type-permalinks/ 
 * insists it's important
 */
add_action('pre_get_posts', function($query){
  
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
});

/**
 * Add to At A Glance
 */
add_action('dashboard_glance_items', function() {
  $portfolio_item_count = wp_count_posts( 'portfolio' );
  $formatted_number = number_format_i18n( $portfolio_item_count->publish );
  $text = _n( 'Piece', 'Pieces', intval($portfolio_item_count->publish) );
  echo "<li class='portfolio-count'><a href='edit.php?post_type=portfolio'>{$formatted_number} {$text}</a></li>";
});
