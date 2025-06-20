<?php get_header(); ?>
<?php include 'components/top-menu/top-menu.php'; ?>



<?php

use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;



?>



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

            // pega os campos do som
            $song_id = $song->ID;
            $song_title = $song->post_title;
            $download_link = get_field('song_download_link', $song->ID);
            $song_duration = get_field('song_duration', $song->ID);
            $song_src = get_field('song_source', $song->ID);
            $song_img = get_field('song_image', $song->ID);

        ?>

        <?php

            //pegando o artista
            $categories = get_the_category($song->ID);
            $artist = null;

            if (!empty($categories)) {
                foreach ($categories as $category) {
                    // Verifica se esta categoria tem um pai, e se o pai dela é a categoria "Avô" (nível 1)
                    $parent_cat = get_category($category->parent);
                    if ($parent_cat && $parent_cat->parent == 0) {
                        $artist = $category; // A categoria que é "Pai" (nível 2)
                        break; // Já encontramos a categoria Pai, podemos sair do loop
                    }
                }
            }

            //pega o link do artista
            //esc_url(get_category_link($artist->term_id));
        ?>

        <?php 
        // Obtém as tags da música
        $tags = get_the_terms($song->ID, 'post_tag');

        if (!empty($tags) && !is_wp_error($tags)) : ?>
            <p>Tags: 
                <?php foreach ($tags as $tag) : ?>
                    <a href="<?php echo esc_url(get_term_link($tag->term_id)); ?>">
                        <?php echo esc_html($tag->name); ?>
                    </a>
                <?php endforeach; ?>
            </p>
        <?php endif; ?>

        <!-- song  -->
        <div class="song" data-song-id="<?php  echo($song_id); ?>" data-src="<?php echo($song_src); ?>">
            <span class="title"><?php echo($song_title); ?></span>
            <span class="artist">
                <?php echo esc_html($artist->name); ?>
            </span>
            <span class="time"><?php echo($song_duration); ?></span>
            <ul class="genders">
            <?php
                if (!empty($tags) && !is_wp_error($tags)) : ?>
                    <?php foreach ($tags as $tag) : ?>
                        <li>
                            <?php echo esc_html($tag->name); ?>
                    </li>
                    <?php endforeach; ?>
                <?php 
                endif; 
            ?>
            </ul>
            <img class="thumb" src="<?php echo($song_img); ?>" >
            <a class="download-link" href="<?php echo($download_link); ?>" download>Download</a>
            <button class="play-button">Play</button>
        </div>
        <!-- /song -->

        <!-- Formulário para adicionar música à playlist -->
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
    </li>
<?php endforeach; ?>

</ul>


<?php get_footer(); ?>