@extends('layouts.masterLayout')
@section('title', 'Users')
@section('pagestyles')
<link
   rel="stylesheet"
   href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css"
   />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
   <div class="card">
      <div class="card-datatable table-responsive">
         <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
               <div class="head-label">
                  <h5 class="card-title mb-0">Users</h5>
               </div>
               
               @if($isAddButtonDisable == 1 && $organizationId !=1)
                  @if($loggedUserOrganizationId == 1)
                  <div class="row" id="ErrorDiv" style="padding:10px;">
                     <div class="col">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                           <span id="errorMsg">The license for this organization is exausted.</span>					
                        </div>
                     </div>
                  </div>     
                  @else
                  <div class="row" id="ErrorDiv" style="padding:10px;">
                     <div class="col">
                        <div class="alert alert-warning alert-dismissible" role="alert">
                           <span id="errorMsg">The license for your organization is exausted.
                              <a class="dt-button create-new btn btn-warning" href="/organization/upgrade" style="float: right; margin-top: -8px;">
                                 <span><i class="bx bx-credit-card me-sm-2"></i> <span class="d-none d-sm-inline-block">Upgrade</span></span>
                              </a> 
                           </span>					
                        </div>
                     </div>
                  </div>    
                  @endif
               @endif
               <div class="dt-action-buttons text-end pt-3 pt-md-0">
                  <div class="dt-buttons"> 
                     <a class="dt-button create-new btn btn-primary" onclick="sendMultipleUsersLoginDetails('/users/bulksendlogindetails')" href="javascript:void(0);">
                        <span><i class="bx bx-send me-sm-2"></i> <span class="d-none d-sm-inline-block">Send Login Details</span></span>
                    </a> 
                     @if($isAddButtonDisable == 0 || $organizationId ==1)
                        <a class="dt-button create-new btn btn-primary" onclick="openImportUserModal('/users/import')" href="javascript:void(0);">
                           <span><i class="bx bx-up-arrow-alt me-sm-2"></i> <span class="d-none d-sm-inline-block">Import</span></span>
                        </a> 

                        <a class="dt-button create-new btn btn-primary" onclick="openCreateSystemUserModal('/users/create')" href="javascript:void(0);">
                           <span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add User</span></span>
                        </a> 
                     @else
                        <button type="button" class="btn btn-primary" disabled=""><span><i class="bx bx-up-arrow-alt me-sm-2"></i> <span class="d-none d-sm-inline-block">Import</span></span></button>
                        <button type="button" class="btn btn-primary" disabled=""><span><i class="bx bx-plus me-sm-2"></i> <span class="d-none d-sm-inline-block">Add</span></span></button>
                     @endif
                     
                  </div>
               </div>
               <div class="dt-action-buttons mt-4 pt-3 pt-md-0">
                  <div class="dt-buttons"> 
                     @if($loggedUserOrganizationId == 1)
                        <div class="row">
                           <div class="col mb-3">
                              
                           </div>
                           <div class="col mb-3">
                              
                           </div>
                           <div class="col mb-3">
                              <select class="form-select select2" aria-label="Default select example" id="organization_id" name="organization_id" onchange="getOrganizationUsers(this)">
                                 @foreach($organizations as $organization)							
                                    <option value="{{$organization->id}}" @if($organization->id == $organizationId) selected @endif >{{$organization->organization_name}}</option>	
                                 @endforeach
                              </select>
                           </div>					
                        </div> 
                     @else 
                        <input type="hidden" id="organization_id" name="organization_id" value="{{ $organizationId }}">
                     @endif
                  </div>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <table class="datatables-basic table border-top dataTable no-footer dtr-column dt-responsive" id="systemUserDT" aria-describedby="DataTables_Table_0_info">
                        <thead>
                           <tr>
                              <th class="sorting">Id</th>
                              <th class="sorting">Name</th>
                              <th class="sorting">Role</th>
                              <th class="sorting">Status</th>
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
<input type="hidden" id="selectedUserIds" value="">
<div class="modal fade" id="modal-add-systemuser" data-keyboard="false" data-backdrop="static"></div>
<div class="modal fade" id="modal-change-password" data-keyboard="false" data-backdrop="static"></div>
<div class="modal fade" id="modal-import-systemuser" data-keyboard="false" data-backdrop="static"></div>
@endsection
@section('afterscripts')
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/security/systemusers.js"></script>
<script>
    
</script>
@endsection
