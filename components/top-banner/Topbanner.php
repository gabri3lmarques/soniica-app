<?php
    use user\Users;
    $is_premium = Users::check_user_premium_status();
    if(!is_user_logged_in()){
        ?>
            <div class="top-banner not-logged-in">
                <div class="top-banner-content">
                    <div>
                        <h2>Infinitas possibilidades com Inteligência Artificial</h2>
                        <p><span>Soundibly</span> tem a trilha sonora perfeita para o seu <span>projeto</span>, seu <span>negócio</span> — ou para a <span>sua vida</span>.</p>
                        <p><a href="/sign-up"><span>Cadastre-se</span></a> com apenas um e-mail. <span>Sem cartão</span> de crédito. Rápido, seguro e grátis.</p>
                        <a class="cta" href="/sign-up">Cadastrar grátis</a>
                    </div>
                </div>
            </div>
        <?php
    } else {
        // se o usuário estiver logado e não for premium

        if(!$is_premium){
            ?>
            <div class="top-banner not-premium">
            <div class="top-banner-content">
                    <div>
                        <h2>Unlock the full power of Soniica for only <span>$1.99</span> per month!</h2>
                        <p>Ad-free experience, instant access to the latest releases, unlimited downloads and unlimited playlists.</p>
                        <a class="cta" href="/get-premium">Upgrade now</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>