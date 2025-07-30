<?php 
use stripe\StripeService;
use user\Users;
require_once get_template_directory() . '/components/search/Search.php';
$userEmail = wp_get_current_user()->user_email;
$stripe = new StripeService();
$checkoutUrl = $stripe->createCheckoutSession($userEmail);
$is_premium = Users::check_user_premium_status();
get_header();
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="page-template go-premium">
    <div class="page-template_left-side"></div>
    <div class="page-template_right-side">
       <div class="page-template_right-side_content">
        <p style="margin-bottom:40px;" class="back-to-previous-page">
            <a href="/" title="home">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p> 
       <h2>Seja Premium por só R$9,99/mês!</h2>
        <p>Desbloqueie todo o poder do Soniica por apenas R$9,99 por mês!</p>
        <p>Como membro Premium, você vai ter:</p>
        <ul>
            <li>🎸 Downloads ilimitados — monte sua biblioteca sem limites.</li>
            <li>🎶 Playlists ilimitadas — crie, personalize e curta do seu jeito.</li>
            <li>🚀 Acesso instantâneo aos lançamentos — nada de esperar pra ouvir os hits mais novos.</li>
            <li>🔕 Experiência sem anúncios — curte seu som sem interrupções.</li>
        </ul>
        <p>Tudo isso por menos do que o preço de um café!</p>
        <p>Não se prenda — leve sua experiência musical pro próximo nível.</p>
        <p>Vire Premium e deixe a música rolar sem parar!</p>   
        <?php 
            // Verifica se o usuário está logado
            if(is_user_logged_in()) {
                // Verifica se o usuário já é premium
                if($is_premium) {
                    ?>
                    <script>window.location = "<?php echo home_url('/premium-success'); ?>";</script>
                    <?php
                } 
                // Se não for premium, exibe o botão de assinatura
                else {
                    if ($checkoutUrl): ?>
                        <a class="premium-btn" href="<?php echo esc_url($checkoutUrl); ?>" class="btn btn-primary">Ative agora por R$9,99/mês</a>
                    <?php else: ?>
                        <p>Erro no checkout</p>
                    <?php endif;
                }
            } else {
                ?>
                <script>window.location = "<?php echo home_url('/login'); ?>";</script>
                <?php
            }
        ?>
        <p>Cancele quando quiser, mas mantenha a vibe 🎸</p>        
       </div>
    </div>
</div>
<?php get_footer(); ?>
