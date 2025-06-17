<?php
use password\PasswordRecovery;
get_header();
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="page-template recover-password">
    <div class="page-template_left-side">
    </div>
    <div class="page-template_right-side">
        <div class="page-template_right-side_content">
            <h2>Recuperar senha</h2>
            <p>Digite o seu email no campo abaixo.</p>
            <p>Se houver uma conta associada a ele, você receberá um link de redefinição de senha.</p>
            <form id="recover-form">
                <input type="text" placeholder="Digite seu email" name="email">
                <button type="submit" id="send-email-btn">Recuperar senha</button>
            </form>
            <p id="feedback"></p>
        </div>
    </div>
</div>
<div class="playlist-modal-background">
    <div class="playlist-modal">
        <div class="playlist-modal-close">
            <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" width="20" height="20">
                <style>.a{fill:#fff}</style>
                <path fill-rule="evenodd" class="a" d="m0.4 0.4c0.6-0.5 1.5-0.5 2.1 0l7.5 7.6 7.5-7.6c0.6-0.5 1.5-0.5 2.1 0 0.5 0.6 0.5 1.5 0 2.1l-7.6 7.5 7.6 7.5c0.5 0.6 0.5 1.5 0 2.1-0.6 0.5-1.5 0.5-2.1 0l-7.5-7.6-7.5 7.6c-0.6 0.5-1.5 0.5-2.1 0-0.5-0.6-0.5-1.5 0-2.1l7.6-7.5-7.6-7.5c-0.5-0.6-0.5-1.5 0-2.1z"></path>
            </svg>                  
        </div>
        <div class="playlist-modal-head">
            <h3>Selecione uma playlist</h3>
        </div>
        <div class="playlist-modal-body"></div>
    </div>
</div>
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<script>
    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }


  function criarBotaoComTimeout(botaoId, timeoutMs = 60000) {
  const botao = document.getElementById(botaoId);
  const STORAGE_KEY = 'botaoDesabilitadoAte_' + botaoId;
  let intervalo = null;

  function atualizarTexto(segundos) {
    botao.textContent = `Aguarde ${segundos}s...`;
  }

  function liberarBotao() {
    botao.disabled = false;
    botao.textContent = 'Recuperar senha';
    localStorage.removeItem(STORAGE_KEY);
    if (intervalo) clearInterval(intervalo);
  }

  function iniciarTimeout() {
    botao.disabled = true;
    const habilitarEm = Date.now() + timeoutMs;
    localStorage.setItem(STORAGE_KEY, habilitarEm);
    atualizarTexto(timeoutMs / 1000);

    intervalo = setInterval(() => {
      const restante = habilitarEm - Date.now();
      if (restante > 0) {
        atualizarTexto(Math.ceil(restante / 1000));
      } else {
        liberarBotao();
      }
    }, 1000);
  }

  function atualizarEstado() {
    const ateQuando = localStorage.getItem(STORAGE_KEY);
    const agora = Date.now();

    if (ateQuando && agora < parseInt(ateQuando)) {
      botao.disabled = true;
      atualizarTexto(Math.ceil((ateQuando - agora) / 1000));
      intervalo = setInterval(() => {
        const restante = parseInt(ateQuando) - Date.now();
        if (restante > 0) {
          atualizarTexto(Math.ceil(restante / 1000));
        } else {
          liberarBotao();
        }
      }, 1000);
    } else {
      liberarBotao();
    }
  }

  // Sempre atualizar o estado ao criar
  atualizarEstado();

  // Retorna a função que você deve chamar ao clicar no botão
  return {
    iniciarTimeout
  };
}

const controleBotao = criarBotaoComTimeout('send-email-btn', 60000);

</script>
<script>

  emailjs.init('user_IcEtHdn5JVnYwQuQCggEy'); // substitua pelo seu user_id

  document.getElementById('recover-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email = this.email.value;

    if(email === '') {
      //document.getElementById('feedback').textContent = 'Por favor, insira um email válido.';
      document.querySelector('.playlist-modal-body').textContent = 'Por favor, insira um email válido.';
      document.querySelector('.playlist-modal-background').classList.toggle('active');
      return;

    }

    if (!validarEmail(email)) {
        document.querySelector('.playlist-modal-body').textContent = 'O endereço de email não é válido.';
        document.querySelector('.playlist-modal-background').classList.toggle('active');
        return
    } 

    const res = await fetch('/wp-json/soniica/v1/recover', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email })
    });

    const data = await res.json();

    if (data.reset_link) {
      await emailjs.send('service_li1v16e', 'template_4qx675l', {
        to_email: email,
        email: email,
        reset_link: data.reset_link
      });
    }

    //document.getElementById('feedback').textContent = data.message;
    document.querySelector('.playlist-modal-body').textContent = data.message;
    document.querySelector('.playlist-modal-background').classList.toggle('active');
    controleBotao.iniciarTimeout();
  });
</script>
<script>
    const modalCloseButton = document.querySelector('.playlist-modal-close');
        modalCloseButton.addEventListener('click', function() {
        document.querySelector('.playlist-modal-background').classList.remove('active');
    }); 
</script>
<?php get_footer(); ?>


