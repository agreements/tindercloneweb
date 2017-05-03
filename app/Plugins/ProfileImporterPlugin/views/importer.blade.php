@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
  .add-creditpackage-col {
    width: 100%;
    margin-bottom: 0px;
  }
  .import-as {
    color: white;
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.16);
  }
  .import-as > .package-label , .import-as > input {
    margin-right: 5px;
  }
</style>
	<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans('admin.profileimporter')}} {{trans_choice('admin.manage',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">

        <form class="form-horizontal" id="importer" role="form" method = "POST" enctype="multipart/form-data" action = "{{{ url('/admin/pluginsettings/profileimporter') }}}">
               {{ csrf_field() }}
          <div class="row">

               <div class="col-md-10 add-creditpackage-col add-interest-div">
                  <p class="add-credit-package-text">Importer Fields</p>

                  <div class="form-group import-as">
                      <label class="package-label">Import Profile as:</label>
                      <input type="radio" class="input-border-custom" name="import_as" checked value = "bot">Bot
                      <input type="radio" class="input-border-custom" name="import_as" value="user">User
                  </div>

                  <div class="form-group">
                     <label class="package-label">Csv or Excel File : </label>
                     <label class="input-label-custom"><input type="file" name="file" id="fileInput" class="input-custom-style"/></label>
                  </div>

                  <div class="form-group">
                     <label class="package-label">Profile Photos Folder Name : </label>
                     <input type="text" placeholder="Enter Folder Name" id = "photo_folder" name = "photo_folder" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                    <label class="checkbox-inline checkbox-inline-custom"><input name="pwd-for" type="radio" value="0" checked><span class="input-text">Specify Password</span></label> 
                    <label class="checkbox-inline checkbox-inline-custom"><input name="pwd-for" type="radio" value="1"><span class="input-text">Password in File</span></label>
                  </div>

                   <div class="form-group" id="pwd_all_users">
                        <label class="package-label" for="password_all">Password for all Users</label>
                        <input type="text" class="input-border-custom" id="password_all" name="password_all" placeholder="">
                    </div>

                    <div id="pwd-file-options" style="display:none">
                        <div class="form-group" id="pwd_col_no" >
                            <label class="package-label" for="password_column">Password Column No</label>
                            <input type="number" placeholder="Column No" id = "password_column" name = "password_column" value = "" class="input-border-custom">
                        </div> <!-- /control-group -->

                         <div class="form-group">                                            
                            <label class="package-label">Password Type</label>
                                  <label class="checkbox-inline checkbox-inline-custom"><input type="radio"  value="0" name="pwd-hashed"> <span class="input-text">Hashed </span></label> 
                                  <label class="checkbox-inline checkbox-inline-custom"><input type="radio" value="1" name="pwd-hashed" checked> <span class="input-text">Not Hashed </span></label> 
                        </div> 
                    </div>
                    
                    <div class="form-group">
                    <label class="checkbox-inline checkbox-inline-custom"><input name="location" type="radio" value="0" checked><span class="input-text">Specify Location</span></label> 
                    <label class="checkbox-inline checkbox-inline-custom"><input name="location" type="radio" value="1"><span class="input-text">Location Specified in File</span></label>
                  </div>

                  <div class="form-group float-label-control" id="city-autocomplete">
                     <input autocomplete="on" name="loc_city" placeholder="{{{trans('app.city')}}}" class="txtPlaces form-control input-border-custom"  id="txtPlaces1"  type="text">
                     <input type="hidden" id="lat" name="lat" value=""/>
                     <input type="hidden" id="lng" name="lng" value=""/>
                     <input type="hidden" id="city" name="city" value=""/>
                    <input type="hidden" id="country" name="country" value=""/>
                  </div>

                  <div id="loc-col" style="display:none">
                    <div class="form-group">
                       <label class="package-label">Latitude : </label>
                       <input type="number" placeholder="Column No" id = "col-lat" name = "col-lat" value = "" class="input-border-custom">
                    </div>

                    <div class="form-group">
                       <label class="package-label">Longitude : </label>
                       <input type="number" placeholder="Column No" id = "col-lng" name = "col-lng" value = "" class="input-border-custom">
                    </div>

                    <div class="form-group">
                     <label class="package-label">City : </label>
                     <input type="number" placeholder="Column No" id = "col-city" name = "col-city" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Country : </label>
                     <input type="number" placeholder="Column No" id = "col-country" name = "col-country" value = "" class="input-border-custom">
                  </div>

                  </div>

                  <div class="form-group">
                     <label class="package-label">Columns Separated With : </label>
                     <input type="text" placeholder="Enter Delimeter" id = "columns_separate" name = "columns_separate" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">User Id : </label>
                     <input type="number" placeholder="Column No" id = "id" name = "id" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Email : </label>
                     <input type="number" placeholder="Column No" id = "email" name = "email" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">First Name : </label>
                     <input type="number" placeholder="Column No" id = "fname" name = "fname" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Last Name : </label>
                     <input type="number" placeholder="Column No" id = "lname" name = "lname" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Date of Birth Format Specified In File: </label>
                     <input type="text" placeholder="Date format"  name = "dob_format" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Date of Birth(yyyy-mm-dd) : </label>
                     <input type="number" placeholder="Column No" id = "dob" name = "dob" value = "" class="input-border-custom">
                  </div>

                  <div class="form-group">
                     <label class="package-label">Gender : </label>
                     <input type="number" placeholder="Column No" id = "gender" name = "gender" value = "" class="input-border-custom">
                  </div>

          @foreach($field_sections as $sec)  
              <label class="package-label" style="text-align:center"><h3>{{{$sec->name}}} </h3></label> <br />
             <input type="hidden" id = "{{{$sec->id}}}" name = "{{{$sec->code}}}" value = "{{{$sec->id}}}" class="input-border-custom">
             @foreach($sec->fields() as $field)
             <div class="form-group">
                 <label class="package-label">{{{$field->name}}} :</label>
                 <input type="number" placeholder="Column No" id = "{{{$field->name}}}" name = "{{{$field->code}}}" value = "" class="input-border-custom"> <br />
            </div>
             @endforeach
          @endforeach

           <div class="form-group">
                 <label class="package-label">About Me :</label>
                 <input type="number" placeholder="Column No" id = "about_me" name = "about" value = "" class="input-border-custom"> <br />
            </div>
                  <button id = "" class="btn btn-info btn-addpackage btn-custom">{{trans('app.submit')}}</button>
               </div>
            </form>

          </div>

        </div>
      </section>
   </div>

@endsection
@section('scripts')
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
                // var mesg = "Address: " + address;
                document.getElementById('city').value = city;
                
            });
        });

}
    </script>
    @if($google_map_key != '')
      <script src="https://maps.googleapis.com/maps/api/js?&key={{{$google_map_key}}}&signed_in=true&libraries=places&callback=initMap"
        async defer></script>
    @else
      <script src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap"
        async defer></script>
    @endif

<script>
$(document).ready(function() {
       $("input[name='pwd-for']").click(function(){
            if($(this).val() == 1) {
                $('#pwd_all_users').hide();
                $('#pwd-file-options').show();
            } else {
                 $('#pwd_all_users').show();
                $('#pwd-file-options').hide();
            }
            
        });
    });
</script>

<script>
$(document).ready(function() {
       $("input[name='location']").click(function(){
            if($(this).val() == 1) {
                $('#city-autocomplete').hide();
                $('#loc-col').show();
            } else {
                 $('#city-autocomplete').show();
                $('#loc-col').hide();
            }
            
        });
    });
</script>
<script type="text/javascript">
  @if(session('error'))
    toastr.error("{{{session('error')}}}");
  @endif
  @if(session('success'))
    toastr.success("{{{session('success')}}}");
  @endif
</script>
@endsection