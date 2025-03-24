<?php get_header(); ?>
<?php include 'components/top-menu/top-menu.php'; ?>



<?php

use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;

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
        <strong><?php echo esc_html($song->post_title); ?></strong> (ID: <?php echo $song->ID; ?>)

        <?php

            // pega os campos do som
            $download_link = get_field('song_download_link', $song->ID);
            $song_duration = get_field('song_duration', $song->ID);

        ?>

        <!-- song  -->
        <div class="song" data-song-id="105" data-src="https://cdn1.suno.ai/79c6284f-961f-4583-9ebb-1978aef559e8.mp3">
            <span class="title">Song 1</span>
            <span class="artist">Artist 1</span>
            <span class="time">02:55</span>
            <ul class="genders">
                <li>gender 1</li>
                <li>gender 2</li>
            </ul>
            <img class="thumb" src="https://cdn2.suno.ai/image_large_79c6284f-961f-4583-9ebb-1978aef559e8.jpeg" >
            <a class="download-link" href="https://example.com/song3.mp3" download>Download</a>
            <button class="play-button">Play</button>
        </div>
        <!-- /song -->

        <!-- Formulário para adicionar música à playlist -->
        <!-- <form method="POST" style="margin-top: 10px;">
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
        </form> -->
    </li>
<?php endforeach; ?>

</ul>


<?php get_footer(); ?>