<!DOCTYPE html>
<html <?php language_attributes(); ?> style = "margin: 0px !important">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<div class="error404-container">

    <div class="error404-content">

        <h1 class="error404-title">404</h1>

        <p class="error404-text">
            <?php pll_e('Oops... this page got lost in the system.'); ?>
        </p>

        <a href="<?php echo home_url(); ?>" class="error404-btn">
            <?php pll_e('Back to home'); ?>
        </a>

    </div>


</div>



</html>