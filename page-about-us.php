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
        <p style="margin-bottom:40px;" class="back-to-previous-page">
            <a href="/" title="home">
                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
            </a>
        </p> 
        <h2>Sobre</h2>
        <div class="section">
            <h4>&#127925; Soniica é diferente de tudo que você já viu e ouviu</h4> 
            <p>O Soniica não é só mais uma plataforma de música. É diferente . É um mix de tecnologia de ponta com o feeling musical que a sua história merece.</p>
            <p>Um lugar onde cada faixa é criada não só para os ouvidos, mas também para o coração.</p>
            <p>O Soniica foi criado de um jeito único. É a mistura da genialidade da Inteligência Artificial com a sensibilidade humana — uma fusão perfeita entre dois mundos.</p>
            <p>Não importa se vocẽ está em busca do próximo Oscar, ou quer apenas curtir o momento. A sua trilha sonora perfeita está aqui. </p>
            <p>Com artistas e trilhas sonoras originais, o Soniica reúne tudo o que você precisa em um só lugar.</p>
            <p>Quer aquele som viral pra bombar no TikTok? Que tal uma pegada sci-fi? Precisa embalar um evento profissional — ou o churrasco da galera? Talvez você só queira curtir um som enquanto pega o busão de volta pra casa.</p>
            <p>E o melhor: novos artistas e músicas são adicionados todos os dias.</p>
            <p>Bem-vindo a evolução do audio visual.</p>
            <p>Bem-vindo ao Soniica.</p>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
