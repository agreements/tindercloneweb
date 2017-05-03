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
.social_margin {
    display: flex;
    margin-left: 19%;
}
.social_margin li {
    list-style-type: none;
}
.signup-error {
    background-color: red;
    color: white;
    border-radius: 0px;
    border-color: red;
    padding: 0px 0px 8px 0px;
}
.signup-error > .w3-closebtn {
    background: black;
    padding: 0px 7px 1px 6px;
    font-size: 25px;
    border-radius: 21px;
    position: relative;
    right: 17px;
    top: -18px;
    cursor: pointer;
}
</style>

</head>

</head>
<body>
	
	<div class="loader"></div>  

 <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
			<img class="logo_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
			<ul class="list-inline display_inline float_right display_flex">
        <li class="text_black opacity_7 signin_padding">{{{trans_choice('LandingPagesPlugin.top_signin_text',0)}}}</li>
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
		<div class="col-md-12 col_bg_img col_main-height bg_size"  style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @plugin_asset('LandingPagesPlugin/Trendy/images/bg.png') @endif')">
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
  <form method = "POST" action = "{{{ URL::to('register') }}}" id="registration-form" name="edit-profile">
           <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
           <div class="col-md-4 float_right form_margin form_bg form_padding form_width form_color">
             
            <h4 class="opacity_7">{{{trans_choice('LandingPagesPlugin.signup_top',8)}}}</h4>
            <div class="alert alert-danger signup-error" style="display:none">
                <span onclick="this.parentElement.style.display='none'" class="w3-closebtn">&times;</span>
                <span class= "error-text"></span>
            </div>
            <div class="col-md-12">
              <div class="row">
            <div class="form-group">
              <label class="control-label" for="name">{{{trans('app.name')}}}</label>
              <input type="text" name="name" class="form-control input_remove_border remove_boxshadow form_color" id="name">
             </div>
               <p class="validation-error" id="name-error-para">{{{trans_choice('app.name_required',0)}}}</p>
             </div>
             </div>
             <div class="col-md-12">
              <div class="row">           
             <div class="form-group ">
              <label class="control-label" for="email">{{{trans('app.email')}}}</label>
              <input type="email"  name="username" class="form-control input_remove_border remove_boxshadow form_color" id="email">
             </div>
                <p class="validation-error" id="email-error">{{{trans('app.email_required')}}}</p>
             </div>
             </div>
             <div class="col-md-12">
              <div class="row">
             <!-- <div class="form-group form_group_width form_display dob_margin-top">-->
              <div class="col-md-5">
                <div class="row">
              <div class="form-group">
              <div class='form-group date' id='datetimepicker'>
                    <label class="control-label" for="dob">{{{trans('app.dob')}}}</label>
                    <input type='text' name="dob" id="dob" value="" class="form-control input_remove_border remove_boxshadow form_color" />
                    <span class="input-group-addon input_remove_border input_bg_none date-input-addon">
                        <span class="fa fa-caret-down"></span>
                    </span>
                </div>
             </div>
              </div>
              <p class="validation-error" id="dob-error">{{{trans('app.dob')}}} {{{trans('app.is_required')}}}</p>
             </div>
              
              <div class="col-md-5 pull-right">
                <div class="row">              
              <div class="form-group ">
                <label class="control-label" for="txtPlaces1">{{{trans('app.city')}}}</label>
                <input autocomplete="on" name="city" placeholder="" class="form-control input_remove_border remove_boxshadow form_color"  id="txtPlaces1"  type="text">
                         
                         <input type="hidden" id="lat" name="lat" value=""/>
                         <input type="hidden" id="lng" name="lng" value=""/>
                         <input type="hidden" id="city" name="city" value=""/>
                         <input type="hidden" id="country" name="country" value=""/>
              
              </div>
              </div>
               <p class="validation-error" id="location-error-para" >{{{trans('app.city')}}} {{{trans('app.is_required')}}}</p>
              </div>
             </div>
             </div>
              <div class="col-md-12">
                <div class="row">
                  <input type="hidden" value="" id="gender_val" name="gender_val"/>
                  <div class="form-group">
                    @foreach($sections as $section)
                      @foreach($section->fields as $field)
                        @if($field->on_registration == 'yes' && $field->type == "dropdown")
                        <div class="col-md-6 col-xs-6">
                          <select  name="{{{ $field->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$field->code}}}">
                          <option >{{{trans('custom_profile.'.$field->code)}}}</option>
                             @foreach($field->field_options as $option)
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
                           @foreach($gender->field_options as $option)
                            <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                           @endforeach
                        </select>
                      </div>
                    @endif
                  </div>
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
             <div class="form-group">
              <label class="control-label" for="pwd2">{{{trans('app.password_confirm')}}}</label>
              <input type="password" name="password_confirmation" class="form-control input_remove_border remove_boxshadow form_color" id="pwd2">
             </div>
              <p class="validation-error" id="confirm-password-error">{{{trans('app.password_confirm')}}} {{{trans('app.is_required')}}}</p>
             </div>
             </div>
            <div class="col-md-12">
              <div class="row">
             <div class="form-group">
               <p class="validation-error" id="email_activated">{{{trans('app.verify_email')}}}</p>
              <button type="submit" class="btn btn-primary btn-block btn_signup_bg btn_border-radius">{{{trans('app.signup')}}}</button>
             </div>
             </div>
             </div>
             <div class="col-md-12">
             <div class="row">
             <div class="form-group">
              <p class="sign_up_text">{{{trans_choice('LandingPagesPlugin.signup_bottom',4)}}}
                <a href="@LandingPageUrl('terms_and_conditions')">{{{trans('LandingPagesPlugin.terms_and_conditions')}}}</a>
                <a href="@LandingPageUrl('privacy_policy')">{{{trans('LandingPagesPlugin.privacy_policy')}}}</a>
                <a href="@LandingPageUrl('cookie_policy')">{{{trans('LandingPagesPlugin.cookie_policy')}}}</a>
              </p>
             </div>
             </div>
             </div>
           
          
          </form>
                </div>
		</div>
