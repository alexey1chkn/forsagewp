var woobe_popup_clicked = null;
//init_woobe_popups();
var woobe_sort_order = [];
var woobe_checked_products = [];//product id which been checked
var woobe_last_checked_product = {id: 0, checked: false};
var woobe_tools_panel_full_width = 0;


(function ($) {

    jQuery.fn.life = function (types, data, fn) {
        jQuery(this.context).on(types, this.selector, data, fn);
        return this;
    };

    //***


    $(function () {

        $('.woobe-tabs').woobeTabs();
        //$.woobe_mod.popup_prepare();

        //***

        $(document).keyup(function (e) {
            if (e.keyCode === 27) {
                $('.woobe-modal-close').trigger('click');
            }
        });

        woobe_init_tips($('.zebra_tips1'));

        //***
        //for columns coloring
        try {
            $('.woobe-color-picker').wpColorPicker();
        } catch (e) {
            console.log(e);
        }

        setTimeout(function () {
            $('.woobe_column_color_pickers').each(function (index, picker) {
                $(picker).find('span.wp-color-result-text').eq(0).html(lang.color_picker_col);
                $(picker).find('span.wp-color-result-text').eq(1).html(lang.color_picker_txt);
                //$('.button.wp-color-result').attr('disabled', true);
            });
        }, 1000);

        //***

        $(".woobe_fields").sortable({
            items: "li:not(.unsortable)",
            update: function (event, ui) {
                woobe_sort_order = [];
                $('.woobe_fields').children('li').each(function (index, value) {
                    var key = $(this).data('key');
                    woobe_sort_order.push(key);
                });
                $('input[name="woobe[items_order]"]').val(woobe_sort_order.toString());
            },
            opacity: 0.8,
            cursor: "crosshair",
            handle: '.woobe_drag_and_drope',
            placeholder: 'woobe-options-highlight'
        });

        //fix: to avoid jumping
        $('.woobe_drag_and_drope').life('click', function () {
            return false;
        });

        //***

        $('#tabs_f .woobe_calendar_cell_clear').click(function () {
            $(this).parent().find('.woobe_calendar').val('').trigger('change');
            return false;
        });


        //options saving
        $('#mainform').submit(function () {
            woobe_save_form(this, 'woobe_save_options');
            return false;
        });

        //***

        $('#show_all_columns').click(function () {
            $('.woobe_fields li').show();
            $(this).parent().remove();
            return false;
        });

        //columns finder
        $('#woobe_columns_finder').on('keyup keypress', function (e) {
            var keyCode = e.keyCode || e.which;
            //preventing form submit if press Enter button
            if (keyCode === 13) {
                e.preventDefault();
                return false;
            }

            //***

            $('#tabs-settings .woobe_fields li').show();
            var search = $(this).val().toLowerCase();

            $('#tabs-settings .woobe_fields li.woobe_options_li .woobe_column_li_option').each(function (index, input) {
                var txt = $(input).val().toLowerCase();
                if (txt.indexOf(search) != -1) {
                    $(input).parents('li').show();
                } else {
                    $(input).parents('li').hide();
                }
            });

            return true;
        });

        //*****************************************

        $('.woobe_select_image').life('click', function ()
        {
            var input_object = $(this).prev('input[type=text]');
            window.send_to_editor = function (html)
            {
                $('#woobe_buffer').html(html);
                var imgurl = $('#woobe_buffer').find('a').eq(0).attr('href');
                $('#woobe_buffer').html("");
                $(input_object).val(imgurl);
                $(input_object).trigger('change');
                tb_remove();
            };
            tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true');

            return false;
        });

        //***

        woobe_init_advanced_panel();
        if (parseInt(woobe_get_from_storage('woobe_tools_panel_full_width_btn'), 10)) {
            $('.woobe_tools_panel_full_width_btn').trigger('click');
        }
        //woobe_init_bulk_panel();

        //options columns switchers only!
        woobe_init_switchery(false);

        //***
        $(document).scroll(function (e) {
            var offset = ($('#tabs').offset().top + 15) - $(document).scrollTop();

            if (offset < 0) {
                if (!$('#woobe_tools_panel').hasClass('woobe-adv-panel-fixed')) {
                    $('#woobe_tools_panel').addClass('woobe-adv-panel-fixed');
                    $('#woobe_tools_panel').css('top', $('#wpadminbar').height() + 'px');
                    $('#woobe_tools_panel').css('width', $('#tabs-products').width() + 'px');
                }
            } else {
                $('#woobe_tools_panel').removeClass('woobe-adv-panel-fixed');
            }
        });


        //the data table horizontal scrollbar



        /*
         jQuery('.scrollbar-external').scrollbar({
         "autoScrollSize": false,
         "scrollx": $('.external-scroll_x'),
         //"scrolly": $('.external-scroll_y')
         });
         */

        setTimeout(function () {
            $('.dataTables_scrollBody').scrollbar({
                autoScrollSize: false,
                scrollx: $('.external-scroll_x'),
                scrolly: $('.external-scroll_y')
            });

            //***


            $(document).on("tab_switched", {}, function (e, tab_id) {

                var allow = ['tabs-products'];

                if ($.inArray(tab_id, allow) > -1) {
                    $('.external-scroll_wrapper').show();
                } else {
                    $('.external-scroll_wrapper').hide();
                }

                return true;
            });

        }, 2000);

        //***

        $('.shop_manager_visibility').click(function () {
            var key = $(this).data('key');
            var val = 0;

            if ($(this).is(':checked')) {
                val = 1;
            }

            $("input[name='woobe_options[fields][" + key + "][shop_manager_visibility]']").val(val);
            return true;
        });


    });

})(jQuery);


