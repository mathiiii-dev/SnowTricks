let btnMore = document.getElementById('show-more');
let btnLess = document.getElementById('show-less');

function showMore() {
    let el = document.querySelectorAll('*[id^="hidden-cards"]');
    document.getElementById('show-more').classList.add('hidden');
    document.getElementById('show-less').classList.remove('hidden');
    let index = 0, length = el.length;
    for ( ; index < length; index++) {
        el[index].classList.remove('hidden');
    }

function showLess() {
    let el = document.querySelectorAll('*[id^="hidden-cards"]');
    document.getElementById('show-more').classList.remove('hidden');
    document.getElementById('show-less').classList.add('hidden');
    let index = 0, length = el.length;
    for ( ; index < length; index++) {
        el[index].classList.add('hidden');
    }
}

btnMore.addEventListener("click", showMore);
btnLess.addEventListener("click", showLess);

