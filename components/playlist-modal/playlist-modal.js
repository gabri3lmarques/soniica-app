document.querySelectorAll('.add-to-playlist-button').forEach(button => {
  button.addEventListener('click', event => {
    const songElement = event.currentTarget.closest('.song');
    const originalForm = songElement.querySelector('.playlist-form');

    const modal = document.querySelector('.playlist-modal');
    const modalBody = document.querySelector('.playlist-modal-body');
    const modalBackground = document.querySelector('.playlist-modal-background');

    if (originalForm && modalBody && modalBackground) {
      const clonedForm = originalForm.cloneNode(true); // clona o formulário inteiro

      modalBody.innerHTML = ''; // limpa conteúdo anterior
      modalBody.appendChild(clonedForm); // insere o clone
      modalBackground.classList.add('active');

      // Fechar ao clicar fora do modal
      const closeOnOutsideClick = (e) => {
        if (!modal.contains(e.target)) {
          modalBackground.classList.remove('active');
          document.removeEventListener('click', closeOnOutsideClick);
        }
      };

      // Espera o clique atual terminar para evitar conflito
      setTimeout(() => {
        document.addEventListener('click', closeOnOutsideClick);
      }, 10);
    }
  });
});
