<?php
 // Página de sucesso (premium-success.php)
 use Stripe\Stripe;
 Stripe::setApiKey(STRIPE_SECRET_KEY);
 if (isset($_GET['session_id'])) {
     $session_id = sanitize_text_field($_GET['session_id']);
     // Use o session_id para obter os detalhes da sessão de checkout da Stripe
     $session = \Stripe\Checkout\Session::retrieve($session_id);
     // Verifique se o pagamento foi bem-sucedido
     if ($session->payment_status === 'paid') {
         $user_id = get_current_user_id();
         // Atualize o meta do usuário para 'premium'
         update_user_meta($user_id, 'is_premium', true);  // Atualiza o status de premium para true
     } 
 }
 use user\Users;
 get_header();
 ?>
 <div class="top-bar">
     <?php include 'components/top-menu/top-menu.php'; ?>
 </div>
 <div class="page-template premium-success">
     <div class="page-template_left-side"></div>
     <div class="page-template_right-side">
        <div class="page-template_right-side_content">
             <?php
                 $is_premium = Users::check_user_premium_status();
                 if($is_premium){
                     ?>
                        <p style="margin-bottom:40px;" class="back-to-previous-page">
                            <a href="/login">
                                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
                            </a>
                        </p> 
                         <h2>Parabéns!🦸‍♂️ Você liberou os super poderes premium!</h2>
                         <p>Sem limites. Sem propagandas. Só liberdade musical.</p>
                         <p>
                             🎶 Playlists ilimitadas <br>
                            🚀 Acesso instantâneo a novas músicas <br>
                            📥 Downloads ilimitados <br>
                            🔕 100% livre de propaganda
                         </p>
                        <p>Obrigado  por apoiar o Soniica! Agora é sua hora de brilhar 🔊✨   </p>                             
                     <?php
                 } else {
                     ?>
                        <p style="margin-bottom:40px;">
                            <a href="/login">
                                <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 17" width="20" height="17"><style>.a{fill:#fff}</style><path class="a" d="m8.6 0q-0.1 0-0.2 0 0 0.1-0.1 0.1c-0.1 0-0.3 0.3-4.1 4-3.2 3-4 3.9-4.1 4 0 0 0 0.1-0.1 0.1 0 0.1 0 0.2 0 0.3q0 0.1 0 0.2c0 0 0.1 0.1 0.1 0.2 0 0.1 0.2 0.3 4 4 2.4 2.3 4.1 3.9 4.1 3.9q0.1 0.1 0.1 0.1 0.1 0 0.2 0.1 0.1 0 0.2 0 0.1 0 0.2 0 0.1-0.1 0.1-0.1 0.1 0 0.2-0.1c0 0 0.1 0 0.1-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1-0.1-0.1-0.3-0.3-3.3-3.3-1.8-1.7-3.2-3.1-3.2-3.1 0-0.1 3.6-0.1 8.1-0.1h8.1q0.3 0 0.3-0.1c0.1 0 0.2 0 0.2-0.1 0.1 0 0.1-0.1 0.2-0.2 0 0 0-0.1 0-0.1 0.1-0.1 0.1-0.1 0.1-0.2 0-0.1 0-0.1 0-0.2 0 0 0-0.1-0.1-0.2 0 0 0-0.1-0.1-0.2 0 0-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.1-0.2-0.2 0 0-0.1 0-0.2 0-0.1 0-0.3 0-8.2 0-4.5 0-8.1 0-8.1-0.1 0 0 1.4-1.4 3.2-3.1 3-3 3.2-3.2 3.3-3.3 0 0 0-0.1 0-0.1q0-0.1 0.1-0.3-0.1-0.1-0.1-0.2c0 0 0-0.1 0-0.1q-0.1-0.1-0.2-0.2c-0.1-0.1-0.1-0.2-0.2-0.2-0.1 0-0.2-0.1-0.2-0.1q-0.1 0-0.2 0 0 0-0.1 0z"/></svg>            
                            </a>
                        </p> 
                         <h2>Oops! Algo deu errado.</h2>
                         <p>Parece que você ainda não é um usuário Premium.</p>
                         <a href="get-premium" class="button go-premium">Virar Premium</a>
                     <?php
                 }                 
             ?>
         </div>
     </div>
 </div>
 <?php get_footer(); ?>