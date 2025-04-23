<?php get_header(); ?>
<?php 
use playlist\Playlist;
use flash_message\FlashMessage;
use user\Users;
use download\DownloadController;
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
            <?php
                if ( is_category() ) {
                    // Recupera o objeto da categoria atual
                    $current_category = get_queried_object();
                    if ( ! empty( $current_category ) && ! is_wp_error( $current_category ) ) {
                        // Obtém o ID da imagem salva como meta da categoria
                        $image_id = get_term_meta( $current_category->term_id, 'category-image', true );
                        $image_url = $image_id ? wp_get_attachment_url( $image_id ) : '';
                        // Exibe o nome da categoria como link
                        $parent_link = get_term_link( $current_category );
                        ?>
                        <div class="artist-header">
                            <?php 
                                if ( $image_url ) {
                                    echo '<div class="artist-image">';
                                    echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( $current_category->name ) . '">';
                                    echo '</div>';
                                }
                            ?>
                            <div class="artist-info">
                                <?php
                                    echo '<h2 class="artist-name">' . esc_html( $current_category->name ) . '</h2>';
                                    // Exibe a imagem da categoria, se existir
                                    // Exibe a descrição da categoria, se existir
                                    if ( ! empty( $current_category->description ) ) {
                                        echo '<p class="artist-description">' . esc_html( $current_category->description ) . '</p>';
                                    }                                
                                ?>
                            </div>
                        </div>
                        <?php
                        // Configura os argumentos para buscar as categorias filhas da atual
                        $args = array(
                            'taxonomy'   => 'category',
                            'parent'     => $current_category->term_id,
                            'hide_empty' => false, // Altere para true se preferir ocultar categorias sem posts
                        );
                        // Recupera as categorias filhas
                        $child_categories = get_terms( $args );
                        // Verifica se há categorias filhas
                        if ( ! empty( $child_categories ) && ! is_wp_error( $child_categories ) ) {
                            echo '<div class="albums-songs">';
                            echo '<ul>';
                            foreach ( $child_categories as $child ) {
                                // Obtém a imagem da subcategoria
                                $child_image_id = get_term_meta( $child->term_id, 'category-image', true );
                                $child_image_url = $child_image_id ? wp_get_attachment_url( $child_image_id ) : '';
                                echo '<li>';
                                // Exibe a imagem da subcategoria, se existir
                                if ( $child_image_url ) {
                                    echo '<img src="' . esc_url( $child_image_url ) . '" alt="' . esc_attr( $child->name ) . '" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;"> ';
                                }
                                echo '<a href="' . esc_url( get_term_link( $child ) ) . '">' . esc_html( $child->name ) . '</a>';
                                // Exibe a descrição da subcategoria, se existir
                                if ( ! empty( $child->description ) ) {
                                    echo '<p class="child-category-description">' . esc_html( $child->description ) . '</p>';
                                }
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>Nenhuma categoria filha encontrada.</p>';
                        }
                    }
                }
            ?>
            <div class="playlist">                
            </div>
            <!-- /playlist -->
            </div>
            <!-- /albums-songs -->
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
