<?php
namespace life_cycle;
use WP_Post;
class LifeCycleManager {
    public static function init() {
        add_action('wp_insert_post', [__CLASS__, 'schedule_life_cycle_update'], 10, 2);
        add_action('before_delete_post', [__CLASS__, 'cancel_life_cycle_update']);
        add_action('soniica_update_life_cycle', [__CLASS__, 'update_life_cycle'], 10, 1);
    }
    public static function schedule_life_cycle_update($post_id, WP_Post $post) {
        if ($post->post_type !== 'song') {
            return;
        }
        $life_cycle = get_field('song_life_cycle', $post_id);
        if ($life_cycle !== 'new') {
            return;
        }
        if (!wp_next_scheduled('soniica_update_life_cycle', [$post_id])) {
            wp_schedule_single_event(time() + 86400, 'soniica_update_life_cycle', [$post_id]); // 86400 segundos = 24 horas
        }
    }
    public static function cancel_life_cycle_update($post_id) {
        if (get_post_type($post_id) !== 'song') {
            return;
        }
        $timestamp = wp_next_scheduled('soniica_update_life_cycle', [$post_id]);
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'soniica_update_life_cycle', [$post_id]);
        }
    }
    public static function update_life_cycle($post_id) {
        update_field('song_life_cycle', 'old', $post_id);
    }
}
// Inicializa a classe
LifeCycleManager::init();
?>
