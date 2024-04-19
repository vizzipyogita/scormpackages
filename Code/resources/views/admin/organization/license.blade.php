@extends('layouts.masterLayout')
@section('title', 'License')
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
                  <h5 class="card-title mb-0">License</h5>
               </div>
               <div class="dt-action-buttons text-end pt-3 pt-md-0">
                  <div class="dt-buttons"> 
                     <a class="dt-button create-new btn btn-primary hide" href="{{route('createOrganization')}}">
                        <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add New Record</span></span>
                    </a> 
                  </div>
               </div>
            </div>

            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
                        <thead>
                           <tr>
                            
                              <th class="sorting">Organization Name</th>
                              <th class="sorting">Code</th>
                              <th class="sorting">Email</th>
                              <th class="sorting_disabled">License</th>
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
        $(".license").addClass("active");

        
         // DataTable
         $('#DataTables_Table_0').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: "{{route('listLicense')}}",
            columns: [
               { data: 'organization_name', "sWidth": "30%" },
               { data: 'organization_code', "sWidth": "20%" },
               { data: 'email', "sWidth": "30%" },
               { data: 'action', "sWidth": "20%" },
            ]
         });
    });

   function updateOrganizationLicense(elemObj, organizationId){
      var token = $('meta[name="csrf-token"]').attr('content');
      var license = parseInt($(elemObj).val());
      var usersCount = parseInt($(elemObj).attr('userscount'));
      var isSubscription = $(elemObj).attr('isSubscription');
      
      if(isSubscription == 1 && license < usersCount){
         swal("Error!", "This organization has taken Subscription, Don't reduce license.", "error"); 
         $(elemObj).val(usersCount)
      }else{
         if (license != '') {
            var numberRegex = /^[+-]?\d+(\.\d+)?([eE][+-]?\d+)?$/;
            if (numberRegex.test(license)) {
               $.ajax({
                  url: '/license/save',
                  type: 'POST',
                  data: { '_method': 'post', '_token': token, 'user_license': license, 'organization_id': organizationId }, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
                  dataType: "json",
                  success: function(responseTxt, textStatus, jqXHR) {
                     if (responseTxt.status == "success") {
                        swal("Updated!", "Organization License Updated.", "success"); 
                     } else if (responseTxt.status == "error") {
                        swal("Error!", responseTxt.message, "error");               
                     }
                  },
                  error: function(error) {
                     console.log(error)
                     var responseObj = jQuery.parseJSON(error.responseText);                
                  }
               }); 
               
            } else {
               $(elemObj).val('');
               swal("Error!", "Please enter only number.", "error"); 
            }
         }
      }
      
      
   }
   
</script>
@endsection
