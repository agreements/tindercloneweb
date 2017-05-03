<?php use App\Components\Theme;?>
<div class="col-md-3 pad right_box_master">
		<div class="coming_next_block" ng-if="next_users.length">
			<p >{{{ trans("app.up_next")}}}</p>
			<ul class="list-inline">
				
				<li    ng-repeat="user in next_users  | limitTo:4  track by $index" class="next_users_encounters" ><img ng-src="[[ user ]]"></li>
				
			</ul>
		</div>
		
		@if($auth_user->isSuperpowerActivated())
		<div class="premium text-center" style="background-image:url('@theme_asset('images/background-premium.png')');background-size:cover;">
			<h2>{{{ trans("app.superpower_activated")}}}</h2>
								 	 <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI1NiAyNTYiIGhlaWdodD0iMjU2cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiB3aWR0aD0iMjU2cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMkM2Mi4xLDI0Ny4zMjIsOC42NzgsMTkzLjksOC42NzgsMTI4QzguNjc4LDE5NC4yNzQsNjIuMSwyNDgsMTI4LDI0OCAgICBzMTE5LjMyMi01My43MjYsMTE5LjMyMi0xMjBDMjQ3LjMyMiwxOTMuOSwxOTMuOSwyNDcuMzIyLDEyOCwyNDcuMzIyeiIgZmlsbD0iI0I3MUMxQyIgb3BhY2l0eT0iMC4yIi8+PHBhdGggZD0iTTEyOCw4LjY3OEMxOTMuOSw4LjY3OCwyNDcuMzIyLDYyLjEsMjQ3LjMyMiwxMjhDMjQ3LjMyMiw2MS43MjYsMTkzLjksOCwxMjgsOCAgICBTOC42NzgsNjEuNzI2LDguNjc4LDEyOEM4LjY3OCw2Mi4xLDYyLjEsOC42NzgsMTI4LDguNjc4eiIgZmlsbD0iI0ZGRkZGRiIgb3BhY2l0eT0iMC4yIi8+PGNpcmNsZSBjeD0iMTI4IiBjeT0iMTI4IiBmaWxsPSIjRjQ0MzM2IiByPSIxMTkuMzIyIi8+PC9nPjxsaW5lYXJHcmFkaWVudCBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaWQ9IlNWR0lEXzFfIiB4MT0iMTExLjQxOCIgeDI9IjIxMi4zNzg1IiB5MT0iMTExLjQwNzciIHkyPSIyMTIuMzY4MiI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojMjEyMTIxO3N0b3Atb3BhY2l0eTowLjIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMyMTIxMjE7c3RvcC1vcGFjaXR5OjAiLz48L2xpbmVhckdyYWRpZW50PjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMmM2NS45LDAsMTE5LjMyMi01My40MjIsMTE5LjMyMi0xMTkuMzIyYzAtMi41MzItMC4wODgtNS4wNDItMC4yNDQtNy41MzVsLTAuMDI0LTAuMzgxICAgYy0wLjAyNC0wLjM2OC0wLjA0My0wLjczNy0wLjA3LTEuMTAzbC0wLjA2NC0wLjc3MmMtMC4wMTktMC4yMjgtMC4wNDEtMC40NTUtMC4wNjEtMC42ODNsLTAuMTAyLTEuMTQ5ICAgYy0wLjAxLTAuMTAxLTAuMDE3LTAuMjAzLTAuMDI3LTAuMzAzbC0wLjEyNC0xLjIyMmwtNjcuMjM5LTY3LjJsMCwwbC03OC40ODMsNzQuMjlsOS41MjIsOC44OTNsLTIyLjU2NiwyOC45ODdsMTIuNzMyLDEyLjEyNiAgIGwtMjMuOTIsMzYuNDM3bDAsMGwwLDBsMzkuMTIzLDM4LjMxOGMwLjA2NywwLjAwNywwLjEzNSwwLjAxMiwwLjIwMiwwLjAxOWwxLjA2MywwLjA5NGMwLjE2NCwwLjAxNSwwLjMyOCwwLjAyOSwwLjQ5MywwLjA0NCAgIGwwLjc3MywwLjA2OWMwLjI2NCwwLjAyMSwwLjUyNiwwLjA0NCwwLjc5LDAuMDYzbDAuNDg3LDAuMDMxYzAuMzY5LDAuMDI2LDAuNzQsMC4wNDgsMS4xMSwwLjA3bDAuMTc2LDAuMDExICAgQzEyMy4yMjgsMjQ3LjI0MywxMjUuNjA1LDI0Ny4zMjIsMTI4LDI0Ny4zMjJ6IiBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIvPjxwb2x5Z29uIGZpbGw9IiNGRkVCM0IiIHBvaW50cz0iMTQ2Ljg2NCw5OS4zNTQgMTQ2LjcxNCw5OS4zNTMgMTc5LjM2Niw0Ny42NTIgMTAwLjg4NCwxMjEuOTQyIDExNy4zMjgsMTIxLjk0MiAxMTcuMjg5LDEyMS45OTIgICAgMTE3LjMyOCwxMjEuOTkyIDExMi42MTIsMTI4LjAwMSA4Ny44MzksMTU5LjgyMSAxMDguMzU4LDE1OS44MjEgMTA4LjE4MiwxNjAuMDg3IDEwOC4zNTgsMTYwLjA4NyA3Ni42NTIsMjA4LjM4NSAxNTcuMTU2LDEzNi4xNjggICAgMTM3Ljc0NCwxMzYuMTY4IDEzNy43OTcsMTM2LjEwMSAxMzcuNzQ0LDEzNi4xMDEgMTQ0LjE3LDEyNy45OTcgMTY2LjMyOCw5OS44MiAxNDYuNzE0LDk5LjU4OSAgIi8+PHBvbHlnb24gZmlsbD0iI0JGMzYwQyIgb3BhY2l0eT0iMC4yIiBwb2ludHM9IjE4MC40MzEsNDUuOTY2IDEwMC44MzEsMTIxLjk0MiAxMDAuODg0LDEyMS45NDIgMTc5LjM2Niw0Ny42NTIgICIvPjwvZz48L3N2Zz4=" class="super_power_image_rightbar" data-toggle="tooltip" title="Super power activated!">
								 	 
								 	 <h4 class="super_power_days_remaining"><span style="font-size: 20px;font-weight: bold;">{{{$auth_user->superpowerdaysleft()}}}</span> {{{trans('app.super_power_days')}}}</h4>
								 	 </div>
								 	
	@else
		<div class="premium text-center" style="background-image:url('@theme_asset('images/background-premium.png')');background-size:cover;">
			<h2>{{{ trans("app.upgrade_premium")}}}<br>
				{{{ trans("app.get_featured")}}}</h2>
		<a href="#/" ><button type="button" class="btn btn-default custom upgrade-now"><span class="upgrade-now">{{{ trans("app.upgrade_now")}}}</span></button></a>
		</div> 
  @endif
		<div class="your-prof">
			
			@if($percent != '100')
			<div class="complete">
				
				<h2 class="complete-your-profile">{{{ trans("app.complete_profile")}}}<h2>
				<h3 style="display: none">{{{ trans("app.complete_profile")}}} {{{$percent}}}% {{{ trans("app.complete")}}}.</h3>
				
				 <p style="width:55%" data-value="{{{$percent}}}"><span class="profile_header_rightbar">{{{trans('app.profile')}}}</span></p>
				<progress class="css3" max="100" value="{{{$percent}}}"></progress>
				<p>{{{trans('app.complete_profile_msg')}}}</p>
			</div>
			<a href="{{{url('profile/'.$auth_user->id)}}}"><button type="button" class="btn btn-default custom_modal-popup edit_profile"><span class="">{{{ trans("app.edit_profile")}}}</span></button></a>
			@else
			<div class="complete">
				
				<h2 class="complete-your-profile">{{{trans('app.profile_is')}}} {{{trans('app.completed')}}}<h2>
				<h3 style="display: none">{{{ trans("app.complete_profile")}}} {{{$percent}}}% {{{ trans("app.complete")}}}.</h3>
				
				 <p style="width:{{{$percent}}}%" data-value="{{{$percent}}}"><span class="profile_header_rightbar">{{{trans('app.profile')}}}</span></p>
				<progress class="css3" max="100" value="{{{$percent}}}"></progress>
				<p>{{{trans('app.increase_like_chance')}}}</p>
				<a href="{{{url('/encounter')}}}"><button type="button" class="btn btn-default custom_modal-popup edit_profile"><span class="">{{{trans('app.play_encounters')}}}s</span></button></a>
			</div>
			
			@endif
			
			
		</div>

		
		<div class="your-prof text-center ad-col-right hidden-xs" style="border:none;background:inherit;box-shadow:none">
	       {{{Theme::render('right-sidebar-ad')}}}
	    </div>   
	    {{{Theme::render('user_action_widget')}}}
</div>
<input type = "hidden" value = "{{{trans('app.upgrade_premium')}}}" id ="activte-supoer-power-header">

@section('rightsidebar-scripts')
		@parent
		<script>
			console.log('rightsidebarscripts');
	
	$(function(){
		
		$(document).on('click', '.upgrade-now', function(){
	
	
		
					//$('.superpower-invisible-header').text($("#activte-supoer-power-header").val());
					 
					//open activate super power pop up
					//$('#myModalInvisibleMode').modal('show');
					
					angular.element('#AppController').scope().openPaymentModal('superpower',{'invisible':'0'});
					
					//$('#superpower_invisible').val('0');
		
		
	})
	
	});
	
	</script>
		
		@endsection
