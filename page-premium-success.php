<?php
use user\Users;
get_header();
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="page-template premium-success">
    <div class="page-template_left-side"></div>
    <div class="page-template_right-side">
       <div class="page-template_right-side_content">
            <?php
                $is_premium = Users::check_user_premium_status();
                if($is_premium){
                    ?>
                        <h2>Congratulations! You are now a Premium member!</h2>                
                    <?php
                } else {
                    ?>
                        <h2>Oops! Something went wrong.</h2>
                        <p>It seems like you are not a Premium member yet. Please try again.</p>
                    <?php
                }                 
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>