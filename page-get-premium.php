<?php 

use stripe\StripeService;

$userEmail = wp_get_current_user()->user_email;
$stripe = new StripeService();
$checkoutUrl = $stripe->createCheckoutSession($userEmail);

if(is_user_logged_in()){
    if ($checkoutUrl): ?>
        <a href="<?php echo esc_url($checkoutUrl); ?>" class="btn btn-primary">Assinar Premium</a>
    <?php else: ?>
        <p>Erro ao gerar o checkout.</p>
    <?php endif;
} else {
    echo '<p>É necessário estar logado para assinar o plano premium.</p>';
}
