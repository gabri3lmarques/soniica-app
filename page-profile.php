<?php

if(!is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}

use svg\SvgIcons;
use user\Users;
require_once get_template_directory() . '/components/search/Search.php';
$user_id = get_current_user_id();
$profile_url = get_user_meta($user_id, 'profile_picture_url', true);
get_header();
// Verifica se o usuário está logado
$is_premium = Users::check_user_premium_status();
?>

<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="main-content profile">
    <!-- a coluna da esquerda -->
    <div class="sidebar hide-1200">
        <?php include 'components/accordion/accordion.php'; ?>
    </div>
    <!-- o corpo do site -->
    <div class="main profile">
        <p style="margin-bottom:40px;" class="back-to-previous-page">
            <a href="/login">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p>         
        <h2>Sua conta</h2>
        <div class="section">
            <h3>Imagem de perfil</h3>
            <div class="profile-img">
                <?php
                    if($profile_url) {
                        ?>
                        <img class="profile-image" src="<?php echo esc_url($profile_url); ?>">
                        <?php
                    } else {
                        SvgIcons::render('user');
                    }
                ?>
            </div>
            <form id="profile-picture-form" enctype="multipart/form-data">
                <div class="file-picker-div">
                    <input class="file-picker" id="profile_picture" type="file" name="profile_picture" accept="image/*" />
                </div>
                <button class="send-image" type="submit">Enviar imagem</button>
            </form>
            <button id="remove-profile-picture">Deletar imagem</button>
            <div id="upload-status"></div>
        </div>
        <!-- section -->
         <div class="section">
            <h3>User Status</h3>
                <?php
                    if($is_premium){
                        ?>
                        <div class="user-status premium">Plano premium</div>
                        <p>Você já tem nosso melhor plano.</p> 
                        <p>Precisa de uma solução customizada para o seu negócio? Dá um oi e nos diga o que você preicsa<a href="#">hello@soniica.com</a></p>
                        <?php
                    } else {
                        ?>
                        <div class="user-status free">Plano Free</div>
                        <p>Quer ter acesso a todo o poder que só uma contra premium te dá? <a href="/get-premium">Seja premium</a> </p>
                        <?php
                    }
                ?>
        </div>
        <!-- /section -->
        <div class="section">
            <h3>Ajuda</h3>
            <p>Precisa de ajuda? É só chamar aqui <a href="#">help@soniica.com</a></p>
        </div>
        <!-- /section -->
        <div class="section">
            <h3>Cancelar conta</h3>
            <?php if($is_premium): ?>
                <p>Antes de excluir sua conta, certifique-se de cancelar sua assinatura para que a gente pare de cobrar você.</p>
                <p><a href="/cancel-premium">Clique aqui</a> para acessar a página de cancelamento de assinatura.</p>
            <?php endif; ?>
            <p class="delete-account-warning">Clicando no botão abaixo, <span>Você deletará permanentemente sua conta</span>.</p>
            <button id="delete-account-btn">☠️ Delete minha conta!</button>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<script>
document.getElementById('delete-account-btn')?.addEventListener('click', function() {
    if (confirm("Tem certeza que deseja deletar sua conta? Essa ação é irreversível.")) {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action: 'delete_user_account',
                _ajax_nonce: '<?php echo wp_create_nonce('delete_user_account_nonce'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Conta deletada com sucesso!');
                window.location.href = '<?php echo home_url(); ?>';
            } else {
                alert('Error deleting account.: ' + data.data);
            }
        });
    }
});
</script>
<?php get_footer(); ?>
