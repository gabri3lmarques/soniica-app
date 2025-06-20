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
                        <h2>We'll Miss You! </h2>
                        <p>Sometimes it's time to follow a new rhythm — and that's okay!</p>
                        <p>By canceling your Premium plan, you’ll still enjoy Soniica, but you’ll lose access to special benefits like:</p>
                        <ul>
                            <li>Unlimited access to exclusive playlists</li>
                            <li>Music downloads for offline listening</li>
                            <li>An ad-free experience</li>
                        </ul>
                        <p>Your account will remain active, and you can upgrade back to Premium anytime if you change your mind!</p>
                        <p>If you need any help or have any questions, we’re here for you.</p>
                        <p>Are you sure you want to cancel your Premium subscription?</p>
                        <form method="POST">
                            <?php wp_nonce_field('cancel_subscription_nonce'); ?>
                            <button class="cancel-premium-btn" type="submit" name="cancel_subscription" class="btn btn-danger">
                                Cancel premium
                            </button>
                        </form>
                        <p>You can always come back whenever you want!</p>
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