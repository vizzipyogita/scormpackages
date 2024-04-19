<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">{{$pageTitle}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

		<div class="row hide" id="ErrorDiv" style="padding:10px;">
			<div class="col">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<span id="errorMsg"></span>					
				</div>
			</div>
		</div>       

		<form id="Addform" class="mb-3" action="{{route('saveUser')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" id="systemUserId" name="id" value="{{$systemUserId}}">
			<div class="modal-body">
				
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">First Name<span style="color:red;">*</span></label>
						<input type="text" class="form-control" onkeydown="return /[a-z]/i.test(event.key)" style="text-transform:capitalize;" name="firstname" placeholder="" value="{{$firstname}}" required />
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">Last Name<span style="color:red;">*</span></label>
						<input type="text" class="form-control" name="lastname" placeholder="" onkeydown="return /[a-z]/i.test(event.key)" style="text-transform:capitalize;" value="{{$lastname}}" required />
					</div>
				</div>    
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Email<span style="color:red;">*</span></label>
						<input type="email" class="form-control" name="email" placeholder="" value="{{$email}}" required />
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">Mobile No.</label>
						<input type="text" class="form-control" name="mobile_number" placeholder="" value="{{$mobile_number}}" onblur="validateMobileNumber(this)" />
						<span class="error-mobile" style="color:red;"></span>
					</div>
				</div>    
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">User Type<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="user_type" name="user_type" required>
							<option selected value="">Select User Type</option>
							<option value="A" @if($user_type == "A") selected @endif >Admin</option>	
							<option value="T" @if($user_type == "T") selected @endif>User</option>	
						</select>
					</div>	
					<div class="col mb-3 hide">
						<label class="form-label" for="name">Role<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="role_id" name="role_id">
							<option selected value="">Select Role</option>
							@foreach($roles as $role)							
								<option value="{{$role->id}}" @if($role->id == $role_id) selected @endif >{{$role->name}}</option>	
							@endforeach
						</select>
					</div>					
				</div>  
				@if($loggedUserOrganizationId == 1)
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Organization</label>
						<select class="form-select select2" aria-label="Default select example" id="organization_id" name="organization_id">
							<option selected value="">Select Organization</option>
							@foreach($organizations as $organization)							
								<option value="{{$organization->id}}" @if($organization->id == $organization_id) selected @endif >{{$organization->organization_name}}</option>	
							@endforeach
						</select>
					</div>					
				</div> 
				@endif

				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Address</label>
						<textarea class="form-control" name="address" placeholder=""> {{$address}}</textarea>
					</div>
				</div>  
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">City</label>
						<input type="text" class="form-control" name="city" placeholder="" value="{{$city}}" />
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">Zip Code</label>
						<input type="text" class="form-control" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" name="zip" placeholder="" value="{{$zip}}" />
					</div>
				</div> 
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Country</label>
						<select class="form-select select2" aria-label="Default select example" id="country_id" name="country_id" onchange="getStates(this)">
							<option selected value="">Select Country</option>
							@foreach($countries as $country)							
								<option value="{{$country->id}}" @if($country->id == $countryId) selected @endif >{{$country->title}}</option>	
							@endforeach
						</select>
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">State</label>
						<select class="form-select select2" aria-label="Default select example" id="state_id" name="state_id">
							<option selected value="">Select State</option>
						</select>
					</div>
				</div>    

				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="formFile">Photo</label>
						<input class="form-control" type="file" id="formFile" name="image_name" onchange="loadFile(event,this)">
					</div>		
				</div>    
				
				<img id="output" src="{{$filepath}}" style="width:100px;height:100px;" class="{{$filepath ? '' : 'hide'}} justify-content-center align-items-center"/>
					
				 
			</div>
			
			<div class="modal-footer">
				<button type="button" class="closeBtn btn btn-outline-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" class="submitBtn btn btn-primary">Submit</button>
			</div>
		</form>

    </div>
</div>

<script>
	 var loadFile = function(event, eleObj) {
            $("#output").removeClass('hide');
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);

            var FileUrl = output.src;
            $('#output').attr('src', FileUrl)
           
        };
</script>


