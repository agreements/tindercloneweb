<?php use App\Components\Theme;?>
<!doctype html>
<html>
<head>
<meta name="csrf-token" content="{{{ csrf_token() }}}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{{$website_title}}}</title>
        <link rel="stylesheet" href="{{{asset('css/bootstrap3.3.6.min.css')}}}">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="{{{asset('css/bootstrap-datepicker3v1.6.0.css')}}}">
        <link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/css/reset.css')">
        <link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/css/style-alllanding.css')">
        <link rel="stylesheet" href="{{{asset('css/bootstrap3.3.6.min.css')}}}">
        <link href="@plugin_asset('LandingPagesPlugin/css/landing-custom.css')" rel="stylesheet">
        <link rel="stylesheet" href="{{{asset('css/animate2.1.0.min.css')}}}">

<link href="@plugin_asset('LandingPagesPlugin/css/landing-crossbrowser.css')" rel="stylesheet">
<script src="{{{asset('js/jquery1.12.0.min.js')}}}"></script>
        <script src="{{{asset('js/bootstrap3.3.6.min.js')}}}"></script>
        <script src="{{{asset('js/bootstrap-datepicker1.6.0.js')}}}"></script>
<script src="@plugin_asset('LandingPagesPlugin/js/modernizr.js')"></script>
<script src="@plugin_asset('LandingPagesPlugin/js/wow.js')"></script>
<script src="@plugin_asset('LandingPagesPlugin/js/moment.min.js')"></script>
<script src="@plugin_asset('LandingPagesPlugin/js/underscore.js')" type="text/javascript"></script>
<link href="@plugin_asset('LandingPagesPlugin/css/toastr.css')" rel="stylesheet">
<script src="@plugin_asset('LandingPagesPlugin/js/toastr.js')"></script>
<link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/Trendy/css/pinklanding.css')">


<style>
.loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url("@plugin_asset('LandingPagesPlugin/images/heart_small.gif')")  50% 50% no-repeat rgb(249,249,249);
  opacity: 0.7;
}
.social_login_cnt{
  margin-top: 0px;
  }
.btn--google {
    background: #dc5050;
    color: #fff;
    padding: 7px 20px 7px;
    border-radius: 16px;
    font-size: 1em;
    line-height: 1.3572;
    transition: background .2s,color .2s;
    margin-right: 4%;
}
.icongoogle {
    background-color: white;
    color: #dc5050;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    line-height: 1.5em;
    margin-right: 2%;
    margin: -2px 10px -6px 0;
}
.display_in_block {
    display: inline-block;
}
.profile_drop_menu {
    border-radius: 35px;
    color: rgba(0, 0, 0, 0.68);
    background: white;
    line-height: 4px;
    padding: 3px;
    border: 1px solid rgba(0, 0, 0, 0.61);
}
.social-dropdown-div-ul {
    top: 140%;
    left: -13px;
    padding: 8px 8px;
    border-radius: 11px;
}
.social-dropdown-div-ul>li>a {
    margin-bottom: 2px;
}
.btn--facebook {
    background: #3464d4;
    color: #fff;
    padding: 7px 24px 7px;
    border-radius: 16px;
    font-size: 1em;
    line-height: 1.3572;
    transition: background .2s,color .2s;
}
.iconfb {
    background-color: white;
    color: #3464D4;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    line-height: 1.5em;
    margin-right: 2%;
    margin: -2px 10px -6px 0;
}
.btn--facebook:hover {
    background: #2851AF !important;
    color: #fff !important;
}
.btn--google:hover {
    background: #BD3E3E !important;<meta name="csrf-token" content="{{{ csrf_token() }}}">
    color: #fff !important;
}
.dropdown-custom-styling {
    float: right;
    margin-right: 13%;
    margin-top: 0.3%;
}
.validation-error {
    color: #AD4646;
    display: none;
    margin-top:-9px;
    font-size:13px;
}
.liteoxide-purpose {
    font-weight: 400;
    font-size: 11px;
    opacity: 0.7;
}
.liteoxide-purpose {
    width: 87%;
}
.date-input-addon
{
 padding-top:20px;
}
#options
{
 top:-20px;
}
.text_black
{
 color:black;
}
.alert-danger {
    border-radius: 4px;
    margin-bottom: 4px;
   padding-left: 9px;
    padding-top: 9px;
    padding-bottom: 9px;
  }
.social_margin {
    display: flex;
    margin-left: 19%;
}
.social_margin li {
    list-style-type: none;
}
</style>
<body>

