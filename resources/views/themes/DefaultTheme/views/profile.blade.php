<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<style type="text/css">
    .loading {
  font-size: 30px;
}

.loading:after {
  overflow: hidden;
  display: inline-block;
  vertical-align: bottom;
  -webkit-animation: ellipsis steps(4,end) 900ms infinite;      
  animation: ellipsis steps(4,end) 900ms infinite;
  content: "\2026"; /* ascii code for the ellipsis character */
  width: 0px;
}

@keyframes ellipsis {
  to {
    width: 1.25em;    
  }
}

@-webkit-keyframes ellipsis {
  to {
    width: 1.25em;    
  }
}
</style>
<div id="matchModalProfile" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="match_mdl_cnt">
            <img src="@theme_asset('images/heart.png')">
            <div class="match_mdl_body">
                <h4 style="color: #FFF;">{{{trans('app.its_a_match')}}}</h4>
                <ul class="list-inline">
                    <!--
                        @if($user != null)
                        
                           <li><img src="{{{ $user->encounter_pic_url() }}}"></li>
                           
                           @endif
                           -->
                    <li><img src="{{{$user->profile_pic_url()}}}"></li>
                    <li><img src="{{{$auth_user->profile_pic_url()}}}"></li>
                </ul>
                <p>{{{trans('app.you_and')}}} {{{ $user->name }}} {{{trans('app.liked_each_other')}}}</p>
                <!-- <button class="match_msg" ng-click="openchat(currentUser.id)" data-dismiss="modal ">Message</button> -->
            </div>
        </div>
    </div>
