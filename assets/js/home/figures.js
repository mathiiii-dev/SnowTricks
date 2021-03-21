let deleteFigure = document.querySelectorAll('*[id^=delete-figure]')
let hiddenModal = document.getElementById('hidden-modal');
let close = document.querySelectorAll('*[id^=close-modal]')

deleteFigure.forEach((item) => {
    item.addEventListener('click', (e) => {
        document.getElementById('figure-name').innerHTML = item.dataset.name;
        hiddenModal.classList.remove('hidden')
        document.getElementById('delete-action').setAttribute('href', 'figure/delete/' + item.dataset.id)
    })
})

close.forEach((item) => {
    item.addEventListener('click', (e) => {
        hiddenModal.classList.add('hidden')
    })
})