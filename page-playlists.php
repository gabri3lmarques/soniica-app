<?php

use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
    $is_premium = Users::check_user_premium_status();
    // Se não for premium, verificar quantidade de playlists
    if (!$is_premium) {
        $user_id = get_current_user_id();
        $user_playlists = get_posts([
            'post_type' => 'playlist',
            'author'    => $user_id,
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        if (count($user_playlists) >= 1) {
            FlashMessage::set('error', 'Usuários não premium podem criar apenas uma playlist. Faça upgrade para criar mais!');
            ?>
            <script>window.location="<?php echo esc_url(home_url('/playlists')); ?>";</script> 
            <?php
            exit;
        }
    }
    $playlist = new Playlist();
    $result = $playlist->create_playlist([
        'title' => sanitize_text_field($_POST['playlist_title'])
    ]);
    if ($result['success']) {
        FlashMessage::set('success', 'Playlist criada com sucesso!');
    } else {
        FlashMessage::set('error', $result['message'] ?? 'Não foi possível criar a playlist.');
    }
}
?>

<?php get_header(); ?>

<h2>Criar Nova Playlist</h2>
<form method="POST">
    <input type="text" name="playlist_title" placeholder="Nome da Playlist" required>
    <button type="submit" name="create_playlist">Criar Playlist</button>
</form>

<h2>Minhas Playlists</h2>
<ul>
    <?php
    $current_user_id = get_current_user_id();
    $playlists = get_posts([
        'post_type' => 'playlist',
        'author'    => $current_user_id,
        'post_status' => 'publish',
        'numberposts' => -1
    ]);

    foreach ($playlists as $playlist) {
        // Obtém o link direto da playlist
        $playlist_link = get_permalink($playlist->ID);

        // Exibe o link na lista
        echo "<li><a href='" . esc_url($playlist_link) . "'>" . esc_html($playlist->post_title) . "</a></li>";
    }
    ?>
</ul>

<?php get_footer(); ?>