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

    <title>Welcome - American Academy</title>

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
    <!-- Helpers -->
    <script src="/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="/assets/js/config.js"></script>
    <style>
      .parsley-required{
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
        <div class="container-xxl flex-grow-1 container-p-y" style="margin-top:5%">
            <div class="row">
                <div class="app-brand justify-content-center">
                    <img src="/assets/img/user_img/Logo_2.png" style="width: 200px;">
                </div>
                <div class="app-brand justify-content-center">
                    <p class="mb-4">If you already our customer then login specific role to start. If you not our customer click on sign up and get free account.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="mb-3 bx bx-md bx-user"></i>
                            <h5>User Login</h5>
                            <p>Click to login user.</p>
                            <a href="\user\login" class="btn btn-primary"> Login </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="mb-3 bx bx-md bx-user"></i>
                            <h5>Organization Admin Login</h5>
                            <p>Click to organization admin login.</p>
                            <a href="\login" class="btn btn-primary"> Login </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-sm-6 col-lg-4 mb-4">
                    <div class="card">
                    <div class="card-body text-center">
                        <i class="mb-3 bx bx-md bx-buildings"></i>
                        <h5>Sign Up for Organization</h5>
                        <p> Sign Up here new organization.</p>
                        <a href="\signup" class="btn btn-primary"> Sign Up </a>
                    </div>
                    </div>
                </div>
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

    <script src="/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>

    <script src="/assets/js/parsley.js"></script>
    <script>
	  $('#formAuthentication').parsley();

      $(document).on('submit','#formAuthentication',function(){
            $('#submitBtn').prop('disabled', true);
            $('#submitBtn').text('Please wait...');
      });
	  
	</script>
   
  </body>
</html>
