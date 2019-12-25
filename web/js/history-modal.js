//JavaScript File

let modal = $('#historyModal');
let quick_view = $('a[data-target="#historyModal"]');
let alert = $('<div class="alert alert-warning alert-dismissible fade show mb-0" role="alert"></div>');

quick_view.on('click', function(e) {

    let target = $(e.target);
    let target_id = target.data('target-id');
    let target_type = target.data('type');
    let title = target_type + ' id: ' + target_id;

    switch (target_type.toLowerCase()) {
        case 'order':
        case 'report':
            modal.find('.modal-dialog').addClass('modal-lg');
    }

    modal.find('.modal-title').text(title);

    modal.modal('show');

    $.ajax('/history/' + target_type.toLowerCase() + '/' + target_id,
        {
        success: function (data) {
            let row = $('<tr></tr>');
            $.each(data.items[0], function( key ) {
                row.append('<th>' + key + '</th>');
            });
            modal.find('.modal-thead').html(row);

            $.each(data.items, function( index, product ) {
                let row = $('<tr></tr>');
                $.each(product, function( key ) {
                    row.append('<td>' + product[key] + '</td>');
                });
                modal.find('.modal-tbody').append(row);
            });

            if (data.items.length > 1) {
                let row = $('<tr></tr>');
                row.append('<th colspan="'+ (Object.keys(data.items[0]).length - 1) +'" class="text-right">&nbsp;</th>');
                row.append('<td>' + data.total + '</td>');
                modal.find('.modal-tfoot').html(row);
            }

            modal.find('.modal-table').fadeIn();
        },
        statusCode: {
            404: function(data) {
                data = JSON.parse(data.responseText);
                alert.html('<strong>Error: 404</strong> ' + data.error);
                modal.find('.modal-body').append(alert);
            },
            500: function() {
                alert.html('<strong>Error: 500</strong> Internal Server Error');
                modal.find('.modal-body').append(alert);
            }
        }
    });

    e.preventDefault();
});

modal.on('hidden.bs.modal', function () {
    modal.find('.modal-title').html(null);
    modal.find('.modal-tbody').html(null);
    modal.find('.modal-tfoot').html(null);
    modal.find('.modal-table').hide();
    if (modal.find('.modal-dialog').hasClass('modal-lg')) {
        modal.find('.modal-dialog').removeClass('modal-lg');
    }
    if (modal.find('.alert').length) {
        modal.find('.alert').remove();
    }
});