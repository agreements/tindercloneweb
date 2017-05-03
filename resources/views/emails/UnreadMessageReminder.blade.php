<?php 
    use App\Models\Settings;
    ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Email Templates</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
        <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="Template_one.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>
    <body style="text-decoration: none;margin: 0; padding: 0;background-color:rgba(128, 128, 128, 0.39);font-family: 'Roboto', sans-serif;">
        <table border="1" cellpadding="0" cellspacing="0" width="100%;">
            <tr>
                <td style="padding-top: 50px;padding-bottom: 50px;">
                    <table align="center" border="1" cellpadding="0" cellspacing="0" width="600px" style="border-collapse: collapse;border-color:rgba(0, 0, 0, 0.07);box-shadow: 0px 2px 5px #888888;">
                        <tr>
                            <td style="background-color: #FFFFFF;border-top: 2px solid #FF4E79;">
                                <div style="display:inline-block;width:100%;">
                                    <div class="row" style="padding:24px;position:relative">
                                        <div style="display:inline-block;margin-right:335px;vertical-align: middle;">
                                            <img style="max-width: 140px" src="{{{url('uploads/logo/')}}}/{{{$website_logo}}}">
                                        </div>
                                        <div style="display:inline-block;position: absolute;right: 0px;top: 0px;">
                                            <img src="{{{url('uploads/email_template/fold.png')}}}" />
                                        </div>
                                        <p style="font-size: 18px;margin-left:22px;">{{{trans('email.unread_msg')}}}</span></p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 24px 24px 0px 24px;background-color: #FFFFFF;">
                                <div>
                                    <p>{{{trans('email.hi')}}}, {{$user->name}}</p>
                                </div>
                                <div style="margin-left: 22px;">
                                    <p>{{{trans('email.total')}}} <b>{{$user->unread_messages_count}}</b> {{{trans('email.unread_msg')}}}. {{{trans('email.visit')}}} <a href="{{url('/login')}}">{{$website_title}}</a></p>
                                </div>
                                
                            </td>
                        </tr>
                        <tr>
                            <td class="td-footer" style="  background-color: #FFFFFF;padding: 32px 24px 24px 24px;position:relative;z-index:999999;">
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