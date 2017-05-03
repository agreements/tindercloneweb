<?php use App\Components\Theme; ?>
@extends(Theme::layout('master')) 
@section('content')
@parent
<link href="@theme_asset('css/people-nearby.css')" rel="stylesheet" />
<div class="col-xs">
    <div id="close-currentlocation1" class="col-md-12 people-current-location-tab">
        <div class="row">
            <div class="col-md-6 people-nearby-current-picker">
<!--                 <i class="material-icons md-24 md-dark material-pindrop">pin_drop</i> -->
				<img data-src="@theme_asset('images/placeholder.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="60" width="30" /> 
                <input autocomplete="on" name="city" value="{{{$auth_user->city}}} {{{$auth_user->country}}}" placeholder="{{{trans('app.enter')}}} {{{trans_choice('app.location',0)}}}" class="txtPlaces" id="txtPlaces1" style="width: 137px" type="text">
                <!-- <input autocomplete="on" name="city" value="Moscow Russia" placeholder="Enter a location" class="txtPlaces" id="txtPlaces12" disbaled="disabled" style="width: 137px" type="text"> -->
                <input type="hidden" id="lat" name="lat" value=""/>
                <input type="hidden" id="lng" name="lng" value=""/>
                <input type="hidden" id="country" name="country" value=""/>
                <input type="hidden" id="city" name="city" value=""/>
                 @if($maxmind_geoip_enabled || $auto_browser_geolocation=='true')
                    <i class="fa fa-arrow-circle-right enter_loc" onClick="setLocationProfile()"></i>
                @else
                    <i class="fa fa-arrow-circle-right enter_loc" data-toggle="modal" data-target="#set-location-confirmation"></i>
                @endif
            </div>
            <div class="col-md-2 col-xs-5 people-nearby-filter">
                <i  data-toggle="modal" data-target="#filterModal" class="material-icons md-24 material-tune-settings">tune</i>
            </div>
             <<div class="col-md-2 col-xs-4 onlineoffline">
                 <select id="onlinefilter" class="form-control rangemin remove_boxshadow input_height input_max_width input_max_margin" >
			         <option value="all" >{{{trans('app.all')}}}</option>
			         <option value="online">{{{trans('app.online')}}}</option>                  
		          </select>
            </div>
        </div>
    </div>
    <div id="close-currentlocation" class="col-md-12 people-current-location-tab" style="display:none">
        <div class="row">
            <div class="col-md-2 people-nearby-filter">
                <i onclick="closeMark()" class="material-icons md-24 people-nearby-filter-button">{{{trans_choice('app.close',1)}}}</i>
            </div>
        </div>
    </div>
    <div id="hidden" class="col-md-12 people-nearby-filter-div" style="display:none">
        <div class="row">
            <div class="col-md-4 people-nearby-show">
                <span class="show-text">{{{trans_choice('app.show',0)}}}</span>
                <input id="rad1" type="radio" name="rad"><label for="rad1">{{{trans_choice('app.guy',1)}}}</label>
                <input id="rad2" type="radio" name="rad"><label for="rad2">{{{trans_choice('app.girl',1)}}}</label>
            </div>
            <div class="col-md-8 people-nearby-distance">
            </div>
            <div class="col-md-4 people-nearby-age">
                <div class="rang_slider1">
                    <p>
                        <label for="age">{{{trans_choice('app.age',1)}}}:</label>
                        <input type="text" id="age12" name = "age1" value = "27-64">
                    </p>
                    <div id="slider-age1"></div>
                </div>
            </div>
            <div class="col-md-8 people-nearby-savechanges">
                <button type="button" class="btn btn-primary people-nearby-savechanges-button">{{{trans_choice('app.save',1)}}} {{{trans_choice('app.change',1)}}}</button>
            </div>
        </div>
    </div>
    <div class="cont-cover">
        <div class="cont-header">
            <!-- start people nearby -->
            @if(count($nearByUsers) > 0)
            <div id="grid" data-columns>
                @foreach($nearByUsers as $user)
                <div  class="col-md-12 col-xs-12 user-image-template-col-main" >
                    @if(count($user->photos))
                    <div class="photo-counter_people_nearby"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($user->photos)}}}</span> 
	                  
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-xs-12" style="height:200px">
                            <div class="row"> 
                                <a href='{{{url("/profile/$user->id")}}}'><img class="profile-template-image-custom" data-src='{{{$user->others_pic_url()}}}' src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs="></a>
                            </div>
                        </div>
                        <div class="col-md-12 col-xs-12 col-user-detail-template">
                            <ul class="list-inline ul-remove-margin ul-name-font-size">
                                <li class="template-profile-name"><a class="peoplenearbylink" href='{{{url("/profile/$user->id")}}}'>{{{$user->name }}},</a></li>
                                <li class="li-remove-padding">{{{ $user->age() }}}</li>
                                @if($user->onlineStatus())
                                <li class="li-icon-styling"><i class="fa fa-circle resize-small online-custom"></i></li>
                                @endif
                            </ul>
                            <ul class="list-inline ul-remove-margin ul-age-styling">
                                <li>{{{$user->city }}}@if($user->country)
                                    {{{$user->country}}}
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12 col-xs-12 circle-div-custom">
                            @if(isset($user->riseup_updated))   
                           
                           <div class="btn-icons">