</div>
<div class="cont-cover">
    <div class="cont-header">
        <div class="online-u">
            <img src="{{{$user->thumbnail_pic_url()}}}" alt="...">
            <!--
                <div class="dot-online">
                	<img src="@theme_asset('images')/online-ico.png" alt="...">
                </div>
                -->
            @if($user->onlineStatus())
            <div class="dot-online">
                <img src="@theme_asset('images')/online-ico.png" alt="...">
            </div>
            @endif
        </div>
        <div class="name-c">
            <input type="hidden" id="otheruser" data-user-isliked = "{{{ $profile->likedMe }}}" />
            <h4 class="username_truncate" data-toggle="tooltip" title="{{{ $user->name }}}">{{{ $user->name }}}, {{{ $user->age() }}}</h4>
            @if($user->is_social_verified())<i class="fa fa-check chk" style="margin-right: 0px"></i> @endif
            <i class="fa fa-ellipsis-h user_block dropdown-toggle profile_drop_menu" data-toggle="dropdown" aria-expanded="true" style="margin-left: 16px;margin-top: 12px;"></i>
           @if($chat_settings_option != 'match_only') <i class="fa fa-comment-o" id="chat_bubble" data-toggle="tooltip" title="Click to chat with this person" ng-click="addToContactsNew({{{ $user->id }}})"></i> @endif
            <ul class="dropdown-menu user_block_dropdown">
                <li class="dropdown-li-custom-styling "><a class="blockUser" data-user-id="{{{$user->id}}}" href="# ">@if($user-> blocked_by_auth_user()) {{{trans_choice('app.unblock',0)}}} @else {{{trans_choice('app.block',0)}}} @endif</a></li>
                <li class="dropdown-li-custom-styling"><a href="#" data-toggle="modal" data-user-id="{{{$user->id}}}" data-target="#myModalReportUser">{{{trans_choice('app.report',1)}}}</a></li>
                {{{Theme::render('profile_user_action_menu')}}}
            </ul>
        </div>
        <div class="name-c-1">
            <!-- <span class="shared">13 Shared Friends</span> -->
            <div class="user-number-likes">{{{$common_interests_count}}}</div>
            <div class="user-common-interests">{{{trans_choice('app.common_interests',1)}}} </div>
        </div>
        @if($profile->like== "-1")
        <div class="right-cross-alpha" style="background-image:url('@theme_asset('images/background_bg.png')');background-size:cover">
            <img ng-mousedown="dislikeyou()" id="close-button" src="@theme_asset('images/close.png')">
            <img   ng-mousedown="likeyou()" id="like-button" src="@theme_asset('images/like.png')">
        </div>
        @endif 
        <img   class="onlydislike" src="@theme_asset('images/close_pressed.png')" style="display:none">
        @if($profile->like=='0')<img class="onlydislike" id="like-button-pressed" src="@theme_asset('images/close_pressed.png')" >@endif
        <img   class="onlylike"  src="@theme_asset('images/like_pressed.png')" style="display:none">
        @if($profile->like=='1')<img class="onlylike" id="like-button-pressed" src="@theme_asset('images/like_pressed.png')" >@endif
    </div>
    <div id="myCarouselUserPhotos" class="carousel slide" data-ride="carousel" data-interval=false>
        <!-- Indicators -->
        <div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($user->photos)-$nude_photos_count}}}</span> </div>
        
         @if(count($user->photos)!=0)
        <div class="report_photo_icon" data-toggle="tooltip" title="{{{trans_choice('admin.report',0)}}} {{{trans_choice('admin.photo',1)}}}" ><img src="data:image/png;base64,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"/></div>
        
        @endif
        <!--
            <ol class="carousel-indicators">
             
              <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
              <li data-target="#myCarousel" data-slide-to="1"></li>
              <li data-target="#myCarousel" data-slide-to="2"></li>
              <li data-target="#myCarousel" data-slide-to="3"></li>
            </ol>
            -->
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
	        @if(count($user->photos)==0)
	        <div class="no_photos_cnt">
	        	<img class="no_photos" height="60" width="80" src="@theme_asset('images/photo-camera.svg')">
	        	<h5 class="white marginfromtop">{{trans('app.add_more_photos_otherprofile')}}</h5>
	        </div>	
	        
	        @endif
	        
            @foreach ($user->photos as $album)
            @if($album->nudity!='1')
            <div class="item" >
                <a class="fancybox" href="{{{ $album->photo_url() }}}" ref="userPhotos" ><img src="{{{ $album->photo_url() }}}" alt="Chania"  id="{{{$album->photo_url()}}}"></a>
                <div class="carousel-caption">
                </div>
            </div>
            @endif
            @endforeach
        </div>
         @if((count($user->photos)-$nude_photos_count)!=0)
		 	<i class="material-icons rotate_image">rotate_right</i>
        
        <!-- Left and right controls -->
        <a class="left carousel-control photorestleft" href="#myCarouselUserPhotos" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"  ></span>
        <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control photorestright" href="#myCarouselUserPhotos"  role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" ></span>
        <span class="sr-only">Next</span>
        </a>
        
        @endif
    </div>
    {{{Theme::render('photo_slider_widget_visited')}}}
    <div class="clearfix"></div>
    <div class="col-md-12 user_info_block">
        <div class="row user_stats">
            @if($profile_visitor_details_show_mode == 'true')
            <div class="col-md-6 score_pop_cnt col-sm-4 col-xs-10">
                {{{$visiting_details->day}}} {{{trans_choice('app.profile',1)}}} {{{trans_choice('app.visitors',1)}}} {{{trans_choice('app.today',1)}}}, {{{$visiting_details->month}}} {{{trans_choice('app.this_month',1)}}} 
                
            
            
            
             @if($hide_popularity != 'true')    
                
                {{{trans_choice('app.his_popularity',1)}}}: 
                @if($user->profile->popularity < 10)
                <span class="very_very_low_p others_popularity" >{{{trans_choice('app.popular_values',0)}}}</span>
                @elseif($user->profile->popularity >= 10 && $user->profile->popularity < 25)
                <span class="very_low_p others_popularity">{{{trans_choice('app.popular_values',1)}}}</span>
                @elseif($user->profile->popularity >= 25 && $user->profile->popularity < 50)
                <span class="low_p others_popularity">{{{trans_choice('app.popular_values',2)}}}</span>
                @elseif($user->profile->popularity >= 50 && $user->profile->popularity < 75)
                <span class="medium_p others_popularity">{{{trans_choice('app.popular_values',3)}}}</span>
                @else
                <span class="high_p others_popularity">{{{trans_choice('app.popular_values',4)}}}</span>
                @endif
              @endif
            </div>
            @endif
            @if($profile_score_show_mode == 'true')
            <div class="col-md-6 score_cnt col-sm-4 col-xs-10">
                <div id="circle"><strong class="score"></strong></div>
                <div class="score_text"><span>{{{$score->likes}}}</span> {{{trans_choice('app.out_of_liked_other_profile',0)}}} <span class="total_likes"></span> {{{trans_choice('app.out_of_liked_other_profile',1)}}}</div>
            </div>
            @endif
            {{{Theme::render('profile-gift')}}}
        </div>
        {{{Theme::render('send-gift')}}}
        <div class="clearfix"></div>
        <div class="row user_profile_details">
            <div class="col-md-6 user_info_style" style="margin-left: 0">
                <h3>{{{trans_choice('app.location',1)}}}</h3>
                <p>{{{ $user->city }}}</p>
            </div>
            <!--
                @if( $user->hereto )
                <div class="col-md-6 user_info_style" style="margin-left: 0">
                	<h3>{{{trans_choice('app.here_to',1)}}}</h3>
                	<p>{{{ $user->hereto }}}</p>
                </div>
                @endif
                -->
        </div>
        <div class="row user_profile_details">
             @if($profile_map_show_mode == 'true')<div id="map_canvas" style="    top: 30px;"></div>@endif
        </div>
        <div class="clearfix"></div>
        <div class="row user_profile_details">
            <div class="col-md-12 ">
                
                @if($profile_interests_show_mode == 'true' && $common_interests_count > 0)
                <div class="row user_interests user_profile_details bg-wh2 bg-wh">
                    <h3>{{{$common_interests_count}}} {{{trans_choice('app.common_interests',1)}}} </h3>
                    <ul id="user_interests">
                            @foreach($common_interests as $common_interest)
                                <li><a href="#"  class="userinteresttext">{{{$common_interest->interest}}}</a></li>
                            @endforeach
                    </ul>
                </div>
                @endif

                @if($fb_mutual_friends_count > 0)
                <div class="row user_interests user_profile_details bg-wh2 bg-wh">
                    <h3>{{{$fb_mutual_friends_count}}} {{{trans_choice('app.common_friends',1)}}} </h3>
                    <ul id="user_interests">
                            @foreach($fb_mutual_friends as $friend)
                                <li><a href="{{{url('profile/'.$friend->id)}}}" class="userinteresttext"><img style="width: 30px;margin-right: 4px;border-radius: 50%;" src="{{{$friend->thumbnail_pic_url()}}}">{{{$friend->name}}}</a></li>
                            @endforeach
                    </ul>
                </div>
                @endif
              
                <!-- <div class="row user_interests user_profile_details bg-wh2 bg-wh" ng-show="total_interests_count" ng-controller="InterestsController" ng-init="getUserInterests()">
                    <h3>[[total_interests_count]] [[interest_title]] </h3>
                    <ul id="user_interests">
                        
                        <li ng-repeat="user_interest in user_interests"><a href="#"  class="userinteresttext">[[user_interest.interest]]</a></li>
                        
                        <p class="no_interest" ng-show="!total_interests_count">{{{trans_choice('app.add_interest',1)}}}</p>
                       
                        <li style="cursor:pointer" ng-show="showLoadMoreBool" ng-click="loadMore()" class="interest-load-more-btn">
		            		<a href="javascript:void(0)" style="cursor:pointer;left: 8px;position: relative;top: -4px;">.....</a>
		            	</li>

                    </ul>
                </div> -->
              


                @if($profile_about_me_show_mode == 'true' && $profile->aboutme)
                <div class="row user_about_me user_profile_details">
                    <h3>{{{trans_choice('app.about_me',1)}}}</h3>
                    <p>{{{$profile->aboutme}}}</p>
                </div>
                @endif
                
                @foreach($user_sections as $section)
                    @if(count($section->fields)>0)
                        <div class="row user_personal_info user_profile_details">
                            <h3>{{{trans("custom_profile.$section->code")}}}</h3>
                                @foreach($section->fields as $field)
                                    @if($field->type == "dropdown")
                                        <p style="color: black;"> {{{trans("custom_profile.$field->code")}}} : </p>
                                        <span style="color: rgb(153, 153, 153);"> {{{trans("custom_profile.$field->value")}}}</span>
                                    @elseif($field->type == "textarea" || $field->type == "text")
                                        <p style="color: black">{{{trans("custom_profile.$field->code")}}} :</p>
                                        <span style="color: rgb(153, 153, 153);">{{{$field->value}}}</span>
                                    @elseif($field->type == 'checkbox')
                                        <p style="color: black">{{{trans("custom_profile.$field->code")}}} :</p>
                                        @foreach($field->value as $option)
                                            <span style="color: rgb(153, 153, 153);">{{{$option}}}</span>
                                        @endforeach

                                    @endif
                                @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<!-- rise in popularity -->
