jQuery(document).ready(function ($) {
    // Se o elemento característico não estiver presente, não executa o restante do script.
    if (!$('.upload-category-image').length) {
        return;
    }

    let mediaUploader;

    $(document).on('click', '.upload-category-image', function (e) {
        e.preventDefault();

        const button = $(this);
        const preview = button.siblings('#category-image-preview').find('img');
        const input = button.siblings('#category-image');
        const removeButton = button.siblings('.remove-category-image');

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media({
            title: 'Escolha uma imagem',
            button: {
                text: 'Usar esta imagem'
            },
            multiple: false
        });

        mediaUploader.on('select', function () {
            const attachment = mediaUploader.state().get('selection').first().toJSON();
            input.val(attachment.id);
            preview.attr('src', attachment.url).show();
            removeButton.show();
        });

        mediaUploader.open();
    });

    $(document).on('click', '.remove-category-image', function (e) {
        e.preventDefault();
        const button = $(this);
        const preview = button.siblings('#category-image-preview').find('img');
        const input = button.siblings('#category-image');

        input.val('');
        preview.attr('src', '').hide();
        button.hide();
    });
});
