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
              <img style="max-width: 140px" class="logo-custom" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}" style="margin-left: -18px;margin-bottom: -7px;margin-top: -12px;">
               </div>
               <div style="display:inline-block;">
                  <img src="{{{url('uploads/email_template/fold.png')}}}" />
                </div>
               <p style="font-size: 18px;margin-top: 24px;margin-left:6px;opacity:0.8;font-family: 'Roboto';">Its {{{$user2->name}}}'s {{{trans('email.friends_today')}}}</p>
                </div>
              </div>

           </td>
      </tr>

       <tr>
          <td class="td-user" style="padding: 24px 24px 0px 24px;background-color: #FFFFFF;">
             <div style="width:300px;display:inline-block;">
             <div style="background-image: url(http://hahaquotes.com/wp-content/uploads/2015/12/Happy-Birthday-Images-for-friend-download-1080p.jpg);
             padding: 2px;height: 274px;width: 292px;margin-bottom: 23px;
    background-position:center;">
            </div>
             <ul style="padding: 10px 10px 10px 0px;">
                          <li style="list-style-type: none;font-weight: bold;font-size:23px;opacity: 0.7;"><a href="#" style="color: #000000;
  opacity: 0.54;
  font-size: 16px;
  font-weight: 500;text-decoration:none;">{{{$user2->name}}},{{{$user2->age()}}}</a></li>
                          <li style="list-style-type: none;font-size: 16px;opacity: 0.54;">{{{$user2->city}}},{{{$user2->country}}}</li>
                        </ul>
                      </div>
         
     
       
          
        

       
         <div style="width:200px;margin-left:22px;display:inline-block;vertical-align: top;">
         <label style="font-weight: normal;font-size: 14px;color: black;opacity: 0.87;
  margin-bottom: 24px;display: inline-block;
    max-width: 100%;">Its {{{$user2->name}}}'s birthday today send @if($user2->gender == 'F') her @else him @endif some wishes</label>

 <a href="{{{url('/')}}}"  style="margin-top: 24px!important;background-color: #1AC636;color: #fff;
    background-color: #5cb85c;
    border-color: #4cae4c;display: inline-block;
    padding: 6px 12px;
    text-decoration: none;
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
    border: 1px solid transparent;  display: block;
    margin-top: 18px;
  padding-left: 10px;
  padding-right: 10px;
  background-color: #1AC636;width:106px;border-radius:23px;">{{{trans('email.wish')}}}</a>
</div>
      
         
        </td>
      </tr>
   <tr>
     
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

</body>
</html>