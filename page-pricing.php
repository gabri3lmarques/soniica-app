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
        <h2>Pricing</h2>
        <div class="section">
            <h4>Free</h4>
            <h3>$ 0.00/month</h3>
            <ul>
                <li>🔔 Ads between songs</li>
                <li>⏳ Wait 24 hours to download new releases</li>
                <li>📥 1 download every 30 minutes</li>
                <li>📁 1 playlist only </li>
            </ul>
            <?php if(!$is_premium): ?>
                <a href="/get-premium" class="button go-premium">Go premium</a>
            <?php endif; ?>                        
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Premium</h4>
            <h3>$ 1.99/month</h3>
            <p>(One dollar and ninety-nine cents per month)</p>
            <ul>
                <li>🔕 Ad-free experience</li>
                <li>🚀 Instant access to the latest releases</li>
                <li>🎸 Unlimited downloads</li>
                <li>🎶 Unlimited playlists </li>
            </ul> 
            <?php if(!$is_premium): ?>
                <a href="/get-premium" class="button go-premium">Go premium</a>
            <?php endif; ?>            
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
