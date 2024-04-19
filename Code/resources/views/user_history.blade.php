@extends('layouts.user_layout')
@section('title', $pageTitle)

@section('pagestyles')
<link href="/assets/css/style-user.css" rel="stylesheet">
<link href="/assets/css/responsive-user.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="/assets/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="/assets/css/ratingcss.css">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
        float: right;
    }
    .administrative-slider{
        width:293px !important;
    }
    .searchspan {
        margin-left: 10px;
        margin-top: -27px;
        position: absolute;
        z-index: 2;
        color: #7a777d;
    }
    #searchText{
        padding-left: 30px !important;
    }
    .navbar-nav{
        float: right !important;
    }
</style>
@endsection

@section('content')

<section class=" mt-5 ">
    <div class="container">
        <div class=" d-flex justify-content-between align-items-center">
            <h3 class="heading">History</h3>
        </div>

        <main class="ext">
            <div class="row d-flex justify-content-center" id="view_courses_div">
            
            </div>
        </main>
    </div>

    <div class="container mt-2">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($isInprogress == 0) active @endif" id="favorite-tab" data-bs-toggle="tab" data-bs-target="#favorite" type="button" role="tab" aria-controls="inprogress" aria-selected="true">Favorite Courses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link @if($isInprogress == 1) active @endif" id="inprogress-tab" data-bs-toggle="tab" data-bs-target="#inprogress" type="button" role="tab" aria-controls="inprogress" aria-selected="false">In Progress Courses</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed Courses</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade @if($isInprogress == 0) show active @endif" id="favorite" role="tabpanel" aria-labelledby="favorite-tab">
                <div class="col-12">
                    <div class="row justify-content-center">
                        @include('course_item_box', ['courses'=>$favoriteCourses])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade @if($isInprogress == 1) show active @endif" id="inprogress" role="tabpanel" aria-labelledby="inprogress-tab">
                <div class="col-12">
                    <div class="row justify-content-center">
                        @include('course_item_box', ['courses'=>$inprogressCourses])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                <div class="col-12">
                    <div class="row justify-content-center">
                        @include('course_item_box', ['courses'=>$completedCourses, 'isCopletedCourse'=>1])
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</section>

<div class="modal fade" id="modal-course-ratings" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('pageScripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
        $(document).ready(function() {
            var existCount = 0;
            $("#searchText").keyup(function() {
                $('#no_courses_available_div').css('display', 'none');
                var matchString = $.trim($(this).val()).toLowerCase();
                if (matchString == '') {
                    $('.course-manager-box').show();
                    return;
                }

                $(".accordion-toggle").each(function() {
                    var currentSearchText = $(this).attr('searchText').toLowerCase();
                    var foundCounter = currentSearchText.indexOf(matchString);
                    if(existCount > 0){
                        $('#no_courses_available_div').css('display', 'none');
                    }else{
                        $('#no_courses_available_div').css('display', 'block');
                    }
                    if (foundCounter > -1) {
                        $(this).closest('.course-manager-box').show();
                        $('#no_courses_available_div').css('display', 'none');
                        existCount = existCount + 1;
                    } else {
                        $(this).closest('.course-manager-box').hide();
                        $('#no_courses_available_div').css('display', 'block');
                    }
                });

                if(existCount > 0){
                    $('#no_courses_available_div').css('display', 'none');
                    existCount = 0;
                }else{
                    $('#no_courses_available_div').css('display', 'block');
                }
            });
        });

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

        function changeCategory(elemObj){
            var categoryId = $(elemObj).val();
            location.href = '/user/category/'+categoryId+'/viewall';
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
