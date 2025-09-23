'use strict';

// require('./bootstrap');

let xheader = $('meta[name="csrf-token"]').attr('content');

const pull_slots = (date, time) => {
    const loader = $('#appoint-spiner');

    $.ajax({
        'url': 'slots',
        'type': 'post',
        'data': {
            'adate': date,
            'atime': time
        },
        'headers': {
            'X-CSRF-TOKEN': xheader,
        },
        'beforeSend': function () {
            $('.form-control').removeClass('is-invalid');
            loader.removeClass('d-none');

        },
        'complete': () => {
            loader.addClass('d-none');

        },
        'success': (data) => {
            $('#timeslot-avail').text(data.message);

        },
        'error': (err) => {
            var err_msg,
                err_json = err.responseJSON;

            switch (err.status) {
                case 422:
                    let err_data = err_json.errors;

                    err_msg = err_json.message;

                    // console.log(err_data);

                    for (var _key in err_data) {

                        $('#' + _key).addClass('is-invalid').next().text(err_data[_key][0]);

                    }

                    let first_err_arr = Object.keys(err_data)[0];

                    if (first_err_arr.includes('.')) {
                        let err_split = first_err_arr.split('.');

                        $('.' + err_split[0]).eq(err_split[1]).focus();

                    } else {
                        $('#' + Object.keys(err_data)[0]).focus();

                    }

                    break;

                case 404:
                    err_msg = err.statusText;
                    break;

                case 500:
                    err_msg = "Oops! There's something wrong.";
                    break;
            }

            // $(a_box).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
        }

    });

}

const pullWalkInAvaiableSlots = (departmentid, transaction_type_id, bin, adate, atime) => {
    const loader = $('#appoint-spiner');

    $.ajax({
        'url': '30c234cc35caba164c8dbd3837a0c55a/pull-slots',
        'type': 'post',
        'data': {
            'departmentid': departmentid,
            'transaction_type_id': transaction_type_id,
            'bin': bin,
            'adate': adate,
            'atime': atime
        },
        'headers': {
            'X-CSRF-TOKEN': xheader,
        },
        'beforeSend': function () {
            $('.form-control').removeClass('is-invalid');
            loader.removeClass('d-none');

        },
        'complete': () => {
            loader.addClass('d-none');

        },
        'success': (data) => {
            $('#timeslot-avail').text(data.message);

        },
        'error': (err) => {
            var err_msg,
                err_json = err.responseJSON;

            switch (err.status) {
                case 422:
                    let err_data = err_json.errors;
                    err_msg = err_json.message;

                    for (var _key in err_data) {
                        $('#' + _key).addClass('is-invalid').next().text(err_data[_key][0]);
                    }

                    let first_err_arr = Object.keys(err_data)[0];
                    if (first_err_arr.includes('.')) {
                        let err_split = first_err_arr.split('.');

                        $('.' + err_split[0]).eq(err_split[1]).focus();
                    } else {
                        $('#' + Object.keys(err_data)[0]).focus();
                    }
                    break;

                case 404:
                    err_msg = err.statusText;
                    break;

                case 500:
                    err_msg = "Oops! There's something wrong.";
                    break;
            }

            // $(a_box).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
        }
    });
}


