@extends('layouts.user_layout')
@section('title', 'Dashboard')

@section('pagestyles')
<link href="/assets/css/style-user.css" rel="stylesheet">
<link href="/assets/css/responsive-user.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="/assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="/assets/css/ratingcss.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/js/magnific-popup/magnific-popup.css">
<style>
    #page-overlay{
        position:fixed;
        z-index: 10000;
    }
    .rating-star span{
        color:#e59819 !important;
    }
    .btn-view-all{
        color:#e59819 !important;
        display: flex;
        justify-content: end;
    }

    .administrative-card .card-footer{
        padding: 0.5rem 1rem;
        padding-bottom: 10px !important;
        background-color: rgb(255 255 255 / 3%) !important;
        border-top: none !important;
    }

</style>
@endsection

@section('content')

<!----------Banner start---------->
<section class="">
    <div class="container-fluid p-0">
        <div class="banner">
            <div class="row justify-content-center">
                <div class="banner-content">
                    <div class="col-md-7">
                        @if($user->is_guest == 1)
                            @if($user->is_subscription == 1)
                                @if($subscribedCategoryCount < count($courseCategories))
                                    <div class="free-trial-div">Please click to <span class="blink_me">Pay Now </span>to access all courses.</div>
                                @endif
                            @else
                                @if($freeTrialDays <= 5 && $freeTrialDays != 0)
                                    <div class="free-trial-div">Your free trial will expire within <span class="blink_me">{{$freeTrialDays}} Days. </span><br>Please click to pay now to access all courses.</div>
                                @else
                                    <div class="free-trial-div">Your free trial has been <span class="blink_me">Expired. </span><br>Please click to pay now to access all courses.</div>
                                @endif
                            @endif
                        @endif
                        <p class="sub-heading">Acquire Corporate Customized Training Courses</p>
                        <h2 class="banner-text">Welcome to <strong>American</strong> Academy</h2>
                        <p class="sub-heading">Acquire Corporate Customized Training Courses</p>
                        <a class="btn learn-btn d-flex align-items-center justify-content-center btnSearch popup-video" href="https://www.youtube.com/watch?v=org1H20Muvs">
                            Help Video
                        </a>
                        @if($user->is_guest == 1)
                            <a class="btn learn-btn d-flex align-items-center justify-content-center btnSearch" style="margin-left: 170px; margin-top: -53px;" href="/user/checkout">
                                Pay Now
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" style="display:none">
        <div class="carousel-inner ">
            <div class="carousel-item active">
                <div class="row banner-content">
                    <div class="col-md-7">
                        <h2 class="banner-text">Welcome to <strong>American</strong> academy</h2>
                        <p class="sub-heading">E- Learning Programme by travelBiz Monitor</p>
                        <button class="btn learn-btn" style="display:none">
                            Learn More<img class="ml-5" src="/assets/img/user_img/arrow.png">
                        </button>
                    </div>
                    <div class="col-md-5">
                        <img class="img-fluid" src="/assets/img/user_img/Banner1_img.png">
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row banner-content">
                    <div class="col-md-7">
                        <h2 class="banner-text">Welcome to <strong>American</strong> academy</h2>
                        <p class="sub-heading">E- Learning Programme by travelBiz Monitor</p>
                        <button class="btn learn-btn d-flex align-items-center justify-content-center ">
                            Learn More<img src="/assets/img/user_img/arrow.png">
                        </button>
                    </div>
                    <div class="col-md-5">
                        <img class="img-fluid" src="/assets/img/user_img/Banner1_img.png">
                    </div>
                </div>
            </div>
            <div class="carousel-item">
                <div class="row banner-content">
                    <div class="col-md-7">
                        <h2 class="banner-text">Welcome to <strong>American</strong> academy</h2>
                        <p class="sub-heading">E- Learning Programme by travelBiz Monitor</p>
                        <button class="btn learn-btn d-flex align-items-center justify-content-center ">
                            Learn More<img src="/assets/img/user_img/arrow.png">
                        </button>
                    </div>
                    <div class="col-md-5">
                        <img class="img-fluid" src="/assets/img/user_img/Banner1_img.png">
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-4" style="display:none">
            <button class="carousel-control-prev banner-prev pb-5 " type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <img src="/assets/img/user_img/previous.png" class="prev-icon" width="50">
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next banner-next pb-5" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <img src="/assets/img/user_img/next.png" class="next-icon" width="50">
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div> -->
</section>
<!----------Banner End---------->

<!---------------->

