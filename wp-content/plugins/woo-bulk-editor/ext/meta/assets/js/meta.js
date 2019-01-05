jQuery(function ($) {
    $('.woobe_meta_delete').life('click', function () {
        $(this).parents('li').remove();
        return false;
    });

    //***

    $('#metaform').submit(function () {
        woobe_save_form(this, 'woobe_save_meta');
        return false;
    });

    //***

    //***
    //action for bulk meta_popup_editor
    $(document).on("woobe_act_meta_popup_editor_saved", {}, function (event, product_id, field_name, value) {

        if (product_id === 0) {
            //looks like we want to apply it for bulk editing
            $('#meta_popup_editor').hide();
            $("[name='woobe_bulk[" + field_name + "][value]']").val(value);
            $("[name='woobe_bulk[" + field_name + "][behavior]']").val('new');
        }

        return true;
    });

    //***

    $('#woobe_meta_add_new_btn').click(function () {
        var key = $('.woobe_meta_key_input').val();

        if (key.length > 0) {
            var html = $('#woobe_meta_li_tpl').html();
            html = html.replace(/__META_KEY__/gi, key);
            html = html.replace(/__TITLE__/gi, lang.meta.new_key);
            $('#woobe_meta_list').prepend(html);
            $('.woobe_meta_key_input').val('');
        } else {
            woobe_message(lang.meta.enter_key, 'error');
        }

        return false;
    });

    $('.woobe_meta_key_input').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#woobe_meta_add_new_btn').trigger('click');
        }
    });

    //***

    $('#woobe_meta_get_btn').click(function () {
        var id = parseInt($('.woobe_meta_keys_get_input').val(), 10);

        if (id > 0) {

            $('.woobe_meta_keys_get_input').val('');

            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_meta_get_keys',
                    product_id: id
                },
                success: function (keys) {
                    if (keys.length > 0) {
                        keys = JSON.parse(keys);
                        var html = $('#woobe_meta_li_tpl').html();
                        for (var i = 0; i < keys.length; i++) {
                            var li = html.replace(/__META_KEY__/gi, keys[i]);
                            li = li.replace(/__TITLE__/gi, keys[i]);
                            $('#woobe_meta_list').prepend(li);
                        }
                    } else {
                        woobe_message(lang.meta.no_keys_found, 'error');
                    }
                }
            });

        } else {
            woobe_message(lang.meta.enter_prod_id, 'error');
        }

        return false;
    });

    $('.woobe_meta_keys_get_input').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#woobe_meta_get_btn').trigger('click');
        }
    });

    //***

    $('.woobe_meta_view_selector').life('change', function () {
        var value = $(this).val();
        var type_selector = $(this).parents('li').find('.woobe_meta_type_selector');
        switch (value) {
            case 'popupeditor':
                $(type_selector).val('string');
                $(type_selector).parent().hide();
                break;

            case 'meta_popup_editor':
                $(type_selector).val('string');
                $(type_selector).parent().hide();
                break;

            case 'switcher':
                $(type_selector).val('number');
                $(type_selector).parent().hide();
                break;

            default:
                $(type_selector).parent().show();
                break;
        }

        return true;
    });

});

//*********************