$(document).ready(function (e) {

    $('body').on('change', '#atime', function (e) {
        let date_val = $('#adate').val(),
            time_val = $(this).val();

        pull_slots(date_val, time_val);

    });

    $('body').on('input', '#adate', function (e) {
        let date_val = $(this).val(),
            time_val = $('#atime').val();

        pull_slots(date_val, time_val);

    });

    $('body').on('submit', '#master-form', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let form = $(this);

        const dept_val = $('#departmentid').val(),
            transact_val = $('#transaction_type_id').val();

        form.removeClass('was-validated');
        $('.form-control').removeClass('is-invalid');

        // if (dept_val > 0) {

        //     if (transact_val != 8 || transact_val != 16) { // NEW
        //         let bin_max_length = 10,
        //             bin_length = $('#bin').val().trim().length;

        //         if (bin_length > bin_max_length) {
        //             $('#bin').addClass('is-invalid').parent().find('.invalid-feedback').text('BIN should be ten(10) characters.');
        //             return false;
        //         }

        //     }

        // }


        if (form[0].checkValidity() == false) {
            form.addClass('was-validated');

        } else {
            let a_box = $('#alert-box-form'),
                sub_btn = $('#btn-submit-appointment'),
                loader = $('#submit-spiner');

            $.ajax({
                'url': 'save',
                'type': 'post',
                'data': form.serialize(),
                'headers': {
                    'X-CSRF-TOKEN': xheader,
                },
                'beforeSend': function () {
                    $('.form-control').removeClass('is-invalid');
                    a_box.addClass('d-none');
                    loader.removeClass('d-none');
                    sub_btn.attr('disabled', true);

                },
                'complete': () => {
                    loader.addClass('d-none');
                    sub_btn.attr('disabled', false);

                },
                'success': (data) => {
                    const {
                        message,
                        slot_message
                    } = data;

                    if (data.error) {
                        a_box.removeClass('alert-success d-none').addClass('alert-danger').html(message);
                        $('#timeslot-avail').text(slot_message);
                        $(window).scrollTop(0);
                    } else {
                        a_box.removeClass('alert-danger d-none').addClass('alert-success').html(message);
                        $('#timeslot-avail').text(slot_message);

                        $('.aptrefno').text(data.ref_no);
                        $('.aptadate').text(data.adate);
                        $('.aptatime').text(data.atime.split('-')[0].trim());
                       // $('.aptatime').text(data.atime);
                        $('.apttrans').text(data.trans);
                        //$('.aptbusname').text(data.bus_name);
                        //$('.aptbin').text(data.bin);
                        $('.apteid').text(data.eid);
                        $("#modal-qr-img").attr('src', data.qr_url);
                        
                        $('#as-modal').modal('show');
						$('#master-form')[0].reset();
                    }
                },
                'error': (err) => {
                    var err_msg,
                        err_json = err.responseJSON;

                    switch (err.status) {
                        case 422:
                            let err_data = err_json.errors;

                            err_msg = err_json.message;

                            // console.log(err_data);

                            for (var _key in err_data) {

                                $('#' + _key).addClass('is-invalid').parent().find('.invalid-feedback').text(err_data[_key][0]);

                            }

                            let first_err_arr = Object.keys(err_data)[0];

                            if (first_err_arr.includes('.')) {
                                let err_split = first_err_arr.split('.');

                                $('.' + err_split[0]).eq(err_split[1]).focus();

                            } else {
                                $('#' + Object.keys(err_data)[0]).focus();

                            }

                            break;

                        case 404:
                            err_msg = err.statusText;
                            break;

                        case 500:
                            err_msg = "Oops! There's something wrong.";
                            break;
                    }

                    $(a_box).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
                    $(window).scrollTop(0);
                }

            });

        }

    });


    let ref_val = 0;

    $('body').on('click', '#cancel-find-info', function (e) {
        let ref_inp = $('#reference_code');

        ref_val = ref_inp.val().trim();

        ref_inp.parent().removeClass('is-invalid');

        if (ref_val == '') {
            ref_inp.addClass('is-invalid');

        } else {
            let a_box = $('#info-box'),
                a_box2 = $('#alert-box-form'),
                loader = $('#search-spiner'),
                cancel_btn = $('#submit-cancel');

            $.ajax({
                'url': 'search-info',
                'type': 'post',
                'data': {'reference_code': ref_val},
                'headers': {
                    'X-CSRF-TOKEN': xheader,
                },
                'beforeSend': function () {
                    ref_inp.removeClass('is-invalid');
                    loader.removeClass('d-none');
                    a_box.addClass('d-none');
                    a_box2.addClass('d-none');

                },
                'complete': () => {
                    loader.addClass('d-none');

                },
                'success': (data) => {
                    const {
                        message,
                        fullname
                    } = data;

                    if (data.error) {
                        a_box.removeClass('alert-primary d-none').addClass('alert-danger').find('h4').text('Sorry').end().find('p').html(message);
                        cancel_btn.attr('disabled', true);

                    } else {
                        a_box.removeClass('alert-danger d-none').addClass('alert-primary').find('h4').text('Hi ' + fullname + ',').end().find('p').html(message);
                        cancel_btn.attr('disabled', false);

                    }
                },
                'error': (err) => {
                    var err_msg,
                        err_json = err.responseJSON;

                    switch (err.status) {
                        case 422:
                            let err_data = err_json.errors;

                            err_msg = err_json.message;

                            // console.log(err_data);

                            for (var _key in err_data) {

                                $('#' + _key).parent().addClass('is-invalid').next().text(err_data[_key][0]);

                            }

                            break;

                        case 404:
                            err_msg = err.statusText;
                            break;

                        case 500:
                            err_msg = "Oops! There's something wrong.";
                            break;
                    }

                    $(a_box2).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
                }

            });

        }

    });

    $('body').on('click', '#submit-cancel', function (e) {
        let a_box = $('#alert-box-form'),
            loader = $('#submit-cancel-spiner'),
            $_this = $(this);

            $.ajax({
                'url': 'cancel',
                'type': 'post',
                'data': {'reference_code': ref_val},
                'headers': {
                    'X-CSRF-TOKEN': xheader,
                },
                'beforeSend': function () {
                    loader.removeClass('d-none');

                },
                'complete': () => {
                    loader.addClass('d-none');

                },
                'success': (data) => {

                    if (data.error) {
                        a_box.removeClass('alert-success d-none').addClass('alert-danger').text(data.message);

                    } else {
                        a_box.removeClass('alert-danger d-none').addClass('alert-success').text(data.message);

                    }

                    $_this.attr('disabled', true);
                },
                'error': (err) => {
                    var err_msg,
                        err_json = err.responseJSON;

                    switch (err.status) {
                        case 422:
                            let err_data = err_json.errors;

                            err_msg = err_json.message;

                            // console.log(err_data);

                            for (var _key in err_data) {

                                $('#' + _key).addClass('is-invalid').next().text(err_data[_key][0]);

                            }

                            break;

                        case 404:
                            err_msg = err.statusText;
                            break;

                        case 500:
                            err_msg = "Oops! There's something wrong.";
                            break;
                    }

                    $(a_box).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
                }

            });


    });


    // health dec submit form
    $('body').on('submit', '#health-mainfrm', function (e) {
        e.preventDefault();
        e.stopPropagation();

        let form = $(this);

        form.removeClass('was-validated');
        $('.form-control').removeClass('is-invalid');
        $('.custom-feedback').remove();

        $('#tbl-yes-no tr').each(function(i, v) {
            const chk = $(this).find('input:radio');

            if (chk.is(':checked') == false) {
                $(this).find('td:first-child').append('<div class="invalid-feedback d-block custom-feedback">Please choose whether OO/HINDI.</div>');
            }
        });

        if (form[0].checkValidity() == false) {
            form.addClass('was-validated');

            $('.form-control:invalid').eq(0).focus();

        } else {
            let a_box = $('#alert-box-form'),
                sub_btn = $('#health-submit-btn'),
                loader = $('#submit-spiner');

            $.ajax({
                'url': 'health/save',
                'type': 'post',
                'data': form.serialize(),
                'headers': {
                    'X-CSRF-TOKEN': xheader,
                },
                'beforeSend': function () {
                    $('.form-control').removeClass('is-invalid');
                    a_box.addClass('d-none');
                    loader.removeClass('d-none');
                    sub_btn.attr('disabled', true);

                },
                'complete': () => {
                    loader.addClass('d-none');
                    sub_btn.attr('disabled', false);

                },
                'success': (data) => {
                    const {
                        message,
                    } = data;

                    if (data.error) {
                        a_box.removeClass('alert-success d-none').addClass('alert-danger').html(message);

                    } else {
                        a_box.removeClass('alert-danger d-none').addClass('alert-success').html(message);

                    }

                    $(window).scrollTop(0);

                },
                'error': (err) => {
                    var err_msg,
                        err_json = err.responseJSON;

                    switch (err.status) {
                        case 422:
                            let err_data = err_json.errors;

                            err_msg = err_json.message;

                            // console.log(err_data);

                            for (var _key in err_data) {

                                $('#' + _key).addClass('is-invalid').parent().find('.invalid-feedback').text(err_data[_key][0]);

                            }

                            let first_err_arr = Object.keys(err_data)[0];

                            if (first_err_arr.includes('.')) {
                                let err_split = first_err_arr.split('.');

                                $('.' + err_split[0]).eq(err_split[1]).focus();

                            } else {
                                $('#' + Object.keys(err_data)[0]).focus();

                            }

                            break;

                        case 404:
                            err_msg = err.statusText;
                            break;

                        case 500:
                            err_msg = "Oops! There's something wrong.";
                            break;
                    }

                    $(a_box).removeClass('alert-success d-none').addClass('alert-danger').text(err_msg);
                }

            });

        }

    });

    $('body').on('click', '.linked-text', function(e) {
        let _id = $(this).attr('name'),
            enable_txt = $(this).data('allow-txt'),
            inpt = $('#' + _id + 'ans');

        inpt.prop('disabled', !enable_txt).focus();
    });

    // health End



    $('body').on('change', '#departmentid', function () {
        let departmentId = $(this).val();

        const transaction_elem = $('#transaction_type_id'),
            tran_spiner = $('#transaction_spinner'),
            bplo_only_inp = $('#bin, #business_name');

        bplo_only_inp.toggleClass('d-none', (departmentId != 2 && departmentId != 23));

        // show alert if any
        $('.department-alert-active').addClass('d-none').removeClass('.department-alert-active');
        $('.department-alert[data-alert-id='+ departmentId +']').addClass('.department-alert-active').removeClass('d-none');
        // show alert if any end

        $.ajax({
            url: 'dropdowns/transaction-types?department_id=' + departmentId,
            beforeSend: function() {
                transaction_elem.prop('disabled', true);
                tran_spiner.removeClass('d-none');
            },
            complete: function() {
                transaction_elem.prop('disabled', false);
                tran_spiner.addClass('d-none');

            },
            success:function (response) {
                var len = response.results.length;
                //$("#transaction_type_id").empty();
                //$("#transaction_type_id").append("<option value='-1' selected disabled>-- PLEASE SELECT LOCATION --</option>");

                for (var i = 0; i < len; i++) {
                    var id = response.results[i]['id'];
                    var name = response.results[i]['text'];

                    //$("#transaction_type_id").append("<option value='"+id+"'>"+name+"</option>");
                }

            }
        });
    });


    // Biz Name Finder



    // $('body').on('click', '#bin-biz-name', function(e) {
    //     const btn_submit = $('#btn-submit-appointment'),
    //         binv = $('#bin').val(),
    //         bin_find_spiner = $('#biz_info_find');

    //     $('.form-control').removeClass('is-invalid');

    //     // let bin_max_length = 10,
    //     //     bin_length = $('#bin').val().trim().length;

    //     // if (bin_length > bin_max_length) {
    //     //     $('#bin').addClass('is-invalid').parent().find('.invalid-feedback').text('BIN should be ten(10) characters.');
    //     //     return false;
    //     // }

    //     $.ajax({
    //         url: 'biz_search',
    //         data: {'bin': binv},
    //         type: 'post',
    //         headers: {
    //             'X-CSRF-TOKEN': xheader,
    //         },
    //         beforeSend: function() {
    //             btn_submit.prop('disabled', true);
    //             bin_find_spiner.removeClass('d-none');
    //         },
    //         complete: function() {
    //             btn_submit.prop('disabled', false);
    //             bin_find_spiner.addClass('d-none');

    //         },
    //         success:function (response) {
    //             const {biz_name, message, error} = response;

    //             if (error) {
    //                 $('#business_name').attr('placeholder', 'BUSINESS NAME NOT FOUND');

    //             } else {
    //                 $('#business_name').val(biz_name);

    //             }

    //         }
    //     });

    //     if ($('#walk_in_atime').val() != undefined) {
    //         // Walk-In Pull Slots
    //         let walk_in_adate = $('#walk_in_adate').val(),
    //             walk_in_atime = $('#walk_in_atime').val(),
    //             walk_in_departmentid = $('#departmentid').val(),
    //             walk_in_transaction_type_id = $('#transaction_type_id').val(),
    //             walk_in_bin = $('#bin').val();

    //         pullWalkInAvaiableSlots(walk_in_departmentid, walk_in_transaction_type_id, walk_in_bin, walk_in_adate, walk_in_atime);
    //     }
    // });
    // Biz Name Finder End

    // Load old input for transaction type
    let oldTransactionType = $('#transaction_type_id').data('old');
    if (oldTransactionType) {
        $("#departmentid").change(function(e, callback) {
            setTimeout(function() {
                if (typeof callback === "function")
                    callback();
            }, 800);
        });

        $('#departmentid').trigger('change', function() {
            $('#transaction_type_id').val(oldTransactionType);
            $('#transaction_type_id').trigger('change');
        });
    }


    // Walk-In Pull Slots
    $('body').on('change', '#walk_in_atime', function (e) {
        let walk_in_adate = $('#walk_in_adate').val(),
            walk_in_atime = $(this).val(),
            walk_in_departmentid = $('#departmentid').val(),
            walk_in_transaction_type_id = $('#transaction_type_id').val(),
            walk_in_bin = $('#bin').val();

        pullWalkInAvaiableSlots(walk_in_departmentid, walk_in_transaction_type_id, walk_in_bin, walk_in_adate, walk_in_atime);
    });

    $('body').on('input', '#walk_in_adate', function (e) {
        let walk_in_adate = $(this).val(),
            walk_in_atime = $('#walk_in_atime').val(),
            walk_in_departmentid = $('#departmentid').val(),
            walk_in_transaction_type_id = $('#transaction_type_id').val(),
            walk_in_bin = $('#bin').val();

        pullWalkInAvaiableSlots(walk_in_departmentid, walk_in_transaction_type_id, walk_in_bin, walk_in_adate, walk_in_atime);
    });

//Transaction Type Rules
    $('body').on('change', '#transaction_type_id', function(e) {
        const selected_val = $(this).val();

        console.log(selected_val);

        let bin_holder = $('#bin'),
            busname_holder = $('#business_name');

        if (selected_val == 8 || selected_val == 16) { // = NEW
            bin_holder.closest('.input-group').addClass('d-none');
            busname_holder.prop('readonly', false);

        } else {
            bin_holder.closest('.input-group').removeClass('d-none');
            busname_holder.prop('readonly', true);

        }

    });
});

// Daterangepicker
$('.use-daterangepicker').each(function () {
    $(this).daterangepicker();
});



   
 


