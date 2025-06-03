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
        <h2>About Us</h2>
        <div class="section">
            <h4>&#127925; Soniica is unlike anything you’ve seen before</h4> 
            <p>This isn’t just another music platform. <span>It’s Soniica</span>.</p>
            <p>A place where <spn>every track is crafted</spn> not just <span>for your ears</span> but <span>for your story</span>.</p>
            <p>Soniica is built different.</p>
            <p>Whether you're creating the <span>next big thing</span> or just <span>living your moment</span>, we’ve got the <span>perfect soundtrack</span> for it—custom-made using cutting-edge <span>AI technology</span>.</p>
            <p>From background scores for your projects to <span>mood-perfect songs for your life</span>, Soniica delivers music that feels like it was <span>made just for you</span>.</p>
            <p>Welcome to the future of sound.</p>
            <p>Welcome to Soniica.                               </p>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
