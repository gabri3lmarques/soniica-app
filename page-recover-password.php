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
            <p></p>
            <form id="recover-form">
            <label>Email:</label>
            <input type="email" name="email" required>
            <button type="submit">Recuperar senha</button>
            </form>
            <p id="feedback"></p>
        </div>
    </div>
</div>
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
</script>
<script type="module">

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

    document.getElementById('feedback').textContent = data.message;
  });
</script>
<?php get_footer(); ?>


