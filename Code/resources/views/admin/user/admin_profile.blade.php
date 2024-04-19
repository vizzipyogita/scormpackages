@extends('layouts.masterLayout')
@section('title', $pageTitle)

@section('pagestyles')

@endsection

@section('content')

   <!-- Content -->

   <div class="container-xxl flex-grow-1 container-p-y">
      <!-- Basic Layout -->
      <div class="row">
         <div class="col-xl">
         <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center"> 
               <div class="col">
                  <h5 class="mb-0">{{$pageTitle}}</h5>   
               </div>
               <div class="col">
                  <a href="javascript:void(0);" onclick="openChangePasswordModal('/users/changepassword/{{$loggedUserId}}')" class="btn btn-primary me-2" style="float: right;" title="Change Password">Change Password</a>
               </div>                 
            </div>

            @if(Session::has('error_message'))
               <div class="alert alert-danger alert-dismissible" role="alert">
                  {{Session::get('error_message')}}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
               
            @elseif(Session::has('success_message'))
               <div class="alert alert-success alert-dismissible" role="alert">
                  {{Session::get('success_message')}}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>                
            @endif

            <div class="card-body">
               <form id="Addform" class="mb-3" action="{{route('adminProfileUpdate')}}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" id="loggedUserId" name="id" value="{{$loggedUserId}}">
                  <div class="modal-body">
                     
                     <div class="row">
                        <div class="col mb-3">
                           <label class="form-label" for="name">First Name<span style="color:red;">*</span></label>
                           <input type="text" class="form-control validateTextOnly" name="firstname" data-parsley-required-message="Please enter first name" placeholder="" value="{{$firstname}}" required />
                        </div>
                        <div class="col mb-3">
                           <label class="form-label" for="name">Last Name<span style="color:red;">*</span></label>
                           <input type="text" class="form-control validateTextOnly" data-parsley-required-message="Please enter first name" name="lastname" placeholder="" value="{{$lastname}}" required />
                        </div>
                     </div>    
                     <div class="row">
                        <div class="col mb-3">
                           <label class="form-label" for="name">Email<span style="color:red;">*</span></label>
                           <input type="email" class="form-control" name="email" placeholder="" value="{{$email}}" disabled required />
                        </div>
                        <div class="col mb-3">
                           <label class="form-label" for="name">Mobile No.</label>
                           <input type="text" class="form-control" name="mobile_number" placeholder="" value="{{$mobile_number}}" />
                        </div>
                     </div>    
                     

                     <div class="row">
                        <div class="col-md-6 mb-3">
                           <label class="form-label" for="formFile">Photo</label>
                           <input class="form-control" type="file" id="formFile" name="image_name" onchange="loadFile(event,this)">
                        </div>	
                        
                     </div>  
                     
                     <img id="output" src="{{$filepath}}" style="width:100px;height:100px;" class="{{$filepath ? '' : 'hide'}} justify-content-center align-items-center"/>
                  </div>
                  
                  <div class="modal-footer">
                     <a type="button" class="btn btn-outline-secondary" href="/dashboard">Close</a>
                     <button type="submit" class="btn btn-primary me-2">Submit</button>
                  </div>
               </form>
            </div>
         </div>
         </div>               
      </div>
   </div>
   <!-- / Content -->
   
   <div class="modal fade" id="modal-change-password" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('afterscripts')


<script>
	 var loadFile = function(event, eleObj) {
         $("#output").removeClass('hide');
         var output = document.getElementById('output');
         output.src = URL.createObjectURL(event.target.files[0]);

         var FileUrl = output.src;
         $('#output').attr('src', FileUrl)
         
      };

   function openChangePasswordModal(url) {
      $.ajax({
         url: url,
         dataType: 'json',
         processData: false,
         cache : false,
         success: function(responseObj) {
               if (responseObj.status == "error") {   
                  swal("Error!", responseObj.message, "error");                   
               } else if (responseObj.status == "success") {
                  jQuery('#modal-change-password').html(responseObj.view).modal('show', { backdrop: 'static' });
                  $('#ChangePassword').parsley();
               }
         },
         // A function to be called if the request fails. 
         error: function(jqXHR, textStatus, errorThrown) {
               var responseObj = jQuery.parseJSON(jqXHR.responseText);            
         }
      });    
   }

   $(document).on("submit", "#ChangePassword", function(event) {
      event.preventDefault();
      var loggedUserId = $('#loggedUserId').val();
      $.ajax({
         url: '/users/changepassword/save/'+loggedUserId,
         type: $(this).attr('method'),
         data: new FormData(this),
         contentType:false,
         processData:false,
         dataType: "json",
         beforeSend: function() {
              
         },
         success: function(responseTxt, textStatus, jqXHR) {
               if (responseTxt.status == "success") {
                  $('.closeBtn').trigger('click');
                  swal("Success!", "Updated", "success");  
               } else if (responseTxt.status == "error") {
                  $('#ErrorDiv').removeClass('hide');
                  $('#errorMsg').text(responseTxt.message);
                  
               }            
         },
         error: function(error) {
             
         }
      });
   });
</script>
@endsection

