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


<link <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css">





<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/reset.css')}}}" type='text/css'>

 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">



<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/flag.css')}}}" type="text/css" media="screen" />




 <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet" type="text/css">


<link href="{{{asset('themes/DefaultTheme/css/LandingPageSignIn.css')}}}" rel="stylesheet">



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script>

<link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/RainbowV2/css/greenlanding.css')">


<style>
.loader {
  position: fixed;
  left: 0px;
  top: 0px;
  width: 100%;
  height: 100%;
  z-index: 9999;
/*   background: url("@plugin_asset('LandingPagesPlugin/images/heart_small.gif')")  50% 50% no-repeat rgb(249,249,249); */
  opacity: 0.7;
}
.social_login_cnt{
  margin-top: 0px;
  }
.social_margin{
display: flex;
margin-left: 33%;
}
.social_margin li {
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
</style>

</head>
<body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @plugin_asset('LandingPagesPlugin/RainbowV2/images/bg.png') @endif');background-size:cover;">

<div class="loader"></div>  

 <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
			<img class="logo_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
			<ul class="list-inline display_inline float_right display_flex">
        <li class="text_white opacity_7 signin_padding">{{{trans_choice('LandingPagesPlugin.df_two_sig',0)}}}</li>
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
		<div class="col-md-12 col_bg_img col_main-height bg_size">
		  <div class="row">
			<div class="@if($only_social_logins == 'true') col-md-12 makeitcenter text-center @else col-md-6 @endif col-xs-12 col_main-text-margin">
				<h3 class="text-center text_white head_opacity header_green">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading',1)}}}</span></h3>
				<p class="text-center text_white users_para_padding opacity_7">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_sub',0)}}}!</p>
				<div class="text-center social_margin">
				     {{{Theme::render('login')}}}
        </div>
               
          <div class="text-center last_div_main_margin">
           <ul class="list-inline text_white">
            <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('about_us')'">{{{trans_choice('LandingPagesPlugin.about_us',3)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('cookie_policy')'">{{{trans_choice('LandingPagesPlugin.cookie_policy',4)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('terms_and_conditions')'">{{{trans_choice('LandingPagesPlugin.terms_and_conditions',5)}}}</li>
                        <li class="border_right social_padding_right opacity_7" onclick="window.location.href='@LandingPageUrl('privacy_policy')'">{{{trans_choice('LandingPagesPlugin.privacy_policy',6)}}}</li>
                        <li class="opacity_7">{{{trans_choice('LandingPagesPlugin.df_two_reg',7)}}}</li>
           </ul>
          </div>
           </div>
              
           <div class="col-md-4 float_right form_margin form_bg form_padding form_width form_color" @if($only_social_logins == 'true') style="display:none" @endif>
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
            <div class="form-group form_group_margin-bottom">
              <input type="email" readonly onfocus="this.removeAttribute('readonly');" class="form-control remove_boxshadow input_height" name="username" id="email" placeholder="{{{trans('app.email')}}}">
             </div>
              
            
             <div class="form-group form_group_margin-bottom">
              
              <input type="password" readonly onfocus="this.removeAttribute('readonly');" class="form-control remove_boxshadow input_height" id="pwd1" name="password" placeholder="{{{trans('app.password')}}}">
             </div>

             <div class="form-group" style="position:relative">
              <input type="checkbox" class="form-control remember-me-checkbox" name="remember_me" style="width:auto;">
              <span style="position: absolute;top: 6px;margin-left: 16px;color: white;width: 100%;font-size:16px">{{{trans('LandingPagesPlugin.remember_me')}}}</span>
             </div>


             
             <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block btn_signup_bg input_height border_none signup_height">{{{trans('app.signin')}}}</button>
             </div>
             <div class="form-group">
              <p class="sign_up_text text_white">{{trans_choice('LandingPagesPlugin.signup_bottom',3)}} <a href="@LandingPageUrl('terms_and_conditions')">{{trans('LandingPagesPlugin.terms_and_conditions')}} </a> and <a href="@LandingPageUrl('privacy_policy')">{{trans('LandingPagesPlugin.privacy_policy')}}</a></p>
              <p class="sign_up_text text_white"> {{trans_choice('LandingPagesPlugin.about_us_before_text',0)}} <a href="@LandingPageUrl('about_us')"> {{trans_choice('LandingPagesPlugin.about_us',1)}} </a></p>
              
             </div>
              @if($show_forgot_password_link)
              	<a id="forgot-password-link" href="#/" class="forgetPassword" >{{{trans('LandingPagesPlugin.forgot_password_link_text')}}}</a>
             @endif
          </form>

<form id="forgot-password-form" action="{{{url('/forgotPassword/submit')}}}" method="POST" style="display:none;">
                         {!! csrf_field() !!} 
                        <div class="row">
                          
                            <div class="col-md-12">
                              <div class="form-group">
                                 
                              <input type="text" name="username" id="username_forgotpwd" class="form-control remove_boxshadow input_height" placeholder="{{{trans('app.email')}}}" required>
                              </div>
                            </div>
                            
                            <div class="col-md-12">
                              <div class="form-group ">
                                 <p class="text_white">{{{trans('LandingPagesPlugin.reset_password_text')}}}</p> 
                              
                              </div>
                            </div>
                           
                            <div class="col-md-12">
                              <a href="{{{url('/login')}}}" class="rememberPassword" >{{{trans('LandingPagesPlugin.remember_password_link_text')}}}</a>
                             <button type="submit" class="btn btn-primary border_none btn_signup_bg pull-right" id="reset">{{{trans('LandingPagesPlugin.reset_password_button_text')}}}</button>
                            </div>
                          </div>
                       </form>

		</div>
		
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{{asset('themes/DefaultTheme/js/bootstrap-datepicker.js')}}}"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
<!--  <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script> -->

<script src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>

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
</body>
</html>
