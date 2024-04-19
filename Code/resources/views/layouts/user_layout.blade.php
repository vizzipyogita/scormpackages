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

    <!-- Include Styles -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/loader.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/js/sweetalert/sweetalert.css" />
   
    <script src="/assets/vendor/js/bootstrap.js"></script>
    <script src="/assets/js/jquery-1.11.1.min.js"></script>
    <script src="/assets/js/helper.js"></script>
    <script src="/assets/js/parsley.js"></script>
    @yield('pagestyles')

  </head>

<body>

@include('.layouts.sections.navbar.user_navbar')
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
    @yield('content')
    @yield('pageScripts')
    @include('layouts.sections.footer.user_footer')
    <script src="/assets/js/sweetalert/sweetalert.js"></script>
    <script>
        function userLogout(url){
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
