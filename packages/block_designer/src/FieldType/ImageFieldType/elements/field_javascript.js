$(document).on('change', '.content-field[data-type="image"] input.make_thumbnail', function () {
    var content_field = $(this).parents('.content-field');
    if ($(this).is(':checked')) {
        $(content_field).find('.thumbnail-values').slideDown('medium');
        $(content_field).find('.width, .height').attr('data-validation-optional', 'false').attr('data-validation', 'number');
    }
    else {
        $(content_field).find('.thumbnail-values').slideUp('medium');
        $(content_field).find('.width, .height').attr('data-validation-optional', 'true').attr('data-validation', '');
    }
});

$(document).on('change', '.content-field[data-type="image"] select.make_link', function () {
    var content_field = $(this).parents('.content-field');
    switch($(this).val()){
        case '1':
        case '2':
            $(content_field).find('.link-values').slideDown('medium');
            break;
        default:
            $(content_field).find('.link-values').slideUp('medium');
            break;
    }
});

$(document).ready(function () {
    $('.content-field[data-type="image"]').find('input.make_thumbnail:checked').trigger('change');
    $('.content-field[data-type="image"]').find('select.make_link').trigger('change');
});