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
        $errors[] = 'Preencha todos os campos.';
    }
    if ( ! is_email( $email ) ) {
        $errors[] = 'Email inválido.';
    }
    if ( username_exists( $username ) ) {
        $errors[] = 'O nome de usuário já está em uso.';
    }
    if ( email_exists( $email ) ) {
        $errors[] = 'O email já está em uso.';
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

<div class="registration-page">
    <h2>Cadastre-se</h2>
    <?php if ( ! empty( $errors ) ) : ?>
        <div class="registration-errors">
            <?php foreach ( $errors as $error ) : ?>
                <p><?php echo esc_html( $error ); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <p>
            <label for="username">Nome de Usuário</label>
            <input type="text" name="username" id="username" required>
        </p>
        <p>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
        </p>
        <p>
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" required>
        </p>
        <p>
            <input type="submit" name="submit" value="Registrar">
        </p>
    </form>
</div>

<?php get_footer(); ?>
