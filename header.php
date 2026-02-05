<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">
  <div class="container">

    <div class="site-branding">
      <a href="<?php echo home_url(); ?>" class="logo">
        <?php bloginfo('name'); ?>
      </a>
    </div>

    <nav class="main-nav">
      <?php
        wp_nav_menu([
          'theme_location' => 'menu-principal',
          'container' => false,
          'menu_class' => 'menu'
        ]);
      ?>
    </nav>

  </div>
</header>
