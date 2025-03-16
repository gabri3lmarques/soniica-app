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
        new category\CategoryImageManager();
        new playlist\Playlist();
        new flash_message\FlashMessage();
        new search\Soniica_Search();
        new svg\SvgIcons();
        new user\Users();
    }
}
