<?php use App\Components\Theme; ?>
@extends(Theme::layout('master-nocols'))
        @section('content')
          @parent
    <div class="centered_div">
    <form role="form" method = "POST" action = "{{{ url('/peoplenearby/filter') }}}" enctype="multipart/form-data" id="peoplenearby-filter">
                {!! csrf_field() !!}
     <div class="col-md-12 col-xs-12 top_chat_div top_location_div">
     	<div class="row">
         
	         <div class="col-md-6 col-xs-6 location_people_div">
	            <div class="form-group form-inline">
	               <label class="meet_people_label" for="txtPlaces">Meet New People Near</label>
	               <input autocomplete="on" name="city" placeholder="" class="form-control people_location_input"  id="txtPlaces"  type="text">
	                         
	                         <input type="hidden" id="lat" name="lat" value=""/>
	                         <input type="hidden" id="lng" name="lng" value=""/>
	                         <input type="hidden" id="city" name="city" value=""/>
	                         <input type="hidden" id="country" name="country" value=""/>
	            </div>
	         </div>

	         <div class="col-md-6 col-xs-6 location_filter_div">
	            <div class="form-group new_people_form_group">
	                 <label for="sel1">Looking for</label>
	                  <select class="form-control" id="sel1" name="hereto">
	                    <option>Dating</option>
	                    <option>Make New Friends</option>
	                    <option>Chatting</option>
	                    <option>For Serious Relationship</option>
	                  </select>
	            </div>
	             <div class="form-group new_people_form_group">
	                 <label for="sel1">With</label>
	                  <select class="form-control" id="sel1" name="gender">
	                    <option value="M">Male</option>
	                    <option value="F">Female</option>
	                    <option value="both">All</option>
	                  </select>
	            </div>
	            <div class="form-group new_people_form_group age_form">
	                 <label for="sel1">Between Ages</label>
	                  <div class="form-group">
	                  <select class="form-control" id="age1" name="from_age">
	                    <option>23</option>
	                    <option>24</option>
	                    <option>25</option>
	                  </select>
	                  <i class="fa fa-minus age_icon"></i>
	                  <select class="form-control" id="age2" name="to_age">
	                    <option>25</option>
	                    <option>26</option>
	                    <option>27</option>
	                  </select>
	                  </div>
	            </div>
	         </div>

	     </div>

	     <div class="row">
	     	<div class="col-md-12 col-xs-12 " style="text-align:center">
	     		
	     		<button type="submit" class="btn btn-primary">Save</button>
	     	</div>
	     </div>
     </div>
 </form>
     <div class="col-md-12 col-xs-12 interest_img_div">
        <div class="row">
           
           <div class="col-md-12 col-xs-12 interest_main_div">
              <h4 class="img_div_text">Get Connected,Chat and Date-Find Online Dating Profiles in {{{$city}}} Today!</h4>
              @foreach($users as $user)
              <a href="{{{url('profile/'.$user->id)}}}"><div class="img-div" style="background:url({{{$user->profile_pic_url()}}});">
                 <ul class="list-inline img_ul">
                   <li class="display_block user_name_li">@if($user->onlineStatus())<span class="online-icon"><i class="fa fa-circle"></i></span>@endif{{{$user->name}}},{{{$user->age()}}}</li>
                   <li class="display_block user_city_li">@if($user->city != ''){{{$user->city}}},@endif @if($user->country != ''){{{$user->country}}}@endif</li>
                 </ul>
              </div></a>
              @endforeach
              {!! $users->render() !!}
              
              
           </div>
        </div>   
     </div>
     
  </div>
  @endsection
	@section('scripts')

		<script>
			$("#peoplenearby-filter").submit(function(e){
    			var city = $('#city').val();

    			
    			if (city == '') {
    				$("#peoplenearby-filter").attr('action', window.location.href);
    			} else {

    				var citytemp= city.split(' ');

    				var city_new='';
    				// for(i=0;i<citytemp.length;i++)
    				// {
    				// 	city_new = citytemp[i]+'-'
    				// }

    				city_new = citytemp.join('-');

    				city_new = city_new.toLowerCase();

    				$("#peoplenearby-filter").attr('action', '{{{ url('/peoplenearby/') }}}' + '/' + city_new);
    			}

			});
		</script>

		<script>
   $(document).ready(function(){ 
    $("#select-ul li").on("click", function() {
      $("#select-ul li").removeClass("active");
      $(this).addClass("active");
    });
   });
</script>
<script type="text/javascript">
function initMap() { 

        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces'));
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
                // var mesg = "Address: " + address;
                document.getElementById('city').value = city;
                // mesg += "\nLatitude: " + latitude;
                // mesg += "\nLongitude: " + longitude;
                
            });
        });

}
</script>
<script src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap"
        async defer></script>
<script>
$(document).ready(function(){
  var select = '';
for (i=18;i<=100;i++){
    select += '<option val=' + i + '>' + i + '</option>';
}
$('#age1').html(select);
$('#age2').html(select);
});
</script> 
<script>
  $(document).ready(function() {
    $("#age1").change(function () {
        var x=$(this).find('option:selected').next().val();
       var select = '';
        for (i=x;i<=100;i++){
          select += '<option val=' + i + '>' + i + '</option>';
        }
        $('#age2').html(select);
        
    });
  
  });
</script>
@endsection