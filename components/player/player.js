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
        this.nextButton = document.querySelector('.next-song');
        this.previousButton = document.querySelector('.previous-song');
        this.loopButton = document.querySelector('.loop');
        this.randomButton = document.querySelector('.random'); // Botão de random
        this.progressBarContainer = document.querySelector('.progress-bar-container');
        this.progressBar = document.querySelector('.progress-bar');
        this.volumeSlider = document.querySelector('#volume-slider'); // Slider de volume
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
    isPlayerDisabled() {
        return document.querySelector('#player-main')?.classList.contains('disabled');
    }
    adjustVolume(event) {
        // Ajustar o volume do elemento de áudio com base no slider
        const newVolume = event.target.value;
        const rightVolume = newVolume * 100;
        const leftVolume = 100 - rightVolume;
        this.audio.volume = newVolume;
        this.volumeSlider.style.background = `linear-gradient(to right, #e95265, #207dff ${rightVolume}%, #272727 ${leftVolume}%)`;
    }
    initializeFirstSong() {
        const firstPlaylist = document.querySelector('.playlist');
        if (!firstPlaylist) return;
        const firstSong = firstPlaylist.querySelector('.song');
        if (!firstSong) return;
        this.currentPlaylist = firstPlaylist;
        this.currentSong = firstSong;
        // Fix: songElement was undefined, should use firstSong
        const encodedUrl = firstSong.dataset.src;
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
        if(this.isPlayerDisabled()){
            return
        }
        this.isRandom = !this.isRandom;
        // Adicionar ou remover a classe 'active' no botão random
        if (this.isRandom) {
            this.randomButton.classList.add('active');
        } else {
            this.randomButton.classList.remove('active');
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
        if(this.isPlayerDisabled()){
            return
        }
        try {
            const encodedUrl = songElement.dataset.src;
            const decodedUrl = atob(encodedUrl);
            if (!decodedUrl.startsWith('http://') && !decodedUrl.startsWith('https://')) {
                console.error('Invalid decoded URL:', decodedUrl);
                return;
            }
            if (this.currentSong && this.currentSong !== songElement) {
                this.currentSong.querySelector('.play-button').classList.remove('active');
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
        this.playPauseButton.classList.remove('active');
    }
    togglePlayPause() {
        if(this.isPlayerDisabled()){
            return
        }
        if (this.isPlaying) {
            this.pause(this.currentSong);
        } else {
            this.audio.play();
            this.isPlaying = true;
            this.currentSong.querySelector('.play-button').classList.add('active');
            this.currentSong.classList.add('active');
            this.playPauseButton.classList.add('active');
        }
    }
    toggleLoop() {
        if(this.isPlayerDisabled()){
            return
        }
        this.isLooping = !this.isLooping;
        if (this.isLooping) {
            this.loopButton.classList.add('active');
        } else {
            this.loopButton.classList.remove('active');
        }
    }
    next() {
        if(this.isPlayerDisabled()){
            return
        }
        const nextSong = this.getNextSong();
        if (nextSong) {
            this.play(nextSong, this.currentPlaylist);
        }
    }
    previous() {
        if(this.isPlayerDisabled()){
            return
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
        this.titleElement.textContent = songElement.querySelector('.title').textContent;
        this.artistElement.textContent = songElement.querySelector('.artist').textContent;
        this.timeElement.textContent = songElement.querySelector('.time').textContent;
        //const genders = [...songElement.querySelectorAll('.genders li')].map(li => li.textContent);
        //this.gendersElement.textContent = genders.join(', ');
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
        } else {
            this.playPauseButton.classList.remove('active');
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

        if (globalPlayer.currentSong === song && globalPlayer.isPlaying) {
            globalPlayer.pause(song);
        } else {
            globalPlayer.play(song, playlist);
        }
    });
}
