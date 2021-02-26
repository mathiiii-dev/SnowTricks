$(document).ready(function () {

    var $photosCollectionHolder = $('ul');
    $photosCollectionHolder.data('index', $photosCollectionHolder.find('input').length);
    $collectionHolder = $('ul');

    $collectionHolder.find('li').each(function () {
        addPhotoFormDeleteLink($(this));
    });

    $('body').on('click', '.add_item_link', function (e) {
        var $collectionHolderClass = $(e.currentTarget).data('collectionHolderClass');
        addFormToCollection($collectionHolderClass);
    })
});

function addFormToCollection($collectionHolderClass) {
    var $collectionHolder = $('.' + $collectionHolderClass);
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);
    $collectionHolder.data('index', index + 1);
    var $newFormLi = $('<li></li>').append(newForm);
    $collectionHolder.append($newFormLi)
    addPhotoFormDeleteLink($newFormLi);
}

function addPhotoFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button type="button" class="font-bold text-red-600 float-right"><i class="fa fa-trash"></i> Supprimer</button>');
    $tagFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}