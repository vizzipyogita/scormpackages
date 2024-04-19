@extends('layouts.user_layout')
@section('title', $pageTitle)

@section('pagestyles')
<link href="/assets/css/style-user.css" rel="stylesheet">
<link href="/assets/css/responsive-user.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="/assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="/assets/css/ratingcss.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    body{
        background-color:#fff;
    }
    #page-overlay{
        position:fixed;
        z-index: 10000;
    }
    .rating-star span{
        color:#e59819 !important;
    }
    .btn-view-all{
        color:#e59819 !important;
        float: right;
    }
    .administrative-slider{
        width:293px !important;
    }
    .searchspan {
        margin-left: 10px;
        margin-top: -27px;
        position: absolute;
        z-index: 2;
        color: #7a777d;
    }
    #searchText{
        padding-left: 30px !important;
    }
    .navbar-nav{
        float: right !important;
    }
    .form-check-input:checked {
        background-color: #FFB900;
        border-color: #FFB900;
    }
    .payment-div{
        background-color: #eee;
        width: 100%;
        height: auto;
        border-radius: 20px;
        padding: 25px;
    }
    .credit-card-box{
      padding: 30px !important;
   }
   .input-group-text{
        height: 38px;
   }
   .btn-secondary{
        border-radius: 25px;
        height: 51px;
        padding-top: 12px;
   }
   #subscription_pay_btn {
        width: 90.23px;
   }
</style>
@endsection

@section('content')

