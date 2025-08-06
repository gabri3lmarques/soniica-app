class Player {
    constructor() {
        this.audio = new Audio();
        this.currentSong = null;
        this.currentPlaylist = null;
        this.isPlaying = false;
        this.isLooping = false;
        this.isRandom = false;

        // Aguardar DOM estar pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.initialize());
        } else {
            this.initialize();
        }
    }

    initialize() {
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

        this.setupEventListeners();
        this.setupAudioEventListeners();
        
        // Delay na inicialização da primeira música
        setTimeout(() => this.initializeFirstSong(), 100);
    }

    setupEventListeners() {
        // Event listeners (somente se os elementos existem)
        if (this.playPauseButton)
            this.playPauseButton.addEventListener('click', () => this.togglePlayPause());

        if (this.nextButton)
            this.nextButton.addEventListener('click', () => this.next());

        if (this.previousButton)
            this.previousButton.addEventListener('click', () => this.previous());

        if (this.loopButton)
            this.loopButton.addEventListener('click', () => this.toggleLoop());

        if (this.randomButton)
            this.randomButton.addEventListener('click', () => this.toggleRandom());

        if (this.volumeSlider) {
            this.volumeSlider.addEventListener('input', (e) => this.adjustVolume(e));
            this.audio.volume = this.volumeSlider.value;
        }

        if (this.progressBarContainer) {
            this.progressBarContainer.addEventListener('click', (e) => this.seek(e));
            this.progressBarContainer.addEventListener('mousedown', (e) => this.startDrag(e));
        }
    }

    setupAudioEventListeners() {
        this.audio.addEventListener('timeupdate', () => this.updateProgressBar());
        this.audio.addEventListener('ended', () => this.handleSongEnd());
        
        // Adicionar tratamento de erros
        this.audio.addEventListener('error', (e) => {
            console.error('Audio error:', e);
            console.error('Audio error code:', this.audio.error?.code);
            console.error('Audio error message:', this.audio.error?.message);
            console.error('Audio src:', this.audio.src);
        });

        this.audio.addEventListener('loadstart', () => {
            console.log('Loading started for:', this.audio.src);
        });

        this.audio.addEventListener('canplaythrough', () => {
            console.log('Can play through:', this.audio.src);
        });
    }

    isPlayerDisabled() {
        return document.querySelector('#player-main')?.classList.contains('disabled');
    }

    adjustVolume(event) {
        const newVolume = event.target.value;
        const rightVolume = newVolume * 100;
        const leftVolume = 100 - rightVolume;
        this.audio.volume = newVolume;
        this.volumeSlider.style.background = `linear-gradient(to right, #e95265, #207dff ${rightVolume}%, #272727 ${leftVolume}%)`;
    }

    // Método melhorado com mais validações
    async initializeFirstSong() {
        try {
            const firstPlaylist = document.querySelector('.playlist');
            if (!firstPlaylist) {
                console.warn('No playlist found');
                return;
            }
            
            const firstSong = firstPlaylist.querySelector('.song');
            if (!firstSong) {
                console.warn('No songs found in playlist');
                return;
            }

            this.currentPlaylist = firstPlaylist;
            this.currentSong = firstSong;
            
            await this.loadAudioSrc(firstSong);
            this.updatePlayerInfo(firstSong);
        } catch (error) {
            console.error('Error initializing first song:', error);
        }
    }

    // Método separado para carregar áudio com melhor tratamento de erro
    async loadAudioSrc(songElement) {
        const encodedUrl = songElement?.dataset?.src;
        
        if (!encodedUrl) {
            throw new Error('No encoded URL found in song element');
        }

        try {
            const decodedUrl = atob(encodedUrl);
            
            // Validações mais rigorosas
            if (!decodedUrl || typeof decodedUrl !== 'string') {
                throw new Error('Invalid decoded URL');
            }

            // Verificar protocolo
            if (!decodedUrl.startsWith('http://') && !decodedUrl.startsWith('https://')) {
                throw new Error(`Invalid protocol in URL: ${decodedUrl}`);
            }

            // Se estamos em HTTPS, forçar URLs para HTTPS também
            let finalUrl = decodedUrl;
            if (window.location.protocol === 'https:' && decodedUrl.startsWith('http://')) {
                finalUrl = decodedUrl.replace('http://', 'https://');
                console.warn('Converting HTTP URL to HTTPS for security:', finalUrl);
            }

            console.log('Loading audio from:', finalUrl);
            
            // Teste de conectividade antes de definir como src
            await this.testAudioUrl(finalUrl);
            
            this.audio.src = finalUrl;
            
        } catch (e) {
            console.error('Error decoding/loading URL:', e);
            console.error('Original encoded URL:', encodedUrl);
            throw e;
        }
    }

    // Método para testar se a URL é válida antes de usar
    async testAudioUrl(url) {
        return new Promise((resolve, reject) => {
            const testAudio = new Audio();
            
            const timeout = setTimeout(() => {
                testAudio.src = '';
                reject(new Error('Audio URL test timeout'));
            }, 5000);

            testAudio.addEventListener('canplay', () => {
                clearTimeout(timeout);
                testAudio.src = '';
                resolve();
            });

            testAudio.addEventListener('error', (e) => {
                clearTimeout(timeout);
                testAudio.src = '';
                reject(new Error(`Audio URL test failed: ${e.message}`));
            });

            testAudio.src = url;
        });
    }

    toggleRandom() {
        if(this.isPlayerDisabled()) return;
        
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

    async play(songElement, playlistElement) {
        if(this.isPlayerDisabled()) return;
        
        try {
            // Pausar música atual se houver
            if (this.currentSong && this.currentSong !== songElement) {
                this.pause(this.currentSong);
            }

            await this.loadAudioSrc(songElement);
            
            await this.audio.play();
            
            this.isPlaying = true;
            this.currentSong = songElement;
            this.currentPlaylist = playlistElement;
            
            this.updatePlayerInfo(songElement);
            this.syncButtons(songElement);

        } catch (e) {
            console.error('Error playing song:', e);
            // Tentar fallback ou mostrar mensagem de erro para o usuário
            this.handlePlayError(e);
        }
    }

    handlePlayError(error) {
        console.error('Play error details:', error);
        // Aqui você pode implementar uma UI de feedback para o usuário
        // Por exemplo, mostrar uma mensagem ou tentar uma URL alternativa
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

    async togglePlayPause() {
        if(this.isPlayerDisabled()) return;
        
        try {
            if (this.isPlaying) {
                this.pause(this.currentSong);
            } else {
                await this.audio.play();
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
        } catch (error) {
            console.error('Error in togglePlayPause:', error);
            this.handlePlayError(error);
        }
    }

    toggleLoop() {
        if(this.isPlayerDisabled()) return;
        
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
        if(this.isPlayerDisabled()) return;
        
        const nextSong = this.getNextSong();
        if (nextSong) {
            this.play(nextSong, this.currentPlaylist);
        }
    }

    previous() {
        if(this.isPlayerDisabled()) return;
        
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
        // Remover active de todos os botões
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

// Inicialização melhorada
function initializePlayer() {
    if (document.querySelector('#player-main')) {
        window.globalPlayer = new Player();

        // Configurar eventos para as músicas
        document.addEventListener('click', (event) => {
            const playButton = event.target.closest('.play-button');
            if (!playButton) return;

            const song = playButton.closest('.song');
            const playlist = playButton.closest('.playlist');
            
            if (!song || !playlist) return;

            if (window.globalPlayer.currentSong === song && window.globalPlayer.isPlaying) {
                window.globalPlayer.pause(song);
            } else {
                window.globalPlayer.play(song, playlist);
            }
        });
    }
}

// Garantir que o DOM esteja pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePlayer);
} else {
    initializePlayer();
}