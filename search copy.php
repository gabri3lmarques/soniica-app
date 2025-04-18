<?php wp_head(); ?>

<?php 

use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;

?>

<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>

<div class="container">
    <main class="site-main">
        <header class="page-header">
            <h1 class="page-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'your-theme-text-domain'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="search-results">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item'); ?>>
                        <header class="entry-header">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                        </header>
                    </article>
                <?php endwhile; ?>

                <?php the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Previous', 'your-theme-text-domain'),
                    'next_text' => __('Next', 'your-theme-text-domain'),
                )); ?>

            </div>
        <?php else : ?>
            <div class="no-results">
                <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'your-theme-text-domain'); ?></p>
                <?php get_search_form(); ?>
            </div>
        <?php endif; ?>
    </main>

</div>

<?php wp_footer(); ?>