<div id="myModalPhotoRest" class="modal fade" role="dialog">
    <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="">
                <h4 class="report_photo_title" style="border: 0">{{{trans_choice('app.more_photos',1)}}}</h4>
                <h5 class="riseup_text">{{{trans_choice('app.to_see_others',0)}}} {{{$minimum_photo_count}}} {{{trans_choice('app.to_see_others',1)}}}</h5>
            </div>
            <div style="    margin-top: 8%;">
                <img src="@theme_asset('images/gallery-blocker.png')" />
            </div>
            <div class="modal-body">
                
                    <div class="loaderUpload"></div>
                   <a class="btn btn-default custom_modal-popup3 riseup_to_numberone" href="{{{url('profile/'.$auth_user->id)}}}">{{{trans_choice('app.add_photos_you',1)}}}</a>
               
            </div>
        </div>
    </div>
</div>
@if($user->blocked_auth_user())
<div class="contents_blocked">
    <h2>{{{trans_choice('app.content',1)}}} {{{trans_choice('app.block',2)}}}</h2>
</div>
@endif
@endsection
@section('scripts')
@parent
<script>
    $(document).ready(function(){
    	$('.fa.fa-angle-down.d-arrow-1').click(function(){
    		 $(this).next('.hover-cover').slideToggle();
    	});
    	$('.fa.fa-angle-down.d-arrow').click(function(){
    		$('.sign-out ').slideToggle();
    	});
    });
    
    