<section class=" mt-5 ">
    <div class="container">
        <div class=" d-flex justify-content-between align-items-center">
            <h3 class="heading">{{$pageTitle}}</h3>
        </div>
    </div>
    <div class="row m-0">
         @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible" role="alert">
               {{Session::get('error_message')}}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            
         @elseif(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible" role="alert">
               {{Session::get('success_message')}}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>                
         @endif
      </div>
    <div class="container mt-2">
        <div class="row mt-4">
            <div class="col-6">
                <h5 class="heading">Select your courses</h5>
                <div class="row">
                    <div class="col-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="all" courseCount="141" payAmount="{{ $planDetailsForAll->yearly_plan_amount }}" id="chkAllCategory">
                            <label class="form-check-label" for="chkAllCategory">
                                All
                            </label>
                        </div>
                    </div>
                    @if(count($courseCategories))
                        @foreach($courseCategories as $category)
                            @php 
                                $payAmount = 25;
                                if($category->title == 'Microsoft Bundle'){
                                    $payAmount = 15;
                                }
                                $disableClass= "";
                                $isChecked = "";
                                $categoryCheckItemClass = "categoryCheckItem";
                                if (in_array($category->id, $subscribedCategoryIds)) {
                                    $disableClass= "disabled-category";
                                    $isChecked = "checked";
                                    $categoryCheckItemClass = "";
                                }
                                $availableCourses = $category->getCategoryCourses();
                                $plan = $category->GetSubscriptionPlansByCountryAndCategoryId($loggedUserCountryId, $category->id);
                                if($plan){
                                    $payAmount = $plan->yearly_plan_amount;
                                }
                            @endphp
                            @if(count($availableCourses))
                                <div class="col-12 mt-3 {{$disableClass}}">
                                    <div class="form-check">
                                        <input class="form-check-input {{$categoryCheckItemClass}}" type="checkbox" value="{{$category->id}}" payAmount="{{$payAmount}}" categoryName="{{$category->title}}" courseCount="{{count($availableCourses)}}" onchange="getCategoryToPay(this)" id="chkCategory_{{$category->id}}" {{$isChecked}}>
                                        <label class="form-check-label" for="chkCategory_{{$category->id}}">
                                            {{$category->title}}
                                        </label>
                                        <div class="text-muted small fw-semibold mb-2">Available courses: {{count($availableCourses)}}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-6">
                <div class="container payment-div">
                    <h5 class="heading">Summary</h5>
                    <div class="" id="pay_categoary_list">
                        
                    </div>
                    <div class="row mt-5 hide" id="order_total_div">
                        <div class="col-10 mt-4">
                            <label class="form-check-label"><strong>Order Total</strong></label>
                        </div>
                        <div class="col-2 mt-4">
                            <strong><label class="form-check-label" id="order_total_amount">$100</label></strong>
                        </div>
                    </div>
                    <div class="row mt-3 justify-content-center hide" id="proceed_to_pay">
                        <div class="text-muted small fw-semibold mb-2">Access <span id="display_total_course_count"></span> soft skill programs for one year.</div>
                        <div class="row mb-3">
                            <div class="form-check">
                                <input class="form-check-input " type="checkbox" value="1" id="terms_conditions" checked>
                                <label class="form-check-label" for="terms_conditions">
                                    I agree to the <a class="" href="https://www.americansoftskillacademy.com/terms-service" target="_blank">Terms &amp; Conditions</a>
                                </label>
                            </div>
                        </div>
                        <a class="btn learn-btn d-flex align-items-center justify-content-center btnSearch" onclick="openPaymentModal('/user/payment')" style="width: 50%;" href="javascript:void(0);">
                            Proceed to pay
                        </a>
                    </div>
                    <input type="hidden" id="totalCourses" name="totalCourses" value="0">
                    <input type="hidden" id="netAmount" name="netAmount" value="0">
                    <input type="hidden" id="currency" name="currency" value="{{ Session::get('currency') }}">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-payment" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('pageScripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    var categoryIds = [];
    var netAmount = 0;
    var totalCourses = 0;
    var categoryId = '{{$categoryId}}';
    var AllCategoryAmount = '{{ $planDetailsForAll->yearly_plan_amount }}';
    $(document).ready(function() {
        if(categoryId > 0){
            $('#chkCategory_'+categoryId).trigger('click');
            $('#chkCategory_'+categoryId).removeClass('categoryCheckItem');
        }

        $("#chkAllCategory").click(function(){
            totalCourses = 0;
            $('#totalCourses').val("0");
            $('#pay_categoary_list').html('');
            var checkboxes = $(".categoryCheckItem");
            netAmount = 0;
            if (this.checked) {
                for (var i = 0; i < checkboxes.length; i++) {
                    checkboxes[i].checked = true;
                    getCategoryToPay(checkboxes[i]);
                    // $('#totalCourses').val("140");
                }
            } else {
                categoryIds = [];
                for (var i = 0; i <= checkboxes.length; i++) {
                    checkboxes[i].checked = false;
                    getCategoryToPay(checkboxes[i]);
                    netAmount = 0;
                    $('#totalCourses').val("0");
                    totalCourses = 0;
                }
            }

            // $(".categoryCheckItem").trigger("click"); 
            // $(".categoryCheckItem").attr('checked', true);  
            // $(".categoryCheckItem").attr('checked', $(this).attr('checked')); //add this line and working proper - hope will help someone
        }); 
    });
    
    function getCategoryToPay(elemObj){
        var checkedStatus = $(elemObj).prop('checked') ? 1 : 0;
        var checkedAllStatus = $('#chkAllCategory').prop('checked') ? 1 : 0;        
        var categoryId = $(elemObj).val();
        var categoryName = $(elemObj).attr('categoryname');
        var payamount = $(elemObj).attr('payamount');
        var courseCount = $(elemObj).attr('courseCount');
        var currency = $('#currency').val();
        totalCourses = $('#totalCourses').val();
        // if(currency == '&#8377;'){
        //     payamount = payamount * 80;
        // }

        if(checkedStatus == 1){
            categoryIds.push(categoryId);
            netAmount = netAmount + parseInt(payamount);
            totalCourses = parseInt(totalCourses) + parseInt(courseCount);
            var html ='<div class="row mt-2" id="category_pay_'+categoryId+'"><div class="col-10 mt-3"><label class="form-check-label">'+categoryName+'</label><div class="text-muted small fw-semibold mb-2">Available courses: '+courseCount+'</div></div><div class="col-2 mt-3"><label class="form-check-label">'+currency+''+payamount+'</label></div></div>';
            $('#pay_categoary_list').append(html);
            $('#display_total_course_count').text(totalCourses);
        }else{
            categoryIds = arrayRemove(categoryIds, categoryId);
            netAmount = netAmount - parseInt(payamount);
            totalCourses = parseInt(totalCourses) - parseInt(courseCount);
            $('#display_total_course_count').text(totalCourses);
            $('#category_pay_'+categoryId).remove();
        }

        if(categoryIds.length > 0){
            $('#proceed_to_pay').removeClass('hide');
            $('#order_total_div').removeClass('hide');
            if(checkedAllStatus == 1){
                payamount = AllCategoryAmount;
                // if(currency == '&#8377;'){
                //     payamount = payamount * 80;
                // }                
                $('#order_total_amount').html('<strike>'+currency+''+netAmount+'</strike> / '+ currency+''+ payamount);
                $('#netAmount').val(payamount);
                $('#totalCourses').val(totalCourses);
                $('#display_total_course_count').text(totalCourses);
            }else{
                $('#order_total_amount').html(currency+''+netAmount);
                $('#netAmount').val(netAmount);
                $('#totalCourses').val(totalCourses);
                $('#display_total_course_count').text(totalCourses);
            }
        }else{
            $('#proceed_to_pay').addClass('hide');
            $('#order_total_div').addClass('hide');
            $('#order_total_amount').html(currency+''+netAmount);
            $('#netAmount').val(netAmount);
            $('#totalCourses').val(totalCourses);
            $('#display_total_course_count').text(totalCourses);
        }
    }

    function arrayRemove(arr, value) {
        return arr.filter(function (ele) {
            return ele != value;
        });

    }

    function openPaymentModal(url) {
        var netAmount = $('#netAmount').val();
        $.ajax({
        url: url+"?netAmount="+netAmount,
        dataType: 'json',
        processData: false,
        cache : false,
        success: function(responseObj) {
                if (responseObj.status == "error") {   
                    swal("Error!", responseObj.message, "error");                   
                } else if (responseObj.status == "success") {
                    jQuery('#modal-payment').html(responseObj.view).modal('show', { backdrop: 'static' });
                    $('#categoryIds').val(categoryIds);
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
</script>
@endsection
