<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<div class="col-md-12  pad" ng-controller="EncounterController">
	
	 @if(Session::has('data_incomplete') && session::get('data_incomplete')) 
    				
    		
    <div id="facebookModal" class="modal fade" role="dialog" data-backdrop="static">
        <div class="modal-dialog" >
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title  superpower-invisible-header" id="">{{trans('app.data_incomplete_modal_heading')}}</h4>
                </div>
                <form class="form-horizontal" id="facebookForm" >
                    <fieldset>
                        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="firstname">{{{trans('app.name')}}}</label>  
                            <div class="col-md-4">
                                <input id="name" name="name" type="text" value="{{{$auth_user->name}}}" placeholder="Name" class="form-control input-md" required="">
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="city">{{{trans('app.city')}}}</label>  
                            <input type="hidden" id="lat" name="lat" value="{{{$auth_user->lat}}}"/>
                            <input type="hidden" id="lng" name="lng" value="{{{$auth_user->lng}}}"/>
                            <input type="hidden" id="cityhidden" name="city" value="{{{$auth_user->city}}}"/>
                            <input type="hidden" id="country" name="country" value="{{{$auth_user->country}}}"/>
                            <div class="col-md-6">
                                <input id="city"  autocomplete="on" type="text" placeholder="City" class="form-control input-md" required="">
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="dob">{{{trans('app.dob')}}}</label>  
                            <div class="col-md-6" style="padding: 1% 13% 0% 0%; color:#898989;">
                                <div class="select select-fancy select-fancy-image" style="width: 60px;margin-right: 5px;"> <select class="dobpart" id="dobday" required=""></select></div>
										<div class="select select-fancy select-fancy-image" style="width: 78px;margin-right: 5px;"><select class="dobpart" id="dobmonth" required="" ></select></div>
										<div class="select select-fancy select-fancy-image" style="width: 60px"><select class="dobpart" id="dobyear" required=""></select>	  </div>
										  <input id="dob" name="dob" type="hidden" placeholder="{{{trans('app.dob')}}}" class="form-control input-md" required="">
                            
                            </div>
                        </div>
                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">{{{trans('app.email')}}}</label>  
                            <div class="col-md-6">
                                <input id="email" name="username" type="text" placeholder="{{{trans('app.email')}}}" class="form-control input-md" required="" value="{{{$auth_user->username}}}">
                            </div>
                        </div>
                        <input type="hidden" value="" id="gender_val" name="gender_val"/>
                        @if($gender->on_registration == 'yes')
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="gender">{{{trans('custom_profile.'.$gender->code)}}}</label>
                            <div class="col-md-4">
                                <select  name="{{{ $gender->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$gender->code}}}">
                                    @foreach($gender->field_options as $option)
                                    <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $("select[name='{{{ $gender->code }}}']").on('change', function () {
                            $('#gender_val').val( 
                            $( "select[name='{{{ $gender->code }}}'] option:selected" ).data('value')
                            );
                            
                            });
                        </script>
                        @endif                        
                        <!-- Select Basic -->
                        @foreach($sections as $section)
                        @foreach($section->fields as $field)
                        @if($field->on_registration == 'yes' && $field->type == "dropdown")
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                            <div class="col-md-4">
                                <select  name="{{{ $field->code }}}" class="form-control remove_boxshadow input_height input_max_width input_max_margin" id="{{{$field->code}}}">
                                    @foreach($field->field_options as $option)
                                    <option data-value="{{{$option->code}}}" value="{{{$option->id}}}">{{{trans('custom_profile.'.$option->code)}}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @elseif($field->on_registration == 'yes' && $field->type == 'checkbox')

                        <div class="form-group">
                            <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                            <div class="col-md-4">
                                 <select type="text" id="multiselect_checkbox{{{$field->id}}}" name="{{$field->code}}[]" class="multiselect_checkbox_{{{$field->code}}}" data-nonSelectedText="{{{$field->code}}}" class="form-control multiselect multiselect-icon" multiple="multiple" role="multiselect" value="{{{$field->code}}}"> 
	                          
	                          @foreach($field->field_options as $option)
                                
                                
                                <option value="{{$option->id}}" name="{{$field->code}}[]">{{trans("custom_profile.{$option->code}")}}</option>
                            @endforeach         
             
            </select> 
                            </div>
                        </div>
                        
                        

                        @elseif($field->on_registration == 'yes')
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="hereto">{{{trans('custom_profile.'.$field->code)}}}</label>
                            <div class="col-md-4">
                                <input type="text" class="form-control input-md hereto_fb_modal" id="" name="{{{$field->code}}}" value="" placeholder="{{{$field->name}}}"/>
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @endforeach
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="submitfbdetails"></label>
                            <div class="col-md-4">
                                <input  id="submitfbdetails"  name="submitfbdetails" class="btn btn-primary" value="Continue"/ >
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    
    		
    		
    	
    	@endif 
    
    
    <div id="matchModal" class="modal fade" role="dialog">
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
                        <li><img  ng-src="[[currentUser.profile_pic_url]]"></li>
                        <li><img src="{{{$auth_user->profile_pic_url()}}}"></li>
                    </ul>
                    <p>{{{trans('app.you_and')}}} [[ currentUser.name]] {{{trans('app.liked_each_other')}}}</p>
                    <!-- <button class="match_msg" ng-click="openchat(currentUser.id)" data-dismiss="modal ">Message</button> -->
                    <button class="match_msg" ng-click="viewProfile()" >{{{trans('app.view_profile')}}}</button>
                    <button class="match_playing" ng-click="keepPlaying()">{{{trans('app.keep_playing')}}}</button>
                </div>
            </div>
        </div>
    </div>
    @if($user != null)
    <div class="cont-cover">
	  
                   <i  data-toggle="modal" data-hover="tooltip" title="{{trans('app.edit_filter_tooltip_text')}}" data-target="#filterModal" class="material-icons md-24 material-tune-settings edit_filter hidden-xs">tune</i>
          
        <div class="cont-header">
            <div class="online-u">
                <img ng-src="[[currentUser.profile_pic_url]]" src="{{{ $user->encounter_pic_url() }}}" alt="...">
                <!-- 								<div class="dot-online"> <img src="images/online-ico.png" alt="..."> </div> -->
                @if($user->onlineStatus())
                <div class="dot-online"> <img src="images/online-ico.png" alt="..."> </div>
                @endif
            </div>
            <div class="name-c">
                <h4 style="border: 0px;"><span ng-bind="currentUser.name"></span>, <span ng-bind="currentUser.age"></span></h4>
                <i class="fa fa-check chk" data-toggle="tooltip" title="{{trans('app.social_verified_tooltip')}}"></i> 
                <input type="hidden" id="current_user" data-user-id="{{{ $user->id }}}" data-friends-count="{{{$user->common_friends_count}}}" data-common-interest="{{{$user->common_interest_count}}}" data-user-name="{{{ $user->name }}}" data-user-age="{{{ $user->age() }}}" data-user-isliked = "{{{ $user->isLiked }}}" />
            </div>
            <div class="name-c-1 user_loc_cnt">
                <!--<span class="user_location_icon"><i class="material-icons ">location_on</i></span> -->
                <!--
                    <div class="user-common-interests">{{{ trans("app.common_interests")}}}</div>
                    <h2 ng-if="!commonInterests.length"><span class="no_interest">0</span></h2>
                    -->
                <div class="flex__item flex__item--fixed common-info-wrap_   hidden-sm hidden-xs">
                    <!--
                        --><!--
                        -->
                    <div class="common-info loading" ng-click="viewProfile()">
                        <div class="inline common-info__count js-profile-header-shared-friends-wrap">
                           
                            <span class="common-info__count-cnt js-profile-header-shared-friends-num">[[currentUser.commonfriend_count]]</span>
                        </div>
                        <span class="inline js-profile-header-shared-friends-word">{{trans('app.friends')}}</span>
                        <span class="b-link js-profile-header-toggle"></span>
                    </div>
                    <!--
                        --><!--
                        -->
                    <div class="common-info common-info--hide">
                        <div class="common-info__count">
                            <span class="common-info__count-cnt">[[currentUser.commoninterest_count]]</span>
                        </div>
                        <span>{{{trans('app.common_interests')}}}</span>
                        <span class="b-link js-profile-header-toggle" ng-click="viewProfile()"></span>
                    </div>
                    <!--
                        --><!--
                        -->
                </div>
            </div>
            <!--
                <div class="name-c-1">
                	
                	<span class="user_location_icon">Big harry potter fan</span> -->
            <!--
                <div class="user-common-interests">{{{ trans("app.common_interests")}}}</div>
                <h2 ng-if="!commonInterests.length"><span class="no_interest">0</span></h2>
                
                </div>
                -->
            <div class="right-cross-alpha" style="background-image:url('@theme_asset('images/background_bg.png')');background-size:cover">
                <img id="close-button" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/close.png')" ng-mousedown="dislikeUser()">
                <img id="close-button-pressed" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/close_pressed.png')" style="display:none">
                <img id="like-button" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/like.png')" ng-mousedown="likeUser()" >
                <img id="like-button-pressed" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/like_pressed.png')" style="display:none" >
            </div>
            <div>
                <ul class="tags">
                    <!--
                        <li ng-repeat="interests in commonInterests track by $index"><span class="tag">[[interests.interest]]</span></li>
                        	-->							 
                </ul>
            </div>
        </div>
        <div class="video" style="position: relative;top: -30px">
            <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0"  ng-repeat="photo in currentUser.photos track by $index" ng-class="{'active' : $first}"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox" >
                    <div class="item"   ng-repeat="photo in currentUser.photos track by $index" ng-class="{'active' : $first}" >
                        <img class="home-page-carousel-image"  ng-src="[[ photo]]" >
                    </div>
                </div>
                <div class="report_photo" ng-show="currentUser.photos.length > 0" data-toggle="tooltip" title="{{{trans_choice('admin.report',0)}}} {{{trans_choice('admin.photo',1)}}}" ><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="data:image/png;base64,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"/></div>
                <!-- Left and right controls -->
                <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" ng-show="currentUser.photos.length > 1"></span>
                <span class="sr-only">{{{trans('app.previous')}}}</span>
                </a>
                <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" ng-show="currentUser.photos.length > 1"></span>
                <span class="sr-only">{{{trans('app.next')}}}</span>
                </a>
            </div>
        </div>
        <div class="bg-wh viewProfile">
            <div class="row">
                <div class="col-xs-5 col-md-3">
                    <div class="profile"> <a ng-click="viewProfile()" href="#"><i class="fa fa-user u"></i>{{{ trans("app.view_profile")}}}</a> </div>
                </div>
                
              
                
                
            </div>
        </div>
    </div>
    @else
    
    <div class="cont-cover">
	    <i  data-toggle="modal" data-hover="tooltip" title="Edit filter" data-target="#filterModal" class="material-icons md-24 material-tune-settings edit_filter hidden-xs">tune</i>
    <div class="" style = "color : black;text-align: center">
        <p class="mv30 teardropAnimation dib">
            <span class="tear"></span>
            <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/no_encounters.png')" width="192" height="192">
        </p>
        <div class="mv20 fs16">
            {{{trans('app.ah_shoot')}}} {{{trans('app.out_of_encounters')}}}
        </div>
    </div>
    <div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">{{{trans('app.no_encounters')}}}</a></div>
    
    </div>
    @endif
</div>

<div class="bs-example">
    <div id="filterModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="margin-top: 100px">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p class="modal-title" style="    font-size: 16px;color: white;margin: 0">
                        {{{trans_choice('app.header_modal_heading', 0)}}}
                    </p>
                  
                </div>
                <form >
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4" style="border-right: 1px solid #dddddd;">
                                <div class="drop_heading">
                                    <p>{{{trans('app.gender')}}}</p>
                                </div>
                                <div class="drop_body">
                                    <ul class="list-inline genderlist">
                                        
                                        <div class="checkbox">
                                            @foreach($custom_filter_data->prefered_genders as $key=>$value)
                                            <input id="check{{{$key}}}" class="customchkbox gender" type="checkbox" name="{{{$key}}}"  @if($value==1)checked @endif>
                                            <label class="gendercustom" for="check{{{$key}}}">{{{trans('custom_profile.'.$key)}}}</label>
                                            @endforeach
                                            
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4" style="border-right: 1px solid #dddddd;">
                                <div class="drop_heading">
                                    <p>{{{trans_choice('app.filter_field',1)}}}</p>
                                </div>
                                <div class="drop_body">
                                    <div class="rang_slider">
                                        <p>
                                            <label for="age">{{{trans_choice('app.age',1)}}}:</label>
                                            <input type="text" id="age" name = "age" value = "{{{$custom_filter_data->prefered_ages}}}">
                                        </p>
                                        <div id="slider-age"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="drop_heading">
                                    <p>{{{trans_choice('app.filter_field',2)}}}</p>
                                </div>
                                <div class="drop_body">
                                    <div id="imaginary_container">
                                     
                                    </div>
                                    <div class="rang_slider">
                                        <p>
                                            <label for="km">{{trans('admin.'.$filter_distance_unit)}}:</label>
                                            <input type="text" id="km"  name = "distance" value = "{{{$custom_filter_data->prefered_distance}}}">
                                        </p>
                                        <div id="slider-km"></div>
                                        
                                        @if($filter_non_superpowers_range_enabled)
                                        	@if(!$auth_user->isSuperPowerActivated())
                                        		<span class="alert-warning activate_sp_big_radius">{{{trans('notification.activate_sp_big_radius')}}}</span>								@endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    <div class="modal-footer">
                        <!-- modal footer -->
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{{trans_choice('app.cancel',1)}}}</button>
                        <button type="button" class="btn btn-primary savefiltersettings" >{{{trans_choice('app.save',1)}}}</button>
                    </div>
                </form>
            </div>
            <!--                      <div class="disabled_content"><h2>Search filter disabled for demo purpose</h2></div> -->
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
     $('.savefiltersettings').on('click',function(){
               
                
                //gender
                var gender=[];
                
                var customfields={};
                $('.customchkbox').each(function() {
                    
                    
                    if( $(this).is(":checked"))
                                gender.push($(this).attr('name'));
                    
                });
                
                
                customfields['prefer_gender']=gender.join(',');
                
                //age
                customfields['prefer_age']=$('#age').val().replace(/\s/g, "");
                
                
                //distance
                
                customfields['prefer_distance']=$('#km').val();
                
                
                //for people neraby
                
                 customfields['for_encounter']=true;
                            
                
                $.ajax({
                  type: "POST",
                  url: filter_url,
                  data: customfields,
                  success: function(msg){
                       
                        
                        
                        if(msg.status=='error')
                        {
                            toastr.error(failed_save);
                        }
                        else
                        {
	                        
	                        
                            toastr.success(success_save);
                            
                            
                            window.location.href = encounter_url;
                            
                            
                            $('#filterModal').modal('hide');
                            
                            
                                                    
                            
                        }
                  },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.info(api_error);
                  }
                });
            })

