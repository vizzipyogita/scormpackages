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
			<div class="modal-body">
				<div class="row">
					@if($isUserPlan == 0)
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Plan Name</label>
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Monthly Amount</label>
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Yearly Amount</label>
						</div>
					@else
						<div class="col-8 mb-2">
							<label class="form-label" for="name">Plan Name</label>
						</div>
						<div class="col-4 mb-2">
							<label class="form-label" for="name">Yearly Amount</label>
						</div>
					@endif
				</div>    
				@foreach($plans as $plan)
					<div class="row">
						@if($isUserPlan == 0)
							<div class="col-4 mb-2">
								<label class="form-label" for="name">{{$plan->plan_name}}</label>
							</div>
							<div class="col-4 mb-2">
								<input type="text" class="form-control" value="{{$plan->monthly_plan_amount}}" onblur="changePlanAmount(this, '/plans/save/{{$plan->id}}', 'M')">
							</div>
							<div class="col-4 mb-2">
								<input type="text" class="form-control" value="{{$plan->yearly_plan_amount}}" onblur="changePlanAmount(this, '/plans/save/{{$plan->id}}', 'Y')">
							</div>
						@else
							@if($plan->country_id == $countryId)
								<div class="col-8 mb-2">
								<label class="form-label" for="name">{{$plan->plan_name}}</label>
								</div>
								<div class="col-4 mb-2">
								<input type="text" class="form-control" value="{{$plan->yearly_plan_amount}}" onblur="changePlanAmount(this, '/plans/save/{{$plan->id}}', 'Y')">
								</div>
							@endif
						@endif
					</div>    
				@endforeach
			</div>
		</form>

    </div>
</div>


