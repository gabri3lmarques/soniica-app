class AccordionMenu {
    constructor() {
        this.accordionItems = document.querySelectorAll('.accordion-item');
        this.init();
    }

    init() {
        this.accordionItems.forEach(item => {
            const header = item.querySelector('.accordion-header');
            
            header.addEventListener('click', () => {
                const currentItem = item;
                const isActive = currentItem.classList.contains('active');
                
                // Fecha todos os itens
                this.accordionItems.forEach(item => {
                    item.classList.remove('active');
                    const content = item.querySelector('.accordion-content');
                    content.style.maxHeight = null;
                });
                
                // Abre o item atual se não estava ativo
                if (!isActive) {
                    currentItem.classList.add('active');
                    const content = currentItem.querySelector('.accordion-content');
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        });
    }
}

// Inicializa o accordion quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('.accordion-item')) {
        new AccordionMenu();
    }
});