</script>
<script>
    $('#gender_val').val( $( "select[name='gender'] option:selected" ).data('value'));
    $("select[name='gender']").on('change',function(){
    
    $('#gender_val').val( $( "select[name='gender'] option:selected" ).data('value'));
    
    }); 
    
</script>
<script type="template" id="no_encounter_template">
    <div class="" style = "color : black;text-align: center">
    					<p class="mv30 teardropAnimation dib">
    						<span class="tear"></span>
    						<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/no_encounters.png')" width="192" height="192">
    					</p>
    
    				<div class="mv20 fs16">
    				Ah, Shoot! You are out of encounters.
    					</div>
    		
    			</div>
    			<div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">Try "People nearby" and change search filter!</a></div>
    
</script>
 @if(Session::has('data_incomplete') && session::get('data_incomplete')) 
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
@endif
<script>
    // console.log($encounter_list);
    
    
    String.prototype.replaceAll = function(search, replacement) {
       var target = this;
       return target.replace(new RegExp(search, 'g'), replacement);
    };
    
    @if(Session::has('data_incomplete') && session::get('data_incomplete')) 
    	
    			
    			//open facebook intermediate popup to enter user specific data
    			
    			$('#facebookModal').modal('show'); 
    			
    			 $("#submitfbdetails").on("click",function(e){
    	
    	
			    	 $('.loader').fadeIn();
			    
			    	$.ajax({
			           	type: 'post',
			           	url: '{{{url('/save_left_fields')}}}',
			           	data: $('#facebookForm').serialize(),
			           	success: function (response) {
			            
				            $('.loader').fadeOut();
				    		if(response.errors) { 
				    			//toastr.error("Please try again!");
				    			
				    			$.each(response.errors,function(key,value){
					    			
					    			toastr.error(value);
					    			
				    			})
				    			
				    		} else {
				                toastr.success("Registeration done successfully!");
				                $('#facebookModal').modal('hide');
				    			
				            }   
			           }
			    
			        });
			        
			     })
			     
			     
			     
			 
			$(document).ready(function(){
			  $.dobPicker({
			    daySelector: '#dobday', /* Required */
			    monthSelector: '#dobmonth', /* Required */
			    yearSelector: '#dobyear', /* Required */
			    dayDefault: '{{{trans('LandingPagesPlugin.day')}}}', /* Optional */
			    monthDefault: '{{{trans('LandingPagesPlugin.month')}}}', /* Optional */
			    yearDefault: '{{{trans('LandingPagesPlugin.year')}}}', /* Optional */
			    minimumAge: 8, /* Optional */
			    maximumAge: 100 /* Optional */
			  });
			});
	
	
		
			$('.dobpart').on('change',function(){
				
				
				$('#dob').val($('#dobday').val()+'/'+$('#dobmonth').val()+'/'+$('#dobyear').val());
				
			})
			
				
	
    			
    		
    	@endif 
    
    
    
    
    var EncounterController = App.controller("EncounterController", ["$scope", "$rootScope","$http", function($scope, $rootScope,$http){
    
    	
    
    		
    	$scope.currentUser = {};
    	$scope.currentUser.id = $("#current_user").data("user-id");
    	$scope.currentUser.name = $("#current_user").data("user-name");
    	$scope.currentUser.age = $("#current_user").data("user-age");
    	$scope.currentUser.islikedme = $("#current_user").data("user-isliked");
    	
    	$scope.currentUser.commoninterest_count = $("#current_user").data("common-interest");
    	
    	$scope.currentUser.commonfriend_count = $("#current_user").data("friends-count");
    	
    	
    	
    
    /*
    
    	
    	$scope.currentUser = jQuery.extend(true, {}, demo_encounter_list.encounters_list[0].user);
    	
    	var age = function(){
    		
    		var ageDifMs = Date.now() - new Date($scope.currentUser.dob).getTime();
       var ageDate = new Date(ageDifMs); // miliseconds from epoch
       return Math.abs(ageDate.getUTCFullYear() - 1970);
    	}
    	
    	$scope.currentUser.age = age;
    	
    	$scope.currentUser.islikedme = demo_encounter_list.encounters_list[0].islikedme;
    	
    	//$scope.encounter_list = jQuery.extend(true, {}, demo_encounter_list.encounters_list);
    	$scope.currentUser.photos = [];
    	
    	console.log(demo_encounter_list.encounters_list[0].user.photos.count);
    	console.log(demo_encounter_list.encounters_list[0].user.photos);
    	for(var i = 0; i < demo_encounter_list.encounters_list[0].user.photos.count; i++){
    		
    		//console.log(demo_encounter_list.encounters_list[0].user.photos.items[i].url);
    		var url = demo_encounter_list.encounters_list[0].user.photos.items[i].url;
    		//url = url.replaceAll("//", "/");
    		$scope.currentUser.photos.push(url);
    		
    	} 
    	
    	console.log($scope.currentUser); */
    
    	$scope.currentUser.photos = [];
    	
    
    	
    	@if($user)
    	
    			 @for($i = 0; $i < count($user->photos); $i++)
       	
    				 $scope.currentUser.photos.push("{{{$user->photos[$i]->photo_url()}}}");
    
    	 		@endfor
    		
    	 @endif
    	 
    	 
    	 
/*
    	 var res=$http.post("{{{ url('/user/get/common_interests') }}}",{id:$scope.currentUser.id});
    
    	res.success(function(data, status, headers, config) {
    		
    		if(data.common_interests)
    			$scope.commonInterests= data.common_interests.interests;
    		
    	});	
*/
    	 
    	 
    
    
    	$scope.likeUser = function(){
    
    		
    	if($scope.encounters_left > 0 ){
    	
    			$.post("{{{ url('/liked') }}}/"+ $scope.currentUser.id+"/1", {  _token: App.csrf_token }, function(data){
    
    			});
    			 
    			
    			
    			$scope.encounters_left=$scope.encounters_left-1;
    
    			if($scope.currentUser.islikedme){
    
    				$('#matchModal').modal("show");
    
    			}
    			else{ 	
    					
    					$scope.encounter_list.shift();
    					 console.log("Copy ->", jQuery.extend(true, {},$scope.encounter_list));
    					console.log($scope.encounter_list);
    					$('.cont-cover').fadeOut();
    					//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
    					
    					if($scope.encounter_list[0]){ 
    						$scope.currentUser = $scope.encounter_list[0].user;
    						
    						var age = function(){
    		
    							var ageDifMs = Date.now() - new Date($scope.currentUser.dob).getTime();
    				    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    				    return Math.abs(ageDate.getUTCFullYear() - 1970);
    						}
    						$scope.currentUser.age = age();
    						$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');  
    						
    						$scope.currentUser.commoninterest_count = $scope.encounter_list[0].user.common_interest_count;
    						
    						$scope.currentUser.commonfriend_count = $scope.encounter_list[0].user.common_friends_count;
    						
    						
    						$scope.currentUser.profile_pic_url = $scope.encounter_list[0].user.profile_pic_url;
    						
/*
    						var user_photos = _.object(_.pluck($scope.encounter_list[0].photos.items, 'url'), _.pluck($scope.encounter_list[0].photos.items, 'nudity')); 
    						
    						
    						$scope.currentUser.photos=_.map(user_photos, function(value, key){
  return { 'url' : key, 'nude' : value };
}); 
*/
    						 
    						$scope.currentUser.islikedme = $scope.encounter_list[0].islikedme;
    						
    						$rootScope.$emit('next_user', $scope.encounter_list);
    						$('.cont-cover').fadeIn();
    						//$(".cont-cover").show( "slide", { direction: "right"  }, 100 );
    					}
    					else{
    						
    						window.location.reload();
    						
    						
    						
    					}				
    				
    			}
    	}	
    	else{
    			
    			toastr.info('{{trans('app.encounter_limit_message_title')}}');
    			
    			$('#myModalExceedsEncounters').modal('show');
    		}
    
    
    			
    		
    
    	}
    	
    	
    	
    	$scope.viewProfile = function(){
    		//console.log("Test", user_id);
    		window.location.href = "{{{ url('/profile/') }}}/"+$scope.currentUser.id;
    	}
    
    
    	$scope.keepPlaying = function(){
    
    		
    		
    		if($scope.encounters_left > 0 ){
    			
    			$('#matchModal').modal("hide");
    
    			$scope.encounter_list.shift();
    
    				if($scope.encounter_list[0]){ 
    					$scope.currentUser = $scope.encounter_list[0].user;
    					var age = function(){
    		
    							var ageDifMs = Date.now() - new Date($scope.currentUser.dob).getTime();
    				    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    				    return Math.abs(ageDate.getUTCFullYear() - 1970);
    						}
    					$scope.currentUser.age = age();
    					
    					
    					$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');
    					
    					
    					$scope.currentUser.commoninterest_count = $scope.encounter_list[0].user.common_interest_count;
    						
    						$scope.currentUser.commonfriend_count = $scope.encounter_list[0].user.common_friends_count;
    						
    						$scope.currentUser.profile_pic_url = $scope.encounter_list[0].user.profile_pic_url;
    					
/*
    					var user_photos = _.object(_.pluck($scope.encounter_list[0].photos.items, 'url'), _.pluck($scope.encounter_list[0].photos.items, 'nudity')); 
    						
    						
    						$scope.currentUser.photos=_.map(user_photos, function(value, key){
  return { 'url' : key, 'nude' : value };
}); 
*/
    					
    					$scope.currentUser.islikedme = $scope.encounter_list[0].islikedme;
    					
    					
    					$rootScope.$broadcast('next_user', $scope.encounter_list);
    						$('.cont-cover').fadeIn();
    						//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
    				}
    				else{
    						
    						window.location.reload();
    						
    					}
    					
    		}
    		else
    		{
    			toastr.info('{{trans('app.encounter_limit_message_title')}}');
    			$('#myModalExceedsEncounters').modal('show');
    		}
    	}
    
    
    	var getEncounterList = function(){ 
    			
    	var promise=$.post("{{{ url('/doencounter') }}}", { id: $scope.currentUser.id, _token: App.csrf_token }).promise();
    	
    	promise.done(callBack);
    	
    	  function callBack(data){
    
    
    				if(data.status == "no encounter"){
    						
    				}
    				else {
    				
    				$scope.encounter_list = data.encounters_list;
    				
    				
    				
    				//encounters limit
    				$scope.encounters_left=data.encounters_left;
    				
    				$rootScope.$emit('encounter_list_updated', data);
    			}
    
    
    	} 
    		/*
    		$scope.encounter_list = demo_encounter_list.encounters_list;
    				
    				//encounters limit
    				$scope.encounters_left=demo_encounter_list.encounters_left;
    				
    				$rootScope.$broadcast('encounter_list_updated', demo_encounter_list); */
    		
    		
    		
    	}
    
    
    	getEncounterList();
    
    
    
    	$scope.dislikeUser = function(){
    
    		if($scope.encounters_left > 0 ){
    		
    			$scope.encounters_left=$scope.encounters_left-1;
    			
    			$.post("{{{ url('/liked') }}}/"+ $scope.currentUser.id+"/0", {  _token: App.csrf_token }, function(data){
    
    
    				$scope.encounters_left=$scope.encounters_left-1;
    
    			}); 
    							
    			$scope.encounter_list.shift();
    			
    			$('.cont-cover').fadeOut();
    			//$(".cont-cover").hide( "slide", { direction: "left"  }, 100 );
    			
    			if($scope.encounter_list[0]){ 
    				$scope.currentUser = $scope.encounter_list[0].user;
    				var age = function(){
    		
    							var ageDifMs = Date.now() - new Date($scope.currentUser.dob).getTime();
    				    var ageDate = new Date(ageDifMs); // miliseconds from epoch
    				    return Math.abs(ageDate.getUTCFullYear() - 1970);
    						}
    				$scope.currentUser.age = age();
    				
    				
    				$scope.currentUser.photos = _.pluck($scope.encounter_list[0].photos.items, 'url');
    				
    				
    				$scope.currentUser.commoninterest_count = $scope.encounter_list[0].user.common_interest_count;
    						
    						$scope.currentUser.commonfriend_count = $scope.encounter_list[0].user.common_friends_count;
    						
    						$scope.currentUser.profile_pic_url = $scope.encounter_list[0].user.profile_pic_url;
    				
    				//var user_photos = _.object(_.pluck($scope.encounter_list[0].photos.items, 'url'), _.pluck($scope.encounter_list[0].photos.items, 'nudity')); 
    						
    						
/*
    						$scope.currentUser.photos=_.map(user_photos, function(value, key){
  return { 'url' : key, 'nude' : value };
}); 
*/
    				
    				$rootScope.$emit('next_user', $scope.encounter_list);
    				$('.cont-cover').fadeIn();
    				//$(".cont-cover").show( "slide", { direction: "right"  }, 100 );
    			}
    			else{
    				
    				window.location.reload();
    				
    			}
    		}
    		else
    		{
    			toastr.info('{{trans('app.encounter_limit_message_title')}}');
    			$('#myModalExceedsEncounters').modal('show');
    		}
    
    			
    		
    
    	}
    
    
    }]);
    
    
