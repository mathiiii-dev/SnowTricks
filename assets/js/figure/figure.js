let deleteFigure = document.getElementById('delete-figure');
let hiddenModal = document.getElementById('hidden-modal');

deleteFigure.addEventListener('click', (e) => {
    document.getElementById('figure-name').innerHTML = deleteFigure.dataset.name;
    hiddenModal.classList.remove('hidden');
    document.getElementById('delete-action').setAttribute('href', deleteFigure.dataset.url);
})

hiddenModal.addEventListener('click', (e) => {
    hiddenModal.classList.add('hidden');
})

let showMedia = document.getElementById('show-media');
let media = document.querySelectorAll('*[id^=media]')
let removeMedia = document.getElementById('remove-media')

showMedia.addEventListener('click', (e) =>{
    removeMedia.classList.remove('hidden')
    showMedia.classList.add('hidden')
    media.forEach((item) => {
        item.classList.remove('hidden')
    })
})

removeMedia.addEventListener('click', (e) =>{
    removeMedia.classList.add('hidden')
    showMedia.classList.remove('hidden')
    media.forEach((item) => {
        item.classList.add('hidden')
    })
})



