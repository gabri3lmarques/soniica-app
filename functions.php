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







