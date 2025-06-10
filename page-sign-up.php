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
            <p style="margin-bottom:40px;">
                <a href="/login">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
                </a>
            </p> 
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