</script>
<script type="text/javascript">
    function initMap() { 
 
    
    
            google.maps.event.addDomListener(window, 'load', function () {
                var places = new google.maps.places.Autocomplete(document.getElementById('city'));
                google.maps.event.addListener(places, 'place_changed', function () {
                    var place = places.getPlace();
    
                    var address = place.formatted_address;
                    var latitude = place.geometry.location.lat();
                    var longitude = place.geometry.location.lng();
    
    
                 for (var i=0; i<place.address_components.length; i++) {
                for (var b=0;b<place.address_components[i].types.length;b++) {
    
    
                     if (place.address_components[i].types[b] == "country") {
                        //this is the object you are looking for
                        var country= place.address_components[i];
    
                       
                    }
                    if (place.address_components[i].types[b] == "locality") {
                        //this is the object you are looking for
                        var city= place.address_components[i].long_name;
    
                       
                    }
                    
                }
            }
            //city data
          
    
    
    
    
    
    
    
                    var country = country.long_name;
                    
                    document.getElementById('lat').value = latitude;
                    document.getElementById('lng').value = longitude;
                    document.getElementById('country').value = country;
                    document.getElementById('cityhidden').value = city;
                    
                    $('.enter_loc').fadeIn('slow');
                    // var mesg = "Address: " + address;
                    // mesg += "\nLatitude: " + latitude;
                    // mesg += "\nLongitude: " + longitude;
                    
                });
            });
    
    }
        
