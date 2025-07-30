<?php

use svg\SvgIcons;
use user\Users;
require_once get_template_directory() . '/components/notifications/Notifications.php';

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
                    <a href="/">
                        <?php //SvgIcons::render('logo'); ?>
                        <img style="width:100px;" src="<?php echo get_template_directory_uri(); ?>/assets/img/logo/logo.png" alt="">
                    </a>            
                </div>
                <form class="search hide-768" method="get" action="<?php echo home_url('/'); ?>">
                    <input type="text" name="s" placeholder="Estilo, gênero, vibe..." autocomplete="off">
                </form>
                <?php
                    $notifier = new NewSongsNotifier();
                    $notifier->render();
                ?>
                <div class="user-menu">
                    <?php if(!is_user_logged_in()) { ?>
                        <a href="<?php echo home_url('/login'); ?>">
                            <div class="login-button">
                                <?php  SvgIcons::render('user'); ?>
                                Log in
                            </div>
                        </a>
                    <?php } else { ?>
                        <?php
                        $current_user = wp_get_current_user();
                        $first_letter = substr($current_user->display_name, 0, 1);
                        $user_id = get_current_user_id();
                        $profile_url = get_user_meta($user_id, 'profile_picture_url', true);
                        ?>
                        <div class="user-avatar">
                            <?php
                                if($profile_url) {
                                    ?>
                                    <img class="profile-image" src="<?php echo esc_url($profile_url); ?>">
                                    <?php
                                } else {
                                    echo $first_letter;
                                }
                            ?>
                        </div>
                        <!-- user sub menu -->
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
                                    <li><a href="<?php echo home_url('/profile'); ?>">Profile</a></li>
                                    <li><a href="<?php echo home_url('/playlists'); ?>">Playlists</a></li><!--  -->
                                    <?php if($is_premium) {
                                        ?>
                                        <li class="bordered"><a href="<?php echo home_url('/cancel-premium'); ?>">Cancel Premium</a></li>
                                        <?php
                                        } else {
                                            ?>
                                            <li class="bordered"><a href="<?php echo home_url('/get-premium'); ?>">Go premium</a></li>
                                            <?php
                                        }
                                    ?>
                                    <li><a href="<?php echo home_url('/about-us'); ?>">About us</a></li>
                                    <li><a href="<?php echo home_url('/pricing'); ?>">Pricing</a></li>
                                    <li><a href="<?php echo home_url('/contact-us'); ?>">Contact us</a></li>
                                    <li class="bordered"><a href="<?php echo home_url('/license'); ?>">License</a></li>
                                    <li><a href="<?php echo esc_url(home_url('/logout')); ?>">Log out</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- /user sub menu -->
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
