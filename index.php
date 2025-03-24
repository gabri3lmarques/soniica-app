<?php get_header(); ?>

<?php 

use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;

?>

<?php 
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
?>

<?php 
require_once get_template_directory() . '/components/card/CardComponent.php';
require_once get_template_directory() . '/components/player/Player.php';
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
                if(!is_user_logged_in()){
                    ?>
                        <div class="cards">
                            <?php 

                                $image_url = get_template_directory_uri() . '/assets/img/cards/1.jpg';
                                
                                echo CardComponent::render(
                                    $image_url, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                            ?>  
                        </div>
                    <?php
                } else {
                    $is_premium = Users::check_user_premium_status();
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
                                    '/sign-up'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Video editor', 
                                    'Never run out of options.', 
                                    'Go premium', 
                                    '/sign-up'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Professional producer', 
                                    'The perfect soundtrack is here.', 
                                    'Go premium', 
                                    '/sign-up'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Just chill', 
                                    'Just enjoy your vibe.', 
                                    'Go premium', 
                                    '/sign-up'
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

<h2>Minhas Músicas</h2>
<ul>
<?php foreach ($songs as $song) : ?>
    <li>
        <?php
            $song_id = $song->ID;
            $song_title = $song->post_title;
            $download_link = get_field('song_download_link', $song->ID);
            $song_duration = get_field('song_duration', $song->ID);
            $song_img = get_field('song_image', $song->ID);
            $song_life_cycle = get_field('life_cycle', $song->ID);

            // URL protegida via REST API
            $secure_song_url = get_rest_url(null, 'soniica/v1/stream?id=' . $song_id);

            // Pegando o artista
            $categories = get_the_category($song->ID);
            $artist = null;

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    $parent_cat = get_category($category->parent);
                    if ($parent_cat && $parent_cat->parent == 0) {
                        $artist = $category;
                        break;
                    }
                }
            }

            // Pegando as tags
            $tags = get_the_terms($song->ID, 'post_tag');

            $expiration = time() + 600; // Token válido por 10 minutos
            $token_data = json_encode(['song_id' => $song_id, 'exp' => $expiration]);
            $token = base64_encode($token_data);
            $secure_download_url = get_rest_url(null, "soniica/v1/download/?token=" . urlencode($token));

        ?>

        <!-- song -->
        <div class="song" data-song-id="<?php echo $song_id; ?>" data-src="<?php echo esc_url($secure_song_url); ?>">

            <img class="thumb" src="<?php echo esc_url($song_img); ?>" alt="Capa da música">
            <span class="is-new"><?php if($song_life_cycle === "new"){echo("new");}  ?></span>
            <span class="title"><?php echo esc_html($song_title); ?></span>
            <span class="artist"><?php echo esc_html($artist->name ?? 'Desconhecido'); ?></span>
            <span class="time"><?php echo esc_html($song_duration); ?></span>
            
            <ul class="genders">
                <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                    <?php foreach ($tags as $tag) : ?>
                        <li><?php echo esc_html($tag->name); ?></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <?php 
    if(is_user_logged_in()){
        echo DownloadController::getDownloadLink($secure_download_url);
    }
            ?>

            <button class="play-button">Play</button>

            <?php 
                if(is_user_logged_in()){
                    ?>
                        <form method="POST" style="margin-top: 10px;">
                        <input type="hidden" name="song_id" value="<?php echo $song->ID; ?>">

                        <label for="playlist_id_<?php echo $song->ID; ?>">Escolha a Playlist:</label>
                        <select name="playlist_id" id="playlist_id_<?php echo $song->ID; ?>" required>
                            <option value="">Selecione uma playlist</option>
                            <?php foreach ($user_playlists as $playlist) : ?>
                                <option value="<?php echo $playlist->ID; ?>">
                                    <?php echo esc_html($playlist->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit" name="add_song_to_playlist">Adicionar à Playlist</button>
                        </form>
                    <?php
                }
            ?>
        </div>
        <!-- /song -->

        <!-- Formulário para adicionar música à playlist -->

    </li>
<?php endforeach; ?>


</ul>
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


<?php get_footer(); ?>
