<?php
use password\PasswordRecovery;
get_header();
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="page-template not-found">
    <div class="page-template_left-side">
    </div>
    <div class="page-template_right-side">
        <div class="page-template_right-side_content">
            <h2>Recuperar senha</h2>
            <p>Digite o seu email no campo abaixo.</p>
            <p>Se houver uma conta relacionada ao seu email você recebra um link de redefinição de senha.</p>
            <form id="recover-form">
                <input type="email" name="email" required>
                <button type="submit">Recuperar senha</button>
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

  emailjs.init('user_IcEtHdn5JVnYwQuQCggEy'); // substitua pelo seu user_id

  document.getElementById('recover-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const email = this.email.value;

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
    
  });
</script>
<script>
    const modalCloseButton = document.querySelector('.playlist-modal-close');
    modalCloseButton.addEventListener('click', function() {
        document.querySelector('.playlist-modal-background').classList.remove('active');
    }); 
</script>
<?php get_footer(); ?>


