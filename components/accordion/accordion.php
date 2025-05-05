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
                        <h3>Music</h3>
                        <span class="icon">+</span>
                    </div>
                    <div class="accordion-content">
                        <ul>
                            <li><a href="/tag/happy/">Happy</a></li>
                            <li><a href="#">Recentes</a></li>
                            <li><a href="#">Playlists</a></li>
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
                            <li><a href="#">About us</a></li>
                            <li><a href="#">Pricing</a></li>
                            <li><a href="#">FAQs</a></li>
                            <li><a href="#">Contact us</a></li>
                            <li><a href="#">Blog</a></li>
                        </ul>
                    </div>
                </div>
                <?php
                // Verifica se o usuário está logado
                if (is_user_logged_in()) {
                    ?>
                    <div class="accordion-item">
                        <div class="accordion-header">
                            <h3>My Account</h3>
                            <span class="icon">+</span>
                        </div>
                        <div class="accordion-content">
                            <ul>
                                <li><a href="<?php echo home_url('/profile'); ?>">Profile</a></li>
                                <li><a href="<?php echo home_url('/playlists'); ?>">Playlists</a></li>
                                <?php 
                                // Verifica se o usuário é premium
                                $is_premium = Users::check_user_premium_status();
                                if (!$is_premium) {
                                    ?>
                                    <li><a href="<?php echo home_url('/get-premium'); ?>">Go premium</a></li>
                                    <?php
                                } 
                                ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                } 
                ?>
            </div>
        <?php
        return ob_get_clean();
    }
}
$accordion = new Accordion();
echo $accordion->render();
