<?php get_header(); ?>
<?php 
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;
$is_premium = Users::check_user_premium_status();
?>
<?php 
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
?>
<?php 
require_once get_template_directory() . '/components/card/CardComponent.php';
require_once get_template_directory() . '/components/player/Player.php';
require_once get_template_directory() . '/components/search/Search.php';
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
            <?php include 'components/top-banner/Topbanner.php'; ?>
            <?php
                if(!is_user_logged_in()){
                    ?>
                        <div class="cards">
                            <?php 

                                $image_url = get_template_directory_uri() . '/assets/img/cards/1.jpg';
                                
                                echo CardComponent::render(
                                    $image_url, 
                                    'Digital influencer', 
                                    'Your audience deserves the best.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Video editor', 
                                    'Never run out of options.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Professional producer', 
                                    'The perfect soundtrack is here.', 
                                    'Sign up', 
                                    '/sign-up'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Just chill', 
                                    'Just enjoy your vibe.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                            ?>  
                        </div>
                    <?php
                } else {
                    if(!$is_premium){
                        ?>
                        <div class="cards">
                            <?php 

                                $image_url = get_template_directory_uri() . '/assets/img/cards/1.jpg';
                                
                                echo CardComponent::render(
                                    $image_url, 
                                    'Digital influencer', 
                                    'Your audience deserves the best.', 
                                    'Go premium', 
                                    '/get-premium'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Video editor', 
                                    'Never run out of options.', 
                                    'Go premium', 
                                    '/get-premium'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Professional producer', 
                                    'The perfect soundtrack is here.', 
                                    'Go premium', 
                                    '/get-premium'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Just chill', 
                                    'Just enjoy your vibe.', 
                                    'Go premium', 
                                    '/get-premium'
                                );
                            ?>  
                        </div>
                        <?php
                    }
                }
            ?>
            <div class="playlist">
            <?php
                // Processamento do formulário
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
                            <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
                            <?php
                        } else {
                            FlashMessage::set('error', $result['message']);
                            ?>
                            <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
                            <?php
                        }
                    } else {
                        FlashMessage::set('error', 'Você não tem permissão para adicionar musica a esta playlist.');
                        ?>
                        <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
                        <?php
                    }
                }
                // Obtém as músicas (posts do tipo 'song')
                $songs = get_posts([
                    'post_type' => 'song',
                    'post_status' => 'publish',
                    'numberposts' => -1
                ]);
                // Obtém as playlists do usuário logado
                $current_user_id = get_current_user_id();
                $user_playlists = get_posts([
                    'post_type' => 'playlist',
                    'author'    => $current_user_id,
                    'post_status' => 'publish',
                    'numberposts' => -1
                ]);
            ?>
            <?php foreach ($songs as $song) : ?>
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
                        <button class="add-to-playlist-button"></button>
                        <?php 
                            if(is_user_logged_in()){
                                ?>
                                    <form class="playlist-form" method="POST" style="margin-top: 10px;">
                                        <input type="hidden" name="song_id" value="<?php echo $song->ID; ?>">
                                        <label for="playlist_id_<?php echo $song->ID; ?>"></label>
                                            <select name="playlist_id" id="playlist_id_<?php echo $song->ID; ?>" required>
                                                <option value="">select playlist</option>
                                                <?php foreach ($user_playlists as $playlist) : ?>
                                                    <option value="<?php echo $playlist->ID; ?>">
                                                        <?php echo esc_html($playlist->post_title); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        <button class="add-to-playlist" type="submit" name="add_song_to_playlist">+</button>
                                    </form>
                                <?php
                            }
                        ?>
                        <ul class="genders">
                            <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                                <?php foreach ($tags as $tag) : ?>
                                    <li><?php echo esc_html($tag->name); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <!-- /song -->
            <?php endforeach; ?>
            </div>
            <!-- /playlist -->
        </div>
        <!-- o corpo do site -->
    </div>
    <!-- /main content -->
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
<?php get_footer(); ?>



