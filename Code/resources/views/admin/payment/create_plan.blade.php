<div class="modal-dialog modal-lg" role="document">
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

		<form id="Addform" class="mb-3" action="{{route('savePlan')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="isUserPlan" id="isUserPlan" value="{{$isUserPlan}}">
			<div class="modal-body">
				<div class="row">
					<div class="col-6 mb-3">
						<label class="form-label" for="name">Country<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="country_id" name="country_id" required>
							<option selected value="">Select Country</option>
							@foreach($countries as $country)
								@if(!in_array($country->id, $countryIds)) 
									<option value="{{$country->id}}">{{$country->title}}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div> 
				@if($isUserPlan == 0)
					<div class="row">
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Plan Name</label><br>
							<label class="form-label" for="name">50 Users<span style="color:red;">*</span></label>
							<input type="hidden" class="form-control" name="plan_name[]" value="50 Users">
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Monthly Amount<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="monthly_plan_amount[]" value="" required>
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Yearly Amount<span style="color:red;">*</span></label>
							<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
						</div>
					</div>      
					<div class="row">
						<div class="col-4 mb-2">
							<label class="form-label" for="name">100 Users<span style="color:red;">*</span></label>
							<input type="hidden" class="form-control" name="plan_name[]" value="100 Users">
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="monthly_plan_amount[]" value="" required>
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
						</div>
					</div>   

					<div class="row">
						<div class="col-4 mb-2">
							<label class="form-label" for="name">500 Users<span style="color:red;">*</span></label>
							<input type="hidden" class="form-control" name="plan_name[]" value="500 Users">
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="monthly_plan_amount[]" value="" required>
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
						</div>
					</div>   

					<div class="row">
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Unlimited Users<span style="color:red;">*</span></label>
							<input type="hidden" class="form-control" name="plan_name[]" value="Unlimited Users">
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="monthly_plan_amount[]" value="" required>
						</div>
						<div class="col-4 mb-2">
							<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
						</div>
					</div> 
				@else
					<div class="row">
						<div class="col-8 mb-2">
							<label class="form-label" for="name">Plan Name</label>
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Yearly Amount<span style="color:red;">*</span></label>
						</div>
					</div>  
					@foreach($categories as $category)
						<div class="row">
							<div class="col-8 mb-2">
								<label class="form-label" for="name">{{$category->title}}<span style="color:red;">*</span></label>
								<input type="hidden" class="form-control" name="plan_name[]" value="{{$category->title}}">
								<input type="hidden" class="form-control" name="category_id[]" value="{{$category->id}}">
								<input type="hidden" class="form-control" name="is_all_category[]" value="0">
							</div>
							<div class="col-4 mb-2">
								<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
							</div>
						</div>      
					@endforeach
						<div class="row">
							<div class="col-8 mb-2">
								<label class="form-label" for="name">All categories<span style="color:red;">*</span></label>
								<input type="hidden" class="form-control" name="plan_name[]" value="All categories">
								<input type="hidden" class="form-control" name="category_id[]" value="0">
								<input type="hidden" class="form-control" name="is_all_category[]" value="1">
							</div>
							<div class="col-4 mb-2">
								<input type="text" class="form-control" name="yearly_plan_amount[]" value="" required>
							</div>
						</div>     
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
<script>
// $('.datepicker').datepicker({
// 	format: 'yyyy/mm/dd',
// });

// $('.edatepicker').datepicker({
// 	format: 'yyyy-dd-mm',
// });
</script>


