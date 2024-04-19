@extends('layouts.user_layout')
@section('title', $pageTitle)

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
<style>
    .hide{
		display:none !important;
	}
	.review-user-name{
		color: #FFB900 !important
	}

    .disabled-content {
        pointer-events: none;
        opacity: 0.4;
    }
    .rating-star span{
        color:#e59819 !important;
    }
</style>
@endsection

@section('content')

<section class=" mt-5 ">
    <div class="container">
        <div class=" d-flex justify-content-between align-items-center">
            <h3 class="heading">{{$pageTitle}}</h3>
        </div>
        <div class="row">
            <a class="rating-star" href="javascript:void(0);">
                <span class="fa @if($courseRatingAvg >= 1) fa-star checked @else fa-star-o @endif"></span>
                <span class="fa @if($courseRatingAvg >= 2) fa-star checked @else fa-star-o @endif"></span>
                <span class="fa @if($courseRatingAvg >= 3) fa-star checked @else fa-star-o @endif"></span>
                <span class="fa @if($courseRatingAvg >= 4) fa-star checked @else fa-star-o @endif"></span>
                <span class="fa @if($courseRatingAvg >= 5) fa-star checked @else fa-star-o @endif"></span>
                <span class="ratings">{{ round($courseRatingAvg, 1) }} <span style="color: #6A6F73;">({{ $courseRatingCount }})Reviews</span></span>
            </a>
        </div>

        <main class="ext">
            <div class="row d-flex justify-content-center" id="view_course_ratings_div">
                <div class="col-md-12 mt-4">
                    <div class="offer-dedicated-body-left">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="bg-white rounded shadow-sm p-4 mb-4 restaurant-detailed-ratings-and-reviews">
                                <div id="status"></div>
                                <h5 class="mb-1">All Reviews</h5>
                                @if(count($ratings))
                                    @php 
                                        $count = 1;
                                        $noReviewCount = 0;
                                    @endphp
                                    @foreach($ratings as $rating)
                                        @if($rating->comment != '')
                                            @php 
                                                $userName = $rating->firstname.' '.$rating->lastname;
                                                if($loggedUserId == $rating->user_id){
                                                    $userName = 'You';
                                                }
                                                $noReviewCount = $noReviewCount + 1;
                                                $currentUserRating = $rating->GetRatingDetailsByUserId($rating->user_id, $courseId); 
                                                $currentUserRatingPoints = isset($currentUserRating->rating_points) ? $currentUserRating->rating_points : 0;
                                            @endphp
                                            @if($count != 1)
                                            <hr>
                                            @endif
                                            <div class="reviews-members pt-4">
                                                <div class="media">
                                                    <a href="#"></a>
                                                    <div class="media-body">
                                                        <div class="reviews-members-header">
                                                            <h6 class="mb-1 review-user-name">{{ $userName }}</h6>
                                                            <p class="text-gray" style="margin:0; font-style: italic;">{{ date_format($rating->created_at, "M, d Y") }}</p>
                                                            <a class="rating-star" href="javascript:void(0);">
                                                                <span class="fa @if($currentUserRatingPoints >= 1) fa-star checked @else fa-star-o @endif"></span>
                                                                <span class="fa @if($currentUserRatingPoints >= 2) fa-star checked @else fa-star-o @endif"></span>
                                                                <span class="fa @if($currentUserRatingPoints >= 3) fa-star checked @else fa-star-o @endif"></span>
                                                                <span class="fa @if($currentUserRatingPoints >= 4) fa-star checked @else fa-star-o @endif"></span>
                                                                <span class="fa @if($currentUserRatingPoints >= 5) fa-star checked @else fa-star-o @endif"></span>
                                                                <span class="ratings">{{ round($currentUserRatingPoints, 1) }}</span>
                                                            </a>
                                                        </div>
                                                        <div class="reviews-members-body mt-2">
                                                            <p>{{ $rating->comment }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @php 
                                            $count = $count + 1;
                                        @endphp
                                    @endforeach
                                    @if($noReviewCount == 0)
                                    <div class="reviews-members pt-4">
                                        <div class="media">
                                            <p>No comments available</p>
                                        </div>
                                    </div>
                                    @endif
                                @else
                                <div class="reviews-members pt-4">
                                    <div class="media">
                                        <p>No comments available</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</section>

<div class="modal fade" id="modal-course-ratings" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('pageScripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js "></script>
<script>
    
</script>
@endsection
