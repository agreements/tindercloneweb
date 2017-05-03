<?php use App\Components\Theme;?>
<!doctype html>
<html>
<head>
<meta name="csrf-token" content="{{{ csrf_token() }}}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{{$website_title}}}</title>
<link rel="stylesheet" href="@theme_asset('css/bootstrap.min.css')">
<link rel="stylesheet" href="@theme_asset('css/font-awesome.min.css')">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="@theme_asset('css/bootstrap-datepicker3.css')">
<link rel="stylesheet" href="@theme_asset('css/reset.css')">
<link rel="stylesheet" href="@theme_asset('css/style-alllanding.css')">

<link href="@theme_asset('css/landing-custom.css')" rel="stylesheet">
<link rel="stylesheet" href=" @theme_asset('css/animate.min.css')"><!-- load animate -->
<link rel="stylesheet" href="@theme_asset('css/flag.css')" type="text/css" media="screen" />

<link href="@theme_asset('css/landing-crossbrowser.css')" rel="stylesheet">
<script src="@theme_asset('js/jquery-1.11.3.js')"></script>
<script src="@theme_asset('js/bootstrap.min.js')"></script> 
<script src="@theme_asset('js/bootstrap-datepicker.js')"></script> 
<script src="@theme_asset('js/modernizr.js')"></script>
<script src="@theme_asset('js/wow.js')"></script>
<script src="@theme_asset('js/moment.min.js')"></script>
<script src="@theme_asset('js/underscore.js')" type="text/javascript"></script>
<link href="@theme_asset('css/toastr.css')" rel="stylesheet">
<script src="@theme_asset('js/toastr.js')"></script>
<link rel="stylesheet" href="@theme_asset('css/datingframework_lastlanding.css')">


<style>
.loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
  background: url("@theme_asset('images/heart_small.gif')")  50% 50% no-repeat rgb(249,249,249);
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
    background: #BD3E3E !important;
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

.social_p_margin
{
  display:none;
}
#options
{
  top:-20px;
}
.here-dropdown
{
  width:106%;
  text-align:left;
}
.chatmeetdate-caret
{
float:right;
margin-top:8px;
}
.border-left_pseudo:before
{
  content: '';
    position: absolute;
    top: 11px;
    left: -92px;
    height: 96%;
    width: 52%;
    border-left: 1px solid rgba(255, 255, 255, 0.25);
}
.border-left_pseudo:after
{
  content: url("@theme_asset('images/or.png')");
  position: absolute;
  left: -117px;
  bottom: 246px;
}

.btn .caret
{
 margin-left:10px;
}


.fullscreen-bg {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    overflow: hidden;
    z-index: -100;
}

.fullscreen-bg__video {
    position: absolute;
    top: 50%;
    left: 50%;
    width: auto;
    height: auto;
    min-width: 100%;
    min-height: 100%;
    -webkit-transform: translate(-50%, -50%);
       -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
	    transform: translate(-50%, -50%);
}

@media (max-width: 767px) {
    .fullscreen-bg {
        background: url('http://dev2.slicejack.com/fullscreen-video-demo/img/videoframe.jpg') center center / cover no-repeat;
    }

    .fullscreen-bg__video {
        display: none;
    }
}





</style>


</head>
<body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @theme_asset('images/bg.png') @endif');background-size:cover;">


	
	<div class="fullscreen-bg">
	    <video loop muted autoplay poster="img/videoframe.jpg" class="fullscreen-bg__video">
	        <source src="http://dev2.slicejack.com/fullscreen-video-demo/video/big_buck_bunny.webm" type="video/webm">
	        <source src="http://dev2.slicejack.com/fullscreen-video-demo/video/big_buck_bunny.mp4" type="video/mp4">
	        <source src="http://dev2.slicejack.com/fullscreen-video-demo/video/big_buck_bunny.ogv" type="video/ogg">
	    </video>
	</div>
  
  <div class="loader"></div>  

  <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
      
      <ul class="list-inline display_inline float_right display_flex">
       <li>
       <p class="text_white opacity_7 signin_padding">{{{trans('app.alredy_member')}}}</p>
       </li>
        <li class="li-padding">
          <a href="{{{url('/login')}}}" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn">{{{trans('app.signin')}}}</a>
        </li>
        <li>
          <div class="form-group display_block">
                       {{{Theme::render('top-header')}}}
                   </div>
        </li>
      </ul>
    </div>  
    <div class="col-md-12 col_bg_img col_main-height bg_size">
      <div class="text-center logo_col_margin">
        <img src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
        <h4 class="text-center text_white">{{{trans_choice('app.register_long_text',0)}}}</h4>
      </div>
      <div class="row">
      <div class="col-md-6 col-xs-12 col_main-text-margin text-center">
        <div class="social_margin text-center">
              {{{Theme::render('login')}}}

         </div>
          <p class="text_white opacity_7 text-center">{{{trans_choice('app.register_long_text',1)}}}</p> 
         
      </div>
        
           <form method = "POST" action = "{{{ URL::to('register') }}}"  id="registration-form" name="edit-profile">
                  <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                  <input type="hidden" name="gender" value="" />
        <div class="col-md-4 form_margin form_bg form_padding form_width form_boxshadow border-left_pseudo">
            <h4 class="text_white text-center opacity_7 header_margin">{{{trans_choice('app.register_long_text',2)}}}</h4>
            <div class="form-group form_group_margin-bottom">
              <input type="text" class="form-control remove_boxshadow input_height" name="name" id="name" placeholder="{{{trans('app.name')}}}" data-toggle="tooltip" title="Full Name Required" data-placement="right">
             </div>
             
            <div class="form-group form_group_margin-bottom">
              <input type="email" class="form-control remove_boxshadow input_height" name="username" id="email" placeholder="{{{trans('app.email')}}}" data-toggle="tooltip" title="Email Required" data-placement="right">
             </div>
              <div class="form-group form_group_margin-bottom">
               <div class="row">
                  <div class="col-md-6 col-xs-6">
	                  <span style="color: white;font-size: 13px;position: relative;bottom: 4px">{{{trans('app.dob')}}}</span>
                    <div class="input-group date input_max_width" style="bottom: 4px;">

