@extends($layout)
@section('title', $pageTitle)

@section('pagestyles')
<link href="/assets/css/style-user.css" rel="stylesheet">
<link href="/assets/css/responsive-user.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bxslider/4.2.5/jquery.bxslider.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/js/sweetalert/sweetalert.css" />

<style>
    .hide{
        display:none;
    }
</style>
@endsection

@section('content')

<!---course--->
<div class="container course-container">
    <div class="row course-play">
         <iframe src="{{$coursePlayUrl}}" title="{{$course->title}}"></iframe>
    </div>
</div>

<div class="container" id="course_finish_div">
    <div class="row mt-5 justify-content-end">
        <div class="col-md-3">
            <a class="btn learn-btn d-flex align-items-center justify-content-center" href="javascript:void(0);" onclick="finishCourse('/user/course/{{$courseId}}/finish')" >Finish Course</a>
        </div>
    </div>
</div>

@endsection

@section('pageScripts')
<script src="/assets/js/sweetalert/sweetalert.js"></script>
<script>

    $('iframe').on('load', function() {
        $('iframe').contents().find('.overview__button-enrolled').click(function() {
            $('#course_finish_div').removeClass('hide');
        });
    });

    function finishCourse(url){
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
                        swal("Success!", "Course Completed Successfully", "success");    
                        location.href = '/user/dashboard';
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
