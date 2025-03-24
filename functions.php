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


// Cryptografar a url

function soniica_register_stream_endpoint() {
    register_rest_route('soniica/v1', '/stream/', [
        'methods'  => 'GET',
        'callback' => 'soniica_stream_audio',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'soniica_register_stream_endpoint');

function soniica_stream_audio(WP_REST_Request $request) {
    $song_id = $request->get_param('id');

    if (!$song_id) {
        return new WP_Error('no_id', 'Nenhum ID fornecido', ['status' => 400]);
    }

    $audio_url = get_field('song_source', $song_id);

    if (!$audio_url) {
        return new WP_Error('not_found', 'Áudio não encontrado', ['status' => 404]);
    }

    // Pega o Referer da requisição
    $referer = $_SERVER['HTTP_REFERER'] ?? '';

    // Domínio do seu site (ajuste conforme necessário)
    $allowed_domain = get_site_url();

    // Se não houver Referer OU se não for do seu site, retorna 404
    if (empty($referer) || strpos($referer, $allowed_domain) === false) {
        return new WP_Error('direct_access_blocked', 'Acesso não permitido', ['status' => 404]);
    }

    // Stream do áudio sem expor a URL real
    header("Content-Type: audio/mpeg");
    header("Content-Disposition: inline; filename=song.mp3");
    readfile($audio_url);

    exit;
}

//cryptografar download
function soniica_register_download_endpoint() {
    register_rest_route('soniica/v1', '/download/', [
        'methods'  => 'GET',
        'callback' => 'soniica_download_file',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'soniica_register_download_endpoint');

function soniica_download_file(WP_REST_Request $request) {
    $token = $request->get_param('token');

    // 🔹 Bloqueia acesso direto via navegador
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], get_site_url()) === false) {
        return new WP_Error('forbidden', 'Acesso negado.', ['status' => 403]);
    }

    if (!$token) {
        return new WP_Error('missing_token', 'Token inválido.', ['status' => 404]);
    }

    // Decodifica o token
    $decoded = json_decode(base64_decode($token), true);

    if (!isset($decoded['song_id']) || !isset($decoded['exp']) || $decoded['exp'] < time()) {
        return new WP_Error('invalid_token', 'Token expirado ou inválido.', ['status' => 404]);
    }

    $song_id = intval($decoded['song_id']);
    $file_url = get_field('song_download_link', $song_id);

    if (!$file_url) {
        return new WP_Error('file_not_found', 'Arquivo não encontrado.', ['status' => 404]);
    }

    // 🔹 Redireciona para a URL real do arquivo apenas se o referer for válido
    wp_redirect($file_url);
    exit;
}

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




