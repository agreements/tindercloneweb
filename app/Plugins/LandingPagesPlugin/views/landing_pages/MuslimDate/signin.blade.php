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
        <link rel="stylesheet" href="@plugin_asset('LandingPagesPlugin/MuslimDate/css/emuslima.css')">
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
            top:-18px !important;
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
    <body>
        <div class="loader"></div>
        <div class="col-md-12 header-height header-bg header-padding absolute-position z_index-change">
            <img class="img_margin" src="{{{asset('uploads/logo')}}}/{{{$website_outerlogo}}}"/>
            <ul class="list-inline display_inline float_right display_flex">
                <li class="li-padding">
                    <button type="button" class="btn btn-padding btn_border btn-border_radius btn_bg btn_color login_btn" onclick="window.location = '{{{url('/register')}}}'">{{{trans('app.signup')}}}</button>
                </li>
                <li>
                    {{{Theme::render('top-header')}}}
                </li>
            </ul>
        </div>
        <div class="col-md-12 col_bg_img col_main-height bg_size" style=" background:url('@if($website_backgroundimage) {{{asset("uploads/backgroundimage/{$website_backgroundimage}")}}} @else @plugin_asset('LandingPagesPlugin/MuslimDate/images/top-img.png') @endif'); background-size:cover;height:100vh">
        <div class="row">
        <div class="@if($only_social_logins == 'true') col-md-12 makeitcenter text-center @else col-md-6 @endif  col-xs-12 col_main-text-margin">
            <h2 class="text-center text_white head_opacity">{{{trans_choice('LandingPagesPlugin.welcome_to',0)}}} <span class="bold_heading">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_bold_text',1)}}}</span></h2>
            <h3 class="text-center text_white">{{{trans_choice('LandingPagesPlugin.first_screen_main_heading_sub',2)}}}</h3>
            <div class="text-center social_margin">
                <!-- <img class="cursor_pointer" src="f_login.png"/>
                    <img class="cursor_pointer" src="g_login.png"/> -->
                {{{Theme::render('login')}}}
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
              <p class="sign_up_text text_white">{{{trans_choice('LandingPagesPlugin.signup_bottom',0)}}}
                        <a href="@LandingPageUrl('terms_and_conditions')">{{{trans('LandingPagesPlugin.terms_and_conditions')}}}</a>
                        <a href="@LandingPageUrl('privacy_policy')">{{{trans('LandingPagesPlugin.privacy_policy')}}}</a>
                        <a href="@LandingPageUrl('cookie_policy')">{{{trans('LandingPagesPlugin.cookie_policy')}}}</a>
            </p>
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
        $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });
        $(document).ready(function(){
        
        
        $('.dropdown-menu a').on('click', function(){    
            $(this).parent().parent().prev().html($(this).html() + '<span class="caret"></span>');    
        });
        });
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
          /*
        $('#registration-form').on('click',function(){
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
    <script>
        new WOW().init();
    </script>
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
        $(document).ready(function(){
         $("#forgot-password-link").click(function(){
          $("#forgot-password-form").show();
          $("#form1").hide();
        });
        });
    </script>
    </body>
</html>