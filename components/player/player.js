class Player {
    constructor() {
        this.audio = new Audio();
        this.currentSong = null;
        this.currentPlaylist = null;
        this.isPlaying = false;
        this.isLooping = false;
        this.isRandom = false;

        // Elementos do player principal
        this.titleElement = document.querySelector('.current-title');
        this.artistElement = document.querySelector('.current-artist');
        this.timeElement = document.querySelector('.current-time');
        this.gendersElement = document.querySelector('.current-genders');
        this.thumbElement = document.querySelector('.current-thumb');
        this.playPauseButton = document.querySelector('.play-pause');
        this.nextButton = document.querySelector('.next-song');
        this.previousButton = document.querySelector('.previous-song');
        this.loopButton = document.querySelector('.loop');
        this.randomButton = document.querySelector('.random');
        this.progressBarContainer = document.querySelector('.progress-bar-container');
        this.progressBar = document.querySelector('.progress-bar');
        this.volumeSlider = document.querySelector('#volume-slider');

        // Event listeners (APENAS se os elementos existem)
        if (this.playPauseButton) {
            this.playPauseButton.addEventListener('click', () => this.togglePlayPause());
        }

        if (this.nextButton) {
            this.nextButton.addEventListener('click', () => this.next());
        }

        if (this.previousButton) {
            this.previousButton.addEventListener('click', () => this.previous());
        }

        if (this.loopButton) {
            this.loopButton.addEventListener('click', () => this.toggleLoop());
        }

        if (this.randomButton) {
            this.randomButton.addEventListener('click', () => this.toggleRandom());
        }

        if (this.volumeSlider) {
            this.volumeSlider.addEventListener('input', (e) => this.adjustVolume(e));
            this.audio.volume = this.volumeSlider.value;
        }

        if (this.progressBarContainer) {
            this.progressBarContainer.addEventListener('click', (e) => this.seek(e));
            this.progressBarContainer.addEventListener('mousedown', (e) => this.startDrag(e));
        }

        // Event listeners do áudio (sempre existem)
        this.audio.addEventListener('timeupdate', () => this.updateProgressBar());
        this.audio.addEventListener('ended', () => this.handleSongEnd());

        // Inicializa a primeira música automaticamente
        this.initializeFirstSong();
    }

    isPlayerDisabled() {
        return document.querySelector('#player-main')?.classList.contains('disabled');
    }

    adjustVolume(event) {
        const newVolume = event.target.value;
        const rightVolume = newVolume * 100;
        const leftVolume = 100 - rightVolume;
        this.audio.volume = newVolume;
        if (this.volumeSlider) {
            this.volumeSlider.style.background = `linear-gradient(to right, #e95265, #207dff ${rightVolume}%, #272727 ${leftVolume}%)`;
        }
    }

    initializeFirstSong() {
        const firstPlaylist = document.querySelector('.playlist');
        if (!firstPlaylist) return;
        
        const firstSong = firstPlaylist.querySelector('.song');
        if (!firstSong) return;
        
        this.currentPlaylist = firstPlaylist;
        this.currentSong = firstSong;
        
        const encodedUrl = firstSong.dataset.src;
        if (!encodedUrl) return;
        
        try {
            const decodedUrl = atob(encodedUrl);
            if (!decodedUrl.startsWith('http://') && !decodedUrl.startsWith('https://')) {
                console.error('Invalid decoded URL:', decodedUrl);
                return;
            }
            this.audio.src = decodedUrl;
            this.updatePlayerInfo(firstSong);
        } catch (e) {
            console.error('Error decoding URL:', e);
        }
    }

    toggleRandom() {
        if (this.isPlayerDisabled()) {
            return;
        }
        this.isRandom = !this.isRandom;
        
        if (this.randomButton) {
            if (this.isRandom) {
                this.randomButton.classList.add('active');
            } else {
                this.randomButton.classList.remove('active');
            }
        }
    }

    getRandomSong() {
        if (!this.currentPlaylist) return null;

        const playlistSongs = Array.from(this.currentPlaylist.querySelectorAll('.song'));

        if (playlistSongs.length <= 1) return playlistSongs[0] || null;

        const otherSongs = playlistSongs.filter(song => song !== this.currentSong);
        const randomIndex = Math.floor(Math.random() * otherSongs.length);

        return otherSongs[randomIndex];
    }

    getNextSong() {
        if (this.isRandom) {
            return this.getRandomSong();
        }
        if (!this.currentPlaylist) return null;
        
        const songs = [...this.currentPlaylist.querySelectorAll('.song')];
        const currentIndex = songs.findIndex(song => song === this.currentSong);
        return songs[currentIndex + 1] || (this.isLooping ? songs[0] : null);
    }

    getPreviousSong() {
        if (!this.currentPlaylist) return null;
        
        const songs = [...this.currentPlaylist.querySelectorAll('.song')];
        const currentIndex = songs.findIndex(song => song === this.currentSong);
        return songs[currentIndex - 1] || (this.isLooping ? songs[songs.length - 1] : null);
    }

    play(songElement, playlistElement) {
        if (this.isPlayerDisabled()) {
            return;
        }
        try {
            const encodedUrl = songElement.dataset.src;
            if (!encodedUrl) {
                console.error('No encoded URL found');
                return;
            }
            
            const decodedUrl = atob(encodedUrl);
            if (!decodedUrl.startsWith('http://') && !decodedUrl.startsWith('https://')) {
                console.error('Invalid decoded URL:', decodedUrl);
                return;
            }

            if (this.currentSong && this.currentSong !== songElement) {
                const playButton = this.currentSong.querySelector('.play-button');
                if (playButton) playButton.classList.remove('active');
                this.currentSong.classList.remove('active');
            }

            if (this.audio.src !== decodedUrl) {
                this.audio.src = decodedUrl;
            }

            this.audio.play();
            this.isPlaying = true;
            this.currentSong = songElement;
            this.currentPlaylist = playlistElement;
            this.updatePlayerInfo(songElement);
            this.syncButtons(songElement);

        } catch (e) {
            console.error('Error playing song:', e);
        }
    }

    pause(songElement = null) {
        this.audio.pause();
        this.isPlaying = false;
        
        if (songElement) {
            const playButton = songElement.querySelector('.play-button');
            if (playButton) playButton.classList.remove('active');
            songElement.classList.remove('active');
        }
        
        if (this.playPauseButton) {
            this.playPauseButton.classList.remove('active');
        }
    }

    togglePlayPause() {
        if (this.isPlayerDisabled()) {
            return;
        }
        if (this.isPlaying) {
            this.pause(this.currentSong);
        } else {
            this.audio.play();
            this.isPlaying = true;
            
            if (this.currentSong) {
                const playButton = this.currentSong.querySelector('.play-button');
                if (playButton) playButton.classList.add('active');
                this.currentSong.classList.add('active');
            }
            
            if (this.playPauseButton) {
                this.playPauseButton.classList.add('active');
            }
        }
    }

    toggleLoop() {
        if (this.isPlayerDisabled()) {
            return;
        }
        this.isLooping = !this.isLooping;
        
        if (this.loopButton) {
            if (this.isLooping) {
                this.loopButton.classList.add('active');
            } else {
                this.loopButton.classList.remove('active');
            }
        }
    }

    next() {
        if (this.isPlayerDisabled()) {
            return;
        }
        const nextSong = this.getNextSong();
        if (nextSong) {
            this.play(nextSong, this.currentPlaylist);
        }
    }

    previous() {
        if (this.isPlayerDisabled()) {
            return;
        }
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
        if (!songElement) return;
        
        const titleEl = songElement.querySelector('.title');
        const artistEl = songElement.querySelector('.artist');
        const timeEl = songElement.querySelector('.time');
        const thumbEl = songElement.querySelector('.thumb');

        if (this.titleElement && titleEl) {
            this.titleElement.textContent = titleEl.textContent;
        }
        if (this.artistElement && artistEl) {
            this.artistElement.textContent = artistEl.textContent;
        }
        if (this.timeElement && timeEl) {
            this.timeElement.textContent = timeEl.textContent;
        }
        if (this.thumbElement && thumbEl) {
            this.thumbElement.src = thumbEl.src;
        }
    }

    syncButtons(songElement) {
        document.querySelectorAll('.play-button').forEach(button => button.classList.remove('active'));
        document.querySelectorAll('.song').forEach(song => song.classList.remove('active'));

        if (songElement) {
            const playButton = songElement.querySelector('.play-button');
            if (playButton) playButton.classList.add('active');
            songElement.classList.add('active');
            
            if (this.playPauseButton) {
                this.playPauseButton.classList.add('active');
            }
        } else {
            if (this.playPauseButton) {
                this.playPauseButton.classList.remove('active');
            }
        }
    }

    updateProgressBar() {
        if (!this.progressBar || !this.audio.duration) return;
        
        const progress = (this.audio.currentTime / this.audio.duration) * 100;
        this.progressBar.style.width = `${progress}%`;
    }

    seek(event) {
        if (!this.progressBarContainer || !this.audio.duration) return;
        
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
}

// Inicializar o player apenas se o elemento existir
if (document.querySelector('#player-main')) {
    window.globalPlayer = new Player(); // Define como global

    // Configurar eventos para as músicas
    document.addEventListener('click', (event) => {
        const playButton = event.target.closest('.play-button');
        if (!playButton) return;

        const song = playButton.closest('.song');
        const playlist = playButton.closest('.playlist');
        
        if (!song || !playlist) return;

        if (window.globalPlayer && window.globalPlayer.currentSong === song && window.globalPlayer.isPlaying) {
            window.globalPlayer.pause(song);
        } else if (window.globalPlayer) {
            window.globalPlayer.play(song, playlist);
        }
    });
}