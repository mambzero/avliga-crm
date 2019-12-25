//JavaScript file

let collectionHolder = $('table#details');

$('.chosen-plugin').chosen().on('change', function (e, params) {
    let option = $(this).find('option[value="' + params.selected + '"]');
    if (!params.selected) {
        collectionHolder.find('tbody > tr').each(function () {
            $('#' + $(this).attr('id') + '_discount').val(null);
        });
    } else {
        collectionHolder.find('tbody > tr').each(function () {
            $('#' + $(this).attr('id') + '_discount').val(option.attr('data-discount'));
        });
    }
});

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover'
});