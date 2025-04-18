let fm_close = document.querySelector('.fm-close');
let modal = document.querySelector('.flash-message-overlay');

if(fm_close ){
    fm_close.addEventListener('click', function() {
        modal.style.display = 'none';
    });
}
