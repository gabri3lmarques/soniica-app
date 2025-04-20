<?php
if (is_category()) {
    $category = get_queried_object();         // Obtém a categoria atual
    $ancestors = get_ancestors($category->term_id, 'category'); // Lista de ancestrais da categoria atual
    //pega o id correto da categoria dinamicamente
    $artist_category = get_term_by('slug', 'artist', 'category');
    $artist_category_id = $artist_category ? $artist_category->term_id : null;
    // ID da categoria "Artist" (substitua pelo ID correto)
    //$artist_category_id = 16;
    // Verifica se a categoria atual é filha direta de "Artist"
    if ($category->parent === $artist_category_id) {
        include(TEMPLATEPATH . '/category-artist.php');
        exit(); // Interrompe o restante do código
    }
    // Verifica se a categoria atual é neta de "Artist" (filho do filho)
    if (in_array($artist_category_id, $ancestors)) {
        // Verifica se é neto direto (dois níveis de ancestrais)
        if (count($ancestors) >= 2 && $ancestors[1] == $artist_category_id) {
            include(TEMPLATEPATH . '/category-artist-album.php');
            exit();
        }
    }
}
?>
