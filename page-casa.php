<?php
/*
Template Name: Login Personalizado
*/

use user\Users;

// Verifica se o usuário já está logado
if ( is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
}

$login_error = '';

if ( isset( $_POST['submit'] ) ) {
    $creds = array();
    $creds['user_login']    = sanitize_user( $_POST['username'] );
    $creds['user_password'] = $_POST['password'];
    $creds['remember']      = isset( $_POST['remember'] );

    $user = wp_signon( $creds, false );

    if ( is_wp_error( $user ) ) {
        $login_error = $user->get_error_message();
    } else {
        // Força verificação de status premium imediatamente após login
        Users::check_user_premium_status();

        // Redireciona para a home
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
        <div class="login-form-content"> a
            <h3>Em breve</h3>
            <p>👉 As melhores músicas e trilhas sonoras estarão aqui.</p>
            <!--/form  -->
        </div>       
    </div>
    <div class="login-form-side"></div>
</div>
<?php get_footer(); ?>
