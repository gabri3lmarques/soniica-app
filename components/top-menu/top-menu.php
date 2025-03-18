<?php

use svg\SvgIcons;
use user\Users;

class TopMenu {

    public function render() {
        
        // Verifica se o usuário está logado
        $is_premium = Users::check_user_premium_status();

        // Pega o e-mail do usuário logado
        $current_user = wp_get_current_user();
        $email = $current_user->user_email;

        // Pega o nome do usuário logado
        $current_user = wp_get_current_user();
        $first_name = get_user_meta($current_user->ID, 'nickname', true);
      
        // Inicia o buffer de saída
        ob_start();
        ?>
            <div class="top-menu">
                <div class="nav-logo">
                    <a href="/"><?php SvgIcons::render('logo'); ?>  </a>            
                </div>
                <form class="search hide-768" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="text" name="s" placeholder="Enter music, genre, style..." autocomplete="off">
                </form>
                <div class="user-menu">
                    <?php if(!is_user_logged_in()) { ?>
                        <?php  SvgIcons::render('user'); ?>
                        <a href="<?php echo home_url('/login'); ?>">Log in</a>
                    <?php } else { ?>
                        <?php
                        $current_user = wp_get_current_user();
                        $first_letter = substr($current_user->display_name, 0, 1);
                        ?>
                        <div class="user-avatar">
                            <?php echo $first_letter; ?>
                        </div>
                        <div class="user-sub-menu">
                            <div class="user-sub-menu-content">
                                <?php 
                                if ($is_premium) {
                                    echo '<div class="user-status premium">Premium</div>';
                                } else {
                                    echo '<div class="user-status">free</div>';
                                }
                                ?>
                                <div class="user-name"><?php   echo esc_html($first_name); ?></div>
                                <div class="email"><?php echo esc_html($email); ?></div>
                                <ul class='user-menu-list'>
                                    <li><a href="<?php echo home_url('/profile'); ?>">Account</a></li>
                                    <li><a href="<?php echo home_url('/playlists'); ?>">Playlists</a></li>
                                    <li><a href="<?php echo wp_logout_url(home_url()); ?>">Log out</a></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php
        // Retorna o conteúdo do buffer e limpa-o
        return ob_get_clean();
    }
}

$componente = new TopMenu();
echo $componente->render();
