$(document).ready(function() {
    //Active Left Menu
    $(".menu-item").removeClass("active");
    $(".upgrade").addClass("active");
});

$(document).on("change", ".price-duration-toggler", function(event) {
  event.preventDefault();
  if ($(this).is(':checked')) {
     $(".monthly-plan").addClass('d-none');
     $('.yearly-plan').removeClass('d-none');
     $('#is_yearly_plan').val(1);
  } else {
     $(".monthly-plan").removeClass('d-none');
     $('.yearly-plan').addClass('d-none');
     $('#is_yearly_plan').val(0);
  }
});

function openPaymentModal(url, planId) {
    var isYearlyPlan = $('#is_yearly_plan').val();
    $.ajax({
       url: url+"/"+planId+"?is_yearly_plan="+isYearlyPlan,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
             } else if (responseObj.status == "success") {
                jQuery('#modal-payment').html(responseObj.view).modal('show', { backdrop: 'static' });
                // $('#Addform').parsley();
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    });    
}

function showCouponDiv(){
    if($("#apply_coupon").prop('checked') == true){
        $('#coupon_apply_div').removeClass('hide');
    }else{
        $('#coupon_apply_div').addClass('hide');
        var subscriptionPlanAmount = $('#subscriptionPlanAmount').val();
        $('#netAmmount').val(subscriptionPlanAmount);
        $('#total_display_amount').text(subscriptionPlanAmount);
            
    }
}

function checkCouponValidity(elemObj, url){
    var couponCode = $(elemObj).val();
    if(couponCode !=''){
        $.ajax({
            url: url+"?code="+couponCode,
            dataType: 'json',
            processData: false,
            cache : false,
            success: function(responseObj) {
                  if (responseObj.status == "error") {   
                     swal("Error!", responseObj.message, "error");                   
                  } else if (responseObj.status == "success") {
                     console.log(responseObj);
                     var couponData = responseObj.couponData;
                     if(couponData.errorMassage !=""){
                        swal("Error!", couponData.errorMassage, "error");   
                        $(elemObj).val('');
                     }else{
                        var planAmount = $('#subscriptionPlanAmount').val();
                        $('#coupon_info_div').removeClass('hide');
                        $('#discount_amount').html(couponData.discount);
    
                        // Calculate discount
                        var discounttype = couponData.discountType;
                        var totalAmount = 0;
                        var netAmount = $('#netAmmount').val();
                        var subscriptionPlanAmount = $('#subscriptionPlanAmount').val();
                        netAmount = subscriptionPlanAmount;
                        if(discounttype == 2){
                            var disc = couponData.discount;
                            disc = couponData.discount.slice(0,-1);
                            var discountAmount = parseFloat(subscriptionPlanAmount * disc)/100;
                            var totalAmount = subscriptionPlanAmount - discountAmount;
                            var netAmount = subscriptionPlanAmount - discountAmount;

                        }else if(discounttype == 3){
                            var disc = couponData.discount;
                           //  disc = couponData.discount.slice(1);
                            var totalAmount = subscriptionPlanAmount - disc;
                            var netAmount = subscriptionPlanAmount - disc;
                            $('#discount_amount').html(couponData.currency+''+couponData.discount);
                        }else{
                            var totalAmount = 'Free';
                            var netAmount = 0;
                        }
    
                        $('#total_display_amount').text(totalAmount);
                        $('#netAmmount').val(netAmount);
                        
                     }
                  }
            },
            // A function to be called if the request fails. 
            error: function(jqXHR, textStatus, errorThrown) {
                  var responseObj = jQuery.parseJSON(jqXHR.responseText);            
            }
         });  
    }
      
}

function cancelUserSubscription(url) {
    $.ajax({
       url: url,
       dataType: 'json',
       processData: false,
       cache : false,
       success: function(responseObj) {
             if (responseObj.status == "error") {   
                swal("Error!", responseObj.message, "error");                   
             } else if (responseObj.status == "success") {
                swal("Success!", 'Subscription Canceled', "success");   
                location.href = '/organization/upgrade';
             }
       },
       // A function to be called if the request fails. 
       error: function(jqXHR, textStatus, errorThrown) {
             var responseObj = jQuery.parseJSON(jqXHR.responseText);            
       }
    }); 
}