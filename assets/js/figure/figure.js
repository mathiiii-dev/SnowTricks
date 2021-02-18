let deleteFigure = document.getElementById('delete-figure')
let hiddenModal = document.getElementById('hidden-modal');


deleteFigure.addEventListener('click', (e) => {
    document.getElementById('figure-name').innerHTML = deleteFigure.dataset.name;
    hiddenModal.classList.remove('hidden')
    document.getElementById('delete-action').setAttribute('href', 'delete-figure/' + deleteFigure.dataset.id)
})

hiddenModal.addEventListener('click', (e) => {
    hiddenModal.classList.add('hidden')
})