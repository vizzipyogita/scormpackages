@extends('layouts.masterLayout')
@section('title', 'Coupons')
@section('pagestyles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card">
      <div class="card-datatable table-responsive">
         <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
               <div class="head-label">
                  <h5 class="card-title mb-0">Coupons</h5>
               </div>
               <div class="dt-action-buttons text-end pt-3 pt-md-0">
                  <div class="dt-buttons"> 
                     <a class="dt-button create-new btn btn-primary" onclick="openCreateCouponModal('/coupons/create')" href="javascript:void(0);">
                        <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add New Coupon</span></span>
                    </a> 
                  </div>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="couponDT" aria-describedby="DataTables_Table_0_info">
                        <thead>
                           <tr>
                              <th class="sorting">Id</th>
                              <th class="sorting">Coupon Code</th>
                              <th class="sorting">Discount Type</th>
                              <th class="sorting">Start Date</th>
                              <th class="sorting">End Date</th>
                              <th class="sorting_disabled">Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           
                          
                        </tbody>
                     </table>

                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="modal-add-coupon" data-keyboard="false" data-backdrop="static"></div>
@endsection
@section('afterscripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="/assets/js/security/coupons.js"></script>
<script>
    
</script>
@endsection
