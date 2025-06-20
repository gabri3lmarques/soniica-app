<?php
class NewSongsNotifier {
    protected int $new_songs_count;
    public function __construct() {
        $this->new_songs_count = $this->count_new_songs();
    }
    protected function count_new_songs(): int {
        $date_24_hours_ago = gmdate('Y-m-d H:i:s', strtotime('-24 hours'));
        $query = new \WP_Query([
            'post_type'      => 'song',
            'post_status'    => 'publish',
            'date_query'     => [
                [
                    'after'     => $date_24_hours_ago,
                    'inclusive' => true,
                    'column'    => 'post_date_gmt',
                ],
            ],
            'fields'         => 'ids',
            'no_found_rows'  => true,
            'posts_per_page' => -1,
        ]);
        return count($query->posts);
    }
    public function render(): void {
        if(is_user_logged_in()){
            if ($this->new_songs_count > 1) {
                echo '<div class="soniica-notification red">';
                echo '<svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><style>.a{fill:#fff}</style><path class="a" d="m15 0h-10c-2.7 0-5 2.2-5 5v6 1c0 2.7 2.3 5 5 5h1.5c0.3 0 0.6 0.1 0.8 0.4l1.5 1.9c0.7 0.9 1.7 0.9 2.4 0l1.5-1.9c0.2-0.3 0.5-0.4 0.8-0.4h1.5c2.7 0 5-2.3 5-5v-7c0-2.8-2.3-5-5-5zm-4 11.8h-6c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h6c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8zm4-5h-10c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h10c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8z"/></svg>';
                echo '<span class="notification-number">'.esc_html($this->new_songs_count).'<span>';
                echo '<div class="notification-message">'.esc_html($this->new_songs_count).' new songs was released.</div>';
                echo '</div>';
            } elseif($this->new_songs_count > 0) {
                echo '<div class="soniica-notification red">';
                echo '<svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><style>.a{fill:#fff}</style><path class="a" d="m15 0h-10c-2.7 0-5 2.2-5 5v6 1c0 2.7 2.3 5 5 5h1.5c0.3 0 0.6 0.1 0.8 0.4l1.5 1.9c0.7 0.9 1.7 0.9 2.4 0l1.5-1.9c0.2-0.3 0.5-0.4 0.8-0.4h1.5c2.7 0 5-2.3 5-5v-7c0-2.8-2.3-5-5-5zm-4 11.8h-6c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h6c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8zm4-5h-10c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h10c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8z"/></svg>';
                echo '<span class="notification-number">'.esc_html($this->new_songs_count).'<span>';
                echo '<div class="notification-message">A new song was released.</div>';
                echo '</div>';
            } else {
                echo '<div class="soniica-notification gray">';
                echo '<svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20"><style>.a{fill:#fff}</style><path class="a" d="m15 0h-10c-2.7 0-5 2.2-5 5v6 1c0 2.7 2.3 5 5 5h1.5c0.3 0 0.6 0.1 0.8 0.4l1.5 1.9c0.7 0.9 1.7 0.9 2.4 0l1.5-1.9c0.2-0.3 0.5-0.4 0.8-0.4h1.5c2.7 0 5-2.3 5-5v-7c0-2.8-2.3-5-5-5zm-4 11.8h-6c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h6c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8zm4-5h-10c-0.4 0-0.7-0.4-0.7-0.8 0-0.4 0.3-0.7 0.7-0.7h10c0.4 0 0.7 0.3 0.7 0.7 0 0.4-0.3 0.8-0.7 0.8z"/></svg>';
                echo '<span class="notification-number">0<span>';
                echo '</div>';
            }
        }
    }
    public function get_count(): int {
        return $this->new_songs_count;
    }
}
// uso
//notifier = new NewSongsNotifier();
//notifier->render(); // mostra a notificação se houver novas músicas