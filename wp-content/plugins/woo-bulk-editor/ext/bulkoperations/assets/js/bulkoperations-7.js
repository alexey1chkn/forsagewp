jQuery(function ($) {

    $('#bulkoperations_att_visibility_add').click(function () {
        var html = $('#bulkoperations_visibility_att_tpl').html();
        html = html.replace(/__ID1__/gi, (woobe_get_random_string()).toLowerCase());
        html = html.replace(/__ID2__/gi, (woobe_get_random_string()).toLowerCase());
        $('#bulkoperations_att_visibility').append('<li>' + html + '</li>');
        $('.bulkoperations_apply_7_btn').show();
        return false;
    });

    //***

    $('.bulkoperations_att_visibility_del').life('click', function () {
        $(this).parents('li').remove();
        if ($('#bulkoperations_att_visibility li').size() === 0) {
            $('.bulkoperations_apply_7_btn').hide();
        }
        return false;
    });

});

//**************************************************************

function bulkoperations_apply_7() {
    if (confirm(lang.sure)) {

        woobe_bulkoperations_is_going();
        $('.bulkoperations_apply_7_btn').hide();
        $('.woobe_bulkoperations_terminate_btn').show();
        woobe_set_progress('woobe_bulkoperations_progress_7', 0);

        //***
        //assembling data before sending to the server
        var vis_data = [];
        $('#bulkoperations_att_visibility li').each(function (index, li) {
            var o = {};
            o.attribute = $(li).find('select').val();
            if (parseInt(o.attribute) !== -1) {
                o.is_visible = $(li).find('input:checkbox').eq(0).is(':checked') ? 1 : 0;
                o.is_variation = $(li).find('input:checkbox').eq(1).is(':checked') ? 1 : 0;
                vis_data.push(o);
            }
        });

        //***
        if (woobe_checked_products.length > 0) {
            __woobe_bulkoperations_7(woobe_checked_products, 0, vis_data);
        } else {
            woobe_bulkoperations_xhr = $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_bulkoperations_get_prod_count',
                    filter_current_key: woobe_filter_current_key
                },
                success: function (products_ids) {
                    products_ids = JSON.parse(products_ids);

                    if (products_ids.length) {
                        __woobe_bulkoperations_7(products_ids, 0, vis_data);
                    }
                },
                error: function () {
                    if (!woobe_bulkoperations_user_cancel) {
                        alert(lang.error);
                        woobe_bulkoperations_terminate_7();
                    }
                    woobe_bulkoperations_is_going(false);
                }
            });
        }

    }


    return false;
}

//***

//service
function __woobe_bulkoperations_7(products, start, vis_data) {
    var step = 10;
    var products_ids = products.slice(start, start + step);

    //***

    woobe_bulkoperations_xhr = $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_bulkoperations_visibility',
            vis_data: vis_data,
            products_ids: products_ids
        },
        success: function () {
            if ((start + step) > products.length) {

                woobe_message(lang.bulkoperations.finished7, 'notice');
                $('.bulkoperations_apply_7_btn').show();
                $('.woobe_bulkoperations_terminate_btn').hide();
                woobe_set_progress('woobe_bulkoperations_progress_7', 100);
                $(document).trigger('woobe_bulkoperations_completed_visibility');
                woobe_bulkoperations_is_going(false);

            } else {
                //show %
                woobe_set_progress('woobe_bulkoperations_progress_7', (start + step) * 100 / products.length);
                __woobe_bulkoperations_7(products, start + step, vis_data);
            }
        },
        error: function () {
            if (!woobe_bulkoperations_user_cancel) {
                alert(lang.error);
                woobe_bulkoperations_terminate_7();
            }
            woobe_bulkoperations_is_going(false);
        }
    });
}


function woobe_bulkoperations_terminate_7() {
    if (confirm(lang.sure)) {
        woobe_bulkoperations_user_cancel = true;
        woobe_bulkoperations_xhr.abort();
        woobe_hide_progress('woobe_bulkoperations_progress_7');
        $('.bulkoperations_apply_7_btn').show();
        $('.woobe_bulkoperations_terminate_btn').hide();
        woobe_message(lang.canceled, 'error');
        woobe_bulkoperations_user_cancel = false;
        woobe_bulkoperations_is_going(false);
    }
}

