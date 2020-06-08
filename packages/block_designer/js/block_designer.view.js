$(document).ready(function () {
    $.validate({
        modules: 'file',
        form: '.block-designer-container form',
        borderColorOnError: '#a94442',
        onSuccess: function () {
            var return_ = false;
            $.ajax({
                url: CCM_DISPATCHER_FILENAME + '/dashboard/blocks/block_designer/handle_check/' + $('input[name="block_handle"]').val(),
                type: 'post',
                async: false,
                data: {},
                dataType: 'json',
                success: function (data) {
                    if (data.error) {
                        $('.block-designer-container > .alert').remove();
                        $('.block-designer-container form').before('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">×</button>' + data.error + '</div>');
                        $('html, body').animate({
                            scrollTop: $('.block-designer-container > .alert').offset().top - $('#ccm-toolbar').height() - 10
                        }, 1000);
                        return false;
                    }
                    else {
                        return_ = true;
                    }
                }
            });
            return return_;
        }
    });

    $('#horizontalTab').responsiveTabs({
        animation: 'slide',
        collapsible: 'accordion',
        rotate: false,
        startCollapsed: false,
        setHash: false,
        activate: function () {
            $(this).removeClass('hidden');
        }
    });

    $(".block-designer-container .content-fields").sortable({
        placeholder: 'content-field content-field-placeholder',
        handle: '.handle',
        start: function (e, ui) {
            ui.placeholder.height(ui.item.height());
        }
    });

    var max_field_type_length = 0;
    $.each($('.block-designer-container .field-types li'), function () {
        var width = $(this).width();
        if (width > max_field_type_length) {
            max_field_type_length = width;
        }
    });
    $('.block-designer-container .field-types li').css({minWidth: max_field_type_length + 'px'}).find('a, .fa-info[title]').tooltip({placement: 'top'});

    $('select[name="block_install"]').trigger('change');
    $('.block-designer-container').blockDesigner().fillFields('#json_fields', '#contentField');
});

$(document).on('click', '.field-types a', function (e) {
    e.preventDefault();
    $('.block-designer-container').blockDesigner().addField(this, '#contentField');
});

$(document).on('change', 'select[name="block_install"]', function (e) {
    var block_type_group = $('select[name="block_type_set"]').parents('.form-group');
    if ($('select[name="block_install"]').val() != '1') {
        $(block_type_group).slideUp('medium');
    }
    else {
        $(block_type_group).slideDown('medium');
    }
});

$(document).on('click', '.delete_folder', function (e) {
    e.preventDefault();
    var me = this;
    $.ajax({
        url: $(me).attr('href'),
        type: 'post',
        dataType: 'json',
        success: function (data) {
            if (data.success) {
                var alert = $(me).parents('.alert');
                $(me).parent().remove();
                if ($(alert).find('> div').length <= 0) {
                    $(alert).remove();
                }
            }
        }
    });
});

$(document).on('click', '.content-fields .content-field .delete', function (e) {
    e.preventDefault();
    $('.block-designer-container').blockDesigner().deleteField(this);
});

$(document).on('click', '.nav-tabs a', function (e) {
    e.preventDefault();
    $(this).tab('show');
});