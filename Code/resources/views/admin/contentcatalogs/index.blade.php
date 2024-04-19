@extends('layouts.masterLayout')
@section('title', 'Content Catalogs')
@section('pagestyles')
<link rel="stylesheet"  href="/assets/js/datatable/jquery.dataTables.min.css" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card">
      <div class="card-datatable table-responsive">
         <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
               <div class="head-label">
                  <h5 class="card-title mb-0">Content Catalogs</h5>
               </div>
               <div class="dt-action-buttons text-end pt-3 pt-md-0">
                  <div class="dt-buttons"> 
                     <a class="dt-button create-new btn btn-primary" href="{{route('createOrganization')}}">
                        <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>
                    </a> 
                  </div>
               </div>
            </div>

            <div class="container hide">
               <div class="row">
                  <div class="col-xs-12">
                     <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                           <tr>
                              <th class="sorting">Name</th>
                              <th class="sorting">Code</th>
                              <th class="sorting">Email</th>
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
@endsection
@section('afterscripts')
<script src="/assets/js/datatable/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {

        //Active Left Menu
        $(".menu-item").removeClass("active");
        $(".content-catalog").addClass("active");

    });
   
</script>
@endsection
