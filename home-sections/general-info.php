 <section  class="general-info">
    <?php
      $infoPost = new WP_Query([
        'post_type' => 'texto',
        'name' => 'texto-costa-rica'
      ]);
    
      if($infoPost -> have_posts()) :
        while ($infoPost->have_posts()) : $infoPost->the_post();
        $img = get_field('imagen');
        $titulo = get_field('titulo');
        $contenido = get_field('contenido');
    ?>
    <div class="general-info-costaRica">
      <h2><?php echo esc_html($titulo); ?></h2>
        <p><?php echo esc_html($contenido); ?></p>
    </div>
    <div class="general-info-minicard">

        <div class="general-info-img"
            style="background-image: url('<?php echo esc_url($img); ?>')">
        </div>

        <div class = "general-info-second">
          <div class = "general-info-second-card">
            <h2>+2 MILLIONS</h2>
            <p>Many people fjdisofjiso fjndsofl paradise</p>
          </div>
          <button class="button">
            <span class="button-content">Contact us >></span>
          </button>
        </div>
    </div>

    <?php
        endwhile;
        wp_reset_postdata();
      endif;
    ?>
</section>