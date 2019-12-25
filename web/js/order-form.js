// Javascipt  File

let collectionHolder;

// setup an "add a detail" link
let detailButton = $('#add_product');

// Get the ul that holds the collection of tags
collectionHolder = $('table#details');

// count the current form inputs we have (e.g. 2), use that as the new
// index when inserting a new item (e.g. 2)
collectionHolder.data('index', parseInt(collectionHolder.attr('data-index')) + 1);

detailButton.on('click', function (e) {
    // add a new tag form (see next code block)
    addDetailRow(collectionHolder);
    e.preventDefault();
});

collectionHolder.on('click', '.remove-detail', function (e) {
    let row = $(this).parents('tr');
    if (collectionHolder.find('tbody > tr').length !== 1) {
        row.remove();
    }
    e.preventDefault();
});

function addDetailRow(collectionHolder) {
    // Get the data-prototype explained earlier
    let prototype = collectionHolder.data('prototype');

    // get the new index
    let index = collectionHolder.data('index');

    let newRow = $('<tr id="order_details_' + index + '"></tr>').append(prototype.replace(/__name__/g, index));

    collectionHolder.prepend(newRow);

    $('#order_details_' + index + '_product').chosen().on('change',function (e,params) {

        let client_id = $('#order_client').val();
        let client_discount = $("#order_client > option[value='" + client_id + "']").attr('data-discount');
        let price = $(this).find('option[value="'+params.selected+'"]').attr('data-price');

        if (!client_id.length) {
            $(this).val(null).trigger('chosen:updated');
            alert('Select client first!');
            return;
        }

        let price_input = $('#order_details_' + index + '_price');
        let discount_input = $('#order_details_' + index + '_discount');

        price_input.val(price);
        discount_input.val(client_discount);

        if (!price) {
            discount_input.val(null);
        }

    });

    // increase the index with one for the next item
    collectionHolder.data('index', index + 1);

}

$('.chosen-plugin').on('chosen:ready', function(event, params) {
    let chosen_id = event.target.id;
    let $chosen = $('#'+chosen_id);
    if ($chosen.hasClass('border-danger')) {
        $('#'+chosen_id+'_chosen').find('.chosen-single').addClass('border-danger');
    }
});

$('.chosen-plugin').chosen().on('change',function (e,params) {
    let client_id = $('#order_client').val();
    let option = $(this).find('option[value="'+params.selected+'"]');
    if ('order_client' === e.target.id) {
        if (!params.selected) {
            collectionHolder.find('tbody').html(null);
            addDetailRow(collectionHolder);
        } else {
            collectionHolder.find('tbody > tr').each(function(){
                if ($('#' + $(this).attr('id') + '_product').val().length) {
                    $('#' + $(this).attr('id') + '_discount').val(option.attr('data-discount'));
                }
            });
        }
    } else {
        if (!client_id.length) {
            $(this).val(null).trigger('chosen:updated');
            alert('Select client first!');
            return;
        }
        let row_id = $(this).parents('tr').attr('id');
        let price_input = $('#'+row_id+'_price');
        let discount_input = $('#'+row_id+'_discount');
        price_input.val(option.attr('data-price'));
        discount_input.val($('#order_client option[value="' + client_id + '"]').attr('data-discount'));
        if (!option.attr('data-price')) {
            discount_input.val(null);
        }
    }
});

$('[data-toggle="tooltip"]').tooltip({
    trigger: 'hover'
});