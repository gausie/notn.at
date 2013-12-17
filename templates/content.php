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
<!--
  <header>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('templates/entry-meta'); ?>
  </header>
  <div class="entry-summary">
    <?php the_excerpt(); ?>
  </div>
-->
