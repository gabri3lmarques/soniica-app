<?php
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;

require_once get_template_directory() . '/components/search/Search.php';
require_once get_template_directory() . '/components/player/Player.php';

get_header();

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

//pega as playlists do
$current_user_id = get_current_user_id();

$user_playlists = get_posts([
    'post_type' => 'playlist',
    'author'    => $current_user_id,
    'post_status' => 'publish',
    'numberposts' => -1
]);
?>

<!-- Aqui começa o HTML -->
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="main-content">
    <div class="sidebar hide-1200">
        <?php include 'components/accordion/accordion.php'; ?>
    </div>
    <div class="main">
        <p class="back-to-previous-page">
            <a href="/">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p>        
        <?php 
            $search = new SearchComponent();
            echo $search->render();
        ?>
        <div class="playlist">
        <?php if (have_posts()) : ?>
        <div class="search-results">
        <?php while (have_posts()) : the_post(); ?>
        <?php
            $song_id = get_the_ID();
            $song_title = get_the_title();
            $download_link = get_field('song_download_link', $song_id);
            $song_duration = get_field('song_duration', $song_id);
            $song_img = get_field('song_image', $song_id);
            $song_life_cycle = get_field('song_life_cycle', $song_id);
            $song_source = get_field('song_source', $song_id);

            $safe_url = base64_encode($song_source);
            $safe_download_link = base64_encode($download_link);

            $categories = get_the_category($song_id);
            $artist = null;
            $artist_link = null;

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $parent_cat = get_category($category->parent);
                    if ($parent_cat && $parent_cat->parent == 0) {
                        $artist = $category;
                        $artist_link = get_category_link($category->term_id);
                        break;
                    }
                }
            }

            $tags = get_the_terms($song_id, 'post_tag');
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
                        <a href="<?php echo($song_link); ?>"><span class="title"><?php echo esc_html($song_title); ?></span></a>
                        <a class="artist" href="<?php echo $artist_link; ?>"><?php echo esc_html($artist->name ?? 'Desconhecido'); ?></a>
                    </div>
                    <button class="play-button"></button>
                    <span class="time"><?php echo esc_html($song_duration); ?></span>
                    <div class="new-tag-spot"><?php if ($song_life_cycle === "new") { echo '<span class="is-new">new</span>'; } ?></div>
                    <?php
                        if (is_user_logged_in()) {
                            if ($song_life_cycle === "new" && !Users::check_user_premium_status()) {
                                ?>
                                <a class="download-link" href="<?php echo esc_url(home_url('/get-premium')); ?>">
                                    <img style="width:15px" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/download.png">
                                </a>
                                <?php
                            } else {
                                echo DownloadController::getDownloadLink($safe_download_link);
                            }
                        } else {
                            ?>
                            <a class="download-link" href="/login">
                                <img style="width:15px" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/download.png">
                            </a>
                            <?php
                        }
                    ?>
                    <?php 
                        if(is_user_logged_in()):
                    ?>
                    <button class="add-to-playlist-button"></button>
                    <?php endif; ?>
                    <?php if (is_user_logged_in()) : ?>
                        <form class="playlist-form" method="POST" style="margin-top: 10px;">
                            <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">
                            <label for="playlist_id_<?php echo $song_id; ?>"></label>
                            <select name="playlist_id" id="playlist_id_<?php echo $song_id; ?>" required>
                                <option value="">select playlist</option>
                                <?php foreach ($user_playlists as $playlist) : ?>
                                    <option value="<?php echo $playlist->ID; ?>">
                                        <?php echo esc_html($playlist->post_title); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="add-to-playlist" type="submit" name="add_song_to_playlist">+</button>
                        </form>
                    <?php endif; ?>
                    <ul class="genders">
                        <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                            <?php foreach ($tags as $tag) : ?>
                                <li><?php echo esc_html($tag->name); ?></li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- /song -->
        <?php endwhile; ?>
        <?php the_posts_pagination(array(
            'mid_size' => 20,
            'prev_text' => 'Anterior',
            'next_text' => 'Próximo',
        )); ?>
        </div>
        <?php else : ?>
            <div class="no-results">
                <p><?php esc_html_e('Poxa, não encontramos nada.'); ?></p>
            </div>
        <?php endif; ?>                
        </div>
    </div>
</div>
<div class="bottom-bar">
    <?php 
        echo PlayerComponent::render();
    ?>
</div>
<div class="playlist-modal-background">
    <div class="playlist-modal">
        <div class="playlist-modal-close">
            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20">
                <style>.a{fill:#fff}</style>
                <path fill-rule="evenodd" class="a" d="m0.4 0.4c0.6-0.5 1.5-0.5 2.1 0l7.5 7.6 7.5-7.6c0.6-0.5 1.5-0.5 2.1 0 0.5 0.6 0.5 1.5 0 2.1l-7.6 7.5 7.6 7.5c0.5 0.6 0.5 1.5 0 2.1-0.6 0.5-1.5 0.5-2.1 0l-7.5-7.6-7.5 7.6c-0.6 0.5-1.5 0.5-2.1 0-0.5-0.6-0.5-1.5 0-2.1l7.6-7.5-7.6-7.5c-0.5-0.6-0.5-1.5 0-2.1z"></path>
            </svg>                  
        </div>
        <div class="playlist-modal-head">
            <h3>Selecione uma playlist</h3>
        </div>
        <div class="playlist-modal-body"></div>
    </div>
</div>
<?php
// Processamento do formulário de envio de musica para a playlist
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_song_to_playlist'])) {
    $playlist = new Playlist();
    $playlist_id = (int) $_POST['playlist_id'];
    $song_id = (int) $_POST['song_id'];
    // Verifica se a playlist pertence ao usuário logado
    $playlist_post = get_post($playlist_id);
    if ($playlist_post && (int) $playlist_post->post_author === get_current_user_id()) {
        $result = $playlist->add_song_to_playlist([
            'id' => $playlist_id,
            'song_id' => $song_id
        ]);
        if ($result['success']) {
            FlashMessage::set('success', $result['message']);
            ?>
            <script>window.location.href = window.location.href;</script>
            <?php
        } else {
            FlashMessage::set('error', $result['message']);
            ?>
            <script>window.location.href = window.location.href;</script>
            <?php
        }
    } else {
        FlashMessage::set('error', 'Você não tem permissão para adicionar musica a esta playlist.');
        ?>
            <script>window.location.href = window.location.href;</script>
        <?php
    }
}
?> 
<?php get_footer(); ?>
