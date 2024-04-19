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

		<form id="ChangePassword" class="mb-3" action="{{route('saveChangePassword')}}" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" id="systemUserId" name="id" value="{{$systemUserId}}">
			<div class="modal-body">
				<div class="row">
					<div class="col mb-6">
						<label class="form-label" for="name">New Password<span style="color:red;">*</span></label>
						<input type="password" maxlength="6" data-parsley-maxlength="6" minlength="6" data-parsley-minlength="6"   data-parsley-regexp="^\(?(?:\+?61|0)4\)?(?:[ -]?[0-9]){2}\)?(?:[ -]?[0-9]){5}[0-9]$"  id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
					</div>
				</div>   
				<div class="row">
					<div class="col mb-6">
						<label class="form-label" for="name">Confirm Password<span style="color:red;">*</span></label>
						<input type="password" id="confirm_password" maxlength="6" data-parsley-maxlength="6"  minlength="6" data-parsley-minlength="6" data-parsley-regexp="^\(?(?:\+?61|0)4\)?(?:[ -]?[0-9]){2}\)?(?:[ -]?[0-9]){5}[0-9]$" class="form-control show_password" name="confirm_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
					</div>
				</div>   
			</div>
			
			<div class="modal-footer">
				<button type="button" class="closeBtn btn btn-outline-secondary" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" class="changePasswordsubmitBtn btn btn-primary">Submit</button>
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

		$("#confirm_password").blur(function(){
			var newPass = $('#password').val();
			var confirmPass = $(this).val();

			if(newPass != confirmPass){
				swal("Error!", "Password not match", "error");
				$(this).val('');
			}
		});

</script>


