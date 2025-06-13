
<?php 
    use password\PasswordRecovery;
?>

<form id="reset-form">
  <input type="hidden" name="token" value="<?php echo $_GET['token'] ?? ''; ?>">
  <label>Nova senha:</label>
  <input type="password" name="new_password" required>
  <button type="submit">Atualizar senha</button>
</form>

<p id="feedback"></p>

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
  });
</script>
