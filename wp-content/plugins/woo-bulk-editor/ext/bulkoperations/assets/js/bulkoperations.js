var bulkoperations_selected_attributes = [];
var woobe_bulkoperations_xhr = null;//current ajax request (for cancel)
var woobe_bulkoperations_user_cancel = false;//current ajax request (for cancel)


jQuery(function ($) {
    $('.woobe_tools_panel_newvars_btn').click(function () {

        var popup = $('#bulkoperations_popup');

        $(popup).show();

        $('.woobe-modal-close-bulkoperations').unbind('click');
        $('.woobe-modal-close-bulkoperations').click(function () {
            $(popup).hide();
        });

        //***

        $('#bulkoperations_attributes').chosen({
            width: '100%'
        }).trigger("chosen:updated");


        $('#bulkoperations_attributes').unbind('change');
        $('#bulkoperations_attributes').change(function () {

            if ($(this).val() && bulkoperations_selected_attributes.length < $(this).val().length) {
                //add
                //https://stackoverflow.com/questions/1187518/how-to-get-the-difference-between-two-arrays-in-javascript
                var diff = $($(this).val()).not(bulkoperations_selected_attributes).get();
                bulkoperations_selected_attributes = $(this).val();
                var new_attribute = diff[0];
                var new_attribute_label = $(this).find('option[value="' + new_attribute + '"]').text();

                $('.bulkoperations_generate_combinations_btn').show();

                //***

                woobe_message(lang.loading, 'warning');

                $.ajax({
                    method: "POST",
                    url: ajaxurl,
                    data: {
                        action: 'woobe_bulkoperations_get_att_terms',
                        attribute: new_attribute
                    },
                    success: function (terms) {
                        woobe_message(lang.loaded, 'notice');
                        var select_id = 'bulkoperations_t_' + new_attribute;
                        $('#bulkoperations_attributes_terms').append('<li><select multiple="" id="' + select_id + '" data-attribute="' + new_attribute + '" data-placeholder="' + new_attribute_label + '"></select></li>');
                        __woobe_fill_select(select_id, JSON.parse(terms));
                        $('#' + select_id).chosen({
                            width: '100%'
                        });
                    }
                });

            } else {
                //remove
                if ($(this).val()) {
                    bulkoperations_selected_attributes = $(this).val();
                } else {
                    bulkoperations_selected_attributes = [];
                }

                //***

                if (bulkoperations_selected_attributes.length === 0) {
                    $('#bulkoperations_attributes_terms').html('');
                    $('.bulkoperations_generate_combinations_btn').hide();
                    $('#bulkoperations_step_3').hide();
                } else {

                    $('#bulkoperations_attributes_terms select').each(function (i, s) {
                        var tax = $(this).data('attribute');
                        if ($.inArray(tax, bulkoperations_selected_attributes) === -1) {
                            $(this).chosen("destroy").parent().remove();
                        }
                    });
                }

            }



        });


        return false;
    });

    //***


});

function bulkoperations_generate_combinations() {

    woobe_message(lang.bulkoperations.generating, 'warning', 30000);

    var data = [];
    var labels = [];
    $('#bulkoperations_attributes_combos').empty();
    //bulkoperations_attributes_terms
    $('#bulkoperations_attributes_terms select').each(function (i, sel) {
        var vals = $(sel).val();

        if (!vals) {
            return;
        }

        vals = vals.map(function (x) {
            return parseInt(x, 10);
        });

        data[$(sel).data('attribute')] = vals;

        $.each($(sel).find('option'), function (i, o) {
            if ($.inArray(parseInt($(o).val(), 10), vals) > -1) {
                labels[parseInt($(o).val(), 10)] = $(o).text();
            }
        });
    });


    //***


    var variants = [];
    //lets generate possible variants
    Object.keys(data).map(function (objectKey, index) {
        variants.push(data[objectKey]);
    });

    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_bulkoperations_get_possible_combos',
            arrays: variants
        },
        success: function (combinations) {
            combinations = JSON.parse(combinations);
            if (combinations.length > 0) {
                var li_tpl = $('#bulkoperations_attributes_combo_tpl').html();
                for (var i = 0; i < combinations.length; i++) {
                    if (combinations[i].length) {
                        var st1 = '';
                        var st2 = '';
                        for (var y = 0; y < combinations[i].length; y++) {
                            if (y > 0) {
                                st1 += ' | ';
                                st2 += ',';
                            }
                            st1 += labels[combinations[i][y]];
                            st2 += combinations[i][y];
                        }
                        var li = li_tpl;
                        li = li.replace(/__LABEL__/gi, st1);
                        li = li.replace(/__DATA_TERMS__/gi, st2);
                        li = li.replace(/__ID__/gi, woobe_get_random_string(8));
                        $('#bulkoperations_attributes_combos').append(li);
                    }
                }
            }

            $('#bulkoperations_attributes_combos').parent().find('h4 span').html($('#bulkoperations_attributes_combos li').size());

            $("#bulkoperations_attributes_combos").sortable({
                items: "li:not(.unsortable)",
                update: function (event, ui) {},
                opacity: 0.8,
                cursor: "crosshair",
                handle: '.woobe_drag_and_drope',
                placeholder: 'woobe-options-highlight'
            });

            //***

            woobe_message(lang.bulkoperations.generated, 'notice');
            $('#bulkoperations_step_3').show();
        }
    });


    return false;
}

