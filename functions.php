<?php
// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/vendor/autoload.php';


spl_autoload_register(function ($class) {
    $base_dir = __DIR__ . '/inc/';
    $class = str_replace('\\', '/', $class);
    $file = $base_dir . $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Verifica se a classe Theme existe antes de instanciar
if (class_exists('Theme')) {
    new Theme();
} else {
    error_log("Erro: Classe Theme não encontrada!");
}

// Inicializa o tema
new Theme();

// esconde o painel admin
add_filter('show_admin_bar', '__return_false');

function filtrar_songs_em_tags( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_tag() ) {
        $query->set( 'post_type', 'song' );
        $query->set( 'posts_per_page', 1 );
    }
}
add_action( 'pre_get_posts', 'filtrar_songs_em_tags' );








