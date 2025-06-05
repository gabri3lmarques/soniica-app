<?php
get_header();
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;
require_once get_template_directory() . '/components/card/CardComponent.php';
require_once get_template_directory() . '/components/player/Player.php';
require_once get_template_directory() . '/components/search/Search.php';
//pega a mensagem de sucesso, se existir
$success_message = FlashMessage::get('success');
if ($success_message) {
    FlashMessage::render($success_message);
}
// Pega a menagem de erro, se existir
$error_message = FlashMessage::get('error');
if ($error_message) {
    FlashMessage::render($error_message);
}
$playlist_id = get_the_ID();
$post_author_id = get_post_field('post_author', $playlist_id); // Obtém o ID do autor do post

// Obtém as músicas da playlist (IDs armazenados no meta campo 'songs')
$song_ids = get_post_meta($playlist_id, 'songs', true);
// Verifica se existem músicas associadas
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_playlist'])) {
    $playlist = new Playlist();
    $playlist_id = (int) $_POST['playlist_id'];
    // Verifica se a playlist pertence ao usuário logado
    $playlist_post = get_post($playlist_id);
    if ($playlist_post && (int) $playlist_post->post_author === get_current_user_id()) {
        $result = $playlist->delete_playlist($playlist_id);
        if ($result['success']) {
            FlashMessage::set('success', '👉 Playlist successfully deleted.');
        } else {
            FlashMessage::set('error', '👉 Error deleting the playlist.');
        }
    } else {
        FlashMessage::set('error', "👉 You don't have permission to delete this playlist.");
    }
    // Redireciona para a home
    ?>
    <script>window.location="<?php echo esc_url(home_url('/playlists')); ?>";</script>
    <?php
    exit;
}
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="main-content">

<!-- a coluna da esquerda -->
<div class="sidebar hide-1200">
    <?php include 'components/accordion/accordion.php'; ?>
</div>
<!-- o corpo do site -->
<div class="main">
    <?php 
        $search = new SearchComponent();
        echo $search->render();
    ?>
    <div class="playlist-single-header">
        <p>
            <a href="/playlists">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p>
        <h1><?php echo esc_html(get_the_title(get_the_ID())); ?></h1>
    </div>
    <div class="playlist-single-controles">
        <?php 
                $current_user_id = get_current_user_id(); // Obtém o ID do usuário logado
                if ($current_user_id === (int) $post_author_id) {
                    ?>
                    <form method="POST">
                        <input type="hidden" name="playlist_id" value="<?php echo get_the_ID(); ?>">
                        <button type="submit" name="delete_playlist">Delete playlist</button>
                    </form>
                    <?php
                }
            ?>
            <a class="back-to-playlists" href="/playlists"></a>   
    </div>
    <div class="playlist">
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
                <?php
                            $song_id = $song->ID;
                            $song_title = $song->post_title;
                            $download_link = get_field('song_download_link', $song->ID);
                            $song_duration = get_field('song_duration', $song->ID);
                            $song_img = get_field('song_image', $song->ID);
                            $song_life_cycle = get_field('song_life_cycle', $song->ID);
                            $song_source = get_field('song_source', $song->ID);
                            //encrypta o url
                            $safe_url = base64_encode($song_source);
                            //Encrypta Download link
                            $safe_download_link = base64_encode($download_link);
                            // Pegando o artista
                            $categories = get_the_category($song->ID);
                            $artist = null;
                            $artist_link = null; // Variável para armazenar o link
                            if (!empty($categories)) {
                                foreach ($categories as $category) {
                                    $parent_cat = get_category($category->parent);
                                    if ($parent_cat && $parent_cat->parent == 0) {
                                        $artist = $category;
                                        $artist_link = get_category_link($category->term_id); // Obtendo o link
                                        break;
                                    }
                                }
                            }
                            // Pegando as tags
                            $tags = get_the_terms($song->ID, 'post_tag');
                        ?>
                        <!-- song --> 
                        <div class="song" data-song-id="<?php echo $song_id; ?>" data-src="<?php echo $safe_url; ?>">

                            <div class="song-cover">
                                <img class="thumb" src="<?php echo esc_url($song_img); ?>" alt="Capa da música">
                                <div class="sound-wave">
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                    <div class="bar"></div>
                                </div> 
                            </div>
                            <div class="title-artist">
                                <span class="title"><?php echo esc_html($song_title); ?></span>
                                <a class="artist" href="<?php echo($artist_link); ?>"><?php echo esc_html($artist->name ?? 'Desconhecido'); ?></a>
                            </div>
                            <button class="play-button"></button>
                            <span class="time"><?php echo esc_html($song_duration); ?></span>
                            <div class="new-tag-spot"><?php if($song_life_cycle === "new"){echo('<span class="is-new">new</span>');}  ?></div>
                            <?php 
                                if(is_user_logged_in()){
                                    if($song_life_cycle === "new" && !Users::check_user_premium_status()){
                                        ?>
                                        <a class="download-link" href="<?php echo esc_url(home_url('/get-premium')); ?>" class="download-button"><img style="width:15px" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/download.png"></a>
                                        <?php
                                    } else {
                                        echo DownloadController::getDownloadLink($safe_download_link);
                                    }
                                } else {
                                    ?>
                                    <a class="download-link" href="/login"><img style="width:15px" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/download.png"></a>
                                    <?php
                                }
                            ?>
                            <!-- Formulário para remover música da playlist -->
                            <form class="remove-song-form" method="POST" style="display: inline;">
                                <input type="hidden" name="playlist_id" value="<?php echo $playlist_id; ?>">
                                <input type="hidden" name="song_id" value="<?php echo $song->ID; ?>">
                                <button class="remove-song-btn" type="submit" name="remove_song_from_playlist"></button>
                            </form>
                            <ul class="genders">
                                <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                                    <?php foreach ($tags as $tag) : ?>
                                        <li><?php echo esc_html($tag->name); ?></li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                        <!-- /song -->
                <?php endforeach;
            }
        } else {
            echo "<p style='margin-top:30px'>This playlist doesn't have any songs yet.</p>";
        }
        ?>    
    </div>
    <!-- /playlist -->
</div>
<!-- o corpo do site -->
</div>
<div class="bottom-bar">
    <?php 
        echo PlayerComponent::render();
    ?>
</div>
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
        FlashMessage::set('success', '👉 Song removed successfully!');
        // Atualiza a página para refletir a mudança
        echo "<meta http-equiv='refresh' content='0'>";
    } else {
        FlashMessage::set('error', '👉 An error occurred while removing the song.');
        // Atualiza a página para refletir a mudança
        echo "<meta http-equiv='refresh' content='0'>";
    }
}
?>
<?php
get_footer();
?>
