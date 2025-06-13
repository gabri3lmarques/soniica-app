<?php
namespace password;

class PasswordRecovery {

  public function __construct() {
    add_action('rest_api_init', [$this, 'register_routes']);
  }

  public function register_routes() {
    register_rest_route('soniica/v1', '/recover', [
      'methods' => 'POST',
      'callback' => [$this, 'handle_recover'],
      'permission_callback' => '__return_true',
    ]);

    register_rest_route('soniica/v1', '/reset', [
      'methods' => 'POST',
      'callback' => [$this, 'handle_reset'],
      'permission_callback' => '__return_true',
    ]);
  }

  public function handle_recover($request) {
    $email = sanitize_email($request->get_param('email'));
    $user = get_user_by('email', $email);

    if (!$user) {
      return ['message' => 'Se o email existir, enviaremos um link.'];
    }

    $token = bin2hex(random_bytes(16));
    update_user_meta($user->ID, 'reset_token', $token);
    update_user_meta($user->ID, 'reset_expires', time() + 3600);

    $link = home_url("/reset-password?token={$token}");

    return [
      'message' => 'Se o email existir, você receberá um link.',
      'reset_link' => $link
    ];
  }

  public function handle_reset($request) {
    $token = sanitize_text_field($request->get_param('token'));
    $new_password = sanitize_text_field($request->get_param('new_password'));

    $users = get_users([
      'meta_key' => 'reset_token',
      'meta_value' => $token,
      'number' => 1
    ]);

    if (empty($users)) {
      return ['message' => 'Token inválido.'];
    }

    $user = $users[0];
    $expires = (int) get_user_meta($user->ID, 'reset_expires', true);

    if (time() > $expires) {
      return ['message' => 'Token expirado.'];
    }

    wp_set_password($new_password, $user->ID);
    delete_user_meta($user->ID, 'reset_token');
    delete_user_meta($user->ID, 'reset_expires');

    return ['message' => 'Senha redefinida com sucesso.'];
  }
}
