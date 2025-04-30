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
       <h2>Go Premium for Just $1.99/Month!</h2>
        <p>Unlock the full power of Soniica for only $1.99 per month!</p>
        <p>As a Premium member, you'll get:</p>
        <ul>
            <li>🎸 Unlimited downloads — rock your library with no limits.</li>
            <li>🎶 Unlimited playlists — create, customize, and blast your sound your way.</li>
            <li>🚀 Instant access to the latest releases — no more waiting to jam to the newest hits.</li>
            <li>🔕 Ad-free experience — enjoy your music without interruptions.</li>
        </ul>
        <p>All that for less than the price of a coffee.</p>
        <p>Don't hold back — take your music experience to the next level.</p>
        <p>Go Premium and let the music never stop! </p>   
        <?php 
            // Verifica se o usuário está logado
            if(is_user_logged_in()) {
                // Verifica se o usuário já é premium
                if($is_premium) {
                    ?>
                    <script>window.location = "<?php echo home_url('/'); ?>";</script>
                    <?php
                } 
                // Se não for premium, exibe o botão de assinatura
                else {
                    if ($checkoutUrl): ?>
                        <a class="premium-btn" href="<?php echo esc_url($checkoutUrl); ?>" class="btn btn-primary">Upgrade now $1.99/Month</a>
                    <?php else: ?>
                        <p>Erro ao gerar o checkout.</p>
                    <?php endif;
                }
            } else {
                ?>
                <script>window.location = "<?php echo home_url('/login'); ?>";</script>
                <?php
            }
        ?>
        <p>Cancel anytime, keep the vibe going 🎸</p>        
       </div>
    </div>
</div>
<?php get_footer(); ?>
