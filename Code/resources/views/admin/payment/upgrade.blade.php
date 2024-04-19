@extends('layouts.masterLayout')
@section('title', 'Upgrade')
@section('pagestyles')
<link rel="stylesheet"  href="/assets/js/datatable/jquery.dataTables.min.css" />
<style>
   .credit-card-box{
      padding: 30px !important;
   }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card overflow-hidden">
      <input type="hidden" id="is_yearly_plan" value="0">
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
      <!-- Pricing Plans -->
      <div class="pb-sm-5 pb-2 rounded-top">
         <div class="container py-5">
            <h2 class="text-center mb-2 mt-0 mt-md-4">Find the right plan for your site</h2>
            <p class="text-center pb-3"> Choose a subscription plan that meets your needs. </p>
            <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 py-4">
               <label class="switch switch-primary ms-sm-5 ps-sm-5 me-0">
                  <span class="switch-label">Monthly</span>
                  <input type="checkbox" class="switch-input price-duration-toggler" style="margin-left:40px">
                  <span class="switch-label">Annual</span>
               </label>
               <div class="mt-n5 ms-n5 ml-2 mb-2 d-none d-sm-inline-flex align-items-start">
                  <img src="/assets/img/icons/unicons/pricing-arrow-light.png" alt="arrow img" class="scaleX-n1-rtl" data-app-dark-img="pages/pricing-arrow-dark.png" data-app-light-img="pages/pricing-arrow-light.png">
                  <span class="badge badge-sm bg-label-primary">Save upto 40%</span>
               </div>
            </div>
            
            <div class="row mx-0 gy-3 px-lg-5">
               @if($isSubscription == 1)
                  <div class="d-flex align-items-end justify-content-end flex-wrap gap-2 py-4">
                     <a href="javascript:void(0);" class="" onclick="cancelUserSubscription('/organization/cancelsubscription')">Cancel Subscription</a>
                  </div>
               @endif
            <!-- 50 Users -->
            @php 
               $plan1 = $plans[0];
               $plan2 = $plans[1];
               $plan3 = $plans[2];
               $plan4 = $plans[3];
            @endphp
            <div class="col-lg mb-md-0 mb-4">
               <div class="card border-primary border shadow-none">
                  <div class="card-body position-relative">
                  <div class="position-absolute end-0 me-4 top-0 mt-4">
                     
                  </div>
                  <div class="my-3 pt-2 text-center">
                     <img src="/assets/img/icons/unicons/pro-plan.png" alt="Pro Image" height="80">
                  </div>
                  <h3 class="card-title fw-semibold text-center text-capitalize mb-1">50 Users</h3>
                  <p class="text-center">For small to medium businesses</p>
                  <div class="text-center monthly-plan">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan1['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan1['monthly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/month</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted d-none">{!! Session::get('currency') !!} {{$plan1['yearly_plan_amount']}} / year</small>
                  </div>

                  <div class="text-center yearly-plan d-none">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan1['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan1['yearly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/year</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted">{!! Session::get('currency') !!} {{(int)($plan1['yearly_plan_amount']/12)}} / month</small>
                  </div>

                  <ul class="ps-3 my-4 list-unstyled">
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Up to 50 users</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> No additional user available</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access 140 plus soft skill programs</li>
                  </ul>

                  @if($activePlanId != 1)
                     <a href="javascript:void(0);" class="btn btn-primary d-grid w-100" onclick="openPaymentModal('/organization/payment', {{$plan1['id']}})">Upgrade</a>
                  @else
                     <a href="javascript:void(0);" class="btn btn-success d-grid w-100">Current Plan</a>
                  @endif
                  </div>
               </div>
            </div>
            <!-- 100 Users -->
            <div class="col-lg mb-md-0 mb-4">
               <div class="card border-primary border shadow-none">
                  <div class="card-body position-relative">
                  <div class="position-absolute end-0 me-4 top-0 mt-4">
                     
                  </div>
                  <div class="my-3 pt-2 text-center">
                     <img src="/assets/img/icons/unicons/pro-plan.png" alt="Pro Image" height="80">
                  </div>
                  <h3 class="card-title fw-semibold text-center text-capitalize mb-1">100 Users</h3>
                  <p class="text-center">For small to medium businesses</p>
                  <div class="text-center monthly-plan">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan2['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan2['monthly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/month</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted d-none">{!! Session::get('currency') !!} {{$plan2['yearly_plan_amount']}} / year</small>
                  </div>

                  <div class="text-center yearly-plan d-none">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan2['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan2['yearly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/year</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted">{!! Session::get('currency') !!} {{(int)($plan2['yearly_plan_amount']/12)}} / month</small>
                  </div>

                  <ul class="ps-3 my-4 list-unstyled">
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Up to 100 users</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Additional user available at $3/mo</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access 140 plus soft skill programs</li>
                  </ul>

                  @if($activePlanId != 2)
                     <a href="javascript:void(0);" class="btn btn-primary d-grid w-100" onclick="openPaymentModal('/organization/payment', {{$plan2['id']}})">Upgrade</a>
                  @else
                     <a href="javascript:void(0);" class="btn btn-success d-grid w-100">Current Plan</a>
                  @endif
                  </div>
               </div>
            </div>

            <!-- 500 Users -->
            <div class="col-lg mb-md-0 mb-4">
               <div class="card border-primary border shadow-none">
                  <div class="card-body position-relative">
                  <div class="position-absolute end-0 me-4 top-0 mt-4">
                     
                  </div>
                  <div class="my-3 pt-2 text-center">
                     <img src="/assets/img/icons/unicons/pro-plan.png" alt="Pro Image" height="80">
                  </div>
                  <h3 class="card-title fw-semibold text-center text-capitalize mb-1">500 Users</h3>
                  <p class="text-center">For small to medium businesses</p>
                  <div class="text-center monthly-plan">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan3['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan3['monthly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal d-none">/month</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted d-none">{!! Session::get('currency') !!} {{$plan3['yearly_plan_amount']}} / year</small>
                  </div>

                  <div class="text-center yearly-plan d-none">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan3['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan3['yearly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/year</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted">{!! Session::get('currency') !!} {{(int)($plan3['yearly_plan_amount']/12)}} / month</small>
                  </div>


                  <ul class="ps-3 my-4 list-unstyled">
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Up to 500 users</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Additional user available at $2/mo</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access 140 plus soft skill programs</li>
                  </ul>

                  @if($activePlanId != 3)
                     <a href="javascript:void(0);" class="btn btn-primary d-grid w-100" onclick="openPaymentModal('/organization/payment', {{$plan3['id']}})">Upgrade</a>
                  @else
                     <a href="javascript:void(0);" class="btn btn-success d-grid w-100">Current Plan</a>
                  @endif
                  </div>
               </div>
            </div>

            <!-- Unlimited -->
            <div class="col-lg mb-md-0 mb-4">
               <div class="card border-primary border shadow-none">
                  <div class="card-body position-relative">
                  <div class="position-absolute end-0 me-4 top-0 mt-4">
                     
                  </div>
                  <div class="my-3 pt-2 text-center">
                     <img src="/assets/img/icons/unicons/pro-plan.png" alt="Pro Image" height="80">
                  </div>
                  <h3 class="card-title fw-semibold text-center text-capitalize mb-1">Unlimited</h3>
                  <p class="text-center">For small to medium businesses</p>
                  <div class="text-center monthly-plan">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan4['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan4['monthly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/month</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted d-none">{!! Session::get('currency') !!} {{$plan4['yearly_plan_amount']}} / year</small>
                  </div>

                  <div class="text-center yearly-plan d-none">
                     <div class="d-flex justify-content-center">
                        <sup class="h6 pricing-currency mt-3 mb-0 me-1 text-primary">{!! Session::get('currency') !!}</sup>
                        <h1 class="price-toggle price-yearly fw-semibold display-4 text-primary mb-0 d-none">{{(int)($plan4['yearly_plan_amount']/12)}}</h1>
                        <h1 class="price-toggle price-monthly fw-semibold display-4 text-primary mb-0">{{$plan4['yearly_plan_amount']}}</h1>
                        <sub class="h6 text-muted pricing-duration mt-auto mb-2 fw-normal">/year</sub>
                     </div>
                     <small class="price-yearly price-yearly-toggle text-muted">{!! Session::get('currency') !!} {{(int)($plan4['yearly_plan_amount']/12)}} / month</small>
                  </div>

                  <ul class="ps-3 my-4 list-unstyled">
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Unlimited users</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Additional user available at $1/mo</li>
                     <li class="mb-2"><span class="badge badge-center w-px-20 h-px-20 rounded-pill bg-label-primary me-2"><i class="bx bx-check bx-xs"></i></span> Access 140 plus soft skill programs</li>
                  </ul>
                  @if($activePlanId != 4)
                     <a href="javascript:void(0);" class="btn btn-primary d-grid w-100" onclick="openPaymentModal('/organization/payment', {{$plan4['id']}})">Upgrade</a>
                  @else
                     <a href="javascript:void(0);" class="btn btn-success d-grid w-100">Current Plan</a>
                  @endif
                  </div>
               </div>
            </div>

            </div>
         </div>
      </div>
      <!--/ Pricing Plans -->
      </div>
</div>

<div class="modal fade" id="modal-payment" data-keyboard="false" data-backdrop="static"></div>
@endsection
@section('afterscripts')
<script src="/assets/js/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="/assets/js/security/upgrade.js"></script>
<script>
    var subPalnType = '{{$subPalnType}}';
    if(subPalnType == 'Y'){
      $('.price-duration-toggler').click();
    }
</script>
@endsection