<!--
                    <input type="text" placeholder="{{{trans('app.dob')}}}" name="dob" id="dob"  class="form-control remove_boxshadow input_height border_right_none" data-toggle="tooltip" title="Date of Birth Required" data-placement="left">
                    





<span class="input-group-addon input_remove_border">
                        <span class="fa fa-caret-down"></span>
                    </span>
-->
 

										 <select class="dobpart" id="dobday" required=""></select>
										<select class="dobpart" id="dobmonth" required="" style="    width: 36%;"></select>
										<select class="dobpart" id="dobyear" required=""></select>	  
										  <input id="dob" name="dob" type="hidden" placeholder="{{{trans('app.dob')}}}" class="form-control input-md" required="">
										    

										  



                   </div>
             </div>
             
              <div class="col-md-6 col-xs-6">
                <input autocomplete="off" name="city" placeholder="{{{trans('app.city')}}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="txtPlaces1" type="text" data-toggle="tooltip" title="Location Required" data-placement="right">
                <input type="hidden" id="lat" name="lat" value=""/>
                         <input type="hidden" id="lng" name="lng" value=""/>
                         <input type="hidden" id="city" name="city" value=""/>
                         <input type="hidden" id="country" name="country" value=""/>
              </div>
              </div>
              </div>
              <div class="form-group form_group_margin-bottom">
                <input type="hidden" value="" id="gender_val" name="gender_val"/>
              <div class="row">
                @foreach($sections as $section)
                  @foreach($section->fields() as $field)
                    @if($field->on_registration == 'yes' && $field->type == "dropdown")
                    <div class="col-md-6 col-xs-6">
                      <select  name="{{{ $field->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$field->code}}}">
	                     <option data-value="0" value="0">{{{trans('custom_profile.'.$field->code )}}}</option>  
                         @foreach($field->field_options() as $option)
                          <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                         @endforeach
                      </select>
                    </div>
                    @elseif($field->on_registration == 'yes')
                    <div class="col-md-6 col-xs-6">
                      <input type="text" id="" name="{{{$field->code}}}" value="" placeholder="{{{$field->name}}}"/>
                    </div>
                    @endif
                  @endforeach
                @endforeach

                @if($gender->on_registration == 'yes')
                  <div class="col-md-6 col-xs-6">
                    <select  name="{{{ $gender->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$gender->code}}}">
                       @foreach($gender->field_options() as $option)
                        <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                       @endforeach
                    </select>
                  </div>
                @endif
              
              </div>
              </div>
             <div class="form-group form_group_margin-bottom">
              
              <input type="password" class="form-control remove_boxshadow input_height" id="pwd1" name="password" placeholder="{{{trans('app.password')}}}" data-toggle="tooltip" title="Password Required" data-placement="right">
             </div>
             <div class="form-group form_group_margin-bottom">
              
              <input type="password" class="form-control remove_boxshadow input_height" id="pwd2" name="password_confirmation" placeholder="{{{trans('app.password_confirm')}}}" data-toggle="tooltip" title="Confirm Password Required" data-placement="right">
             </div>
             <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block btn_signup_bg input_height border_none signup_height">{{{trans('app.signup')}}}</button>
             </div>
             <div class="form-group">
              <p class="sign_up_text text_white">{{trans_choice('app.register_long_text',3)}} <a href="{{{url('/pages/terms-and-conditions')}}}">{{trans('app.terms_conditions')}} </a> and <a href="{{{url('pages/privacy-policy')}}}">{{trans('app.privacy_policy')}}</a></p>
              <p class="sign_up_text text_white"> {{trans_choice('app.about_us',0)}} <a href="{{{url('/pages/about-us')}}}"> {{trans_choice('app.about_us',1)}} </a></p>
             </div>
          </form>
           </div>
           
    </div>

