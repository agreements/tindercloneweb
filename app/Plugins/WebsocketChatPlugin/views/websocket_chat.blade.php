
<?php use App\Components\Theme; ?>

<link rel="stylesheet" type = "text/css" href="@plugin_asset('WebsocketChatPlugin/css/chat.css')">

<link rel="stylesheet" href="@plugin_asset('WebsocketChatPlugin/css/emoji.min.css') ">
 
<link rel="stylesheet" href="@plugin_asset('WebsocketChatPlugin/css/jemoji.css') ">

<link href="@theme_asset('css/chat.css')" rel="stylesheet" />
  



<!-- Chat Modal -->
<div ng-controller="WebsocketChatController" id="websocket_chat_modal" class="modal fade" data-backdrop="static" data-keyboard="false" role="dialog" nv-file-drop="" uploader="uploader">
	
	

	
<div class="modal-dialog">
    <!-- Chat Modal content-->
    
          
            
    <div class="col-md-12 col-xs-12 modal-content position_relative chat_modal_content ">
	    <i class="fa fa-users users_list_mob" ng-show="mobile"></i>
        <div class="row" style = "text-align:left;color:black">
	      
            <div class="col-md-4 col-xs-4 chat_left_border-right left-main-col_height" ng-if="desktop">
                <div class="row">
	               
                    <div class="col-md-12 col-xs-12 modal_search_div_padding modal_search_div_height border_bottom_search" ng-show='desktop'>
                        <div class="form-group">
                            <input type="text" class="form-control position_relative search_input_border opacity_7" ng-model="userSearch" placeholder="Search by Name">
                            <i class="fa fa-search position_absolute search_position opacity_7"></i>
                        </div>
                    </div>
                    <!-- All users content-->
                    <div id="all-col" class="col-md-12 col-xs-12 user_main_col_height user_main_col_border_bottom  ">
                        <div class="row users_list_cnt">
                            <div  ng-click="loadChatData(user,chatUsers.indexOf(user))"   data-index="[[$index]]" data-id="user-[[user.user.id]]" ng-class="{active: $index == selected}" class="col-md-12 col-xs-12 user_div_padding left-first-div all_users" ng-repeat="user in chatUsers | filter:userSearch">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]<i ng-show="user.online"  class="fa fa-circle position_absolute chat_online_icon"></i></h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-xs-2 user_right_icon_padding">
                                    
                                    <span ng-show="user.user.total_unread_messages_count" style="border-radius: 50%;
    background-color: red;
    color: white;
    padding: 3px 7px 3px 7px;;
    position: relative;
    right: -6px;
    top: 9px;
    box-shadow:  1px 1px 6px 1px rgba(0, 0, 0, 0.52);font-size: 12px;">[[user.user.total_unread_messages_count]]</span>
                                </div>
                            </div>
                            
                            <div ng-hide="chatUsers.length" class="col-md-12 col-xs-12 no_users_match_only">
	                            No users to chat!
                            </div>   
                            
                        </div>
                    </div>
                    <!-- Matched users content-->
                    <div id="matches-col" class="col-md-12 col-xs-12 user_main_col_height user_main_col_border_bottom  " style="display:none">
                        <div class="row users_list_cnt">
                            <div class="col-md-12 col-xs-12 col-xs-12 user_div_padding left-first-div" ng-class="{active: $index == selected}" ng-if="user.matched" ng-click="loadChatData(user,$index)" ng-repeat="user in chatUsers">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]<i ng-show="user.online"  class="fa fa-circle position_absolute chat_online_icon"></i></h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-s-2 user_right_icon_padding">
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Online users content-->
                    <div id="online-col" class="col-md-12 col-xs-12 user_main_col_height user_main_col_border_bottom  " style="display:none">
                        <div class="row users_list_cnt">
                            <div class="col-md-12 col-xs-12 col-xs-12 user_div_padding left-first-div" ng-class="{active: $index == selected}" ng-if="user.online" ng-click="loadChatData(user,$index)" ng-repeat="user in chatUsers">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]<i ng-show="user.online"  class="fa fa-circle position_absolute chat_online_icon"></i></h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-s-2 user_right_icon_padding">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                                       <!-- Chat tabs-->
                    <div class="col-md-12 col-xs-12" >
                        <div class="row">
                            <ul class="list-inline ul_padding_left">
                                <li id="all" ng-click="loadAll()" class="active">{{trans('websocket_chat.all')}}</li>
                                <li id="matches" ng-click="loadMatched()">{{trans('websocket_chat.matches')}}</li>
                                <li id="online" ng-click="loadOnline()">{{trans('websocket_chat.online')}}</li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            
            
            </div>


          
			<div class="col-md-12 col-xs-12 chat_left_border-right left-main-col_height mobile_users"  ng-if="mobile">
                  
                <div class="row">
	               
                    <div class="col-md-12 col-xs-12 modal_search_div_padding modal_search_div_height border_bottom_search" ng-show='desktop'>
                        <div class="form-group">
                            
                            <i class="fa fa-search position_absolute search_position opacity_7"></i>
                        </div>
                    </div>
                    <!-- All users content-->
                    <div id="all-col" class="col-md-12 col-xs-12 user_main_col_height">
                         <div class="row users_list_cnt">
                            <div  ng-click="loadChatData(user,$index)" data-index="[[$index]]" data-id="user-[[user.user.id]]" ng-class="{active: $index == selected}" class="col-md-12 col-xs-12 user_div_padding left-first-div all_users" ng-repeat="user in chatUsers ">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]<i ng-show="user.online"  class="fa fa-circle position_absolute chat_online_icon"></i></h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-xs-2 user_right_icon_padding">
                                    <i class="fa fa-star-o pull-right favourite" ng-click="markAsFavourite($event,user.user.id)"></i>
                                    <span ng-show="user.user.total_unread_messages_count" style="border-radius: 50%;
    background-color: red;
    color: white;
    padding: 3px 7px 3px 7px;;
    position: relative;
    right: -6px;
    top: 9px;
    box-shadow:  1px 1px 6px 1px rgba(0, 0, 0, 0.52);font-size: 12px;">[[user.user.total_unread_messages_count]]</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Matched users content-->
                    <div id="matches-col" class="col-md-12 col-xs-12 user_main_col_height user_main_col_border_bottom  " style="display:none">
                        <div class="row users_list_cnt">
                            <div class="col-md-12 col-xs-12 col-xs-12 user_div_padding left-first-div" ng-class="{active: $index == selected}" ng-click="loadChatData(user,$index)" ng-repeat="user in matchedUsers">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]</h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-s-2 user_right_icon_padding">
                                    <i class="fa fa-star-o pull-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Online users content-->
                    <div id="online-col" class="col-md-12 col-xs-12 user_main_col_height user_main_col_border_bottom  " style="display:none">
                        <div class="row users_list_cnt">
                            <div class="col-md-12 col-xs-12 col-xs-12 user_div_padding left-first-div" ng-class="{active: $index == selected}" ng-click="loadChatData(user,$index)" ng-repeat="user in onlineSelectedUsers">
                                <div class="col-md-4 col-xs-4 circle-image" style="background:url([[user.user.profile_picture]])">
                                </div>
                                <div class="col-md-7 col-xs-7 chat_text_padding">
                                    <h5 class="sanserif_font remove_margin_bottom position_relative opacity_8">[[user.user.name]]</h5>
                                    <h6 class="chat_inline_margin opacity_4">[[user.user.last_msg]]</h6>
                                </div>
                                <div class="col-md-2 col-s-2 user_right_icon_padding">
                                    <i class="fa fa-star-o pull-right"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                                        <!-- Chat tabs-->
                    <div class="col-md-12 col-xs-12" ng-if="desktop">
                        <div class="row">
                            <ul class="list-inline ul_padding_left">
                                <li id="all" ng-click="loadAll()" class="active">{{trans('websocket_chat.all')}}</li>
                                <li id="matches" ng-click="loadMatched()">{{trans('websocket_chat.matches')}}</li>
                                <li id="online" ng-click="loadOnline()">{{trans('websocket_chat.online')}}</li>
                                
                            </ul>
                        </div>
                    </div>
                </div>
            
            
            </div>

			 

            <!-- User with no chat content--> 
            <div id="first_img_div" class="col-md-10 col-xs-12 col-lg-8">
                <div class="row">
	                 
                    <div class="col-md-12 col-xs-12 chat_user_main_col_padding user_main_col_height user_main_col_border_bottom">
                        <div class="col-md-12 col-xs-12 col_main_dropdown_padding">
                            <div class="dropdown pull-right">
