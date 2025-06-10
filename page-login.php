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
            <p style="margin-bottom:40px;">
                <a href="/" title="home">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
                </a>
            </p> 
            <h3>Log in</h3>
            <p>Enter your email and password.</p>
            <form action="" method="post">
                    <label for="username">Email</label>
                    <input type="text" name="username" id="username" required>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <div class="checkbox">
                        <input type="checkbox" name="remember"> <span>Remember-me</span>
                    </div>
                    <input class="login-button" type="submit" name="submit" value="Sign in">
                    <?php if ( $login_error ) : ?>
                        <div class="login-error">
                            <?php echo "<p class='error'>Invalid password or username.</p>"; ?>
                        </div>
                    <?php endif; ?>
            </form>
            <p>👉 Don't have an account? <a href="/sign-up">Sign Up Now</a></p>
            <!--/form  -->
        </div>       
    </div>
    <div class="login-form-side"></div>
</div>
<?php get_footer(); ?>