</script>
@if($google_map_key == '')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?&signed_in=true&libraries=places&callback=initMap" async defer></script>
@else
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{{$google_map_key}}}&signed_in=true&libraries=places&callback=initMap" async defer></script>
@endif
<script src="@theme_asset('js/dobPicker.min.js')"></script>

<script type="text/javascript">
    $(document).ready(function() {
	    
	     @foreach($sections as $section)
                        @foreach($section->fields as $field)
	    
	    
	    
	    	@if($field->type == 'checkbox')
	    		 if($('#multiselect_checkbox{{$field->id}}').length)
	    {
	        $('#multiselect_checkbox{{$field->id}}').multiselect({
		        nonSelectedText: $('#multiselect_checkbox{{$field->id}}').attr('data-nonselectedtext'),
		        onChange:function(element, checked)
				        {
					         var selectedItems = $('#multiselect_checkbox{{$field->id}} option:selected');
					         
					         selected = [];
					        
					        $(selectedItems).each(function(index, selectedItem){
					            selected.push($(this).text());
					        });
					        
					        
					        if(selectedItems.length==0)
					        {
						        $("#custom_field_{{$field->id}}").text('');
					        }
					        
					       
				        }
	        });
        }
	    	
	    	@endif
	    
	      @endforeach
	      
	     @endforeach 	
	    
       
    });
