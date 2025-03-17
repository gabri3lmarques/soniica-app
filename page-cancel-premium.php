<?php 
use stripe\StripeService;

// Processa o formulário de cancelamento
if (isset($_POST['cancel_subscription']) && check_admin_referer('cancel_subscription_nonce')) {
    $stripe = new StripeService();
    $result = $stripe->cancelSubscription(get_current_user_id());
    
    if ($result['success']) {
        $message = '<div class="alert alert-success">' . esc_html($result['message']) . '</div>';
    } else {
        $message = '<div class="alert alert-error">' . esc_html($result['message']) . '</div>';
    }
}

// Exibe a página
if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $stripe = new StripeService();
    
    // Verifica se o usuário é premium
    if ($stripe->isUserPremium(get_current_user_id())) {
        ?>
        <div class="premium-status">
            <h2>Status Premium Ativo</h2>
            <p>Você é um usuário premium e tem acesso a todos os recursos.</p>
            
            <form method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar sua assinatura?');">
                <?php wp_nonce_field('cancel_subscription_nonce'); ?>
                <button type="submit" name="cancel_subscription" class="btn btn-danger">
                    Cancelar Assinatura
                </button>
            </form>
            
            <?php if (isset($message)) echo $message; ?>
        </div>
        <?php
    } else {
        $checkoutUrl = $stripe->createCheckoutSession($user->user_email);
        if ($checkoutUrl): ?>
            <div class="premium-signup">
                <h2>Torne-se Premium</h2>
                <a href="<?php echo esc_url($checkoutUrl); ?>" class="btn btn-primary">
                    Assinar Premium
                </a>
            </div>
        <?php else: ?>
            <p>Erro ao gerar o checkout.</p>
        <?php endif;
    }
} else {
    ?>
    <p>É necessário estar logado para assinar o plano premium.</p>
    <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="btn btn-secondary">
        Fazer Login
    </a>
    <?php
}