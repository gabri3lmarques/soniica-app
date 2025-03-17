<?php 

use stripe\StripeService;
use user\Users;

$userEmail = wp_get_current_user()->user_email;
$stripe = new StripeService();
$checkoutUrl = $stripe->createCheckoutSession($userEmail);

$is_premium = Users::check_user_premium_status();

// Verifica se o usuário está logado
if(is_user_logged_in()){

    // Verifica se o usuário já é premium
    if($is_premium) {
        echo '<p>Você já é um usuário premium.</p>';
    } 
    // Se não for premium, exibe o botão de assinatura
    else {
        if ($checkoutUrl): ?>
            <a href="<?php echo esc_url($checkoutUrl); ?>" class="btn btn-primary">Assinar Premium</a>
        <?php else: ?>
            <p>Erro ao gerar o checkout.</p>
        <?php endif;
    }
    // Se não estiver logado, exibe a mensagem abaixo
} else {
    echo '<p>É necessário estar logado para assinar o plano premium.</p>';
}