function woobe_init_advanced_panel() {

    //full width button
    $('.woobe_tools_panel_full_width_btn').click(function () {
        if (woobe_tools_panel_full_width === 0) {
            woobe_tools_panel_full_width = $('#adminmenuwrap').width();
            $('#adminmenuback').hide();
            $('#adminmenuwrap').hide();
            $('#wpcontent').css('margin-left', '0px');
            $(this).addClass('button-primary');
            woobe_set_to_storage('woobe_tools_panel_full_width_btn', 1);
        } else {
            $('#adminmenuback').show();
            $('#adminmenuwrap').show();
            $('#wpcontent').css('margin-left', woobe_tools_panel_full_width + 'px');
            $(this).removeClass('button-primary');
            woobe_tools_panel_full_width = 0;
            woobe_set_to_storage('woobe_tools_panel_full_width_btn', 0);
        }

        __trigger_resize();

        return false;
    });

    //***

    $('.woobe_tools_panel_profile_btn').click(function () {
        //$('#woobe_tools_panel_profile_popup .woobe-modal-title').html($(this).data('name') + ' [' +$(this).data('key') + ']');
        $('#woobe_tools_panel_profile_popup').show();
        $('#woobe_new_profile').focus();

        return false;
    });


    //***

    $('.woobe-modal-close8').click(function () {
        $('#woobe_tools_panel_profile_popup').hide();
    });

    //***

    woobe_init_profiles();

    //***
    //creating of new product
    $('.woobe_tools_panel_newprod_btn').click(function () {

        var count = 1;

        if (count = prompt(lang.enter_new_count, 1)) {
            if (count > 0) {
                woobe_message(lang.creating, 'warning');
                __woobe_product_new(count, 0);
            }
        }

        return false;
    });

    //***

    $('.woobe_tools_panel_duplicate_btn').click(function () {

        var products_ids = [];
        $('.woobe_product_check').each(function (ii, ch) {
            if ($(ch).prop('checked')) {
                products_ids.push($(ch).data('product-id'));
            }
        });

        if (products_ids.length) {
            var count = 1;
            if (count = prompt(lang.enter_duplicate_count, 1)) {
                if (count > 0) {
                    var products = [];
                    for (var i = 0; i < count; i++) {
                        for (var y = 0; y < products_ids.length; y++) {
                            products.push(products_ids[y]);
                        }
                    }

                    products = products.reverse();

                    woobe_message(lang.duplicating, 'warning', 99999);
                    __woobe_product_duplication(products, 0, 0);
                }
            }
        }

        return false;
    });

    //hide or show duplicate button
    $('.woobe_product_check').life('click', function (e) {

        var product_id = parseInt($(this).data('product-id'), 10);

        //if keep SHIFT button and check product checkbox - possible to select/deselect products rows
        if (e.shiftKey) {

            if ($(this).prop('checked')) {
                var to_check = true;
            } else {
                var to_check = false;
            }
            var distance_now = $('#product_row_' + $(this).data('product-id')).offset().top;
            var distance_last = $('#product_row_' + woobe_last_checked_product.id).offset().top;
            var rows = $('#advanced-table tbody tr');

            if (distance_now > distance_last) {
                //check/uncheck all above to woobe_last_checked_product.id
                $(rows).each(function (index, tr) {
                    var d = $(tr).offset().top;
                    if (d < distance_now && d > distance_last) {
                        $(tr).find('.woobe_product_check').prop('checked', to_check);
                    }
                });
            } else {
                //check/uncheck all below to woobe_last_checked_product.id
                $(rows).each(function (index, tr) {
                    var d = $(tr).offset().top;
                    if (d > distance_now && d < distance_last) {
                        $(tr).find('.woobe_product_check').prop('checked', to_check);
                    }
                });
            }
        }

        //***

        if ($(this).prop('checked')) {
            woobe_select_row(product_id);
            woobe_checked_products.push(product_id);
            woobe_last_checked_product.checked = true;
        } else {
            woobe_select_row(product_id, false);
            //woobe_checked_products.splice(woobe_checked_products.indexOf(product_id), 1);
            woobe_checked_products = $.grep(woobe_checked_products, function (value) {
                return value != product_id;
            });
            woobe_last_checked_product.checked = false;
        }

        //***

        //push all another checked ids
        if (e.shiftKey) {
            $(rows).each(function (index, tr) {
                var p_id = parseInt($(tr).data('product-id'), 10);
                if ($(tr).find('.woobe_product_check').prop('checked')) {
                    //console.log(p_id);
                    woobe_checked_products.push(p_id);
                    woobe_select_row(p_id);
                } else {
                    //console.log('---' + p_id);
                    //woobe_checked_products.splice(woobe_checked_products.indexOf(p_id), 1);
                    for (var i = 0; i < woobe_checked_products.length; i++) {
                        if (p_id === woobe_checked_products[i]) {
                            woobe_select_row(woobe_checked_products[i], false);
                            delete woobe_checked_products[i];
                        }
                    }
                }
            });

        }

        //***

        //remove duplicates if exists and filter values
        woobe_checked_products = Array.from(new Set(woobe_checked_products));
        woobe_checked_products = woobe_checked_products.filter(function (n) {
            return n != undefined;
        });
        //console.log(woobe_checked_products);

        //***
        woobe_last_checked_product.id = product_id;
        __woobe_action_will_be_applied_to();
        __manipulate_by_depend_buttons();
    });

    //***
    //check all products
    $('.all_products_checker').click(function () {
        if (woobe_show_variations > 0) {
            $('tr .woobe_product_check').trigger('click');
            if ($('tr .woobe_product_check:checked').length) {
                $(this).prop('checked', 'checked');
            }
        } else {
            //product_type_variation
            $('tr:not(.product_type_variation) .woobe_product_check').trigger('click');
            if ($('tr:not(.product_type_variation) .woobe_product_check:checked').length) {
                $(this).prop('checked', 'checked');
            }
        }
    });

    //uncheck all products
    $('.woobe_tools_panel_uncheck_all').click(function () {
        $('.woobe_product_check').prop('checked', false);
        $('.all_products_checker').prop('checked', false);
        woobe_checked_products = [];
        __manipulate_by_depend_buttons();
        __woobe_action_will_be_applied_to();

        return false;
    });

    //***

    $('.woobe_tools_panel_delete_btn').click(function () {

        if (confirm(lang.sure)) {
            var products_ids = [];
            $('.woobe_product_check').each(function (ii, ch) {
                if ($(ch).prop('checked')) {
                    products_ids.push($(ch).data('product-id'));
                }
            });

            if (products_ids.length) {
                woobe_message(lang.deleting, 'warning', 999999);
                __woobe_product_removing(products_ids, 0, 0);
            }
        }

        return false;
    });

    //***
    //another way chosen drop-downs width is 0
    setTimeout(function () {
        $('.woobe_top_panel').hide();
        //$('.woobe_top_panel').height(500);
        $('.woobe_top_panel').css('margin-top', '-' + $('.woobe_top_panel').height());
        //page loader fade
        $(".woobe-admin-preloader").fadeOut("slow");
    }, 1000);
    /*
     window.onresize = function (event) {
     $('.woobe_top_panel').hide();
     $('.woobe_top_panel').height(500);
     $('.woobe_top_panel').css('margin-top', 0);
     };
     */

    //Show/Hide button for filter
    $('.woobe_top_panel_btn').click(function () {
        var _this = this;
        $('.woobe_top_panel').slideToggle('slow', function () {
            if ($(this).is(':visible')) {
                $(_this).html(lang.close_panel);
                //$('#woobe_scroll_left').hide();
                //$('#woobe_scroll_right').hide();
            } else {
                /*
                 if ($('#advanced-table').width() > $('#tabs-products').width()) {
                 $('#woobe_scroll_left').show();
                 $('#woobe_scroll_right').show();
                 }
                 */

                $(_this).html(lang.show_panel);
            }
        });

        $(document).trigger("woobe_top_panel_clicked");

        return false;
    });


    $('.woobe_top_panel_btn2').click(function (e) {
        $('.woobe_top_panel_btn').trigger('click');
        return false;
    });

    //***

    $('#js_check_woobe_show_variations').on('check_changed', function () {

        woobe_show_variations = parseInt($(this).val(), 10);
        woobe_set_to_storage('woobe_show_variations', woobe_show_variations);

        if (woobe_show_variations > 0) {
            if ($('tr.product_type_variation').length > 0) {
                $('tr.product_type_variation').show();
            } else {
                data_table.draw('page');
            }
            $('.not-for-variations').hide();
            $('#woobe_show_variations_mode').show();

            //***

            $('#woobe_select_all_vars').show();

        } else {
            $('tr.product_type_variation').hide();
            $('.not-for-variations').show();
            woobe_init_js_intab('tabs-bulk');
            $('#woobe_show_variations_mode').hide();

            //***
            //uncheck all checked attributes to avoid confusing with any bulk operation!
            if ($('tr.product_type_variation.woobe_selected_row').length > 0) {
                $('tr.product_type_variation.woobe_selected_row .woobe_product_check').prop('checked', false);
                //$('.all_products_checker').prop('checked', false);

                $('tr.product_type_variation.woobe_selected_row').each(function (index, row) {
                    var product_id = parseInt($(row).data('product-id'));

                    //https://stackoverflow.com/questions/3596089/how-to-remove-specific-value-from-array-using-jquery
                    woobe_checked_products = $.grep(woobe_checked_products, function (value) {
                        return value != product_id;
                    });

                });

                __manipulate_by_depend_buttons();
                __woobe_action_will_be_applied_to();
            }

            //***

            $('#woobe_select_all_vars').hide();
            //__trigger_resize();
            woobe_init_js_intab('tabs-products');
        }

        //***

        $('#tabs-bulk .chosen-select').chosen('destroy');
        $('#tabs-bulk .chosen-select').chosen();

        //***

        return true;
    });

    if (woobe_show_variations > 0) {
        $("[data-numcheck='woobe_show_variations']").prop('checked', true);
        $('#js_check_woobe_show_variations').prop('value', 1);
    }

    //***


}

