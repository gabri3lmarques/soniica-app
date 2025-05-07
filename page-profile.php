<?php

if(!is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}

use svg\SvgIcons;
use user\Users;
require_once get_template_directory() . '/components/search/Search.php';
$user_id = get_current_user_id();
$profile_url = get_user_meta($user_id, 'profile_picture_url', true);
get_header();
// Verifica se o usuário está logado
$is_premium = Users::check_user_premium_status();
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
        <?php 
            $search = new SearchComponent();
            echo $search->render();
        ?>
        <h2>Account</h2>
        <div class="section">
            <h3>Profile Image</h3>
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
                <button class="send-image" type="submit">Send image</button>
            </form>
            <button id="remove-profile-picture">Delete image</button>
            <div id="upload-status"></div>
        </div>
        <!-- section -->
         <div class="section">
            <h3>User Status</h3>

                <?php
                    if($is_premium){
                        ?>
                        <div class="user-status premium">Premium plan</div>
                        <p>You already have our best plan.</p> 
                        <p>Do you need some custom solution for your business? Say hello and tell us what you need in <a href="#">hello@soniica.com</a></p>
                        <?php
                    } else {
                        ?>
                        <div class="user-status free">Free plan</div>
                        <p>Want to unlock all the power that only premium give to you? <a href="/get-premium">Go premium</a> </p>
                        <?php
                    }
                ?>
      
    </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
