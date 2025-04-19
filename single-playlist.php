<?php
get_header();
use playlist\Playlist;
use flash_message\FlashMessage;
// Exibe mensagem de sucesso, se existir
$success_message = FlashMessage::get('success');
if ($success_message) {
    echo "<div class='flash-message success'>" . esc_html($success_message) . "</div>";
}
// Exibe mensagem de erro, se existir
$error_message = FlashMessage::get('error');
if ($error_message) {
    echo "<div class='flash-message error'>" . esc_html($error_message) . "</div>";
}
// Obtém o ID da playlist diretamente do contexto do post
$playlist_id = get_the_ID();
$post_author_id = get_post_field('post_author', $playlist_id); // Obtém o ID do autor do post
$current_user_id = get_current_user_id(); // Obtém o ID do usuário logado
if ($current_user_id === (int) $post_author_id) {
    echo "<p>Você é o autor deste post!</p>";
    ?>
    <form method="POST">
        <input type="hidden" name="playlist_id" value="<?php echo get_the_ID(); ?>">
        <button type="submit" name="delete_playlist">Excluir Playlist</button>
    </form>
    <?php
} else {
    echo "<p>Você não é o autor deste post.</p>";
}
// Obtém as músicas da playlist (IDs armazenados no meta campo 'songs')
$song_ids = get_post_meta($playlist_id, 'songs', true);
// Verifica se existem músicas associadas
if ($song_ids && is_array($song_ids)) {
    echo "<h1>" . esc_html(get_the_title($playlist_id)) . "</h1>";
    echo "<ul>";
    // Busca os posts do tipo 'song' usando uma consulta simples
    $songs = get_posts([
        'post_type' => 'song',
        'post__in'  => $song_ids,
        'posts_per_page' => -1
    ]);
    if ($songs) {
        foreach ($songs as $song) {
            echo "<li>" . esc_html($song->post_title) . "</li>";
        }
    } else {
        echo "<li>Esta playlist não possui músicas válidas.</li>";
    }
    echo "</ul>";
} else {
    echo "<p>Esta playlist ainda não possui músicas.</p>";
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_playlist'])) {
    $playlist = new Playlist();
    $playlist_id = (int) $_POST['playlist_id'];
    // Verifica se a playlist pertence ao usuário logado
    $playlist_post = get_post($playlist_id);
    if ($playlist_post && (int) $playlist_post->post_author === get_current_user_id()) {
        $result = $playlist->delete_playlist($playlist_id);
        if ($result['success']) {
            FlashMessage::set('success', 'Playlist excluída com sucesso.');
        } else {
            FlashMessage::set('error', 'Erro ao excluir a playlist.');
        }
    } else {
        FlashMessage::set('error', 'Você não tem permissão para excluir esta playlist.');
    }
    // Redireciona para a home
    ?>
    <script>window.location="<?php echo esc_url(home_url('/playlists')); ?>";</script>
    <?php
    exit;
}
?>
<h1><?php echo esc_html(get_the_title(get_the_ID())); ?></h1>
<ul>
    <?php
    $playlist_id = get_the_ID();
    $song_ids = get_post_meta($playlist_id, 'songs', true);
    if ($song_ids && is_array($song_ids)) {
        $songs = get_posts([
            'post_type' => 'song',
            'post__in'  => $song_ids,
            'posts_per_page' => -1
        ]);
        if ($songs) {
            foreach ($songs as $song) : ?>
                <li>
                    <?php echo esc_html($song->post_title); ?>
                    <!-- Formulário para remover música da playlist -->
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
                        <input type="hidden" name="song_id" value="<?php echo $song->ID; ?>">
                        <button type="submit" name="remove_song_from_playlist">Remover</button>
                    </form>
                </li>
            <?php endforeach;
        } else {
            echo "<li>Esta playlist não possui músicas válidas.</li>";
        }
    } else {
        echo "<p>Esta playlist ainda não possui músicas.</p>";
    }
    ?>
</ul>
<?php
// Processamento do formulário de remoção
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_song_from_playlist'])) {
    $playlist_id = (int) $_POST['playlist_id'];
    $song_id = (int) $_POST['song_id'];
    // Chama a função para remover a música da playlist
    $playlist_handler = new Playlist(); // Supondo que sua classe se chama PlaylistHandler
    $result = $playlist_handler->remove_song_from_playlist([
        'id' => $playlist_id,
        'song_id' => $song_id
    ]);
    if ($result['success']) {
        FlashMessage::set('success', 'Música removida com sucesso!');
        // Atualiza a página para refletir a mudança
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        FlashMessage::set('error', 'Ocorreu um erro ao remover a música.');
        // Atualiza a página para refletir a mudança
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
?>
<?php
get_footer();
?>
