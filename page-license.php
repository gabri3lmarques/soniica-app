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
        <p style="margin-bottom:40px;">
            <a href="/" title="home">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p> 
        <h2>License</h2>
        <h3>📝Soniica License Agreement</h3>
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
        <div class="section">
            <h4>Usage Restrictions</h4>
            <p>To maintain a fair and safe experience for all users, you may not:</p>
            <ul>
                <li>❌ Reproduce, redistribute, or resell the Soniica platform (its code, interface, or services) without prior written permission.</li>
                <li>❌ Reverse engineer, decompile, or otherwise attempt to access protected components of the system.</li>
                <li>❌ Share your premium account with others. Each account is for individual use only.</li>                
            </ul>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Intellectual Property</h4>
            <p>All code, design, and functionality of the Soniica platform is the intellectual property of its creators. Unless otherwise stated, music tracks provided through the platform are licensed for royalty-free commercial use, but original copyrights remain with the respective artists or content owners.            </p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Third-Party Content</h4>
            <p>Some music or media may be provided by third parties. All content available on Soniica is curated to be safe for royalty-free commercial use. If any attribution or specific terms are required for a track, this will be clearly indicated.</p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Account Termination</h4>
            <p>We reserve the right to suspend or terminate your account if you violate the terms of this license or our Terms of Use. You may cancel your subscription at any time through your account settings.            </p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Changes to This License</h4>
            <p>We may update this license agreement from time to time. You will be notified of any significant changes via email or directly through the Soniica platform.            </p>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
