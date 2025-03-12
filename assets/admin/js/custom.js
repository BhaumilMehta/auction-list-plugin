jQuery(document).ready(function ($) {


    const dropdown = $('#auction_list_option');
    const idField = $('#auction_list_id_field');
    const urlField = $('#auction_list_url_field');

    dropdown.on('change', function () {
        if ($(this).val() === 'id') {
            idField.show();
            urlField.hide();
        } else if ($(this).val() === 'url') {
            idField.hide();
            urlField.hide();
        }
    });
    dropdown.trigger('change');
});
