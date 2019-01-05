var woobe_calculator_current_cell = null;
var woobe_calculator_is_drawned = false;


jQuery(function ($) {

    $('.woobe_calculator_operation').val(woobe_get_from_storage('woobe_calculator_operation'));
    $('.woobe_calculator_how').val(woobe_get_from_storage('woobe_calculator_how'));

    //***

    $('.woobe_calculator_close').click(function () {
        $('#woobe_calculator').hide(99);
        woobe_calculator_is_drawned = false;
        return false;
    });

    //***

    $(document).on("tab_switched", {}, function (event) {
        $('.woobe_calculator_btn').hide();
        return true;
    });

    $(document).on("data_redraw_done", {}, function (event) {
        $('.woobe_calculator_btn').hide();
        return true;
    });

    $(document).on("woobe_top_panel_clicked", {}, function (event) {
        $('.woobe_calculator_btn').hide();
        return true;
    });

    //***

    $(document).on("woobe_onmouseover_num_textinput", {}, function (event, o, colIndex) {
        woobe_calc_onmouseover_num_textinput(o, colIndex);
        return true;
    });

    $(document).on("woobe_onmouseout_num_textinput", {}, function (event, o, colIndex) {
        woobe_calc_onmouseout_num_textinput(o, colIndex);
        return true;
    });

    //***

    $('.woobe_calculator_set').click(function () {

        var val = parseFloat($('.woobe_calculator_value').val());

        if (isNaN(val)) {
            $('.woobe_calculator_close').trigger('click');
            return;
        }

        var operation = $('.woobe_calculator_operation').val();
        var how = $('.woobe_calculator_how').val();



        //***

        var cell = woobe_calculator_current_cell;//to avoid mouse over set of another cell whicle ajaxing
        var product_id = $(cell).data('product-id');

        //***

        //fix
        if ($(cell).data('field') !== 'sale_price' && operation == 'rp-') {
            operation = '+';
        }

        if ($(cell).data('field') !== 'regular_price' && operation == 'sp+') {
            operation = '+';
        }

        //***

        var cell_value = parseFloat($(cell).html());

        var bulk_operation = 'invalue';

        //***

        switch (operation) {
            case '+':
                if (how == 'value') {
                    cell_value += val;
                } else {
                    //%
                    cell_value = cell_value + cell_value * val / 100;
                    bulk_operation = 'inpercent';
                }
                break;

            case '-':
                if (how == 'value') {
                    cell_value -= val;
                    bulk_operation = 'devalue';
                } else {
                    //%
                    cell_value = cell_value - cell_value * val / 100;
                    bulk_operation = 'depercent';
                }
                break;

            case 'rp-':

                cell_value = parseFloat($('#product_row_' + product_id).find("[data-field='regular_price']").html());

                if (how == 'value') {
                    cell_value = cell_value - val;
                    bulk_operation = 'devalue_regular_price';
                } else {
                    //%
                    cell_value = cell_value - cell_value * val / 100;
                    bulk_operation = 'depercent_regular_price';
                }
                break;

            case 'sp+':

                cell_value = parseFloat($('#product_row_' + product_id).find("[data-field='sale_price']").html());

                if (how == 'value') {
                    cell_value = cell_value + val;
                    bulk_operation = 'invalue_sale_price';
                } else {
                    //%
                    cell_value = cell_value + cell_value * val / 100;
                    bulk_operation = 'inpercent_sale_price';
                }
                break;
        }

        //***

        woobe_message(lang.saving, '');


        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_update_page_field',
                product_id: product_id,
                field: $(cell).data('field'),
                value: cell_value,
                num_rounding: $('.woobe_num_rounding').eq(0).val()
            },
            success: function (answer) {
                $(cell).html(answer);
                woobe_message(lang.saved, 'notice');


                //fix for stock_quantity + manage_stock
                if (!woobe_bind_editing) {
                    if ($(cell).data('field') == 'stock_quantity') {
                        woobe_redraw_table_row($('#product_row_' + $(cell).data('product-id')));
                    }
                }

                $(document).trigger('woobe_page_field_updated', [$(cell).data('product-id'), $(cell).data('field'), val, bulk_operation]);

                //$('.woobe_num_rounding').val(0);

                //woobe_calculator_current_cell = null;
            }
        });


        $('.woobe_calculator_close').trigger('click');
        return false;
    });

    //***

    $(".woobe_calculator_value").keydown(function (e) {
        if (e.keyCode == 13)
        {
            $('.woobe_calculator_set').trigger('click');
        }

        if (e.keyCode == 27)
        {
            $('.woobe_calculator_close').trigger('click');
        }
    });

    $("#woobe_calculator").keydown(function (e) {
        if (e.keyCode == 27)
        {
            $('.woobe_calculator_close').trigger('click');
        }
    });

    //***

    $('.woobe_calculator_operation').change(function () {
        woobe_set_to_storage('woobe_calculator_operation', $(this).val());
        return true;
    });

    $('.woobe_calculator_how').change(function () {
        woobe_set_to_storage('woobe_calculator_how', $(this).val());
        return true;
    });

    //***
    $('div.dataTables_scrollBody').scroll(function () {
        $('.woobe_calculator_btn').hide();
    });

});