//service
function __woobe_product_new(count, created) {

    var step = 10;
    var to_create = (created + step) < count ? step : count - created;
    woobe_message(lang.creating + ' (' + (created + to_create) + ')', 'warning');
    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_create_new_product',
            to_create: to_create
        },
        success: function () {
            if ((created + step) < count) {
                created += step;
                __woobe_product_new(count, created);
            } else {
                //https://stackoverflow.com/questions/25929347/how-to-redraw-datatable-with-new-data
                //data_table.clear().draw();
                woobe_checked_products = [];
                __manipulate_by_depend_buttons();
                data_table.order([1, 'desc']).draw();
                //data_table.rows.add(NewlyCreatedData); // Add new data
                //data_table.columns.adjust().draw(); // Redraw the DataTable
                woobe_message(lang.created, 'notice');
            }
        },
        error: function () {
            alert(lang.error);
        }
    });

}

//service
var woobe_product_duplication_errors = 0;
function __woobe_product_duplication(products, start, duplicated) {

    var step = 2;
    var products_ids = products.slice(start, start + step);

    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_duplicate_products',
            products_ids: products_ids
        },
        success: function () {
            if ((start + step) > products.length) {
                //data_table.clear().draw();
                woobe_checked_products = [];
                __manipulate_by_depend_buttons();
                data_table.order([1, 'desc']).draw();
                woobe_message(lang.duplicated, 'notice', 99999);
            } else {
                duplicated += step;
                if (duplicated > products.length) {
                    duplicated = products.length;
                }
                woobe_message(lang.duplicating + ' (' + (products.length - duplicated) + ')', 'warning', 99999);
                __woobe_product_duplication(products, start + step, duplicated);
            }
        },
        error: function () {
            woobe_message(lang.error, 'error');
            woobe_product_duplication_errors++;
            if (woobe_product_duplication_errors > 5) {
                alert(lang.error);
                woobe_product_duplication_errors=0;
            } else {
                //lets try again
                __woobe_product_duplication(products, start, duplicated);
            }
        }
    });


}