<script>

  $('#gender_val').val( $("select[name='gender'] option:selected" ).data('value'));

$("select[name='gender']").on('change',function(){

  $('#gender_val').val( $( "select[name='gender'] option:selected" ).data('value'));

 }); 
 
 
 

</script>
<script>
$(document).ready(function(){


$('.dropdown-menu a').on('click', function(){    
   $(this).parent().parent().prev().html($(this).html() + '<span class="caret"></span>');    
});
});
</script>

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
$(document).ready(function()
{
 

  $("#registration-form").submit(function(e){
    e.preventDefault();
    $('.loader').fadeIn();
    
    $(".validation-error").hide();
    
    var name = $("#name").val();
    
    var email = $("#email").val();
    var dob = $("#dob").val();
    var location = $("#txtPlaces1").val();

     var password = $("#pwd1").val();
     var confirm_password = $("#pwd2").val();

     var flag = 0;

     if(name == ''){
      
       $("#name").addClass('border-red');
       
       $("#name").attr("placeholder","{{{trans('app.name')}}}");
       
      flag = 1;
       $('.loader').fadeOut();
       }
      
  
     if(dob == ''){
        $("#dob").addClass('border-red');
         $("#dob").attr("placeholder","{{{trans('app.dob_required')}}}");
      flag = 1;
       $('.loader').fadeOut();
       }
       
     
 

     if(email == ''){
        $("#email").addClass('border-red');
         $("#email").attr("placeholder","{{{trans('app.email_required')}}}");
      flag = 1;
       $('.loader').fadeOut();
     }
     else if(!/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(email)){
     $('#email').addClass('border-red');
     $("#email").attr("placeholder","{{{trans('app.valid_email')}}}");
       flag = 1;
       $('.loader').fadeOut();
     }
     

      if(location == ''){
       $("#txtPlaces1").addClass('border-red');
       $("#txtPlaces1").attr("placeholder","{{{trans('app.location_required')}}}");
      flag = 1;
       $('.loader').fadeOut();
      }
      
    
    

     if(password == '')
     {
       $("#pwd1").addClass('border-red');
       $("#pwd1").attr("placeholder","{{{trans_choice('app.password_validate_field',0)}}}");
     }
     else if(password.length < 8){
       $("#pwd1").addClass('border-red');
       $("#pwd1").attr("placeholder","P{{{trans_choice('app.password_validate_field',1)}}}");
       
      flag = 1;
       $('.loader').fadeOut();
     }
     else if(password != confirm_password){
      $("#pwd2").addClass('border-red');
      $("#pwd1").attr("placeholder","{{{trans_choice('app.password_validate_field',2)}}}");
     
      
      flag = 1;
       $('.loader').fadeOut();
      }
     


    if(flag == 0){

         $.ajax({
        type: 'post',
        url: '{{{url('/register')}}}',
        data: $(this).serialize(),
        success: function (response) {
          
          $('.loader').fadeOut();
      if(response.errors) { 


        if(response.errors.dob)
          //$("#dob-error").show().html(response.errors.dob);
                                         toastr.error(response.errors.dob);
                                         $('#dob').addClass('border-red');
          
        if(response.errors.username)
            //$("#email-error").show().html(response.errors.username);
                                                toastr.error(response.errors.username);
            
        if(response.errors.city)
        {
          //$('#location-error-para').show().html('City must be selected from suggestion!');
                                        toastr.error("{{{trans('app.city_select_suggestion')}}}");
        }
                


            }
             else
             {
               if(response.email_verify_required)
               {
             
                    toastr.info("{{{trans('app.email_verify_required')}}}");
                    toastr.success("{{{trans('app.registration_success')}}}");
               }

              else
               {
              
              toastr.success("{{{trans('app.registration_success')}}}");
       window.location.href = "{{{ url('/login') }}}";
               }

            }
        }

     });

                }


});


});
</script>

<script type="text/javascript">
$(window).load(function() {
  $(".loader").fadeOut("slow");
})
</script>
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip('hide'); 
});
</script>
<script>
    $(document).ready(function() {
 $(window).keydown(function(event){
   if(event.keyCode == 13) {
     event.preventDefault();
     return false;
   }
 });
});
</script>

	<script src="@theme_asset('js/dobPicker.min.js')"></script>
	
	
	<script>
	$(document).ready(function(){
	  $.dobPicker({
	    daySelector: '#dobday', /* Required */
	    monthSelector: '#dobmonth', /* Required */
	    yearSelector: '#dobyear', /* Required */
	    dayDefault: 'Day', /* Optional */
	    monthDefault: 'Month', /* Optional */
	    yearDefault: 'Year', /* Optional */
	    minimumAge: 8, /* Optional */
	    maximumAge: 100 /* Optional */
	  });
	});
	</script>
	
	<script>
		$('.dobpart').on('change',function(){
			
			
			$('#dob').val($('#dobday').val()+'/'+$('#dobmonth').val()+'/'+$('#dobyear').val());
			
		})
		
				
</script>
</body>
</html>