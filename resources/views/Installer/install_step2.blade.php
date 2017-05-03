<!DOCTYPE html">
<html>
    <head>
        <title>DatingFramework Installer v2</title>
        <link rel="stylesheet" href="{{{asset('css/bootstrap3.3.6.min.css')}}}">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <script src="{{{asset('js/jquery1.12.0.min.js')}}}"></script>
        <script src="{{{asset('js/bootstrap3.3.6.min.js')}}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.3.0/zxcvbn.js"></script>
        <link rel="stylesheet" href="{{{asset('Install')}}}/css/installer_new.css">
    </head>
    <body>
        <div class="col-md-12 full-col">
            <div class="col-md-8 main-col" style="display: block;" id = "admin-form-div">
                <div class="col-md-12 text-center top-head-col">
                    <img class="text-center" src="{{{asset('Install')}}}/images/logo.png" height="50px" width="250px"/>
                </div>
                <div class="row">
                    <div class="col-md-6 main-left-col">
                        <h5 class="text_white opacity_6">DatingFramework v1.5</h5>
                        <h2 class="text_white width_70">SETUP ADMIN &amp; TITLE.</h2>
                        <div class="col-md-10 left_bottom_div">
                            <div class="col-md-2 text-center step1_div">
                                <div class="row">
                                    <button type="button" class="btn circle-btn outline_none active1"></button>
                                    <h5 class="text_white step_text">Step 1</h5>
                                </div>
                            </div>
                            <div class="col-md-3 step_line_col active1">
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="row">
                                    <button type="button" class="btn circle-btn outline_none active1"></button>
                                    <h5 class="text_white step_text">Step 2</h5>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-6 main-right-col">
                        <div class="top-text-col">
                            <h5 class="text_white">Installing DatingFramework</h5>
                        </div>
                        <form action = "{{{url('/installer')}}}" method = "POST" id = "liteoxide-form-id">
                            {{csrf_field()}}
                            <input type = "hidden" name = "installation" value = "step3">
                            <div class="form-group form_style">
                                <label class="control-label" for="db_host">Admin Name</label>
                                <input type="text" class="form-control input_style" name = "name" id="name">
                                <p class=" error-para1" id="error-name-para">Name required</p>
                            </div>
                            
                            <div class="form-group form_style">
                                <label class="control-label" for="db_uname">Email</label>
                                <input type="email" class="form-control input_style" id="username" name ="username">
                                <p class=" error-para1" id="error-username-para">Username required</p>
                            </div>
                            
                            <div class="form-group form_style">
                                <label class="control-label" for="db_pass">Admin Password</label>
                                <input type="password" class="form-control input_style" id="db_pass" name ="password">
                                <div class="password-background"></div>
                                <p class=" error-para1" id="password-error">Password required</p>
                            </div>
                            
                            <div class="form-group form_style">
                                <label class="control-label" for="db_uname">Confirm Password</label>
                                <input type="password" class="form-control input_style" id="password_confirmation" name ="password_confirmation">
                                <p class=" error-para1" id="confirm_password-error">Password required</p> 
                            </div>
                           
                            <div class="form-group form_style">
                                <label class="control-label" for="db_name">Website Title</label>
                                <input type="text" class="form-control input_style" id="website_title" name ="website_title">
                               <p class="error-para1 " id="website-title-error">Website Title required</p> 
                            </div>
                            <input type="hidden" name="domain" id= "domain">
                            <div class="form-group form_style">
                                <button type="submit" class="btn btn-danger pull-right next_btn outline_none ">Install <i class="fa fa-angle-right next_icon"></i> </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- progressbar -->
            <div class="col-md-8 main-col_steps text-center" style = "display:none;" id ="progress-div">
                <div class="col-md-12">
                    <h3 class="opacity_6">Installation is in Progress</h3>
                    <h6 class="opacity_6">This will install DatingFramework Version 1.5</h6>
                </div>
                <div class="col-md-12 progress_div">
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="">
                            50% Complete (info)
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                <a type="button" href="{{{url('/admin/dashboard')}}}" class="btn btn-danger next_btn outline_none done-btn">Admin Panel<i class="fa fa-angle-right next_icon"></i> </a> 
                <a type="button" href="{{{url('/')}}}" class="btn btn-danger next_btn outline_none done-btn">Visit Website<i class="fa fa-angle-right next_icon"></i> </a> 
                </div>
            </div>
            <!-- end progressbar -->



        </div>


        <script>
            $(document).ready(function()
            {
            $('.form-control').on('focus blur', function (e) {
               $(this).parents('.form-group').toggleClass('focused', (e.type === 'focus' || this.value.length > 0));
            }).trigger('blur');
            });
        </script>
        <script>
            $(document).ready(function(){
              $( document ).ready(function() {
             $('#db_pass').on('propertychange change keyup paste input', function() {
               // TODO: only use the first 128 characters to stop this from blocking the browser if a giant password is entered
               var password = $(this).val();
               var passwordScore = zxcvbn(password)['score'];
               
               var updateMeter = function(width, background, text) {
                 $('.password-background').css({"width": width, "background-color": background});
                 $('.strength').text('Strength: ' + text).css('color', background);
               }
               
               if (passwordScore === 0) {
                 if (password.length === 0) {
                   updateMeter("0%", "#ffa0a0", "none");
                 } else {
                   updateMeter("20%", "#ffa0a0", "very weak");
                 }
               }
               if (passwordScore == 1) updateMeter("40%", "#ffb78c", "weak");
               if (passwordScore == 2) updateMeter("60%", "#ffec8b", "medium");
               if (passwordScore == 3) updateMeter("80%", "#c3ff88", "strong");
               if (passwordScore == 4) updateMeter("100%", "#ACE872", "very strong"); // Color needs changing
               
             });
             
             // TODO: add ie 8/7 support, what browsers didnt support this check market share
             $('.show-password').click(function(event) {
               event.preventDefault();
               if ($('#db_pass').attr('type') === 'password') {
                 $('#db_pass').attr('type', 'text');
                 $('.show-password').text('Hide password');
               } else {
                 $('#db_pass').attr('type', 'password');
                 $('.show-password').text('Show password');
               }
             });
             
            });
            });
            
            
            
            
            
        </script>  

