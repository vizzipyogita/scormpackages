<!----------Navbar Start----------->
<nav class="navbar navbar-expand-lg navbar-light bg-dark px-5">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="/assets/img/user_img/Logo_1.png" style="width: 200px;">
            </a>
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item mr-5">
                        <a class="nav-link active" aria-current="page" title="Home" href="/user/dashboard">Home</a>
                    </li>
                    <li class="nav-item mr-5">
                        <a class="nav-link" href="/user/history" title="History">History</a>
                    </li>
                    @if(Session::get('isLoginFromIsmailiChamber') != 1)
                        <li class="nav-item mr-5">
                            <a class="nav-link" href="/user/profile/T" title="Profile">Profile</a>
                        </li>
                        <li class="nav-item mr-5">
                            <a class="nav-link" onclick="userLogout('/user/logout')" href="javascript:void(0);" title="Logout">Logout</a>
                        </li>
                    @endif
                    <li style="display:none">
                        <button class="btn register-btn d-flex align-items-center justify-content-center ">
                            Register<img class="ml-5" src="/assets/img/user_img/arrow.png">
                        </button>
                    </li>

                </ul>

            </div>
        </div>
    </nav>

    <!----------Navbar End----------->
