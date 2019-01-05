var bulkoperations_6_attributes = [];//list of attributes combos in operation
var bulkoperations_6_selected_attribute = 0;//selected attribute to check for existance

var bulkoperations_6_terms = [];//solo js
var bulkoperations_6_def_term = 0;//solo js

jQuery(function ($) {

    $('#bulkoperations_attaching_att').change(function () {
        woobe_message(lang.loading, 'warning');
        $(this).find('option[value=-1]').remove();
        bulkoperations_6_selected_attribute = $(this).val();
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_bulkoperations_get_att_terms',
                attribute: $(this).val()
            },
            success: function (terms) {
                woobe_message(lang.loaded, 'notice');
                var select_id = 'bulkoperations_attaching_defterms';
                $('#bulkoperations_attaching_defterms_container').html('<select id="' + select_id + '"></select>');
                bulkoperations_6_terms = JSON.parse(terms);

                bulkoperations_6_terms.push({term_id: 0, name: lang.ignore, slug: 'woobe-ignore'});

                __woobe_fill_select(select_id, bulkoperations_6_terms, [], 0, true);

                //***

                $('#bulkoperations_attaching_defterms').unbind('change');
                $('#bulkoperations_attaching_defterms').change(function () {
                    bulkoperations_6_def_term = $(this).val();
                    return true;
                });
                $('#bulkoperations_attaching_defterms').trigger('change');

                //***

                $('#bulkoperations_attributes_var_attaching select').html('');
                $('#bulkoperations_attributes_var_attaching select').each(function (id, select) {
                    __woobe_fill_select($(select).prop('id'), bulkoperations_6_terms, [bulkoperations_6_def_term], 0, true);
                });
            },
            error: function () {
                alert(lang.error);
            }
        });

        return true;
    });

    //***

    $('.bulkoperations_get_product_variations_btn_6').click(function () {
        bulkoperations_get_product_variations_btn_6();
        return false;
    });


    $("#woobe-bulkoperations-attaching-id").keydown(function (e) {
        if (e.keyCode == 13)
        {
            $('.bulkoperations_get_product_variations_btn_6').trigger('click');
        }
    });



});


//***

function bulkoperations_get_product_variations_btn_6() {
    var product_id = parseInt($('#woobe-bulkoperations-attaching-id').val(), 10);

    if (product_id > 0) {
        woobe_message(lang.loading, 'warning', 99999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_bulkoperations_get_product_variations',
                product_id: product_id
            },
            success: function (vars) {
                woobe_message(lang.loaded, 'notice', 999);
                vars = JSON.parse(vars);

                $('#bulkoperations_attributes_var_attaching').html('');
                $('.bulkoperations_apply_6_btn').hide();

                if ($(vars).size() > 0) {
                    $('.bulkoperations_apply_6_btn').show();
                    var li_tpl = $('#bulkoperations_attributes_attaching_tpl').html();

                    var num = 0;

                    var select_options = bulkoperations_6_terms;
                    //select_options.push({term_id: 0, name: lang.ignore, slug: 'woobe-ignore'});

                    $.each(vars, function (id, v) {
                        var li = li_tpl;
                        li = li.replace(/__LABEL__/gi, v.title);
                        li = li.replace(/__ID__/gi, id);
                        li = li.replace(/__NUM__/gi, num);
                        bulkoperations_6_attributes.push({attributes: v.attributes, id: id});
                        $('#bulkoperations_attributes_var_attaching').append(li);
                        num++;

                        //***
                        //fill up drop-downs                        
                        __woobe_fill_select('bulkoperations_attributes_attaching_sel_' + id, select_options, [bulkoperations_6_def_term], 0, true);

                    });

                } else {
                    woobe_message(lang.bulkoperations.no_vars, 'error');
                }
            },
            error: function () {
                $('#bulkoperations_attributes_var_attaching').html('');
                $('.bulkoperations_apply_6_btn').hide();
                alert(lang.error);
            }
        });
    }
}


//**************************************************************

function bulkoperations_apply_6() {

    var attaching_att = $('#bulkoperations_attaching_att').val();

    if (parseInt(attaching_att, 10) === -1) {
        woobe_message(lang.bulkoperations.no_combinations, 'error');
        return;
    }

    //***

    if (confirm(lang.sure)) {

        woobe_bulkoperations_is_going();
        $('.bulkoperations_apply_6_btn').hide();
        $('.woobe_bulkoperations_terminate_btn').show();
        woobe_set_progress('woobe_bulkoperations_progress_6', 0);

        //***
        //assembling data before sending to the server
        $(bulkoperations_6_attributes).each(function (index, att) {
            att.value = $('#bulkoperations_attributes_attaching_sel_' + att.id).val();
        });

        //***
        if (woobe_checked_products.length > 0) {
            __woobe_bulkoperations_6(woobe_checked_products, 0);
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
                        __woobe_bulkoperations_6(products_ids, 0);
                    }
                },
                error: function () {
                    if (!woobe_bulkoperations_user_cancel) {
                        alert(lang.error);
                        woobe_bulkoperations_terminate_6();
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
function __woobe_bulkoperations_6(products, start) {
    var step = 2;
    var products_ids = products.slice(start, start + step);

    //***

    woobe_bulkoperations_xhr = $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_bulkoperations_attaching',
            selected_attribute: bulkoperations_6_selected_attribute,
            products_ids: products_ids,
            attaching_att: bulkoperations_6_attributes
        },
        success: function () {
            if ((start + step) > products.length) {

                woobe_message(lang.bulkoperations.finished6, 'notice');
                //https://datatables.net/reference/api/draw()
                data_table.draw('page');
                $('.bulkoperations_apply_6_btn').show();
                $('.woobe_bulkoperations_terminate_btn').hide();
                woobe_set_progress('woobe_bulkoperations_progress_6', 100);
                $(document).trigger('woobe_bulkoperations_completed_attaching');
                woobe_bulkoperations_is_going(false);

            } else {
                //show %
                woobe_set_progress('woobe_bulkoperations_progress_6', (start + step) * 100 / products.length);
                __woobe_bulkoperations_6(products, start + step);
            }
        },
        error: function () {
            if (!woobe_bulkoperations_user_cancel) {
                alert(lang.error);
                woobe_bulkoperations_terminate_6();
            }
            woobe_bulkoperations_is_going(false);
        }
    });
}


function woobe_bulkoperations_terminate_6() {
    if (confirm(lang.sure)) {
        woobe_bulkoperations_user_cancel = true;
        woobe_bulkoperations_xhr.abort();
        woobe_hide_progress('woobe_bulkoperations_progress_6');
        $('.bulkoperations_apply_6_btn').show();
        $('.woobe_bulkoperations_terminate_btn').hide();
        woobe_message(lang.canceled, 'error');
        woobe_bulkoperations_user_cancel = false;
        woobe_bulkoperations_is_going(false);
    }
}

