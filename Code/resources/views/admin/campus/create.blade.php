<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">{{$pageTitle}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
		<form id="Addform" class="mb-3" action="{{route('saveCampus')}}" method="POST">
			@csrf
			<input type="hidden" id="campusId" name="id" value="{{$campusId}}">
			<div class="modal-body">
				
					@if($campusId>0)
					<div class="row">
						<div class="col mb-3">
							<label class="form-label" for="name">Name<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="name" placeholder="" value="{{$name}}" required />
						</div>
					</div>          
					@else
					<div class="row">
						<div class="col-md-10">
							<label class="form-label" for="name">Name<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="name[]" placeholder="" value="" required />
						</div>
						<div class="col-md-2">
							<button type="button" id="AddCampus" class="btn btn-sm btn-outline-primary btn-fw" style="margin-top:33px;" onclick="AddCampusRow();">Add</button>
						</div>
					</div>     

					<div id="CampusArray"></div>		
					@endif
				 
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
