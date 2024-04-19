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

		<form id="Importform" class="mb-3" action="{{route('userImportPost')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<div class="modal-body">
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">User Type<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="user_type" name="user_type" required>
							<option selected value="">Select User Type</option>
							<option value="A">Admin</option>	
							<option value="T">User</option>	
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
								<option value="{{$organization->id}}">{{$organization->organization_name}}</option>	
							@endforeach
						</select>
					</div>					
				</div> 
				@endif
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="formFile">Upload File</label>
						<input class="form-control" type="file" accept=".xls,.xlsx,.csv" id="formFile" name="file_name">
						<a href="/assets/SampleFiles/SampleUserImportFile.xlsx" style="float:right; margin-top:10px">Download Sample File</a>
					</div>		
				</div>   
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