<div class="loader"></div>  

 <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
			<img class="logo_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
			<ul class="list-inline display_inline float_right display_flex">
        <li class="text_black opacity_7 signin_padding">{{{trans_choice('LandingPagesPlugin.top_signup_text',0)}}}</li>
				<li class="li-padding">
					<button type="button" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn" onclick="window.location = '{{{url('/register')}}}'">{{{trans('app.signup')}}}</button>
				</li>
				<li>
					<div class="form-group display_block">
                       {{{Theme::render('top-header')}}}
                   </div>
				</li>
			</ul>
		</div>	
		<div class="col-md-12 col_bg_img col_main-height bg_size signin_main_height"  style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @plugin_asset('LandingPagesPlugin/Trendy/images/bg.png') @endif'); background-size:cover;height:100vh;">
		  <div class="row">
			<div class="col-md-6 col-xs-12 col_main-text-margin">
				<h3 class="text_white head_opacity users_para_padding">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_top',0)}}}</h3>
				<h2 class="text_white users_para_padding para_width font_bold">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading',1)}}}</h2>
				<div class="text-center social_margin">
				      {{{Theme::render('login')}}}
               </div>
               
            <!--<div class="download_app_margin_top">
           <p class="text_white opacity_7">Download our App from</p>
           <ul class="list-inline display_inline">
           <li>
            <a href="#"><img src="@theme_asset('images/google.png')"></a>
           </li>
           <li>
            <a href="#"><img src="@theme_asset('images/apple.png')"></a>
           </li>
           </ul>
          </div>-->
          
           </div>
  
           <div class="col-md-4 float_right form_margin form_bg form_padding form_width form_color">
              <form id="form1" action = "{{{ URL::to('login') }}}" method = "POST">
	            {!! csrf_field() !!} 
	            <input type = "hidden" value = "" id = "lat" name = "lat">
	            <input type = "hidden" value = "" id = "lng" name = "longi">
                      
              @if(session()->has('message'))
	                            <div class="alert-danger">
	                                {{{session('message')}}}{{{session()->forget('message')}}}
	                            </div>
	                          @elseif(session()->has('emailChange'))
	                            <div class="alert-danger">
	                               {{{session('emailChange')}}}
	                               {{{session()->forget('emailChange')}}}
	                            </div>         
	                          @endif
            
             <div class="col-md-12">
              <div class="row">           
             <div class="form-group">
              <label class="control-label" for="email">{{{trans('app.email')}}}</label>
              <input type="email"  name="username" class="form-control input_remove_border remove_boxshadow form_color" id="email">
             </div>
                <p class="validation-error" id="email-error">{{{trans('app.email_required')}}}</p>
             </div>
             </div>
             
              <div class="col-md-12">
              <div class="row">
             <div class="form-group">
              <label class="control-label" for="pwd1">{{{trans('app.password')}}}</label>
              <input type="password"  name="password" class="form-control input_remove_border remove_boxshadow form_color" id="pwd1">
             </div>
                <p class="validation-error" id="password-error1">{{{trans('app.password')}}} {{{trans('app.is_required')}}}</p>
             </div>
             </div>
            <div class="col-md-12">
                    <div class="row">

                <div class="form-group" style="position:relative">
              <input type="checkbox" class="form-control remember-me-checkbox" name="remember_me" style="width:auto;">
              <span style="position: absolute;top: 17px;margin-left: 5px;width: 100%;font-size:16px">{{{trans('LandingPagesPlugin.remember_me')}}}</span>
             </div>
             </div>
             </div>
            <div class="col-md-12">
              <div class="row">
             <div class="form-group">
               <button type="submit" class="btn btn-primary btn-block btn_signup_bg btn_border-radius">{{{trans('app.signin')}}}</button>
             </div>
             </div>
             </div>
            <a id="forgot-password-link" href="#/" class="forgetPassword" >{{{trans_choice('LandingPagesPlugin.forgot_password_link_text',4)}}}</a>
          </form>
          <form id="forgot-password-form" action="{{{url('/forgotPassword/submit')}}}" method="POST" style="display:none;">
			                   {!! csrf_field() !!} 
	                      <div class="row">
	                        
	                          <div class="col-md-12">
	                            <div class="form-group">
	                              <label class="control-label" for="username_forgotpwd">{{{trans('app.email')}}}</label> 
	                            <input type="text" name="username" id="username_forgotpwd" class="form-control input_remove_border remove_boxshadow form_color" required>
	                            </div>
	                          </div>
	                          
	                          <div class="col-md-12">
	                            <div class="form-group ">
                                      
	                               <p class="text_white text_black">{{{trans_choice('LandingPagesPlugin.reset_password_text',5)}}}</p> 
	                            
	                            </div>
	                          </div>
	                         
	                          <div class="col-md-12">
		                          <a href="{{{url('/login')}}}" class="rememberPassword" >{{{trans_choice('LandingPagesPlugin.remember_password_link_text',6)}}}</a>
	                           <button type="submit" class="btn btn-primary border_none btn_signup_bg pull-right" id="reset">{{{trans_choice('LandingPagesPlugin.reset_password_button_text',7)}}}</button>
	                          </div>
	                        </div>
		                   </form>
        </div>
    





<script>
	$.ajaxSetup({ 
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
</script>	


 <script>
 $(document).ready(function()
 {
 $('.form-control').on('focus blur', function (e) {
    $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
}).trigger('blur');
});
 </script>  
 <script>
      $(document).ready(function() {
        $("#datetimepicker")
              .datepicker({
                  format: 'dd/mm/yyyy'
              });
        });
            
</script>

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
                // mesg += "\nLatitude: " + latitude;
                // mesg += "\nLongitude: " + longitude;
                
            });
        });

}
</script>
<script src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap"
        async defer></script>



<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
})
</script>
<script>
$(document).ready(function(){
 $("#forgot-password-link").click(function(){
  $("#forgot-password-form").show();
  $("#form1").hide();
});
});
</script>


</body>
</html>
