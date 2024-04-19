<!DOCTYPE html>
<html lang="en">

<head>
    <title>Forgot Password - American Academy</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

     <!-- Favicon -->
     <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon_1.png" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css//style.css" rel="stylesheet">
    <link href="/assets/css//responsive-user.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!---Navigation-->

    <div class="container-fluid login-section">
        <div class="row">
            <div class="col-md-7 p-0">
                <img class="img-fluid" src="\assets\img\user_img\login_banner.png">
            </div>
            <div class="col-md-5 login-form px-5 mt-5">
                <h1>Forgot Password? 🔒</h1>
                <h4>Enter your email and we'll send you instructions to reset your password</h4>

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

                <form class="row" action="{{route('userforgotpasswordpost')}}" method="post">
                    @csrf
                    <div class="col-md-12 mt-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" required id="email" name="email" placeholder="Email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="d-flex justify-content-end" style="width: 550px; margin-top: -30px;">
                            <a class="expo-normal-font" href="{{ route('userlogin') }}">Back to Login </a>
                        </div>
                    </div>

                    <div class="col-12 mt-2 text-center d-flex justify-content-center mb-3">
                        <button type="submit" class="btn btn-primary mt-4 login-btn d-flex justify-content-center align-items-center">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>