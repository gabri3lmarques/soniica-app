<?php

use svg\SvgIcons;

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
                            <li><a href="#">Favoritas</a></li>
                            <li><a href="#">Recentes</a></li>
                            <li><a href="#">Playlists</a></li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header">
                        <h3>Gêneros</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <ul>
                            <li><a href="#">Rock</a></li>
                            <li><a href="#">Jazz</a></li>
                            <li><a href="#">Eletrônica</a></li>
                            <li><a href="#">Rock</a></li>
                            <li><a href="#">Jazz</a></li>
                            <li><a href="#">Eletrônica</a></li>
                        </ul>
                    </div>
                </div>

                <div class="accordion-item">
                    <div class="accordion-header">
                        <h3>Configurações</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <ul>
                            <li><a href="#">Perfil</a></li>
                            <li><a href="#">Conta</a></li>
                            <li><a href="#">Privacidade</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php
        return ob_get_clean();
    }
}

$accordion = new Accordion();
echo $accordion->render();
