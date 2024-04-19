<!DOCTYPE html>
<html lang="en">

<head>
    <title>E-learning User Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon/favicon_1.png" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css//style.css" rel="stylesheet">
    <link href="/assets/css//responsive-user.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <style>
      .parsley-required{
        color: red;
      }
    </style>
</head>

<body>

    <!---Navigation-->

    <div class="container login-section">
        <div class="row">
            <div class="col-md-7 p-0">
                <div class="d-flex justify-content-center">
                    <img class="img-fluid" style="height:710px" src="\assets\img\user_img\login_banner.png">
                </div>
            </div>
            <div class="col-md-5 login-form px-5 mt-2">
                <h1>Let’s sign you in</h1>
                <h4>Welcome Back</h4>

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

                <form class="row" id="formAuthentication" action="{{route('userloginpost')}}" method="post">
                    @csrf
                    <div class="col-md-12 mt-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" required id="email" name="email" placeholder="Email">
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" required id="password" name="password" placeholder="Password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="d-flex justify-content-end mb-4 mt-2" style="margin-top: -30px;">
                            <a class="expo-normal-font" href="{{ route('userforgotpassword') }}">Forgot Password </a>
                        </div>
                    </div>
                    <div class="form-group " style="display: flex;justify-content: center;" data-parsley-group="block1">
                        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_SITE_KEY_V2')}}" data-callback="callback"></div>

                    </div>
                    <div class="col-12 mt-5 text-center d-flex justify-content-center mb-3">
                        <button type="submit" class="btn btn-primary mt-2 login-btn d-flex justify-content-center align-items-center">Log In</button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex justify-content-start">
                            <a class="expo-normal-font" href="/"><small>Back to Home</small></a>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a class="expo-normal-font" href="/user/signup"><small>Sign Up</small></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/assets/js/parsley.js"></script>
<script>
   
</script>
</body>

</html>