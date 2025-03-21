<?php

class CardComponent {
    public static function render(string $image_url, string $title, string $text, string $button_text, string $button_link): string {
        return '
        <div class="card">
            <div class="card-image">
                <img src="' . htmlspecialchars($image_url, ENT_QUOTES, 'UTF-8') . '" 
                     alt="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">
            </div>
            <div class="card-content">
                <h3 class="card-title">' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h3>
                <p class="card-text">' . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . '</p>
                <a href="' . htmlspecialchars($button_link, ENT_QUOTES, 'UTF-8') . '" class="card-button">
                    ' . htmlspecialchars($button_text, ENT_QUOTES, 'UTF-8') . '
                </a>
            </div>
        </div>';
    }
}

/*

exemplo de uso

echo CardComponent::render(
    'https://via.placeholder.com/300', 
    'Título do Card', 
    'Este é um exemplo de texto do card.', 
    'Saiba Mais', 
    'https://example.com'
);

*/

?>