function woobe_act_meta_popup_editor(_this) {
    woobe_popup_clicked = $(_this);
    var product_id = parseInt($(_this).data('product_id'), 10);
    var key = $(_this).data('key');
    $('#meta_popup_editor .woobe-modal-title').html($(_this).data('name') + ' [' + key + ']');

    //***
    //console.log($(_this).find('.meta_popup_btn_data').html());
    var meta = JSON.parse($(_this).find('.meta_popup_btn_data').html());


    if (Object.keys(meta).length > 0 && product_id > 0) {
        var html = '';

        try {
            $.each(meta, function (k, v) {
                var li_html = $('#meta_popup_editor_li').html();
                li_html = li_html.replace(/__KEY__/gi, k);

                if ($.isArray(v)) {
                    var ul = '<ul class="meta_popup_editor_child_ul">';

                    $.each(v, function (kk, vv) {
                        var li_html2 = $('#meta_popup_editor_li').html();
                        li_html2 = li_html2.replace(/__KEY__/gi, kk);
                        li_html2 = li_html2.replace(/__VALUE__/gi, vv);
                        li_html2 = li_html2.replace(/__CHILD_LIST__/gi, '');
                        li_html2 = li_html2.replace('keys[]', 'keys[' + k + '][]');
                        li_html2 = li_html2.replace('values[]', 'values[' + k + '][]');
                        ul += li_html2;
                    });

                    ul += '</ul>';

                    li_html = li_html.replace(/__CHILD_LIST__/gi, ul + '&nbsp;<a href="#" class="meta_popup_editor_add_sub_item" data-key="' + k + '">' + lang.append_sub_item + '</a><br />');
                    li_html = li_html.replace(/__VALUE__/gi, 'delete-this');
                } else if ($.isPlainObject(v)) {
                    var ul = '<ul class="meta_popup_editor_child_ul">';
                    $.each(v, function (kk, vv) {
                        var li_html_obj = $('#meta_popup_editor_li_object').html();
                        li_html_obj = li_html_obj.replace(/__KEY__/gi, kk);
                        li_html_obj = li_html_obj.replace(/__VALUE__/gi, vv);
                        li_html_obj = li_html_obj.replace('keys2[]', 'keys2[' + k + '][]');
                        li_html_obj = li_html_obj.replace('values2[]', 'values2[' + k + '][]');
                        ul += li_html_obj;
                    });
                    ul += '</ul>';

                    li_html = li_html.replace(/__CHILD_LIST__/gi, ul + '&nbsp;<a href="#" class="meta_popup_editor_add_sub_item2" data-key="' + k + '">' + lang.append_sub_item + '</a><br />');
                    li_html = li_html.replace(/__VALUE__/gi, 'delete-this');

                } else {
                    li_html = li_html.replace(/__VALUE__/gi, v);
                    li_html = li_html.replace(/__CHILD_LIST__/gi, '<ul class="meta_popup_editor_child_ul" data-key="' + k + '"></ul>&nbsp;<a href="#" class="meta_popup_editor_add_sub_item" data-key="' + k + '">' + lang.append_sub_item + '</a><br />');
                }

                html += li_html;
            });
        } catch (e) {
            //console.log(e);
        }

        $('#meta_popup_editor form').html('<ul class="woobe_fields_tmp">' + html + '</ul>');
        $('#meta_popup_editor form').find("input[value='delete-this']").remove();

        $('.meta_popup_editor_li_key2').parents('ul.woobe_fields_tmp').find('.meta_popup_editor_li_key:not(.meta_popup_editor_li_key2)').attr('readonly', true);


        $('#meta_popup_editor').show();
        __woobe_init_meta_popup_editor();

    } else {

        if (product_id > 0) {
            $('#meta_popup_editor form').html('<ul class="woobe_fields_tmp"></ul>');
        } else {
            //this we need do for another applications, for example bulk editor
            if ($('#meta_popup_editor form .woobe_fields_tmp').length == 0) {
                $('#meta_popup_editor form').html('<ul class="woobe_fields_tmp"></ul>');
            }
        }

        $('#meta_popup_editor').show();
        __woobe_init_meta_popup_editor();
    }

    //***


    $('.woobe-modal-save10').unbind('click');
    $('.woobe-modal-save10').click(function () {

        var product_id = woobe_popup_clicked.data('product_id');
        var key = woobe_popup_clicked.data('key');

        if (product_id > 0) {
            $('#meta_popup_editor').hide();
            woobe_message(lang.saving, 'warning');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_update_page_field',
                    product_id: product_id,
                    field: key,
                    value: $('#meta_popup_editor_form').serialize(),
                    is_serialized: 1
                },
                success: function (answer) {
                    $(_this).find('.meta_popup_btn_data').html(answer);
                    woobe_message(lang.saved, 'notice');
                    $(document).trigger('woobe_page_field_updated', [product_id, key, $('#meta_popup_editor_form').serialize()]);
                }
            });
        } else {
            //for buttons in any extensions
            $(document).trigger('woobe_act_meta_popup_editor_saved', [product_id, key, $('#meta_popup_editor_form').serialize()]);
        }

        return false;
    });

    $('.woobe-modal-close10').unbind('click');
    $('.woobe-modal-close10').click(function () {
        //$('#meta_popup_editor_editor form').html(''); - do not do this, as it make incompatibility with another extensions
        $('#meta_popup_editor').hide();
        return false;
    });

}

