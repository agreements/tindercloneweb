<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<link rel="stylesheet" href="@theme_asset('css/flag.css')" type="text/css" media="screen" />
<div class="col-md-12">
    <div class="col-md-12 right_box right_box_pad">
        <div class="cont-header-settings">
            <h4 class="h4-settings h4-header"><strong>{{{trans_choice('app.advance', 1)}}} {{{trans_choice('app.setting', 2)}}}</strong></h4>
        </div>
        <div class=" cont-body cont-body-setting">
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.change', 0)}}} {{{trans_choice('app.email', 1)}}}</strong> <i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                <form role="form" name="changeEmail" method="POST" action="{{{ URL::to('changeEmail') }}}" id="changeEmail" class="display_none">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" name="email" placeholder="{{{trans_choice('app.password_field_holder', 1)}}} {{{trans_choice('app.email', 1)}}} {{{trans_choice('app.address', 1)}}}">
                    </div>
                    <button type="button" class="btn btn-default btn-hide" id="updateemail"> {{{trans_choice('app.submit', 1)}}} </button>
                    <button type="reset" class="btn btn-default cancel btn-hide reset">{{{trans_choice('app.cancel', 1)}}}</button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.change', 0)}}} {{{trans_choice('app.password', 1)}}}</strong> <i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                <form role="form" method="POST" action="{{{ URL::to('changePassword') }}}" id="form_changePassword" name="changePassword" class="display_none">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{{trans_choice('app.password_field_holder', 0)}}} {{{trans_choice('app.password', 1)}}}" name="oldPassword">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{{trans_choice('app.password_field_holder', 1)}}} {{{trans_choice('app.password', 1)}}}" name="password">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" placeholder="{{{trans_choice('app.password_field_holder', 2)}}} {{{trans_choice('app.password_field_holder', 1)}}} {{{trans_choice('app.password', 1)}}}" name="confirm_password" >
                    </div>
                    <button type="button" class="btn btn-default btn-hide" id="updatepassword" name="changePassword"> {{{trans_choice('app.submit', 1)}}} </button>
                    <button type="button" class="btn btn-default cancel btn-hide reset">{{{trans_choice('app.cancel', 1)}}}</button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.privacy', 1)}}}</strong><i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                <form action="{{{url('save_privacy_settings')}}}" method="POST" id="form_privacy">
                    <table class="table table-settings">
                        <tbody>
                            <tr>
                                <td>{{{trans_choice('app.show_me_online', 1)}}}</td>
                                <td class=" val">@if($privacy_settings["online_status"]) {{{trans_choice('app.yes',1)}}} @else {{{trans_choice('app.no',1)}}} @endif</td>
                                <td class=" option">
                                    <!--
                                        <input class="ƒ$("#block"+user_id).fadeOut();" type="radio" name="show_online" value="1"> Yes
                                        <input type="radio" name="show_online" value="0"> No
                                        -->
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="online_status" id="online_settings" class="radio" value="1" @if($privacy_settings["online_status"])
                                            checked="checked"@endif/>
                                            <label for="online_settings"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="online_status" id="online_settings2" class="radio" value="0" @if(!$privacy_settings["online_status"])
                                            checked="checked"@endif/>
                                            <label for="online_settings2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{{trans_choice('app.show_distance',1)}}}</td>
                                <td class=" val">@if($privacy_settings["show_distance"]) {{{trans_choice('app.yes',1)}}} @else {{{trans_choice('app.no',1)}}} @endif</td>
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="show_distance" id="distance_settings" class="radio" value="1"  @if($privacy_settings["show_distance"])
                                            checked="checked"@endif/>
                                            <label for="distance_settings"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="show_distance" id="distance_settings2" class="radio" value="0"  @if(!$privacy_settings["show_distance"])
                                            checked="checked"@endif/>
                                            <label for="distance_settings2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-default btn-hide" id="updateprivacy"> {{{trans_choice('app.submit', 1)}}} </button>
                    <button type="reset" class="btn btn-default cancel btn-hide">{{{trans_choice('app.cancel', 1)}}}</button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.notification_settings', 1)}}}</strong><i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                <form action="{{{url('save_notif_settings')}}}" method="POST" id="form_notification">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                    <table class="table table-settings">
                        <thead>
                            <tr>
                                <td></td>
                                <td>{{{trans_choice('app.browser', 1)}}}</td>
                                <td>{{{trans_choice('app.email', 1)}}}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Matches</td>
                                @if(!isset($notif_settings['browser_match']) || $notif_settings['browser_match'] == 1)
                                <td class="val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class="val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class="option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="browser_match" id="browser_matches" class="radio" value="1" @if(!isset($notif_settings['browser_match']) || $notif_settings['browser_match'])
                                            checked="checked"@endif/>
                                            <label for="browser_matches"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="browser_match" id="browser_matches2" class="radio" value="0" @if(!(isset($notif_settings['browser_match']) && $notif_settings['browser_match']))
                                            checked="checked"@endif/>
                                            <label for="browser_matches2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                                @if(!isset($notif_settings['email_match']) || $notif_settings['email_match'] == 1)
                                <td class="val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class="val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class="option">
                                    <div class="radio_cnt_settings">
                                        <input type="radio" name="email_match" id="email_matches" class="radio" value="1" @if(!isset($notif_settings['email_match']) || $notif_settings['email_match'])
                                        checked="checked"@endif/>
                                        <label for="email_matches"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                    </div>
                                    <div>
                                        <input type="radio" name="email_match" id="email_matches2" class="radio" value="0" @if(!(isset($notif_settings['email_match']) && $notif_settings['email_match']))
                                        checked="checked"@endif/>
                                        <label for="email_matches2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{{trans_choice('app.liked_you', 1)}}}</td>
                                @if( $notif_settings['browser_liked'] == 1)
                                <td class="val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class="val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="browser_liked" id="browser_liked" class="radio" value="1" @if($notif_settings['browser_liked'])
                                            checked="checked"@endif/>
                                            <label for="browser_liked"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="browser_liked" id="browser_liked2" class="radio" value="0" @if(! $notif_settings['browser_liked'])
                                            checked="checked"@endif/>
                                            <label for="browser_liked2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                                @if( $notif_settings['email_liked'] == 1)
                                <td class=" val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class=" val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="email_liked" id="email_liked" class="radio" value="1" @if( $notif_settings['email_liked'])
                                            checked="checked"@endif/>
                                            <label for="email_liked"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="email_liked" id="email_liked2" class="radio" value="0" @if(! $notif_settings['email_liked'])
                                            checked="checked"@endif/>
                                            <label for="email_liked2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{{trans_choice('app.visitor', 1)}}}</td>
                                @if( $notif_settings['browser_visitor'] == 1)
                                <td class=" val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class=" val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="browser_visitor" id="browser_visitors" class="radio" value="1" @if( $notif_settings['browser_visitor'])
                                            checked="checked"@endif/>
                                            <label for="browser_visitors"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="browser_visitor" id="browser_visitors2" class="radio" value="0" @if(! $notif_settings['browser_visitor'])
                                            checked="checked"@endif/>
                                            <label for="browser_visitors2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                                @if($notif_settings['email_visitor'] == 1)
                                <td class=" val">{{{trans_choice('app.yes', 1)}}}</td>
                                @else
                                <td class=" val">{{{trans_choice('app.no', 1)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="email_visitor" id="email_visitors" class="radio" value="1" @if($notif_settings['email_visitor'])
                                            checked="checked"@endif/>
                                            <label for="email_visitors"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="email_visitor" id="email_visitors2" class="radio" value="0" @if(!$notif_settings['email_visitor'])
                                            checked="checked"@endif/>
                                            <label for="email_visitors2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-default btn-hide" id="update_notification"> {{{trans_choice('app.submit', 1)}}} </button>
                    <button type="reset" class="btn btn-default cancel btn-hide">{{{trans_choice('app.cancel', 1)}}}</button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.invisible_form', 0)}}}</strong><i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                <form  method="POST" id="form_invisible">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}"> 
                    <table class="table table-settings">
                        <tbody>
                            <tr>
                                <td>{{{trans_choice('app.invisible_form', 1)}}}</td>
                                @if($invisible_settings['hide_visitors'] == 1)
                                <td class=" val">{{{trans_choice('app.yes', 0)}}}</td>
                                @else
                                <td class=" val">{{{trans_choice('app.no', 0)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="hide_visitors" id="hide_visitors" class="radio" value="1" @if($invisible_settings['hide_visitors'])
                                            checked="checked"@endif/>
                                            <label for="hide_visitors"><span></span>{{{trans_choice('app.yes', 0)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="hide_visitors" id="hide_visitors2" class="radio" value="0" @if(! $invisible_settings['hide_visitors'])
                                            checked="checked"@endif/>
                                            <label for="hide_visitors2"><span></span>{{{trans_choice('app.no', 0)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>{{{trans_choice('app.invisible_form', 2)}}}</td>
                                @if($invisible_settings['hide_superpowers'] == 1)
                                <td class=" val">{{{trans_choice('app.yes', 0)}}}</td>
                                @else
                                <td class=" val">{{{trans_choice('app.no', 0)}}}</td>
                                @endif
                                <td class=" option">
                                    <div class="radiocnt">
                                        <div class="radio_cnt_settings">
                                            <input type="radio" name="hide_superpowers" id="hide_superpowers" class="radio" value="1" @if( $invisible_settings['hide_superpowers'])
                                            checked="checked"@endif/>
                                            <label for="hide_superpowers"><span></span>{{{trans_choice('app.yes', 1)}}}</label>
                                        </div>
                                        <div>
                                            <input type="radio" name="hide_superpowers" id="hide_superpowers2" class="radio" value="0" @if(! $invisible_settings['hide_superpowers'])
                                            checked="checked"@endif/>
                                            <label for="hide_superpowers2"><span></span>{{{trans_choice('app.no', 1)}}}</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-default btn-hide" id="update_invisible">{{{trans_choice('app.submit', 1)}}}</button>
                    <button type="button"  class="btn btn-default cancel btn-hide">{{{trans_choice('app.cancel', 1)}}}</button>
                </form>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.language', 1)}}}</strong><i class="fa fa-pencil-square-o pencil update_information fa-settings"></i></h4>
                <form role="form" name="changeEmail" method="POST" action="" id="" class="display_none">
                    <table class="table table-settings">
                        <tbody>
                            <tr>
                                <td>{{{trans_choice('app.your_language',1)}}}
                                </td>
                                <td class="">
                                    <div id="options"
                                        data-input-name="country2"
                                        data-selected-country="{{{$auth_user->language}}}">
                                    </div>
                                    <div class="dropdown">
                                        <!-- <li><a href="#">Choose Language</a></li> -->
                                        <!--
                                            <select class="language-select">
                                             <option value ="">
                                                                                    Choose Language
                                                                                 </option>
                                             @foreach($languages as $language)
                                             <option value="{{{$language}}}" data-language="{{{$language}}}">{{{$language}}}</option>
                                             @endforeach
                                            </select>
                                            -->
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- <button type="submit" class="btn btn-default btn-hide">{{{trans_choice('app.submit',1)}}}</button>
                    <button type="reset" class="btn btn-default cancel btn-hide reset">{{{trans_choice('app.cancel',1)}}}</button> -->
                </form>
            </div>
            <div class="clearfix"></div>


            <div class="col-md-12 form form-setting">
                <h4 class="h4-settings"><strong class="strong_setting">{{{trans_choice('app.user_account_deactivate_msg',0)}}}</strong><i class="fa fa-pencil-square-o pencil update_information fa-settings "></i></h4>
                
                <form role="form" name="changeEmail" method="POST" action="" id="" class="display_none">
                    <table class="table table-settings">
                        <tbody>
                            <tr>
                                <td>{{{trans_choice('app.user_account_deactivate_msg',1)}}}
                                </td>
                                <td>
                                   <button type="button" id="deactivate-account" class="btn btn-danger btn-xs">{{{trans_choice('app.user_account_deactivate_msg',2)}}}</button>
                                </td>
                            </tr>
                            <tr>
                                <td>{{{trans_choice('app.user_account_deactivate_msg',3)}}}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" onclick="showhidesocialpwd()" data-target="#delete-account-modal" style="width:70px">{{{trans_choice('app.user_account_deactivate_msg',4)}}}</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
               
            </div>
            <div class="clearfix"></div>




        </div>
    </div>
</div>

<!-- <div class="modal fade" id="delete-account-modal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Want to delete your account permenently ?</h4>
        </div>
        <div class="modal-body">
          <div class="col-xs-3">
          <label for="ex2">Confirm Your Password</label>
          <input class="form-control" id="" type="password">
        </div>
          
          <button type="button" class="btn btn-danger btn-md" id = "delete-account-btn">Delete Permenently</button>
      </div>
    </div>
  </div>
</div> -->
<div class="modal fade" id="delete-account-modal" tabindex="-1" role="dialog" 
      aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
            
                <h4 class="modal-title" id="myModalLabel">
                    {{{trans_choice('app.user_account_deactivate_msg',5)}}}
                </h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                
                <form class="form-horizontal " role="form" >
                  <div class="form-group password">
                    <label  class="col-sm-3 control-label"
                              for="inputEmail3">{{{trans_choice('app.user_account_deactivate_msg',6)}}}</label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" 
                        id="delete-confirm-password" placeholder="Enter Your Password"/>
                    </div>
                  </div>
                </form>
                
               <div class='loggedinsocial alert alert-info'>Logged in through social media!</div>
                
        
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-md" id = "delete-account-btn">{{{trans_choice('app.user_account_deactivate_msg',7)}}}</button>
            </div>
        </div>
    </div>
</div>
<input type = "hidden" value ="{{{trans_choice('app.invisible_modal_title',0)}}}" id = "invisible-modal-header">

@endsection
@section('scripts')
@parent
<script src="@theme_asset('js/setting.js')"></script>
<script src="@theme_asset('js/flag.js')"></script>
<script>
    var temp=[];
    @foreach($languages as $language)
     temp.push('{{{$language}}}');
    @endforeach
     
     
    temp= _.object(_.map(temp, function(x){return [x, x]})); 
    
    $('#options').flagStrap({
      countries:temp,
      buttonSize: "btn-sm",
      buttonType: "btn-info",
      labelMargin: "10px",
      scrollable: false,
      scrollableHeight: "350px"
    });
    
</script>
<script type="text/javascript">
    $(document).ready(function()
    {
/*
      $( ".form" ).hover(function() {
    $(this).find('i.update_information').fadeIn( "slow" );
    });
    $( ".form" ).mouseleave(function() {
    $(this).find('i.update_information').fadeOut( "slow" );
    });
*/
    $(".update_information").on("click",function(){
    $(this).parent().parent().find('td.val').fadeOut( "slow" );
    $(this).parent().parent().find('td.option').css("display","table-cell");
    $(this).parent().parent().find('button.btn-default').fadeIn( "slow" );
    $(this).parent().parent().find('form.display_none').fadeIn( "slow" );
    $(this).parent().parent().find('div.radiocnt').fadeIn('slow');
    });
    $(".cancel").on("click",function(){
    $(this).parent().find('td.val').css("display","table-cell");
    $(this).parent().find('td.option').fadeOut( "slow" );
    $(this).parent().find('button.btn-default').fadeOut( "slow" );
    });
    
    $(".reset").on("click",function(){
    $(this).parent().fadeOut( "slow" );
    });
    });
    $(".ripple").click(function(e){
        var rippler = $(this);
    
        // create .ink element if it doesn't exist
        if(rippler.find(".ink").length == 0) {
            rippler.append("<span class='ink'></span>");
        }
    
        var ink = rippler.find(".ink");
    
        // prevent quick double clicks
        ink.removeClass("animate");
    
        // set .ink diametr
        if(!ink.height() && !ink.width())
        {
            var d = Math.max(rippler.outerWidth(), rippler.outerHeight());
            ink.css({height: d, width: d});
        }
    
        // get click coordinates
        var x = e.pageX - rippler.offset().left - ink.width()/2;
        var y = e.pageY - rippler.offset().top - ink.height()/2;
    
        // set .ink position and add class .animate
        ink.css({
          top: y+'px',
          left:x+'px'
        }).addClass("animate");
    });
    
    
    $('#updateemail').on('click',function(){
    
    $.ajax({
    type: "POST",
    url: "{{{ url('changeEmail') }}}",
    data: $("#changeEmail").serialize(),
    success: function(msg){
                            
    if(msg.status!='error')
    {
        toastr.info('Email Changed');
        $("#changeEmail").fadeOut('slow');
                                
    }
    else
    {
        toastr.info(msg.message);
                                
    }
                            
                            
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.info("some error");
        
    }   
                      
    });
    });
    
    $('#updatepassword').on('click',function(){
    
    $.ajax({
    type: "POST",
    url: "{{{ url('changePassword') }}}",
    data: $("#form_changePassword").serialize(),
    success: function(msg){
                            
    if(msg.status!='error')
    {
        toastr.info('Password Changed');
        $("#form_changePassword").fadeOut('slow');
                                
    }
    else
    {
        toastr.info(msg.error);
                                
    }
                            
                            
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.info("some error");
    }   
                      
    });
    });
    
    $('#updateprivacy').on('click',function(){
    
    $.ajax({
    type: "POST",
    url: "{{{ url('save_privacy_settings') }}}",
    data: $("#form_privacy").serialize(),
    success: function(msg){
                            
    if(msg.status!='error')
    {
        toastr.info('{{{trans('app.settings_saved')}}}');
        
        window.location.reload();
        
        
                                
    }
    else
    {
        toastr.info(msg.error);
                                
    }
                            
                            
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.info("some error");
    }   
                      
    });
    });
    
    
    $('#update_notification').on('click',function(){
    
    $.ajax({
    type: "POST",
    url: "{{{ url('save_notif_settings') }}}",
    data: $("#form_notification").serialize(),
    success: function(msg){
                            
    if(msg.status!='error')
    {
        toastr.info('{{{trans_choice('app.settings_saved',1)}}}');
        window.location.reload();
                                
    }
    else
    {
        toastr.info(msg.error);
                                
    }
                            
                            
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.info("{{{trans_choice('app.error',1)}}}");
    }   
                      
    });
    });
    
    $('#update_invisible').on('click',function(){
    
    $.ajax({
    type: "POST",
    url: "{{{ url('save_invisible_settings') }}}",
    data: $("#form_invisible").serialize(),
    success: function(msg){
                            
    if(msg.status!='error')
    {
        toastr.info('{{{trans_choice('app.settings_saved',1)}}}');
        window.location.reload();
                                
    }
    else
    {
        toastr.info(msg.error);
                                
    }
                            
                            
    },
    error: function(XMLHttpRequest, textStatus, errorThrown) {
        toastr.info("{{{trans_choice('app.error',1)}}}");
    }   
                      
    });
    });
    
    
    $('input[name="hide_visitors"]').click(function(e) {
    
    if ($('input[id="hide_visitors"]').length == $("input[id='hide_visitors']:checked").length) {
    
    //alert('yes');
    //check if super power is on
    $.post("{{{ url('/isSuperPowerActivated/') }}}"+'/'+'{{{$auth_user->id}}}', function(data){
    
    if(!data)
    {
        //open activate super power pop up
        $('.superpower-invisible-header').text($("#invisible-modal-header").val());
        
        $('#myModalInvisibleMode').modal('show');
        
        $('.close').on('click', function () {
            $(this).data('clicked', true);
            $('input[id="hide_visitors"]').attr("checked",false);
        })
        
        
        
        if(!$('.close').data('clicked')) 
        {
                $('#superpower_invisible').val('1');
                
                
                $('input[id="hide_visitors"]').attr("checked",true);
        }
    
    }
                    
    })  
    }
    else
    {
    alert('no');
    }   
    }); 
    
    
    $("#options > ul > li > a").on('click',function(){
    
    var lang = $(this).data('val');
    
    $(".loader").fadeIn("slow");
    
    $.post('{{{url('/user/language/set')}}}', {language : lang}, function(e){
    
      if(e.status == 'success') {
       $(".loader").fadeOut("slow");
        toastr.success('{{{trans('app.your_language')}}}{{{trans('app.saved')}}}');
        window.location.reload();
      }else{
        toastr.error('failed to save language');
      }
      
    });
    
    
    });
    
    
</script>
<script>
	
	
	function showhidesocialpwd(){
		
		
		 if('{{{$user_password}}}')
	       {
	          $('.password').show();
	          $('.loggedinsocial').hide();
	       }
	       else
	       {
		      $('.password').hide(); 
		      
		      $('.loggedinsocial').show();
	       }

		
		
	}
	
	
	
    $(document).ready(function()
    {
      $(".option").css('display','none');

      $('#deactivate-account').on('click', function(){
            $.post("{{{url('user/deactivate')}}}", function(response){
                if(response.status == 'success') {
                    window.location.href = '{{{url("/logout")}}}';
                }
            });
      });

      $('#delete-account-btn').on('click', function(){
	       
	       
	        var data  = {};
	        
	        
	       if('{{{$user_password}}}')
	       {
	          data['confirm_password'] = $('#delete-confirm-password').val();
	       }
	       else
	       {
		       data['confirm_password'] = "";

	       }
	       
           
            
            
            $.post("{{{url('user/delete')}}}", data,function(response){
                if(response.status == 'success') {
                    window.location.href = '{{{url("/logout")}}}';
                }
                if(response.status == 'error') {
                    toastr.error("{{{trans_choice('app.user_account_deactivate_msg',8)}}}");
                }
            });
      });


    });
</script>
<style type="text/css">
    input[type='radio'] {
    -webkit-appearance: none;
    width: 12px;
    height: 12px;
    background: #CACACA;
    border-radius: 50%;
    box-shadow: none;
    margin: 4px 0 0;
    border-color:transparent;
    outline: none!important;
    }
    table {
    border: none;
    }
    td
    {
    border-right:none;
    }
    .btn-hide
    {
    display: none;
    }
    .dropdown-preferences
    {
    background: #2B65F8;
    }
    .show-radio
    {
    display: inline-block!important;
    }
    .table-settings
    {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
    margin-left: 0px!important;
    color: #ea2a67;
    }
    .h4-header
    {
    color: white!important;
    }
    .btn-hide
    {
    display: none;
    }
    .dropdown-preferences
    {
    background: #2B65F8;
    }
    .show-radio
    {
    display: inline-block!important;
    }
    .form-setting
    {
    margin-bottom: 15px;
    }
    .strong_setting {
    font-size: 18px;
    }
    .display_none
    {
    display: none;
    }
    .filter_icon{
    font-size: 26px;
    color: #5A5858;
    margin-left: 20px;
    }
    .filter_icon:hover
    {
    color: #928B8B;
    }
    #filterModal > div.modal-dialog > div > div.modal-header
    {
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
    color: #000000;
    border-bottom: none;
    }
    .drop_heading p{
    padding: 5px 10px;
    background-color: #A29E9E;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    color:#ffffff;
    }
    .drop_body{
    min-height: 120px;
    background-color: #E8E8E8;
    padding:10px;
    margin-bottom: 10px;
    }
    .drop_body p{
    padding: 5px;
    color: #000000;
    font-size: 12px;
    }
    .drop_body ul li{
    color:#000000;
    font-size: 16px;
    }
    .checkbox-inline{
    color:#000000;
    }
    .rang_slider{
    padding: 10px;
    }
    .rang_slider p input[type=text]{
    border: none;
    color: gray;
    background-color: #C5C5C5;
    width: 35%;
    }
    .stylish-input-group .input-group-addon{
    background: white !important; 
    }
    .stylish-input-group .form-control{
    border-right:0; 
    box-shadow:0 0 0; 
    border-color:#ccc;
    }
    .stylish-input-group button{
    border:0;
    background:transparent;
    }
    .right_box {
    box-shadow: 0px 0px 4px rgba(0,0,0,0.36);
    margin-bottom: 5%;
    }
    .right_box_pad
    {
    padding: 0px 0px 10px 0px;
    }
    .cont-header-settings
    {
    
    overflow: hidden;
padding: 15px;
background: #E52B50;
padding: 1% 0% 0% 0%;
text-align: center;
    }
    .cont-header-setting h4 {
    color: rgb(102, 102, 102);
    float: left;
    line-height: 20px;
    margin-bottom: 0;
    font-size: 15px;
    border-bottom: none;
    }
    .cont-body-setting-custom
    {
    padding: 10px;
    background:rgba(128, 128, 128, 0.24)
    }
</style>

@endsection