<?php /*  style="height:<?php echo get_option('thumbnail_size_h'); ?>px" */ ?>
<article <?php post_class(); ?>>
  <a href="<?php the_permalink(); ?>">
    <?php 
      if ( has_post_thumbnail() ) { 
        the_post_thumbnail('thumbnail');
      }
    ?>
  </a>
  <span class="caption">  
    <p><?php the_title(); ?></p>  
  </span>  
</article>