<!-- 	                           <i class="fa fa-arrow-circle-o-up rise_up" data-toggle="tooltip" title="{{{trans('app.profile_raised')}}}"></i> -->
	                           <img data-src="@theme_asset('images/up-arrow.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="25" width="35" data-toggle="tooltip" title="{{{trans('app.profile_raised')}}}" />
	                           
	                           </div>
                           <time class="timeago rise_up_tooltip" datetime="{{{ $user->riseup_updated }}}" ></time>
                           @endif
                            @if($user->isSuperpowerActivated())
                            <div class="btn-icons"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDI1NiAyNTYiIGhlaWdodD0iMjU2cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyNTYgMjU2IiB3aWR0aD0iMjU2cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMkM2Mi4xLDI0Ny4zMjIsOC42NzgsMTkzLjksOC42NzgsMTI4QzguNjc4LDE5NC4yNzQsNjIuMSwyNDgsMTI4LDI0OCAgICBzMTE5LjMyMi01My43MjYsMTE5LjMyMi0xMjBDMjQ3LjMyMiwxOTMuOSwxOTMuOSwyNDcuMzIyLDEyOCwyNDcuMzIyeiIgZmlsbD0iI0I3MUMxQyIgb3BhY2l0eT0iMC4yIi8+PHBhdGggZD0iTTEyOCw4LjY3OEMxOTMuOSw4LjY3OCwyNDcuMzIyLDYyLjEsMjQ3LjMyMiwxMjhDMjQ3LjMyMiw2MS43MjYsMTkzLjksOCwxMjgsOCAgICBTOC42NzgsNjEuNzI2LDguNjc4LDEyOEM4LjY3OCw2Mi4xLDYyLjEsOC42NzgsMTI4LDguNjc4eiIgZmlsbD0iI0ZGRkZGRiIgb3BhY2l0eT0iMC4yIi8+PGNpcmNsZSBjeD0iMTI4IiBjeT0iMTI4IiBmaWxsPSIjRjQ0MzM2IiByPSIxMTkuMzIyIi8+PC9nPjxsaW5lYXJHcmFkaWVudCBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaWQ9IlNWR0lEXzFfIiB4MT0iMTExLjQxOCIgeDI9IjIxMi4zNzg1IiB5MT0iMTExLjQwNzciIHkyPSIyMTIuMzY4MiI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojMjEyMTIxO3N0b3Atb3BhY2l0eTowLjIiLz48c3RvcCBvZmZzZXQ9IjEiIHN0eWxlPSJzdG9wLWNvbG9yOiMyMTIxMjE7c3RvcC1vcGFjaXR5OjAiLz48L2xpbmVhckdyYWRpZW50PjxwYXRoIGQ9Ik0xMjgsMjQ3LjMyMmM2NS45LDAsMTE5LjMyMi01My40MjIsMTE5LjMyMi0xMTkuMzIyYzAtMi41MzItMC4wODgtNS4wNDItMC4yNDQtNy41MzVsLTAuMDI0LTAuMzgxICAgYy0wLjAyNC0wLjM2OC0wLjA0My0wLjczNy0wLjA3LTEuMTAzbC0wLjA2NC0wLjc3MmMtMC4wMTktMC4yMjgtMC4wNDEtMC40NTUtMC4wNjEtMC42ODNsLTAuMTAyLTEuMTQ5ICAgYy0wLjAxLTAuMTAxLTAuMDE3LTAuMjAzLTAuMDI3LTAuMzAzbC0wLjEyNC0xLjIyMmwtNjcuMjM5LTY3LjJsMCwwbC03OC40ODMsNzQuMjlsOS41MjIsOC44OTNsLTIyLjU2NiwyOC45ODdsMTIuNzMyLDEyLjEyNiAgIGwtMjMuOTIsMzYuNDM3bDAsMGwwLDBsMzkuMTIzLDM4LjMxOGMwLjA2NywwLjAwNywwLjEzNSwwLjAxMiwwLjIwMiwwLjAxOWwxLjA2MywwLjA5NGMwLjE2NCwwLjAxNSwwLjMyOCwwLjAyOSwwLjQ5MywwLjA0NCAgIGwwLjc3MywwLjA2OWMwLjI2NCwwLjAyMSwwLjUyNiwwLjA0NCwwLjc5LDAuMDYzbDAuNDg3LDAuMDMxYzAuMzY5LDAuMDI2LDAuNzQsMC4wNDgsMS4xMSwwLjA3bDAuMTc2LDAuMDExICAgQzEyMy4yMjgsMjQ3LjI0MywxMjUuNjA1LDI0Ny4zMjIsMTI4LDI0Ny4zMjJ6IiBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIvPjxwb2x5Z29uIGZpbGw9IiNGRkVCM0IiIHBvaW50cz0iMTQ2Ljg2NCw5OS4zNTQgMTQ2LjcxNCw5OS4zNTMgMTc5LjM2Niw0Ny42NTIgMTAwLjg4NCwxMjEuOTQyIDExNy4zMjgsMTIxLjk0MiAxMTcuMjg5LDEyMS45OTIgICAgMTE3LjMyOCwxMjEuOTkyIDExMi42MTIsMTI4LjAwMSA4Ny44MzksMTU5LjgyMSAxMDguMzU4LDE1OS44MjEgMTA4LjE4MiwxNjAuMDg3IDEwOC4zNTgsMTYwLjA4NyA3Ni42NTIsMjA4LjM4NSAxNTcuMTU2LDEzNi4xNjggICAgMTM3Ljc0NCwxMzYuMTY4IDEzNy43OTcsMTM2LjEwMSAxMzcuNzQ0LDEzNi4xMDEgMTQ0LjE3LDEyNy45OTcgMTY2LjMyOCw5OS44MiAxNDYuNzE0LDk5LjU4OSAgIi8+PHBvbHlnb24gZmlsbD0iI0JGMzYwQyIgb3BhY2l0eT0iMC4yIiBwb2ludHM9IjE4MC40MzEsNDUuOTY2IDEwMC44MzEsMTIxLjk0MiAxMDAuODg0LDEyMS45NDIgMTc5LjM2Niw0Ny42NTIgICIvPjwvZz48L3N2Zz4=" class="super_power_image" data-toggle="tooltip" title="{{{trans('app.super_power_active_status')}}}"></div>
                            @endif 
                            @if($user->popularity == 100)
                            <div class="btn-icons">
                                <img data-toggle="tooltip" title="{{{trans_choice('app.popularity_choice',4)}}}" class="popular_user_image" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgY29udGVudFNjcmlwdFR5cGU9InRleHQvZWNtYXNjcmlwdCIgY29udGVudFN0eWxlVHlwZT0idGV4dC9jc3MiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDEzNi4zMzMgMTIzIiBoZWlnaHQ9IjcwLjk2MjAwNTYxNTIzNDM4cHgiIGlkPSJMYXllcl8xIiBwcmVzZXJ2ZUFzcGVjdFJhdGlvPSJ4TWlkWU1pZCBtZWV0IiB2ZXJzaW9uPSIxLjAiIHZpZXdCb3g9IjI1LjkzNDAwMDAxNTI1ODc5IDE1LjQwNDAwMzE0MzMxMDU0NyA3MC45NjIwMDU2MTUyMzQzOCA3MC45NjIwMDU2MTUyMzQzOCIgd2lkdGg9IjcwLjk2MjAwNTYxNTIzNDM4cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHpvb21BbmRQYW49Im1hZ25pZnkiPjxnPjxwYXRoIGQ9Ik05MC4wOSw1NC41ODRjLTEuNTY2LTIuMDAxLTkuMDE1LTkuNTU1LTUuODAxLTE3Ljc1MWMwLDAtNy42MDYsNS45NDYtNC43NTEsMTQuNTYgICBjLTEuMzE4LTEuOTE2LTEzLjgzMy04LjQ1Mi0xMi43OTEtMzIuNTZjMCwwLTAuNzksMC43OC0yLDIuMTQ5djYxLjgzNmM2LjkwMS0wLjE5MywxMi4wOTctMi4yNzEsMTIuMDk3LTIuMjcxICAgUzk2Ljg5Niw3MC40NzMsOTAuMDksNTQuNTg0eiIgZmlsbD0iI0QyNjIzRSIvPjxwYXRoIGQ9Ik01MS44MzksNDguMjAyYzAsMCwxLjk3OSwxNS45NTgsOC41NzUsMTguNTE0YzAsMC0xMy4xOTEtMy4xOTMtMTUuODMtMTkuMTU0ICAgYy0wLjY1OCwxLjkxNi0xOC42NSwxOS4yNzUsNi41NDYsMzIuNjY0YzQuNjQ5LDIuMTM5LDkuNDQ1LDIuNzExLDEzLjYxNywyLjU5NFYyMC45ODNDNjAuNjg2LDI1LjU4MSw1MS44MzksMzYuODg5LDUxLjgzOSw0OC4yMDJ6ICAgIiBmaWxsPSIjRUM2QzUxIi8+PC9nPjwvc3ZnPg=="  />
                            </div>
                            @endif 
                            <!-- <button type="button" class="btn btn-default div-circle div-circle-blue btn-blue-custom"></button> -->
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="alert alert-info">
                {{{trans_choice('app.no_people',1)}}}
            </div>
            @endif
            <!-- end start people nearby -->
        </div>
       <div class="pagination_cnt"> {!! $nearByUsers->render() !!}</div>
    </div>
