<?php
use svg\SvgIcons;
class PlayerComponent {
    public static function render() {
        $play = SvgIcons::get('play');
        $pause = SvgIcons::get('pause');
        $prev = SvgIcons::get('prev');
        $next = SvgIcons::get('next');
        $loop = SvgIcons::get('loop');
        $random = SvgIcons::get('random');
        $volume = SvgIcons::get('volume');
        $thumb  = get_template_directory_uri() . '/assets/img/logo/soniica.png';
        return '
        <div id="player-main">
            <div class="info">
                <img class="current-thumb" src="'.$thumb.'" alt="Thumbnail">
                <div class="title-artist">
                    <span class="current-title">No song</span>
                    <span class="current-artist">No artist</span>
                </div>
                <span class="current-genders"></span>
            </div>
            <div class="controls-progress-bar">
                <div class="controls">
                    <div class="button random">'.$random.'</div>
                    <div class="button previous">'.$prev.'</div>
                    <div class="button play-pause">'.$play.$pause.'</div>
                    <div class="button next">'.$next.'</div>
                    <div class="button loop">'.$loop.'</div>
                    <a class="download-button" onclick="return handleDownload(event)">Download</a>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar"></div>
                </div>
                <span class="current-time">00:00</span>
            </div>
            <div class="volume-control">
                '.$volume.'<input id="volume-slider" type="range" min="0" max="1" step="0.01" value="1">
            </div>
        </div>
        ';
    }
}

// como usar 
// require_once get_template_directory() . '/components/player/PlayerComponent.php';
// echo PlayerComponent::render();
