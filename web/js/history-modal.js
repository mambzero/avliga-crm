//JavaScript File

let modal = $('#historyModal');
let quick_view = $('a[data-target="#historyModal"]');

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
});