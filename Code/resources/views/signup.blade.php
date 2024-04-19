<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->

<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport"  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Sign Up - American Academy</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon_1.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"/>

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="/assets/vendor/css/pages/page-auth.css" />
    <link rel="stylesheet" href="/assets/js/sweetalert/sweetalert.css" />
    <!-- Helpers -->
    <script src="/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/assets/js/config.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <style>
      .parsley-required, .parsley-errors-list{
        color:red;
      }
      .hide{
        display:none;
      }
    </style>
  </head>

  <body>
    <!-- Content -->
    <div class="content-wrapper">
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <img src="/assets/img/user_img/Logo_2.png" style="width: 200px;">
              </div>
              <!-- /Logo -->
              <h4 class="mb-2">Register your organization here 🚀</h4>
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

              <form id="formSignUp" class="mb-3" action="signuppost" method="POST">
              @csrf
                <input type="hidden" name="contact_person_email" id="contact_person_email" value="">
                <div class="mb-3">
                  <label for="fname" class="form-label">First Name<span style="color:red;">*</span></label>
                  <input type="text" class="form-control validateTextOnly" id="firstname" name="firstname" placeholder="Enter your First Name" data-parsley-required-message="Please enter first name" style="text-transform: capitalize;" autofocus required />
                </div>
                <div class="mb-3">
                  <label for="fname" class="form-label">Last Name<span style="color:red;">*</span></label>
                  <input type="text" class="form-control validateTextOnly" id="lastname" name="lastname" placeholder="Enter your Last Name" data-parsley-required-message="Please enter last name" style="text-transform: capitalize;" autofocus required />
                </div>
                <div class="mb-3">
                  <label for="fname" class="form-label">Organization Name<span style="color:red;">*</span></label>
                  <input type="text" class="form-control" id="organization_name" name="organization_name" style="text-transform: capitalize;" placeholder="Enter Organization Name" data-parsley-required-message="Please enter organization name" autofocus required />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email<span style="color:red;">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" onblur="setContactPersonEmail(this)" data-parsley-required-message="Please enter email" autofocus required />
                </div>
                <div class="mb-3 form-password-toggle">
                <label class="form-label" for="password">Password<span style="color:red;">*</span></label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required data-parsley-errors-container="#password-errors" data-parsley-required-message="Please enter password"/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                  <div id="password-errors"></div>
                </div>
                <div class="mb-3" style="display: flex;justify-content: center;" data-parsley-group="block1">
                    <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_SITE_KEY_V2')}}" data-callback="callback"></div>
                </div>
                <div class="mb-3 mt-5">
                  <button id="submitBtn" class="btn btn-primary d-grid w-100" type="submit">Sign Up</button>
                </div>
                <div class="row mb-3">
                  <div class="col-md-6 d-flex justify-content-start">
                      <a href="/"><small>Back to Home</small></a>
                  </div>
                  <div class="col-md-6 d-flex justify-content-end">
                      <a href="/login"><small>Login</small></a>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      © <script>
        document.write(new Date().getFullYear())

      </script>
      by <a href="https://www.americansoftskillacademy.com/" target="_blank" class="footer-link fw-bolder">American softskill Academy</a>
    </div>
    <div class="hide">
      <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
      <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>
      <a href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/documentation/laravel-introduction.html" target="_blank" class="footer-link me-4">Documentation</a>
      <a href="https://themeselection.com/support/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
    </div>
  </div>
</footer>
</div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/assets/vendor/libs/popper/popper.js"></script>
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="/assets/js/sweetalert/sweetalert.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script src="/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>

    <script src="/assets/js/parsley.js"></script>
    <script>
      function callback() {
          var captchaLength = (grecaptcha.getResponse().length);
          //   alert(captchaLength)
          return captchaLength;

      }

      setTimeout(function() {
        const $recaptcha = document.querySelector('#g-recaptcha-response');
        if ($recaptcha) {
          $recaptcha.setAttribute('required', 'required');
        }
      }, 2000);
	  $('#formSignUp').parsley();
      $(document).on('submit','#formSignUp',function(){
            $('#submitBtn').prop('disabled', true);
            $('#submitBtn').text('Please wait...');
      });

    function setContactPersonEmail(elemObj){
      $('#contact_person_email').val($(elemObj).val());
    }
	  
	</script>
   
  </body>
</html>
