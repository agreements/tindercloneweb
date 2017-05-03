<?php 
use App\Models\Settings;
?>
<!DOCTYPE html PUBLIC>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Email Templates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="font-family: 'Roboto', sans-serif;text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);">
 <table border="1" cellpadding="0" cellspacing="0" width="100%;">
  <tr>
   <td>
     <table align="center" border="1" cellpadding="0" cellspacing="0" width="600px" style="border-collapse: collapse;border-color:rgba(0, 0, 0, 0.07);box-shadow: 0px 2px 5px #888888;">

       <tr>
           <td style="background-color: #FFFFFF;padding:0px 0px 10px 24px;border-top: 2px solid #FF4E79;">
            
              <div style="display:inline-block;">
                 <div class="row">
                  <div style="display:inline-block;margin-right:335px;vertical-align: middle;">
               <img style="max-width: 140px" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}">
                 </div>
                 <div style="display:inline-block;">
                   <img src="{{{url('uploads/email_template/fold.png')}}}" />
                 </div>
               <p style="font-size: 18px;margin-top: 24px;margin-left:6px;opacity:0.8;font-family: 'Roboto';">{{{trans('email.birthday_today')}}}</p>
                 </div>
              </div>
              
               
              
            
           </td>
      </tr>

       <tr>
          <td style="padding: 24px 24px 0px 24px;background-color: #FFFFFF;">
              <div style="width:300px;height:auto;">
             <div style="display: inline-block">
	           <img src="{{{$user->profile_pic_url()}}}" width="100" height="100" style="object-fit:cover;"/>
            </div>

                          <ul style="padding: 10px 10px 10px 0px;">
                          <li style="list-style-type: none;font-weight: bold;font-size:23px;opacity: 0.7;"><a class="li-a" href="#" style="color: #000000;
  opacity: 0.54;
  font-size: 16px;
  font-weight: 500;text-decoration:none;">{{{$user->name}}},{{{$user->age()}}}</a></li>
                          <li style="list-style-type: none;font-size: 16px;opacity: 0.54;">{{{$user->city}}},{{{$user->country}}}</li>
                         </ul>
            </div>
         
        </td>
      </tr>
  
       <tr>
         <td class="td-footer" style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
           <p class="footer-text" style=" color: #000000;opacity: 0.54;font-size: 11px;">{{{$footer_text}}}</p>

          </td>
           </table>
         </td>
       </tr>
     </table>
   </td>
  </tr>
 </table>

<script>
  $(document).ready(function() {

    $("button").hover(function(){
    $(this).css("background-color", "#398439");
     $("a.button-link").css("color","white");
    }, function(){
    $(this).css("background-color", "#1AC636");  
    $("button").find("a").css("color", "#FFFFFF");
});  
   $("a.li-a").hover(function(){
    $(this).css("color", "blue");
    }, function(){
    $(this).css("color", "black");
});
  
});
</script>
</body>
</html>