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

      <?php
      // URL correcta según idioma actual
      $home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
      ?>

      <a href="<?php echo esc_url($home_url); ?>" class="logo">

        <div class="logo-content">

          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/boskoa-logo.png"
               alt="<?php bloginfo('name'); ?>"
               class="logo-image"
               style="height:50px;">

          <div>

            <!-- Nombre traducible -->
            <span><?php pll_e('Boskoa Travel'); ?></span>

            <div class="tiquicia">

              <div class="logo-divider">
                <svg width="90" height="28" viewBox="-10 -10 200 80"
                     xmlns="http://www.w3.org/2000/svg">
                  <path
                    class="spiral-path"
                    d="M13.109 29.63C9.07 6.576 45.923 6.913 39.498 34.027 33.215 57.413-.858 58.069-6.2 32.85-9.535 7.479 7.051-9.915 46.596-1.501 109.531 13.812 147.35 36.05 175.65 0"
                    fill="none"
                    stroke="#846E59"
                    stroke-width="7"
                  />
                </svg>
              </div>

              <!-- Texto traducible -->
              <span class="subtitle"><?php pll_e('Costa Rica'); ?></span>

            </div>

          </div>

        </div>

      </a>

    </div>


    <!-- ================= MENU TOGGLE ================= -->
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


      <!-- ================= LANGUAGE SWITCHER MOBILE ================= -->

      <div class="mobile-lang">

        <?php
        if (function_exists('pll_the_languages')) {

          $languages = pll_the_languages([
            'raw' => 1
          ]);

          foreach ($languages as $lang) {

            $flag = ($lang['slug'] === 'es') ? 'es-cr.png' : 'en-usa.png';
            ?>

            <a href="<?php echo esc_url($lang['url']); ?>">

              <?php echo strtoupper($lang['slug']); ?>

              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $flag; ?>"
                   alt="<?php echo esc_attr($lang['name']); ?>">

            </a>

            <?php
          }
        }
        ?>

      </div>

    </nav>


    <!-- ================= LANGUAGE SWITCHER DESKTOP ================= -->

    <?php
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'en';

    $current_flag = ($current_lang === 'es')
      ? 'es-cr.png'
      : 'en-usa.png';
    ?>


    <div class="lang-switcher">

      <button class="lang-toggle" aria-label="<?php pll_e('Change language'); ?>">

        <span class="lang-text">
          <?php echo strtoupper($current_lang); ?>
        </span>

        <img class="lang-flag"
             src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $current_flag; ?>"
             alt="<?php echo strtoupper($current_lang); ?>">

      </button>


      <ul class="lang-dropdown">

        <?php
        if (function_exists('pll_the_languages')) {

          $languages = pll_the_languages([
            'raw' => 1
          ]);

          foreach ($languages as $lang) {

            $flag = ($lang['slug'] === 'es')
              ? 'es-cr.png'
              : 'en-usa.png';
            ?>

            <li>

              <a href="<?php echo esc_url($lang['url']); ?>">

                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $flag; ?>"
                     alt="<?php echo esc_attr($lang['name']); ?>">

                <span>
                  <?php echo strtoupper($lang['slug']); ?>
                </span>

              </a>

            </li>

            <?php
          }
        }
        ?>

      </ul>

    </div>


  </div>
</header>