//service
function __woobe_product_removing(products, start, deleted) {
    var step = 10;

    var products_ids_portion = products.slice(start, start + step);

    $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_delete_products',
            products_ids: products_ids_portion
        },
        success: function () {
            if ((start + step) > products.length) {
                //data_table.clear().draw();
                woobe_checked_products = $(woobe_checked_products).not(products).get();

                for (var i = 0; i < products.length; i++) {
                    if ($('#product_row_' + products[i]).hasClass('product_type_variable')) {
                        ($('#product_row_' + products[i]).nextAll('tr')).each(function (index, tr) {
                            if ($(tr).hasClass('product_type_variation')) {
                                $(tr).remove();
                            } else {
                                return false;
                            }
                        });
                    }

                    $('#product_row_' + products[i]).remove();
                }
                woobe_message(lang.deleted, 'notice');

                __manipulate_by_depend_buttons();
                __woobe_action_will_be_applied_to();
            } else {
                deleted += step;
                if (deleted > products.length) {
                    deleted = products.length;
                }
                woobe_message(lang.deleting + ' (' + (products.length - deleted) + ')', 'warning');
                __woobe_product_removing(products, start + step, deleted);
            }
        },
        error: function () {
            alert(lang.error);
        }
    });


}


//service
var __manipulate_by_depend_color_rows_lock = false;
function __manipulate_by_depend_buttons(show = true) {

    if (show) {
        show = $('.woobe_product_check:checked').length;
    }

    //***

    if (show) {
        $('.woobe_tools_panel_duplicate_btn').show();
        $('.woobe_tools_panel_delete_btn').show();
    } else {
        $('.woobe_tools_panel_duplicate_btn').hide();
        $('.woobe_tools_panel_delete_btn').hide();
    }

    //***

    if (woobe_checked_products.length) {
        $('.woobe_tools_panel_uncheck_all').show();

        if (!__manipulate_by_depend_color_rows_lock) {
            setTimeout(function () {

                for (var i = 0; i < woobe_checked_products.length; i++) {
                    woobe_select_row(woobe_checked_products[i]);
                }

                __manipulate_by_depend_color_rows_lock = false;
            }, 777);
            __manipulate_by_depend_color_rows_lock = true;
        }

    } else {
        $('.woobe_tools_panel_uncheck_all').hide();
        $('#advanced-table tr').removeClass('woobe_selected_row');
}
}

