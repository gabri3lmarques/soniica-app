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
        </div>
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
