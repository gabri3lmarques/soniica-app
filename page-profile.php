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
        <!-- /section -->
        <div class="section">
            <h3>Help</h3>
            <p>Do you need help? Just call us on <a href="#">help@soniica.com</a></p>
        </div>
        <!-- /section -->
        <div class="section">
            <h3>Cancel Account</h3>
            <?php if($is_premium): ?>
                <p>Before deleting your account is very important to cancel your payment subscription so we can stop charge you.</p>
                <p><a href="/cancel-premium">Click here</a> to go to cancel premium page.</p>
            <?php endif; ?>
            <p class="delete-account-warning">By clicking the button below, <span>you will permanently delete your account</span>.</p>
            <button id="delete-account-btn">☠️ Delete my account!</button>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<script>
document.getElementById('delete-account-btn')?.addEventListener('click', function() {
    if (confirm("Are you sure you want to delete your account? This action is irreversible.")) {
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                action: 'delete_user_account',
                _ajax_nonce: '<?php echo wp_create_nonce('delete_user_account_nonce'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Account successfully deleted!');
                window.location.href = '<?php echo home_url(); ?>';
            } else {
                alert('Error deleting account.: ' + data.data);
            }
        });
    }
});
</script>
<?php get_footer(); ?>
