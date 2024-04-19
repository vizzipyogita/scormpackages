<style>
	.rating {
		float:left;
	}
	.rating:not(:checked) > input {
		position:absolute;
		top:-9999px;
		clip:rect(0,0,0,0);
	}

	.rating:not(:checked) > label {
		float:right;
		width:1em;
		padding:0 .1em;
		overflow:hidden;
		white-space:nowrap;
		cursor:pointer;
		font-size:290%;
		line-height:1.2;
		color:#ddd;
		text-shadow:1px 1px #bbb, 2px 2px #666, .1em .1em .2em rgba(0,0,0,.5);
	}

	.rating:not(:checked) > label:before {
		content: '★ ';
	}

	.rating > input:checked ~ label {
		color: gold;
		text-shadow:1px 1px #c60, 2px 2px #940, .1em .1em .2em rgba(0,0,0,.5);
	}

	.rating:not(:checked) > label:hover,
	.rating:not(:checked) > label:hover ~ label {
		color: gold;
		text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
	}

	.rating > input:checked + label:hover,
	.rating > input:checked + label:hover ~ label,
	.rating > input:checked ~ label:hover,
	.rating > input:checked ~ label:hover ~ label,
	.rating > label:hover ~ input:checked ~ label {
		color: gold;
		text-shadow:1px 1px goldenrod, 2px 2px #B57340, .1em .1em .2em rgba(0,0,0,.5);
	}

	.rating > label:active {
		position:relative;
		top:2px;
		left:2px;
	}
	.clearfix:before,
	.clearfix:after {
		content: " "; /* 1 */
		display: table; /* 2 */
	}
	.clearfix:after {
		clear: both;
	}
	.clearfix {
		*zoom: 1;
	}
	#status, button {
		margin: 20px 0;
	}
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

</style>
@php 
	$disableClass = '';
@endphp

<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">{{$course->title}} {{$pageTitle}}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
		<div class="row hide" id="ErrorDiv" style="padding:10px;">
			<div class="col">
				<div class="alert alert-danger alert-dismissible" role="alert">
					<span id="errorMsg"></span>					
				</div>
			</div>
		</div> 
		@if($isStartCourse == 0)
			<div class="row" id="ratingErrorDiv" style="padding:10px;">
				<div class="col">
					<div class="alert alert-danger alert-dismissible" role="alert">
						<span id="ratingErrorMsg">Ratings can only be given to completed Courses. Please complete the course.</span>					
					</div>
				</div>
			</div> 
			@php 
				$disableClass = 'disabled-content';
			@endphp
		@endif
		
		<form id="ratingForm" class="mb-3" action="/course/{{$courseId}}/postratings" method="POST" enctype="multipart/form-data">
			@csrf
			<input type="hidden" name="rating_points" id="rating_points" value="0">
			<div class="modal-body">
				<div class="container">
					<div class="col-md-12 {{$disableClass}}" id="user-rating-section">
						<fieldset class="rating">
							<h5>Your Ratings</h5>
							<input type="radio" id="star5" name="rating" value="5" /><label for="star5" title="Rocks!">5 stars</label>
							<input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="Pretty good">4 stars</label>
							<input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="Meh">3 stars</label>
							<input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="Kinda bad">2 stars</label>
							<input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="Sucks big time">1 star</label>
						</fieldset>
						<div class="clearfix"></div>
					</div>
					<div class="row">
						<div class="col mb-3">
							<label class="form-label" for="name">Comment</label>
							<textarea class="form-control" name="comment" placeholder="Type your comment here"> </textarea>
						</div>
					</div>   
					<div class="col-md-12">
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
																<p class="text-gray" style="font-style: italic; margin:0">{{ date_format($rating->updated_at, "M, d Y") }}</p>
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
									
									@if($activeRatingsCount > 3)
										<div class="col-sm mb-5">
											<a href="/user/course/{{ $courseId }}/ratings/viewall" class="btn-view-all" target="_blank" style="height: 39.58px;">View all ratings</a>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="closeBtn btn btn-outline-secondary hide" data-bs-dismiss="modal">
					Close
				</button>
				<button type="submit" class="btn learn-btn submitBtn">Submit</button>
			</div>
		</form>

    </div>
</div>

<script>
	var currentUserRatingPoints = '{{$currentUserRatingPoints}}';
	$("#star"+currentUserRatingPoints).prop("checked", true)  
	
	 var loadFile = function(event, eleObj) {
            $("#output").removeClass('hide');
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);

            var FileUrl = output.src;
            $('#output').attr('src', FileUrl)
           
        };

		$(document).ready(function() {
			$("form#ratingForm").submit(function(e) 
			{
				$('#ErrorDiv').addClass('hide');
				e.preventDefault(); // prevent the default click action from being performed
				if ($("#ratingForm :radio:checked").length == 0) {
					$('#ErrorDiv').removeClass('hide');
					$('#errorMsg').text("Please give ratings");
					return false;
				} else {
					var ratingPoints = $('input:radio[name=rating]:checked').val();
					$('#rating_points').val(ratingPoints);
					$.ajax({
						url: $(this).attr('action'),
						type: $(this).attr('method'),
						data: new FormData(this),
						contentType:false,
						processData:false,
						dataType: "json",
						beforeSend: function() {
							$('.submitBtn').text('Please wait...');
							$('.submitBtn').prop('disabled', true);
						},
						success: function(responseTxt, textStatus, jqXHR) {
							if (responseTxt.status == "success") {
								location.reload();
							} else if (responseTxt.status == "error") {
								$('#ErrorDiv').removeClass('hide');
								$('#errorMsg').text(responseTxt.message);
								$('.submitBtn').text('Submit');
								$('.submitBtn').prop('disabled', false);
							}            
						},
						error: function(error) {
							$('.submitBtn').text('Submit');
							$('.submitBtn').prop('disabled', false);
						}
					});
				}
			});
		});
</script>