<!-- 	                            <i class="fa fa-video-camera chat_video_audio" data-toggle="modal" data-target="#myModalVideoAudio"></i> -->
								{{Theme::render('chat_window_buttons')}}
                                <button class="btn btn-primary dropdown-toggle btn_circle_radius option_btn_padding pull-right dropdown-btn-color" type="button" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-option-horizontal"></i>
                                </button>
                                <ul class="dropdown-menu">
                                        <li><a href="#/" data-user-id="[[userChatDetail.user.id]]" ng-click="blockUser(userChatDetail.user.id)" class="blockChatUser">{{trans('websocket_chat.block')}}</a></li>
                                        <li><a href="#/" data-target="#myModalReportUser" data-toggle="modal" data-user-id="[[userChatDetail.user.id]]">Report</a></li>
                                        <li><a href="#/" ng-click="deleteConversation(userChatDetail)">{{trans('websocket_chat.delete_conversation')}}</a></li>
                                    </ul>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 text-center css3_div_center img_main_div_padding">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <h4 class="text-center letter_spacing1 opacity_7">[[userChatDetail.user.name]] {{trans('websocket_chat.feels_like_chatting')}}</h4>
                                </div>
                                <div class="col-md-12 css3_div_center img_second_div_padding">
                                    <div class="circle-image circle-image_big position_relative" style="background:url([[userChatDetail.user.profile_picture]])">
                                    </div>
                                    <div class="circle-image circle-image_medium position_absolute circle_img_medium_position circle-image_medium_border circle-image_medium_bg">
                                        <h3 class="text-center remove_margin_bottom text_white">[[userChatDetail.user.total_photos_count]]</h3>
                                        <h6 class="text-center remove_margin_top text_white">{{trans('websocket_chat.photos')}}</h6>
                                    </div>
                                    <div class="circle-image circle-image_medium position_absolute circle_img_medium_score_position circle-image_medium_border circle-image_medium_score_bg">
                                        <h3 class="text-center remove_margin_bottom text_white">[[userChatDetail.user.score| number:2]]</h3>
                                        <h6 class="text-center remove_margin_top text_white text-center font_size_10">{{trans('websocket_chat.overall_score')}}</h6>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <h5 class="text-center letter_spacing1 opacity_7">{{trans('websocket_chat.why_not_sent_message')}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 chat_enter_padding">
                        <div class="col-md-3 col-xs-1 smiley_div_padding">
                            <i class="fa fa-smile-o enter_text_icon_size smiley_margin_right icon_opacity send_emoji"></i>

                            <i class="fa fa-camera icon_opacity send_photo smiley_margin_right " ng-if="desktop" data-target="#uploadPhoto" data-toggle="modal"></i>
                        </div>
                         <div class="col-md-7 col-xs-10">
                            <div class="form-group">
                                <input id="chat_input_first" ng-keydown="typing()" ng-trim="false" ng-model="messageInput" type="text" class="form-control search_input_border input_border_blue" ng-enter="submitMessage()" placeholder="{{trans('websocket_chat.write_message_placeholder')}}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2" ng-if="desktop">
                            <button id="submit_chat_first" ng-click="submitMessage()" type="button" class="btn btn-primary telegram_btn_radius telegram-btn-padding telegram-btn-bg outline_none"><i class="glyphicon glyphicon-send"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- User with chat content--> 
            <div id="third_img_div" class="col-md-10 col-xs-12 col-lg-8" style="display:none">
	            <div class="loaderChatData"></div>
                <div class="row">
	              
                    <div class="col-md-12 chat_user_main_col_padding user_main_col_height user_main_col_border_bottom">
                        <div class="col-md-12 col_main_dropdown_padding col_dropdown_border_bottom" ng-if="desktop">
	                        
	                       
                            <div class="row">
                                <div class="col-md-8 col-xs-5" >
                                    
                                	
                                	<img ng-src="[[userChatDetail.user.profile_picture]]" class="userChatimage"/>
                                    <h5 class="display_inline font_bold opacity_7">[[userChatDetail.user.name]],</h5>
                                    <h5 class="display_inline font_bold opacity_7">[[userChatDetail.user.age]]</h5>
                                    <i class="fa fa-circle chat_online_icon" ng-show="userChatDetail.online"></i>
                                </div>
                                <div class="dropdown pull-right">
<!-- 	                                <i class="fa fa-video-camera chat_video_audio" data-toggle="modal" data-target="#myModalVideoAudio"></i> -->

									{{Theme::render('chat_window_buttons')}}

	                                                                     <button class="btn btn-primary dropdown-toggle btn_circle_radius option_btn_padding pull-right dropdown-btn-color" type="button" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-option-horizontal"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#/" data-user-id="[[userChatDetail.user.id]]" ng-click="blockUser(userChatDetail.user.id)" class="blockChatUser">{{trans('websocket_chat.block')}}</a></li>
                                        <li><a href="#/" data-target="#myModalReportUser" data-toggle="modal" data-user-id="[[userChatDetail.user.id]]">Report</a></li>
                                        <li><a href="#/" ng-click="deleteConversation(userChatDetail)">{{trans('websocket_chat.delete_conversation')}}</a></li>
                                    </ul>
                                </div>
                                <div class="col-md-1 pull-right person-menu_margin" ng-i>
                                    <button type="button" class="btn btn-info btn_circle_radius option_btn_padding dropdown-btn-color" data-toggle="tooltip" title="View profile" ng-click="viewProfile(userChatDetail.user.id)"><i class="fa fa-user"></i></button>
                                </div>
                                
                              
                                
                            </div>
                        </div>
                        
                        
                         <div class="col-md-12 col_main_dropdown_padding_mob col_dropdown_border_bottom" ng-if="mobile">
	                        
	                       
                            <div class="row">
                                <div class="col-md-8 col-xs-7" ng-click="viewProfile(userChatDetail.user.id)">
                                    
                                	
                                	<img ng-src="[[userChatDetail.user.profile_picture]]" class="userChatimage"/>
                                    <h5 class="display_inline font_bold opacity_7">[[userChatDetail.user.name]],</h5>
                                    <h5 class="display_inline font_bold opacity_7">[[userChatDetail.user.age]]</h5>
                                    <i class="fa fa-circle chat_online_icon" ng-show="userChatDetail.online"></i>
                                </div>
                                <div class="dropdown pull-right">
                                    <button class="btn btn-primary dropdown-toggle btn_circle_radius option_btn_padding pull-right dropdown-btn-color" type="button" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-option-horizontal"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">{{trans('websocket_chat.block')}}</a></li>
                                        <li><a href="#/" data-target="#myModalReportUser" data-toggle="modal" data-user-id="[[userChatDetail.user.id]]">Report</a></li>
                                        <li><a href="#/" ng-click="deleteConversation(userChatDetail)">{{trans('websocket_chat.delete_conversation')}}</a></li>
                                    </ul>
                                </div>
                                
								  
                            </div>
                        </div>
                        <div id = "chat-container"  style="position: relative;width: 100%;height: 89%;overflow-y: auto;overflow-x:hidden;top: 3px">
	                        
	                       <div class="ph-float">
					        <a href="" id="load_more" ng-show="chatUsers[selected].user.messages.length" ng-click="loadMoreConversation(userChatDetail)" class='ph-button ph-btn-grey'>{{trans('websocket_chat.load_more')}}</a>
					    </div> 
                        <div class="col-md-12 img_main_div_padding">
                            
                            <div class="row fade2" ng-repeat="message in chatUsers[selected].user.messages" id="post-[[ ::$index ]]" scroll-to-last="post-[[ ::$index ]]">
                                <ul class="chat_ul list-inline" >

									<li class="other" ng-if="message.recipient=='other'" id="msg-[[message.id]]">
								       <div class="chat" >
							                 <div class="avatar" ng-if="desktop"><img ng-src="[[chatUsers[selected].user.profile_picture]]" draggable="false"/></div>
							                 
							                 <div class="avatar mobile_avatar" ng-if="mobile"><img ng-src="[[chatUsers[selected].user.profile_picture]]" draggable="false"/></div>

							                <div class="bubbleOther you msg" ng-hide="message.type==10 || message.type==11 || message.type==12">
							                    <p ng-if="message.type==0" ng-bind-html="message.text | emoji | videoembed"></p>
							                    <p ng-if="message.type==2"><img ng-src="{{{asset('uploads/chat')}}}/[[message.meta]]"/></p>
													<time  am-time-ago="message.created_at| amUtc | amLocal" ></time>
							                </div>
							               
							               {{Theme::render('chat_window_messages')}} 							                
						               </div>
						               
								    </li>
								    
								    
								    <li class="self" ng-if="message.recipient=='self'" id="msg-[[message.id]]">

								       <div class="chat" >
							                 
							                <div class="bubble me msg" ng-hide="message.type==10 || message.type==11 || message.type==12">
								                
								              
							                     <p ng-if="message.type==0"  ng-bind-html="message.text | emoji | videoembed"></p>

												<p ng-if="message.type==2"><img ng-src="{{{asset('uploads/chat')}}}/[[message.meta]]"/></p>
											<time  am-time-ago="message.created_at | amUtc | amLocal" ></time>
								       
							                </div>
							                
							              <div class="deletethismsgself" ng-if="desktop && message.type==0">
								                <i class="fa fa-trash-o " ng-click="deleteThisMessege(message.id)"></i>
							                </div>
							                
							                 <div class="deletethismsgself_image" ng-if="desktop && message.type==2">
								                <i class="fa fa-trash-o " ng-click="deleteThisMessege(message.id)"></i>
							                </div>
							                {{Theme::render('chat_window_messages')}}
						               </div>
						               
						               
						              
											
								    </li>
								    
								    
								 
                            
                                </ul>

                            </div>
                            
                            <img  class="isTyping" id="typing_[[userChatDetail.user.id]]" src="@plugin_asset('WebsocketChatPlugin/images/ellipsis.svg')"/>
                        </div>
                        </div>
                    </div>
                    <!-- Send message --> 
                    <div class="col-md-12 chat_enter_padding">
                        <div class="col-md-3 col-xs-1 smiley_div_padding">
                            <i class="fa fa-smile-o enter_text_icon_size smiley_margin_right icon_opacity send_emoji"></i>

                            <i class="fa fa-camera icon_opacity send_photo smiley_margin_right " ng-if="desktop" data-target="#uploadPhoto" data-toggle="modal"></i>
                        </div>
                        <div class="col-md-7 col-xs-10">
                            <div class="form-group">
                                <input id="chat_input" ng-keydown="typing()" ng-trim="false" ng-model="messageInput" type="text" class="form-control search_input_border input_border_blue" ng-enter="submitMessage()" placeholder="{{trans('websocket_chat.write_message_placeholder')}}">
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-2" ng-if="desktop">
                            <button id="submit_chat" ng-click="submitMessage()" type="button" class="btn btn-primary telegram_btn_radius telegram-btn-padding telegram-btn-bg outline_none"><i class="glyphicon glyphicon-send"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- no users--> 
            <div id="no-users-div" class="col-md-10 col-xs-12 col-lg-8">
                <div class="row">
                    <div class="col-md-12 col-xs-12 chat_user_main_col_padding user_main_col_height user_main_col_border_bottom">                        
                        <div class="col-md-12 col-xs-12 text-center css3_div_center img_main_div_padding">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
<!--                                     <h5 class="text-center letter_spacing1 opacity_7">{{trans('websocket_chat.no_users')}}</h5> -->
<section class="section-empty"> 
	                                    	<i class="section-empty__ico section-empty__ico--chat"></i> 
	                                    	<p class="xx-large">{{trans('websocket_chat.no_users')}}<br>{{trans('websocket_chat.start_playing_encounters')}}</p>
	                                    	 <div class="btn btn--blue"> 
		                                    	 <span class="btn-txt">{{trans('websocket_chat.play_encounters')}}</span> 
		                                    	 <a href="{{{url('encounter/')}}}" class="b-link app" rel="mm"></a>
		                                     </div> 
		                            </section>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            
            
            <!-- no users--> 
            <div id="fourth_div" class="col-md-10 col-xs-12 col-lg-8" >
                <div class="row">
                    <div class="col-md-12 col-xs-12 chat_user_main_col_padding user_main_col_height user_main_col_border_bottom">                        
                        <div class="col-md-12 col-xs-12 text-center css3_div_center img_main_div_padding">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <section class="section-empty"> 
	                                    	<i class="section-empty__ico section-empty__ico--chat"></i> 
	                                    	<p class="xx-large">{{trans('websocket_chat.you_have_not_contacted')}}<br>{{trans('websocket_chat.start_playing_encounters')}}</p>
	                                    	 <div class="btn btn--blue"> 
		                                    	 <span class="btn-txt">{{trans('websocket_chat.play_encounters')}}</span> 
		                                    	 <a href="{{{url('encounter/')}}}" class="b-link app" rel="mm"></a>
		                                     </div> 
		                            </section>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- no users -->

            <button type="button"  class="btn btn-default position_absolute close_modal_position btn_circle_radius outline_none close_btn_modal_bg" data-dismiss="modal"><i class="fa fa-times"></i></button> 
        </div>
    </div>
    
    
    
</div>


<div id="uploadPhoto" class="modal fade in" style="z-index: 99999;">
        <div class="modal-dialog">
            <div class="modal-content">
 
                
                <div class="modal-body">
	                  <div class="container">
      <div class="panel panel-default">
        <div class="panel-heading" style="color: #EFE9E9;
background-color: #ec225b;"><strong>{{trans('websocket_chat.upload_photos')}}</strong></div>
        <div class="panel-body">

       
          <!-- Drop Zone -->
           <div ngf-drop ngf-select ng-model="files" class="drop-box" 
        ngf-drag-over-class="'dragover'" ngf-multiple="true" ngf-allow-dir="true"
        accept="image/*,application/pdf" 
        ngf-pattern="'image/*,application/pdf'">{{trans('websocket_chat.drop_image')}}</div>
          </div>


          <!-- Progress Bar -->
          <div class="progress">
            <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: [[log]]%;">
              <span class="sr-only">[[log]]% {{trans('websocket_chat.complete')}}</span>
            </div>
          </div>
          
<!--           cancel upload -->
<button type="button" class="btn btn-danger " id="cancel_upload" ng-click="cancelUpload()">{{trans('websocket_chat.cancel_upload')}}</button>

        </div>
      </div>
                    
                </div> 
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dalog -->
    </div>



<div id="myModalExceedsChatLimit" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content encounterexceeds_modal">
      <div class="">
        
        <h4 class="report_photo_title">{{{trans('app.exceed_daylimit')}}}</h4>
      </div>
      <div class="modal-body user_block_modal_body">
	     <div class="clock_cnt"> 
		     {{{trans('app.wait_till')}}}..
	     	<span id="clock"></span>
	     </div>
		  <!-- <p id="note" ></p> -->
		 <div style="
    font-size: 16px;
    color: red;
    /* text-align: center; */
    margin-left: 33%;
">-OR-</div>
	      		
        </div>
      <div class="" style="text-align: center">
       
        
        <button type="button" class="btn btn-default encounter_exceeds" onclick="angular.element('#AppController').scope().openPaymentModal('superpower',{'invisible':'0'})" data-dismiss="modal">{{{trans('app.upgrade_premium')}}} {{{trans('app.to_continue_instantly')}}}</button>
      </div>
    </div>

  </div>
</div>


<div id="myModalExceedsChatHourExpired" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content encounterexceeds_modal">
      <div class="">
        
        <h4 class="report_photo_title" style="font-size: 18px;">You have exhausted your chat time limit of <span class="bold_hours">{{{$chat_limit_hours}}} hours </span>with matched members!</h4>
      </div>
      <div class="modal-body user_block_modal_body">
	     
	     
			
        </div>
      <div class="" style="text-align: center">
       
        
        <button type="button" class="btn btn-default encounter_exceeds" onclick="angular.element('#AppController').scope().openPaymentModal('superpower',{'invisible':'0'})" data-dismiss="modal">{{{trans('app.upgrade_premium')}}} {{{trans('app.to_continue_instantly')}}}</button>
      </div>
    </div>

  </div>
</div>






</div>


  
  
  
    
    
    
    
    


@section('plugin-scripts')
@parent
<script src="@plugin_asset('WebsocketChatPlugin/js/chat.js')"></script>

<script src="@plugin_asset('WebsocketChatPlugin/js/socket.io.js')"></script>

<script src="https://angular-file-upload.appspot.com/js/ng-file-upload.js"></script>

<script src="@plugin_asset('WebsocketChatPlugin/js/emoji.min.js') "></script>


<script src="@plugin_asset('WebsocketChatPlugin/js/jemoji.js') "></script>

<!-- load angular-moment -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-moment/1.0.0-beta.3/angular-moment.min.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/angular-media-queries/0.6.1/match-media.min.js"></script>







<script>
	uploadPhoto_chat="{{{url('plugins/websocketchatplugin/upload-image')}}}";
	profile_url_chat="{{{url('profile/')}}}";
	user_block_chat="{{{ url('/user/block') }}}";
	user_already_blocked_chat="{{{ url('/user/blocked_by_auth_user') }}}";
	add_contact_chat_url="{{{ url('plugins/websocketchatplugin/add-to-contacts') }}}";
	deleteContact_chat_url="{{{ url('plugins/websocketchatplugin/delete-contact') }}}";
	delete_msg_chat="{{{url('plugins/websocketchatplugin/delete-message')}}}";
	chat_user="{{{url('plugins/websocketchatplugin/chat-user')}}}";
	auth_user_id_chat={{{$auth_user->id}}};
	map_user_socket="{{{url('plugins/websocketchatplugin/map-user-socket')}}}";
	chat_users= "{{{url('plugins/websocketchatplugin/chat-users')}}}";
	msgs_chat="{{{url('plugins/websocketchatplugin/messages')}}}";
	add_to_contacts="{{{url('plugins/websocketchatplugin/add-to-contacts')}}}";
	mark_read="{{{url('plugins/websocketchatplugin/mark-read')}}}";
	server_port="{{{$server_port}}}";
	base_url ="{{{url('')}}}";
	websocket_domain = "{{$websocket_domain}}";
	
	cannotblock="{{{trans('websocket_chat.cannotblock')}}}";
	recieved_msg="{{{trans('websocket_chat.recieved_msg')}}}";
	
	 

	
	
</script>	

<script>
	
	
	$(document).ready(function(){
		
		
		
		var original_content= $('.mobile_chat_cnt').html();
		
		// Set jEmoji
		$('#chat_input').jemoji({
			btn:    $('.send_emoji'),
		   container:  $('#chat_input').parent().parent().parent(),
		   folder : "@plugin_asset('WebsocketChatPlugin/images/emojis')/",
		   theme: 'df'
		});	
		
		$('#chat_input_first').jemoji({
			btn:    $('.send_emoji'),
		   container:  $('#chat_input_first').parent().parent().parent(),
		   folder : "@plugin_asset('WebsocketChatPlugin/images/emojis')/",
		   theme: 'df'
		});	
		
		 $(".button-fill").hover(function () {
		    $(this).children(".button-inside").addClass('full');
		}, function() {
		  $(this).children(".button-inside").removeClass('full');
		});
		
		
				
		
	})
	
$('.users_list_mob').on('click',function(){
			
			$('.mobile_users').toggle();
			
			
			$('#third_img_div').toggle();
})





function loadChatUsers() {

    var scope = angular.element("#websocket_chat_modal").scope();
    scope.load_chat_data_first = true;
    scope.getChatUsers();
    scope.$apply();
    
    
    $(".notification-message").html('');
    
    removeHashToPreventJump();
}


function removeHashToPreventJump()
{
	window.location.hash = window.location.hash.replace(/#.*$/, '#_');
}

</script>


<script src="@plugin_asset('WebsocketChatPlugin/js/WebsocketChat_angular.js') "></script>



@endsection