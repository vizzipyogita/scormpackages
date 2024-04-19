@extends('layouts.masterLayout')
@section('title', 'Plans')
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
      <div class="card-header flex-column flex-md-row">
         <div class="head-label">
            <h5 class="card-title mb-0">Plans</h5>
         </div>
         <div class="dt-action-buttons text-end pt-3 pt-md-0">
            <div class="dt-buttons"> 
               <a class="dt-button create-new btn btn-primary" href="javascript:void(0);" onclick="openCreatePlanModal('/plans/create')">
                  <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add Organization Plan</span></span>
               </a> 
               <a class="dt-button create-new btn btn-primary" href="javascript:void(0);" onclick="openCreatePlanModal('/plans/create?isUserPlan=1')">
                  <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add User Plan</span></span>
               </a> 
            </div>
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
      <!-- Pricing Plans -->
      <div class="container">
         <div class="row">
            <div class="col-xs-12">
               <table class="datatables-basic table border-top dataTable no-footer dt-responsive" id="planDT" aria-describedby="DataTables_Table_0_info">
                  <thead>
                     <tr>
                        <th class="sorting">Id</th>
                        <th class="sorting">Country Name</th>
                        <th class="sorting_disabled">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($countryPlans as $country)
                        @php 
                           $organizationPlans = $country->GetSubscriptionPlansByCountryId($country->country_id);
                           $userPlans = $country->GetSubscriptionPlansByCountryId($country->country_id, 1);
                        @endphp
                        <tr>
                           <td>{{$country->id}}</td>
                           <td>{{$country->country->title}}</td>
                           <td>
                              @if(count($organizationPlans))
                                 <a href="javascript:void(0);" class="btn btn-sm btn-icon item-edit" title="Organization Plans" onclick="openViewPlansModal('/plans/view/{{$country->country_id}}')"><i class="bx bx-show"></i></a>
                              @endif
                              @if(count($userPlans))
                                 <a href="javascript:void(0);" class="btn btn-sm btn-icon item-edit" title="Guest User Plans" onclick="openViewPlansModal('/plans/view/{{$country->country_id}}', '1')"><i class="bx bx-show"></i></a>
                              @endif
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>

               
            </div>
         </div>
      </div>

      <div class="row m-3 hide">
         @foreach($countryPlans as $country)
            @php 
               $plans = $country->GetSubscriptionPlansByCountryId($country->country_id);
            @endphp
            <div class="col-md-12 mb-3">
               <h5 class="card-title mb-0">{{$country->country->title}}</h5>
                  <table class="table table-bordered mt-4">
                     <thead>
                        <th>Plan Name</th>
                        <th>Monthly Amount</th>
                        <th>Yearly Amount</th>
                     </thead>
                     <tbody>
                        @if($plans)
                           @foreach($plans as $plan)
                              <tr>
                                 <td>{{$plan->plan_name}}</td>
                                 <td><input type="text" class="form-control" value="{{$plan->monthly_plan_amount}}" onblur="changePlanAmount(this, '/plans/save/{{$plan->id}}', 'M')"></td>
                                 <td><input type="text" class="form-control" value="{{$plan->yearly_plan_amount}}" onblur="changePlanAmount(this, '/plans/save/{{$plan->id}}', 'Y')"></td>
                              </tr>
                           @endforeach
                        @endif
                     </tbody>
                  </table>
            </div>
         @endforeach
      </div>
      <!--/ Pricing Plans -->
      </div>
</div>
<div class="modal fade" id="modal-add-plan" data-keyboard="false" data-backdrop="static"></div>
<div class="modal fade" id="modal-view-plan" data-keyboard="false" data-backdrop="static"></div>
@endsection
@section('afterscripts')
<script src="/assets/js/datatable/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="/assets/js/security/plans.js"></script>
<script>
    
</script>
@endsection
