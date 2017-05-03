<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
				@section('content')
					@parent
						<div class="col-md-12 mid_body_container">
							<div class="row">
								<div class="col-md-12 mid_body_head">
									<h4>{{{ trans("app.likedme")}}}</h4>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									
							@if($activated)
						            @if($wholiked->count!=0)
										@foreach($wholiked->likes as $l)
						                    <div class="col-md-4 col-xs-6 person_box" style="
												    background-image: url('{{{$l->others_pic_url()}}}');
												    background-repeat: no-repeat;
												    background-size: cover;
												    background-position: center;
												">
												<!-- 												<img src="{{{$l->profile_pic_url}}}"> -->
												
												@if(count($l->photos))<div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($l->photos)}}}</span> </div>@endif
																																							                     	<ul class="list-inline">
																			                     	@if($l->onlineStatus())<li style="float:right"><i class="fa fa-circle small_circle" style="color:#00BF00;display:inline"></i></li>@endif
																				                   
																				                    <li class="user_name"><a class="profile_visit" href="{{{url("/profile/$l->id")}}}" >{{{$l->name}}}</a></li>
																									<p>{{{$l->city}}}, {{{$l->country}}}</p>
																							 	</ul>
																							 	
																							 	
																							 																			                    </div>
						        		@endforeach
						        		
						        		@if($wholiked->count== 0)
							
						
											<p class="mv30 teardropAnimation dib">
												<span class="tear"></span>
												<img src="@theme_asset('images/crying.png')" width="192" height="192">
											</p>
						
													<div class="mv20 fs16">
											{{{ trans("app.msg_no_likedme")}}}
											</div>
											
										
										<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">{{{trans_choice('app.who_liked_msg',0)}}}</a></div>	
										@endif
									@else
										<div class="" style = "color : black;text-align: center">
											<p class="mv30 teardropAnimation dib">
												<span class="tear"></span>
												<img src="@theme_asset('images/no_likes.png')" width="192" height="192">
											</p>
						
													<div class="mv20 fs16">
											{{{ trans("app.msg_no_likedme")}}}
											</div>
											
										</div>
										<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/home') }}}">{{{trans_choice('app.who_liked_msg',0)}}}</a></div>

									@endif
							
							
							
							
							@else
							
							<div class="" style = "color : black;text-align: center">
										@if($wholiked->count!= 0)
										
											<p class="mv30 teardropAnimation dib">
													<span class="tear"></span>
													<img src="@theme_asset('images/happy.png')" width="192" height="192">
											</p>
											<div class="col-md-12">
												@foreach($wholiked->likes as $l)
													<img class="vistors-pictures-blurred" src="{{{$l->profile_pic_url()}}}">
												@endforeach
											</div>
											<br/>
											<div class="mv20 fs16" style="font-size: 18px;">
												
												{{{trans_choice('app.who_liked_msg',3)}}} <span style="font-size: 25px">{{{$wholiked->count}}}</span> {{{trans_choice('app.who_liked_msg', 2)}}}
												</div>
											
											<div class="encounters_more"><a class="explore_more button--large button--blue upgrade-now" href = "#"> {{{trans_choice('app.activate_superpower',1)}}}</a></div>	
				
									@else	
										
											<p class="mv30 teardropAnimation dib">
												<span class="tear"></span>
												<img src="@theme_asset('images/crying.png')" width="192" height="192">
											</p>
						
													<div class="mv20 fs16">
											{{{ trans("app.msg_no_likedme")}}}
											</div>
											
										
										<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">{{{trans_choice('app.who_liked_msg', 0)}}}</a></div>	
										@endif
						
							</div>
								
						@endif
							
							
							
							
							
							
							
							
							
							
							
							
							
							
								</div>
							</div>
						</div>
					{{--@endif--}}
				@endsection
				@section('scripts')

			             
				</div>
				<div class="clearfix"></div>
    		</div>

					{{--@endif--}}
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
