<?php
    use user\Users;
    $is_premium = Users::check_user_premium_status();
    if(!is_user_logged_in()){
        ?>
            <div class="top-banner not-logged-in">
                <div class="top-banner-content">
                    <div>
                        <h2>Infinitas possibilidades com Soniica</h2>
                        <p><span>Soniica</span> tem a trilha sonora perfeita para o seu <span>projeto</span>, seu <span>negócio</span> — ou para a <span>sua vida</span>.</p>
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
                        <h2>Todo o poder de Soniica por apenas <span>R$9.99</span> por mês</h2>
                        <p>Uma experiência <span>sem anúncios</span>, acesso instantâneo aos <span>lançamentos</span> mais recentes, <span>downloads ilimitados</span> e <span>playlists ilimitadas</span>.</p>
                        <a class="cta" href="/get-premium">Seja premium</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>