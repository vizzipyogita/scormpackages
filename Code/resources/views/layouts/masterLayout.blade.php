<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="@yield('pagetitle')" />
    <meta name="keywords" content="@yield('pagetitle')" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon_1.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"/>

    <!-- Include Styles -->
    @include('layouts/sections/styles')

    <!-- Page Styles -->
    @yield('pagestyles')

    <!-- Include Scripts for customizer, helper, analytics, config -->
    @include('layouts/sections/scriptsIncludes')


  </head>


<body>

 <!-- Layout wrapper -->
 <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        <!-- Menu -->
        @include('layouts/sections/menu/verticalMenu')
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">

          <!-- Navbar -->
          @include('layouts/sections/navbar/navbar')
          <!-- / Navbar -->

           <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
                @yield('content')
            <!--/ Content -->
            </div>
            <!-- Footer -->
            @include('layouts/sections/footer/footer')
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

  @php
      $errorMsg = "";
      $successMsg = "";
      if(session('error')){
          $errorMsg = session('error');
      }
      else if(session('success')){
          $successMsg = session('success');
      }
              
  @endphp

  <!-- Include Scripts -->
  @include('layouts/sections/scripts')

  @yield('afterscripts')

  <script>

    if($(".select2").length > 0)
      $('.select2').select2({allowClear: true });

    var errorMsg = "{{$errorMsg}}";
    var successMsg = "{{$successMsg}}";
    if(errorMsg!="")
    {
        
        swal("Error!", errorMsg, "error");
    }else if(successMsg!=""){
        //swal("", successMsg, "success");
        swal.fire({
          position: 'top-end',
          icon: 'success',
          title: successMsg,
          showConfirmButton: false,
          timer: 2000
        })
    }

    function ShowProgressAnimationLoader()
    {
      window.swal({
        title: "Please wait...",
        //text: "Please wait",
        imageUrl: "/assets/img/loading.gif",
        customClass: 'swal-wide',
        showConfirmButton: false,
        allowOutsideClick: false,
        //timer: 1500
      });	
    }

    function adminLogout(url){
        swal({
            title: "Are you sure?",
            text: "You want to logout?",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-primary",
            confirmButtonText: "Yes, Logout!",
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function(){
            window.location.href = url;     
        });
    }
  </script>

</body>

</html>
