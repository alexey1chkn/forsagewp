var woobe_filter_profile_data = null;
jQuery(function ($) {

    $('.woobe_tools_panel_fprofile_btn').click(function () {
        if (woobe_filter_current_key) {
            $('.woobe-new-fprofile-inputs').show();
            $('.woobe-new-fprofile-attention').hide();
            $('#woobe_new_fprofile_btn').show();
        } else {
            $('.woobe-new-fprofile-inputs').hide();
            $('.woobe-new-fprofile-attention').show();
            $('#woobe_new_fprofile_btn').hide();
        }

        //***
        //hide input for new profile if loaded one of the profiles
        $("#woobe_load_fprofile option").each(function (i, o)
        {
            if ($(o).val() != 0 && $(o).val() == woobe_filter_current_key) {
                $('.woobe-new-fprofile-inputs').hide();
                $('.woobe-new-fprofile-attention').show();
                $('#woobe_new_fprofile_btn').hide();
                return false;
            }
        });

        //***

        $('#woobe_fprofile_popup').show();
        $('#woobe_new_fprofile').focus();
        return false;
    });
    $('.woobe-modal-close-fprofile').click(function () {
        $('#woobe_fprofile_popup').hide();
    });
    //***

    $('#woobe_load_fprofile').change(function () {

        var profile_key = $(this).val();
        if (profile_key != 0) {
            $('#woobe_load_fprofile_actions').show();
        } else {
            $('#woobe_load_fprofile_actions').hide();
        }

        //***

        if (profile_key != 0) {

            $('#woobe_load_fprofile_actions').hide();
            $('#woobe_loaded_fprofile_data_info').html(lang.loading);
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_get_filter_profile_data',
                    profile_key: profile_key
                },
                success: function (answer) {
                    answer = JSON.parse(answer);
                    $('#woobe_loaded_fprofile_data_info').html(answer.html);
                    woobe_filter_profile_data = answer.res;
                    $('#woobe_load_fprofile_actions').show();
                }
            });
        }

    });
    //***

    $('#woobe_load_fprofile_btn').click(function () {

        var profile_key = $('#woobe_load_fprofile').val();
        $('.woobe-modal-close-fprofile').trigger('click');
        if (profile_key != 0) {
            woobe_message(lang.loading, 'warning');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_load_filter_profile',
                    profile_key: profile_key
                },
                success: function (answer) {
                    answer = parseInt(answer, 10);
                    if (answer === 1) {
                        woobe_filter_current_key = profile_key;
                        woobe_message(lang.filters.filtered, 'notice', 30000);
                        data_table.clear().draw();
                        $('.woobe_tools_panel_newprod_btn').hide();
                        $('.woobe_filter_reset_btn1').show();
                        $('.woobe_filter_reset_btn2').show();
                        //clear all filter drop-downs and inputs
                        __woobe_clean_filter_form();

                        woobe_filtering_is_going = true;
                        __woobe_action_will_be_applied_to();
                        //lets fill filter form by data from the loaded profile
                        if (Object.keys(woobe_filter_profile_data).length !== 0) {
                            //console.log(woobe_filter_profile_data);

                            Object.keys(woobe_filter_profile_data).forEach(function (key, index) {

                                if (key == 'taxonomies_terms_titles') {
                                    return true;//we not need it here at all
                                }

                                if (key == 'taxonomies_operators') {
                                    if (Object.keys(woobe_filter_profile_data[key]).length !== 0) {
                                        Object.keys(woobe_filter_profile_data[key]).forEach(function (k, i) {
                                            $('[name="woobe_filter[taxonomies_operators][' + k + ']"]').val(woobe_filter_profile_data[key][k]);
                                        });
                                    }

                                    return true;
                                }


                                if (key == 'taxonomies') {

                                    if (Object.keys(woobe_filter_profile_data[key]).length !== 0) {
                                        Object.keys(woobe_filter_profile_data[key]).forEach(function (k, i) {
                                            $('[name="woobe_filter[taxonomies][' + k + '][]"]').val(woobe_filter_profile_data[key][k]);
                                        });

                                        $('#woobe_filter_form select.chosen-select').trigger("chosen:updated");
                                    }

                                    return true;
                                }

                                //console.log(woobe_filter_profile_data[key]);
                                if (typeof woobe_filter_profile_data[key] == 'object') {
                                    if ("value" in woobe_filter_profile_data[key]) {
                                        $('[name="woobe_filter[' + key + '][value]"]').prev('label').css('margin-top', -11 + 'px');//fix fo jquery.placeholder.label.min
                                        $('[name="woobe_filter[' + key + '][value]"]').val(woobe_filter_profile_data[key]['value']);
                                        $('[name="woobe_filter[' + key + '][behavior]"]').val(woobe_filter_profile_data[key]['behavior']);
                                    }

                                    if ("from" in woobe_filter_profile_data[key]) {
                                        $('[name="woobe_filter[' + key + '][from]"]').prev('label').css('margin-top', -11 + 'px');//fix fo jquery.placeholder.label.min
                                        $('[name="woobe_filter[' + key + '][from]"]').val(woobe_filter_profile_data[key]['from']);
                                    }

                                    if ("to" in woobe_filter_profile_data[key]) {
                                        $('[name="woobe_filter[' + key + '][to]"]').prev('label').css('margin-top', -11 + 'px');//fix fo jquery.placeholder.label.min
                                        $('[name="woobe_filter[' + key + '][to]"]').val(woobe_filter_profile_data[key]['to']);
                                    }
                                } else {
                                    $('[name="woobe_filter[' + key + ']"]').val(woobe_filter_profile_data[key]);
                                    $('[name="woobe_filter[' + key + ']"]').addClass('woobe_set_attention');
                                }

                                //console.log(key);
                                //console.log(answer[key]);
                            });

                            //***
                            $('#woobe_filter_form .woobe_calendar').trigger('change');
                        }
                    } else {
                        alert(lang.error);
                    }
                }
            });
        }

    });
    //***

    $('#woobe_new_fprofile_btn').click(function () {
        var profile_title = $('#woobe_new_fprofile').val();
        if (profile_title.length) {
            woobe_message(lang.saving, 'warning');
            $('#woobe_new_fprofile').val('');
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_create_filter_profile',
                    profile_title: profile_title,
                    filter_current_key: woobe_filter_current_key
                },
                success: function (key) {
                    if (parseInt(key, 10) !== -2) {
                        $('#woobe_load_fprofile').append('<option selected value="' + key + '">' + profile_title + '</option>');
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
    $('#woobe_new_fprofile').keydown(function (e) {
        if (e.keyCode == 13) {
            $('#woobe_new_fprofile_btn').trigger('click');
        }
    });
    //***

    $('.woobe_delete_fprofile').click(function () {

        var profile_key = $(this).attr('href');
        if (profile_key === '#') {
            profile_key = $('#woobe_load_fprofile').val();
        }

        if (profile_key == 'default') {
            woobe_message(lang.no_deletable, 'warning');
            return false;
        }

        //***

        if (confirm(lang.sure)) {
            woobe_message(lang.saving, 'warning');
            var select = document.getElementById('woobe_load_fprofile');
            select.removeChild(select.querySelector('option[value="' + profile_key + '"]'));
            $.ajax({
                method: "POST",
                url: ajaxurl,
                data: {
                    action: 'woobe_delete_filter_profile',
                    profile_key: profile_key
                },
                success: function () {
                    woobe_message(lang.deleted, 'notice');
                }
            });
        }
        return false;
    });
});