</div>

<!-- setlocation user confirmation modal -->

<div class="bs-example">
    <div id="set-location-confirmation" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 style="color:black">
                       {{{trans('app.set_location_modal_title')}}}
                    </h4>
                </div>
                <div class="modal-body">
                	<h5 style="color:rgb(102, 102, 102)">{{{trans('app.set_location_modal_body')}}}</h5>
                </div>
                <div class="modal-footer">
                    <!-- modal footer -->
                    <button type="button" class="btn btn-default custom_modal-popup3 riseup_to_numberone" onClick="setLocationBoth()"><div class="loaderUpload"></div><span class="">{{{trans_choice('app.confirmation',0)}}}</span></button>
                    <button type="button" style="margin-bottom:5%" class="btn btn-default custom_modal-popup3 riseup_to_numberone" onClick="setLocationProfile()"><div class="loaderUpload"></div><span class="">{{{trans_choice('app.confirmation',1)}}}</span></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- end setlocation user confirmation modal -->
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
                        @if(($advance_filter_only_superpowers == 'false' ) || $auth_user->isSuperPowerActivated())
                        <div class="row">
                            <div class="customoptionslink linkblue" style="color:#b3b3b3;">
                                <div style="margin-bottom: 10px;margin-top: 15px">{{{trans('app.search_criteria_set_for')}}}:</div>
                                @foreach($custom_filter_data->search_fields as $field)
                                @if(count($field->field_options) > 0)
                                @if($field->isSelected)
                                <div class="tw3-filter__form__content__flex">
                                    <div class="tw3-rangeHolder jsCustomRange jsAdvancedFilter tw3-dropdownHolder--blue tw3-dropdownHolder--outline" name="advancedFilter[3][]">
                                        <div class="tw3-range jsRange">
                                            <div class="tw3-range__label jsRangeLabel">
                                                {{{$field->name}}}
                                                @foreach($field->options as $options)   
                                                <span class="customfieldoptions"> @if($options->isSelected){{{$options->name}}} {{{$field->unit}}} @endif  </span>
                                                @endforeach         
                                            </div>
                                            <div class="tw3-range__actions">
                                                <a href="javascript://" class="jsDropdownDelete tw3-dropdown--custom__actions__delete"><i class="tw3-iconRemove"></i></a>
                                            </div>
                                            <div class="tw3-range__edit jsRangeEdit">
                                                Height <input type="number" name="advancedFilter[3][min]" min="130" max="230" class="jsMinInput jsAjaxifyDisabled" value="130" step="1"> to <input type="number" name="advancedFilter[3][max]" min="130" max="230" class="jsMaxInput jsAjaxifyDisabled" value="230" step="1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif
                                @endforeach
                            </div>
                            <div class="customoptionslink linkblue optionslink" data-toggle="tooltip" title="{{{trans('app.click_to_change_search_criteria')}}}">{{{trans('app.custom_options')}}}</div>
                            <!--  <div type="hidden"  id="search_fields" value="{{--{{{$field}}}--}}"/> -->
                            @for ($i = 0; ($i < count($custom_filter_data->search_fields) && $i < 3 ); $i++)
                            <div class="col-md-4 customoptions" style="display: none;">
                                
                                <div class="drop_body" style="min-height: auto">
                                    <i class="fa fa-times crossfilter" ></i>
                                    <select  name="field" id="customoption{{{$i}}}"  class="form-control customoption remove_boxshadow input_height input_max_width input_max_margin">
                                        <option value="0" data-options="">--{{{trans_choice('app.select', 0)}}}--</option>
                                        @foreach($custom_filter_data->search_fields as $field)
                                        @if(count($field->field_options) > 0)
                                        <!--                                         {{{$field}}} -->
                                        <option value="{{{$field->code}}}" name="{{{$field->name}}}" data-options="{{{$field->field_options}}}" data-value="{{{$field}}}">{{{trans('custom_profile.'.$field->code)}}}</option>
                                        @endif
                                        @endforeach  
                                    </select>
                                    <select multiple="multiple" class="test-2 form-control dropdowncustom remove_boxshadow input_height input_max_width input_max_margin" style="display: none;">
                                    </select>
                                    <div class="row ranges">
                                        <div class="col-sm-6 dropdowncustomrangemin" style="display: none;">
                                            <select class="form-control rangemin remove_boxshadow input_height input_max_width input_max_margin" >
                                            </select>
                                        </div>
                                        <div class="col-sm-6 dropdowncustomrangemax" style="display: none;">
                                            <select class="form-control  rangemax remove_boxshadow input_height input_max_width input_max_margin" >
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        @endif
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
<style>
    .btn-icons{
    min-height: 41px;
    float:left;
    }
    
