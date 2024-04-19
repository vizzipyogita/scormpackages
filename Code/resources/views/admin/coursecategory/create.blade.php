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

		<form id="AddCategoryform" class="mb-3" action="{{route('saveCourseCategory')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" id="courseCategoryId" name="id" value="{{$courseCategoryId}}">
			<div class="modal-body">
				
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Title<span style="color:red;">*</span></label>
						<input type="text" onkeydown="return /[a-z]/i.test(event.key)" style="text-transform:capitalize;" maxlength="15" class="form-control" name="title" placeholder="" value="{{$title}}" required />
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



