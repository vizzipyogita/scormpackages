@extends('layouts.user_layout')
@section('title', 'User Profile')

@section('pagestyles')
<link href="/assets/css/style-user.css" rel="stylesheet">
<link href="/assets/css/responsive-user.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>


<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/js/sweetalert/sweetalert.css" />

<style>
   .form-control {
      font-size: 17px;
      font-weight: 400;
      width: 100% !important;
      height: 67px;
      margin-bottom: 20px!important;
      border-radius: 197px;
   }
   .form-control[type="file"] {
      height: 38px;
   }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y mt-5">
   <div class="row">
      <div class="col mb-3">
      </div>
      <div class="col mb-3">
         <a href="javascript:void(0);" onclick="openChangePasswordModal('/user/changepassword/{{$loggedUserId}}')" class="btn learn-btn d-flex align-items-center justify-content-center mt-4" style="float: right;" title="Change Password">Change Password</a>
      </div>
   </div> 
   <div class="card">
      <div class="card-datatable table-responsive">
         <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <div class="card-header flex-column flex-md-row">
               <div class="head-label">
                  <h5 class="card-title mb-0">Edit Profile</h5>
               </div>
            </div>
            <div class="container">
               <div class="row">
                  <div class="col-xs-12">
                     <form id="Addform" class="mb-3" action="{{route('userProfileUpdate')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="loggedUserId" name="id" value="{{$loggedUserId}}">
                        <div class="modal-body">
                           
                           <div class="row">
                              <div class="col mb-3">
                                 <label class="form-label" for="name">First Name<span style="color:red;">*</span></label>
                                 <input type="text" class="form-control" name="firstname" placeholder="" value="{{$firstname}}" required />
                              </div>
                              <div class="col mb-3">
                                 <label class="form-label" for="name">Last Name<span style="color:red;">*</span></label>
                                 <input type="text" class="form-control" name="lastname" placeholder="" value="{{$lastname}}" required />
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
                           <a type="button" class="closeBtn btn btn-outline-secondary" href="/user/dashboard">Close</a>
                           <button type="submit" class="submitBtn btn learn-btn d-flex align-items-center justify-content-center">Submit</button>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="modal fade" id="modal-change-password" data-keyboard="false" data-backdrop="static"></div>

@endsection
@section('pageScripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js "></script>
<script src="/assets/js/sweetalert/sweetalert.js"></script>
<script src="/assets/js/parsley.js"></script>

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
         url: '/user/changepassword/save/'+loggedUserId,
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
