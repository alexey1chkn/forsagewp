//flags
var woobe_history_reverted = false;
var woobe_history_reverting_going = false;//to block products tab
var woobe_history_data_is_changed = true;//for updating history list by ajax when its tab clicked

jQuery(function ($) {
    //redraw products if bulk revert done and clicked on tab Products
    //https://learn.jquery.com/events/introduction-to-custom-events/
    $(document).on("do_tabs-products", {
        //foo: "bar"
    }, function () {
        if (woobe_history_reverting_going) {
            alert(lang.history.wait_until_finish);
            return false;
        } else {
            if (woobe_history_reverted) {
                //console.log( event.data.foo );
                woobe_history_reverted = false;
                data_table.draw('page');
            }
        }
        
        __trigger_resize();
        return true;
    });

    //***

    $(document).on("woobe_page_field_updated", {}, function (event, product_id, field_key) {
        woobe_history_data_is_changed = true;
        return true;
    });

    $(document).on("woobe_bulk_completed", {}, function (event) {
        woobe_history_data_is_changed = true;
        return true;
    });

    //***
    //for history updating if data changed
    $(document).on("do_tabs-history", {}, function () {
        if (woobe_history_data_is_changed && !woobe_history_reverting_going) {
            woobe_history_update_list();
        }
        return true;
    });

    //***

    $('#woobe_history_show_types').change(function () {
        switch (parseInt($(this).val(), 10)) {
            case 1:
                $('#woobe_history_list li.solo_li').show();
                $('#woobe_history_list li.bulk_li').hide();
                break;
            case 2:
                $('#woobe_history_list li.solo_li').hide();
                $('#woobe_history_list li.bulk_li').show();
                break;
            default:
                //0
                $('#woobe_history_list li').show();
                break;
        }

        return true;
    });

});

function woobe_history_update_list() {
    $('#woobe_history_list_container').html('<h5>' + lang.loading + '</h5>');
    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_get_history_list'
        },
        success: function (content) {
            $('#woobe_history_list_container').html(content);
        },
        error: function () {
            alert(lang.error);
        }
    });

    //***
    //should be here!!
    woobe_history_data_is_changed = false;
}

function woobe_history_revert_solo(id, product_id) {
    if (confirm(lang.sure)) {

        woobe_disable_bind_editing();

        //***

        woobe_message(lang.history.reverting, 'warning', 999999);
        $('.woobe_history_btn').hide();
        woobe_history_is_going();
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_history_revert_product',
                id: id
            },
            success: function () {
                woobe_message(lang.history.reverted, 'notice');

                if ($('#product_row_' + product_id).length > 0) {
                    woobe_redraw_table_row($('#product_row_' + product_id));
                }

                //woobe_history_reverted = true;
                $('#woobe_history_' + id).remove();
                $('.woobe_history_btn').show();
                woobe_history_is_going(true);
            },
            error: function () {
                alert(lang.error);
                woobe_history_is_going(true);
            }
        });
    }
}

function woobe_history_revert_bulk(bulk_key, bulk_id) {
    if (confirm(lang.sure)) {

        if (woobe_bind_editing) {
            $("[data-numcheck='woobe_bind_editing']").trigger('click');
            woobe_bind_editing = 0;
        }

        //***

        woobe_message(lang.history.reverting, 'warning', 999999);
        woobe_history_reverting_going = true;
        $('.woobe_history_btn').hide();
        woobe_set_progress('woobe_bulk_progress_' + bulk_id, 0);
        woobe_history_is_going();

        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_history_get_bulk_count',
                bulk_key: bulk_key
            },
            success: function (total_count) {
                woobe_history_revert_bulk_portion(bulk_id, bulk_key, total_count, 0);
            },
            error: function () {
                alert(lang.error);
                woobe_history_reverting_going = false;
                woobe_history_is_going(true);
            }
        });
    }
}

function woobe_history_revert_bulk_portion(bulk_id, bulk_key, total_count, removed) {
    var step = 10;

    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_history_revert_bulk_portion',
            bulk_key: bulk_key,
            limit: step,
            removed_count: removed,
            total_count: total_count
        },
        success: function () {

            woobe_set_progress('woobe_bulk_progress_' + bulk_id, (removed + step) * 100 / total_count);

            if ((total_count - (removed + step)) <= 0) {
                woobe_message(lang.history.reverted, 'notice');
                woobe_history_reverted = true;
                woobe_history_reverting_going = false;
                $('#woobe_history_' + bulk_key).remove();
                $('.woobe_history_btn').show();
                woobe_history_is_going(true);
            } else {
                woobe_history_revert_bulk_portion(bulk_id, bulk_key, total_count, removed + step);
            }

        },
        error: function () {
            woobe_history_is_going(true);
            woobe_history_reverting_going = false;
            alert(lang.error);
        }
    });
}

function woobe_history_clear() {

    if (confirm(lang.sure)) {
        woobe_message(lang.history.clearing, 'warning', 999999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_history_clear'
            },
            success: function () {
                woobe_message(lang.history.cleared, 'notice');
                $('#woobe_history_list_container').html('<h5>' + lang.history.cleared + '</h5>');
            },
            error: function () {
                alert(lang.error);
            }
        });
    }

}

function woobe_history_delete_solo(id) {
    if (confirm(lang.sure)) {
        woobe_message(lang.deleting, 'warning', 999999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_history_delete_solo',
                id: id
            },
            success: function () {
                woobe_message(lang.deleted, 'notice');
                $('#woobe_history_' + id).remove();
            },
            error: function () {
                alert(lang.error);
            }
        });
    }
}

function woobe_history_delete_bulk(bulk_key) {
    if (confirm(lang.sure)) {
        woobe_message(lang.deleting, 'warning', 999999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_history_delete_bulk',
                bulk_key: bulk_key
            },
            success: function () {
                woobe_message(lang.deleted, 'notice');
                $('#woobe_history_' + bulk_key).remove();
            },
            error: function () {
                alert(lang.error);
            }
        });
    }
}


function woobe_history_is_going(clear = false) {
    if (clear) {
        $('#woobe_history_is_going').remove();
    } else {
        $('#wp-admin-bar-root-default').append("<li id='woobe_history_is_going'>" + lang.history.history_is_going + "</li>");
}

}

