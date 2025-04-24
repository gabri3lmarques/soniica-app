<?php
if ( is_category() ) {
    // Recupera o objeto da categoria atual
    $current_category = get_queried_object();
    if ( ! empty( $current_category ) && ! is_wp_error( $current_category ) ) {
        // Obtém o ID da imagem salva como meta da categoria
        $image_id = get_term_meta( $current_category->term_id, 'category-image', true );
        $image_url = $image_id ? wp_get_attachment_url( $image_id ) : '';
    }
    echo($current_category->name);
    echo($image_url);
    // Configura os argumentos para a query
    $args = array(
        'post_type'      => 'song', // Tipo de post customizado
        'posts_per_page' => -1,     // Número de posts (-1 para exibir todos)
        'tax_query'      => array(
            array(
                'taxonomy' => 'category',              // Taxonomia padrão de categorias
                'field'    => 'term_id',
                'terms'    => $current_category->term_id, // Filtra pela categoria atual
            ),
        ),
    );
    // Realiza a query personalizada
    $song_query = new WP_Query( $args );
    // Verifica se há posts para exibir
    if ( $song_query->have_posts() ) {
        echo '<ul>';
        while ( $song_query->have_posts() ) {
            $song_query->the_post();
            ?>
            <li>
                <a href="<?php the_permalink(); ?>">
                    <?php the_title(); ?>
                </a>
            </li>
            <?php
        }
        echo '</ul>';
    } else {
        echo '<p>Nenhum post do tipo "song" encontrado nesta categoria.</p>';
    }
    // Restaura os dados originais do post
    wp_reset_postdata();
}
?>

