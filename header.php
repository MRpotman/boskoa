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

    <!-- Logo -->
    <div class="site-branding">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
         <span>Boskoa Travel</span>
        <span class="subtitle">Costa Rica</span>
      </a>
    </div>

    <!-- Menú principal -->
    <nav class="main-nav">
      <?php
        wp_nav_menu([
          'theme_location' => 'menu-principal',
          'container' => false,
          'menu_class' => 'menu'
        ]);
      ?>
    
    </nav>

    <!-- Idioma -->
    <div class="lang-switcher">
      <a href="#" class="lang">EN</a>
    </div>

  </div>
</header>

