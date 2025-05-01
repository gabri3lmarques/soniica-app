<?php
    use user\Users;
    $is_premium = Users::check_user_premium_status();
    if(!is_user_logged_in()){
        ?>
            <div class="top-banner not-logged-in">
                <div class="top-banner-content">
                    <div>
                        <h2>Sign up for free</h2>
                        <p>Create playlists, discover amazing <span>royalty free</span> music, and download it <span>for free</span>.</p>
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
                        <h2>Sign up for free</h2>
                        <p>Create playlists, discover amazing music, and download your favorites.</p>
                        <p>Sign up with just an email — no sensitive data required. Fast and secure!</p>
                        <a class="cta" href="/sign-up">Sign up</a>
                    </div>
                </div>
            </div>
            <?php
        }
    }
?>