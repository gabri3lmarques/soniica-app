<?php

class PlayerComponent {
    public static function render() {
        return '
        <div id="player-main">
            <div class="info">
                <img class="current-thumb" src="" alt="Thumbnail">
                <div class="text-info">
                    <span class="current-title">Título da música</span>
                    <span class="current-artist">Artista</span>
                    <span class="current-time">00:00</span>
                    <span class="current-genders">Gêneros</span>
                </div>
            </div>
        
            <div class="controls">
                <button class="random">Random</button>
                <button class="previous">Previous</button>
                <button class="play-pause">Play</button>
                <button class="next">Next</button>
                <button class="loop">Loop</button>
                <a class="download-button" disabled>Download</a>
            </div>
            
            <div class="volume-control">
                <input id="volume-slider" type="range" min="0" max="1" step="0.01" value="1">
            </div>
                
            <div class="progress-bar-container">
                <div class="progress-bar"></div>
            </div>
        </div>
        ';
    }
}

// como usar 
// require_once get_template_directory() . '/components/player/PlayerComponent.php';
// echo PlayerComponent::render();
