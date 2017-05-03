<?php use App\Components\Theme;?>
<!doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{{ csrf_token() }}}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{{$website_title}}}</title>
       <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css">
<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/bootstrap-datepicker3.css')}}}" type="text/css">
<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/reset.css')}}}" type="text/css">



<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" type="text/css"><!-- load animate -->
<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/flag.css')}}}" type="text/css" media="screen" />

 <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet" type="text/css">
 
 <link href="{{{asset('themes/DefaultTheme/css/LandingPageRegister.css')}}}" rel="stylesheet" type="text/css">
 
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js" type="text/javascript"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script>


        <link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/Rainbow/css/datingframework_bluelanding.css')">
        <style>
            .loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
/*             background: url("@plugin_asset('LandingPagesPlugin/images/heart_small.gif')")  50% 50% no-repeat rgb(249,249,249); */
            opacity: 0.7;
            }
            .social_login_cnt{
            margin-top: 0px;
            display: flex;
            }
            .social_login_cnt li {
            list-style-type: none;
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
            <meta name="csrf-token" content="{{{ csrf_token() }}}">
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
    <body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @plugin_asset('LandingPagesPlugin/Rainbow/images/bg.png') @endif');background-size:cover;">
    <div class="loader"></div>
    <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
        <ul class="list-inline display_inline float_right display_flex">
            <li>
                <p class="text_white opacity_7 signin_padding">{{{trans_choice('LandingPagesPlugin.alredy_member',0)}}}</p>
            </li>
            <li class="li-padding">
                <a href="{{{url('/login')}}}" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn">{{{trans('app.signin')}}}</a>
            </li>
            <li>
                {{{Theme::render('top-header')}}}
            </li>
        </ul>
    </div>
    <div class="col-md-12 col_bg_img col_main-height bg_size">
        <div class="row">
            <div class="@if($only_social_logins == 'true') col-md-12 makeitcenter @else col-md-6 @endif col-xs-12 col_main-text-margin text-center">
                <img class="logo_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
                <p class="text_white opacity_7">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading',1)}}}</p>
                <p class="text_white display_inline social_p_margin opacity_7">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_sub',2)}}}</p>
                <div class="social_div">
                    <div class="social_margin">
                        {{{Theme::render('login')}}}
                    </div>
                </div>
                <div>
                    <ul class="list-inline text_white">
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('about_us')'">{{{trans_choice('LandingPagesPlugin.about_us',3)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('cookie_policy')'">{{{trans_choice('LandingPagesPlugin.cookie_policy',4)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('terms_and_conditions')'">{{{trans_choice('LandingPagesPlugin.terms_and_conditions',5)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('privacy_policy')'">{{{trans_choice('LandingPagesPlugin.privacy_policy',6)}}}</li>
                        <li class="opacity_7">{{{trans_choice('LandingPagesPlugin.df_two_reg',7)}}}</li>
                    </ul>
                </div>
            </div>
            <form method = "POST" action = "{{{ URL::to('register') }}}"  id="registration-form" name="edit-profile" @if($only_social_logins == 'true') style="display:none" @endif>
                  <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                  <input type="hidden" name="gender" value="" />
        <div class="col-md-4 form_margin form_bg form_padding form_width form_boxshadow border-left_pseudo">
            <h4 class="text_white text-center opacity_7 header_margin">{{{trans_choice('LandingPagesPlugin.signup_top',2)}}}</h4>
            <div class="alert alert-danger signup-error" style="display:none">
                <span onclick="this.parentElement.style.display='none'" class="w3-closebtn">&times;</span>
                <span class= "error-text"></span>
            </div>
            <div class="form-group form_group_margin-bottom">
              <input type="text" class="form-control remove_boxshadow input_height" name="name" id="name" placeholder="{{{trans('app.name')}}}" data-toggle="tooltip" title="Full Name Required" data-placement="right">
             </div>
             
            <div class="form-group form_group_margin-bottom">
              <input type="email" class="form-control remove_boxshadow input_height" name="username" id="email" placeholder="{{{trans('app.email')}}}" data-toggle="tooltip" title="Email Required" data-placement="right">
             </div>
              <div class="form-group form_group_margin-bottom">
               <div class="row">
                  <div class="col-md-12 col-xs-12">
	                  <span style="color: white;font-size: 13px;position: relative;bottom: 4px">{{{trans('app.dob')}}}</span>
                    <div class="input-group date input_max_width" style="bottom: 4px;">

<!--
                    <input type="text" placeholder="{{{trans('app.dob')}}}" name="dob" id="dob"  class="form-control remove_boxshadow input_height border_right_none" data-toggle="tooltip" title="Date of Birth Required" data-placement="left">
                    





<span class="input-group-addon input_remove_border">
                        <span class="fa fa-caret-down"></span>
                    </span>
-->
 

										<div class="select select-fancy select-fancy-image" style="width: 60px;margin-right: 5px;"> <select class="dobpart" id="dobday" required=""></select></div>
										<div class="select select-fancy select-fancy-image" style="width: 78px;margin-right: 5px;"><select class="dobpart" id="dobmonth" required="" ></select></div>
										<div class="select select-fancy select-fancy-image" style="width: 60px"><select class="dobpart" id="dobyear" required=""></select>	  </div>
										  <input id="dob" name="dob" type="hidden" placeholder="{{{trans('app.dob')}}}" class="form-control input-md" required="">
										    

										  



                   </div>
             </div>
             
              
              </div>
              </div>
              
              <div class="form-group form_group_margin-bottom">
               <div class="row">
              
             
              <div class="col-md-12 col-xs-12" @if($maxmind_geoip_enabled) style="display:none" @endif>
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
                  @foreach($section->fields as $field)
                    @if($field->on_registration == 'yes' && $field->type == "dropdown")
                    <div class="col-md-6 col-xs-6">
                      <select  name="{{{ $field->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$field->code}}}">
	                     <option data-value="0" value="0">{{{trans('custom_profile.'.$field->code )}}}</option>  
                         @foreach($field->field_options as $option)
                          <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                         @endforeach
                      </select>
                    </div>
                    
                    @elseif($field->on_registration == 'yes' && $field->type == 'checkbox')

                        <div class="col-md-6 col-xs-6">

                          <select type="text" id="multiselect_checkbox" name="{{$field->code}}[]" data-nonSelectedText="{{{$field->code}}}" class="form-control multiselect multiselect-icon" multiple="multiple" role="multiselect"> 
	                          
	                          @foreach($field->field_options as $option)
                                
                                
                                <option value="{{$option->id}}" name="{{$field->code}}[]">{{trans("custom_profile.{$option->code}")}}</option>
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
              <p class="sign_up_text text_white">{{trans_choice('LandingPagesPlugin.signup_bottom',3)}} <a href="@LandingPageUrl('terms_and_conditions')">{{trans('LandingPagesPlugin.terms_and_conditions')}} </a> and <a href="@LandingPageUrl('privacy_policy')">{{trans('LandingPagesPlugin.privacy_policy')}}</a></p>
              <p class="sign_up_text text_white"> {{trans_choice('LandingPagesPlugin.about_us_before_text',0)}} <a href="@LandingPageUrl('about_us')"> {{trans_choice('LandingPagesPlugin.about_us',1)}} </a></p>
             </div>
          </form>
        </div>
    </div>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script> 
<script src="{{{asset('themes/DefaultTheme/js/bootstrap-datepicker.js')}}}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>


<script src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>

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
/*
if(location == ''){
$(".signup-error > .error-text").html("{{{trans_choice('app.location_required',0)}}}");
$(".signup-error").show();
// $("#txtPlaces1").addClass('border-red');
// $("#txtPlaces1").attr("placeholder","{{{trans('app.location_required')}}}");
flag = 1;
$('.loader').fadeOut();return;
}
*/
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
if(response.errors.custom_field) {
$(".signup-error > .error-text").html(response.errors.custom_field);
$(".signup-error").show();
// //$("#email-error").show().html(response.errors.username);
//                                     toastr.error(response.errors.username);
}
if(response.errors.username) {
$(".signup-error > .error-text").html(response.errors.username);
$(".signup-error").show();
// //$("#email-error").show().html(response.errors.username);
//                                     toastr.error(response.errors.username);
}
if(response.errors.city || response.errors.country || response.errors.lat || response.errors.lng)
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
    <script src="@plugin_asset('LandingPagesPlugin/Rainbow/js/bootstrap-datepicker.js')"></script>
    <script>
        $(document).ready(function() {
          $('#datetimepicker')
                .datepicker({
                    format: 'dd/mm/yyyy'
                });
          });
               
    </script>
    <script type="text/javascript">
        $("#options > ul > li > a").on('click',function(){
          
           var lang = $(this).data('val');
           
           $(".loader").fadeIn("slow");
        
          var data = {};
          data['_token'] = "{{{csrf_token()}}}";
          data['language'] = lang;
          $.post("{{{url('set/language/cookie')}}}", data, function(response){
        
            if(response.status == 'success') {
             $(".loader").fadeOut("slow");
               toastr.success('Language set successfully');
              window.location.reload();
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
    
    	<script type="text/javascript">
  /* jQuery DOB Picker 1.0 | Tom Yeadon | https://github.com/tyea/dobpicker | MIT License */
jQuery.extend({dobPicker:function(e){if(typeof e.dayDefault==="undefined")e.dayDefault="Day";if(typeof e.monthDefault==="undefined")e.monthDefault="Month";if(typeof e.yearDefault==="undefined")e.yearDefault="Year";if(typeof e.minimumAge==="undefined")e.minimumAge=12;if(typeof e.maximumAge==="undefined")e.maximumAge=80;$(e.daySelector).append('<option value="">'+e.dayDefault+"</option>");$(e.monthSelector).append('<option value="">'+e.monthDefault+"</option>");$(e.yearSelector).append('<option value="">'+e.yearDefault+"</option>");for(i=1;i<=31;i++){if(i<=9){var t="0"+i}else{var t=i}$(e.daySelector).append('<option value="'+t+'">'+i+"</option>")}var n=["{{{trans('LandingPagesPlugin.january')}}}","{{{trans('LandingPagesPlugin.february')}}}","{{{trans('LandingPagesPlugin.march')}}}","{{{trans('LandingPagesPlugin.april')}}}","{{{trans('LandingPagesPlugin.may')}}}","{{{trans('LandingPagesPlugin.june')}}}","{{{trans('LandingPagesPlugin.july')}}}","{{{trans('LandingPagesPlugin.august')}}}","{{{trans('LandingPagesPlugin.september')}}}","{{{trans('LandingPagesPlugin.october')}}}","{{{trans('LandingPagesPlugin.november')}}}","{{{trans('LandingPagesPlugin.december')}}}"];for(i=1;i<=12;i++){if(i<=9){var t="0"+i}else{var t=i}$(e.monthSelector).append('<option value="'+t+'">'+n[i-1]+"</option>")}var r=new Date;var s=r.getFullYear();var o=s-e.minimumAge;var u=o-e.maximumAge;for(i=o;i>=u;i--){$(e.yearSelector).append('<option value="'+i+'">'+i+"</option>")}$(e.daySelector).change(function(){$(e.monthSelector)[0].selectedIndex=0;$(e.yearSelector)[0].selectedIndex=0;$(e.yearSelector+" option").removeAttr("disabled");if($(e.daySelector).val()>=1&&$(e.daySelector).val()<=29){$(e.monthSelector+" option").removeAttr("disabled")}else if($(e.daySelector).val()==30){$(e.monthSelector+" option").removeAttr("disabled");$(e.monthSelector+' option[value="02"]').attr("disabled","disabled")}else if($(e.daySelector).val()==31){$(e.monthSelector+" option").removeAttr("disabled");$(e.monthSelector+' option[value="02"]').attr("disabled","disabled");$(e.monthSelector+' option[value="04"]').attr("disabled","disabled");$(e.monthSelector+' option[value="06"]').attr("disabled","disabled");$(e.monthSelector+' option[value="09"]').attr("disabled","disabled");$(e.monthSelector+' option[value="11"]').attr("disabled","disabled")}});$(e.monthSelector).change(function(){$(e.yearSelector)[0].selectedIndex=0;$(e.yearSelector+" option").removeAttr("disabled");if($(e.daySelector).val()==29&&$(e.monthSelector).val()=="02"){$(e.yearSelector+" option").each(function(e){if(e!==0){var t=$(this).attr("value");var n=!(t%4||!(t%100)&&t%400);if(n===false){$(this).attr("disabled","disabled")}}})}})}})
  </script>
	
	
	<script>
	$(document).ready(function(){
	  $.dobPicker({
	    daySelector: '#dobday', /* Required */
	    monthSelector: '#dobmonth', /* Required */
	    yearSelector: '#dobyear', /* Required */
	    dayDefault: '{{{trans('LandingPagesPlugin.day')}}}', /* Optional */
	    monthDefault: '{{{trans('LandingPagesPlugin.month')}}}', /* Optional */
	    yearDefault: '{{{trans('LandingPagesPlugin.year')}}}', /* Optional */
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


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        if($('#multiselect_checkbox').length)
	    {
	        $('#multiselect_checkbox').multiselect({
		        nonSelectedText: $('#multiselect_checkbox').attr('data-nonselectedtext')
	        });
        }
    });
</script>
 
    </body>
</html>