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
    <div class="main">
        <?php 
            $search = new SearchComponent();
            echo $search->render();
        ?>
        <!-- License -->
        <h2>License</h2>
        <h3>Soniica License Agreement</h3>
        <div class="section">
            <h4>What is Soniica?</h4>
            <p>Soniica is a web-based music platform designed to offer a modern, personalized experience for listening to music, organizing playlists, and downloading tracks.</p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>User Rights</h4>
            <p> As a Soniica user, you are granted the following rights:</p>
            <ul>
                <li>✅ Use the Soniica platform for both personal and commercial purposes.</li>
                <li>✅ Create and manage playlists, stream music, and explore available features based on your plan (free or premium).</li>
                <li>✅ Download and use the music made available on the platform in your commercial projects (such as videos, events, podcasts, games, etc.), under a royalty-free license — meaning you are not required to pay additional fees or royalties for usage.</li>
            </ul>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
