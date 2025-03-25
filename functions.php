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


// controla downloads

use download\DownloadController;
use user\Users;

add_action('rest_api_init', function () {
    register_rest_route('soniica/v1', '/register-download', [
        'methods' => 'POST',
        'callback' => function ($request) {
            if (!is_user_logged_in()) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => 'Você precisa estar logado.'
                ], 401);
            }

            $download_check = DownloadController::canDownload();

            if (!$download_check['allowed']) {
                return new WP_REST_Response([
                    'success' => false,
                    'message' => $download_check['message']
                ], 403);
            }

            $is_premium = Users::check_user_premium_status();

            if (!$is_premium) {
                DownloadController::registerDownload();
            }

            return new WP_REST_Response([
                'success' => true,
                'message' => 'Download permitido',
                'is_premium' => $is_premium
            ], 200);
        },
        'permission_callback' => '__return_true'
    ]);
});

// musicas novas
// adiciona a tag new
function soniica_schedule_life_cycle_update($post_id, $post) {
    if ($post->post_type !== 'song') {
        return;
    }

    // Verifica se o ACF 'life_cycle' já está definido como "new" antes de agendar
    $life_cycle = get_field('life_cycle', $post_id);
    if ($life_cycle !== 'new') {
        return;
    }

    // Verifica se já existe um evento agendado para este post
    if (!wp_next_scheduled('soniica_update_life_cycle', array($post_id))) {
        // Agenda o evento para 24 horas após a criação
        wp_schedule_single_event(time() + 600, 'soniica_update_life_cycle', array($post_id)); // 86400 segundos = 24 horas
    }
}
add_action('wp_insert_post', 'soniica_schedule_life_cycle_update', 10, 2);

// Cancela o agendamento do evento se o post for deletado
function soniica_cancel_life_cycle_update($post_id) {
    if (get_post_type($post_id) !== 'song') {
        return;
    }

    // Cancela o evento agendado para este post, se existir
    $timestamp = wp_next_scheduled('soniica_update_life_cycle', array($post_id));
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'soniica_update_life_cycle', array($post_id));
    }
}
add_action('before_delete_post', 'soniica_cancel_life_cycle_update');

// Atualiza o campo 'life_cycle' para 'old' após 24 horas
function soniica_update_life_cycle($post_id) {
    update_field('life_cycle', 'old', $post_id);
}
add_action('soniica_update_life_cycle', 'soniica_update_life_cycle', 10, 1);




