<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
				@section('content')
					@parent
					<div class="col-md-12 mid_body_container">
						<div class="row">
							<div class="col-md-12 mid_body_head">
								<h4>{{{ trans("app.mylikes")}}}</h4>
							</div>
							<div class="clearfix"></div>
							<div class="col-md-12">
					            @if($like->count!=0)
									@foreach($like as $l)
					                    <div class="col-md-4 col-xs-6 person_box" style="
    background-image: url('{{{$l->user->others_pic_url()}}}');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
">
<!-- 											<img src="{{{$l->user->profile_pic_url()}}}"> -->
<div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($l->user->photos)}}}</span> </div>
									
					                     	<ul class="list-inline">
							                   <li ><i class="fa fa-circle" style="color:#8DC63F;display:inline"></i></li>
							                    <li class="user_name"><a class="profile_visit" href="{{{ url("/profile/$l->user2") }}}" >{{{$l->user->name}}}</a></li>
												<p>{{{$l->user->city}}}</p>
										 	</ul>
										 	
					                    </div>
					        		@endforeach
								@else
									<div class="" style = "color : black;text-align: center">
					<p class="mv30 teardropAnimation dib">
						<span class="tear"></span>
						<img src="@theme_asset('images/no_likes.png')" width="192" height="192">
					</p>

							<div class="mv20 fs16">
					{{{ trans("app.msg_no_iliked")}}}
					</div>
					
				</div>
				<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/home') }}}">{{{trans_choice('app.liked_msg',1)}}}</a></div>
								@endif
							</div>
						</div>
					</div>
				@endsection
				@section('scripts')



@endsection