function __woobe_init_meta_popup_editor() {

    $("#meta_popup_editor form .woobe_fields_tmp, #meta_popup_editor form .meta_popup_editor_child_ul").sortable({
        update: function (event, ui) {
            //***
        },
        opacity: 0.8,
        cursor: "crosshair",
        handle: '.woobe_drag_and_drope',
        placeholder: 'woobe-options-highlight'
    });

    //***

    $('.meta_popup_editor_insert_new').unbind('click');
    $('.meta_popup_editor_insert_new').click(function () {
        var li_html = $('#meta_popup_editor_li').html();

        li_html = li_html.replace(/__KEY__/gi, '');
        li_html = li_html.replace(/__VALUE__/gi, '');
        li_html = li_html.replace(/__CHILD_LIST__/gi, '<ul class="meta_popup_editor_child_ul"></ul>&nbsp;<a href="#" class="meta_popup_editor_add_sub_item" data-key="">' + lang.append_sub_item + '</a><br />');

        if ($(this).data('place') == 'top') {
            $('#meta_popup_editor form .woobe_fields_tmp').prepend(li_html);
        } else {
            $('#meta_popup_editor form .woobe_fields_tmp').append(li_html);
        }
        __woobe_init_meta_popup_editor();

        return false;
    });

    //***

    $('.meta_popup_editor_insert_new_o').unbind('click');
    $('.meta_popup_editor_insert_new_o').click(function () {
        var li_html = $('#meta_popup_editor_li_o').html();
        var li_sub = $('#meta_popup_editor_li_object').html();

        li_html = li_html.replace(/__KEY__/gi, '');
        li_html = li_html.replace(/__VALUE__/gi, '');
        
        li_sub = li_sub.replace(/__KEY__/gi, '');
        li_sub = li_sub.replace(/__VALUE__/gi, '');
        
        li_html = li_html.replace(/__CHILD_LIST__/gi, '<ul class="meta_popup_editor_child_ul">' + li_sub + '</ul>&nbsp;<a href="#" class="meta_popup_editor_add_sub_item2" data-key="">' + lang.append_sub_item + '</a><br />');

        if ($(this).data('place') == 'top') {
            $('#meta_popup_editor form .woobe_fields_tmp').prepend(li_html);
        } else {
            $('#meta_popup_editor form .woobe_fields_tmp').append(li_html);
        }
        __woobe_init_meta_popup_editor();

        return false;
    });

    //***

    $('.meta_popup_editor_add_sub_item, .meta_popup_editor_add_sub_item2').unbind('click');
    $('.meta_popup_editor_add_sub_item, .meta_popup_editor_add_sub_item2').click(function () {

        if ($(this).hasClass('meta_popup_editor_add_sub_item')) {
            var li_html = $('#meta_popup_editor_li').html();
        } else {
            //meta_popup_editor_add_sub_item2
            var li_html = $('#meta_popup_editor_li_object').html();
        }

        //***

        li_html = li_html.replace(/__KEY__/gi, '');
        li_html = li_html.replace(/__VALUE__/gi, '');
        li_html = li_html.replace(/__CHILD_LIST__/gi, '');

        var key = $(this).data('key');
        if (key.length === 0) {
            key = $(this).prev('.meta_popup_editor_child_ul').data('key');
        }

        if (typeof key == 'undefined') {
            key = $(this).parent().find('.meta_popup_editor_li_key').eq(0).val();
        }

        li_html = li_html.replace('keys[]', 'keys[' + key + '][]');
        li_html = li_html.replace('values[]', 'values[' + key + '][]');

        li_html = li_html.replace('keys2[]', 'keys2[' + key + '][]');
        li_html = li_html.replace('values2[]', 'values2[' + key + '][]');

        //remove value textinput of the parent
        $(this).parent().find("[name='values[]']").remove();

        if ($(this).data('place') == 'top') {
            $(this).prev('.meta_popup_editor_child_ul').prepend(li_html);
        } else {
            $(this).prev('.meta_popup_editor_child_ul').append(li_html);
        }
        __woobe_init_meta_popup_editor();

        return false;
    });

    //***

    $('.meta_popup_editor_li_key').unbind('click');
    $('.meta_popup_editor_li_key').keyup(function () {

        $(this).parent().find('.meta_popup_editor_child_ul .meta_popup_editor_li_key').attr('name', 'keys[' + $(this).val() + '][]');
        $(this).parent().find('.meta_popup_editor_child_ul .meta_popup_editor_li_value').attr('name', 'values[' + $(this).val() + '][]');

        $(this).parent().find('.meta_popup_editor_child_ul .meta_popup_editor_li_key2').attr('name', 'keys2[' + $(this).val() + '][]');
        $(this).parent().find('.meta_popup_editor_child_ul .meta_popup_editor_li_value2').attr('name', 'values2[' + $(this).val() + '][]');


        return true;
    });

    //***

    $('.woobe_prod_delete').unbind('click');
    $('.woobe_prod_delete').click(function () {
        $(this).parent('li').remove();
        return false;
    });
}

