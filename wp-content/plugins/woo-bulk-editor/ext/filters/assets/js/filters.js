var woobe_filtering_is_going = false;//marker about that products are filtered
var woobe_filter_chosen_inited = false;//just fix to init chosen
var woobe_filter_current_key = null;//unique id of current filter operation, which allow make bulk by filter in different browser tabs!

jQuery(function ($) {

    //init chosen by first click because chosen init doesn work for hidden containers
    $(document).on("do_tabs-filters", {}, function () {
        //if (!woobe_filter_chosen_inited) {
        setTimeout(function () {
            //set chosen
            $('#tabs-filters .chosen-select').chosen();
            woobe_filter_chosen_inited = true;
        }, 150);
        //}

        return true;
    });

    //set chosen to filters tab only
    $('a[href="#tabs-filters"]').trigger('click');

    $('.woobe_filter_select').change(function () {
        if ($(this).val() == -1 || $(this).val() == 0) {
            $(this).removeClass('woobe_set_attention');
        } else {
            $(this).addClass('woobe_set_attention');
        }
        return true;
    });

    //***

    //placeholder label
    $('#woobe_filter_form input[placeholder]:not(.woobe_calendar)').placeholderLabel();

    //***

    //Filter button
    $('#woobe_filter_products_btn').click(function () {

        //$('.woobe_txt_search').val('');
        woobe_message(lang.filters.filtering, 'warning');
        woobe_filter_current_key = (woobe_get_random_string(16)).toLowerCase();
        $('.woobe_tools_panel_newprod_btn').hide();
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_filter_products',
                filter_data: $('#woobe_filter_form').serialize(),
                filter_current_key: woobe_filter_current_key
            },
            success: function () {
                woobe_message(lang.filters.filtered, 'notice', 30000);
                data_table.clear().draw();

                $('.woobe_filter_reset_btn1').show();
                $('.woobe_filter_reset_btn2').show();
                woobe_filtering_is_going = true;
                __woobe_action_will_be_applied_to();
            },
            error: function () {
                alert(lang.error);
            }
        });

        return false;
    });


    //Reset Filter button
    $('.woobe_filter_reset_btn1, .woobe_filter_reset_btn2').click(function () {

        var _this = this;
        woobe_message(lang.reseting, 'warning', 99999);
        $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_reset_filter',
                filter_current_key: woobe_filter_current_key
            },
            success: function () {

                if (!$(_this).hasClass('woobe_filter_reset_btn2')) {
                    //$('.woobe_top_panel_btn').trigger('click');
                }

                woobe_filter_current_key = null;
                $('.woobe_tools_panel_newprod_btn').show();

                data_table.clear().draw();
                woobe_message(lang.reseted, 'notice');
                $('.woobe_filter_reset_btn1').hide();
                $('.woobe_filter_reset_btn2').hide();
                //clear all filter drop-downs and inputs
                __woobe_clean_filter_form();

                woobe_filtering_is_going = false;
                __woobe_action_will_be_applied_to();
            },
            error: function () {
                alert(lang.error);
            }
        });

        return false;
    });

    //***

    $('#woobe_filter_form input').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#woobe_filter_products_btn').trigger('click');
        }
    });

    //***

    $(document).on("taxonomy_data_redrawn", {}, function (event, tax_key, term_id) {

        var select_id = 'woobe_filter_taxonomies_' + tax_key;
        var select = $('#' + select_id);
        $(select).empty();
        __woobe_fill_select(select_id, taxonomies_terms[tax_key]);
        $($('#' + select_id)).chosen({
            width: '100%'
        }).trigger("chosen:updated");

        return true;
    });


});


function __woobe_clean_filter_form() {
    $('#woobe_filter_form input[type=text]').val('');
    $('#woobe_filter_form input[type=number]').val('');
    $('#woobe_filter_form .woobe_calendar').val('').trigger('change');
    $('#woobe_filter_form select.chosen-select').val('').trigger("chosen:updated");
    $('#woobe_filter_form select:not(.chosen-select)').each(function (i, s) {
        $(s).val($(s).find('option:first').val());
    });
    $('#woobe_filter_form select').removeClass('woobe_set_attention');
}

