
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
            <h2>Redefinir senha</h2>
            <form id="reset-form">
              <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">
              <label>Nova senha:</label>
              <input style="margin-top:16px" type="password" name="new_password" required>
              <button type="submit">Atualizar senha</button>
            </form>

            <p id="feedback"></p>
            <a class="go-to-login" href="/login">Ir para login</a>
        </div>
    </div>
</div>
<script>
  document.getElementById('reset-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const token = this.token.value;
    const new_password = this.new_password.value;

    const res = await fetch('/wp-json/soniica/v1/reset', {
      method: 'POST',
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify({ token, new_password })
    });

    const data = await res.json();
    document.getElementById('feedback').textContent = data.message;
    document.getElementById('reset-form').style.display = "none";
    document.querySelector(".go-to-login").style.display = "flex";
  });
</script>
<?php get_footer(); ?>



