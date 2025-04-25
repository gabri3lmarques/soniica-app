<?php
    use user\Users;
    // se o usuário não estiver logado
    if(!is_user_logged_in()){
        ?>
            <p>Não está logado.</p>
        <?php
    } else {
        // se o usuário estiver logado e não for premium
        $is_premium = Users::check_user_premium_status();
        if(!$is_premium){
            ?>
                <p>Não é premium.</p>
            <?php
        }
    }
?>