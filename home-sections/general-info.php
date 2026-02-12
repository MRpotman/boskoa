<section class="general-info">

<?php
$post = get_page_by_path('texto-costa-rica', OBJECT, 'texto');

if ($post) :

    $img = get_field('imagen', $post->ID);
    $titulo = get_field('titulo', $post->ID);
    $contenido = get_field('contenido', $post->ID);
?>

    <div class="general-info-costaRica">
        <h2><?php echo esc_html($titulo); ?></h2>
        <p><?php echo esc_html($contenido); ?></p>
    </div>

    <div class="general-info-minicard">

        <div class="general-info-img"
            style="background-image: url('<?php echo esc_url($img); ?>')">
        </div>

        <div class="general-info-second">
            <div class="general-info-second-card">
                <h2>+2 MILLIONS</h2>
                <p>Many people visit this natural paradise</p>
            </div>

            <button class="button">
                <span class="button-content">Contact us >></span>
            </button>
        </div>

    </div>

<?php endif; ?>

</section>
