@extends('admin.layouts.admin')
@section('content')
@parent
<style type="text/css">
    .admin_list_dropup{
    margin-left: -155px;
    background-color: #353E47;
    }
    .admin-create-div{
    width : 100%;
    }

    .admin-create-div .add-credit-package-text {
        display: inline;
        cursor: pointer;
    }

    .section-first-col{
    min-height: 0px;
    }
    
    .block-switch{
   margin-left: 108%;
    margin-top: -21px;
}
.row {
    margin-bottom: -10px;
}
.screenshot {
    margin-bottom: 25px;
}

.btn-addpackage {
    margin-right: 5px;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{{trans_choice('admin.edit',0)}}} {{{$landing_page}}} {{{trans('LandingPagesPluginAdmin.for')}}} {{{trans_choice('admin.language',1)}}} : {{{$edit_language}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p data-toggle="collapse" data-target="#content-section" class="add-credit-package-text" title ="Click to edit">{{{trans('LandingPagesPluginAdmin.content_section_title')}}}
                    <i class="fa fa-pencil-square-o"></i>
                    </p>
                   
                    <form method = "POST" id = "content-section-form" >
                    {{csrf_field()}}
                    <div class = "form-group collapse" id = "content-section">
                        @if(count($contentSections) > 0)
                            @foreach($contentSections as $key => $value)
                                <label class = "package-label">{{{$key}}}</label>
                                <textarea class="form-control input-border-custom" name = "{{{$key}}}">{{{$value}}}</textarea>
                            @endforeach
                        @else
                            <label class = "package-label">{{{trans('LandingPagesPluginAdmin.no_section')}}}</label>
                        @endif
                        <button type="button" id="content-section-save" class="btn btn-info btn-addpackage btn-custom">{{{trans('admin.save')}}}</button>
                    </div>
                    </form>
                </div>

                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p data-toggle="collapse" data-target="#image-section" class="add-credit-package-text" title ="Click to edit">{{{trans('LandingPagesPluginAdmin.image_section_title')}}}
                    <i class="fa fa-pencil-square-o"></i>
                    </p>
                    <form method="POST" id = "image-section-form">
                    {{csrf_field()}}
                    <div class = "form-group collapse" id = "image-section">
                        @if(count($imageSections) > 0)
                            @foreach($imageSections as $key => $arr)
                                <label class = "package-label">{{{$key}}}</label>
                                <div class = "row">
                                    <div class="col-md-6">
                                        <img src="{{{$arr['url']}}}"  width="100%" id = "image_{{{$key}}}" height="350px">
                                    </div>
                                    <div class="col-md-6">
                                        <label class = "package-label">URL</label>
                                        <input type="text" class="form-control input-border-custom" value ="{{{$arr['url']}}}" name = "{{{$key}}}" id = "image_input_{{{$key}}}">
                                        <br>
                                        <label class = "package-label">{{{trans('admin.upload')}}} {{{trans('admin.image')}}}</label>
                                        <form id = "form_{{{$key}}}">{{csrf_field()}}<input type = "file" name="image" id ="image_input_field_{{{$key}}}"><form>
                                        <button type="button" data-id = "{{{$key}}}" class="btn btn-info btn-addpackage btn-custom upload-image" style="position: relative;top: -40px;">{{{trans('admin.upload')}}}</button>
                                    </div>
                                </div><br>
                            @endforeach
                        @else
                            <label class = "package-label">{{{trans('LandingPagesPluginAdmin.no_section')}}}</label>
                        @endif
                        <button type="button" class="btn btn-info btn-addpackage btn-custom" id = "image-section-save" >{{{trans('admin.save')}}}</button>
                    </div>
                    </form>
                </div>

                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p data-toggle="collapse" data-target="#link-section" class="add-credit-package-text" title ="Click to edit">{{{trans('LandingPagesPluginAdmin.link_section_title')}}}
                    <i class="fa fa-pencil-square-o"></i>
                    </p>
                   
                    <form method="POST" id = "link-section-form">
                    {{csrf_field()}}
                    <div class = "form-group collapse" id = "link-section">
                        @if(count($linkSections) > 0)
                            @foreach($linkSections as $key => $arr)
                                <label class = "package-label">{{{$key}}}</label>
                                <input type="text" class="form-control input-border-custom" value ="{{{$arr['url']}}}" name = "{{{$key}}}" id = "link_input_{{{$key}}}">
                                   <br>
                            @endforeach
                        @else
                            <label class = "package-label">{{{trans('LandingPagesPluginAdmin.no_section')}}}</label>
                        @endif
                        <button type="button" class="btn btn-info btn-addpackage btn-custom" id = "link-section-save" >{{{trans('admin.save')}}}</button>
                    </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<link type="text/css" rel="stylesheet" href="{{{asset('admin_assets')}}}/css/jquery-te-1.4.0.css">
<script type="text/javascript" src="{{{asset('admin_assets')}}}/js/jquery-te-1.4.0.min.js" charset="utf-8"></script>
<script>
$(document).ready(function(){

    $("#link-section-save").on('click', function(){
        var links = $('#link-section-form').serializeArray();

        $.post("{{{url('admin/plugins/landing-pages-setting/link-section/save')}}}", {
            links : links, 
            edit_language: "{{{$edit_language}}}", 
            landing_page:"{{{$landing_page}}}", 
            language_file: "{{{$language_file}}}"
        }, function(res){

                if (res.status == 'success') {
                    toastr.success('{{{trans('LandingPagesPluginAdmin.link_section_save_success')}}}');
                } else {
                    toastr.error('{{{trans('LandingPagesPluginAdmin.link_section_save_error')}}}');
                }
        });
    });



    $('.upload-image').on('click', function(){

        var id = $(this).data('id');

        var form = $("#form_"+id)[0];
        var formData = new FormData(form);
        formData.append('image', $('#image_input_field_'+id)[0].files[0]);

        $.ajax({
        url: '{{{url("admin/plugins/landing-pages-setting/image-section/upload")}}}',
        type: 'POST',
        data: formData,
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            if (res.status == 'success') {
                    $("#image_"+id).attr("src", "");
                    $("#image_"+id).attr("src", res.url);
                    $("#image_input_"+id).val(res.url);
                    toastr.success('{{{trans('LandingPagesPluginAdmin.image_upload_success')}}}');
                    $("#image-section-save").trigger('click');
            } else {
                toastr.error('{{{trans('LandingPagesPluginAdmin.image_upload_error')}}}');
            }
    
        }
      });

    });



    $("#image-section-save").on('click', function(){
        var images = $('#image-section-form').serializeArray();

        $.post("{{{url('admin/plugins/landing-pages-setting/image-section/save')}}}", {
            images : images, 
            edit_language: "{{{$edit_language}}}", 
            landing_page:"{{{$landing_page}}}", 
            language_file: "{{{$language_file}}}"
        }, function(res){

                if (res.status == 'success') {
                    toastr.success('{{{trans('LandingPagesPluginAdmin.image_section_save_success')}}}');
                } else {
                    toastr.error('{{{trans('LandingPagesPluginAdmin.image_section_save_error')}}}');
                }
        });
    });




    $("#content-section-save").on('click', function(){
        var contents = $('#content-section-form').serializeArray();

        $.post("{{{url('/admin/plugins/landing-pages-setting/content-section/save')}}}", {
            contents : contents, 
            edit_language: "{{{$edit_language}}}", 
            landing_page:"{{{$landing_page}}}", 
            language_file: "{{{$language_file}}}"
        }, function(res){
                if (res.status == 'success') {
                    toastr.success('{{{trans('LandingPagesPluginAdmin.content_section_save_success')}}}');
                } else {
                    toastr.error('{{{trans('LandingPagesPluginAdmin.content_section_save_error')}}}');
                }
        });
    });

});
</script>
@endsection