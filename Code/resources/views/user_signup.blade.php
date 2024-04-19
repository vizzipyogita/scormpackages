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
                <h1>Let’s register here</h1>
                <h4>Welcome...</h4>

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

                <form class="row" id="formSignUp" action="{{route('userSignuppost')}}" method="post">
                    @csrf
                    <div class="col-md-6 mt-2">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" required id="firstname" name="firstname" placeholder="First Name">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" required id="lastname" name="lastname" placeholder="Last Name">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" required id="email" name="email" placeholder="Email">
                    </div>
                    <div class="col-md-6 mt-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" required id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group mt-4 mb-3" style="display: flex;justify-content: center;" data-parsley-group="block1">
                        <div class="g-recaptcha" data-sitekey="{{env('GOOGLE_CAPTCHA_SITE_KEY_V2')}}" data-callback="callback"></div>

                    </div>
                    <div class="col-12 mt-2 text-center d-flex justify-content-center mb-3">
                        <button type="submit" class="btn btn-primary mt-2 login-btn d-flex justify-content-center align-items-center">Sign Up</button>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 d-flex justify-content-start">
                            <a class="expo-normal-font" href="/"><small>Back to Home</small></a>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <a class="expo-normal-font" href="/user/login"><small>login</small></a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="/assets/vendor/libs/jquery/jquery.js"></script>
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

</script>
</body>

</html>