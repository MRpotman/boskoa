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
      $home_url = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
      ?>

      <a href="<?php echo esc_url($home_url); ?>" class="logo">
        <div class="logo-content">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/boskoa-logo.png"
               alt="<?php bloginfo('name'); ?>"
               class="logo-image"
               style="height:50px;">
          <div>
            <span><?php pll_e('Boskoa Travel'); ?></span>
            <div class="tiquicia">
              <div class="logo-divider">
                <svg width="90" height="28" viewBox="-10 -10 200 80" xmlns="http://www.w3.org/2000/svg">
                  <path class="spiral-path"
                    d="M13.109 29.63C9.07 6.576 45.923 6.913 39.498 34.027 33.215 57.413-.858 58.069-6.2 32.85-9.535 7.479 7.051-9.915 46.596-1.501 109.531 13.812 147.35 36.05 175.65 0"
                    fill="none" stroke="#846E59" stroke-width="7"/>
                </svg>
              </div>
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

    <nav class="main-nav">
      <?php
      wp_nav_menu([
        'theme_location' => 'menu-principal',
        'container'      => false,
        'menu_class'     => 'menu'
      ]);
      ?>
      <div class="mobile-lang">
        <?php
        if (function_exists('pll_the_languages')) {
          $languages = pll_the_languages(['raw' => 1]);
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

    <!-- ================= ACTIONS (carrito + idioma) ================= -->
    <?php
    $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
    $current_flag = ($current_lang === 'es') ? 'es-cr.png' : 'en-usa.png';
    ?>

    <div class="header-actions">

      <!-- Carrito -->
      <button class="cart-toggle" type="button" id="cart-toggle-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="9" cy="21" r="1"/>
          <circle cx="20" cy="21" r="1"/>
          <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
        </svg>
        <?php if (function_exists('WC')) : ?>
          <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        <?php endif; ?>
      </button>

      <!-- Idioma -->
      <div class="lang-switcher">
        <button class="lang-toggle" aria-label="<?php pll_e('Change language'); ?>">
          <span class="lang-text"><?php echo strtoupper($current_lang); ?></span>
          <img class="lang-flag"
               src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $current_flag; ?>"
               alt="<?php echo strtoupper($current_lang); ?>">
        </button>
        <ul class="lang-dropdown">
          <?php
          if (function_exists('pll_the_languages')) {
            $languages = pll_the_languages(['raw' => 1]);
            foreach ($languages as $lang) {
              $flag = ($lang['slug'] === 'es') ? 'es-cr.png' : 'en-usa.png';
              ?>
              <li>
                <a href="<?php echo esc_url($lang['url']); ?>">
                  <img src="<?php echo get_template_directory_uri(); ?>/assets/img/<?php echo $flag; ?>"
                       alt="<?php echo esc_attr($lang['name']); ?>">
                  <span><?php echo strtoupper($lang['slug']); ?></span>
                </a>
              </li>
              <?php
            }
          }
          ?>
        </ul>
      </div>

    </div><!-- /.header-actions -->

  </div>
</header>

<!-- ================= CART SIDEBAR ================= -->
<div class="cart-overlay" id="cart-overlay"></div>

<div class="cart-sidebar" id="cart-sidebar">
  <div class="cart-sidebar__header">
    <h5 class="cart-sidebar__title"><?php pll_e('Your cart'); ?></h5>
    <button class="cart-sidebar__close" id="cart-close-btn">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
           stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
  </div>
  <div class="cart-sidebar__body">
    <?php if (function_exists('WC')) : ?>
      <?php woocommerce_mini_cart(); ?>
    <?php else : ?>
      <p><?php pll_e('Your cart is empty'); ?></p>
    <?php endif; ?>
  </div>
</div>

<!-- ================= CART CHECKOUT MODAL ================= -->
<div id="cart-checkout-modal" class="booking-modal" style="display:none;">
    <div class="booking-modal-content">
        <span class="cart-checkout-modal-close booking-modal-close">&times;</span>
        <h2 class="booking-modal-title"><?php pll_e('Complete Your Booking'); ?></h2>

        <!-- Resumen del carrito -->
        <div id="cart-modal-summary" class="cart-modal-summary">
            <!-- Se rellena dinámicamente con JS -->
        </div>

        <form id="cart-checkout-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="boskoa_cart_checkout">
            <input type="hidden" name="contact_nonce" value="<?php echo wp_create_nonce('boskoa_contact_form'); ?>">
            <input type="hidden" name="cart_items" id="cart-items-input" value="">
            <input type="hidden" name="recaptcha_token" id="cartRecaptchaToken">

            <div class="booking-form-group">
                <label for="cart_contact_name"><?php pll_e('Name'); ?> *</label>
                <input type="text" id="cart_contact_name" name="contact_name" required placeholder="<?php pll_e('Your full name'); ?>">
            </div>

            <div class="booking-form-group">
                <label for="cart_contact_email"><?php pll_e('Email'); ?> *</label>
                <input type="email" id="cart_contact_email" name="contact_email" required placeholder="your@email.com">
            </div>
            
            <div class="booking-form-group">
                <label for="cart_contact_phone"><?php pll_e('Phone (optional)'); ?></label>
                <input type="tel" id="cart_contact_phone" name="contact_phone">
                <input type="hidden" id="cart_contact_phone_full" name="contact_phone_full">
            </div>

            <div class="booking-form-group">
                <label for="cart_contact_message"><?php pll_e('Message'); ?> *</label>
                <textarea id="cart_contact_message" name="contact_message" rows="4" required
                    placeholder="<?php pll_e('I would like to book these activities...'); ?>"></textarea>
            </div>

            <button type="submit" class="booking-submit-btn cart-checkout-submit-btn">
                <?php pll_e('Send Booking Request'); ?>
            </button>
        </form>
    </div>
</div>