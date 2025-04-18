<?php get_header(); ?>
<?php 
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;
require_once get_template_directory() . '/components/search/Search.php';
?>
<?php 
//pega a mensagem de sucesso, se existir
$success_message = FlashMessage::get('success');
if ($success_message) {
    echo "<div class='flash-message success'>";
    echo "</div>";
}
// Pega a menagem de erro, se existir
$error_message = FlashMessage::get('error');
if ($error_message) {
    echo "<div class='flash-message error'>" . esc_html($error_message) . "</div>";
}
?>
<?php 
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
                    <form class="hide-576" method="POST" style="margin-top: 10px;">
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
                        <button class="add-to-playlist" type="submit" name="add_song_to_playlist">add</button>
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
                'mid_size' => 2,
                'prev_text' => __('Previous'),
                'next_text' => __('Next'),
            )); ?>
            </div>
            <?php else : ?>
                <div class="no-results">
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.'); ?></p>
                </div>
            <?php endif; ?>                
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