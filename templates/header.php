<?php
  $menus = get_registered_nav_menus();
?>
<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
     
    <div class="logo">
      <a href="<?php echo home_url(); ?>/">
        <img class="site-logo" src="<?php header_image(); ?>" />
        <h1 style="display:none;"><?php bloginfo('name'); ?></h1>
      </a>
    </div>
 
    <?php
      if (has_nav_menu('social_media')) :
    ?>

    <nav class="navbar navbar-default" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="div.menu-social-media">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand visible-xs" href="#">Menu</a>
      </div>
    
      <div class="collapse navbar-collapse menu-social-media">
        <?php
            wp_nav_menu(array('theme_location' => 'social_media', 'menu_class' => 'nav navbar-nav'));
        ?>
      </div>
    </nav>

    <?php
      endif;
    ?>

    <?php
      if (has_nav_menu('categories')) :
    ?>

    <nav class="navbar navbar-default" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="div.menu-categories">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand visible-xs" href="#"><?php echo $menus['categories']; ?></a>
      </div>
    
      <div class="collapse navbar-collapse menu-categories">
        <?php
            wp_nav_menu(array('theme_location' => 'categories', 'menu_class' => 'nav navbar-nav'));
        ?>
      </nav>
    </nav>

    <?php
      endif;
    ?>

  </div>
</header>
