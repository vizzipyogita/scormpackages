function validateMobileNumber(eleObj) {
    $('.error-mobile').text('');
    var val = $(eleObj).val();
    if (val != '') {
        var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
        if (numberRegex.test(val)) {
            if (val.length > 10) {
                $('.error-mobile').text('Please enter 10 digits.');
                // swal("Error!", "Please enter 10 digits.", "error");   
                $(eleObj).val("");
            } else if (val.length < 10) {
                $('.error-mobile').text('Please enter 10 digits.');
                // swal("Error!", "Please enter 10 digits.", "error"); 
                $(eleObj).val("");
            }
        } else {
            $('.error-mobile').text('Please enter only number.');
            // swal("Error!", "Please enter only number.", "error"); 
            $(eleObj).val("");
        }
    }
}

// Get States All or By country
function getStates(eleObj) {
    var countryid = $(eleObj).val();

    $.ajax({
        url: '/masters/states/' + countryid,
        dataType: 'json',
        success: function(responseObj) {
            //console.log(result);
            if (responseObj.status == "error") {
                swal("Error!", responseObj.message, "error");
            } else if (responseObj.status == "success") {
                var optionshtml = '<option value="">--Select State--</option>';
                $.each(responseObj.states, function(index, obj) {
                    optionshtml += '<option value="' + obj.id + '">' + obj.name + '</option>';
                });
                $('[name=state_id]').html(optionshtml).trigger('change');
                $('[name="state_id"]').val(state_id).trigger('change');
            }
        },
        // A function to be called if the request fails. 
        error: function(jqXHR, textStatus, errorThrown) {
            var responseObj = jQuery.parseJSON(jqXHR.responseText);
            swal("Error!", responseObj.message, "error");
        }
    });
}

$("input.validateOnlynumbers").keypress(function(event) {
    if (event.keyCode == 13) {
        return true;
    } else {
        return /\d/.test(String.fromCharCode(event.keyCode));
    }

});

$(".validateOnlynumbers").keypress(function(event) {
    if (event.keyCode == 13) {
        return true;
    } else {
        return /\d/.test(String.fromCharCode(event.keyCode));
    }

});

$(".validateTextOnly").keyup(function() {
    var val = $(this).val();
    var textRegex = /^[a-zA-Z\s]+$/;
    if (textRegex.test(val)) {
        $(this).focus();
    } else {
        swal("Error!", "Please enter only text.", "error");
        $(this).val("");
        $(this).focus();
    }
});