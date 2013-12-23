<?php

/**
* Additional functions
*/

/**
 * Set session for portfolio category navigation
 */
if(class_exists("WP_Session")){
  add_action('pre_get_posts', function($query){
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
  });
}

/**
 * Only show the portfolio category currently being traversed as active
 */
add_filter( 'nav_menu_css_class',function( $classes, $item ){
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
}, 9, 2 );

/** 
 * Rename uploaded images to add "-original" so we can stop users seeing them  
 * ( Obviously relying on $_REQUEST isn't great but messing )
 * ( around with add_attachment couldn't saved generated    )
 * ( thumbnails because at the end of media_handle_upload() )
 * ( it recalculates them to be blank without a hook.       ) 
 * */
add_filter( 'wp_handle_upload_prefilter', function($image){
  
    // prep attached file data
    $path = pathinfo($image['name']);
    $filename = $path['filename'];
    
    // determine if attachment has portfolio as a parent
    $parent_id = $_REQUEST['post_id'];
    $parent_type = get_post_type($parent_id);
    if($parent_type && $parent_type == "portfolio"){
      // parent is a portfolio - add -original if not present
      if(!$has_original = preg_match("/(-original)$/",$filename)){
        $image['name'] = $filename."-original.".$path['extension'];
      }
    }

    return $image;

});
