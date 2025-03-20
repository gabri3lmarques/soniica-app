<?php

class TopBanner {

    public function render() {
        // Inicia o buffer de saída
        ob_start();
        ?>
            <div class="top-banner">
                top banner
            </div>
        <?php
        return ob_get_clean();
    }
}

$topbanner = new TopBanner();
echo $topbanner->render();
