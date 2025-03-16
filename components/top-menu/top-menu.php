<?php

use svg\SvgIcons;

class TopMenu {

    public function render() {
        // Inicia o buffer de saída
        ob_start();
        ?>
        <div class="top-menu">
            <div class="login">
                <?php if(!is_user_logged_in()) { ?>
                    <?php  SvgIcons::render('user'); ?>
                    <a href="<?php echo home_url('/login'); ?>">Login</a>
                <?php } else { ?>
                    <?php  SvgIcons::render('logout'); ?>
                    <a href="<?php echo wp_logout_url( home_url() ); ?>">Logoff</a>
                <?php } ?>
            </div>
            <form class="search" method="get" action="<?php echo home_url('/'); ?>">
                <input type="text" name="s" placeholder="Enter music, genre, style..." autocomplete="off">
            </form>
            <div class="nav-logo">
                <nav>
                    <ul>
                        <li><a href="<?php echo home_url('/get-premium'); ?>">Get Premium</a></li>
                        <li><a href="<?php echo home_url('/contato'); ?>">Contato</a></li>
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
