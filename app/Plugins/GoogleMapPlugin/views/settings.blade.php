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
        <h1 class="content-header-head">{{{trans('GoogleMapPlugin.heading')}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" id = "set-google-map-form">
                    {{csrf_field()}}
                    <input type = "hidden" name = "_task" value = "createAdmin">
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{{trans('GoogleMapPlugin.title')}}}</p>
                        <div class="form-group">
                            <label class="package-label">{{{trans('GoogleMapPlugin.label')}}}</label>
                            <input type="text"  placeholder="{{{trans('GoogleMapPlugin.placeholder')}}}" id = "google-map-key" name = "google_map_key" value = "{{{$google_map_key}}}" class="form-control  input-border-custom">
                        </div>
                        <button type="submit" id = "set-google-map-key-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                    </div>
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
    $('#set-google-map-key-btn').click(function(e){
        e.preventDefault();
    
        var google_key = $('#google-map-key').val();
        
    
        var data = $('#set-google-map-form').serializeArray();
        $.post("{{{url('/admin/plugin/google-map/save')}}}", data, function(response){
    
            if(response.status == 'success')
                toastr.success(response.message);
        });
    
    });
    
       
</script>
@endsection