function woobe_select_row(product_id, select = true) {
    if (select) {
        $('#product_row_' + product_id).addClass('woobe_selected_row');
    } else {
        $('#product_row_' + product_id).removeClass('woobe_selected_row');
}
}

function woobe_init_tips(obj) {
    //https://www.jqueryscript.net/demo/Lightweight-Highly-Customizable-jQuery-Tooltip-Plugin-Zebra-Tooltips/examples/
    new $.Zebra_Tooltips(obj, {
        background_color: '#333',
        color: '#FFF'
    });
}


function woobe_init_switchery(only_data_table = true, product_id = 0) {

    var adv_tbl_id_string = '#advanced-table ';
    if (!only_data_table) {
        adv_tbl_id_string = '';//initialization switches for options too
    }

    //reinit only 1 row
    if (product_id > 0) {
        adv_tbl_id_string = adv_tbl_id_string + '#product_row_' + product_id + ' ';
    }

    //***

    //http://abpetkov.github.io/switchery/
    if (typeof Switchery !== 'undefined') {
        var elems = Array.prototype.slice.call(document.querySelectorAll(adv_tbl_id_string + '.js-switch'));
        elems.forEach(function (ch) {
            new Switchery(ch);
            //while reinit draws duplicates of switchers
            $(ch).parent().find('span.switchery:not(:first)').remove();
        });
    }

    //***

    if ($(adv_tbl_id_string + '.js-check-change').length > 0) {

        $.each($(adv_tbl_id_string + '.js-check-change'), function (index, item) {

            $(item).unbind('change');
            $(item).change(function () {
                var state = item.checked.toString();
                var numcheck = $(item).data('numcheck');
                var trigger_target = $(item).data('trigger-target');
                var label = $("*[data-label-numcheck='" + numcheck + "']");
                var hidden = $("*[data-hidden-numcheck='" + numcheck + "']");
                label.html($(item).data(state));
                $(label).removeClass($(item).data('class-' + (!(item.checked)).toString()));
                $(label).addClass($(item).data('class-' + state));
                var val = $(item).data('val-' + state);
                var field_name = $(hidden).attr('name');
                $(hidden).val(val);

                if (trigger_target.length) {
                    $(this).trigger("check_changed", [trigger_target, field_name, item.checked, val, numcheck]);
                    $('#' + trigger_target).trigger("check_changed");//for any single switchers
                }
            });

        });

        //***
        $("#advanced-table .js-check-change").unbind('check_changed');
        $("#advanced-table .js-check-change").on("check_changed", function (event, trigger_target, field_name, is_checked, val, product_id) {
            woobe_message(lang.saving, '');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_update_page_field',
                    product_id: product_id,
                    field: field_name,
                    value: val
                },
                success: function () {
                    $(document).trigger('woobe_page_field_updated', [parseInt(product_id, 10), field_name, val]);
                    woobe_message(lang.saved, 'notice');
                }
            });
        });

}
}

