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

		<form id="Addform" class="mb-3" action="{{route('saveCoupons')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" id="couponId" name="id" value="{{$couponId}}">
			<div class="modal-body">
				
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Coupon Code<span style="color:red;">*</span></label>
						<input type="text" class="form-control" name="coupon_code" placeholder="" readonly value="{{$couponCode}}" required />
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">Discount Type<span style="color:red;">*</span></label>
						<select class="form-select select2" aria-label="Default select example" id="discount_type" name="discount_type" required>
							<option selected value="">Select Discount Type</option>
							<option value="1" @if($discountType == "1") selected @endif >Free</option>	
							<option value="2" @if($discountType == "2") selected @endif>%</option>	
							<option value="3" @if($discountType == "3") selected @endif>USD OFF</option>	
						</select>
					</div>
				</div>    
				<div class="row" id="couponDetails">
					<div class="col mb-3">
						<label class="form-label" for="name">Discount<span style="color:red;">*</span></label>
						<input type="text" class="form-control validateOnlynumbers"  maxlength="5"  name="discount" placeholder="" value="{{$discount}}" required />
					</div>
					<div class="col mb-3">
						<label class="form-label" for="name">No of Redemption<span style="color:red;">*</span></label>
						<input type="text"  class="form-control validateOnlynumbers" maxlength="5" name="no_of_redemption" placeholder=""  value="{{$noOfRedemption}}" required/>
					</div>
				</div>    
				<div class="row">
					<div class="col mb-3">
						<label class="form-label" for="name">Start Date<span style="color:red;">*</span></label>
						<div class="input-group date" data-provide="datepicker" >
							<!-- <input type="text" class="form-control sdatepicker" placeholder="mm/dd/yyyy" name="start_date" value="{{$startDate}}" required data-parsley-errors-container="#sdate-errors"> -->
							<input class="form-control" type="date" id="html5-date-input" name="start_date" value="{{$startDate}}" required data-parsley-errors-container="#sdate-errors"/> 	
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<div id="sdate-errors"></div>
					</div>	
					<div class="col mb-3">
						<label class="form-label" for="name">End Date<span style="color:red;">*</span></label>
						<div class="input-group date" data-provide="datepicker" >
							<!-- <input type="text" class="form-control edatepicker" placeholder="mm/dd/yyyy" name="end_date" value="{{$endDate}}" required data-parsley-errors-container="#edate-errors"> -->
							<input class="form-control" type="date" id="html5-date-input" name="end_date" value="{{$endDate}}" required data-parsley-errors-container="#edate-errors"/> 	
							<div class="input-group-addon">
								<span class="glyphicon glyphicon-th"></span>
							</div>
						</div>
						<div id="edate-errors"></div>
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
    <script src="/assets/js/custom.js"></script>

<script>
// $('.datepicker').datepicker({
// 	format: 'yyyy/mm/dd',
// });

// $('.edatepicker').datepicker({
// 	format: 'yyyy-dd-mm',
// });
</script>


