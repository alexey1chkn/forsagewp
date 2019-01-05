var bulkoperations_4_products_id = 0;
var bulkoperations_4_attributes = [];

jQuery(function ($) {

    $('.bulkoperations_get_product_variations_btn').click(function () {
        bulkoperations_get_product_variations();
        return false;
    });


    $("#woobe-bulkoperations-ordering-id").keydown(function (e) {
        if (e.keyCode == 13)
        {
            $('.bulkoperations_get_product_variations_btn').trigger('click');
        }
    });
});

function bulkoperations_get_product_variations() {
    bulkoperations_4_products_id = parseInt($('#woobe-bulkoperations-ordering-id').val(), 10);

    if (bulkoperations_4_products_id > 0) {
        woobe_message(lang.loading, 'warning', 99999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_bulkoperations_get_product_variations',
                product_id: bulkoperations_4_products_id
            },
            success: function (vars) {
                woobe_message(lang.loaded, 'notice', 999);
                vars = JSON.parse(vars);

                $('#bulkoperations_attributes_var_order').html('');
                $('.bulkoperations_apply_4_btn').hide();

                if ($(vars).size() > 0) {
                    $('.bulkoperations_apply_4_btn').show();
                    var li_tpl = $('#bulkoperations_attributes_order_tpl').html();

                    var num = 0;
                    $.each(vars, function (id, v) {
                        var li = li_tpl;
                        li = li.replace(/__LABEL__/gi, v.title);
                        li = li.replace(/__ID__/gi, id);
                        li = li.replace(/__NUM__/gi, num);
                        bulkoperations_4_attributes.push(v.attributes);
                        $('#bulkoperations_attributes_var_order').append(li);
                        num++;
                    });

                    $("#bulkoperations_attributes_var_order").sortable({
                        items: "li:not(.unsortable)",
                        update: function (event, ui) {},
                        opacity: 0.8,
                        cursor: "crosshair",
                        handle: '.woobe_drag_and_drope',
                        placeholder: 'woobe-options-highlight'
                    });

                } else {
                    woobe_message(lang.bulkoperations.no_vars, 'error');
                }
            },
            error: function () {
                alert(lang.error);
            }
        });
    }
}

function bulkoperations_apply_4() {

    if (bulkoperations_4_products_id > 0) {
        if (confirm(lang.sure)) {

            woobe_bulkoperations_is_going();
            $('.bulkoperations_apply_4_btn').hide();
            $('.woobe_bulkoperations_terminate_btn').show();
            woobe_set_progress('woobe_bulkoperations_progress_4', 0);

            //***
            var combination = [];
            $('#bulkoperations_attributes_var_order li').each(function (i, li) {
                combination.push(bulkoperations_4_attributes[$(li).data('var-num')]);
            });

            //***
            if (woobe_checked_products.length > 0) {
                __woobe_bulkoperations_4(woobe_checked_products, 0, combination);
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
                            __woobe_bulkoperations_4(products_ids, 0, combination);
                        }
                    },
                    error: function () {
                        if (!woobe_bulkoperations_user_cancel) {
                            alert(lang.error);
                            woobe_bulkoperations_terminate4();
                        }
                        woobe_bulkoperations_is_going(false);
                    }
                });
            }

        }
    }

    return false;
}

//***

//service
function __woobe_bulkoperations_4(products, start, combination) {
    var step = 10;
    var products_ids = products.slice(start, start + step);

    woobe_bulkoperations_xhr = $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_bulkoperations_ordering',
            products_ids: products_ids,
            combination: combination,
            product_id: bulkoperations_4_products_id
        },
        success: function () {
            if ((start + step) > products.length) {

                woobe_message(lang.bulkoperations.finished4, 'notice');
                //https://datatables.net/reference/api/draw()
                data_table.draw('page');
                $('.bulkoperations_apply_4_btn').show();
                $('.woobe_bulkoperations_terminate_btn').hide();
                woobe_set_progress('woobe_bulkoperations_progress_4', 100);
                $(document).trigger('woobe_bulkoperations_completed_ordering');
                woobe_bulkoperations_is_going(false);

            } else {
                //show %
                woobe_set_progress('woobe_bulkoperations_progress_4', (start + step) * 100 / products.length);
                __woobe_bulkoperations_4(products, start + step, combination);
            }
        },
        error: function () {
            if (!woobe_bulkoperations_user_cancel) {
                alert(lang.error);
                woobe_bulkoperations_terminate4();
            }
            woobe_bulkoperations_is_going(false);
        }
    });
}


function woobe_bulkoperations_terminate4() {
    if (confirm(lang.sure)) {
        woobe_bulkoperations_user_cancel = true;
        woobe_bulkoperations_xhr.abort();
        woobe_hide_progress('woobe_bulkoperations_progress_4');
        $('.bulkoperations_apply_4_btn').show();
        $('.woobe_bulkoperations_terminate_btn').hide();
        woobe_message(lang.canceled, 'error');
        woobe_bulkoperations_user_cancel = false;
        woobe_bulkoperations_is_going(false);
    }
}