/**************************************************************************/


function woobe_set_progress(id, width) {
    if ($('#' + id).length > 0) {
        $('#' + id).parents('.woobe_progress').show();
        //document.getElementById(id).parentElement.style.display = 'block';
        document.getElementById(id).style.width = width + '%';
        document.getElementById(id).innerHTML = width.toFixed(2) + '%';
    }
}

function woobe_hide_progress(id) {
    if ($('#' + id).length > 0) {
        woobe_set_progress(id, 0);
        $('#' + id).parents('.woobe_progress').hide();
    }
}

//attach event for any manipulations with content of the tabs by their id
function woobe_init_js_intab(tab_id) {
    $(document).trigger("do_" + tab_id);
    $(document).trigger("tab_switched", [tab_id]);
    return true;
}


function woobe_get_from_storage(key) {
    if (typeof (Storage) !== "undefined") {
        return localStorage.getItem(key);
    }

    return 0;
}

function woobe_set_to_storage(key, value) {
    if (typeof (Storage) !== "undefined") {
        localStorage.setItem(key, value);
        return key;
    }

    return 0;
}

function woobe_save_form(form, action) {
    woobe_message(lang.saving, 'warning');
    $('[type=submit]').replaceWith('<img src="' + spinner + '" width="60" alt="" />');
    var data = {
        action: action,
        formdata: $(form).serialize()
    };
    $.post(ajaxurl, data, function () {
        window.location.reload();
    });
}


//give info about to which products will be applied bulk edition
function __woobe_action_will_be_applied_to() {
    //woobe_action_will_be_applied_to
    if (woobe_checked_products.length) {
        //high priority
        $('.woobe_action_will_be_applied_to').html(lang.action_state_31 + ': ' + woobe_checked_products.length + '. ' + lang.action_state_32);
    } else {
        if (woobe_filtering_is_going) {
            //if there is filtering going
            $('.woobe_action_will_be_applied_to').html(lang.action_state_2);
        } else {
            //no filtering and no checked products
            $('.woobe_action_will_be_applied_to').html(lang.action_state_1);
        }
    }
}

