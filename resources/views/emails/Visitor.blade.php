<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Email Templates</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
</head>
<body style="text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);font-family: 'Roboto' !important;">
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
                    <img class="td-fold" src="{{{url('uploads/email_template/fold.png')}}}"/>
                  </div>  
                 <p style="font-size: 18px;margin-top: 24px;margin-left:6px;opacity:0.8;font-family: 'Roboto';">{{{trans('email.some_visited')}}}</p>
               

           </td>
      </tr>
      <tr>
          <td style="padding: 24px 24px 0px 24px;background-color: #FFFFFF;">
             <div style="    background: #FFFFFF;
    overflow: hidden;
    height: 50px;
    margin-bottom: 16px;">
              
               
             
              
                <h4 style="   color: rgb(102, 102, 102);
    font-size: 18px;
    margin-bottom: 0;
    text-transform: capitalize;font-family: inherit;
    font-weight: 500;
    line-height: 1.1;    display: inline-block;
    -webkit-margin-before: 1.33em;
    -webkit-margin-after: 1.33em;
    -webkit-margin-start: 0px;
    -webkit-margin-end: 0px;position:relative;top:-12px;margin-bottom:0px;"><span>{{{$user->name}}}</span>, <span>{{{$user->age()}}}</span></h4>
                
                
                <input type="hidden">
             
              
             </div>
             
            <div style="
    display: inline-block;">
	    <img src="{{{$user2->profile_pic_url()}}}" width="100" height="100" style="object-fit:cover;"/>
            </div>
           
            <div style="width: 300px;  padding-top: 14px;
    padding-right: 15px;
    padding-left: 15px;display:inline-block;margin-bottom:20px;vertical-align: top;">
            <p style="  display: inline;
  font-size: 16px;
  color: black;
  opacity: 0.87;margin: 0 0 10px;">{{{$user2->name}}} {{{trans('email.visited_profile')}}}</p>
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
  background-color: #1AC636;width:106px;border-radius:23px;">{{{trans('email.message')}}}</a>
            </div>
           
          
        

       
         
   
         
        </td>
      </tr>
       <tr>
         <td style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
           <p class="footer-text" style=" color: #000000;opacity: 0.54;font-size: 11px;">{{{$footer_text}}}</p>
          

          </td>
          
       </tr>
     </table>
   </td>
  </tr>
 </table>
 
  <script>
$( document ).ready(function() {
   $("#close-button").click(function()
   {
     $("#close-button").hide();
     $("#close-button-pressed").show();
   });
     $("#like-button").click(function()
   {
     $("#like-button").hide();
     $("#like-button-pressed").show();
   });

});
 </script>
</body>
</html>
