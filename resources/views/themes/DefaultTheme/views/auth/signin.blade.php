<?php use App\Components\Theme;?>
<!doctype html>
<html>
<head>
<meta name="csrf-token" content="{{{ csrf_token() }}}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{{$website_title}}}</title>
<link rel="stylesheet" href="@theme_asset('css/bootstrap.min.css')">
<link rel="stylesheet" href="@theme_asset('css/font-awesome.min.css')">
<link href="@theme_asset('css/robotofont.css')" rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="@theme_asset('css/materialfonts.css')">
<link rel="stylesheet" href="@theme_asset('css/bootstrap-datepicker3.css')">
<link rel="stylesheet" href="@theme_asset('css/reset.css')">


<link href="@theme_asset('css/landing-custom.css')" rel="stylesheet">
<link rel="stylesheet" href=" @theme_asset('css/animate.min.css')"><!-- load animate -->
<link rel="stylesheet" href="@theme_asset('css/flag.css')" type="text/css" media="screen" />

<link href="@theme_asset('css/landing-crossbrowser.css')" rel="stylesheet">
<script src="@theme_asset('js/jquery-1.11.3.js')"></script>
<script src="@theme_asset('js/bootstrap.min.js')"></script> 
<script src="@theme_asset('js/bootstrap-datepicker.js')"></script> 

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
  background: url("@theme_asset('images/heart_small.gif)")  50% 50% no-repeat rgb(249,249,249);
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
  .alert-danger {
    border-radius: 4px;
    margin-bottom: 4px;
   padding-left: 9px;
    padding-top: 9px;
    padding-bottom: 9px;
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
</style>

</head>
<body style="background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @theme_asset('images/bg.png') @endif');background-size:cover;">

<div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
      
      <ul class="list-inline display_inline float_right display_flex">
       <li>
       <p class="text_white opacity_7 signin_padding">{{{trans('app.alredy_member')}}}</p>
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
        <h4 class="text-center text_white">{{{trans_choice('app.register_long_text',0)}}}</h4>
      </div>
      <div class="row">
      <div class="col-md-6 col-xs-12 col_main-text-margin text-center">
        <div class="social_margin text-center">
              {{{Theme::render('login')}}}

         </div>
          <p class="text_white opacity_7 text-center">{{{trans_choice('app.register_long_text',1)}}}</p> 
          
         
         
          
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
             
             <div class="form-group">
              <button type="submit" class="btn btn-primary btn-block btn_signup_bg input_height border_none signup_height">{{{trans('app.signin')}}}</button>
             </div>
             <div class="form-group">
              <p class="sign_up_text text_white">{{trans_choice('app.register_long_text',3)}} <a href="{{{url('/pages/terms-and-conditions')}}}">{{trans('app.terms_conditions')}} </a> and <a href="{{{url('pages/privacy-policy')}}}">{{trans('app.privacy_policy')}}</a></p>
              <p class="sign_up_text text_white"> {{trans_choice('app.about_us',0)}} <a href="{{{url('/pages/about-us')}}}"> {{trans_choice('app.about_us',1)}} </a></p>
             </div>
              <a id="forgot-password-link" href="#/" class="forgetPassword" >{{{trans('app.forgot_password')}}}</a>
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
                                 <p class="text_white">{{{trans('app.forgot_form_text')}}}</p> 
                              
                              </div>
                            </div>
                           
                            <div class="col-md-12">
                              <a href="{{{url('/login')}}}" class="rememberPassword" >{{{trans('app.forgot_remember_text')}}}</a>
                             <button type="submit" class="btn btn-primary border_none btn_signup_bg pull-right" id="reset">{{{trans('app.reset')}}}</button>
                            </div>
                          </div>
                       </form>
           </div>
           
    </div>


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
$(document).ready(function()
{
  /*
$('#form1').on('click',function(){
   if($('#pwd1').val()!=$('#pwd2').val()){
       $(".col-gamma").find('label').html("Password Not Matched");
        return false;
         }
   return true;
});
var frm=$("#edit-profile");
$('#edit-profile').on('submit',function(e){
  e.preventDefault();
   $.ajax({
        type: 'post',
        url: '{{{url('/register')}}}',
        data: frm.serialize(),
        success: function (response) {
          if(response)
          {
          $(".col-beta").find('label').html(response.status);
          }
        }

           
    });

   });*/


  $("#registration-form").submit(function(e){
    
    $('.loader').fadeIn();

    e.preventDefault();
    $(".validation-error").hide();
    
    var first_name = $("#first-name").val();
    var last_name = $("#last-name").val();
    var email = $("#email").val();
    var dob = $("#dob").val();
    var location = $("#txtPlaces1").val();

     var password = $("#pwd1").val();
     var confirm_password = $("#pwd2").val();

     var flag = 0;

     if(first_name == '' || last_name == ''){
       $("#firstname-error-para").show().html("Full name is required");
      flag = 1;
       $('.loader').fadeOut();
     }
  
     if(dob == ''){
        $("#dob-error").show();
      flag = 1;
       $('.loader').fadeOut();
     }
 

     if(email == ''){
        $("#email-error").show();
      flag = 1;
       $('.loader').fadeOut();
     }
     else if(!/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(email)){
     $("#email-error").show().html("Email is not valid");
     
      flag = 1;
       $('.loader').fadeOut();
     }
     else
      {
        $("#email-error").hide();
      }

      if(location == ''){
       $("#location-error-para").show();
      flag = 1;
       $('.loader').fadeOut();
     }
    

     if(password == '')
     {
       $("#password-error1").show();
     }
     else if(password.length < 8){
       $("#password-error1").show().html("Password must be minimum 8 characters");
      flag = 1;
       $('.loader').fadeOut();
     }
     else if(password != confirm_password){
      $("#confirm-password-error").show().html("Password and Confirm password mismatch");
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
          $("#dob-error").show().html(response.errors.dob);
          
        if(response.errors.username)
            $("#email-error").show().html(response.errors.username);
            
        if(response.errors.city)
        {
          $('#location-error-para').show().html('City must be selected from suggestion!');
        }
                


            }else if(response.email_verify_required)
            {
              
              $('#email_activated').show();
            }

            else{
              
              toastr.success("Registeration done successfully!");
        window.location.href = "{{{ url('/login') }}}";
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
 $("#forgot-password-link").click(function(){
  $("#forgot-password-form").show();
  $("#form1").hide();
});
});
</script>

</body>
</html>

