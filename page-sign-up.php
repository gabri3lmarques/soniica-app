<?php
/*
Template Name: Cadastro Personalizado
*/

if ( is_user_logged_in() ) {
    wp_redirect( home_url() );
    exit;
}

$errors = array();

if ( isset( $_POST['submit'] ) ) {
    // Sanitização dos dados
    $username = sanitize_user( $_POST['username'] );
    $email    = sanitize_email( $_POST['email'] );
    $password = $_POST['password'];

    // Validações
    if ( empty( $username ) || empty( $email ) || empty( $password ) ) {
        $errors[] = 'Please fill in all the fields.';
    }
    if ( ! is_email( $email ) ) {
        $errors[] = 'Invalid email.';
    }
    if ( username_exists( $username ) ) {
        $errors[] = 'The username is already taken.';
    }
    if ( email_exists( $email ) ) {
        $errors[] = 'The email is already taken.';
    }

    // Se não houver erros, cria o usuário
    if ( empty( $errors ) ) {
        $user_id = wp_create_user( $username, $password, $email );
        if ( ! is_wp_error( $user_id ) ) {
            // Opcional: autenticar o usuário logo após o cadastro
            wp_set_current_user( $user_id );
            wp_set_auth_cookie( $user_id );
            wp_redirect( home_url() );
            exit;
        } else {
            $errors[] = $user_id->get_error_message();
        }
    }
}

get_header();
?>

<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>

<div class="registration-page">
    <div class="sign-up-side"></div>
    <div class="sign-up-container">
        <div class="sign-up-form">
            <h3>Sign up</h3>
            <p>Please fill in all the fields below.</p>
            <form action="" method="post">
                <label for="username">Nome de Usuário</label>
                <input type="text" name="username" id="username" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>
                <label for="password">Senha</label>
                <input type="password" name="password" id="password" required>
                <input class="signup-button" type="submit" name="submit" value="Register">
            </form>
            <?php if ( ! empty( $errors ) ) : ?>
                <div class="registration-errors">
                    <?php foreach ( $errors as $error ) : ?>
                        <p class="error"><?php echo $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <p>👉 Already have an account? <a href="/login">Log in</a></p>         
        </div>
    </div>
</div>

<?php get_footer(); ?>
