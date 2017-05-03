	<?php use App\Components\Theme; ?>
	@extends(Theme::layout('master'))
					@section('content')
						@parent

						<div class="col-xs">
					      <div id="close-currentlocation1" class="col-md-12 people-current-location-tab">
					       <div class="row">
					        <div class="col-md-6 people-nearby-current-picker">
					        
					        <i class="material-icons md-24 md-dark material-pindrop">pin_drop</i>
					        <input autocomplete="on" name="city" placeholder="Enter a location" class="txtPlaces" id="txtPlaces1" style="width: 137px" type="text">
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
					        <i onclick="closeMark()" class="material-icons md-24 people-nearby-filter-button">close</i>
					        </div>
					       </div>
	                      </div>
	                      <div id="hidden" class="col-md-12 people-nearby-filter-div" style="display:none">
	                      <div class="row">
	                        <div class="col-md-4 people-nearby-show">
	                           <span class="show-text">Show</span>
	                            <input id="rad1" type="radio" name="rad"><label for="rad1">Guys</label>
	                             <input id="rad2" type="radio" name="rad"><label for="rad2">Girls</label>
	                           </div>
	                      <div class="col-md-8 people-nearby-distance">
	                    
	                      </div>
	                      
	                      <div class="col-md-4 people-nearby-age">
	                       
	                      <div class="rang_slider1">
							  <p>
								 <label for="age">Age:</label>
									 <input type="text" id="age12" name = "age1" value = "27-64">
							  </p>
							   <div id="slider-age1"></div>
						  </div>
						  </div>
	                      <div class="col-md-8 people-nearby-savechanges">
	                      <button type="button" class="btn btn-primary people-nearby-savechanges-button">Save Changes</button>
	                      </div>
	                      
	                      </div>
	                      </div>
						<div class="cont-cover">

							<div class="cont-header">

						<!-- start people nearby -->
						@if(count($nearByUsers) > 0)
						<div class="col-md-12 person_cnt">
							@foreach($nearByUsers as $user)
							
							   
								<div class="col-md-4 col-xs-6 person_box" style="
    background-image: url('{{{$user->profile_pic_url}}}');
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
">

