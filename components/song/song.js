function handleDownload(event) {
    fetch(`${wpApiSettings.root}soniica/v1/register-download`, {
        method: 'POST',
        headers: {
            'X-WP-Nonce': wpApiSettings.nonce,
            'Content-Type': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            event.preventDefault();
            alert(data.message);
            return false;
        } else {
            // Atualiza o estado de todos os botões de download para usuários não premium
            if (!data.is_premium) {
                document.querySelectorAll('.download-link').forEach(downloadLink => {
                    downloadLink.classList.add('download-blocked');
                    downloadLink.setAttribute('title', 'Aguarde 24 horas para fazer outro download.');
                    downloadLink.textContent = 'Download indisponível';
                    downloadLink.removeAttribute('href');
                    downloadLink.removeAttribute('download');
                    downloadLink.style.cursor = 'not-allowed';
                });
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        event.preventDefault();
        return false;
    });

    return true;
}

const playerDownloadButtons = document.querySelectorAll('.download-link');

function isBase64(str) {
    try {
        return btoa(atob(str)) === str;
    } catch (e) {
        return false;
    }
}

const PlayerManager = (function () {
    let instance = null;

    return {
        getInstance: function () {
            if (!instance) {
                instance = new Player();
            }
            return instance;
        }
    };
})();

function stopGlobalPlayer() {
    if (window.globalPlayer && window.globalPlayer.currentSong) {
        window.globalPlayer.pause(window.globalPlayer.currentSong);
    }
}



playerDownloadButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
        event.preventDefault();

        let encodedDownloadLink = button.getAttribute("href");
        let decodedLink = '';

        // Verifica se a string é Base64 válida antes de decodificar
        if (isBase64(encodedDownloadLink)) {
            // se for base64, decodifica
            decodedLink = atob(encodedDownloadLink);
        } else {
            // se não for, usa o link normal
            decodedLink = encodedDownloadLink;
 
            window.location.href = decodedLink;
            return;
        }

        if (!button.classList.contains('download-blocked')) {
            window.open(decodedLink, '_blank');
        } else {
            console.log('deu ruim');
        }
    });
});

window.addEventListener('beforeunload', function(event) {
    stopGlobalPlayer();
});
