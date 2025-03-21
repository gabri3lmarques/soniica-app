<?php get_header(); ?>

<?php use user\Users; ?>

<?php require_once get_template_directory() . '/components/card/CardComponent.php';?>

<div class="flex-container">
    <div class="top-bar">
        <?php include 'components/top-menu/top-menu.php'; ?>
    </div>
    <div class="main-content">
        <div class="sidebar hide-1200">
            <?php include 'components/accordion/accordion.php'; ?>
        </div>
        <div class="main">
            <?php
                if(!is_user_logged_in()){
                    ?>
                        <div class="flex" style="justify-content: space-between; gap:20px">
                            <?php 

                                $image_url = get_template_directory_uri() . '/assets/img/cards/1.jpg';
                                
                                echo CardComponent::render(
                                    $image_url, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Sign up', 
                                    '/sign-up'
                                );
                            ?>  
                        </div>
                    <?php
                } else {
                    $is_premium = Users::check_user_premium_status();
                    if(!$is_premium){
                        ?>
                        <div class="flex" style="justify-content: space-between; gap:20px">
                            <?php 

                                $image_url = get_template_directory_uri() . '/assets/img/cards/1.jpg';
                                
                                echo CardComponent::render(
                                    $image_url, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Go premium', 
                                    '/sign-up'
                                );
                    
                                $image_url2 = get_template_directory_uri() . '/assets/img/cards/2.jpg';

                                echo CardComponent::render(
                                    $image_url2, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Go premium', 
                                    '/sign-up'
                                );
                  
                                $image_url3 = get_template_directory_uri() . '/assets/img/cards/3.jpg';

                                echo CardComponent::render(
                                    $image_url3, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Go premium', 
                                    '/sign-up'
                                );

                                $image_url4 = get_template_directory_uri() . '/assets/img/cards/4.jpg';

                                echo CardComponent::render(
                                    $image_url4, 
                                    'Título do Card', 
                                    'Este é um exemplo de texto do card.', 
                                    'Go premium', 
                                    '/sign-up'
                                );
                            ?>  
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
    <div class="bottom-bar"></div>
</div>

<?php get_footer(); ?>