</style>

<script>
	
	distance_unit='{{{$filter_distance_unit}}}';
	distance = '{{{$filter_distance}}}';
	distance_range_min ='{{{$filter_range_min}}}';
	distance_range_max= '{{{$filter_range_max}}}';
	non_superpower_range_enabled='{{{$filter_non_superpowers_range_enabled}}}';
	unit_name="{{trans('admin.'.$filter_distance_unit)}}";
	superpower_user='{{{$auth_user->isSuperPowerActivated()}}}';
</script>
	
<script src="@theme_asset('js/salvattore.js')"></script>
<script src="@theme_asset('js/people_nearby.js')"></script>

<script type="text/javascript">
    function initMap() { 
    	
    			var lat = parseFloat({{{$auth_user->profile->latitude}}});
                var lng = parseFloat({{{$auth_user->profile->longitude}}});
                var latlng = new google.maps.LatLng(lat, lng);
                var geocoder = geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        
                        
                        var result = results[0];
						//look for locality tag and administrative_area_level_1
						var city = "";
						var state = "";
						for(var i=0, len=result.address_components.length; i<len; i++) {
							var ac = result.address_components[i];
							if(ac.types.indexOf("locality") >= 0) city = ac.long_name;
							if(ac.types.indexOf("administrative_area_level_1") >= 0) state = ac.long_name;
						}
						//only report if we got Good Stuff
						if(city != '' && state != '') {
							$('#txtPlaces1').val(city+", "+state);
						}
                        
                        
                    }
                });
    
    
            google.maps.event.addDomListener(window, 'load', function () {
                var places = new google.maps.places.Autocomplete(document.getElementById('txtPlaces1'));
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
    function setLocationBoth () {
    	
    	var Request = {
			city   :$('#city').val(),
			country:$('#country').val(),
			lat    :$('#lat').val(),
			long   :$('#lng').val()	
    	};
    	
    	if (Request.city == "undefined") { 
    		toastr.error('{{{trans_choice("app.set_location_msg", 0)}}}');
    		return false;
    	}

    	$.post("{{{ url('user/profile/location/update') }}}",Request, function(data){
    		toastr.success('{{{trans_choice("app.set_location_msg", 1)}}}');
    		setTimeout(function(){
    			window.location.reload();
    		}, 1000);
    	});
    	
    }

    function setLocationProfile() {
    	var Request = {
			city   :$('#city').val(),
			country:$('#country').val(),
			lat    :$('#lat').val(),
			long   :$('#lng').val()	
    	};

    	if (Request.city == "undefined") { 
    		toastr.error('{{{trans_choice("app.set_location_msg", 0)}}}');
    		return false;
    	}
    	
    	$.post("{{{ url('/set_location') }}}",Request, function(data){
    		toastr.success('{{{trans_choice("app.set_location_msg", 2)}}}');
    		setTimeout(function(){
    			window.location.href = "{{{url('/peoplenearby')}}}";
    		}, 1000);
    	});
    }
</script>	
<script>
    // $('.rise_up').mouseover(function(){
    	
    	
    // 	$(this).attr('title','{{{trans('app.profile_raised')}}} ');//$(".timeago").text()
    // })
</script>

<script>
    $(document).ready(function(){
    	
    	if('{{{$auth_user->profile->prefer_online_status}}}')
    		$('#onlinefilter').val('{{{$auth_user->profile->prefer_online_status}}}');
		else
			$('#onlinefilter').val('all');
    	
    })
    
    
    $('#onlinefilter').on('change',function(){
	    
	    var Request={
		    prefer_online_status: $(this).val()
	    };
	    
	   
	    
	    $.post("{{{ url('/set-prefer-online-status') }}}",Request, function(data){
    		toastr.success(data.status);
    		setTimeout(function(){
    			window.location.href = "{{{url('/peoplenearby')}}}";
    		}, 1000);
    	});
	    
	    
	    
    })
</script>

<script>
	
	   @foreach($custom_filter_data->search_fields as $field)
    
     @if(count($field->field_options) > 0)
     
            @if($field->isSelected)
        
            
            
                selectedFields.push({'code':'{{{$field->code}}}','type':'{{{$field->on_search_type}}}'});
                
                @foreach($field->options as $options)   
                    
                    
                    
                    @if($options->isSelected)
                    
                        selectedOptions.push({'fieldcode':'{{{$field->code}}}','optioncode':'{{{$options->id}}}'});
                        
                    @endif  
                
                @endforeach 
            
                @endif
     @endif
    
    
      @endforeach
	
</script>



	
@endsection