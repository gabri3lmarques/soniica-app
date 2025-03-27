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
}
