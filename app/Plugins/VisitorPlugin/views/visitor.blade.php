	<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
		@section('content')
		@parent
	  <!-- <div class="col-md-12 col-xs" style="box-shadow: 0px 1px 4px rgba(0,0,0,0.36)"> -->
	  <div class="col-md-12 col-xs mid_body_container">
    		<div class="row">	
				<div class="col-md-12 mid_body_head">
					<h4>Visitors</h4>
				</div>
				
				<div class="clearfix"></div>
				<div class="col-md-12">
				<div class="">
						@if($activated)
							@foreach($visit as $v)
							 
								<div class="col-md-4 col-xs-6 mylikes-pictures-styling">
								<img class="vistors-pictures" src="{{{$v->user->profile_pic_url()}}}">
								<div class="col-md-12 my-likes-image-location">
				                     <ul class="list-inline">
				                     <li><i class="fa fa-circle" style="color:#8DC63F;display:inline"></i></li>
				                     <li>{{{$v->user->name}}}</li>
									  <p>{{{$v->user->city}}},{{{$v->user->country}}}</p>
									 </ul>
			                 	</div>
							</div>
							@endforeach
							{!! $visit->render() !!}
							@if($visit->count== 0)
							
						
								<p class="mv30 teardropAnimation dib">
									<span class="tear"></span>
									<img src="@theme_asset('images/crying.png')" width="192" height="192">
								</p>
			
										<div class="mv20 fs16">
								Ah, Shoot! Nobody has visited you yet.
								</div>
								
							
							<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">Try "Rise Up" or "Spotlight" and get more visitors!!</a></div>	
							@endif
						@else
							
							<div class="" style = "color : black;text-align: center">
							@if($visit->count!= 0)
							
								<p class="mv30 teardropAnimation dib">
										<span class="tear"></span>
										<img src="@theme_asset('images/happy.png')" width="192" height="192">
								</p>
								<div class="col-md-12">
									@foreach($visit as $v)
										<img class="vistors-pictures-blurred" src="{{{$v->user->profile_pic_url()}}}">
									@endforeach
								</div>
								<br/>
								<div class="mv20 fs16" style="font-size: 18px;">
									
									WOW! You have <span style="font-size: 25px">{{{$visit->count}}}</span> visitors.
									</div>
								
								<div class="encounters_more"><a class="explore_more button--large button--blue upgrade-now" href = "#"> Activate Super Power to see!</a></div>	
	
							@else	
							
								<p class="mv30 teardropAnimation dib">
									<span class="tear"></span>
									<img src="@theme_asset('images/crying.png')" width="192" height="192">
								</p>
			
										<div class="mv20 fs16">
								Ah, Shoot! Nobody has visited you yet.
								</div>
								
							
							<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">Try "Rise Up" or "Spotlight" and get more visitors!!</a></div>	
							@endif
						
							</div>
								
						@endif
					
					</div>
					</div>
				</div>
			</div>
					@endsection
	@section('scripts')				
	

	<!-- custom scrollbar plugin --> 
	<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
	<script>
			(function($){
				$(window).load(function(){
					
					$("#content-1").mCustomScrollbar({
						axis:"x",
						advanced:{
							autoExpandHorizontalScroll:true
						}
					});
					
				
			
					
					$("a[rel='add-content']").click(function(e){
						e.preventDefault();
						var markup="<li id='img-"+i+"-container'><img src='images/"+imgs[i]+"' /></li>";
						$(".content .mCSB_container ul").append(markup);
						if(i<imgs.length-1){
							i++
						}else{
							i=0;
						}
					});
					
					$("a[rel='remove-content']").click(function(e){
						e.preventDefault();
						if($(".content .mCSB_container ul li").length<4){return;}
						i--
						if(i<0){i=imgs.length-1;}
						$("#img-"+i+"-container").remove();
					});
					
				});
			})(jQuery);
		</script>
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
	<style>
	
	.vistors-picture-styling
	{
	margin-top:10px;
	}
	</style>