</div>
    <div class="col-md-12 col_two_padding">
      <div class="text-center">
        <h3 class="text_black">{{{trans_choice('LandingPagesPlugin.app_available_market_place_heading',2)}}}</h3>
        <p class="text_black">{{{trans_choice('LandingPagesPlugin.app_available_market_place_heading_sub',3)}}}</p>
        <img class="col_two_img_width" src="@LandingPageImage('app_available_market_place_image')"/>
      </div>
    </div>
    

    </div>
    </div>
    <div class="col-md-12 col_4_padding">
      <div class="row">
        <div class="col-md-4">
          <div class="col-md-12">
            <h4 class="headpink_margin">{{{trans_choice('LandingPagesPlugin.middle_content_first_heading',4)}}}</h4>
            <p>{{{trans_choice('LandingPagesPlugin.middle_content_first_description',5)}}}</p>
          </div>
        </div>
        <div class="col-md-4">
         <div class="col-md-12">
          <h4 class="headpink_margin">{{{trans_choice('LandingPagesPlugin.middle_content_second_heading',6)}}}</h4>
          <p>{{{trans_choice('LandingPagesPlugin.middle_content_second_description',7)}}}</p>
         </div>
        </div>
        <div class="col-md-4">
          <div class="col-md-12">
            <h4 class="headpink_margin">{{{trans_choice('LandingPagesPlugin.middle_content_third_heading',8)}}}</h4>
            <p>{{{trans_choice('LandingPagesPlugin.middle_content_third_description',9)}}}</p>
          </div>
        </div>
      </div>
    </div>

