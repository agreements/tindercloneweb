	<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))

	
					@section('content')
						@parent
						<div class="col-xs">
					      <div id="close-currentlocation1" class="col-md-12 people-current-location-tab">
					       <div class="row">
					        <div class="col-md-6 people-nearby-current-picker">
					        
					        <i class="material-icons md-24 md-dark material-pindrop">pin_drop</i>
<!-- 					        <input autocomplete="on" name="city" value="{{{$auth_user->profile->latitude}}}" placeholder="Enter a location" class="txtPlaces" id="txtPlaces1" style="width: 137px" type="text"> -->

<input autocomplete="on" name="city" value="Moscow Russia" placeholder="Enter a location" class="txtPlaces" id="txtPlaces12" disbaled="disabled" style="width: 137px" type="text">
	                        <input type="hidden" id="lat" name="lat" value=""/>
                         <input type="hidden" id="lng" name="lng" value=""/>
                         <input type="hidden" id="country" name="country" value=""/>
                         <input type="hidden" id="city" name="city" value=""/>
                         <i class="fa fa-arrow-circle-right enter_loc" onclick="set_location()"></i>
					        </div>
					        <div class="col-md-2 people-nearby-filter">
					       <i onclick="showSettings()" data-toggle="modal" data-target="#filterModal" class="material-icons md-24 material-tune-settings">tune</i>
					        </div>
					       </div>
	                      </div>
	                       <div id="close-currentlocation" class="col-md-12 people-current-location-tab" style="display:none">
					       <div class="row">
					      
					        <div class="col-md-2 people-nearby-filter">
					        <i onclick="closeMark()" class="material-icons md-24 people-nearby-filter-button">{{{trans_choice('app.close',1)}}}</i>
					        </div>
					       </div>
	                      </div>
	                      <div id="hidden" class="col-md-12 people-nearby-filter-div" style="display:none">
	                      <div class="row">
	                        <div class="col-md-4 people-nearby-show">
	                           <span class="show-text">{{{trans_choice('app.show',0)}}}</span>
	                            <input id="rad1" type="radio" name="rad"><label for="rad1">{{{trans_choice('app.guy',1)}}}</label>
	                             <input id="rad2" type="radio" name="rad"><label for="rad2">{{{trans_choice('app.girl',1)}}}</label>
	                           </div>
	                      <div class="col-md-8 people-nearby-distance">
	                    
	                      </div>
	                      
	                      <div class="col-md-4 people-nearby-age">
	                       
	                      <div class="rang_slider1">
							  <p>
								 <label for="age">{{{trans_choice('app.age',1)}}}:</label>
									 <input type="text" id="age12" name = "age1" value = "27-64">
							  </p>
							   <div id="slider-age1"></div>
						  </div>
						  </div>
	                      <div class="col-md-8 people-nearby-savechanges">
	                      <button type="button" class="btn btn-primary people-nearby-savechanges-button">{{{trans_choice('app.save',1)}}} {{{trans_choice('app.change',1)}}}</button>
	                      </div>
	                      
	                      </div>
	                      </div>
						<div class="cont-cover">

							<div class="cont-header">

						<!-- start people nearby -->
						@if(count($nearByUsers) > 0)
						
							<div id="grid" data-columns>
						
							@foreach($nearByUsers as $user)
							
								<div  class="col-md-12 col-xs-12 user-image-template-col-main" >
						           <div class="row">
						             <div class="col-md-12 col-xs-12" style="height:200px">
						                <div class="row"> 
						                  <img class="profile-template-image-custom" src='{{{$user->others_pic_url()}}}'>
						                </div> 
						             </div>
						             <div class="col-md-12 col-xs-12 col-user-detail-template">
						                <ul class="list-inline ul-remove-margin ul-name-font-size">
						                  
						                  <li class="template-profile-name"><a class="peoplenearbylink" href='{{{url("/profile/$user->id")}}}'>{{{$user->name }}},</a></li>
						                  <li class="li-remove-padding">{{{ $user->age() }}}</li>
						                  @if($user->onlineStatus())<li class="li-icon-styling"><i class="fa fa-circle icon-resize-small online-custom"></i></li>@endif
						                  
						                </ul>
						                <ul class="list-inline ul-remove-margin ul-age-styling">
						                  <li>{{{$user->city }}}@if($user->country)
										  							{{{$user->country}}}
										  						@endif
										  </li>
						                </ul>
						             </div>
						             <div class="col-md-12 col-xs-12 circle-div-custom">
							           @if($user->riseup)   
						              <div class="btn-icons"><i class="fa fa-arrow-circle-o-up rise_up" data-toggle="tooltip" title="Raised profile 8 minutes ago!"></i></div><time class="timeago rise_up_tooltip" datetime="{{{ $user->riseup->updated_at }}}" ></time>
								 	 @endif
								 	 
								 	  @if($user->isSuperpowerActivated())
								 	 <div class="btn-icons"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI1NiAyNTYiIGhlaWdodD0iMjU2cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiB3aWR0aD0iMjU2cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMkM2Mi4xLDI0Ny4zMjIsOC42NzgsMTkzLjksOC42NzgsMTI4QzguNjc4LDE5NC4yNzQsNjIuMSwyNDgsMTI4LDI0OCAgICBzMTE5LjMyMi01My43MjYsMTE5LjMyMi0xMjBDMjQ3LjMyMiwxOTMuOSwxOTMuOSwyNDcuMzIyLDEyOCwyNDcuMzIyeiIgZmlsbD0iI0I3MUMxQyIgb3BhY2l0eT0iMC4yIi8+PHBhdGggZD0iTTEyOCw4LjY3OEMxOTMuOSw4LjY3OCwyNDcuMzIyLDYyLjEsMjQ3LjMyMiwxMjhDMjQ3LjMyMiw2MS43MjYsMTkzLjksOCwxMjgsOCAgICBTOC42NzgsNjEuNzI2LDguNjc4LDEyOEM4LjY3OCw2Mi4xLDYyLjEsOC42NzgsMTI4LDguNjc4eiIgZmlsbD0iI0ZGRkZGRiIgb3BhY2l0eT0iMC4yIi8+PGNpcmNsZSBjeD0iMTI4IiBjeT0iMTI4IiBmaWxsPSIjRjQ0MzM2IiByPSIxMTkuMzIyIi8+PC9nPjxsaW5lYXJHcmFkaWVudCBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaWQ9IlNWR0lEXzFfIiB4MT0iMTExLjQxOCIgeDI9IjIxMi4zNzg1IiB5MT0iMTExLjQwNzciIHkyPSIyMTIuMzY4MiI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojMjEyMTIxO3N0b3Atb3BhY2l0eTowLjIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMyMTIxMjE7c3RvcC1vcGFjaXR5OjAiLz48L2xpbmVhckdyYWRpZW50PjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMmM2NS45LDAsMTE5LjMyMi01My40MjIsMTE5LjMyMi0xMTkuMzIyYzAtMi41MzItMC4wODgtNS4wNDItMC4yNDQtNy41MzVsLTAuMDI0LTAuMzgxICAgYy0wLjAyNC0wLjM2OC0wLjA0My0wLjczNy0wLjA3LTEuMTAzbC0wLjA2NC0wLjc3MmMtMC4wMTktMC4yMjgtMC4wNDEtMC40NTUtMC4wNjEtMC42ODNsLTAuMTAyLTEuMTQ5ICAgYy0wLjAxLTAuMTAxLTAuMDE3LTAuMjAzLTAuMDI3LTAuMzAzbC0wLjEyNC0xLjIyMmwtNjcuMjM5LTY3LjJsMCwwbC03OC40ODMsNzQuMjlsOS41MjIsOC44OTNsLTIyLjU2NiwyOC45ODdsMTIuNzMyLDEyLjEyNiAgIGwtMjMuOTIsMzYuNDM3bDAsMGwwLDBsMzkuMTIzLDM4LjMxOGMwLjA2NywwLjAwNywwLjEzNSwwLjAxMiwwLjIwMiwwLjAxOWwxLjA2MywwLjA5NGMwLjE2NCwwLjAxNSwwLjMyOCwwLjAyOSwwLjQ5MywwLjA0NCAgIGwwLjc3MywwLjA2OWMwLjI2NCwwLjAyMSwwLjUyNiwwLjA0NCwwLjc5LDAuMDYzbDAuNDg3LDAuMDMxYzAuMzY5LDAuMDI2LDAuNzQsMC4wNDgsMS4xMSwwLjA3bDAuMTc2LDAuMDExICAgQzEyMy4yMjgsMjQ3LjI0MywxMjUuNjA1LDI0Ny4zMjIsMTI4LDI0Ny4zMjJ6IiBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIvPjxwb2x5Z29uIGZpbGw9IiNGRkVCM0IiIHBvaW50cz0iMTQ2Ljg2NCw5OS4zNTQgMTQ2LjcxNCw5OS4zNTMgMTc5LjM2Niw0Ny42NTIgMTAwLjg4NCwxMjEuOTQyIDExNy4zMjgsMTIxLjk0MiAxMTcuMjg5LDEyMS45OTIgICAgMTE3LjMyOCwxMjEuOTkyIDExMi42MTIsMTI4LjAwMSA4Ny44MzksMTU5LjgyMSAxMDguMzU4LDE1OS44MjEgMTA4LjE4MiwxNjAuMDg3IDEwOC4zNTgsMTYwLjA4NyA3Ni42NTIsMjA4LjM4NSAxNTcuMTU2LDEzNi4xNjggICAgMTM3Ljc0NCwxMzYuMTY4IDEzNy43OTcsMTM2LjEwMSAxMzcuNzQ0LDEzNi4xMDEgMTQ0LjE3LDEyNy45OTcgMTY2LjMyOCw5OS44MiAxNDYuNzE0LDk5LjU4OSAgIi8+PHBvbHlnb24gZmlsbD0iI0JGMzYwQyIgb3BhY2l0eT0iMC4yIiBwb2ludHM9IjE4MC40MzEsNDUuOTY2IDEwMC44MzEsMTIxLjk0MiAxMDAuODg0LDEyMS45NDIgMTc5LjM2Niw0Ny42NTIgICIvPjwvZz48L3N2Zz4=" class="super_power_image" data-toggle="tooltip" title="Super power activated!"></div>
								 @endif 
								 	 
								 	 @if($user->profile->popularity == 100)
								 	  <div class="btn-icons">
									 	  
									 	    <img data-toggle="tooltip" title="Very popular" class="popular_user_image" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgY29udGVudFNjcmlwdFR5cGU9InRleHQvZWNtYXNjcmlwdCIgY29udGVudFN0eWxlVHlwZT0idGV4dC9jc3MiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDEzNi4zMzMgMTIzIiBoZWlnaHQ9IjcwLjk2MjAwNTYxNTIzNDM4cHgiIGlkPSJMYXllcl8xIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0IiB2ZXJzaW9uPSIxLjAiIHZpZXdCb3g9IjI1LjkzNDAwMDAxNTI1ODc5IDE1LjQwNDAwMzE0MzMxMDU0NyA3MC45NjIwMDU2MTUyMzQzOCA3MC45NjIwMDU2MTUyMzQzOCIgd2lkdGg9IjcwLjk2MjAwNTYxNTIzNDM4cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHpvb21BbmRQYW49Im1hZ25pZnkiPjxnPjxwYXRoIGQ9Ik05MC4wOSw1NC41ODRjLTEuNTY2LTIuMDAxLTkuMDE1LTkuNTU1LTUuODAxLTE3Ljc1MWMwLDAtNy42MDYsNS45NDYtNC43NTEsMTQuNTYgICBjLTEuMzE4LTEuOTE2LTEzLjgzMy04LjQ1Mi0xMi43OTEtMzIuNTZjMCwwLTAuNzksMC43OC0yLDIuMTQ5djYxLjgzNmM2LjkwMS0wLjE5MywxMi4wOTctMi4yNzEsMTIuMDk3LTIuMjcxICAgUzk2Ljg5Niw3MC40NzMsOTAuMDksNTQuNTg0eiIgZmlsbD0iI0QyNjIzRSIvPjxwYXRoIGQ9Ik01MS44MzksNDguMjAyYzAsMCwxLjk3OSwxNS45NTgsOC41NzUsMTguNTE0YzAsMC0xMy4xOTEtMy4xOTMtMTUuODMtMTkuMTU0ICAgYy0wLjY1OCwxLjkxNi0xOC42NSwxOS4yNzUsNi41NDYsMzIuNjY0YzQuNjQ5LDIuMTM5LDkuNDQ1LDIuNzExLDEzLjYxNywyLjU5NFYyMC45ODNDNjAuNjg2LDI1LjU4MSw1MS44MzksMzYuODg5LDUxLjgzOSw0OC4yMDJ6ICAgIiBmaWxsPSIjRUM2QzUxIi8+PC9nPjwvc3ZnPg=="  />
									 	  
								 	  </div>
								@endif 
								 	 
						              <!-- <button type="button" class="btn btn-default div-circle div-circle-blue btn-blue-custom"></button> -->
						             </div>
						           </div> 
						         </div>
							
							   
								 	
								 	 								

								{!! $nearByUsers->render() !!}
							@endforeach
							</div>
								
						@else
							
							<div class="alert alert-info">
							  {{{trans_choice('app.no_people',1)}}}
							</div>
						@endif
							<!-- end start people nearby -->
						</div>
					</div>
				</div>
							
					@endsection
		@section('scripts')

		
		<style>
			
	.btn-icons{
		
		    min-height: 41px;
		    float:left;
	}
			
	@font-face {
  font-family: 'Roboto';
  src: url('roboto.eot'); /* IE9 Compat Modes */
  src: url('roboto.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
  url('roboto.woff') format('woff'), /* Modern Browsers */
  url('roboto.ttf')  format('truetype'), /* Safari, Android, iOS */
  url('roboto.svg#svgFontName') format('svg'); /* Legacy iOS */
}

/* use the font */
body {
  font-family: 'Roboto', sans-serif;
}

	</style>
 <script src="@theme_asset('js/salvattore.js')"></script>
	   <script type="text/javascript">


function initMap() { 
	
			var lat = parseFloat({{{$auth_user->profile->latitude}}});
            var lng = parseFloat({{{$auth_user->profile->longitude}}});
            var latlng = new google.maps.LatLng(lat, lng);
            var geocoder = geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[6]) {
                        //alert("Location: " + results[5].formatted_address);
                        
                        $('#txtPlaces1').val(results[6].formatted_address);
                    }
                }
            });


        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces1'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();

                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();


             for (var i=0; i<place.address_components.length; i++) {
            for (var b=0;b<place.address_components[i].types.length;b++) {


                 if (place.address_components[i].types[b] == "country") {
                    //this is the object you are looking for
                    var country= place.address_components[i];

                   
                }
                if (place.address_components[i].types[b] == "locality") {
                    //this is the object you are looking for
                    var city= place.address_components[i].long_name;

                   
                }
                
            }
        }
        //city data
      







                var country = country.long_name;
                
                document.getElementById('lat').value = latitude;
                document.getElementById('lng').value = longitude;
                document.getElementById('country').value = country;
                document.getElementById('city').value = city;
                
                $('.enter_loc').fadeIn('slow');
                // var mesg = "Address: " + address;
                // mesg += "\nLatitude: " + latitude;
                // mesg += "\nLongitude: " + longitude;
                
            });
        });

}
    </script>
    
  
    
   
    
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap"></script>

	
	<script>
		
		function set_location()
		{
			
			var Request= {
				
				city:$('#city').val(),
				country:$('#country').val(),
				lat:$('#lat').val(),
				long:$('#lng').val()	
			};
			
			$.post("{{{ url('user/profile/location/update') }}}",Request, function(data){


				toastr.info('Location set successfully');
				window.location.reload();

			});

			
		}
	</script>	
	
	
	<script>
		$('.rise_up').mouseover(function(){
			
			
			$(this).attr('title','Profile was raised '+$(this.nextElementSibling).text());
		})
	</script>
	
	
	
	   @endsection
