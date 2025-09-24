/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/app.js":
/*!*****************************!*\
  !*** ./resources/js/app.js ***!
  \*****************************/
/***/ (() => {



// require('./bootstrap');
var xheader = $('meta[name="csrf-token"]').attr('content');
var pull_slots = function pull_slots(date, time) {
  var loader = $('#appoint-spiner');
  $.ajax({
    'url': 'slots',
    'type': 'post',
    'data': {
      'adate': date,
      'atime': time
    },
    'headers': {
      'X-CSRF-TOKEN': xheader
    },
    'beforeSend': function beforeSend() {
      $('.form-control').removeClass('is-invalid');
      loader.removeClass('d-none');
    },
    'complete': function complete() {
      loader.addClass('d-none');
    },
    'success': function success(data) {
      $('#timeslot-avail').text(data.message);
    },
    'error': function error(err) {
      var err_msg,
        err_json = err.responseJSON;
      switch (err.status) {
        case 422:
          var err_data = err_json.errors;
          err_msg = err_json.message;

          // console.log(err_data);

          for (var _key in err_data) {
            $('#' + _key).addClass('is-invalid').next().text(err_data[_key][0]);
          }
          var first_err_arr = Object.keys(err_data)[0];
          if (first_err_arr.includes('.')) {
            var err_split = first_err_arr.split('.');
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
};
var pullWalkInAvaiableSlots = function pullWalkInAvaiableSlots(departmentid, transaction_type_id, bin, adate, atime) {
  var loader = $('#appoint-spiner');
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
      'X-CSRF-TOKEN': xheader
    },
    'beforeSend': function beforeSend() {
      $('.form-control').removeClass('is-invalid');
      loader.removeClass('d-none');
    },
    'complete': function complete() {
      loader.addClass('d-none');
    },
    'success': function success(data) {
      $('#timeslot-avail').text(data.message);
    },
    'error': function error(err) {
      var err_msg,
        err_json = err.responseJSON;
      switch (err.status) {
        case 422:
          var err_data = err_json.errors;
          err_msg = err_json.message;
          for (var _key in err_data) {
            $('#' + _key).addClass('is-invalid').next().text(err_data[_key][0]);
          }
          var first_err_arr = Object.keys(err_data)[0];
          if (first_err_arr.includes('.')) {
            var err_split = first_err_arr.split('.');
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
};
$(document).ready(function (e) {
  $('body').on('change', '#atime', function (e) {
    var date_val = $('#adate').val(),
      time_val = $(this).val();
    pull_slots(date_val, time_val);
  });
  $('body').on('input', '#adate', function (e) {
    var date_val = $(this).val(),
      time_val = $('#atime').val();
    pull_slots(date_val, time_val);
  });
  $('body').on('submit', '#master-form', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var form = $(this);
    var dept_val = $('#departmentid').val(),
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
      var a_box = $('#alert-box-form'),
        sub_btn = $('#btn-submit-appointment'),
        loader = $('#submit-spiner');
      $.ajax({
        'url': 'save',
        'type': 'post',
        'data': form.serialize(),
        'headers': {
          'X-CSRF-TOKEN': xheader
        },
        'beforeSend': function beforeSend() {
          $('.form-control').removeClass('is-invalid');
          a_box.addClass('d-none');
          loader.removeClass('d-none');
          sub_btn.attr('disabled', true);
        },
        'complete': function complete() {
          loader.addClass('d-none');
          sub_btn.attr('disabled', false);
        },
        'success': function success(data) {
          var message = data.message,
            slot_message = data.slot_message;
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
        'error': function error(err) {
          var err_msg,
            err_json = err.responseJSON;
          switch (err.status) {
            case 422:
              var err_data = err_json.errors;
              err_msg = err_json.message;

              // console.log(err_data);

              for (var _key in err_data) {
                $('#' + _key).addClass('is-invalid').parent().find('.invalid-feedback').text(err_data[_key][0]);
              }
              var first_err_arr = Object.keys(err_data)[0];
              if (first_err_arr.includes('.')) {
                var err_split = first_err_arr.split('.');
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
  var ref_val = 0;
  $('body').on('click', '#cancel-find-info', function (e) {
    var ref_inp = $('#reference_code');
    ref_val = ref_inp.val().trim();
    ref_inp.parent().removeClass('is-invalid');
    if (ref_val == '') {
      ref_inp.addClass('is-invalid');
    } else {
      var a_box = $('#info-box'),
        a_box2 = $('#alert-box-form'),
        loader = $('#search-spiner'),
        cancel_btn = $('#submit-cancel');
      $.ajax({
        'url': 'search-info',
        'type': 'post',
        'data': {
          'reference_code': ref_val
        },
        'headers': {
          'X-CSRF-TOKEN': xheader
        },
        'beforeSend': function beforeSend() {
          ref_inp.removeClass('is-invalid');
          loader.removeClass('d-none');
          a_box.addClass('d-none');
          a_box2.addClass('d-none');
        },
        'complete': function complete() {
          loader.addClass('d-none');
        },
        'success': function success(data) {
          var message = data.message,
            fullname = data.fullname;
          if (data.error) {
            a_box.removeClass('alert-primary d-none').addClass('alert-danger').find('h4').text('Sorry').end().find('p').html(message);
            cancel_btn.attr('disabled', true);
          } else {
            a_box.removeClass('alert-danger d-none').addClass('alert-primary').find('h4').text('Hi ' + fullname + ',').end().find('p').html(message);
            cancel_btn.attr('disabled', false);
          }
        },
        'error': function error(err) {
          var err_msg,
            err_json = err.responseJSON;
          switch (err.status) {
            case 422:
              var err_data = err_json.errors;
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
    e.preventDefault();
    console.log("✅ Button clicked"); // check if this shows in console

    var a_box = $('#alert-box-form'),
      loader = $('#submit-cancel-spiner'),
      $_this = $(this);
    $.ajax({
      'url': 'cancel',
      'type': 'post',
      'data': {
        'reference_code': ref_val
      },
      'headers': {
        'X-CSRF-TOKEN': xheader
      },
      'beforeSend': function beforeSend() {
        loader.removeClass('d-none');
      },
      'complete': function complete() {
        loader.addClass('d-none');
      },
      'success': function success(data) {
        if (data.error) {
          a_box.removeClass('alert-success d-none').addClass('alert-danger').text(data.message);
        } else {
          a_box.removeClass('alert-danger d-none').addClass('alert-success').text(data.message);
        }
        $_this.attr('disabled', true);
      },
      'error': function error(err) {
        var err_msg,
          err_json = err.responseJSON;
        switch (err.status) {
          case 422:
            var err_data = err_json.errors;
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

  // submit start accept
  $('body').on('click', '#submit-accept', function (e) {
    e.preventDefault();
    var ref_val = $('#reference_codes').val(); // <-- make sure your input has this ID

    if (!ref_val) {
      alert("Reference code is required");
      return;
    }
    $.ajax({
      url: acceptUrl,
      type: "POST",
      data: {
        reference_code: $('#reference_codes').val(),
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function success(data) {
        alert(data.message);
        if (!data.error) {
          window.location.href = slotViewerUrl; // ✅ this will now be correct
        }
      },
      error: function error(xhr) {
        console.error("❌ AJAX Error:", xhr.responseText);
      }
    });
  });

  // submit end accept

  // health dec submit form
  $('body').on('submit', '#health-mainfrm', function (e) {
    e.preventDefault();
    e.stopPropagation();
    var form = $(this);
    form.removeClass('was-validated');
    $('.form-control').removeClass('is-invalid');
    $('.custom-feedback').remove();
    $('#tbl-yes-no tr').each(function (i, v) {
      var chk = $(this).find('input:radio');
      if (chk.is(':checked') == false) {
        $(this).find('td:first-child').append('<div class="invalid-feedback d-block custom-feedback">Please choose whether OO/HINDI.</div>');
      }
    });
    if (form[0].checkValidity() == false) {
      form.addClass('was-validated');
      $('.form-control:invalid').eq(0).focus();
    } else {
      var a_box = $('#alert-box-form'),
        sub_btn = $('#health-submit-btn'),
        loader = $('#submit-spiner');
      $.ajax({
        'url': 'health/save',
        'type': 'post',
        'data': form.serialize(),
        'headers': {
          'X-CSRF-TOKEN': xheader
        },
        'beforeSend': function beforeSend() {
          $('.form-control').removeClass('is-invalid');
          a_box.addClass('d-none');
          loader.removeClass('d-none');
          sub_btn.attr('disabled', true);
        },
        'complete': function complete() {
          loader.addClass('d-none');
          sub_btn.attr('disabled', false);
        },
        'success': function success(data) {
          var message = data.message;
          if (data.error) {
            a_box.removeClass('alert-success d-none').addClass('alert-danger').html(message);
          } else {
            a_box.removeClass('alert-danger d-none').addClass('alert-success').html(message);
          }
          $(window).scrollTop(0);
        },
        'error': function error(err) {
          var err_msg,
            err_json = err.responseJSON;
          switch (err.status) {
            case 422:
              var err_data = err_json.errors;
              err_msg = err_json.message;

              // console.log(err_data);

              for (var _key in err_data) {
                $('#' + _key).addClass('is-invalid').parent().find('.invalid-feedback').text(err_data[_key][0]);
              }
              var first_err_arr = Object.keys(err_data)[0];
              if (first_err_arr.includes('.')) {
                var err_split = first_err_arr.split('.');
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
  $('body').on('click', '.linked-text', function (e) {
    var _id = $(this).attr('name'),
      enable_txt = $(this).data('allow-txt'),
      inpt = $('#' + _id + 'ans');
    inpt.prop('disabled', !enable_txt).focus();
  });

  // health End

  $('body').on('change', '#departmentid', function () {
    var departmentId = $(this).val();
    var transaction_elem = $('#transaction_type_id'),
      tran_spiner = $('#transaction_spinner'),
      bplo_only_inp = $('#bin, #business_name');
    bplo_only_inp.toggleClass('d-none', departmentId != 2 && departmentId != 23);

    // show alert if any
    $('.department-alert-active').addClass('d-none').removeClass('.department-alert-active');
    $('.department-alert[data-alert-id=' + departmentId + ']').addClass('.department-alert-active').removeClass('d-none');
    // show alert if any end

    $.ajax({
      url: 'dropdowns/transaction-types?department_id=' + departmentId,
      beforeSend: function beforeSend() {
        transaction_elem.prop('disabled', true);
        tran_spiner.removeClass('d-none');
      },
      complete: function complete() {
        transaction_elem.prop('disabled', false);
        tran_spiner.addClass('d-none');
      },
      success: function success(response) {
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
  $('#cancel-find-infos').on('click', function (e) {
    e.preventDefault();
    var refCode = $('#reference_codes').val().trim();
    if (refCode === "") {
      alert("Please enter a reference code before searching.");
      return false;
    }
    window.open(verificationUrl + "/" + refCode, "_self");
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
  var oldTransactionType = $('#transaction_type_id').data('old');
  if (oldTransactionType) {
    $("#departmentid").change(function (e, callback) {
      setTimeout(function () {
        if (typeof callback === "function") callback();
      }, 800);
    });
    $('#departmentid').trigger('change', function () {
      $('#transaction_type_id').val(oldTransactionType);
      $('#transaction_type_id').trigger('change');
    });
  }

  // Walk-In Pull Slots
  $('body').on('change', '#walk_in_atime', function (e) {
    var walk_in_adate = $('#walk_in_adate').val(),
      walk_in_atime = $(this).val(),
      walk_in_departmentid = $('#departmentid').val(),
      walk_in_transaction_type_id = $('#transaction_type_id').val(),
      walk_in_bin = $('#bin').val();
    pullWalkInAvaiableSlots(walk_in_departmentid, walk_in_transaction_type_id, walk_in_bin, walk_in_adate, walk_in_atime);
  });
  $('body').on('input', '#walk_in_adate', function (e) {
    var walk_in_adate = $(this).val(),
      walk_in_atime = $('#walk_in_atime').val(),
      walk_in_departmentid = $('#departmentid').val(),
      walk_in_transaction_type_id = $('#transaction_type_id').val(),
      walk_in_bin = $('#bin').val();
    pullWalkInAvaiableSlots(walk_in_departmentid, walk_in_transaction_type_id, walk_in_bin, walk_in_adate, walk_in_atime);
  });

  //Transaction Type Rules
  $('body').on('change', '#transaction_type_id', function (e) {
    var selected_val = $(this).val();
    console.log(selected_val);
    var bin_holder = $('#bin'),
      busname_holder = $('#business_name');
    if (selected_val == 8 || selected_val == 16) {
      // = NEW
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
$('#sdate').on('change', function () {
  var date = $(this).val();
  $.ajax({
    url: window.slotviewerUrl,
    type: 'GET',
    data: {
      sdate: date
    },
    dataType: 'json',
    success: function success(data) {
      var list = $('#slot-list');
      list.empty();
      if (data.count.length > 0) {
        $.each(data.count, function (index, detail) {
          list.append("\n                            <li class=\"list-group-item d-flex justify-content-between align-items-center\">\n                                ".concat(detail.atime.split('-')[0].trim(), "\n                                <span class=\"badge badge-primary badge-pill\">\n                                    <strong>").concat(detail.Total, "</strong>\n                                </span>\n                            </li>\n                        "));
        });
      } else {
        list.append("<li class=\"list-group-item\">No appointments found</li>");
      }
    },
    error: function error(xhr, status, _error) {
      console.error('Error:', _error);
    }
  });
});

/***/ }),

/***/ "./resources/sass/app.scss":
/*!*********************************!*\
  !*** ./resources/sass/app.scss ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/app": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/js/app.js")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["css/app"], () => (__webpack_require__("./resources/sass/app.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;