function woobe_calc_onmouseover_num_textinput(_this, colIndex) {

    if (woobe_calculator_is_drawned) {
        return;
    }

    if ($(_this).find('.info_restricked').size() > 0) {
        $('.woobe_calculator_btn').hide();
        return;
    }

    //***

    woobe_calculator_current_cell = _this;
    $('.woobe_calculator_btn').show();
    var rt = ($(window).width() - ($(_this).offset().left + $(_this).outerWidth()));
    var tt = $(_this).offset().top/* - $(_this).outerHeight() / 2.3*/;
    $('.woobe_calculator_btn').css({top: tt, right: rt});

    return true;
}

function woobe_draw_calculator() {
    $('#woobe_calculator').show();
    $('#woobe_calculator').css({top: $('.woobe_calculator_btn').css('top'), right: $('.woobe_calculator_btn').css('right')});
    $(".woobe_calculator_value").focus();

    //if input activated and visible in the cell
    if ($(woobe_calculator_current_cell).find('input')) {
        $(woobe_calculator_current_cell).html($(woobe_calculator_current_cell).find('input').val());

        //***

        if ($(woobe_calculator_current_cell).data('field') == 'sale_price') {
            var product_id = $(woobe_calculator_current_cell).data('product-id');
            //reqular_price column is enabled
            if ($('#product_row_' + product_id).find("[data-field='regular_price']").length > 0) {
                $('.woobe_calc_rp').show();
            } else {
                $('.woobe_calc_rp').hide();
                $('.woobe_calculator_operation').val('+');
            }

        } else {
            $('.woobe_calc_rp').hide();
            if ($('.woobe_calculator_operation').val() == 'rp-') {
                $('.woobe_calculator_operation').val('+');
            }
        }

        //***

        if ($(woobe_calculator_current_cell).data('field') == 'regular_price') {
            var product_id = $(woobe_calculator_current_cell).data('product-id');
            //reqular_price column is enabled
            if ($('#product_row_' + product_id).find("[data-field='sale_price']").length > 0) {
                $('.woobe_calc_sp').show();
            } else {
                $('.woobe_calc_sp').hide();
                $('.woobe_calculator_operation').val('+');
            }

        } else {
            $('.woobe_calc_sp').hide();
            if ($('.woobe_calculator_operation').val() == 'sp+') {
                $('.woobe_calculator_operation').val('+');
            }
        }
    }

    woobe_calculator_is_drawned = true;
    
    return true;
}

function woobe_calc_onmouseout_num_textinput() {
    if (woobe_calculator_is_drawned) {
        //$('.woobe_calculator_btn').hide();
    }
    return true;
}


