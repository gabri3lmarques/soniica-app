<?php
namespace user;
class Users {
    public function __construct() {
        add_action('admin_menu', array($this, 'addPremiumUsersMenu'));
    }
    /**
     * Adiciona o menu "Usuários Premium" ao painel administrativo
     */
    public function addPremiumUsersMenu() {
        add_menu_page(
            'Usuários Premium', // Título da página
            'Usuários Premium', // Nome do menu
            'manage_options',   // Capacidade necessária
            'premium-users',    // Slug da página
            array($this, 'renderPremiumUsersPage'), // Método que exibirá a página
            'dashicons-id',     // Ícone do menu
            20                  // Posição do menu
        );
    }
    /**
     * Renderiza a página com a lista de usuários e seu status
     */
    public function renderPremiumUsersPage() {
        if (!current_user_can('manage_options')) {
            wp_die('Você não tem permissão para acessar essa página.');
        }
        $users = get_users();
        ?>
        <div class="wrap">
            <h1>Lista de Usuários</h1>
            <table class="wp-list-table widefat fixed striped users">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column">Nome</th>
                        <th scope="col" class="manage-column">E-mail</th>
                        <th scope="col" class="manage-column">Status Premium</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php $is_premium = get_user_meta($user->ID, 'is_premium', true) ? 'Sim' : 'Não'; ?>
                        <tr>
                            <td><?php echo esc_html($user->display_name); ?></td>
                            <td><?php echo esc_html($user->user_email); ?></td>
                            <td><?php echo esc_html($is_premium); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }
    public static function check_user_premium_status() {
        $user_id = get_current_user_id();
        // Verifica no meta do usuário se ele é premium
        $is_premium = get_user_meta($user_id, 'is_premium', true);
        if ($is_premium) {
            // O usuário é premium, podemos verificar a Stripe para garantir que a assinatura está ativa
            return true;
        }
        // Caso o usuário não seja premium, retorna false
        return false;
    }
}