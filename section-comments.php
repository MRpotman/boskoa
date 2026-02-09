
<section class="comment-section">

<div class="comment-title">
    <h3>What our customer say!!</h3>
    </div>

<section class="comment-carousel">

<button class="comment-arrow prev">‹</button>
  
  <div class="comment-slides">
    <?php
      $comments = new WP_Query([
        'post_type' => 'review',
        'posts_per_page' => -1
      ]);

      if ($comments->have_posts()) :
        while ($comments->have_posts()) : $comments->the_post();
          $name   = get_field('name');
          $text   = get_field('comment_text');
          $stars  = get_field('stars');
    ?>
      <div class="comment-slide">
        <div class="card">
          <h3><?php echo esc_html($name); ?></h3>

          <div class="stars">
            <?php for ($i = 0; $i < $stars; $i++) : ?>
              <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
          </div>

          <p><?php echo esc_html($text); ?></p>
        </div>
      </div>
    <?php
        endwhile;
        wp_reset_postdata();
      endif;
    ?>
  </div>

 <button class="comment-arrow next">›</button>
      </div>
 

</section>
</div>
    </section>