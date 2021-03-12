document.addEventListener('DOMContentLoaded', function () {
    const messageEle = document.getElementById('discussion_message');
    const counterEle = document.getElementById('counter');

    messageEle.addEventListener('input', function (e) {
        const target = e.target;
        const maxLength = target.getAttribute('maxlength');
        const currentLength = target.value.length;

        counterEle.innerHTML = `${currentLength}/${maxLength}`;
    });
});