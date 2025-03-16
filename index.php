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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_playlist'])) {
  
    $is_premium = Users::check_user_premium_status();

    // Se não for premium, verificar quantidade de playlists
    if (!$is_premium) {

        echo "Usuário não é premium";

        $user_playlists = get_posts([
            'post_type' => 'playlist',
            'author'    => $user_id,
            'post_status' => 'publish',
            'numberposts' => -1
        ]);

        if (count($user_playlists) >= 1) {
            FlashMessage::set('error', 'Usuários não premium podem criar apenas uma playlist. Faça upgrade para criar mais!');
            ?>
            <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
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
        <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
        <?php
    } else {
        FlashMessage::set('error', $result['message'] ?? 'Não foi possível criar a playlist.');
        ?>
        <script>window.location="<?php echo esc_url(home_url()); ?>";</script>
        <?php
    }
}

// ...existing code...
?>

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