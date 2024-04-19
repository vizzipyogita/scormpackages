@extends('layouts.masterLayout')
 
@section('title', 'Dashboard')
 
@section('pagestyles')
<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="/assets/js/magnific-popup/magnific-popup.css">
@endsection

@section('content')
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
                <div class="col-lg-12 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-12">
                        <div class="card-body">
                          <h5 class="card-title text-primary">{{ Session::get('LoggedUserOrganizationName') }} 🎉
                            <a class="popup-video float-end" href="https://www.youtube.com/watch?v=A5DveGspdkk">
                              Help Video
                            </a>
                          </h5>
                          <p class="mb-4">
                            Welcome to <span class="fw-bold">LMS</span>
                          </p>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left hide">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="../assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="col-lg-8 col-md-8 order-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                      <a href="/users">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/cc-primary.png" alt="chart success" class="rounded" />
                              </div>
                            </div>
                            <span class="fw-semibold d-block mb-1" style="color: #758496;">Users</span>
                            <h3 class="card-title mb-2">{{ $usersCount }}</h3>
                          </div>
                        </div>
                      </a>
                    </div>
                    @if($loggedUserOrganizationId == 1)
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                      <a href="/organizations">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded" />
                              </div>
                            </div>
                            <span class="fw-semibold d-block mb-1" style="color: #758496;">Organizations</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $organizationCount }}</h3>
                          </div>
                        </div>
                      </a>
                    </div>
                    @endif
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                      <a href="/courses">
                        <div class="card">
                          <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                              <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/wallet.png" alt="Credit Card" class="rounded" />
                              </div>
                            </div>
                            <span class="fw-semibold d-block mb-1" style="color: #758496;">Courses</span>
                            <h3 class="card-title text-nowrap mb-1">{{ $courseCount }}</h3>
                          </div>
                        </div>
                      </a>
                    </div>
                  </div>
                </div>
            </div>
            <!-- / Content -->

@endsection

@section('afterscripts')
    <script src="/assets/js/magnific-popup/jquery.magnific-popup.min.js"></script>
    <!-- Vendors JS -->
    <script src="/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <script src="/assets/js/main.js"></script>
    <!-- Page JS -->
    <script src="/assets/js/dashboards-analytics.js"></script>

    <script>
      $('.popup-video').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,

        fixedContentPos: false
      });
    </script>
@endsection
