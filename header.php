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

    <!-- ================= LOGO ================= -->
    <div class="site-branding">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="logo">
        <div class="logo-content">
          
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/boskoa-logo.png"
               alt="Boskoa Travel Logo"
               class="logo-image"
               style="height:50px;">

          <div>
            <span>Boskoa Travel</span>

            <div class="tiquicia">
              <div class="logo-divider">
                <svg width="90" height="28" viewBox="-10 -10 200 80"
                     xmlns="http://www.w3.org/2000/svg">
                  <path
                    class="spiral-path"
                    d="M13.109 29.63C9.07 6.576 45.923 6.913 39.498 34.027 33.215 57.413-.858 58.069-6.2 32.85-9.535 7.479 7.051-9.915 46.596-1.501 109.531 13.812 147.35 36.05 175.65 0"
                    fill="none"
                    stroke="#846E59"
                    stroke-width="4"
                  />
                </svg>
              </div>

              <span class="subtitle">Costa Rica</span>
            </div>
          </div>

        </div>
      </a>
    </div>

    <!-- ================= MENU TOGGLE (Mobile) ================= -->
    <!-- Move the checkbox out of the label so it can be a sibling of the nav -->
    <input type="checkbox" id="menu-checkbox" />
    <label class="menu-toggle" for="menu-checkbox">
      <div class="checkmark">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </label>

    <!-- ================= NAVIGATION ================= -->
    <nav class="main-nav">

      <?php
        wp_nav_menu([
          'theme_location' => 'menu-principal',
          'container' => false,
          'menu_class' => 'menu'
        ]);
      ?>

      <!-- ===== IDIOMA EN MOBILE ===== -->
      <div class="mobile-lang">
        <a href="/en/">EN <img src="<?php echo get_template_directory_uri(); ?>/assets/img/en-usa.png" alt="English"></a>
        <a href="/es/">ES  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/es-cr.png" alt="Español"></a>
      </div>

    </nav>

    <?php
    // Detectar idioma actual
    $current_lang = 'EN';
    $current_flag = 'en-usa.png';

    if (strpos($_SERVER['REQUEST_URI'], '/es/') !== false) {
        $current_lang = 'ES';
        $current_flag = 'es-cr.png';
    }
    ?>

    <!-- ================= IDIOMA DESKTOP ================= -->
    <div class="lang-switcher">
      <button class="lang-toggle" aria-label="Change language">
        <span class="lang-text"><?php echo $current_lang; ?></span>
        <img class="lang-flag"
             src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $current_flag; ?>"
             alt="<?php echo $current_lang; ?>">
      </button>

      <ul class="lang-dropdown">
        <li>
          <a href="/en/">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/en-usa.png" alt="English">
            <span>EN</span>
          </a>
        </li>
        <li>
          <a href="/es/">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/es-cr.png" alt="Español">
            <span>ES</span>
          </a>
        </li>
      </ul>
    </div>

  </div>
</header>
