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

// adiciona suporte a paginação na pagina de tags
function filtrar_songs_em_tags( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_tag() ) {
        $query->set( 'post_type', 'song' );
        $query->set( 'posts_per_page', 1 );
    }
}
add_action( 'pre_get_posts', 'filtrar_songs_em_tags' );

// handler para o upload da imagem de perfil
add_action('wp_ajax_upload_profile_picture', function () {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => '👉 You need to be logged in.']);
    }

    $user_id = get_current_user_id();

    if (!isset($_FILES['profile_picture'])) {
        wp_send_json_error(['message' => '👉 No image uploaded.']);
    }

    $file = $_FILES['profile_picture'];

    // 1MB = 1048576 bytes
    if ($file['size'] > 1048576) {
        wp_send_json_error(['message' => '👉 The image must be no larger than 1MB.']);
    }

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        wp_send_json_error(['message' => '👉 Unsupported image format. Please use jpg, jpeg, png, or gif.']);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $uploaded = wp_handle_upload($file, ['test_form' => false]);

    if (isset($uploaded['error'])) {
        wp_send_json_error(['message' => 'Error uploading image: ' . $uploaded['error']]);
    }

    // Redimensionar para 300x300 usando WP_Image_Editor
    $image_path = $uploaded['file'];
    $editor = wp_get_image_editor($image_path);

    if (is_wp_error($editor)) {
        wp_send_json_error(['message' => '👉 Error processing image.']);
    }

    $editor->resize(300, 300, false); // true = crop
    $editor->save($image_path);

    $url = esc_url_raw($uploaded['url']);
    update_user_meta($user_id, 'profile_picture_url', $url);

    wp_send_json_success(['message' => '👉 Profile picture updated successfully!', 'url' => $url]);
});

// handler para remover a imagem de perfil
add_action('wp_ajax_remove_profile_picture', function () {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => '👉 You need to be logged in.']);
    }

    $user_id = get_current_user_id();
    $url = get_user_meta($user_id, 'profile_picture_url', true);

    // (Opcional) deletar o arquivo do servidor:
    if ($url) {
        $upload_dir = wp_upload_dir();
        $file_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $url);
        if (file_exists($file_path)) {
            unlink($file_path); // remove o arquivo do disco
        }
    }

    delete_user_meta($user_id, 'profile_picture_url');

    wp_send_json_success(['message' => '👉 Profile picture removed successfully.']);
});
//deleta usuário
add_action('wp_ajax_delete_user_account', 'soniica_delete_user_account');

function soniica_delete_user_account() {
    check_ajax_referer('delete_user_account_nonce');

    if (!is_user_logged_in()) {
        wp_send_json_error('👉 User is not logged in.');
    }

    $user_id = get_current_user_id();

    // Deletar o usuário
    require_once(ABSPATH.'wp-admin/includes/user.php');
    wp_delete_user($user_id);

    wp_send_json_success('👉 Account deleted successfully.');
}








