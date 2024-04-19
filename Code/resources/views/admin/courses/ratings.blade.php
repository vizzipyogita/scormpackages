@extends('layouts.masterLayout')
@section('title', 'Ratings')
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
                  <h5 class="card-title mb-0">Ratings</h5>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="ratingsDT" aria-describedby="DataTables_Table_0_info">
                        <thead>
                           <tr>
                              <th class="sorting">ID</th>
                              <th class="sorting">Comment</th>
                              <th class="sorting_disabled">Status</th>
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
<div class="modal fade" id="modal-add-course" data-keyboard="false" data-backdrop="static"></div>

@endsection
@section('afterscripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/security/ratings.js"></script>
@endsection
