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
                         <h2>Congratulations!🦸‍♂️ You just unlocked your Premium superpowers!</h2>
                         <p>No more limits. No more ads. Just pure sound freedom.</p>
                         <p>
                             🎶 Unlimited playlists <br>
                            🚀 Early access to new releases <br>
                            📥 Unlimited downloads <br>
                            🔕 100% ad-free experience
                         </p>
                        <p>Thanks for supporting Soniica — now go and flex those powers. The stage is yours. 🔊✨   </p>                             
                     <?php
                 } else {
                     ?>
                         <h2>Oops! Something went wrong.</h2>
                         <p>It seems like you are not a Premium member yet.</p>
                         <a href="get-premium" class="button go-premium">Go premium</a>
                     <?php
                 }                 
             ?>
         </div>
     </div>
 </div>
 <?php get_footer(); ?>