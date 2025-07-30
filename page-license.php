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
        <h2>Licença</h2>
        <h3>📝 Acordo de Licença do Soundibly</h3>
        <div class="section">
            <h4>O que é o Soundibly</h4>
            <p>O Soundibly é uma plataforma de música online criada para oferecer uma experiência moderna e personalizada para ouvir músicas, organizar playlists e baixar faixas.</p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Direitos do Usuário</h4>
            <p>Como usuário do Soundibly, você tem os seguintes direitos:</p>
            <ul>
                <li>✅ Usar a plataforma Soundibly tanto para fins pessoais quanto comerciais.</li>
                <li>✅ Criar e gerenciar playlists, ouvir músicas e explorar os recursos disponíveis conforme seu plano (gratuito ou premium).</li>
                <li>✅ Baixar e usar as músicas disponibilizadas na plataforma em seus projetos comerciais (como vídeos, eventos, podcasts, jogos, etc.), sob uma licença royalty-free — ou seja, você não precisa pagar taxas ou royalties adicionais pelo uso.</li>
            </ul>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Restrições de Uso</h4>
            <p>Para garantir uma experiência justa e segura para todos, você não pode:</p>
            <ul>
                <li>❌ Reproduzir, redistribuir ou revender a plataforma Soniica (seu código, interface ou serviços) sem autorização prévia por escrito.</li>
                <li>❌ Realizar engenharia reversa, descompilar ou tentar acessar componentes protegidos do sistema.</li>
                <li>❌ Compartilhar sua conta premium com outras pessoas. Cada conta é de uso individual.</li>                
            </ul>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Propriedade Intelectual</h4>
            <p>Todo o código, design e funcionalidades da plataforma Soniica são propriedade intelectual de seus criadores. Salvo indicação contrária, as faixas de música disponibilizadas pela plataforma são licenciadas para uso comercial sem royalties, mas os direitos autorais originais permanecem com os respectivos artistas ou detentores do conteúdo.</p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Conteúdo de Terceiros</h4>
            <p>Algumas músicas ou mídias podem ser fornecidas por terceiros. Todo o conteúdo disponível no Soniica é cuidadosamente selecionado para ser seguro para uso comercial sem royalties. Caso alguma faixa exija atribuição ou termos específicos, isso será claramente indicado.</p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Encerramento da Conta</h4>
            <p>Reservamo-nos o direito de suspender ou encerrar sua conta caso você viole os termos desta licença ou de nossos Termos de Uso. Você pode cancelar sua assinatura a qualquer momento pelas configurações da sua conta. </p>
        </div>
        <!-- /section -->
        <div class="section">
            <h4>Alterações nesta Licença</h4>
            <p>Podemos atualizar este acordo de licença periodicamente. Você será notificado sobre quaisquer alterações significativas por e-mail ou diretamente pela plataforma Soniica.</p>
        </div>
        <!-- /section -->
     </div>
    <!-- o corpo do site -->
</div>
<!-- /main content -->
<?php get_footer(); ?>
