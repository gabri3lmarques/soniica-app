<?php
/*
Template Name: Login Personalizado
*/

// Verifica se o usuário já está logado
if ( is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
}

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
    <h2>Login</h2>

    <?php if ( $login_error ) : ?>
        <div class="login-error">
            <p><?php echo esc_html( $login_error ); ?></p>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <p>
            <label for="username">Nome de Usuário</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <label>
                <input type="checkbox" name="remember"> Lembrar-me
            </label>
        </p>
        <p>
            <input type="submit" name="submit" value="Entrar">
        </p>
    </form>
</div>

<?php get_footer(); ?>