function bulkoperations_generate_variations() {
    if (confirm(lang.sure)) {
        var checked = $('#bulkoperations_attributes_combos').find('input:checked');
        var combinations = [];

        if (checked.length > 0) {

            woobe_bulkoperations_is_going();
            $('.bulkoperations_generate_variations_btn').hide();
            $('.woobe_bulkoperations_terminate_btn').show();
            woobe_set_progress('woobe_bulkoperations_progress', 0);

            //***

            $(checked).each(function (i, o) {
                var tmp = $(o).data('terms') + '';
                tmp = tmp.split(',');
                tmp = tmp.map(function (x) {
                    return parseInt(x, 10);
                });
                combinations.push(tmp);
            });


            //***
            if (woobe_checked_products.length > 0) {
                __woobe_bulkoperations_products(woobe_checked_products, 0, combinations);
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
                            __woobe_bulkoperations_products(products_ids, 0, combinations);
                        }
                    },
                    error: function () {
                        if (!woobe_bulkoperations_user_cancel) {
                            alert(lang.error);
                            woobe_bulkoperations_terminate();
                        }
                        woobe_bulkoperations_is_going(false);
                    }
                });
            }

        } else {
            woobe_message(lang.bulkoperations.no_combinations, 'warning', 3000);
        }
    }


    return false;
}

//***

//service
function __woobe_bulkoperations_products(products, start, combinations) {
    var step = 2;
    var products_ids = products.slice(start, start + step);

    woobe_bulkoperations_xhr = $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_bulkoperations_apply_combinations',
            products_ids: products_ids,
            combinations: combinations
        },
        success: function () {
            if ((start + step) > products.length) {

                woobe_message(lang.bulkoperations.finished, 'notice', 10000);
                //https://datatables.net/reference/api/draw()
                data_table.draw('page');
                $('.bulkoperations_generate_variations_btn').show();
                $('.woobe_bulkoperations_terminate_btn').hide();
                woobe_set_progress('woobe_bulkoperations_progress', 100);
                $(document).trigger('woobe_bulkoperations_completed');
                woobe_bulkoperations_is_going(false);

            } else {
                //show %
                woobe_set_progress('woobe_bulkoperations_progress', (start + step) * 100 / products.length);
                __woobe_bulkoperations_products(products, start + step, combinations);
            }
        },
        error: function () {
            if (!woobe_bulkoperations_user_cancel) {
                alert(lang.error);
                woobe_bulkoperations_terminate();
            }
            woobe_bulkoperations_is_going(false);
        }
    });
}


function woobe_bulkoperations_terminate() {
    if (confirm(lang.sure)) {
        woobe_bulkoperations_user_cancel = true;
        woobe_bulkoperations_xhr.abort();
        woobe_hide_progress('woobe_bulkoperations_progress');
        $('.bulkoperations_generate_variations_btn').show();
        $('.woobe_bulkoperations_terminate_btn').hide();
        woobe_message(lang.canceled, 'error');
        woobe_bulkoperations_user_cancel = false;
        woobe_bulkoperations_is_going(false);
    }
}

function woobe_bulkoperations_is_going(go = true) {
    if (go) {
        $('#wp-admin-bar-root-default').append("<li id='woobe_bulkoperations_is_going'>" + lang.bulkoperations.going + "</li>");
    } else {
        $('#woobe_bulkoperations_is_going').remove();
    }

    //any way some operations been done
    $(document).trigger('woobe_page_field_updated', [0, 0, 0]);

}