</script>
<script>
    $(document).ready(function(){
     var val = parseInt('{{{$score->score}}}')/10;
     	$('#circle').circleProgress({
           value: val,
           size: 60,
           fill: {
               gradient: ["#526AD7", "#F82856"]
           }
       }).on('circle-animation-progress', function(event, progress) {
        $(this).find('strong').html(parseInt(100 * val) + '<i>%</i>');
    });		
       
     $('.total_likes').text(parseInt('{{{$score->likes}}}')+parseInt('{{{$score->dislikes}}}')); 
       
     });
</script> 
<script type="text/javascript">
    function initMap() { 
    	
    			var myLatLng = {lat: parseFloat('{{{$user->latitude}}}'), lng:parseFloat('{{{$user->longitude}}}')};
    			var map_canvas = document.getElementById('map_canvas');
    			var map_options = {
    					center: new google.maps.LatLng(parseFloat('{{{$user->latitude}}}'), parseFloat('{{{$user->longitude}}}')),
    					zoom:8,
    					mapTypeId: google.maps.MapTypeId.ROADMAP
    				}
    				 map = new google.maps.Map(map_canvas, map_options);
    				
    				 var marker = new google.maps.Marker({
    				    position: myLatLng,
    				    map: map
    				  });
    			
    			google.maps.event.addListenerOnce(map,'idle',function(){
    			  var font=document.querySelector('link[href$="//fonts.googleapis.com/css?family=Roboto:300,400,500,700"]');
    			  if(font){
    			    font.parentNode.removeChild(font);
    			  }
    
    			  var center = map.getCenter();
    	             google.maps.event.trigger(map, "resize");
    				map.setCenter(center); 
    			});
    			
    
    }
        
