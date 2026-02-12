<?php
/**
 * Team Section para About Us
 * Versión simplificada: busca posts de tipo "texto" con slug pattern "team-member-*"
 */


// Buscar todos los miembros del equipo
// Opción 1: Por slug pattern "team-member-*"
$team_query = new WP_Query([
    'post_type' => 'texto',
    'posts_per_page' => -1,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
    'post_name__in' => [],
]);

// Mejor opción: Buscar todos los posts que empiecen con "team-member-"
// O usar meta_query para identificarlos
$team_query = new WP_Query([
    'post_type' => 'texto',
    'posts_per_page' => -1,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key' => 'es_miembro_equipo', 
            'value' => '1',
            'compare' => '='
        ),
        array(
            'key' => 'tipo_seccion',
            'value' => 'team-member',
            'compare' => '='
        )
    )
]);

// Si no hay resultados con meta_query, buscar por name pattern
if (!$team_query->have_posts()) {
    wp_reset_postdata();
    
    // Buscar posts que contengan "team-member" en el slug
    global $wpdb;
    $team_post_names = $wpdb->get_col(
        "SELECT post_name FROM {$wpdb->posts} 
         WHERE post_type = 'texto' 
         AND post_status = 'publish'
         AND post_name LIKE 'team-member-%'
         ORDER BY menu_order ASC, post_title ASC"
    );
    
    if (!empty($team_post_names)) {
        $team_query = new WP_Query([
            'post_type' => 'texto',
            'post_name__in' => $team_post_names,
            'posts_per_page' => -1,
            'orderby' => 'menu_order title',
            'order' => 'ASC',
        ]);
    }
}

if (!$team_query->have_posts()) {
    return; // No mostrar nada si no hay miembros
}
?>

<section class="about-team-section">
    <div class="container-about">
        <h2 class="about-team-title">The Team</h2>
        
        <div class="about-team-grid">
            <?php
            while ($team_query->have_posts()) {
                $team_query->the_post();
                
                // Obtener campos ACF
                $member_name = get_field('titulo') ?: get_field('Titulo') ?: get_the_title();
                $member_position = get_field('contenido') ?: get_field('Contenido') ?: get_field('cargo') ?: get_field('Cargo') ?: '';
                $member_image = get_field('imagen') ?: get_field('Imagen') ?: '';
                $member_bio = get_field('biografia') ?: get_field('Biografia') ?: '';
                
                // Procesar imagen
                $image_url = '';
                $image_alt = $member_name;
                
                if (!empty($member_image)) {
                    $image_url = is_array($member_image) ? $member_image['url'] : $member_image;
                    if (is_array($member_image) && !empty($member_image['alt'])) {
                        $image_alt = $member_image['alt'];
                    }
                } else {
                    // Imagen placeholder si no hay foto
                    $image_url = get_template_directory_uri() . '/assets/img/placeholder-team.jpg';
                }
                ?>
                
                <div class="about-team-member">
                    <div class="about-team-member-image">
                        <img 
                            src="<?php echo esc_url($image_url); ?>" 
                            alt="<?php echo esc_attr($image_alt); ?>"
                            loading="lazy"
                        />
                    </div>
                    <div class="about-team-member-info">
                        <h3 class="about-team-member-name"><?php echo esc_html($member_name); ?></h3>
                        <?php if (!empty($member_position)) : ?>
                            <p class="about-team-member-position"><?php echo esc_html($member_position); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($member_bio)) : ?>
                            <div class="about-team-member-bio">
                                <?php echo wp_kses_post($member_bio); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php } ?>
            <?php wp_reset_postdata(); ?>
        </div>
    </div>
</section>