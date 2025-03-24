class Player {
    constructor() {
        this.audio = new Audio();
        this.currentSong = null;
        this.currentPlaylist = null;
        this.isPlaying = false;
        this.isLooping = false; // Estado do loop
        this.isRandom = false; // Estado do random

        // Elementos do player principal
        this.titleElement = document.querySelector('.current-title');
        this.artistElement = document.querySelector('.current-artist');
        this.timeElement = document.querySelector('.current-time');
        this.gendersElement = document.querySelector('.current-genders');
        this.thumbElement = document.querySelector('.current-thumb');
        this.playPauseButton = document.querySelector('.play-pause');
        this.nextButton = document.querySelector('.next');
        this.previousButton = document.querySelector('.previous');
        this.loopButton = document.querySelector('.loop');
        this.randomButton = document.querySelector('.random'); // Botão de random
        this.progressBarContainer = document.querySelector('.progress-bar-container');
        this.progressBar = document.querySelector('.progress-bar');
        this.volumeSlider = document.querySelector('#volume-slider'); // Slider de volume
        if(document.querySelector('.download-link')){this.downloadButton = document.querySelector('.download-button')}
       

        // Event listeners
        this.playPauseButton.addEventListener('click', () => this.togglePlayPause());
        this.nextButton.addEventListener('click', () => this.next());
        this.previousButton.addEventListener('click', () => this.previous());
        this.loopButton.addEventListener('click', () => this.toggleLoop());
        this.randomButton.addEventListener('click', () => this.toggleRandom());
        this.audio.addEventListener('timeupdate', () => this.updateProgressBar());
        this.audio.addEventListener('ended', () => this.handleSongEnd());
        this.volumeSlider.addEventListener('input', (e) => this.adjustVolume(e));

        // Eventos para a barra de progresso
        this.progressBarContainer.addEventListener('click', (e) => this.seek(e));
        this.progressBarContainer.addEventListener('mousedown', (e) => this.startDrag(e));

        // Inicializar a primeira música automaticamente
        this.initializeFirstSong();
        
        this.audio.volume = this.volumeSlider.value;
    }
    
    adjustVolume(event) {
        // Ajustar o volume do elemento de áudio com base no slider
        const newVolume = event.target.value;
        this.audio.volume = newVolume;
    }

    initializeFirstSong() {
        const firstPlaylist = document.querySelector('.playlist');
        if (!firstPlaylist) return;

        const firstSong = firstPlaylist.querySelector('.song');
        if (!firstSong) return;

        this.currentPlaylist = firstPlaylist;
        this.currentSong = firstSong;
        this.audio.src = firstSong.dataset.src;

        this.updatePlayerInfo(firstSong);

        if(this.downloadButton){
            this.updateDownloadButton(firstSong);
        }

    }

    toggleRandom() {
        this.isRandom = !this.isRandom;

        // Adicionar ou remover a classe 'active' no botão random
        if (this.isRandom) {
            this.randomButton.classList.add('active');
        } else {
            this.randomButton.classList.remove('active');
        }
    }

    getRandomSong() {
        const songs = [...this.currentPlaylist.querySelectorAll('.song')];
        let randomSong;

        do {
            randomSong = songs[Math.floor(Math.random() * songs.length)];
        } while (randomSong === this.currentSong && songs.length > 1);

        return randomSong;
    }

    getNextSong() {
        if (this.isRandom) {
            return this.getRandomSong();
        }

        const songs = [...this.currentPlaylist.querySelectorAll('.song')];
        const currentIndex = songs.findIndex(song => song === this.currentSong);
        return songs[currentIndex + 1] || (this.isLooping ? songs[0] : null);
    }

    getPreviousSong() {
        const songs = [...this.currentPlaylist.querySelectorAll('.song')];
        const currentIndex = songs.findIndex(song => song === this.currentSong);
        return songs[currentIndex - 1] || (this.isLooping ? songs[songs.length - 1] : null);
    }

    play(songElement, playlistElement) {
        const songSrc = songElement.dataset.src;

        if (this.currentSong && this.currentSong !== songElement) {
            this.currentSong.querySelector('.play-button').classList.remove('active');
            this.currentSong.classList.remove('active');
        }

        if (this.audio.src !== songSrc) {
            this.audio.src = songSrc;
        }
        this.audio.play();
        this.isPlaying = true;
        this.currentSong = songElement;
        this.currentPlaylist = playlistElement;

        this.updatePlayerInfo(songElement);
        this.syncButtons(songElement);

        this.updateDownloadButton(songElement);
    }

    pause(songElement) {
        this.audio.pause();
        this.isPlaying = false;
        songElement.querySelector('.play-button').classList.remove('active');
        songElement.classList.remove('active');
        this.playPauseButton.classList.remove('active');
    }

    togglePlayPause() {
        if (this.isPlaying) {
            this.pause(this.currentSong);
            this.playPauseButton.textContent = 'Play';
        } else {
            this.audio.play();
            this.isPlaying = true;
            this.currentSong.querySelector('.play-button').classList.add('active');
            this.currentSong.classList.add('active');
            this.playPauseButton.classList.add('active');
            this.playPauseButton.textContent = 'Pause';
        }
    }

    toggleLoop() {
        this.isLooping = !this.isLooping;

        if (this.isLooping) {
            this.loopButton.classList.add('active');
        } else {
            this.loopButton.classList.remove('active');
        }
    }

    next() {
        const nextSong = this.getNextSong();
        if (nextSong) {
            this.play(nextSong, this.currentPlaylist);
        }
    }

    previous() {
        if (this.audio.currentTime > 2) {
            this.audio.currentTime = 0;
        } else {
            const previousSong = this.getPreviousSong();
            if (previousSong) {
                this.play(previousSong, this.currentPlaylist);
            }
        }
    }

    handleSongEnd() {
        const nextSong = this.getNextSong();
        if (nextSong) {
            this.play(nextSong, this.currentPlaylist);
        } else {
            this.isPlaying = false;
            this.audio.currentTime = 0;
            this.syncButtons(null);
        }
    }

    updatePlayerInfo(songElement) {
        this.titleElement.textContent = songElement.querySelector('.title').textContent;
        this.artistElement.textContent = songElement.querySelector('.artist').textContent;
        this.timeElement.textContent = songElement.querySelector('.time').textContent;

        const genders = [...songElement.querySelectorAll('.genders li')].map(li => li.textContent);
        this.gendersElement.textContent = genders.join(', ');

        const thumbSrc = songElement.querySelector('.thumb').src;
        this.thumbElement.src = thumbSrc;
    }

    syncButtons(songElement) {
        document.querySelectorAll('.play-button').forEach(button => button.classList.remove('active'));
        document.querySelectorAll('.song').forEach(song => song.classList.remove('active'));

        if (songElement) {
            songElement.querySelector('.play-button').classList.add('active');
            songElement.classList.add('active');
            this.playPauseButton.classList.add('active');
            this.playPauseButton.textContent = 'Pause';
        } else {
            this.playPauseButton.classList.remove('active');
            this.playPauseButton.textContent = 'Play';
        }
    }

    updateProgressBar() {
        const progress = (this.audio.currentTime / this.audio.duration) * 100;
        this.progressBar.style.width = `${progress}%`;
    }

    seek(event) {
        const rect = this.progressBarContainer.getBoundingClientRect();
        const offsetX = event.clientX - rect.left;
        const percentage = offsetX / rect.width;
        this.audio.currentTime = percentage * this.audio.duration;
    }

    startDrag(event) {
        const dragHandler = (e) => this.seek(e);
        const stopDrag = () => {
            document.removeEventListener('mousemove', dragHandler);
            document.removeEventListener('mouseup', stopDrag);
        };

        document.addEventListener('mousemove', dragHandler);
        document.addEventListener('mouseup', stopDrag);

        this.seek(event);
    }

    updateDownloadButton(songElement) {
        if(this.downloadButton){
            const downloadLink = songElement.querySelector('.download-link').href;
            this.downloadButton.setAttribute('href', downloadLink);
            this.downloadButton.removeAttribute('disabled');
        }
    }
}



// Inicializar o player apenas se o elemento existir
if (document.querySelector('#player-main')) {
    const player = new Player();

    // Configurar eventos para as músicas
    document.addEventListener('click', (event) => {
        const playButton = event.target.closest('.play-button');
        if (!playButton) return;

        const song = playButton.closest('.song');
        const playlist = playButton.closest('.playlist');
        
        if (!song || !playlist) return;

        if (player.currentSong === song && player.isPlaying) {
            player.pause(song);
        } else {
            player.play(song, playlist);
        }
    });
}