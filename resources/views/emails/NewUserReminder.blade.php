<?php 
use App\Models\Settings;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Email Templates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="font-family: 'Roboto' !important;text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);">
 <table border="1" cellpadding="0" cellspacing="0" width="100%;">
  <tr>
   <td>
     <table align="center" border="1" cellpadding="0" cellspacing="0" width="600px" style="border-collapse: collapse;border-color:rgba(0, 0, 0, 0.07);box-shadow: 0px 2px 5px #888888;">

       <tr>
           <td style="background-color: #FFFFFF;padding:0px 0px 10px 24px;border-top: 2px solid #FF4E79;">
               <div style="display:inline-block;">
                 <div class="row">
                  <div style="display:inline-block;margin-right:335px;vertical-align: middle;">
                    <img style="max-width: 230px" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}">
                 </div>
               
               <div style="display:inline-block;">
                   <img class="td-fold" src="{{{url('uploads/email_template/fold.png')}}}" />
                 </div>
                 <p style="font-size: 18px;margin-top: 24px;">These two girls want to chat</p>
                 </div>
               </div>
           </td>
      </tr>
       <tr>
          <td class="td-user" style=" padding: 24px 24px 0px 24px;
  background-color: #FFFFFF;">
              <div class="img-div" style="display: inline-block;">
	    <img src="http://www.download-free-wallpaper.com/img61/efcfyojxwrzbzjkkvfqe.jpg" width="100" height="100" />
                 
              </div>
              <div class="img-div" style="background:url('http://placekitten.com/g/400/200') no-repeat top / 130px auto;background-position: center;
    background-size: 80px 60px;  height:62px;
    width:64px;
    overflow:hidden;
    border-radius:50%;
    display: inline-block;">
              </div>
         
     
       
           <p class="td-user-text" style="font-size: 21px;">Be the first one to send them a message</p>
        

       
         <button type="button" class="btn btn-success td-user-btn" style="  border-radius: 15px;
  padding-left: 20px;
  padding-right: 20px;
  background-color: #1AC636;border-color: #4cae4c;display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;color:#FFFFFF">Say Hello!</button>
        

       
         <p class="td-social-text" style="  font-size: 18px;
  color: black;
  opacity: 0.5;
  margin-bottom: 3px;
  display: table;
  margin-top: 10%;">Have Fun!</p>
         <p class="td-social-team" style="  font-size: 16px;
  color: black;
  opacity: 0.4;
  display: table;
  margin-bottom: 30px;">Dating Team</p>
         </td>
      </tr>
       <tr>
         <td class="td-footer" style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
           <p class="footer-text" style=" color: #000000;opacity: 0.54;font-size: 11px;">{{{$footer_text}}}</p>
           <ul class="td-social-ul td-footer-ul" style="  display: table;padding-left: 0px;margin-bottom: 0px;margin-top: 24px;">
             <li class="remove-list-style td-footer-li" style="list-style-type: none; display: inline;"><a  class="li-a" href="#" style="color: red; opacity: 0.87;font-size: 11px;font-weight: 400;text-decoration:none;">UNSUBSCRIBE</a></li>

           </ul>

          </td>
           </table>
         </td>
       </tr>
     </table>
   </td>
  </tr>
 </table>
<div class="image-div" style=" height:62px;
    width:64px;
    overflow:hidden;
    border-radius:50%;
    display: inline-block;">
  <img class="td-custom-image td-custom-bday-img" style="  
    margin: auto;right: 310px;
    top: 484px;
    z-index: 1;
    position: absolute;
    opacity: 0.3;"src="{{{url('uploads/email_template/images/message.png')}}}"/> 
 </div>
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