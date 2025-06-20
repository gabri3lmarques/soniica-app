<?php
namespace download;
use user\Users;
class DownloadController {
    //private const DOWNLOAD_COOLDOWN = 86400; // 24 horas em segundos
    private const DOWNLOAD_COOLDOWN = 1800; // 1800 30 min em segundos
    public static function canDownload() {
        if (!is_user_logged_in()) {
            return [
                'allowed' => false,
                'message' => 'Você precisa estar logado para fazer download.'
            ];
        }
        if (Users::check_user_premium_status()) {
            return [
                'allowed' => true,
                'message' => 'Download permitido'
            ];
        }
        return self::checkLastDownload();
    }

    private static function checkLastDownload() {
        $user_id = get_current_user_id();
        $last_download = get_user_meta($user_id, 'last_download_timestamp', true);
        $credits = get_user_meta($user_id, 'credits', true);
        if ($credits === '' || $credits === false) {
            $credits = 0;
        }
        if (empty($last_download) || $credits > 0) {
            return [
                'allowed' => true,
                'message' => 'Download permitido'
            ];
        }
        $time_passed = time() - intval($last_download);
        if ($time_passed >= self::DOWNLOAD_COOLDOWN) {
            update_user_meta($user_id, 'credits', 1);
            return [
                'allowed' => true,
                'message' => 'Download permitido'
            ];
        }
        $hours_remaining = ceil((self::DOWNLOAD_COOLDOWN - $time_passed) / 60);
        return [
            'allowed' => false,
            'message' => "⏰ Aguarde mais {$hours_remaining} minutos para fazer outro download."
        ];
    }

    public static function registerDownload() {
        $user_id = get_current_user_id();
        update_user_meta($user_id, 'last_download_timestamp', time());
        $credits = get_user_meta($user_id, 'credits', true);
        if ($credits === '' || $credits === false) {
            $credits = 0;
        }
        update_user_meta($user_id, 'credits', $credits - 1);
    }
    
    public static function getDownloadLink($secure_download_url) {
        $download_check = self::canDownload();
        if (!$download_check['allowed']) {
            return sprintf(
                '<a class="download-link" data-href="' . $secure_download_url .  '" href="/get-premium" onclick="return handleDownload(event)" download><img style="width:15px" src="'.get_template_directory_uri().'/assets/img/icons/download.png"></a>',
                esc_attr($download_check['message'])
            );
        }
        return sprintf(
            '<a class="download-link" data-href="' . $secure_download_url .  '" href="%s" onclick="return handleDownload(event)" download><img style="width:15px" src="'.get_template_directory_uri().'/assets/img/icons/download.png"></a>',
            $secure_download_url
        );
    }
}