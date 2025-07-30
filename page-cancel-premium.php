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
get_header();

?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="page-template cancel-premium">
    <div class="page-template_left-side">
        <div class="page-template_left-side_content"> 
        <?php 
            // Exibe a página
            if (is_user_logged_in()) {
                $user = wp_get_current_user();
                $stripe = new StripeService();
                // Verifica se o usuário é premium
                if ($stripe->isUserPremium(get_current_user_id())) {
                    ?>
                        <p style="margin-bottom:40px;" class="back-to-previous-page">
                            <a href="/" title="home">
                                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
                            </a>
                        </p> 
                        <h2>Vamos sentir sua falta!</h2>
                        <p>Às vezes a gente precisa seguir um novo ritmo — e tá tudo bem! 🎧</p>
                        <p>Ao cancelar seu plano Premium, você ainda vai poder curtir o Soniica, mas vai perder alguns benefícios especiais, como:</p>
                        <ul>
                            <li>Acesso aos últimos lançamentos</li>
                            <li>Baixar músicas pra ouvir offline</li>
                            <li>Uma experiência sem anúncios</li>
                        </ul>
                        <p>Sua conta vai continuar ativa, e se mudar de ideia, dá pra voltar pro Premium quando quiser!</p>
                        <p>Se precisar de ajuda ou tiver qualquer dúvida, é só chamar a gente.</p>
                        <p>Tem certeza que quer cancelar seu plano Premium?</p>
                        <form method="POST">
                            <?php wp_nonce_field('cancel_subscription_nonce'); ?>
                            <button class="cancel-premium-btn" type="submit" name="cancel_subscription" class="btn btn-danger">
                                Cancelar Premium
                            </button>
                        </form>
                        <p>Você pode voltar quando quiser, beleza? 😉</p>
                    <?php
                } else {
                    echo '<h2>Você não possui uma assinatura premium ativa.</h2>';
                }
            } else {
                ?>
                    <script>window.location = "<?php echo home_url('/'); ?>";</script>
                <?php
            }            
        ?>     
        </div>
    </div>
    <div class="page-template_right-side">
    </div>
</div>
<?php get_footer(); ?>