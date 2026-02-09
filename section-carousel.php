<?php
$label = get_sub_field('label');
$title = get_sub_field('title');
$button_text = get_sub_field('button_text');
$button_link = get_sub_field('button_link');
$button_color = get_sub_field('button_color');
$post_type = get_sub_field('post_type');
?>

<section class="packages-section">

  <div class="section-header">
    <span class="section-label"><?php echo esc_html($label); ?></span>
    <h2><?php echo esc_html($title); ?></h2>

    <a href="<?php echo esc_url($button_link); ?>"
       class="section-btn <?php echo esc_attr($button_color); ?>">
       <?php echo esc_html($button_text); ?>
    </a>
  </div>

  <div class="carousel">
    <?php
      $query = new WP_Query([
        'post_type' => $post_type,
        'posts_per_page' => -1
      ]);

      if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
          get_template_part('parts/card-homepage');
        endwhile;
        wp_reset_postdata();
      endif;
    ?>
  </div>

</section>
