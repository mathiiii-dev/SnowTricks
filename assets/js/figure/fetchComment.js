let counter = 0;
const btn = document.getElementById('voir-plus');
let messages = [];

function fetchLastComment(counter = 0, origin) {

    let url = urlFetchLastComment;

    if (origin === 1) {
        url = url.replace("offset", counter.toString());
    } else {
        url = url.replace("offset", '0');
    }

    fetch(url, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(resp => resp.json())
    .then(data => {
        let html = '';
        let countMessage = data[0].messagesCount;
        data.forEach(message => messages.push(message));
        console.log(messages)
        messages.forEach(
            message =>
                html += '<div class="bg-white rounded p-2 m-2 shadow-xl">' +
                    '<img src="'+ assetUrl.replace('userPicture', message.profilePicture) + '" alt="profilePicture" class="inline object-cover w-12 h-12 rounded-full float-left mr-2">' +
                    '<p class="text-left font-light pr-6 text-gray-700">' + message.user + '</p>' +
                    '<p class="text-center px-6 my-2">' + message.message + '</p>' +
                    '<p class="text-right font-light text-xs">'+ message.createdAt +'</p>' +
                    '<p class="text-right font-light text-sm text-red-600"><a href="'+reportUrl.replace('idDiscussion', message.message_id)+'">Signaler</a></p></div>'
        );

        if (countMessage <= 5) {
            btn.classList.add('hidden');
        }

        if (countMessage <= counter + 5) {
            btn.classList.add('hidden');
        }

        document.querySelector('#messages-boxes').innerHTML = html;
    })
    .catch(function (error) {
        console.log(error);
    });
}

btn.addEventListener('click', () => {
    counter = counter + 5;
    fetchLastComment(counter, 1);
    fetchLastSentComment();
})

window.addEventListener("load", function (event) {
    event.preventDefault();
    fetchLastComment(counter, 0);
});

const form = document.getElementById('send-message');
form.addEventListener("click",
    function (event) {
        event.preventDefault()
        let data = document.getElementById("discussion_message").value;
        fetch(urlSendComment, {
            method: "POST",
            body: data,
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(resp => resp.json())
        .then(() => {
            fetchLastSentComment();
            document.getElementById("discussion_message").value = "";
        })
        .catch(error => {
            console.log(error);
        })
    }
)

function fetchLastSentComment() {
    console.log(urlFetchLastSentComment);
    fetch(urlFetchLastSentComment, {
        method: "GET",
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then(resp => resp.json())
    .then((data) => {
        let html = '';
        html = '<div class="bg-white rounded p-2 m-2 shadow-xl">' +
            '<img src="'+ assetUrl.replace('userPicture', data.profilePicture) +'" alt="profilePicture" class="inline object-cover w-12 h-12 rounded-full float-left mr-2">' +
            '<p class="text-left font-light pr-6 text-gray-700">' + data.user + '</p>' +
            '<p class="text-center px-6 my-2" id="message">' + data.message + '</p>' +
            '<p class="text-right font-light text-xs">'+ data.createdAt +'</p>' +
            '<p class="text-right font-light text-sm text-red-600"><a href="'+reportUrl.replace('idDiscussion', data.message_id)+'">Signaler</a></p></div>'
        document.querySelector('#messages-boxes').insertAdjacentHTML("afterbegin",html);
    })
    .catch(error => {
        console.log(error);
    })
}