</script>
@if($google_map_key == '')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap" async defer></script>
@else
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{{$google_map_key}}}&signed_in=true&libraries=places&callback=initMap" async defer></script>
@endif
<script>
    $(document).ready(function(){
    /*
      $("#close-button").mousedown(function()
      {
         $("#close-button-pressed").show();
         $("#close-button").hide();
         
      });
    */
    /*
     $("#close-button-pressed").mouseup(function()
      {
         $("#close-button-pressed").hide();
         $("#close-button").show();
         
      });
    */
    /*
     $("#like-button").mousedown(function()
      {
         $("#like-button-pressed").show();
         $("#like-button").hide();
         
      });
    */
    /*
     $("#like-button-pressed").mouseup(function()
      {
         $("#like-button-pressed").hide();
         $("#like-button").show();
     });
    */
    });
</script>
<script>
    $('#myCarouselUserPhotos > div > div:nth-child(1)').addClass('active');
    
</script> 
<script>
    $('.photorestright').on('click',function(e){
     
     //check condition
     var is_allowed= '{{{$user_see_all_photos}}}';
     
     
     if(is_allowed)
     {
      $(this).attr("data-slide", "next");
      return;
     }
     else
     {
      //$(this).data('slide').val(false);
      
      $(this).attr("data-slide", "false");
      
      //open photo restriction modal
      
      $('#myModalPhotoRest').modal('show');
      
     }
     
     
    })
    
     $('.photorestleft').on('click',function(e){
     
     //check condition
     var is_allowed= '{{{$user_see_all_photos}}}';			  
     
     if(is_allowed)
     {
      $(this).attr("data-slide", "prev");
      return;
     }
     else
     {
      //$(this).data('slide').val(false);
      
      $(this).attr("data-slide", "false");
      
      $('#myModalPhotoRest').modal('show');
      
     }
     
     
    })
    
</script>  
<script>
    $('.report_photo_icon').on('click',function(){
    	
    	
    	$('#myModalReportPhoto').modal('show');
    	
    	
    	if($(this.nextElementSibling).children("div.active")[0])
    $('.reason').val($(this.nextElementSibling).children("div.active")[0].firstElementChild.href.substring($(this.nextElementSibling).children("div.active")[0].firstElementChild.href.lastIndexOf('/')+1).split('"')[0]);
    	
    })
</script>	
<script>
    $(document).ready(function(){
    	
    	angle=90;
    })
    
    $('.rotate_image').on('click',function(){
    	
    	
    	if($(this.previousElementSibling).children("div.active")[0])
        console.log($(this.previousElementSibling).children("div.active")[0].firstElementChild);
    	
    	
    	$($(this.previousElementSibling).children("div.active")[0].firstElementChild.firstElementChild).css({
         "-webkit-transform": "rotate("+angle+"deg)",
         "-moz-transform": "rotate("+angle+"deg)",
         "transform": "rotate("+angle+"deg)" /* For modern browsers(CSS3)  */
     });
    	
    	angle=angle+90;
    	
    })



// App.controller('InterestsController',function($scope,$rootScope,$http, $timeout, $window){


// 	$scope.total_interests_count = 0;
// 	$scope.user_interests = [];
// 	$scope.last_user_interest_id = 0;
// 	$scope.interest_title = "{{{trans_choice('app.interest',1)}}}";
// 	$scope.get_interest_url = "{{{url('profile/interests/get')}}}";
// 	$scope.current_interests_count = 0;

// 	$scope.csrf_token = "{{{csrf_token()}}}";



// 	$scope.getUserInterests = function(){

// 		$http.post($scope.get_interest_url, {_token:$scope.csrf_token, user_id:"{{{$user->id}}}", last_user_interest_id:$scope.last_user_interest_id})
// 		.then(function(response){