function woobe_get_random_string(len = 16) {
    charSet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';
    for (var i = 0; i < len; i++) {
        var randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz, randomPoz + 1);
    }
    return randomString;
}


function __woobe_fill_select(select_id, data, selected = [], level = 0, val_as_slug = false) {

    var margin_string = '';
    if (level > 0) {
        for (var i = 0; i < level; i++) {
            margin_string += '&nbsp;&nbsp;&nbsp;';
        }
    }

    //***

    $(data).each(function (i, d) {
        var sel = '';
        var val = d.term_id;
        if (val_as_slug) {
            val = d.slug;
        }

        //***

        if ($.inArray(val, selected) > -1) {
            sel = 'selected';
        }
        $('#' + select_id).append('<option ' + sel + ' value="' + val + '">' + margin_string + d.name + '</option>');
        if (d.childs) {
            __woobe_fill_select(select_id, d.childs, selected, level + 1, val_as_slug);
        }
    });
}


function woobe_init_profiles() {
    $('#woobe_load_profile').change(function () {

        var profile_key = $(this).val();
        if (profile_key != 0) {
            $('#woobe_load_profile_actions').show();
        } else {
            $('#woobe_load_profile_actions').hide();
        }

    });

    //***

    $('#woobe_load_profile_btn').click(function () {

        var profile_key = $('#woobe_load_profile').val();

        $('.woobe-modal-close8').trigger('click');

        if (profile_key != 0) {
            woobe_message(lang.loading, 'warning');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_load_profile',
                    profile_key: profile_key
                },
                success: function (answer) {
                    woobe_message(lang.loading, 'warning');
                    window.location.reload();
                }
            });
        }

    });

    //***

    $('#woobe_new_profile_btn').click(function () {
        var profile_title = $('#woobe_new_profile').val();
        if (profile_title.length) {
            woobe_message(lang.creating, 'warning');
            //$('.woobe-modal-close8').trigger('click');
            $('#woobe_new_profile').val('');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_create_profile',
                    profile_title: profile_title
                },
                success: function (key) {
                    if (parseInt(key, 10) !== -2) {
                        $('#woobe_load_profile').append('<option selected value="' + key + '">' + profile_title + '</option>');
                        woobe_message(lang.saved, 'notice');
                    } else {
                        alert(lang.free_ver_profiles);
                        woobe_message('', 'clean');
                    }
                }
            });
        } else {
            woobe_message(lang.fill_up_data, 'warning');
        }
    });

    $('#woobe_new_profile').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#woobe_new_profile_btn').trigger('click');
        }
    });

    //***

    $('.woobe_delete_profile').click(function () {

        var profile_key = $(this).attr('href');
        if (profile_key === '#') {
            profile_key = $('#woobe_load_profile').val();
        }

        if ($.inArray(profile_key, woobe_non_deletable_profiles) > 1) {
            woobe_message(lang.no_deletable, 'warning');
            return false;
        }

        //***

        if (confirm(lang.sure)) {
            woobe_message(lang.saving, 'warning');
            //$('.woobe-modal-close8').trigger('click');
            var select = document.getElementById('woobe_load_profile');
            select.removeChild(select.querySelector('option[value="' + profile_key + '"]'));
            $('.current_profile_disclaimer').remove();
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_delete_profile',
                    profile_key: profile_key
                },
                success: function (key) {
                    woobe_message(lang.saved, 'notice');
                }
            });
        }
        return false;
    });

}

function woobe_disable_bind_editing() {
    if (woobe_bind_editing) {
        $("[data-numcheck='woobe_bind_editing']").trigger('click');
        woobe_bind_editing = 0;
    }
}

//service
function __trigger_resize() {

    //console.log('here');

    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 10);

    //$(window).trigger('resize');

    /*
     * for tests
     $('.woobe_tools_panel_full_width_btn').trigger('click', function () {
     $('.woobe_tools_panel_full_width_btn').trigger('click');
     });
     */

}



