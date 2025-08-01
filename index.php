<?php get_header(); ?>
<!-- Chicha meu amorzinho -->
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
        <div class="main home">
            <!-- renderiza a busca para a versão mobile -->
            <?php 
                $search = new SearchComponent();
                echo $search->render();
            ?>
            <!-- renderiza o top banner  -->
            <?php include 'components/top-banner/Topbanner.php'; ?>

            <div class="playlist">
                <h3>🔊 Tá bombando!</h3>
                <?php
                    // Obtém as músicas (posts do tipo 'song')
                    $songs = get_posts([
                        'post_type' => 'song',
                        'post_status' => 'publish',
                        'numberposts' => 10
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
                            $song_link = get_permalink($song->ID);
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
                                <span class="title"><a href="<?php echo($song_link); ?>"><?php echo esc_html($song_title); ?></a></span>
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
                            <?php 
                            if(is_user_logged_in()):
                            ?>
                            <button class="add-to-playlist-button"></button>
                            <?php endif; ?>
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

            <!-- Cards -->
            <div class="cards" style="margin-top:20px;">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cards/about.jpg" alt="Um senhor de cabelos e barba branca, vestindo uma tunica branca e um brilho em suas maos  colocando fones de ouvido em um jovem hipster, com um fundo colorido.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Sobre o Soniica</h3>
                        <p class="card-text"></p>
                        <a href="/about-us" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cards/precos.jpg" alt="Um casal sentado em ua mesa de lanchonete dos anos 50 confeindo a conta, ela tomando milshake.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Planos e preços</h3>
                        <p class="card-text"></p>
                        <a href="/pricing" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cards/contact.jpg" alt="Um jovem de aparencia nerd e tatuado está numa chamada web usando um headset.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Entre em contato</h3>
                        <p class="card-text"></p>
                        <a href="/contact" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/cards/licenca.jpg" alt="">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Sobre a licença</h3>
                        <p class="card-text"></p>
                        <a href="/license" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
            </div>
            <!-- /Cards -->

            <!-- /playlist -->
             <div class="playlist">
                <h3>🐣 Saindo do forno</h3>
                <?php
                    // IDs das músicas que você deseja exibir
                    $selected_songs = get_posts([
                        'post_type'   => 'song',
                        'post_status' => 'publish',
                        'post__in'    => [50, 51,52],
                        'orderby'     => 'post__in',
                        'numberposts' => 10
                    ]);
                ?>

                <?php foreach ($selected_songs as $song) :
                    $song_id        = $song->ID;
                    $song_title     = $song->post_title;
                    $download_link  = get_field('song_download_link', $song->ID);
                    $song_duration  = get_field('song_duration', $song->ID);
                    $song_img       = get_field('song_image', $song->ID);
                    $song_life_cycle= get_field('song_life_cycle', $song->ID);
                    $song_source    = get_field('song_source', $song->ID);
                    $song_link      = get_permalink($song->ID); 

                    $safe_url           = base64_encode($song_source);
                    $safe_download_link = base64_encode($download_link);

                    // Pegando o artista
                    $categories = get_the_category($song->ID);
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
                <?php endforeach; ?>
            </div>
            <!-- /playlist -->

            <h3>🕶️ Artistas da semana</h3>
            <!-- Cards -->
            <div class="cards">
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artists/jay_jordan.jpg" alt="Um senhor de cabelos e barba branca, vestindo uma tunica branca e um brilho em suas maos  colocando fones de ouvido em um jovem hipster, com um fundo colorido.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Jay Jordan</h3>
                        <p class="card-text"></p>
                        <a href="/about-us" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artists/kayko.jpg" alt="Um casal sentado em ua mesa de lanchonete dos anos 50 confeindo a conta, ela tomando milshake.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">K-Queen</h3>
                        <p class="card-text"></p>
                        <a href="/pricing" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artists/Sean.jpg" alt="Um jovem de aparencia nerd e tatuado está numa chamada web usando um headset.">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Sean</h3>
                        <p class="card-text"></p>
                        <a href="/contact" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
                <div class="card">
                    <div class="card-image">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/artists/dookie.jpg" alt="">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title">Mr.Dookie</h3>
                        <p class="card-text"></p>
                        <a href="/license" class="card-button">
                            conferir
                        </a>
                    </div>
                </div> 
                <!--/card  -->
            </div>
            <!-- /Cards --> 
             
                        <!-- /playlist -->
             <div class="playlist">
                <h3>🪕 Acústicas</h3>
                <?php
                    // IDs das músicas que você deseja exibir
                    $acoustic_songs = get_posts([
                        'post_type'      => 'song',
                        'post_status'    => 'publish',
                        'numberposts'    => 10,
                        'tax_query'      => [
                            [
                                'taxonomy' => 'post_tag',
                                'field'    => 'slug',
                                'terms'    => 'acoustic',
                            ]
                        ]
                    ]);
                ?>

                <?php foreach ($acoustic_songs as $song) :
                    $song_id        = $song->ID;
                    $song_title     = $song->post_title;
                    $download_link  = get_field('song_download_link', $song->ID);
                    $song_duration  = get_field('song_duration', $song->ID);
                    $song_img       = get_field('song_image', $song->ID);
                    $song_life_cycle= get_field('song_life_cycle', $song->ID);
                    $song_source    = get_field('song_source', $song->ID);
                    $song_link      = get_permalink($song->ID); 

                    $safe_url           = base64_encode($song_source);
                    $safe_download_link = base64_encode($download_link);

                    // Pegando o artista
                    $categories = get_the_category($song->ID);
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
?>    
<?php get_footer(); ?>



