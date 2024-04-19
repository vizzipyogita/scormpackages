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
</style>
@endsection

@section('content')

<!------Category filter------>
@php 
    $categoryTitle = '';
@endphp
<div class="container">
    <div class="row mt-5">
        <div class="col-sm">
            <select class="form-select select2" aria-label="Default select example" onchange="changeCategory(this)" id="category_id" name="category_id" required>
                <option selected value="">Select Category</option>
                @foreach($courseCategories as $category)	
                    @php 
                        $selected = '';
                        $availableCourses = $category->getCategoryCourses();
                        if($categoryId == $category->id){
                            $selected = 'selected';
                            $categoryTitle = $category->title;
                        }
                    @endphp
                    @if(count($availableCourses))						
                        <option value="{{$category->id}}" {{ $selected }}>{{$category->title}}</option>	
                    @endif
                @endforeach
            </select>
        </div>
        <div class="col-sm">
            <input type="text" class="form-control" id="searchText" name="title" placeholder="Search" value="{{$title}}">
            <span class="fa fa-search searchspan"></span>
        </div>
    </div>
</div>

<section class=" mt-5 ">
    <div class="container">
        <div class=" d-flex justify-content-between align-items-center">
            <h3 class="heading">{{ $categoryTitle }} Courses</h3>
        </div>

        <main class="ext">
            <div class="row d-flex justify-content-center" id="view_courses_div">
                @include('course_item_box', ['courses'=>$courses])
            </div>
        </main>
    </div>
</section>

<div class="modal fade" id="modal-course-ratings" data-keyboard="false" data-backdrop="static"></div>
@endsection

@section('pageScripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js "></script>
<script>
    var courseCategories = '{{ $courseCategories }}';
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
