@if(count($courses))
	@foreach($courses as $course)
	@php 
		$courseImg = $course->getCourseImage($course->id, $course->image_name);
		$courseRatingCount = $course->getCourseRatingCount($course->id);
		$courseRatingAvg = $course->getCourseCalculatedRatings($course->id);
		$isFavorite = $course->CheckFavoriteCourse($course->id, $loggedUserId); 
	@endphp
	<div class="col-sm-3 administrative-slider mt-5 course-manager-box">
		<div class="administrative-card accordion-toggle" searchText="{{$course->title}}">
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

	<div class="col-sm-6 mt-5" id="no_courses_available_div" style="display:none">
		<img src="\assets\img\user_img\course_list_default.png" class="img-fluid" alt="...">
	</div>
@else
	<div class="col-sm-6 mt-5" id="no_courses_available_div">
		<img src="\assets\img\user_img\course_list_default.png" class="img-fluid" alt="...">
	</div>
@endif