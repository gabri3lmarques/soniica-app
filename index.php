<?php get_header(); ?>


<div class="flex-container">
    <div class="top-bar">
        <?php include 'components/top-menu/top-menu.php'; ?>
    </div>
    <div class="main-content">
        <div class="sidebar hide-1200">
            <?php include 'components/accordion/accordion.php'; ?>
        </div>
        <div class="main">
            <?php include 'components/top-banner/top-banner.php'; ?>
        </div>
    </div>
    <div class="bottom-bar"></div>
</div>

<?php get_footer(); ?>