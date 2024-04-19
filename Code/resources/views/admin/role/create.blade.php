

<div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
    <div class="modal-content">
	<div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>		
      <div class="modal-body">
        
        <div class="text-center mb-4">
          <h3 class="role-title">{{$pageTitle}}</h3>
          <p>Set role permissions</p>
        </div>

		<div class="row hide" id="ErrorDiv" style="padding:10px;">
			<div class="col">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<span id="errorMsg"></span>					
				</div>
			</div>
		</div>       

        <!-- Add role form -->
		<form id="Addform" class="mb-3" action="{{route('saveRole')}}" method="POST">
			@csrf
			<input type="hidden" id="roleId" name="id" value="{{$roleId}}">

          <div class="col-12 mb-4 fv-plugins-icon-container">
            <label class="form-label" for="modalRoleName">Role Name</label>
            <input type="text" id="modalRoleName" name="name" value="{{$name}}" required class="form-control" placeholder="Enter a role name" tabindex="-1">
          <div class="fv-plugins-message-container invalid-feedback"></div></div>
          <div class="col-12">
            <h4>Role Permissions</h4>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-semibold">Administrator Access <i class="bx bx-info-circle bx-xs" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Allows a full access to the system" data-bs-original-title="Allows a full access to the system"></i></td>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" onclick="selectAllCheckbox(this)">
                        <label class="form-check-label" for="selectAll">
                          Select All
                        </label>
                      </div>
                    </td>
                  </tr>

                  @foreach($permissions as $permission)
                    @php 
                      $hasAccess = DB::table('role_permissions')->where('permission_id', $permission->id)->where('role_id', $roleId)->first();
                    @endphp
                  <input class="form-check-input checkboxall" type="hidden" name="permission_ids[]" value="{{$permission->id}}">
                  <tr>
                    <td class="text-nowrap fw-semibold">{{$permission->display_name}}</td>
                    <td>
                      <div class="d-flex">
                        <div class="form-check me-3 me-lg-5">
                          <input class="form-check-input checkboxall" type="checkbox" name="is_read_{{$permission->id}}" value="{{$permission->id}}" id="{{$permission->id}}_Read" {{($hasAccess && $hasAccess->is_read==1) ? 'checked' : ''}}>
                          <label class="form-check-label" for="{{$permission->id}}_Read">Read</label>
                        </div>
                        <div class="form-check me-3 me-lg-5">
                          <input class="form-check-input checkboxall" type="checkbox" name="is_create_{{$permission->id}}" value="{{$permission->id}}" id="{{$permission->id}}_Create" {{($hasAccess && $hasAccess->is_create==1) ? 'checked' : ''}}>
                          <label class="form-check-label" for="{{$permission->id}}_Create">Create</label>                          
                        </div>
                        <div class="form-check  me-3 me-lg-5">
                          <input class="form-check-input checkboxall" type="checkbox" name="is_update_{{$permission->id}}" value="{{$permission->id}}" id="{{$permission->id}}_Update" {{($hasAccess && $hasAccess->is_update==1) ? 'checked' : ''}}>
                          <label class="form-check-label" for="{{$permission->id}}_Update">Update</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input checkboxall" type="checkbox" name="is_delete_{{$permission->id}}" value="{{$permission->id}}" id="{{$permission->id}}_Delete" {{($hasAccess && $hasAccess->is_delete==1) ? 'checked' : ''}}>
                          <label class="form-check-label" for="{{$permission->id}}_Delete">Delete</label>
                        </div>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                

                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center">
            <button type="submit" class="submitBtn btn btn-primary me-sm-3 me-1">Submit</button>
            <button type="button" class="closeBtn btn btn-outline-secondary" data-bs-dismiss="modal">
					Close
				</button>
          </div>
        </form>
      </div>
    </div>
  </div>
