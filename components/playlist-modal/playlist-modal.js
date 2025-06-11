document.querySelectorAll('.add-to-playlist-button').forEach(button => {
  button.addEventListener('click', event => {
    const songElement = event.currentTarget.closest('.song');
    const originalForm = songElement.querySelector('.playlist-form');

    const modal = document.querySelector('.playlist-modal');
    const modalBody = document.querySelector('.playlist-modal-body');
    const modalBackground = document.querySelector('.playlist-modal-background');
    const modalCloseButton = document.querySelector('.playlist-modal-close');

    if (originalForm && modalBody && modalBackground) {
      const clonedForm = originalForm.cloneNode(true); // clona o formulário inteiro

      modalBody.innerHTML = ''; // limpa conteúdo anterior
      modalBody.appendChild(clonedForm); // insere o clone
      modalBackground.classList.add('active');

      // Função de fechar modal
      const closeModal = () => {
        modalBackground.classList.remove('active');
        document.removeEventListener('click', closeOnOutsideClick);
        if (modalCloseButton) {
          modalCloseButton.removeEventListener('click', closeModal);
        }
      };

      // Fechar ao clicar fora do modal
      const closeOnOutsideClick = (e) => {
        if (!modal.contains(e.target)) {
          closeModal();
        }
      };

      // Espera para evitar conflito com o clique atual
      setTimeout(() => {
        document.addEventListener('click', closeOnOutsideClick);
      }, 10);

      // Fechar ao clicar no botão "X"
      if (modalCloseButton) {
        modalCloseButton.addEventListener('click', closeModal);
      }
    }
  });
});
