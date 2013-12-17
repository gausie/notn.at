<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
     
    <div class="logo">
      <a href="<?php echo home_url(); ?>/">
        <img class="site-logo" src="<?php header_image(); ?>" />
        <h1 style="display:none;"><?php bloginfo('name'); ?></h1>
      </a>
    </div>
  
    <nav class="navbar navbar-default" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="div.menu-social-media">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
    
      <div class="collapse navbar-collapse menu-social-media">
        <?php
          if (has_nav_menu('social_media')) :
            wp_nav_menu(array('theme_location' => 'social_media', 'menu_class' => 'nav navbar-nav'));
          endif;
        ?>
      </div>
    </nav>

    <nav class="navbar navbar-default" role="navigation">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="div.menu-categories">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
    
      <div class="collapse navbar-collapse menu-categories">
        <?php
          if (has_nav_menu('categories')) :
            wp_nav_menu(array('theme_location' => 'categories', 'menu_class' => 'nav navbar-nav'));
          endif;
        ?>
      </nav>
    </nav>
  </div>
</header>
