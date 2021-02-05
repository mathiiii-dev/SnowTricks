let deleteFigure = document.querySelectorAll('*[id^=delete-figure]')
let close = document.querySelectorAll('*[id^=close-modal]')
var buttonsCount = deleteFigure.length;

for (var i = 0; i < buttonsCount; i += 1) {
    deleteFigure[i].onclick = function(e) {
        openModal(this)
    };
}

function openModal(i) {
    let modal = document.querySelectorAll('*[id^=hidden-modal]')

    let index = 0, length = modal.length;
    for ( ; index < length; index++) {
        if(modal[index].dataset.id === i.dataset.id) {
            modal[index].classList.remove('hidden');
        }
    }
}

function closeModal() {
    let modal = document.querySelectorAll('*[id^="hidden-modal"]')

    let index = 0, length = modal.length;
    for (; index < length; index++) {
        modal[index].classList.add('hidden');
    }
}

close.forEach(function (i) {
    i.addEventListener('click', closeModal)
})