<script>
$(document).ready(function()
{
   $("#liteoxide-form-id").submit(function(e){
    e.preventDefault();
    $(".error-para1").hide();
    var name=$("#name").val();
    var username=$("#username").val();
    var password=$("#db_pass").val();
    var password_confirmation=$("#password_confirmation").val();
    var website_title=$("#website_title").val();
    var flag=0;

    if(name == '')
    {
      $("#error-name-para").show();
      flag=1;
    }
    if(username == '')
    {
       $("#error-username-para").show();
       flag=1;
    }
    if(password == '')
    {
      $("#password-error").html("Password cannot be empty").show();
      flag=1;
    }
    else if(password.length < 8)
    {
      $("#password-error").html("Password must be minimum 8 chracaters").show();
      flag=1;
    }
    else if(password_confirmation == '')
    {
      $("#confirm_password-error").html("Password cannot be empty").show();
      flag=1;
    }
     else if(password != password_confirmation)
    {
      $("#confirm_password-error").html("Passwords mismatch").show();
      flag=1;
    }
    if(website_title == '')
    {
      $("#website-title-error").show();
      flag=1;
    }

  if(flag == 0){

    $('#admin-form-div').hide();
    $('#progress-div').show();
  
  
  $('.progress-bar-striped').css('width', '50%');
  $('.progress-bar-striped').text('50% Completed');
    
$('.done-btn').hide();

      $("#domain").val(window.location.hostname);


       $.ajax({
       type: "POST",
       url: '{{{url('/installer')}}}',
       data: $("#liteoxide-form-id").serialize(),
       success: function(response) {
            console.log(response);
            
  
          if (response.status == 'completed') {
            
            
            $('.progress-bar-striped').css('width', '100%'); 
            $('.progress-bar-striped').text('100% Completed');
            
            $('.progress-bar-striped').removeClass('active');
            $('.done-btn').show();
            
          }


         
           else if(response.status == 'validation failed')
          {
            

            if(response.errors.name)
            {
                $("#error-name-para").html(response.errors.name).show();
            
            }
             if(response.errors.username)
            {
                $("#error-username-para").html(response.errors.username).show();
             
            }
             if(response.errors.password)
            {
                $("#password-error").html(response.errors.password).show();
            }
             if(response.errors.password_confirmation)
            {
               $("#confirm_password-error").html(response.errors.password_confirmation).show();
            }
             if(response.errors.website_title)
            {
               $("#website-title-error").html(response.errors.website_title).show();
            }

            $('#admin-form-div').show();
            $('#progress-div').hide();
               $('.done-btn').hide();
          }


          if(response.error_type == "UNKNOWN_ERROR") {
            alert(response.errors);
          }

          if(response.cron_error.length > 1) {
            alert("Cron error : " + response.cron_error);
          } 

       }
  });

                }
  });
});
</script>



    </body>
    <style type="text/css">
       body
     {
        background: url("{{{asset('Install')}}}/images/installer_welcome.jpg");
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: auto;
        background-size:cover;
        background-position: center;
        
    }
        .error-para1 {
        color:rgb(181, 71, 71);
        display: none;
        }
    </style>
</html>
