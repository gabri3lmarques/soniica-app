<?php
namespace flash_message;
class FlashMessage {
    public function __construct() {
        if (!session_id()) {
            session_start(); // Garantir que a sessão está ativa
        }
    }
    // Define uma flash message
    public static function set($key, $message) {
        $_SESSION['flash_messages'][$key] = $message;
    }
    // Obtém e remove a flash message da sessão
    public static function get($key) {
        if (!isset($_SESSION['flash_messages'][$key])) {
            return null;
        }
        $message = $_SESSION['flash_messages'][$key];
        unset($_SESSION['flash_messages'][$key]); // Remove após recuperar
        return $message;
    }
    // Renderiza a mensagem
    public static function render($message) {
        echo '
        <div class="flash-message-overlay">
            <div class="flash-message-content">
                <div class="flash-message-header">
                    <svg class="fm-close" version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20">
                        <style>.a{fill:#fff}</style>
                        <path fill-rule="evenodd" class="a" d="m0.4 0.4c0.6-0.5 1.5-0.5 2.1 0l7.5 7.6 7.5-7.6c0.6-0.5 1.5-0.5 2.1 0 0.5 0.6 0.5 1.5 0 2.1l-7.6 7.5 7.6 7.5c0.5 0.6 0.5 1.5 0 2.1-0.6 0.5-1.5 0.5-2.1 0l-7.5-7.6-7.5 7.6c-0.6 0.5-1.5 0.5-2.1 0-0.5-0.6-0.5-1.5 0-2.1l7.6-7.5-7.6-7.5c-0.5-0.6-0.5-1.5 0-2.1z"/>
                    </svg>
                </div>
                <div class="flash-message-icon">
                    <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100"><style>.a{fill:#fff}</style><path fill-rule="evenodd" class="a" d="m34.4 97.5l-1.3-2.1c-2-3.3-3-5-4.5-6-1.6-0.9-3.7-1-7.9-1.1-3.9-0.1-6.5-0.5-8.7-1.4-4.7-2-8.5-5.7-10.4-10.4-1.5-3.6-1.5-8-1.5-17v-3.8c0-12.6 0-18.9 2.8-23.6 1.6-2.6 3.8-4.7 6.4-6.3 4.6-2.9 10.9-2.9 23.5-2.9h11.5c12.6 0 18.9 0 23.6 2.9 2.6 1.6 4.7 3.7 6.3 6.3 2.9 4.7 2.9 11 2.9 23.6v3.8c0 9 0 13.4-1.5 17-2 4.7-5.7 8.4-10.4 10.4-2.3 0.9-4.9 1.3-8.8 1.4-4.1 0.1-6.2 0.2-7.8 1.1-1.6 1-2.6 2.7-4.6 6l-1.2 2.1c-1.9 3.2-6.5 3.2-8.4 0zm25.8-40.9c0-2.6-2.1-4.8-4.8-4.8-2.6 0-4.8 2.2-4.8 4.8 0 2.7 2.2 4.8 4.8 4.8 2.7 0 4.8-2.1 4.8-4.8zm-16.8 0c0-2.6-2.2-4.8-4.8-4.8-2.7 0-4.8 2.2-4.8 4.8 0 2.7 2.1 4.8 4.8 4.8 2.6 0 4.8-2.1 4.8-4.8zm-16.8 0c0-2.6-2.2-4.8-4.9-4.8-2.6 0-4.8 2.2-4.8 4.8 0 2.7 2.2 4.8 4.8 4.8 2.7 0 4.9-2.1 4.9-4.8zm39.2-56.5c5.8 0 10.3 0 14 0.3 3.8 0.4 6.9 1.2 9.8 2.9 2.9 1.8 5.3 4.2 7.1 7.1 1.7 2.9 2.5 6 2.9 9.8 0.3 3.7 0.3 8.2 0.3 14v3.9c0 4.1 0 7.4-0.2 10-0.2 2.7-0.5 5-1.4 7.2-2.2 5.3-6.5 9.5-11.8 11.7q-0.2 0.1-0.4 0.1-0.9 0.4-1.6 0.7c0-2.4 0-5 0-8.1v-4.4c0-6 0-11-0.3-15.1-0.5-4.3-1.3-8.3-3.6-12-2.2-3.6-5.2-6.6-8.8-8.8-3.7-2.3-7.7-3.1-12-3.6-4.1-0.3-9.1-0.3-15.1-0.3h-12.2c-4.1 0-7.7 0-10.9 0.1q0.2-0.8 0.6-1.8 0.6-1.8 1.5-3.4c1.8-2.9 4.3-5.3 7.2-7.1 2.8-1.7 6-2.5 9.7-2.9 3.7-0.3 8.3-0.3 14-0.3z"/></svg>                                
                </div>
                <div class="flash-message-body">
                    ' . htmlspecialchars($message) . '
                </div>
            </div>
        </div>';
    }
}
//  usage
//  FlashMessage::set('success', 'success message');
//  pega a mensagem de sucesso, se existir
//  $success_message = FlashMessage::get('success');
//  if ($success_message) {
//    FlashMessage::render($success_message);
//  }