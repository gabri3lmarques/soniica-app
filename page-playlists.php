<?php
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
//pega a mensagem de sucesso, se existir
$success_message = FlashMessage::get('success');
if ($success_message) {
    echo '
        <div class="flash-message-overlay">
            <div class="flash-message-content">
                <div class="flash-message-header">
                    <svg class="fm-close" version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="400" height="400"><style>.a{fill:#f5f5ff}</style><path fill-rule="evenodd" class="a" d="m200 172.1l51.8-51.8c7.7-7.7 20.2-7.7 27.9 0 7.7 7.7 7.7 20.2 0 27.9l-51.8 51.8 51.8 51.8c7.7 7.7 7.7 20.2 0 27.9-7.7 7.7-20.2 7.7-27.9 0l-51.8-51.8-51.8 51.8c-7.7 7.7-20.2 7.7-27.9 0-7.7-7.7-7.7-20.2 0-27.9l51.8-51.8-51.8-51.8c-7.7-7.7-7.7-20.2 0-27.9 7.7-7.7 20.2-7.7 27.9 0zm103.9-168.6c26.9 3.7 48.1 11.2 64.8 27.8 16.6 16.7 24.2 37.9 27.8 64.8 3.5 26.2 3.5 59.9 3.5 102.8v2.2c0 42.9 0 76.6-3.5 102.8-3.6 26.9-11.2 48.1-27.8 64.8-16.7 16.6-37.9 24.2-64.8 27.8-26.2 3.5-59.9 3.5-102.8 3.5h-2.2c-42.9 0-76.6 0-102.8-3.5-26.9-3.6-48.1-11.2-64.8-27.8-16.6-16.7-24.1-37.9-27.8-64.8-3.5-26.2-3.5-59.9-3.5-102.8v-2.2c0-42.9 0-76.6 3.5-102.8 3.7-26.9 11.2-48.1 27.8-64.8 16.7-16.6 37.9-24.1 64.8-27.8 26.2-3.5 59.9-3.5 102.8-3.5h2.2c42.9 0 76.6 0 102.8 3.5zm-272.7 96.3c-3.3 24.2-3.3 56-3.3 100.2 0 44.3 0 76 3.3 100.2 3.2 23.8 9.3 38.1 19.9 48.7 10.6 10.6 24.9 16.7 48.7 19.9 24.2 3.3 56 3.3 100.2 3.3 44.3 0 76 0 100.2-3.3 23.8-3.2 38.1-9.3 48.7-19.9 10.6-10.6 16.7-24.9 19.9-48.7 3.3-24.2 3.3-55.9 3.3-100.2 0-44.2 0-76-3.3-100.2-3.2-23.8-9.3-38.1-19.9-48.7-10.6-10.6-24.9-16.7-48.7-19.9-24.2-3.3-55.9-3.3-100.2-3.3-44.2 0-76 0-100.2 3.3-23.8 3.2-38.1 9.3-48.7 19.9-10.6 10.6-16.7 24.9-19.9 48.7z"/></svg>
                </div>
                <div class="flash-message-body">
                '.$success_message.'
                </div>
            </div>
        </div>  
    ';
}
// Pega a menagem de erro, se existir
$error_message = FlashMessage::get('error');
if ($error_message) {
    echo '
        <div class="flash-message-overlay">
            <div class="flash-message-content">
                <div class="flash-message-header">
                    <svg class="fm-close" version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400" width="400" height="400"><style>.a{fill:#f5f5ff}</style><path fill-rule="evenodd" class="a" d="m200 172.1l51.8-51.8c7.7-7.7 20.2-7.7 27.9 0 7.7 7.7 7.7 20.2 0 27.9l-51.8 51.8 51.8 51.8c7.7 7.7 7.7 20.2 0 27.9-7.7 7.7-20.2 7.7-27.9 0l-51.8-51.8-51.8 51.8c-7.7 7.7-20.2 7.7-27.9 0-7.7-7.7-7.7-20.2 0-27.9l51.8-51.8-51.8-51.8c-7.7-7.7-7.7-20.2 0-27.9 7.7-7.7 20.2-7.7 27.9 0zm103.9-168.6c26.9 3.7 48.1 11.2 64.8 27.8 16.6 16.7 24.2 37.9 27.8 64.8 3.5 26.2 3.5 59.9 3.5 102.8v2.2c0 42.9 0 76.6-3.5 102.8-3.6 26.9-11.2 48.1-27.8 64.8-16.7 16.6-37.9 24.2-64.8 27.8-26.2 3.5-59.9 3.5-102.8 3.5h-2.2c-42.9 0-76.6 0-102.8-3.5-26.9-3.6-48.1-11.2-64.8-27.8-16.6-16.7-24.1-37.9-27.8-64.8-3.5-26.2-3.5-59.9-3.5-102.8v-2.2c0-42.9 0-76.6 3.5-102.8 3.7-26.9 11.2-48.1 27.8-64.8 16.7-16.6 37.9-24.1 64.8-27.8 26.2-3.5 59.9-3.5 102.8-3.5h2.2c42.9 0 76.6 0 102.8 3.5zm-272.7 96.3c-3.3 24.2-3.3 56-3.3 100.2 0 44.3 0 76 3.3 100.2 3.2 23.8 9.3 38.1 19.9 48.7 10.6 10.6 24.9 16.7 48.7 19.9 24.2 3.3 56 3.3 100.2 3.3 44.3 0 76 0 100.2-3.3 23.8-3.2 38.1-9.3 48.7-19.9 10.6-10.6 16.7-24.9 19.9-48.7 3.3-24.2 3.3-55.9 3.3-100.2 0-44.2 0-76-3.3-100.2-3.2-23.8-9.3-38.1-19.9-48.7-10.6-10.6-24.9-16.7-48.7-19.9-24.2-3.3-55.9-3.3-100.2-3.3-44.2 0-76 0-100.2 3.3-23.8 3.2-38.1 9.3-48.7 19.9-10.6 10.6-16.7 24.9-19.9 48.7z"/></svg>
                </div>
                <div class="flash-message-body">
                '.$error_message.'
                </div>
            </div>
        </div>   
    ';
}
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
        ?>
        <script>window.location="<?php echo esc_url(home_url('/playlists')); ?>";</script> 
        <?php
    } else {
        FlashMessage::set('error', $result['message'] ?? 'Não foi possível criar a playlist.');
        ?>
        <script>window.location="<?php echo esc_url(home_url('/playlists')); ?>";</script> 
        <?php
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