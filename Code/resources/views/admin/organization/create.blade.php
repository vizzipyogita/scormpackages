@extends('layouts.masterLayout')
@section('title', $page_title)

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
                      <h5 class="mb-0">{{$page_title}}</h5>                     
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
                      <form id="Addform" class="mb-3" action="{{route('saveOrganization')}}" method="POST">
                      @csrf
                        <input type="hidden" id="organizationId" name="id" value="{{$organizationId}}">
                        <div class="row mb-3">
                           <div class="col">
                              <label class="form-label" for="name">Organization Name<span style="color:red;">*</span></label>
                              <input type="text" maxlength="25" class="form-control" onkeydown="return /[a-z]/[0-9]/i.test(event.key)" style="text-transform:capitalize;" id="name" name="organization_name" placeholder="" value="{{$name}}" required />
                          </div>
                          <div class="col">
                              <label class="form-label" for="code">Organization Code<span style="color:red;">*</span></label>
                              <input type="text" maxlength="25" class="form-control" onkeydown="return /[a-z]/[0-9]/i.test(event.key)" style="text-transform:capitalize;" id="code" name="organization_code" placeholder="" value="{{$code}}" required/>
                          </div>
                        </div>
                        <div class="row mb-3">
                           <div class="col">
                              <label class="form-label" for="email">Organization Email<span style="color:red;">*</span></label>
                              <div class="input-group input-group-merge">
                                 <input type="email" data-parsley-errors-container="#error_email" id="email" name="email" value="{{$email}}" class="form-control" placeholder="john.doe" aria-label="john.doe" required/>
                                 <span class="input-group-text" id="basic-default-email2">@example.com</span>
                              </div>
                              <div id="error_email"></div>            
                              <!-- <div class="form-text">You can use letters, numbers & periods</div> -->
                           </div>

                           <div class="col">
                              <label class="form-label" for="phone_number">Organization Phone No.</label>
                              <input type="text" id="phone_number" maxlength="11" onkeypress="return validateMobileNumber(event)"  name="phone_number" class="form-control phone-mask validateOnlynumbers" value="{{$phone_number}}" placeholder=""/>
                           </div>
                        </div>
                        <div class="row mb-3">
                           <div class="col">
                              <label class="form-label" for="contact_person_name">Contact Person Name<span style="color:red;">*</span></label>
                              <input type="text" maxlength="25"  onkeydown="return /[a-z]/i.test(event.key)" style="text-transform:capitalize;" id="contact_person_name" name="contact_person_name" class="form-control" value="{{$contact_person_name}}" placeholder="" required/>
                           </div>
                           <div class="col">
                              <label class="form-label" for="contact_person_email">Contact Person Email<span style="color:red;">*</span></label>
                              <div class="input-group input-group-merge">
                                 <input type="email" data-parsley-errors-container="#error_contact_person_email" value="{{$contact_person_email}}" id="contact_person_email" name="contact_person_email" class="form-control" placeholder="john.doe" aria-label="john.doe" required/>
                                 <span class="input-group-text" id="basic-default-email2">@example.com</span>
                              </div>
                              <div id="error_contact_person_email"></div>             
                              <!-- <div class="form-text">You can use letters, numbers & periods</div> -->
                           </div>
                        </div>
                        <div class="mb-3">
                           <div class="col-md-6">
                              <label class="form-label" for="basic-default-message">About Organization</label>
                              <textarea id="basic-default-message" class="form-control" placeholder=""></textarea>
                           </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <a href="{{route('organizations')}}" class="btn btn-outline-secondary">Cancel</a>
                          </div>
                     
                      </form>
                    </div>
                  </div>
                </div>               
              </div>
            </div>
            <!-- / Content -->

@endsection

@section('afterscripts')

    <script src="/assets/js/custom.js"></script>

<script>
   $(document).ready(function() 
   {

      //Active Left Menu
      $(".menu-item").removeClass("active");
      $(".organizations").addClass("active");

      //Parsley Validation
      $('#Addform').parsley().on('field:validated', function() {
         var ok = $('.parsley-error').length === 0;
      })
      .on('form:submit', function() {
         ShowProgressAnimationLoader();
         return true; // Don't submit form for this demo
      });

      //

   });
   
</script>
@endsection
