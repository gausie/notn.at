<?php
/**
 * 
 * Configure portfolio-post-type
 * 
 */

/**
 * Front page should show portfolio items
 */
add_action('pre_get_posts', function($query) {
  if ($query->is_main_query() && is_home()) {
    $query->set('post_type', 'portfolio');
  }
});

/**
 * Change portfolio category slug
 */
add_filter('portfolioposttype_category_args', function($args){  
  $labels = array(
      'name'               => __( 'Portfolio', 'portfolioposttype' ),
      'singular_name'      => __( 'Piece', 'portfolioposttype' ),
      'add_new'            => __( 'Add New Piece', 'portfolioposttype' ),
      'add_new_item'       => __( 'Add New Piece', 'portfolioposttype' ),
      'edit_item'          => __( 'Edit Piece', 'portfolioposttype' ),
      'new_item'           => __( 'Add New Piece', 'portfolioposttype' ),
      'view_item'          => __( 'View Piece', 'portfolioposttype' ),
      'search_items'       => __( 'Search Portfolio', 'portfolioposttype' ),
      'not_found'          => __( 'No pieces found', 'portfolioposttype' ),
      'not_found_in_trash' => __( 'No pieces found in trash', 'portfolioposttype' ),
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
