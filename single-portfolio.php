<?php
/*
Template Name: Portfolio piece single
*/

while (have_posts()) : the_post(); 

  // Prepare query arguments in same custom taxonomy
  $postlist_args = array(
     'posts_per_page'  => -1,
     'orderby'         => 'menu_order title',
     'order'           => 'ASC',
     'post_type'       => 'portfolio',
  ); 
  
  // use taxonomy term in session if it exists
  $wp_session = WP_Session::get_instance(); 
  if(!empty($wp_session['portfolio_category'])){
    if(has_term($wp_session['portfolio_category'],'portfolio_category')){
      $postlist_args['portfolio_category'] = $wp_session['portfolio_category'];
    }else{
      $wp_session['portfolio_category'] = '';
    }
  }
  
  // Get posts with prepared query arguments
  $postlist = get_posts( $postlist_args );

  // Get ids of posts retrieved from get_posts
  $ids = array();
  foreach ($postlist as $thepost) {
     $ids[] = $thepost->ID;
  }

  // Get previous and next posts in this taxonomy       
  $thisindex = array_search($post->ID, $ids);
  $previd = $ids[$thisindex-1];
  $nextid = $ids[$thisindex+1];

?>
  <?php
    if(!empty($previd)) echo '<a class="previous-post" href="' . get_permalink($previd) . '">&larr;</a>';
  ?>
  <?php
    if(!empty($nextid)) echo '<a class="next-post" href="' . get_permalink($nextid) . '">&rarr;</a>';
  ?> 
  <article <?php post_class(); ?>>
    <header>
      <h1 class="entry-title"><?php the_title(); ?></h1>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
  </article>
<?php endwhile; ?>
