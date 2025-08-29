<?php
use svg\SvgIcons;
use user\Users;
class Accordion {
    public function render() {
        // Inicia o buffer de saída
        ob_start();
        ?>
            <div class="accordion-menu">
                <div class="accordion-item">
                    <div class="accordion-header">
                        <h3>Músicas</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <ul>
                            <li><a href="/tag/instrumental/">Instrumental</a></li>
                            <li><a href="/tag/acoustic/">Acoustic</a></li>
                            <li><a href="/tag/emotional/">Emotional</a></li>
                            <li><a href="/tag/indie/">Indie</a></li>
                            <li><a href="/tag/chillhop/">Chillhop</a></li>
                            <li><a href="/tag/pop/">Pop</a></li>
                        </ul>
                    </div>
                </div>
                <div class="accordion-item">
                    <div class="accordion-header">
                        <h3>Soniica</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <ul>
                            <li><a href="/about-us">Sobre</a></li>
                            <li><a href="/pricing">Preços</a></li>
                            <li><a href="/contact">Contato</a></li>
                            <li><a href="/license">Licença</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                // Verifica se o usuário está logado
                if (is_user_logged_in()) {
                    ?>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <h3>Minha conta</h3>
                            <span class="icon">+</span>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><a href="<?php echo home_url('/profile'); ?>">Perfil</a></li>
                                <li><a href="<?php echo home_url('/playlists'); ?>">Playlists</a></li>
                                <?php 
                                // Verifica se o usuário é premium
                                $is_premium = Users::check_user_premium_status();
                                if (!$is_premium) {
                                    ?>
                                    <li><a href="<?php echo home_url('/get-premium'); ?>">Assinar</a></li>
                                    <?php
                                } 
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                } 
                ?>
                <?php if(is_user_logged_in()): ?>
                <div class="accordion-item">
                    <div class="accordion-header">
                        <h3>Playlists</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
   
                            <?php
                                $current_user_id = get_current_user_id();
                                $playlists = get_posts([
                                    'post_type' => 'playlist',
                                    'author'    => $current_user_id,
                                    'post_status' => 'publish',
                                    'numberposts' => -1
                                ]);
                                if(!empty($playlists)){
                                    echo '<ul>';
                                    foreach ($playlists as $playlist) {
                                        // Obtém o link direto da playlist
                                        $playlist_link = get_permalink($playlist->ID);
                                        // Exibe o link na lista
                                        echo
                                        '<li>
                                            <a href="'.$playlist_link.'">
                                            <span>'.$playlist->post_title.'<span>
                                            </a>
                                        </li>';
                                    }
                                    echo '</ul>';
                                }
                            ?>
                        <ul>
                            <li><a href="/playlists">Suas playlists</a></li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php
        return ob_get_clean();
    }
}
$accordion = new Accordion();
echo $accordion->render();
