<?php use App\Components\Theme;?>
<!doctype html>
<html>
<head>
<meta name="csrf-token" content="{{{ csrf_token() }}}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{{$website_title}}}</title>
<!-- <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/bootstrap.min.css')}}}" type='text/css'> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">



<!-- <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/font-awesome.min.css')}}}" type='text/css'> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">


<link href="{{{asset('themes/DefaultTheme/css/robotofont.css')}}}" rel='stylesheet' type='text/css'>


<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/materialfonts.css')}}}" type='text/css'>


<!-- <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/bootstrap-datepicker3.css')}}}" type='text/css'> -->


<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/reset.css')}}}" type='text/css'>


<!-- <link href="{{{asset('themes/DefaultTheme/css/landing-custom.css')}}}" rel="stylesheet" type='text/css'> -->


<!-- <link rel="stylesheet" href=" {{{asset('themes/DefaultTheme/css/animate.min.css')}}}"><!-- load animate --> 

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">



<link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/flag.css')}}}" type="text/css" media="screen" />

<!-- <link href="{{{asset('themes/DefaultTheme/css/landing-crossbrowser.css')}}}" rel="stylesheet" type='text/css'> -->


<!-- <link rel="stylesheet" href="{{{asset('themes/DefaultTheme/css/datingframework_lastlanding.css')}}}"> -->


 <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet" type="text/css">


<link href="{{{asset('themes/DefaultTheme/css/defaultLandingPage.css')}}}" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>
<body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else {{{asset('themes/DefaultTheme/images/bg.png')}}} @endif');background-size:cover;">

<div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
      
      <ul class="list-inline display_inline float_right display_flex">
       <li>
       <p class="text_white opacity_7 signin_padding">{{{trans('LandingPagesPlugin.top_signin_text')}}}</p>
       </li>
        <li class="li-padding">
          <a href="{{{url('/register')}}}" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn">{{{trans('app.signup')}}}</a>
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
        <h4 class="text-center text_white">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading',0)}}}</h4>
      </div>
      <div class="row">
      <div class="col-md-6 col-xs-12 col_main-text-margin text-center">
        <div class="social_margin text-center">
              {{{Theme::render('login')}}}

         </div>
          <p class="text_white opacity_7 text-center">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_sub',1)}}}</p> 
          
         
         
          
      </div>
        
           
        <div class="col-md-4 form_margin form_bg form_padding form_width form_boxshadow">
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
              <a id="forgot-password-link" href="#/" class="forgetPassword" >{{{trans('LandingPagesPlugin.forgot_password_link_text')}}}</a>
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
           
    </div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{{asset('themes/DefaultTheme/js/bootstrap-datepicker.js')}}}"></script> 
<!--

<script src="{{{asset('themes/DefaultTheme/js/wow.js')}}}"></script>
-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script>

<script src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>

<script>
  $.ajaxSetup({ 
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
</script> 

<script>
	$(document).ready(function(){
	
	if('{{{session()->get("account_activation")}}}'=='true')
	{
		toastr.success('Account activated!');
	}
});
	
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

