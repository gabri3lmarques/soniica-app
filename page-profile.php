<?php
use svg\SvgIcons;
get_header();
require_once get_template_directory() . '/components/search/Search.php';
$user_id = get_current_user_id();
$profile_url = get_user_meta($user_id, 'profile_picture_url', true);
?>

<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="main-content profile">
    <!-- a coluna da esquerda -->
    <div class="sidebar hide-1200">
        <?php include 'components/accordion/accordion.php'; ?>
    </div>
    <!-- o corpo do site -->
    <div class="main profile">
        <h2>Account</h2>
        <?php 
            $search = new SearchComponent();
            echo $search->render();
        ?>
        <div class="profile-img">
            <?php
                if($profile_url) {
                    ?>
                    <img class="profile-image" src="<?php echo esc_url($profile_url); ?>">
                    <?php
                } else {
                    SvgIcons::render('user');
                }
            ?>
        </div>
        <form id="profile-picture-form" enctype="multipart/form-data">
            <div class="file-picker-div">
                <input class="file-picker" id="profile_picture" type="file" name="profile_picture" accept="image/*" />
            </div>
            <button type="submit">Enviar imagem</button>
        </form>

        <div id="upload-status"></div>

        <button id="remove-profile-picture">Remover imagem</button>
        <!-- /playlist -->
    </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
