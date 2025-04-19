<?php

class Theme {
    public function __construct() {
        $this->setup_theme();
        $this->init_classes();
    }

    private function setup_theme() {
        add_action('after_setup_theme', [$this, 'theme_supports']);
    }

    public function theme_supports() {
        add_theme_support('title-tag');
    }

    private function init_classes() {
        new assets\Assets();
        new download_api\DownloadAPI();
        new category\CategoryImageManager();
        new search\Soniica_Search();
        new svg\SvgIcons();
        new stripe\StripeService();
        new user\Users();
        new life_cycle\LifeCycleManager();
        new flash_message\FlashMessage();
        new playlist\Playlist();
    }
}
