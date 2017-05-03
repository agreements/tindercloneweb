@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .admin-create-div{
    width : 100%;
    }
    .row {
    background-color: #38414A;
    }
    .section-first-col{
    min-height: 0px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans('admin.maxmind_settings_header')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form >
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('admin.maxmind_settings_form_title')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.maxmind_app_id_title')}}</label>
                            <input type="text"  placeholder="" id = "appId" value = "{{$maxmind_app_id}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.maxmind_license_key_title')}}</label>
                            <input type="text" placeholder="" id = "licenseKey" value = "{{$maxmind_license_key}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{{trans('admin.maxmind_enable_title')}}}</label>
                            <label class="switch">
                            <input class="switch-input maxmind-enable-switch" type="checkbox" @if($maxmind_enabled) checked @endif/>
                            <span class="switch-label" ></span> 
                            <span class="switch-handle"></span> 
                            </label>
                        </div>
                        <button type="button" id = "save-maxmind-settings" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
                </form>
            </div>
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script>
    $('#save-maxmind-settings').click(function(){
    
        var maxmind_app_id = $('#appId').val();
        var maxmind_license_key = $('#licenseKey').val();
        var maxmind_enable = ($('.maxmind-enable-switch').is(':checked')) ? 'true' : 'false';
            
        var data = {
            maxmind_app_id : maxmind_app_id,
            maxmind_license_key : maxmind_license_key,
            maxmind_enable : maxmind_enable,
            _token : "{{csrf_token()}}"
        };

        
        $.post("{{{url('admin/settings/maxmind')}}}", data, function(response){
    
            if(response.status == 'success') {
                toastr.success('{{trans_choice('admin.set_status_message', 0)}}');
            }
    
        });
    
    });
    
       
</script>
@endsection