<!-- 									<img src="{{{$user->profile_pic_url}}}"> -->
			                     	<ul class="list-inline">
					                    <li ><i class="fa fa-circle small_circle" style="color:#8DC63F;display:inline"></i></li>
					                    <li class="user_name">{{{$user->name }}},  {{{ $user->age() }}}</li>
										<p>{{{$user->city }}},IN{{--{{{$user->country}}}--}}</p>
								 	</ul>
								 	
								 	ul>
								 	  @if($user->riseup)
								 	<i class="fa fa-arrow-circle-o-up rise_up" data-toggle="tooltip" title="Raised profile 8 minutes ago!"></i>
								 	<time class="timeago rise_up_tooltip" datetime="{{{ $user->riseup->updated_at }}}" ></time>
								 	@endif
								 	
								 	
								 	@if($user->isSuperpowerActivated())
								 	<img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI1NiAyNTYiIGhlaWdodD0iMjU2cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiB3aWR0aD0iMjU2cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMkM2Mi4xLDI0Ny4zMjIsOC42NzgsMTkzLjksOC42NzgsMTI4QzguNjc4LDE5NC4yNzQsNjIuMSwyNDgsMTI4LDI0OCAgICBzMTE5LjMyMi01My43MjYsMTE5LjMyMi0xMjBDMjQ3LjMyMiwxOTMuOSwxOTMuOSwyNDcuMzIyLDEyOCwyNDcuMzIyeiIgZmlsbD0iI0I3MUMxQyIgb3BhY2l0eT0iMC4yIi8+PHBhdGggZD0iTTEyOCw4LjY3OEMxOTMuOSw4LjY3OCwyNDcuMzIyLDYyLjEsMjQ3LjMyMiwxMjhDMjQ3LjMyMiw2MS43MjYsMTkzLjksOCwxMjgsOCAgICBTOC42NzgsNjEuNzI2LDguNjc4LDEyOEM4LjY3OCw2Mi4xLDYyLjEsOC42NzgsMTI4LDguNjc4eiIgZmlsbD0iI0ZGRkZGRiIgb3BhY2l0eT0iMC4yIi8+PGNpcmNsZSBjeD0iMTI4IiBjeT0iMTI4IiBmaWxsPSIjRjQ0MzM2IiByPSIxMTkuMzIyIi8+PC9nPjxsaW5lYXJHcmFkaWVudCBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaWQ9IlNWR0lEXzFfIiB4MT0iMTExLjQxOCIgeDI9IjIxMi4zNzg1IiB5MT0iMTExLjQwNzciIHkyPSIyMTIuMzY4MiI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojMjEyMTIxO3N0b3Atb3BhY2l0eTowLjIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMyMTIxMjE7c3RvcC1vcGFjaXR5OjAiLz48L2xpbmVhckdyYWRpZW50PjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMmM2NS45LDAsMTE5LjMyMi01My40MjIsMTE5LjMyMi0xMTkuMzIyYzAtMi41MzItMC4wODgtNS4wNDItMC4yNDQtNy41MzVsLTAuMDI0LTAuMzgxICAgYy0wLjAyNC0wLjM2OC0wLjA0My0wLjczNy0wLjA3LTEuMTAzbC0wLjA2NC0wLjc3MmMtMC4wMTktMC4yMjgtMC4wNDEtMC40NTUtMC4wNjEtMC42ODNsLTAuMTAyLTEuMTQ5ICAgYy0wLjAxLTAuMTAxLTAuMDE3LTAuMjAzLTAuMDI3LTAuMzAzbC0wLjEyNC0xLjIyMmwtNjcuMjM5LTY3LjJsMCwwbC03OC40ODMsNzQuMjlsOS41MjIsOC44OTNsLTIyLjU2NiwyOC45ODdsMTIuNzMyLDEyLjEyNiAgIGwtMjMuOTIsMzYuNDM3bDAsMGwwLDBsMzkuMTIzLDM4LjMxOGMwLjA2NywwLjAwNywwLjEzNSwwLjAxMiwwLjIwMiwwLjAxOWwxLjA2MywwLjA5NGMwLjE2NCwwLjAxNSwwLjMyOCwwLjAyOSwwLjQ5MywwLjA0NCAgIGwwLjc3MywwLjA2OWMwLjI2NCwwLjAyMSwwLjUyNiwwLjA0NCwwLjc5LDAuMDYzbDAuNDg3LDAuMDMxYzAuMzY5LDAuMDI2LDAuNzQsMC4wNDgsMS4xMSwwLjA3bDAuMTc2LDAuMDExICAgQzEyMy4yMjgsMjQ3LjI0MywxMjUuNjA1LDI0Ny4zMjIsMTI4LDI0Ny4zMjJ6IiBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIvPjxwb2x5Z29uIGZpbGw9IiNGRkVCM0IiIHBvaW50cz0iMTQ2Ljg2NCw5OS4zNTQgMTQ2LjcxNCw5OS4zNTMgMTc5LjM2Niw0Ny42NTIgMTAwLjg4NCwxMjEuOTQyIDExNy4zMjgsMTIxLjk0MiAxMTcuMjg5LDEyMS45OTIgICAgMTE3LjMyOCwxMjEuOTkyIDExMi42MTIsMTI4LjAwMSA4Ny44MzksMTU5LjgyMSAxMDguMzU4LDE1OS44MjEgMTA4LjE4MiwxNjAuMDg3IDEwOC4zNTgsMTYwLjA4NyA3Ni42NTIsMjA4LjM4NSAxNTcuMTU2LDEzNi4xNjggICAgMTM3Ljc0NCwxMzYuMTY4IDEzNy43OTcsMTM2LjEwMSAxMzcuNzQ0LDEzNi4xMDEgMTQ0LjE3LDEyNy45OTcgMTY2LjMyOCw5OS44MiAxNDYuNzE0LDk5LjU4OSAgIi8+PHBvbHlnb24gZmlsbD0iI0JGMzYwQyIgb3BhY2l0eT0iMC4yIiBwb2ludHM9IjE4MC40MzEsNDUuOTY2IDEwMC44MzEsMTIxLjk0MiAxMDAuODg0LDEyMS45NDIgMTc5LjM2Niw0Ny42NTIgICIvPjwvZz48L3N2Zz4=" class="super_power_image" data-toggle="tooltip" title="Super power activated!">
								 	@endif
			                    </div>
								

								{!! $nearByUsers->render() !!}
							@endforeach
							</div>
						@else
							
							<div class="alert alert-info">
							  No people are nearby.
							</div>
						@endif
							<!-- end start people nearby -->
						</div>
					</div>
				</div>
							
					@endsection
		@section('scripts')
		<style>
		.col-picture
		{
			background-image: url('@asset('images')/person.jpg');
			background-size: cover;
			height:239px;
			background-position:center;
			width:30%;
			margin-right:10px;
			margin-bottom:10px;
		}
	.profile_img{
	    width: 100%;
	    height: 100%;
	    border-top-left-radius: 5px;
	    border-top-right-radius: 5px;
	    border: 1px solid #C1B9B9;
	}
	.n_member{
		margin-bottom: 65px;
	}
	.n_member_profile{
		position: relative;
	}
	.bottom-tag 
	{
	    background: none repeat scroll 0 0 rgba(0, 0, 0, 0.5);
	    bottom: 0;
	    color: rgb(255, 255, 255);
	    font-size: 13px;
	    padding: 6px 8px;
	    text-align: left;
	    position: absolute;
	    width: 100%;

	}
	.profile_tag{
		width: 50px;
		height: 50px;
		position: absolute;
		top: 1px;
	}
	.right-tag{
		position: absolute;
		top: 1px;
		right: 0px;
	}
	.right-tag p{
	    background-color: #DC5050;
	    margin: 5px;
	    padding: 1px 7px;
	    font-size: 12px;
	    border-radius: 2px;
	}
	.vip-on{
		float: right;
	}
	.vip-on span{
	    background-color: #A79E9E;
	    padding: 0 5px;
	    border-radius: 2px;
	}

	
	.n_member_detail ul{
		color: #484646;
		padding:5px;
		font-size: 12px;
	}

	.n_member_detail ul li:first-child{
	    border: 1px solid #B3ACAC;
	    background-color: #F5F2F2;
	}
	.n_member_detail ul li:last-child{
		float: left;
		background-color: inherit;
		border:none;
	}
	a:hover{
		color:#ffffff;
	}

	</style>

	   <script type="text/javascript">


function initMap() { 

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
			
			$.post("{{{ url('/set_location') }}}",Request, function(data){


				toastr.info('Location set successfully');

			});

/*
			
			jQuery.ajax(
			    {
			        'type': 'POST',
			        'url': "{{{ url('/set_location') }}}",
			        'contentType': 'application/json',
			        'data': {
			            'content': formdata,
			            '_token': '{{{ csrf_token()}}}',
			        },
			        'dataType': 'json',
			        'success': function(data){} 
			    }
			);
*/

			
		}
	</script>	
	
	
		
	<script>
		$('.rise_up').mouseover(function(){
			
			
			$(this).attr('title','Profile was raised '+ $(this.nextElementSibling).text());
		})
	</script>
	
	   @endsection
