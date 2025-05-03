<form id="profile-picture-form" enctype="multipart/form-data">
  <input type="file" name="profile_picture" accept="image/*" />
  <button type="submit">Enviar imagem</button>
</form>

<div id="upload-status"></div>

<button id="remove-profile-picture">Remover imagem</button>


<?php
get_header();

$user_id = get_current_user_id();
$profile_url = get_user_meta($user_id, 'profile_picture_url', true);
if ($profile_url):
?>
    <img src="<?php echo esc_url($profile_url); ?>" alt="Imagem de perfil" style="width:100px;height:100px;border-radius:50%;">
<?php endif; ?>
<?php get_footer(); ?>
