<?php

use svg\SvgIcons;
use user\Users;



class TopMenu {

    public function render() {
        $is_premium = Users::check_user_premium_status();
        // Inicia o buffer de saída
        ob_start();
        ?>
        <div class="top-menu">
            <div class="user-menu">
                <?php if(!is_user_logged_in()) { ?>
                    <?php  SvgIcons::render('user'); ?>
                    <a href="<?php echo home_url('/login'); ?>">Login</a>
                <?php } else { ?>
                    <?php  SvgIcons::render('user'); ?>
                    Hey, <?php echo wp_get_current_user()->display_name; ?>
                    <div class="user-sub-menu">
                        <div class="user-sub-menu-content">
                            <a href="<?php echo wp_logout_url(home_url()); ?>">Dip out</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <form class="search" method="get" action="<?php echo home_url('/'); ?>">
                <input type="text" name="s" placeholder="Enter music, genre, style..." autocomplete="off">
            </form>
            <div class="nav-logo">
                <nav>
                    <ul>
                    <?php 
                        if(!is_user_logged_in()) {
                            echo '<li><a href="' . home_url('/sign-up') . '">Sign up</a></li>';
                            echo '<li><a href="' . home_url('/sign-up') . '">What’s the Vibe?</a></li>';
                        } else {
                            if(!$is_premium ){
                                echo '<li><a href="' . home_url('/get-premium') . '">Shine with premium</a></li>';
                            }
                            echo '<li><a href="' . home_url('/playlists') . '">My playlists</a></li>';
                            echo '<li><a href="' . home_url('/profile') . '">Account</a></li>';
                        }
                    ?>
                    </ul>
                </nav> 
                <a href="/"><?php SvgIcons::render('logo'); ?>  </a>            
            </div>
        </div>
        <?php
        // Retorna o conteúdo do buffer e limpa-o
        return ob_get_clean();
    }
}

$componente = new TopMenu();
echo $componente->render();
