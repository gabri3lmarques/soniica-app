<?php

/*
Template Name: Login Personalizado
*/

// Verifica se o usuário já está logado
if ( is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
}

use flash_message\FlashMessage;

$login_error = '';

if ( isset( $_POST['submit'] ) ) {
    // Obtém as credenciais do usuário
    $creds = array();
    $creds['user_login']    = sanitize_user( $_POST['username'] );
    $creds['user_password'] = $_POST['password'];
    $creds['remember']      = isset( $_POST['remember'] );
    
    // Realiza o login
    $user = wp_signon( $creds, false );

    // Se houver erro no login
    if ( is_wp_error( $user ) ) {
        $login_error = $user->get_error_message();
    } else {
        // Aqui verificamos se o usuário é premium
        $stripe_service = new \stripe\StripeService();
        $is_premium = $stripe_service->isUserPremium($user->ID);

        // Atualiza o status do usuário como premium ou não
        update_user_meta($user->ID, 'is_premium', $is_premium);

        // Redireciona para a página inicial
        wp_redirect( home_url() );
        exit;
    }
}

get_header();
?>

<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>


<div class="login-page">
    <div class="login-form">
        <div class="login-form-content">
            <h3>Lorem Ipsum</h3>
            <p>Lorem i psum dollor sit ammet.</p>
            <form action="" method="post">
                    <label for="username">Email</label>
                    <input type="text" name="username" id="username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <div class="checkbox">
                        <input type="checkbox" name="remember"> Remember-me
                    </div>
                    <input class="login-button" type="submit" name="submit" value="Sign in">
                    <?php if ( $login_error ) : ?>
                        <div class="login-error">
                            <?php echo "<p class='error'>Invalid password or username.</p>"; ?>
                        </div>
                    <?php endif; ?>
            </form>
            <p>Don't have an account? <a href="/sign-up">Sign Up Now</a></p>
            <!--/form  -->
        </div>       
    </div>
    <div class="login-form-side"></div>
</div>

<?php get_footer(); ?>
