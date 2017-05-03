<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
					@section('content')
						@parent

<div class="col-md-12 mid_body_container">
	<div class="row">
		<div class="col-md-12 mid_body_head">
			<h4>{{{ trans("app.matches")}}}</h4>
			
		</div>
		<div class="clearfix"></div>
		<div class="col-md-12">
            	@if($matches->count!=0)
				@foreach($matches as $mat)
                    <div class="col-md-4 col-xs-12 person_box" ng-click="gotoprofile('{{{url("/profile/$mat->user2")}}}')" style="
												    background-image: url('{{{$mat->user->others_pic_url()}}}');
												    background-repeat: no-repeat;
												    background-size: cover;
												    background-position: center;
												    cursor:pointer;
												">
						
						 @if(count($mat->user->photos))<div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($mat->user->photos)}}}</span> </div>@endif
                     	<ul class="list-inline">
		                  @if($mat->user->onlineStatus()) <li style="float: right"><i class="fa fa-circle small_circle" style="color:#00BF00;display:inline"></i></li>@endif
		                    <li ><a class="profile_visit" href='{{{url("/profile/$mat->user2")}}}'>{{{$mat->user->name}}}</a></li>
							<p>{{{$mat->user->city}}}, {{{$mat->user->country}}}</p>
					 	</ul>
                    </div>
        		@endforeach
        		
			@else
				<div class="" style = "color : black;text-align: center">
					<p class="mv30 teardropAnimation dib">
						<span class="tear"></span>
						<img src="@theme_asset('images/crying.png')" width="192" height="192">
					</p>

							<div class="mv20 fs16">
					{{{ trans("app.msg_no_match")}}}
					</div>
					
				</div>
				<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/home') }}}">{{{trans_choice('app.no_match',1)}}}</a></div>
			@endif
		</div>
		 <div class="pagination_cnt">{!! $matches->render() !!}</div>
	</div>
</div>






			<!-- <div class="col-xs mid_body_container">
				<div class="col-md-12 mid_body_head ">
					<p>Matches</p>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-12 mylikes-images-styling">
					<div class="row">
						@if($matches->count!=0)
							@foreach($matches as $mat)
								<div class="col-md-4 col-xs-6 mylikes-beta-pictures-styling">
									<img class="mylikes-alpha-pictures" src="{{{$mat->user->profile_pic_url}}}">
									<div class="col-md-12 my-likes-image-location">
							             <ul class="list-inline">
								             <li><i class="fa fa-circle" style="color:#8DC63F;display:inline"></i></li>
								             <li>{{{$mat->user->name}}}</li>
											 <p>{{{$mat->user->city}}}, {{{$mat->user->country}}}</p>
										 </ul>
						             </div>
				                </div>
				        @endforeach

						@else
							<div class="cont-cover" style = "color : black">
							<h1>You havent Matched anyone</h1>
								<a href = "{{{ url('/home') }}}">Explore new people</a>
							</div>
						@endif
					</div>
				</div>
				<div class="clearfix"></div>
			</div> -->
				@endsection
					@section('scripts')
	
		<script>
			$(document).ready(function(){
				$('.fa.fa-angle-down.d-arrow-1').click(function(){
					 $(this).next('.hover-cover').slideToggle();
				});
				$('.fa.fa-angle-down.d-arrow').click(function(){
					$('.sign-out ').slideToggle();
				});
			});
		</script>
	@endsection

