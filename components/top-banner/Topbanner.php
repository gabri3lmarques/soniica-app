<?php
    use user\Users;
    $is_premium = Users::check_user_premium_status();
    if(!is_user_logged_in()){
        ?>
            <div class="top-banner not-logged-in">
                <div class="top-banner-content">
                    <div>
                        <h2>Music for every moment</h2>
                        <p>The <span>perfect soundtrack</span> for your <span>project</span> or your <span>life</span> is right here.</p>
                        <p>Sign up with just an email — no sensitive data required. Fast and secure!</p>
                        <a class="cta" href="/sign-up">Sign up</a>
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