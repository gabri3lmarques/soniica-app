<?php
use svg\SvgIcons;
use user\Users;
require_once get_template_directory() . '/components/search/Search.php';
get_header();
// Verifica se o usuário está logado
$is_premium = Users::check_user_premium_status();
?>
<div class="top-bar">
    <?php include 'components/top-menu/top-menu.php'; ?>
</div>
<div class="main-content page-template">
    <!-- a coluna da esquerda -->
    <div class="sidebar hide-1200">
        <?php include 'components/accordion/accordion.php'; ?>
    </div>
    <!-- o corpo do site -->
    <div class="main template">
        <?php 
            $search = new SearchComponent();
            echo $search->render();
        ?>
        <!-- License -->
        <h2>Contact US</h2>
        <div class="section">
            <h4>📬 Got a question, idea, or just wanna say hi?</h4>
            <p>
                We’d love to hear from you. Whether you’re working on something big or just vibing with the music, drop us a line—we’re here for it.
                📬 <span>hello@soniica.com</span> 
            </p>                    
        </div>
        <!-- /section -->
         <div class="section">
            <h4>💬 Need a hand? We’ve got you! </h4>
            <p>If you’re having any trouble or just need some help, our team is ready to assist.</p>
            <p>📧 Reach out at <span>help@soniica.com</span> — we’ll get back to you as soon as possible!</p>
         </div>
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
