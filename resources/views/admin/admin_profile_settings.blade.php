@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .add-interest-div 
    {
        width : 100%;
    }
    .user-section-first-col 
    {
        min-height: 0px;
    }
    
    .profile-show-hide-settings form > .form-group 
    {
        color: white;
        border: 1px solid white;
        padding: 8px 5px 5px 8px;
        margin-bottom: 5px;
    }

    .profile-show-hide-settings form > .form-group > label 
    {
        margin-left: 5px;
    }
    
</style>
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header content-header-custom">
    <h1 class="content-header-head">{{{trans('admin.profile_settings')}}}</h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="col-md-12 section-first-col user-section-first-col">
        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div profile-show-hide-settings">
                <p class="add-credit-package-text">{{trans('admin.profile_fields_show_hide_settings_title')}}</p>
                
                <form method="post" id = "profile-show-hide-settings-form" action="{{url('admin/settings/profile/save/profile-fields-mode')}}">
                    {{csrf_field()}}
                    <div class="form-group">
                        <input type="checkbox" data-name="profile_map_show_mode" @if($profile_map_show_mode=='true') checked @endif>
                        <label>{{{trans('admin.profile_settings_map_title')}}}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" data-name="profile_visitor_details_show_mode" @if($profile_visitor_details_show_mode=='true') checked @endif>
                        <label>{{{trans('admin.profile_settings_visitor_title')}}}</label>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" data-name="profile_interests_show_mode" @if($profile_interests_show_mode=='true') checked @endif>
                        <label>{{{trans('admin.profile_settings_interests_title')}}}</label>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" data-name="profile_about_me_show_mode" @if($profile_about_me_show_mode=='true') checked @endif>
                        <label>{{{trans('admin.profile_settings_about_me_title')}}}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" data-name="profile_score_show_mode" @if($profile_score_show_mode=='true') checked @endif>
                        <label>{{{trans('admin.profile_settings_score_title')}}}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" data-name="hide_popularity" @if($hide_popularity=='true') checked @endif>
                        <label>{{{trans('admin.hide_popularity_title')}}}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" data-name="disable_interest_edit" @if($disable_interest_edit=='true') checked @endif>
                        <label>{{{trans('admin.disable_interest_edit_title')}}}</label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" data-name="auto_browser_geolocation" @if($auto_browser_geolocation=='true') checked @endif>
                        <label>{{{trans('admin.auto_browser_geolocation_title')}}}</label>
                    </div>
         
                    <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 1)}}</button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{trans('admin.filter_only_superpowers_title')}}}</p>
                <div class="form-group">
                    <select name="advance_filter_only_superpowers" class="form-control input-border-custom select-custom">
                        <option value="true" @if($advance_filter_only_superpowers=='true') selected @endif>{{{trans('admin.filter_only_superpowers_option_one')}}}</option>
                        <option value="false" @if($advance_filter_only_superpowers!='true') selected @endif>{{{trans('admin.filter_only_superpowers_option_two')}}}</option>
                    </select>
                </div>
                <button type="button" class="btn btn-info btn-addpackage btn-custom" id = "filter-setting-save-btn">{{trans_choice('admin.save', 1)}}</button>
            </div>
        </div>

        <form action="{{url('admin/settings/profile/save/filter-range')}}" method="POST">
        {{csrf_field()}}
        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{trans('admin.filter_range_settings_title')}}}</p>
                <div class="form-group">
                    <label class="package-label">{{trans('admin.distance_unit_title')}}</label>
                    <select name="filter_distance_unit" class="form-control input-border-custom select-custom">
                        <option value="km" @if($filter_distance_unit == 'km') selected @endif>{{{trans('admin.km')}}}</option>
                        <option value="mile" @if($filter_distance_unit == 'mile') selected @endif>{{{trans('admin.mile')}}}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="package-label">{{trans('admin.distance_title')}}</label>
                    <input type="text" value="{{$filter_distance}}" class="form-control input-border-custom" name="filter_distance">
                </div>
                <div class="form-group">
                    <label class="package-label">{{trans('admin.range_for_non_superpowers_title')}}</label>
                    <div style="color:white">
                       
                            <label>{{trans('admin.min')}}
                                <input type="text" class="form-control input-border-custom" name="filter_range_min" value = "{{$filter_range_min}}">
                                <label>{{{trans('admin.km')}}}</label>
                            </label>
                            <span style="font-size: 20px;position: relative;top: -23px;margin-right: 5px;margin-left: 5px;">-</span>
                            <label>{{trans('admin.max')}}
                                <input type="text" class="form-control input-border-custom" name="filter_range_max" value="{{$filter_range_max}}">
                                <label>{{{trans("admin.{$filter_distance_unit}")}}}</label>
                            </label>
                    </div>
                    <div class="form-group" style="color:white">
                        <input type="checkbox" name="filter_non_superpowers_range_enabled" @if($filter_non_superpowers_range_enabled=='true') checked @endif>
                        <label>{{trans('admin.filter_non_superpower_range_enable_title')}}</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-info btn-addpackage btn-custom" >{{trans_choice('admin.save', 1)}}</button>
            </div>
        </div>
        </form>
    </div>   
        
</section>
</div>
@endsection
@section('scripts')
<script>

$(document).ready(function(){

    var csrf_token = "{{{csrf_token()}}}";

    


    $("#profile-show-hide-settings-form").on("submit", function(e){
        e.preventDefault();

        var checkBoxes = $("#profile-show-hide-settings-form input:checkbox");
        checkBoxes.each(function(){
            console.log($(this).data('name'));
            var v = $(this).is(':checked') ? 'true' : 'false';
            $(this).after('<input type="hidden" name="'+$(this).data('name')+'" value="'+v+'" />');
        });

        $('#profile-show-hide-settings-form')[0].submit();

    });


    $("#filter-setting-save-btn").on("click", function(){

        var filter_value = $("select[name='advance_filter_only_superpowers']").val();
        $.post("{{{url('admin/settings/profile/save/advance-filter-setting')}}}", {_token : csrf_token, mode : filter_value},
        function(res){
            if (res.status == "success")
                toastr.success('{{{trans_choice('admin.set_status_message',0)}}}');
            else 
                toastr.success('{{{trans_choice('admin.set_status_message',1)}}}');
        });
    });


});    
    
    
</script>
@endsection