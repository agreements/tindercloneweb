<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<link rel="stylesheet"   href="@theme_asset('css/cropper.css')" type="text/css" /> 
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
<div class="cont-cover">
    <div class="cont-header">
        <div class="online-u">
            <img class="imageUpload profile_picture" data-toggle="tooltip" title="Upload profile photo" src="{{{$logUser->thumbnail_pic_url()}}}" alt="...">
            <i class="fa fa-upload profilePicUploadIcon"></i>
            <div class="loaderUploadProfilePic"></div>
            @if($logUser->onlineStatus())
            <div class="dot-online">
                <img src="@theme_asset('images')/online-ico.png" alt="...">
            </div>
            @endif
        </div>
        <div class="name-c">
            <h4 class="user_name_cnt username_truncate" data-toggle="tooltip" title="{{{ $logUser->name }}}"><span id="user_name">{{{$logUser->name}}}</span>, <span id="user_age">{{{$logUser->age()}}}</span></h4>
            @if($logUser->is_social_verified())<i class="fa fa-check chk"></i> @endif
            <i class="fa fa-pencil" id="update_name"></i>
        </div>
        <div class="name-c-1"> <span class="shared">{{{trans_choice('app.you_have_matches',1)}}} 4 {{{trans_choice('app.matches',1)}}}</div>
        <div class="buttons-1">
            <a href="#photoModal"data-toggle="modal" type="button" class="btn btn-add-vid">
            {{{trans_choice('app.add_photos_others',1)}}}
            </a>
        </div>
        <div class="col-md-12 edit_profile_form_user_basic" >
            <form  class="form-horizontal hidden_form " id="updt_name_by_id" role="form">
                <!-- 						  	{{ csrf_field() }} -->
                <div class="form-group edit_profile_form_group" >
                    <div class="row">
                        <div class="col-xs-3">
                            <label class="control-label" for="email">{{{trans_choice('app.name',1)}}}:</label>
                            <input type="text" class="form-control" id="name" name = "name" value="{{{$logUser->name}}}" placeholder="Enter name">
                        </div>
                        <div class="col-xs-6">
                            <label class="control-label">{{{trans_choice('app.birthday',1)}}}</label>
                            <!-- 									    <input type="text" class="form-control" id="dob" name = "dob" value="{{{$logUser->dob}}}" placeholder="Enter date of birth"> -->
                            <div class="input-group date" id="datePicker">
                                <input type="text" class="span2 form-control"  id="dob" value="" name="dob" data-date-format="dd-mm-yyyy" placeholder="Enter date of birth" >
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-3">
                            <label class="control-label">{{{trans_choice('app.gender',1)}}}</label>
                            <select class="form-control" name="gender" id="gender">
                            @foreach($gender->field_options as $option)
                            <option value="{{{$option->code}}}" @if($logUser->gender == $option->code) selected @endif> {{{trans('custom_profile.'.$option->code)}}}</option>
                            @endforeach            
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-10">
                        <button type="button" class="btn btn-default"  id="updatebasicinfo" name = "updatename">{{{trans_choice('app.save',1)}}}</button>
                        <button type="button" class="btn btn-default cancel">{{{trans_choice('app.cancel',1)}}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <input type="hidden" class="photo_tobedeleted_url"/>
    <div id="myCarouselMyPhotos" class="carousel slide" data-ride="carousel" data-interval=false>
        <!-- Indicators -->
        <div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($logUser->photos)}}}</span> </div>
        @if(count($logUser->photos)!=0) <i class="fa fa-trash-o remove_profile_pic"></i> @endif
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
	        @if(count($logUser->photos)==0) 
	        <div class="no_photos_cnt">
	        	<img class="no_photos" height="60" width="80" src="@theme_asset('images/photo-camera.svg')">
	        	<h5 class="white marginfromtop">{{trans('app.add_more_photos_myprofile')}}</h5>
	        </div>	
	        @endif
            @foreach ($logUser->photos as $album)
            <div class="item" >
                <a class="fancybox"  href="{{{ $album->photo_url() }}}" ref="userPhotos" ><img data-photoChecked="{{{$album->is_checked}}}" data-photo="{{{$album->nudity}}}" src="{{{ $album->photo_url() }}}" @if($album->nudity) onclick="imageCensor(this,event)" @endif class="user_album_photo @if($album->nudity)image_nude @endif" alt="Chania" id="{{{$album->photo_url()}}}" data-id="{{{$album -> id}}}"></a>
                <div class="carousel-caption">
                </div>
                <img class="lockiconmain" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIzMnB4IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAzMiAzMiIgd2lkdGg9IjMycHgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48dGl0bGUvPjxkZXNjLz48ZGVmcy8+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIiBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSI+PGcgZmlsbD0iIzE1N0VGQiIgaWQ9Imljb24tMTE0LWxvY2siPjxwYXRoIGQ9Ik0xNiwyMS45MTQ2NDcyIEwxNiwyNC41MDg5OTQ4IEMxNiwyNC43ODAxNjk1IDE2LjIzMTkzMzYsMjUgMTYuNSwyNSBDMTYuNzc2MTQyNCwyNSAxNywyNC43NzIxMTk1IDE3LDI0LjUwODk5NDggTDE3LDIxLjkxNDY0NzIgQzE3LjU4MjU5NjIsMjEuNzA4NzI5IDE4LDIxLjE1MzEwOTUgMTgsMjAuNSBDMTgsMTkuNjcxNTcyOCAxNy4zMjg0MjcyLDE5IDE2LjUsMTkgQzE1LjY3MTU3MjgsMTkgMTUsMTkuNjcxNTcyOCAxNSwyMC41IEMxNSwyMS4xNTMxMDk1IDE1LjQxNzQwMzgsMjEuNzA4NzI5IDE2LDIxLjkxNDY0NzIgTDE2LDIxLjkxNDY0NzIgWiBNOSwxNC4wMDAwMTI1IEw5LDEwLjQ5OTIzNSBDOSw2LjM1NjcwNDg1IDEyLjM1Nzg2NDQsMyAxNi41LDMgQzIwLjYzMzcwNzIsMyAyNCw2LjM1NzUyMTg4IDI0LDEwLjQ5OTIzNSBMMjQsMTQuMDAwMDEyNSBDMjUuNjU5MTQ3MSwxNC4wMDQ3NDg4IDI3LDE1LjM1MDMxNzQgMjcsMTcuMDA5NDc3NiBMMjcsMjYuOTkwNTIyNCBDMjcsMjguNjYzMzY4OSAyNS42NTI5MTk3LDMwIDIzLjk5MTIxMiwzMCBMOS4wMDg3ODc5OSwzMCBDNy4zNDU1OTAxOSwzMCA2LDI4LjY1MjYxMSA2LDI2Ljk5MDUyMjQgTDYsMTcuMDA5NDc3NiBDNiwxNS4zMzk1ODEgNy4zNDIzMzM0OSwxNC4wMDQ3MTUyIDksMTQuMDAwMDEyNSBMOSwxNC4wMDAwMTI1IEw5LDE0LjAwMDAxMjUgWiBNMTIsMTQgTDEyLDEwLjUwMDg1MzcgQzEyLDguMDA5MjQ3OCAxNC4wMTQ3MTg2LDYgMTYuNSw2IEMxOC45ODAyMjQzLDYgMjEsOC4wMTUxMDA4MiAyMSwxMC41MDA4NTM3IEwyMSwxNCBMMTIsMTQgTDEyLDE0IEwxMiwxNCBaIiBpZD0ibG9jayIvPjwvZz48L2c+PC9zdmc+" style="position: absolute;display: none;"/>
            </div>
            @endforeach
        </div>
        @if(count($logUser->photos)!=0) 
        <i class="material-icons make_profilepic dropdown-toggle" aria-expanded="true" data-toggle="dropdown" >settings</i>
        
        <ul class="dropdown-menu user_make_profilepic_dropdown">
            <li class="dropdown-li-custom-styling"><a href="#" class="make_it_as_profile_pic"><i class="fa fa-user custom_user_icon"></i>{{{trans_choice('app.make_profile_pic',1)}}}</a></li>
            {{{Theme::render('icon-pvt-photos')}}}                
        </ul>
        <i class="material-icons rotate_image">rotate_right</i>	
        <!-- Left and right controls -->
        <a class="left carousel-control " href="#myCarouselMyPhotos" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" ></span>
        <span class="sr-only">{{{trans_choice('app.previous',1)}}}</span>
        </a>
        <a class="right carousel-control " href="#myCarouselMyPhotos"  role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" ></span>
        <span class="sr-only">{{{trans_choice('app.next',1)}}}</span>
        </a>
        @endif
    </div>
    {{{Theme::render('photo_slider_widget_loguser')}}}
    <div class="bg-wh">
        <div class="row user_stats">
            @if($profile_visitor_details_show_mode == 'true')
            <div class="col-md-6 score_pop_cnt col-sm-4 col-xs-10">
                <strong data-toggle="tooltip" title="{{{$visiting_details->day}}}">{{{$visiting_details->day}}}</strong> {{{trans_choice('app.profile',1)}}} {{{trans_choice('app.visitors',1)}}} {{{trans_choice('app.today',1)}}}, <strong data-toggle="tooltip" title="{{{$visiting_details->month}}}">{{{$visiting_details->month}}}</strong>
                
                 {{{trans_choice('app.this_month',1)}}} 
                 
            
             
             
             @if($hide_popularity != 'true')
                 
                 {{{trans_choice('app.your_popularity',1)}}}: 
                @if($logUser->profile->popularity < 10)
                <span class="very_very_low_p others_popularity" >{{{trans_choice('app.popular_values',0)}}}</span>
                @elseif($logUser->profile->popularity >= 10 && $logUser->profile->popularity < 25)
                <span class="very_low_p others_popularity">{{{trans_choice('app.popular_values',1)}}}</span>
                @elseif($logUser->profile->popularity >= 25 && $logUser->profile->popularity < 50)
                <span class="low_p others_popularity">{{{trans_choice('app.popular_values',2)}}}</span>
                @elseif($logUser->profile->popularity >= 50 && $logUser->profile->popularity < 75)
                <span class="medium_p others_popularity">{{{trans_choice('app.popular_values',3)}}}</span>
                @else
                <span class="high_p others_popularity">{{{trans_choice('app.popular_values',4)}}}</span>
                @endif
             @endif
            </div>
           @endif  
            @if($profile_score_show_mode == 'true')
            <div class="col-md-6 score_cnt   col-sm-4 col-xs-10">
                <div id="circle"><strong class="score"></strong></div>
                <div class="score_text"><span data-toggle="tooltip" title="{{{$score->likes}}}">{{{$score->likes}}}</span> {{{trans_choice('app.out_of_liked_your_profile',0)}}} <span class="total_likes"></span> {{{trans_choice('app.out_of_liked_your_profile',1)}}}</div>
            </div>
            @endif
            {{{Theme::render('profile_widget_loguser')}}}
        </div>
        {{{Theme::render('user-gift')}}}					
        <div id="location_box">
            <h3>{{{trans_choice('app.location',1)}}} <i class="fa fa-pencil" id="update_location"  @if($auto_browser_geolocation=='true') onclick=" getLocation()" @endif  @if($maxmind_geoip_enabled) style="display:none" @endif></i></h3>
            <p>{{{$logUser->city}}}</p>
            @if($profile_map_show_mode == 'true')<div id="map_canvas"></div> @endif
        </div>
        <div class="col-md-12" @if($maxmind_geoip_enabled || $auto_browser_geolocation=='true') style="display:none" @endif >
            <form  class="form-horizontal hidden_form" id="updt_laction_by_id" role="form">
                <!-- 							  	{{ csrf_field() }} -->
                <div class="form-group">
                    <div class="col-xs-12">
                        <input type="text" autocomplete="on" class="form-control" placeholder="Enter Location" id="city_input" name = "city_input">							        
                        <input type="hidden" id="city" name = "city">
                        <input type="hidden" id="country" name = "country">
                        <input type="hidden" id="lat" name = "lat">
                        <input type="hidden" id="lng" name = "long">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button type="button" class="btn btn-default" id="updatecity" name = "updatecity">{{{trans_choice('app.save',1)}}}</button>
                        <button type="button" class="btn btn-default cancel">{{{trans_choice('app.cancel',1)}}}</button>
                    </div>
                </div>
            </form>
        </div>
        @foreach($field_sections as $section)
        <div id="per_info">
            <h3>{{{trans("custom_profile.$section->code")}}}
                <i class="fa fa-pencil update_per_info" id='{{{"update_per_info"."$section->id"}}}' data-id="{{{$section->id}}}" ></i>
            </h3>
            @foreach($section->fields as $field)
            @if($field->type == "dropdown" && $field->code != 'gender')
            <p class ='{{{"personal_"."$section->id"}}} personal'><span>{{{trans("custom_profile.$field->code")}}}:</span> <span id='{{{"custom_field_"."$field->id"}}}' class="personalinfo">{{{ $field->user_field($logUser->id)? trans("custom_profile.".$field->user_field($logUser->id)) : trans('custom_profile.noinfo') }}}</span></p>
            @elseif($field->type == "textarea" || $field->type == "text")
            <p class ='{{{"personal_"."$section->id"}}} personal'><span>{{{trans("custom_profile.$field->code")}}}:</span> <span id='{{{"custom_field_"."$field->id"}}}' class="personalinfo">{{{ $field->user_field($logUser->id)? $field->user_field($logUser->id) : trans('custom_profile.noinfo') }}}</span></p>

            @elseif($field->type == "checkbox")

            <p class ='{{{"personal_"."$section->id"}}} personal'>
                <span>{{{trans("custom_profile.$field->code")}}}:</span> 
                <span id='{{{"custom_field_"."$field->id"}}}' class="personalinfo">
                    <?php $checkboxArray = $field->user_field($logUser->id); ?>
                    @if(count($checkboxArray) > 0)
                        @foreach($checkboxArray as $value)
                            {{trans("custom_profile.".$value)}}
                        @endforeach
                    @else
                        {{trans('custom_profile.noinfo')}}
                    @endif
                </span>
            </p>

            @endif
            @endforeach
        </div>
        <form  class="form-horizontal hidden_form" id={{{"updt_per_info_by_id"."$section->id"}}} role="form">
            {{ csrf_field() }}
            @foreach($section->fields as $field)
            @if($field->type == "dropdown" && $field->code != 'gender')
            <div class="form-group personinfo_form_group">
                <div class="col-xs-6">{{{trans("custom_profile.$field->code")}}}:</div>
                <div class="col-xs-6" name = "relationship">
                    <select class="form-control" data-name="{{$field->id}}" name ="{{{$field->id}}}" data-type="{{{$field->type}}}" id="{{{$field->id}}}">
                        <option value="-1" {{{ $field->user_field($logUser->id) == 'false'? 'selected' : "" }}}>{{{trans("custom_profile.willreveallater")}}}</option>
                        @foreach($field->field_options as $option)
                        <option value="{{{$option->id}}}" @if($field->user_field($logUser->id) && $field->user_field($logUser->id) == $option->code) selected @endif>{{{trans("custom_profile.$option->code")}}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @elseif($field->type == "text")
            <div class="form-group personinfo_form_group">
                
                    <div class="col-xs-6">{{{trans("custom_profile.$field->code")}}}</div>
                     <div class="col-xs-6"><input data-name="{{$field->id}}" type="text" class="form-control" name ="{{{$field->id}}}" data-type="{{{$field->type}}}"id={{{"$field->id"}}} value="{{{$field->user_field($logUser->id)}}}"></div>
                
            </div>
            @elseif($field->type == "textarea")
            <div class="form-group personinfo_form_group">
               
                    <div class="col-xs-6">{{{trans("custom_profile.$field->code")}}}</div>
                     <div class="col-xs-6"><textarea data-name="{{$field->id}}" name ={{{"$field->id"}}} class="form-control" placeholder="{{{$field->code}}}" data-type="{{{$field->type}}}" id="{{{$field->id}}}"> {{{$field->user_field($logUser->id)}}}</textarea></div>
                
            </div>
            @elseif($field->type == 'checkbox')
            <div class="form-group personinfo_form_group">
               	<?php $checkboxArray = $field->user_field($logUser->id); ?>
                    <div class="col-xs-6">{{{trans("custom_profile.$field->code")}}}:</div>
                   <div class="col-xs-6">
	                   <select type="text" class="multiselect_checkbox{{$field->id}}" data-name="{{$field->id}}"  name="{{$field->id}}[]" data-nonSelectedText="{{{$field->code}}}" class="form-control multiselect multiselect-icon" multiple="multiple" data-type="{{{$field->type}}}" role="multiselect" value="{{{$field->code}}}"> 
                    @foreach($field->field_options as $option)
                       
                        <option value="{{$option->id}}" name="{{$field->id}}[]">{{trans("custom_profile.$option->code")}}</option>
                        
                    @endforeach
	                   </select>
                   </div>
                
            </div>
            @endif    
            @endforeach
            <div class="form-group">
                <div class="col-sm-10">
                    <input type="hidden" id="selected_interest" value="" />
                    <button type="button" class="btn btn-default save_personal_info" data-id="{{{$section->id}}}">{{{trans_choice('app.add',1)}}}</button>
                    <button type="button" class="btn btn-default cancel" id="i_close">{{{trans_choice('app.cancel',1)}}}</button>
                </div>
            </div>
        </form>
        @endforeach	  

        @if($profile_interests_show_mode == 'true')
        <div id="interests" ng-controller = "InterestsController" ng-init="getUserInterests()">
            
            <h3>
            	<span id="interest_count">[[total_interests_count]]</span> 
            	[[interest_title]]
            	@if($disable_interest_edit!='true')<i class="fa fa-pencil" id="update_interests"></i>@endif
            </h3>

            <ul id="user_interests">
                
            	<li ng-repeat="user_interest in user_interests">
            		<a href="#"  class="userinteresttext">[[user_interest.interest]]</a>
            		<a  class="removeinterest" id="[[user_interest.interestid]]" ><i class="fa fa-close c"></i></a>
            	</li>

            	<li style="cursor:pointer" ng-show="showLoadMoreBool" ng-click="loadMore()" class="interest-load-more-btn">
            		<a href="javascript:void(0)" style="cursor:pointer;left: 8px;position: relative;top: -4px;">.....</a>
            	</li>
            </ul>
            <p class="no_interest" ng-show="!total_interests_count">{{{trans_choice('app.add_interest',1)}}}</p>
        </div>
        <form class="form-horizontal hidden_form" id="updt_interest_by_id">
            <div class="form-group">
                <div class="col-xs-12">
                    <input type="text" class="form-control" placeholder="Interests" name="interest" required id="add_interests">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10">
                    <input type="hidden" id="selected_interest" value="" />
                    <button type="button" id="add_interest_btn" class="btn btn-default">{{{trans_choice('app.add',1)}}}</button>
                    <button type="button" class="btn btn-default cancel" id="i_close">{{{trans_choice('app.cancel',1)}}}</button>
                </div>
            </div>
        </form>
        @endif


        @if($profile_about_me_show_mode == 'true')
        <div id="about">
            <h3>{{{trans_choice('app.about_me',1)}}} <i class="fa fa-pencil" id="update_about_me"></i></h3>
            <span id="user_about_me">{{{	($logUser->profile->aboutme==null) ? trans_choice('app.write_about',1) : $logUser->profile->aboutme}}}</span>
        </div>
        <form  class="form-horizontal hidden_form" id="updt_about_me_by_id" role="form">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-xs-12">
                    <textarea name = "aboutme"  id="aboutme" class="form-control" placeholder="About Me"> </textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 col-sm-offset-4">
                    <button type="button" class="btn btn-default" id="save_add_me">{{{trans_choice('app.save',1)}}}</button>
                    <button type="button" class="btn btn-default cancel" id="i_close">{{{trans_choice('app.cancel',1)}}}</button>
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
</div>
<div class="bs-example">
<div id="photoModal" class="modal fade" role="dialog">
    <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content ">
            <div class="">
                <h4 class="report_photo_title" style="border: 0">{{{trans_choice('app.add_photos',1)}}}</h4>
                <h5 class="riseup_text">{{{trans_choice('app.add_photos_msg',1)}}}</h5>
            </div>
            <div style="    margin-top: 8%;">
                <img src="@theme_asset('images/gallery-blocker.png')" />
            </div>
            <form role="form" method = "POST"  action = "{{{ url('/profile/uploadphoto') }}}" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="modal-body">
                    <div class="form-group" style = "color : black">
                        <input type="file" class="form-control" name = "photo[]" multiple>
                    </div>
                    <div class="form-group" style = "color : white">
                        {{{Theme::render('photos')}}}
                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{{trans_choice('app.cancel',1)}}}</button>
                    <button type="submit" class="btn btn-success " disabled>{{{trans_choice('app.upload',1)}}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="imgUploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="loaderPhoto"></div>
        <form id="uploadProfilePic" enctype="multipart/form-data" method="post">
            <div class="modal-content" style="min-height: 300px">
                <div class="modal-body">
                    <output id="list"></output>
                    <div class="custom_upload">
                        <input type="file" id="files" name="profile_picture" class="upload_input" />
                        <label for="file" class="upload_label">{{{trans_choice('app.select_file_from',1)}}}</label>
                    </div>
                    <div id = "prev" class = "preview-container"></div>
                    <div class="crop_pos">
                        x<input type = "text" id ="x" name="crop_x">
                        y<input type = "text" id ="y" name="crop_y">
                        width<input type = "text" id ="width" name="crop_width">
                        height<input type = "text" id ="height" name="crop_height">
                    </div>
                    <div class="loaderUpload"></div>
                    <div class="upload-btn-box">
                        <button class="img-uploader">{{{trans_choice('app.upload',1)}}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="cropModal" class="modal fade" role="dialog">
    <div class="modal-dialog" >
        <!-- Modal content-->
        <div class="modal-content content_model">
            <button type="button" class="close" data-dismiss="modal">&times;</button> 
            <div class="modal-body" id="crop"></div>
            <div class="form-group">        
                <button type="submit" class="btn_blue" data-dismiss="modal">{{{trans_choice('app.submit',1)}}}</button>
            </div>
        </div>
    </div>
</div>
<div id="myModalDeletePhoto" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 33%">
        <!-- Modal content-->
        <div class="modal-content " style="padding:2%;max-width:420px;">
            <div class="">
                <h5 class="report_photo_title" style="font-size: 18px!important;">{{{trans_choice('app.delete_msg',1)}}}</h5 >
            </div>
            <div class="modal-body">
                <div class="loaderUpload"></div>
                <button type="button" class="btn btn-default custom_modal-popup4 delete_photo" style="margin-right: 20px">
                    <div class="loaderUpload"></div>
                    <span class="">{{{trans_choice('app.delete',0)}}}</span>
                </button>
                <button type="button" class="btn btn-default custom_modal-popup3 cancel_delete" data-dismiss="modal" ><span class="">{{{trans_choice('app.cancel',1)}}}</span></button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="cropPhoto" role="dialog" aria-labelledby="modalLabel" tabindex="-1">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background: #3a3af9;">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            
                        <h4 class="modal-title" id="modalLabel">{{{trans('app.add_to_profile_pic')}}}</h4>
                        
                         <button type="button" class="btn btn-success save_to_profile_pic" data-dismiss="modal">{{{trans('app.save')}}}</button>

          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="imagePhoto" src="" photo_metdata='' alt="Picture">
              <input type="hidden" data-x="" data-y="" data-width="" data-height="" id="imgdimenions" />
            </div>
          </div>
<!--
          <div class="modal-footer">
            <button type="button" class="btn btn-success save_to_profile_pic" data-dismiss="modal">{{{trans('app.save')}}}</button>
          </div>
-->
        </div>
      </div>
</div>

{{{Theme::render('photos-nudity')}}}
@endsection
@section('scripts')
<script src="{{{ asset('core/js/bootstrap-typeahead.js') }}}"></script>
<script>
    $(document).ready(function() {
    	$('#datePicker')
            .datepicker({
                format: 'dd-mm-yyyy'
            });
      });
           
</script>
<script>
    $(document).ready(function(){
	    
	    var selected;
	    
    $('.cancel').click(function(){
     $('.hidden_form').fadeOut('slow');
     $('.name-c').fadeIn('slow');
     
     $('.name-c-1').fadeIn('slow');
     $('#location_box').fadeIn('slow');
     $('#here_to_box').fadeIn('slow');
     $('.personal').fadeIn('slow');
     $('#interests').fadeIn('slow');
     $('#about').fadeIn('slow');
     $('#map_canvas').fadeIn('slow');
     //$('#per_info > p.personal').fadeIn('slow');
    });
    $('#update_name').click(function(){
    // 		 $('.name-c').css("display","none");
    // 		 $('.name-c-1').css("display","none");
     $('#updt_name_by_id').fadeIn('slow');
    });
    $('#update_location').click(function(){
    // 		 $('#location_box').css("display","none");
     $('#updt_laction_by_id').fadeIn('slow');
     $('#map_canvas').fadeOut('slow');
    });
    $('#update_here_to').click(function(){
    // 		 $('#here_to_box').css("display","none");
     $('#updt_here_to_by_id').fadeIn('slow');
    });
    $('.update_per_info').click(function(){
           
           
           var sectionId= $(this).data('id');
           
           $('.personal_'+sectionId).fadeOut('slow');
           
     $('#updt_per_info_by_id'+sectionId).fadeIn('slow');
     
    // 		 $('#per_info > p.personal').fadeOut('slow');
    });
    $('#update_about_me').click(function(){
    // 		 $('#about').css("display","none");
     $('#updt_about_me_by_id').fadeIn('slow');
    });
    $('#update_interests').click(function(){
     //$('#interests').css("display","none");
     $('#updt_interest_by_id').fadeIn('slow');
    });
    // image_hover
    $('.expand').click(function(){
    $('#img_popup_div').addClass("img_popup");
    $('#img_popup_div').show();
    });
    $('.img_popup_div_remove').click(function(){
     $('#img_popup_div').hide();
    });
    
    });
    
    
    
    
    var interest_flag = 0;
    
    $('#add_interests').typeahead({
        
          ajax: {
          url: "{{{ url('interest_suggestions/') }}}",
          timeout: 500,
          displayField: "interest",
          triggerLength: 1,
          method: "get",
          loadingClass: "loading-circle",
          preDispatch: function (query) {
              
              return {
                  search: query
              }
          }
      },
          displayField: 'interest',
          onSelect: function(item){
    
          	$("#selected_interest").val(item.text);
    
          	interest_flag = 1;
    
          	
    
    
          }
    });
    
    
    
    
    
</script>
<script>
    $('body').on('click', ".removeinterest", function(){

    			data={interest_id:$(this).attr('id')};
    			
    			var $t = $(this);
    		
    		$.ajax({
    					  type: "POST",
    					  url: "{{{ url('/profile/interest/delete') }}}",
    					  data: data,
    					  success: function(msg){
    					        
    					        if(msg.status=='success')
    					        {
    						        toastr.success('Interest removed');
    						        
    						        var count= $('#interest_count').text(); 
    						        
    						        if(!count)
    						        {
    							        count=0;
    						        
    									$('#interest_count').text('');
    							        
    						        }
    						        else
    						        {
    							        
    							        $('#interest_count').text(parseInt(count)-1);
    						        }
    						        	
    						        
    						        $t.parent().fadeOut('slow');
    					        }
    					        else
    					        {
    						        toastr.error("Can't remove interest");
    					        }
    					        
    					        
    					  },
    					  error: function(XMLHttpRequest, textStatus, errorThrown) {
    					        toastr.error("{{{trans_choice('app.error',1)}}}");
    					  }
    					  
    	   });
    
    })
</script>	
<script>
    $('#updatebasicinfo').on('click',function(){
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/user/profile/basic_info/update') }}}",
    						  data: $("#updt_name_by_id").serialize(),
    						  success: function(msg){
    						        
    						        if(msg.status!='error')
    						        {
    						        
    							        toastr.success('Saved');
    							        
    							        $("#updt_name_by_id").fadeOut('slow');
    							        
    							        
    							        $('#user_name').text(msg.data.name);
    							        
    							        $('#user_age').text(msg.data.age);
    							        
    							        
    							        $('#gender select').val(msg.data.gender);
    						        }
    						        else
    						        {
    							        
    							        toastr.error(msg.errors.join(''));
    							        
    						        }
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
</script>		
<script type="text/javascript">
    function initMap() { 
    	
    		var myLatLng = {lat: parseFloat('{{{$logUser->latitude}}}'), lng:parseFloat('{{{$logUser->longitude}}}')};
    			var map_canvas = document.getElementById('map_canvas');
    			var map_options = {
    					center: new google.maps.LatLng(parseFloat('{{{$logUser->latitude}}}'), parseFloat('{{{$logUser->longitude}}}')),
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
    			});
    			
    
            google.maps.event.addDomListener(window, 'load', function () {
                var places = new google.maps.places.Autocomplete(document.getElementById('city_input'));
    
                var center = map.getCenter();
                 google.maps.event.trigger(map, "resize");
    			map.setCenter(center); 
                
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
                    document.getElementById('city').value = city;
                    
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
<script>
    $('#updatecity').on('click',function(){
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/user/profile/location/update') }}}",
    						  data: $("#updt_laction_by_id").serialize(),
    						  success: function(msg){
    						        
    						        if(msg.status=='error')
    						        {
    							        toastr.error(" Can't Save location");
    						        }
    						        else
    						        {
    							        toastr.success('{{{trans_choice('app.saved',1)}}}');
    						        
    						        
    						        
    						        $("#updt_laction_by_id").fadeOut('slow');
    						        
    						        
    						        $('#location_box p').text($('#city').val());
    						        
    						        
    						        window.location.reload();
    						       
    						       
    							        
    						        }
    						        
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
    
    
    
    
</script>
<script>
    $('#updatehereto').on('click',function(){
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/user/profile/hereto/update') }}}",
    						  data: $("#updt_here_to_by_id").serialize(),
    						  success: function(msg){
    						        
    						        if(msg.status=='error')
    						        {
    							        toastr.error("{{{trans_choice('failed_hereto',1)}}}");
    						        }
    						        else
    						        {
    							        toastr.success('{{{trans_choice('app.saved',1)}}}');								        								        
    							        $("#updt_here_to_by_id").fadeOut('slow');									        
    							        $('#imhereto').text($("#sel1 option:selected").text());
    							        
    						        }
    						        
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
</script>	
<script>
    $('#add_interest_btn').on('click',function(){
    	
    	if(!$('#add_interests').val())
    	{
    		toastr.info("{{{trans_choice('app.interest_required',1)}}}");
    		return;
    	}
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/profile/interest/add') }}}",
    						  data: $("#updt_interest_by_id").serialize(),
    						  success: function(msg){
    						        
    						        if(msg.status=='error')
    						        {
    							        toastr.error("{{{trans_choice('app.failed_interest',1)}}}");
    						        }
    						        else
    						        {
    							        toastr.success('{{{trans_choice('app.saved',1)}}}');								        								        
    							        $("#updt_interest_by_id").fadeOut('slow');									        
    							       
    							       
    							       var count= $('#interest_count').text(); 
    							        
    							        if(!count)
    							        	count=0;
    							        		
    							        		
    							        // if(parseInt(count)==0)
    							        // {
    								       $('.no_interest').hide();
    								        $("#user_interests").prepend('<li><a  class="userinteresttext" >'+$('#add_interests').val()+'</a><a class="removeinterest" id='+msg.interest_id+'></a></li>');
    							  //       }
    							  //       else
    							  //       {
    									// 	$("#user_interests li:first").after('<li><a  class="userinteresttext" >'+$('#add_interests').val()+'</a><a class="removeinterest" id='+msg.interest_id+'></a></li>');
    									// }
    							        
    							       									        
    							        
    							        $('#interest_count').text(parseInt(count)+1);
    							        
    						        }
    						        
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
</script>	
<!-- 	about me api -->
<script>
    $('#save_add_me').on('click',function(){
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/user/profile/aboutme/update') }}}",
    						  data: $("#updt_about_me_by_id").serialize(),
    						  success: function(msg){
    						        
    						        if(msg.status=='error')
    						        {
    							        toastr.error("{{{trans_choice('app.failed_aboutme',1)}}}");
    						        }
    						        else
    						        {
    							        toastr.success('{{{trans_choice('app.saved',1)}}}');								        								        
    							        $("#updt_about_me_by_id").fadeOut('slow');									        
    							       
    							        
    							        
    							       $("#user_about_me").text($('#aboutme').val());
    							     
    							        
    						        }
    						        
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
</script>	
<!-- 	personal info -->
<script>
    $('.save_personal_info').on('click',function(){
    	var sectionId = $(this).data("id");
    	var data= $("#updt_per_info_by_id"+sectionId).serialize();
    	   
    	
    	var data_arr = $("#updt_per_info_by_id"+sectionId).serializeArray();
    	
    	
    	
    	
    			$.ajax({
    						  type: "POST",
    						  url: "{{{ url('/save_user_fields') }}}",
    						  data:data,
    						  success: function(msg){
    						        
    						        if(msg.status=='error')
    						        {
    							        toastr.error("{{{trans_choice('app.failed_aboutme',1)}}}");
    						        }
    						        else
    						        {
    							        toastr.success('Saved');								        								        
    							        $("#updt_per_info_by_id"+sectionId).fadeOut('slow');									        
    							        $('.personal').fadeIn('slow');
    							        
    							        _.each(data_arr,function(item) {
    		
    		
    										if(item.name != "_token") {
    											
    											if(!(item.name.indexOf("[]") >-1)){
	    											
	    											if($("[name="+item.name+"]").data("type") == "dropdown") {
    												
    													$("#custom_field_"+item.name).text($("select[id="+item.name+"] option:selected").text());
    												
	    											} else {
	    												
	    												$("#custom_field_"+item.name).text(item.value);
	    											}
	    											
    											}
    											else
    											{
	    											$("#custom_field_"+item.name.slice(0,-2)).text(selected.join(" "));
    											}
    											
    											
    											
    										}
    									});
    							        
    							      /*
    
    							       $(".relationship").text($("select[name='relationship'] option:selected").text());
    							       $(".sexuality").text($("select[name='sexuality'] option:selected").text());
    
    							       $(".living").text($("select[name='living'] option:selected").text());
    							       
    							       $(".appearance").text($("select[name='height'] option:selected").text()+', '+$("select[name='weight'] option:selected").text()+', '+$("select[name='bodytype'] option:selected").text()+', '+$("select[name='eyecolor'] option:selected").text()+', '+$("select[name='haircolor'] option:selected").text());
    							       
    							       $(".children").text($("select[name='children'] option:selected").text());
    							       $(".smoking").text($("select[name='smoking'] option:selected").text());
    							       $(".drinking").text($("select[name='drinking'] option:selected").text());
    */
    							      
    							     
    							        
    						        }
    						        
    						        
    						        
    						  },
    						  error: function(XMLHttpRequest, textStatus, errorThrown) {
    						        toastr.error("{{{trans_choice('app.error',1)}}}");
    						  }
    						  
    		   });
    
    	
    	
    	
    })
</script>
<script src="@theme_asset('js/bootstrap-datepicker1.6.0.js')"></script>
<script>
    $('.imageUpload').parent().on('click',function(){					
    	
    	$('#imgUploadModal').modal('show');
    	
    	
    })
    
    $('.imageUpload').hover(function(){
    	
    	
    	$('.profilePicUploadIcon').fadeIn();
    	
    	
    })
    
    $('.imageUpload').parent().hover(function(){
    	
    	
    	$('.profilePicUploadIcon').fadeIn();
    	
    	
    })
    
    $('.imageUpload').parent().mouseout(function(){
    	
    	
    	$('.profilePicUploadIcon').fadeOut();
    	
    	
    })
    
    
    
    
    			
</script>		
<script>
    function handleFileSelect(evt) {
    $(".custom_upload").css("display","none");
    $(".extrenal-upload").css("display","none");
    $(".or_box").css("display","none");
    $(".upload-btn-box").css("display","block");
    var files = evt.target.files; // FileList object
    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {
     
     
    
      // Only process image files.
      if (!f.type.match('image.*')) {
        continue;
      }
    
      var reader = new FileReader();
    
      // Closure to capture the file information.
      reader.onload = (function(theFile) {
        return function(e) {
          var prev= $('#prev');
          var x = $('#x');
          var y = $('#y');
          var width = $('#width');
          var height = $('#height');
          var div = document.createElement('div');
          div.classList.add("crop_box");
          div.innerHTML = ['<img id="image" src="', e.target.result,
                            '" title="', escape(theFile.name), '"/>'].join('');
         $("#cropModal").modal();
         
         $("#cropModal").on("shown.bs.modal", function() {
          document.getElementById('crop').innerHTML='';
          
     document.getElementById('crop').insertBefore(div, null);
          $("#prev").css("display","block");
          $('#image').cropper({
             aspectRatio: 1/1,
              viewMode:0,
              preview:prev,
              responsive:true,
              restore:true,
              background:false,
              movable:true,
              minContainerWidth: 320,
            minCropBoxWidth: 100,
    minCropBoxHeight: 100,
              autoCrop:true,
              modal:true,
              guides:true,
              highlight:true,
    
              zoomOnTouch:true,
              crop: function(e) {   
                x.val(e.x);
                y.val(e.y);
                width.val(e.width);
                height.val(e.height);
                
                
                
                
              }
    
          });
    })
          
        };
      })(f);
    
      // Read in the image file as a data URL.
      reader.readAsDataURL(f);
    }
    }
    document.getElementById('files').addEventListener('change', handleFileSelect, false);
</script>


<script src="@theme_asset('js/cropper.js')"></script>

<script>
    $('.img-uploader').on('click',function(){
    	
    	
    
    	$('.loaderUpload').fadeIn();
    	
    		$.ajax({
    					  type: "POST",
    					  url: "{{{ url('/user/profile_picture/upload') }}}",
    					  contentType: false,								  
    					  processData: false,
    					  data: new FormData($('#uploadProfilePic')[0]),
    					  success: function(msg){
    						  
    						  $('.loaderUpload').fadeOut();
    					        
    					        if(msg.status=='error')
    					        {
    						        toastr.error("{{{trans_choice('app.failed_photo_upload',1)}}}");
    					        }
    					        else
    					        {
    						       		
    						       	$('.imageUpload').attr('src',msg.images[0])					        								        
    						        //	
    						        
    						        $('#imgUploadModal').modal('hide');						     
    						        window.location.reload();	
    					        }
    					        
    					        
    					        
    					  },
    					  error: function(XMLHttpRequest, textStatus, errorThrown) {
    					        toastr.info("{{{trans_choice('app.error',1)}}}");
    					  }
    					  
    	   });
    	
    	
    })
</script>
<script>
    $('.addmorephotos').on('click',function(event){
    	
    	event.preventDefault(); 
    	
    	$('#filetypeforaddmore').click();
    	
    })
    
</script>	
<script>
    $('#myCarouselMyPhotos > div > div:nth-child(1)').addClass('active');
    
</script> 
<script>
    $(document).ready(function(){
     var val = parseInt('{{{$score->score}}}')/10;
     	$('#circle').circleProgress({
           value: val,
           size: 60,
           fill: {
               gradient: ["#526AD7 ", "#F82856 "]
           }
       }).on('circle-animation-progress', function(event, progress) {
        $(this).find('strong').html(parseInt(100 * val) + '<i>%</i>');
    });		
       
     $('.total_likes').text(parseInt('{{{$score->likes}}}')+parseInt('{{{$score->dislikes}}}')); 
       
     });
</script> 
<script>
    $('.remove_profile_pic').on('click',function(){
     
    //fetch the photo url to be deleted
    $('#myModalDeletePhoto').modal('show');
    	
    	
    	if($(this.nextElementSibling).children("div.active")[0])
    $('.photo_tobedeleted_url').val($(this.nextElementSibling).children("div.active")[0].firstElementChild.href.substring($(this.nextElementSibling).children("div.active")[0].firstElementChild.href.lastIndexOf('/')+1).split('"')[0]);
    
     
     
    })
    
    
    
    $('.delete_nude_photo').on('click',function(){
    
    
    data={
    		photo_name:$('.photo_tobedeleted_url').val()
    };
    
    $(".loaderUpload").fadeIn("slow");
    
    
    $.ajax({
      type: "POST",
      url: "{{{ url('/user/photo/delete') }}}",
      data: data,
      success: function(msg){
            $(".loaderUpload").fadeOut("slow");
            
            
            if(msg.status=='error')
            {
            	toastr.error("{{{trans_choice('app.failed_photo_delete',1)}}}");
            }
            else
            {
            	toastr.success("Photo deleted");
            	
            	$('#myModalDeletePhoto').modal('hide');
            	
            	
            	window.location.reload();
            	
            	
            }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.info("{{{trans_choice('app.error',1)}}}");
      }
    });
    
    
    })
    
    
    $('.delete_photo').on('click',function(){
    
    
    data={
    		photo_name:$('.photo_tobedeleted_url').val()
    };
    
    $(".loaderUpload").fadeIn("slow");
    
    
    $.ajax({
      type: "POST",
      url: "{{{ url('/user/photo/delete') }}}",
      data: data,
      success: function(msg){
            $(".loaderUpload").fadeOut("slow");
            
            
            if(msg.status=='error')
            {
            	toastr.error("{{{trans_choice('app.failed_photo_delete',1)}}}");
            }
            else
            {
            	toastr.success("Photo deleted");
            	
            	$('#myModalDeletePhoto').modal('hide');
            	
            	
            	window.location.reload();
            	
            	
            }
      },
      error: function(XMLHttpRequest, textStatus, errorThrown) {
            toastr.info("{{{trans_choice('app.error',1)}}}");
      }
    });
    })
</script>	

<script>
    $('.make_it_as_profile_pic').on('click',function(){
	    
	    
	    	
    		
    		
    		var full_url=$(this).parents('div#myCarouselMyPhotos').find('div.carousel-inner div.active')[0].firstElementChild.href;
    		
    		
    		var photo_metdata=$($(this).parents('div#myCarouselMyPhotos').find('div.carousel-inner div.active')[0].firstElementChild).children('img').data('photo');
    		
    		//get width and height of photo
    		var width= $($(this).parents('div#myCarouselMyPhotos').find('div.carousel-inner div.active')[0].firstElementChild).children('img')[0].clientWidth;
    		
    		if(width <= 100)
    		{
	    		
	    		toastr.error("{{{trans('app.larger_image')}}}");
	    		
    		}
    		else
    		{
	    		
	    		$('#cropPhoto').modal('show');
    		
    		
    		$('#imagePhoto').attr('src',full_url);
    		
    		$('#imagePhoto').attr('photo_metdata',photo_metdata);
    		

	    		
    		}
    		
    		    		
    		
    		
    		
    		
    		
    		
    	})
    	
    	
    	
    	
    	
    	function setProfilePicture(full_url,photo_metdata)
    	{
	    	var pic_url= full_url.substring(full_url.lastIndexOf('/')+1).split('"')[0];
    
			var photo_metadata = photo_metdata;
			
			
/*
			//get the profile picture
			var profile_picture_url = $('.profile_picture').attr('src');
			
				profile_picture_url = profile_picture_url.substring(profile_picture_url.lastIndexOf('/')+1).split('"')[0];
			
			//check if user is trying to make profile picture as private
			var if_user_trying_profile_private = 0 ;
			
			if( pic_url===profile_picture_url)
				if_user_trying_profile_private=1;
*/
			
			
			if(photo_metadata=='1')
			 {
				 toastr.error('{{trans("app.nude_photo_profile")}}');
				 return;
			 }
			 
/*
			 if(if_user_trying_profile_private)
			 {
				 
				 toastr.error('{{trans("app.photo_profile_make_private")}}' );
				 return;
				 
			 }
*/
    		
    		data={
    				photo_name:pic_url,
    				crop_width:$('#imgdimenions').data('width'),
    				crop_height:$('#imgdimenions').data('height'),
    				crop_x:$('#imgdimenions').data('x'),
    				crop_y:$('#imgdimenions').data('y')
    		};
    		
    		$(".loaderUploadProfilePic").fadeIn("slow");
    		
    		
    		$.ajax({
    		  type: "POST",
    		  url: "{{{ url('user/profile_picture/change') }}}",
    		  data: data,
    		  success: function(msg){
    		        $(".loaderUploadProfilePic").fadeOut("slow");
    		        
    		        
    		        if(msg.status=='error')
    		        {
    		        	toastr.error("{{{trans_choice('app.failed_profile_picture',1)}}}");
    		        }
    		        else
    		        {
    		        	toastr.success("{{{trans_choice('app.same_picture',1)}}}");
    		        	
    		        	$('.imageUpload').attr('src',full_url);
    		        	
    		        }
    		  },
    		  error: function(XMLHttpRequest, textStatus, errorThrown) {
    		        toastr.error("{{{trans_choice('app.error',1)}}}");
    		  }
    		});
    	}
    
</script>	

<script>
    window.addEventListener('DOMContentLoaded', function () {
      var image = document.getElementById('imagePhoto');
      var cropBoxData;
      var canvasData;
      var cropper;
      $('#cropPhoto').on('shown.bs.modal', function () {
		      
			  var c = $('#imagePhoto').cropper({
		      
			      aspectRatio:1/1,
			      strict:true,
			      background:false,
			      guides:false,
			      autoCropArea:0.6,
			      rotatable:false,
			      //using these just to stop box collapsing on itself
			      minCropBoxWidth:50,
			      minCropBoxHeight:50,
			      
			      crop: function(data){
			          //console.log("data = %o", data);
			          //console.log("this = %o", $(this));
			          
			          //test the new height/width
			          if(data.height < 100 || data.width < 100){
			              //try to make it stop 
			              //$(this).cropper('disable');
			          }else{
			              			              
			              $('#imgdimenions').attr('data-x',data.x);
			              $('#imgdimenions').attr('data-y',data.y);
			              $('#imgdimenions').attr('data-width',data.height);
			              $('#imgdimenions').attr('data-height',data.width);
			          }
			      }
			    })  
		      
		    
      }).on('hidden.bs.modal', function () {
        	      });
      
      
      
    
    
    
     

});



$('.save_to_profile_pic').on('click',function()
{
	
			setProfilePicture($('#imagePhoto').attr('src'),$('#imagePhoto').attr('photo_metdata'));
        	
        	
        	$("#imagePhoto").cropper("destroy");

	
})




 $('#imagePhoto').on('cropmove.cropper', function (e) {
	        console.log('dragmove.cropper');
	        
	        var $cropper = $(e.target);
	        
	        // Call getData() or getImageData() or getCanvasData() or
	        // whatever fits your needs
	        var data = $cropper.cropper('getCropBoxData');
	        
	        console.log("data = %o", data);
	        
	        // Analyze the result
	        if (data.height <= 150 || data.width <= 150) {
	            console.log("Minimum size reached!");
	            
	            // Stop resize
	            return false;
	        }
	        
	         $('#imgdimenions').attr('data-x',data.x);
          $('#imgdimenions').attr('data-y',data.y);
          $('#imgdimenions').attr('data-width',data.height);
          $('#imgdimenions').attr('data-height',data.width);
          
			              	        // Continue resize
	        return true;
     }).on('cropstart.cropper', function (e) {
        console.log('dragstart.cropper');
        
        var $cropper = $(e.target);
        
        // Get the same data as above 
        var data = $cropper.cropper('getCropBoxData');
        
        // Modify the dimensions to quit from disabled mode
        if (data.height <= 150 || data.width <= 150) {
            data.width = 151;
            data.height = 151;
            
            $(e.target).cropper('setCropBoxData', data);
        }
    });
    
    
    
    
  </script>
<script>
    $(function () {
    	if('{{{$logUser->dob}}}') 
    	{
    		$('#dob').val(moment('{{{$logUser->dob}}}').format('DD-MM-YYYY'))
    	}
    });		
</script>	
<script>
    $(document).ready(function(){
    	
    	angle=90;
    })
    
    $('.rotate_image').on('click',function(){
    	
    	
    	if($(this.previousElementSibling.previousElementSibling.previousElementSibling).children("div.active")[0])			    
    	
    	
    	$($(this.previousElementSibling.previousElementSibling.previousElementSibling).children("div.active")[0].firstElementChild.firstElementChild).css({
         "-webkit-transform": "rotate("+angle+"deg)",
         "-moz-transform": "rotate("+angle+"deg)",
         "transform": "rotate("+angle+"deg)" /* For modern browsers(CSS3)  */
     });
    	
    	angle=angle+90;
    	
    })
</script>	
<script>
    $('.imageUpload').on('click',function(){
    	
    	
    	$(".custom_upload").css("display","block");
        $(".extrenal-upload").css("display","block");
        $(".or_box").css("display","block");
        $(".upload-btn-box").css("display","none");
        
        
    	
    })
    
    $("#imgUploadModal").on("shown.bs.modal", function() {
    	
    	$('.loaderPhoto').show();
    	$(".custom_upload").css("display","block");
        $(".extrenal-upload").css("display","block");
        $(".or_box").css("display","block");
        $(".upload-btn-box").css("display","none");
    
    
      $("#image").cropper("destroy")
    
      $("#image").cropper('reset', true);
      $("#image").cropper('clear');
      
      				$('.loaderPhoto').hide();
    })



@if($profile_interests_show_mode == 'true')
App.controller('InterestsController',function($scope,$rootScope,$http, $timeout, $window){


	$scope.total_interests_count = 0;
	$scope.user_interests = [];
	$scope.last_user_interest_id = 0;
	$scope.interest_title = "{{{trans_choice('app.interest',1)}}}";
	$scope.get_interest_url = "{{{url('profile/interests/get')}}}";
	$scope.current_interests_count = 0;

	$scope.csrf_token = "{{{csrf_token()}}}";



	$scope.getUserInterests = function(){

		$http.post($scope.get_interest_url, {_token:$scope.csrf_token, user_id:"{{{$auth_user->id}}}", last_user_interest_id:$scope.last_user_interest_id})
		.then(function(response){

			if(response.data.status == "success") {

				$scope.total_interests_count = response.data.total_user_interests_count;
				$scope.user_interests = $.merge($scope.user_interests, response.data.data);
				$scope.current_interests_count += response.data.count;
				$scope.setLastUserInterestId();
				$scope.renderInterestTitle();
				$scope.showLoadMore();

                $(".interest-load-more-btn").find("> a").removeClass('loading');;
                $(".interest-load-more-btn").find("> a").text("...");
			}


		}, function(response){
			console.log("interest get error");
            $(".interest-load-more-btn").find("> a").addClass('loading');;
            $(".interest-load-more-btn").find("> a").text("");
		});


	}


	$scope.renderInterestTitle = function() {
		$scope.interest_title = ($scope.total_interests_count > 1) ? "{{{trans_choice('app.interest',2)}}}" : "{{{trans_choice('app.interest',1)}}}";
	}


	$scope.setLastUserInterestId = function(){
		$scope.last_user_interest_id = ($scope.total_interests_count > 0)? $scope.user_interests[$scope.user_interests.length -1].id  : 0;
	}


	$scope.loadMore = function(){
        $(".interest-load-more-btn").find("> a").addClass('loading');;
        $(".interest-load-more-btn").find("> a").text("");
		$scope.setLastUserInterestId();
		$scope.getUserInterests();
	}

	$scope.showLoadMoreBool = false;
	$scope.showLoadMore = function(){
		$scope.showLoadMoreBool = ($scope.current_interests_count == $scope.total_interests_count) ? false : true;
	}


});
@endif
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
	    
	    
	    
	    @foreach($field_sections as $section)
	    
	      @foreach($section->fields as $field)
	    
	    	@if($field->type == 'checkbox')
	    	
	    		<?php $checkboxArray = $field->user_field($logUser->id); ?>
	    		
	    				
				var array=[];	
						
	    		
	    		if($('.multiselect_checkbox{{$field->id}}').length)
			    {
				    @foreach($checkboxArray as $value)
				    		
				    		array.push('{{$value}}');
                            
                     @endforeach
                     
                     $('.multiselect_checkbox{{$field->id}}').val(array).prop("selected", true);
                     
                     $(".multiselect_checkbox{{$field->id}} option").each(function() {
					  if( $.inArray($(this).text().toLowerCase(),array) !== -1) {
					    $(this).attr('selected', 'selected');            
					  }                        
					});
				    
			        $('.multiselect_checkbox{{$field->id}}').multiselect({
				        nonSelectedText: $('.multiselect_checkbox').attr('data-nonselectedtext'),
				        onChange:function(element, checked)
				        {
					         var selectedItems = $('.multiselect_checkbox{{$field->id}} option:selected');
					         
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
	
	$(document).ready(
	    function(){
	        $('input:file').change(
	            function(){
	                if ($(this).val()) {
	                    $('button:submit').attr('disabled',false);
	                   
	                } 
	            }
	            );
	            
	            
	      
    });
    
    
    imageCensor =function (event,el)
	      {
		      el.stopPropagation();
		      el.preventDefault();
		      
		      $('.photo_tobedeleted_url').val($(event).attr('src').substring($(event).attr('src').lastIndexOf('/')+1));
    								    
    								    
    		  $('#photoNudeModal').modal('show');
		      
	      }
	
</script>

@if($auto_browser_geolocation=='true')
<script type="text/javascript">
	
    $(document).ready(function() {
	    
	   
	    
	    
	    //make loction disabled
	    if($('#city_input').length)
	    	$('#city_input').attr('disabled','disbaled');
	    
	    
		
	    
    });
    
    
    
    function getLocation() {
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition,showError);
		    } else { 
		        toastr.info("Geolocation is not supported by this browser.");
		    }
		}
		
		 
		 
		 function showPosition(position) {
		   // toastr.info("Latitude: " + position.coords.latitude +"<br>Longitude: " + position.coords.longitude);
		    
		    codeLatLng(position.coords.latitude, position.coords.longitude);
		 }
		 
		 
		 function showError(error) {
		    switch(error.code) {
		        case error.PERMISSION_DENIED:
		            toastr.error("User denied the request for Geolocation.");
		            break;
		        case error.POSITION_UNAVAILABLE:
		            toastr.error("Location information is unavailable.").
		            break;
		        case error.TIMEOUT:
		            toastr.error("The request to get user location timed out.").
		            break;
		        case error.UNKNOWN_ERROR:
		            toastr.error("An unknown error occurred.").
		            break;
		    }
		}
		
		
		
		
		
		 function codeLatLng(lat, lng) {
			 
			 //assign values to hidden fields
			 $('#lat').val(lat);
			 $('#lng').val(lng);
			 
			  geocoder = new google.maps.Geocoder();

			var latlng = new google.maps.LatLng(lat, lng);
			geocoder.geocode({latLng: latlng}, function(results, status) {
			    if (status == google.maps.GeocoderStatus.OK) {
			      if (results[1]) {
			        var arrAddress = results;
			        
			        $.each(arrAddress, function(i, address_component) {
			          if (address_component.types[0] == "locality") {
			            console.log("City: " + address_component.address_components[0].long_name);
			            itemLocality = address_component.address_components[0].long_name;
			            
			            
			            
			            $('#city').val(itemLocality);
			            
			            
			            
			          }
			          
			          if (address_component.types[0] == "country") {
			            
			            country = address_component.address_components[0].long_name;
			            
			            
			            $('#country').val(country);
			           
			          }
			          
			        });
			        
			        if($('#city_input').length)
			        {
			            	$('#city_input').val(itemLocality+' '+country);
			            	
			            	 var Request = {
								city   :itemLocality,
								country:country,
								lat    :lat,
								long   :lng	
					    	};
					    	
					    
					
					    	$.post("{{{ url('user/profile/location/update') }}}" , Request, function(data){
						    	
						    		toastr.success('{{{trans_choice('app.saved',1)}}}');
						    		
						    		setTimeout(function(){
    			window.location.reload();
    		}, 1000);
						    	
					    		   	});	
					            	
					        }    	
			        
			        
			      } else {
			        toastr.error("No results found");
			      }
			    } else {
			      toastr.error("Geocoder failed due to: " + status);
			    }
			});
		}
    
</script>
@endif
	
@endsection
@yield('profile_edit-scripts')





