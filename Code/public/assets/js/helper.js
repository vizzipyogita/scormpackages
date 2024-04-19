$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).ajaxStart(function () {
    $("#overlay").show();
}).ajaxStop(function () {
    $("#overlay").hide();
}).ajaxError(function () {
    $("#overlay").hide();
}).ajaxComplete(function () {
    $("#overlay").hide();
}).ajaxSuccess(function () {
    $("#overlay").hide();
})

function showOverlay() {
    $("#overlay").show();
}

function hideOverlay() {
    $("#overlay").show();
}


function processError(response) {
    try {
        let errorResponse = JSON.parse(response.responseText);
        $.each(errorResponse.errors, function (id, error) {
            $('#' + id).next('.error').html(error[0]);
        });
    } catch (e) {

    }
}

$(document).ready(function () {
    $('input,textarea').on('keyup', function () {
        $(this).next('.error').html('');
    });
    $('select,input').on('change', function () {
        $(this).next('.error').html('');
    });

});

function showMessage(msg, msgTagId = 'error_message_div', alertClass = 'alert-success') {
    $('#' + msgTagId)
        .html('<div class="alert ' + alertClass + '">' + msg + '</div>');
    setTimeout(function () {
        $('#' + msgTagId + ' .alert.' + alertClass).slideUp(500, function (e) {
            $(this).remove();
        });
    }, 4000);
}
function scrollTop() {
    $("html, body").animate({scrollTop: 0}, "slow");
}

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    let charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function capitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

function convertToDoubleDigit(digit) {
    return ("0" + parseInt(digit)).slice(-2)
}

function validateEmail($email) {
    let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}