<div class="col-md-12 col_last_bg">
      <div class="text-center social_bottom_margin">
        <ul class="list-inline">
          <li class="text_white border-right_gray social_padding_right" onclick="window.location.href='@LandingPageUrl('socialoxide')'">{{{trans_choice('LandingPagesPlugin.social_oxide_link_text',11)}}}</li>
                    <li class="text_white border-right_gray social_padding_right" onclick="window.location.href='@LandingPageUrl('facebook')'">{{{trans_choice('LandingPagesPlugin.facebook_link_text',12)}}}</li>
                    <li class="text_white border-right_gray social_padding_right" onclick="window.location.href='@LandingPageUrl('twitter')'">{{{trans_choice('LandingPagesPlugin.twitter_link_text',13)}}}</li>
                    <li class="text_white social_padding_right" onclick="window.location.href='@LandingPageUrl('google_plus')'">{{{trans_choice('LandingPagesPlugin.google_plus_link_text',14)}}}</li>
        </ul>
      </div>
      <div class="text-center">
        <h5 class="text_white footer_margin">{{{trans_choice('LandingPagesPlugin.made_with',15)}}}</h5>
      </div>
    </div>

<script>

  $('#gender_val').val( $("select[name='gender'] option:selected" ).data('value'));

$("select[name='gender']").on('change',function(){

  $('#gender_val').val( $( "select[name='gender'] option:selected" ).data('value'));

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
$(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
// $(document).ready(function(){


// $('.dropdown-menu a').on('click', function(){    
//     $(this).parent().parent().prev().html($(this).html() + '<span class="caret"></span>');    
// });
// });
</script>

<script>
$(document).ready(function(){
  /*
  $('#edit-profile').validate({
        rules: {
            firstname: {
                minlength: 3,
                required: true
            },
            lastname: {
                minlength: 3,
                required: true
            },
            username: {
                required: true,
                email: true
            },
            city: {
                required: true
              },
            dob:{
              required:true
            },  
            password: {
                minlength: 4,
                required: true
            },
           password_confirm:{
              minlength:4,
              required:true
            }
           
        },
        highlight: function (element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
          },
        success: function (element) {
            element.text('OK!').addClass('valid')
                .closest('.control-group').removeClass('error').addClass('success');
        }
    }); */
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
//$("#name").addClass('border-red');
$(".signup-error > .error-text").html("{{{trans_choice('app.name_required',0)}}}");
$(".signup-error").show();
//$("#name").attr("placeholder","{{{trans('app.name')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
if(dob == ''){
$(".signup-error > .error-text").html("{{{trans_choice('app.dob_required',0)}}}");
$(".signup-error").show();
//$("#dob").addClass('border-red');
$("#dob").attr("placeholder","{{{trans('app.dob_required')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
if(email == ''){
$(".signup-error > .error-text").html("{{{trans_choice('app.email_required',0)}}}");
$(".signup-error").show();
// $("#email").addClass('border-red');
$("#email").attr("placeholder","{{{trans('app.email_required')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
else if(!/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(email)){
$(".signup-error > .error-text").html("{{{trans_choice('app.valid_email',0)}}}");
$(".signup-error").show();
// $('#email').addClass('border-red');
// $("#email").attr("placeholder","{{{trans('app.valid_email')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
if(location == ''){
$(".signup-error > .error-text").html("{{{trans_choice('app.location_required',0)}}}");
$(".signup-error").show();
// $("#txtPlaces1").addClass('border-red');
// $("#txtPlaces1").attr("placeholder","{{{trans('app.location_required')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
if(password == '')
{
$(".signup-error > .error-text").html("{{{trans_choice('app.password_validate_field',0)}}}");
$(".signup-error").show();
flag = 1;
$('.loader').fadeOut();return;
// $("#pwd1").addClass('border-red');
// $("#pwd1").attr("placeholder","{{{trans_choice('app.password_validate_field',0)}}}");
}
else if(password.length < 8){
$(".signup-error > .error-text").html("{{{trans_choice('app.password_validate_field',1)}}}");
$(".signup-error").show();
// $("#pwd1").addClass('border-red');
// $("#pwd1").attr("placeholder","P{{{trans_choice('app.password_validate_field',1)}}}");
flag = 1;
$('.loader').fadeOut();return;
}
else if(password != confirm_password){
$(".signup-error > .error-text").html("{{{trans_choice('app.password_validate_field',2)}}}");
$(".signup-error").show();
// $("#pwd2").addClass('border-red');
// $("#pwd1").attr("placeholder","{{{trans_choice('app.password_validate_field',2)}}}");
flag = 1;
$('.loader').fadeOut();return;
}
if(flag == 0){
$.ajax({
type: 'post',
url: '{{{url('/register')}}}',
data: $(this).serialize(),
success: function (response) {
$('.loader').fadeOut();
if(response.errors) { 
if(response.errors.dob) {
$(".signup-error > .error-text").html(response.errors.dob);
$(".signup-error").show();
// toastr.error(response.errors.dob);
// $('#dob').addClass('border-red');
}
if(response.errors.username) {
$(".signup-error > .error-text").html(response.errors.username);
$(".signup-error").show();
// //$("#email-error").show().html(response.errors.username);
//                                     toastr.error(response.errors.username);
}
if(response.errors.city)
{
$(".signup-error > .error-text").html("{{{trans('app.city_select_suggestion')}}}");
$(".signup-error").show();
// //$('#location-error-para').show().html('City must be selected from suggestion!');
//                               toastr.error("{{{trans('app.city_select_suggestion')}}}");
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
$(document).ready(function()
{
 /* Float Label Pattern Plugin for Bootstrap 3.1.0 by Travis Wilson
**************************************************/

(function ($) {
    $.fn.floatLabels = function (options) {

        // Settings
        var self = this;
        var settings = $.extend({}, options);


        // Event Handlers
        function registerEventHandlers() {
            self.on('input keyup change', 'input, textarea', function () {
                actions.swapLabels(this);
            });
        }


        // Actions
        var actions = {
            initialize: function() {
                self.each(function () {
                    var $this = $(this);
                    var $label = $this.children('label');
                    var $field = $this.find('input,textarea').first();

                    if ($this.children().first().is('label')) {
                        $this.children().first().remove();
                        $this.append($label);
                    }

                    var placeholderText = ($field.attr('placeholder') && $field.attr('placeholder') != $label.text()) ? $field.attr('placeholder') : $label.text();

                    $label.data('placeholder-text', placeholderText);
                    $label.data('original-text', $label.text());

                    if ($field.val() == '') {
                        $field.addClass('empty')
                    }
                });
            },
            swapLabels: function (field) {
                var $field = $(field);
                var $label = $(field).siblings('label').first();
                var isEmpty = Boolean($field.val());

                if (isEmpty) {
                    $field.removeClass('empty');
                    $label.text($label.data('original-text'));
                }
                else {
                    $field.addClass('empty');
                    $label.text($label.data('placeholder-text'));
                }
            }
        }


        // Initialization
        function init() {
            registerEventHandlers();

            actions.initialize();
            self.each(function () {
                actions.swapLabels($(this).find('input,textarea').first());
            });
        }
        init();


        return this;
    };

    $(function () {
        $('.float-label-control').floatLabels();
    });
})(jQuery);
});


</script>

 <script>
              new WOW().init();
              </script>
              
              	<script src="@plugin_asset('LandingPagesPlugin/Trendy/js/bootstrap-datepicker.js')"></script>
		<script>
			$(document).ready(function() {
				$('#datetimepicker')
			        .datepicker({
			            format: 'dd/mm/yyyy'
			        });
			  });
		        
			</script>
			
			
			
		
		<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
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
  $(document).ready(function()
  {
    $('#datetimepicker').on('changeDate', function(ev){
    $(this).datepicker('hide');
    $("#dob").focus();

});
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
 <script src="@plugin_asset('LandingPagesPlugin/js/masonry.pkgd.min.js')"></script>
<script src="@theme_asset('js/jquery.flexslider-min.js')"></script>
<script src="@plugin_asset('LandingPagesPlugin/js/main.js')"></script>
</body>
</html>