</script>

<script>
		distance_unit='{{{$filter_distance_unit}}}';
	distance = '{{{$filter_distance}}}';
	distance_range_min ='{{{$filter_range_min}}}';
	distance_range_max= '{{{$filter_range_max}}}';
	non_superpower_range_enabled='{{{$filter_non_superpowers_range_enabled}}}';
	unit_name="{{trans('admin.'.$filter_distance_unit)}}";
	superpower_user='{{{$auth_user->isSuperPowerActivated()}}}';

</script>	
<script>
	$(document).ready(function(){
    
             //age
        var age= $('#age')[0].defaultValue;
    
        var value= age.split('-');
    
        $( "#age" ).val(value[ 0 ] + " - " + value[ 1 ]);
    
        $( "#slider-age" ).slider({
          range: true,
          min: 0,
          max: 80,
          values: [ value[0], value[1] ]
        });
    
        
    
      //km
        var km= $('#km')[0].defaultValue;
        
         var max_range;
        
        if(non_superpower_range_enabled)
        {
	        if(superpower_user)
	        {
		        max_range = parseInt(distance);
	        }
	        else{
		        
		        max_range = parseInt(distance_range_max);
		        
	        }
        }
        else
        {
	        max_range = parseInt(distance);
	        
        }

        
          $( "#km" ).val(km);
        
           $( "#slider-km" ).slider({
         range: "min",
         value: km,
         min: parseInt(distance_range_min),
         max: max_range
          });
           //people-nearby
          /* var age12= $('#age12')[0].defaultValue;*/
          /*$( "#age12" ).val(age12);*/
        
           $( "#slider-age1" ).slider({
         range: "min",
         value: km,
         min: parseInt(distance_range_min),
         max: max_range
          });
           //people-nearby
    
    
          
    
    });       
      
	
</script>
@endsection
@yield('home-scripts')