<section class="mt-5" >
    <div class="container">
        <div class="row">
            @if($inprogressCourse)
                @php 
                    $courseCategoryTitle = $inprogressCourse->Category->title;
                @endphp
                <div class="col-md-6">
                    <a href="/user/history?isInprogress=1">
                        <h3 class="saved-title">In progress ({{$inprogressCoursesCount}})</h3>
                        <div class="progress-card  mb-3">
                            <div class="row g-0 mt-4">
                                <div class="col-md-6">
                                    <img src="/assets/img/user_img/card.png" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-6 h-100">
                                    <div class="card-body">
                                        <h5 class="progress-card-title">{{$courseCategoryTitle}}</h5>
                                        <p class="progress-card-text">
                                            {{$inprogressCourse->title}}
                                        </p>
                                        <span class="d-flex align-items-center">

                                            <div class="progress mr-5">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 70%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"> </div>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            @if($favoriteCourse)
                @php 
                    $courseCategoryTitle = $favoriteCourse->Category->title;
                @endphp
                <div class="col-md-6">
                    <a href="/user/history">
                        <h3 class="saved-title">Favorite ({{$favoriteCoursesCount}})</h3>
                        <div class=" saved-card mb-3">
                            <div class="row g-0 mt-4">
                                <div class="col-md-6">
                                    <img src="/assets/img/user_img/card2.png" class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-6 ">
                                    <div class="card-body">
                                        <h5 class="progress-card-title">{{$courseCategoryTitle}}</h5>
                                        <p class="progress-card-text">
                                            {{$favoriteCourse->title}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
<!---------------->


<!------------------->

<!------Category filter------>
<div class="container">
    <div class="row mt-5">
        <div class="col-sm">
            <select class="form-select select2" aria-label="Default select example" id="category_id" name="category_id" required>
                <option selected value="">Select Category</option>
                @foreach($courseCategories as $category)	
                    @php 
                        $selected = '';
                        $availableCourses = $category->getCategoryCourses();
                        if($categoryId == $category->id){
                            $selected = 'selected';
                        }
                    @endphp
                    @if(count($availableCourses))						
                        <option value="{{$category->id}}" {{ $selected }}>{{$category->title}}</option>	
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-sm">
            <input type="text" class="form-control" id="searchText" name="title" value="{{$title}}">
        </div>
        <div class="col-sm">
            <a class="btn learn-btn d-flex align-items-center justify-content-center btnSearch" onclick="applyFilter()" style="height: 39.58px;">Go</a>
        </div>
    </div>
</div>

@if(count($courseCategories))
    @php 
        $count = 1;
    @endphp
    @foreach($courseCategories as $category)
    @php 
        if($categoryId != $category->id && $categoryId != 0){
            continue;
        }
        $courses = $category->getCategoryCourses($title, 5);
        $courseCount = count($courses);
        $isSubscribedCategory = $category->checkIsSubscribedCategory($category->id, $loggedUserId);
        
        $isGuestUser = $user->is_guest;
        $disabledClass = '';
        if($isGuestUser==1){
            if($freeTrialDays == 0 || $freeTrialDays > 5){
                $disabledClass = 'disabled-category';
            }else{
                if($isSubscribedCategory == 1 || $freeTrialDays <= 5){
                    $disabledClass = '';
                }else{
                    $disabledClass = 'disabled-category';
                }
            }

            if($isSubscribedCategory == 1){
                $disabledClass = '';
            }
        }
    @endphp
    @if($categoryId == $category->id || $categoryId == 0)
        @if(count($courses))
            <section class=" mt-5">
                <div class="container">
                    <div class=" d-flex justify-content-between align-items-center mb-5">
                        <h3 class="heading">{{ $category->title }}</h3>
                        @if(count($courses))
                        <div class="bx-controls bx-has-controls-direction">
                            <div class="bx-controls-direction">
                                @if($isGuestUser == 0)
                                    <a class="bx-prev{{$count}}" href=""></a> &nbsp;
                                    <a class="bx-next{{$count}}" href=""></a>
                                @else
                                    @if($isSubscribedCategory == 0)
                                        <a class="btn-user-pay" href="/user/checkout?categoryId={{ $category->id }}">Pay Now</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <main class="ext {{$disabledClass}}">
                        @if($courseCount > 4)
                            <div class="col-sm">
                                <a href="/user/category/{{ $category->id }}/viewall" class="btn-view-all" target="_blank" style="height: 39.58px;">View All</a>
                            </div>
                        @endif

                        <div class="bxslider{{$count}}  d-flex justify-content-center">
                            @if(count($courses))
                                @foreach($courses as $course)
                                @php 
                                    $courseImg = $course->getCourseImage($course->id, $course->image_name);
                                    $courseRatingCount = $course->getCourseRatingCount($course->id);
                                    $courseRatingAvg = $course->getCourseCalculatedRatings($course->id);
                                    $isFavorite = $course->CheckFavoriteCourse($course->id, $loggedUserId); 
                                @endphp
                                <div class="administrative-slider">
                                    <div class=" administrative-card ">
                                        <a href="/user/course/{{$course->id}}/play" target="_blank">
                                            <img src="{{ $courseImg }}" class="img-fluid" alt="...">
                                        </a>
                                        <div class="card-body">
                                            <a href="/user/course/{{$course->id}}/play" target="_blank">
                                                <h5 class="card-title administrative-title">{{$course->title}}</h5>
                                            </a>
                                            <h6 class="author mt-3" style="display:none">By:Martian Boyle</h6>
                                        </div>
                                        <div class="card-footer">
                                            <a class="rating-star" href="javascript:void(0);" onclick="openRatingsModal('/course/{{$course->id}}/ratings')">
                                                <span class="fa @if($courseRatingAvg >= 1) fa-star checked @else fa-star-o @endif"></span>
                                                <span class="fa @if($courseRatingAvg >= 2) fa-star checked @else fa-star-o @endif"></span>
                                                <span class="fa @if($courseRatingAvg >= 3) fa-star checked @else fa-star-o @endif"></span>
                                                <span class="fa @if($courseRatingAvg >= 4) fa-star checked @else fa-star-o @endif"></span>
                                                <span class="fa @if($courseRatingAvg >= 5) fa-star checked @else fa-star-o @endif"></span>
                                                <span class="ratings">{{ round($courseRatingAvg, 1) }} <span style="color: #6A6F73;">({{$courseRatingCount}})</span></span>
                                            </a>
                                            <a href="Javascript:void(0);" id="favorite_{{$course->id}}" onclick="makeFavoriteCourse(this, '/user/course/{{$course->id}}/favorite')" style="float:right">
                                                @if($isFavorite == 1)
                                                    <i class="fa fa-heart" style="font-size:20px; color:#e59819"></i>
                                                @else
                                                    <i class="fa fa-heart-o" style="font-size:20px; color:#e59819"></i>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p>No Courses Available</p>
                            @endif
                        </div>
                    </main>
                </div>
            </section>
        @endif
    @else
    <section class=" mt-5 ">
        <div class="container">
            <div class=" d-flex justify-content-between align-items-center mb-5">
                <h3 class="heading">No Course Available</h3>
            </div>
        </div>
    </section>
    @endif
    @php 
        $count = $count + 1;
    @endphp
    @endforeach
@endif

<div class="modal fade" id="modal-course-ratings" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('pageScripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js "></script>
<script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js "></script>
<script src="/assets/js/magnific-popup/jquery.magnific-popup.min.js"></script>

<script>
    var courseCategories = '{{ $courseCategories }}';
        $(document).ready(function() {
            $('.popup-video').magnificPopup({
                disableOn: 700,
                type: 'iframe',
                mainClass: 'mfp-fade',
                removalDelay: 160,
                preloader: false,

                fixedContentPos: false
            });

            for(var i=1; i<=courseCategories.length; i++){
                var a = $('.bxslider'+i).bxSlider({
                    autoStart: false,
                    minSlides: 2,
                    maxSlides: 4,
                    mode: 'horizontal',
                    adaptiveHeight: true,
                    slideWidth: 265,
                    moveSlides: 4,
                    auto: true,
                    pager: false,
                    slideMargin: 25,
                    touchEnabled: false,

                    nextSelector: '.bx-next'+i,
                    prevSelector: '.bx-prev'+i,

                    nextText: '<img src="/assets/img/user_img/slide-next.png">',
                    prevText: '<img src="/assets/img/user_img/slide-prev.png">',

                });
            }

        });

        function applyFilter(){
            var categoryId = $('#category_id').val();
            var title = $('#searchText').val();
            window.location.href = '/user/dashboard?category_id='+categoryId+'&title='+title;
        }

        function openRatingsModal(url){
            $.ajax({
                url: url,
                dataType: 'json',
                processData: false,
                cache : false,
                success: function(responseObj) {
                        if (responseObj.status == "error") {   
                            swal("Error!", responseObj.message, "error");                   
                        } else if (responseObj.status == "success") {
                            jQuery('#modal-course-ratings').html(responseObj.view).modal('show', { backdrop: 'static' });
                        }
                },
                // A function to be called if the request fails. 
                error: function(jqXHR, textStatus, errorThrown) {
                        var responseObj = jQuery.parseJSON(jqXHR.responseText);            
                }
            });    
        }

        function makeFavoriteCourse(elemObj, url){
            var token = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: url,
                type: 'POST',
                data: { '_method': 'post', '_token': token},
                dataType: 'json',
                success: function(responseObj) {
                        if (responseObj.status == "error") {   
                            swal("Error!", responseObj.message, "error");                   
                        } else if (responseObj.status == "success") {
                            if(responseObj.isFavorite === 1){
                                $(elemObj).html('<i class="fa fa-heart" style="font-size:20px; color:#e59819"></i>');
                            }else{
                                $(elemObj).html('<i class="fa fa-heart-o" style="font-size:20px; color:#e59819"></i>');
                            }
                            
                        }
                },
                // A function to be called if the request fails. 
                error: function(jqXHR, textStatus, errorThrown) {
                        var responseObj = jQuery.parseJSON(jqXHR.responseText);            
                }
            });    
        }

    </script>
@endsection
