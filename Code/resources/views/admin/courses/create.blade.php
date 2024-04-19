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
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>				
				</div>
			</div>
		</div>    
		
		<form id="Addform" class="mb-3" action="{{route('saveCourse')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" id="courseId" name="id" value="{{$courseId}}">
			<div class="modal-body">
				
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Title<span style="color:red;">*</span></label>
						<input type="text" onkeydown="return /[a-z]/i.test(event.key) "class="form-control" name="title" placeholder="" value="{{$title}}" required />
					</div>
				</div>  
				<div class="row">
					<div class="col mb-3 ">
						<label class="form-label" for="name">Category<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="category_id" name="category_id" required>
							<option selected value="">Select Category</option>
							@foreach($courseCategories as $category)							
								<option value="{{$category->id}}" @if($category->id == $categoryId) selected @endif >{{$category->title}}</option>	
							@endforeach
						</select>
					</div>	
				</div>     
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Description</label>
						<textarea class="form-control" name="description" placeholder=""> {{$description}}</textarea>
					</div>
				</div>   

				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Sortorder<span style="color:red;">*</span></label>
						<input type="number" class="form-control" name="sortorder" placeholder="" value="{{$sortorder}}" required />
					</div>
				</div> 
				
				@if($courseId > 0)
					<div class="row">
						<div class="col mb-3">
							<label class="form-label" for="formFile">File<span style="color:red;">*</span></label>
							<input class="form-control" type="file" id="file_name" name="file_name" accept=".zip,.rar,.7zip">
						</div>		
					</div> 
				@else
					<div class="row">
						<div class="col mb-3">
							<label class="form-label" for="formFile">File<span style="color:red;">*</span></label>
							<input class="form-control" type="file" id="file_name" name="file_name" accept=".zip,.rar,.7zip" required>
						</div>		
					</div> 
				@endif
				 
				
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="formFile">Course Image</label>
						<input class="form-control" type="file" id="formFile" name="image_name" onchange="loadFile(event,this)">
					</div>		
				</div>    
								 
			</div>

			<img id="output" src="" style="width:293px;height:179px;margin-left: 30px;" class="justify-content-center align-items-center hide"/>
			
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