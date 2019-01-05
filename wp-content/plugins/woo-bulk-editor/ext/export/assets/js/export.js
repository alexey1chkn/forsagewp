var woobe_export_current_xhr = null;//current ajax request (for cancel)
var woobe_export_user_cancel = false;

function woobe_export_to_csv() {
    woobe_export('csv');
}

function woobe_export_to_excel() {
    woobe_export('excel');//todo
}

function woobe_export(format) {

    var export_txt = lang.export.want_to_export + '\n';

    //***
    /*
     if (!confirm(export_txt)) {
     return false;
     }
     */
    //***

    $('.woobe_export_products_btn').hide();
    $('.woobe_export_products_btn_down').hide();
    $('.woobe_export_products_btn_cancel').show();
    woobe_export_is_going();

    //***

    $('.woobe_progress_export').show();
    woobe_message(lang.export.exporting, 'warning', 999999);

    if (woobe_checked_products.length > 0) {
        woobe_export_current_xhr = $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_export_products_count',
                format: format,
                no_filter: 1,
                download_files_count: parseInt($('#woobe_export_download_files_count').val(), 10),
                csv_delimiter: $('#woobe_export_delimiter').val()
            },
            success: function () {
                woobe_set_progress('woobe_export_progress', 0);
                __woobe_export_products(format, woobe_checked_products, 0);
            },
            error: function () {
                alert(lang.error);
                woobe_export_is_going(false);
            }
        });
    } else {
        woobe_export_current_xhr = $.ajax({
            method: "POST",
            url: ajaxurl,
            data: {
                action: 'woobe_export_products_count',
                format: format,
                filter_current_key: woobe_filter_current_key,
                csv_delimiter: $('#woobe_export_delimiter').val()
            },
            success: function (products_ids) {
                products_ids = JSON.parse(products_ids);

                if (products_ids.length) {
                    woobe_set_progress('woobe_export_progress', 0);
                    __woobe_export_products(format, products_ids, 0);
                } else {
                    woobe_export_is_going(false);
                }

            },
            error: function () {
                if (!woobe_export_user_cancel) {
                    alert(lang.error);
                    woobe_export_to_csv_cancel();
                }
                woobe_export_is_going(false);
            }
        });
    }


    return false;
}

//service
function __woobe_export_products(format, products, start) {
    var step = 10;
    var products_ids = products.slice(start, start + step);

    woobe_export_current_xhr = $.ajax({
        method: "POST",
        url: ajaxurl,
        data: {
            action: 'woobe_export_products',
            products_ids: products_ids,
            format: format,
            download_files_count: parseInt($('#woobe_export_download_files_count').val(), 10),
            csv_delimiter: $('#woobe_export_delimiter').val()
        },
        success: function () {
            if ((start + step) > products.length) {
                woobe_message(lang.export.exported, 'notice');
                $('.woobe_export_products_btn').show();
                $('.woobe_export_products_btn_down').show();
                $('.woobe_export_products_btn_cancel').hide();
                woobe_set_progress('woobe_export_progress', 100);
                woobe_export_is_going(false);
            } else {
                //show %
                woobe_set_progress('woobe_export_progress', (start + step) * 100 / products.length);
                __woobe_export_products(format, products, start + step);
            }
        },
        error: function () {
            if (!woobe_export_user_cancel) {
                alert(lang.error);
                woobe_export_to_csv_cancel();
            }
            woobe_export_is_going(false);
        }
    });
}

function woobe_export_to_csv_cancel() {
    woobe_export_user_cancel = true;
    woobe_export_current_xhr.abort();
    woobe_hide_progress('woobe_export_progress');
    $('.woobe_export_products_btn').show();
    $('.woobe_export_products_btn_down').hide();
    $('.woobe_export_products_btn_cancel').hide();
    woobe_message(lang.canceled, 'error');
    woobe_export_user_cancel = false;
    woobe_export_is_going(false);
}

function woobe_export_is_going(go = true) {
    if (go) {
        $('#wp-admin-bar-root-default').append("<li id='woobe_export_is_going'>" + lang.export.export_is_going + "</li>");

    } else {
        $('#woobe_export_is_going').remove();
}

}
