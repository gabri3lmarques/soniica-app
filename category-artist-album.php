<?php 
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;
require_once get_template_directory() . '/components/card/CardComponent.php';
require_once get_template_directory() . '/components/player/Player.php';
require_once get_template_directory() . '/components/search/Search.php';
?>
<?php 
// Processamento do formulário — DEVE VIR ANTES DE QUALQUER HTML!
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_song_to_playlist'])) {
    $playlist = new Playlist();
    $playlist_id = (int) $_POST['playlist_id'];
    $song_id = (int) $_POST['song_id'];

    $playlist_post = get_post($playlist_id);
    if ($playlist_post && (int) $playlist_post->post_author === get_current_user_id()) {
        $result = $playlist->add_song_to_playlist([
            'id' => $playlist_id,
            'song_id' => $song_id
        ]);
        if ($result['success']) {
            FlashMessage::set('success', $result['message']);
        } else {
            FlashMessage::set('error', $result['message']);
        }
    } else {
        FlashMessage::set('error', 'Você não tem permissão para adicionar música a esta playlist.');
    }
    // Redireciona antes de qualquer HTML
    wp_redirect($_SERVER['REQUEST_URI']);
    exit;
}
?>
<?php 
//dados para processar a inserção de músicas na playlist
$current_user_id = get_current_user_id();
$user_playlists = get_posts([
    'post_type' => 'playlist',
    'author'    => $current_user_id,
    'post_status' => 'publish',
    'numberposts' => -1
]);
?>
<?php
get_header();
?>
<?php
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
?>
<?php
if ( is_category() ) {
    $current_category = get_queried_object();
    if ( ! empty( $current_category ) && ! is_wp_error( $current_category ) ) {
        $image_id = get_term_meta( $current_category->term_id, 'category-image', true );
        $image_url = $image_id ? wp_get_attachment_url( $image_id ) : '';
        $album_name = $current_category->name;
        $parent_id = $current_category->parent;
        $parent_link = get_category_link( $parent_id );
    }
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
        <p class="back-link">
            <a href="<?php echo($parent_link ); ?>">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p>
        <div class="album-header">
            <div class="album-image">
                <img src="<?php echo($image_url); ?>">
            </div>
            <div class="album-title">
                <h2><?php echo($album_name); ?></h2>
            </div>
        </div>
        <div class="playlist">
                    <?php
                        $args = array(
                            'post_type'      => 'song', // Tipo de post customizado
                            'posts_per_page' => -1,     // Número de posts (-1 para exibir todos)
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'category',              // Taxonomia padrão de categorias
                                    'field'    => 'term_id',
                                    'terms'    => $current_category->term_id, // Filtra pela categoria atual
                                ),
                            ),
                        );
                        // Realiza a query personalizada
                        $song_query = new WP_Query( $args );
                        // Verifica se há posts para exibir
                        if ( $song_query->have_posts() ) {
                            echo '<ul>';
                            while ( $song_query->have_posts() ) {
                                $song_query->the_post();
                                ?>
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
                                        <a class="artist" href="<?php echo $artist_link; ?>"><?php echo esc_html($artist->name ?? 'Desconhecido'); ?></a>
                                    </div>
                                    <button class="play-button"></button>
                                    <span class="time"><?php echo esc_html($song_duration); ?></span>
                                    <div class="new-tag-spot">
                                        <?php if ($song_life_cycle === "new") echo '<span class="is-new">new</span>'; ?>
                                    </div>
                                    <?php 
                                        if (is_user_logged_in()) {
                                            if ($song_life_cycle === "new" && !Users::check_user_premium_status()) {
                                                ?>
                                                <a class="download-link" href="<?php echo esc_url(home_url('/get-premium')); ?>"><img style="width:15px" src="<?php echo get_template_directory_uri(); ?>/assets/img/icons/download.png"></a>
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
                                    <?php if (is_user_logged_in()) : ?>
                                        <form class="hide-992" method="POST" style="margin-top: 10px;">
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
                                <?php
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>Nenhum post do tipo "song" encontrado nesta categoria.</p>';
                        }

                        // Restaura os dados originais do post
                        wp_reset_postdata();
                ?>                             
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