// 			if(response.data.status == "success") {

// 				$scope.total_interests_count = response.data.total_user_interests_count;
// 				$scope.user_interests = $.merge($scope.user_interests, response.data.data);
// 				$scope.current_interests_count += response.data.count;
// 				$scope.setLastUserInterestId();
// 				$scope.renderInterestTitle();
// 				$scope.showLoadMore();

//                 $(".interest-load-more-btn").find("> a").addClass('loading');;
//                 $(".interest-load-more-btn").find("> a").text("");

// 			}


// 		}, function(response){
// 			console.log("interest get error");
//             $(".interest-load-more-btn").find("> a").addClass('loading');;
//             $(".interest-load-more-btn").find("> a").text("");
// 		});


// 	}


// 	$scope.renderInterestTitle = function() {
// 		$scope.interest_title = ($scope.total_interests_count > 1) ? "{{{trans_choice('app.interest',2)}}}" : "{{{trans_choice('app.interest',1)}}}";
// 	}


// 	$scope.setLastUserInterestId = function(){
// 		$scope.last_user_interest_id = ($scope.total_interests_count > 0)? $scope.user_interests[$scope.user_interests.length -1].id  : 0;
// 	}


// 	$scope.loadMore = function(){  
//         $(".interest-load-more-btn").find("> a").addClass('loading');;
//         $(".interest-load-more-btn").find("> a").text("");
// 		$scope.setLastUserInterestId();
// 		$scope.getUserInterests();
// 	}

// 	$scope.showLoadMoreBool = false;
// 	$scope.showLoadMore = function(){
// 		$scope.showLoadMoreBool = ($scope.current_interests_count == $scope.total_interests_count) ? false : true;
// 	}


// });






</script>	

<script>
		
		$('.blockUser').on("click",function(){
			
			var user_to_block= $(this).data('user-id');
			data={
					user_id:user_to_block
					
				};
				
			
			//if user already blocked then unblock user
			$.ajax({
			  type: "POST",
			  url: "{{{ url('/user/blocked_by_auth_user') }}}",
			  data: data,
			  success: function(data){
			        
			        
			        
			        if(data.blocked==false)
			        {
			        	
			        	$.ajax({
						  type: "POST",
						  url: "{{{ url('/user/block') }}}",
						  data: {user_id:user_to_block},
						  success: function(msg){
						        
						        
						        
						        if(msg.status=='error')
						        {
						        	toastr.info("Can't block this user");
						        }
						        else
						        {
						        	toastr.info("User blocked");
						        	
						        	angular.element('#websocket_chat_modal').scope().profile_block(user_to_block);
						        	
						        	
						        	
						        	$('.blockUser').text('Unblock');
						        	
						        							        	
						        }
						  },
						  error: function(XMLHttpRequest, textStatus, errorThrown) {
						        toastr.info("some error");
						  }
						  
						});

			        	
			        }
			        else
			        {
			        	
			        	$.ajax({
							  type: "POST",
							  url: "{{{ url('/user/unblock') }}}",
							  data: {user_id:user_to_block},
							  success: function(msg){
							        
							        var chat_scope= angular.element('#websocket_chat_modal').scope();
							        
							        
							        
							        if(msg.status=='error')
							        {
							        	toastr.info("Can't block this user");
							        }
							        else
							        {
							        	toastr.info("User Unblocked");
							        	
							        	chat_scope.profile_unblock(user_to_block); 
							        	
							        	$('.blockUser').text('Block');
							        								        	
							        }
								},
								 error: function(XMLHttpRequest, textStatus, errorThrown) {
									        toastr.info("some error");
									  }
								});
				
							        	
							        				        	
							        	
							   }
							  },
							  error: function(XMLHttpRequest, textStatus, errorThrown) {
							        toastr.info("some error");
							  }
							});
			
				
			
						
			
			
			
		})
		
		
		
		
	</script>	
	
	
@yield('profile-scripts')
@endsection