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
    /* padding-left: 10%;
    padding-right: 10%;*/
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
        <h1 class="content-header-head">{{{trans('LandingPagesPluginAdmin.title')}}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">


                <form action = "" method = "POST" id = "set-landing-pages-form">
               {{csrf_field()}}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{{trans('LandingPagesPluginAdmin.heading')}}}</p>
                  <div class="form-group">
                        <label class = "package-label" >{{{trans('LandingPagesPluginAdmin.option_label')}}}</label>
                          <select name = "landing_page" class="form-control input-border-custom select-custom landing-option">   
                                @if(count($landing_pages) > 0)
                                    @foreach($landing_pages as $page)
                                        <option value = "{{{$page}}}" @if($custom_landing_page == $page) selected @endif>{{{$page}}}</option>
                                    @endforeach
                                @endif
                                <!-- <option value = "-1" @if($custom_landing_page == '' || $custom_landing_page == '-1') selected @endif>{{{trans('LandingPagesPluginAdmin.default_option')}}} Landing Page</option> -->
                        </select>


                        @if($custom_landing_page != '' && $custom_landing_page != '-1')
                            <label class = "package-label edit-language" >Edit Landing Page For</label>
                            <select class="form-control input-border-custom select-custom edit-language" name = "edit-language">   
                                @if(count($supported_languages) > 0)
                                    @foreach($supported_languages as $lang)
                                        <option value = "{{{$lang}}}">{{{$lang}}}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </div>
                  <button type="button" id = "set-landing-pages-btn" class="btn btn-info btn-addpackage btn-custom">{{{trans('admin.save')}}}</button>
                  @if($custom_landing_page != '' && $custom_landing_page != '-1')
                    <button type="button" id = "edit-btn" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.edit', 0)}}}</button>
                  @endif
               </div>
            </form>

            <div class = "col-sm-6 col-md-6 screenshot">
                <label class = "package-label" >{{{trans('LandingPagesPluginAdmin.reg_screenshot')}}}</label>
                <img class = "img-responsive registration-image"></div>

            <div class = "col-sm-6 col-md-6 screenshot">
                <label class = "package-label" >{{{trans('LandingPagesPluginAdmin.sig_screenshot')}}}</label>
                <img class = "img-responsive signin-image"></div>
            </div>


            <div class= "row">            
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p class="add-credit-package-text">{{{trans('LandingPagesPluginAdmin.video_mode_title')}}}</p>
                    <div style="display:inline-flex">
                        <label class = "package-label" >{{{trans('LandingPagesPluginAdmin.video_mode_enable_text')}}}</label>
                        <label class="switch" style="margin-left: 11px;top: -4px;">
                           <input class="switch-input video-mode" type="checkbox" @if($landing_page_video_mode == 'true') checked @endif>
                           <span class="switch-label"></span> 
                           <span class="switch-handle"></span> 
                        </label>
                    </div>
                </div>
            </div>

            
            <div class= "row" id="video-section" style="@if($landing_page_video_mode == 'false')display: none @endif" >            
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    <p class="add-credit-package-text">{{{trans('LandingPagesPluginAdmin.video_setting_title')}}}</p>
                    <div class="form-group" style="background: #2b3339;padding: 11px;">

                    <div class="progress video-upload-progress" style="padding-left:0px;display:none" >
                      <div class="progress-bar" role="progressbar" aria-valuenow="0"
                      aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span class="">100% Complete</span>
                      </div>
                    </div>



                        <label class="package-label">{{{trans("LandingPagesPluginAdmin.upload_video_text")}}}</label>
                        <input type="text" value="{{{$landing_page_video_url}}}" placeholder="Enter Video Url" class="form-control  input-border-custom" id = "video_url">
                        <input type="text" value="{{{$landing_page_video_poster_url}}}" placeholder="Enter Video Poster Url" class="form-control  input-border-custom" id = "video_poster_url">

                        <div style="color: white;text-align: center;padding: 13px 0px 2px 0px;">{{{trans("LandingPagesPluginAdmin.or")}}}</div>

                        <label class="package-label">{{{trans("LandingPagesPluginAdmin.upload_video_local_text")}}}(.mp4)</label><br>
                        
                        <div class="input-group" style="width:100%">
                            <form id = "form_video">
                            <input type="file" accept=".mp4" name="video" class="form-control input-border-custom bot-input-custom">
                            <button type="submit" class="btn btn-info btn-addpackage btn-custom upload-video">{{{trans('admin.upload')}}} {{{trans("LandingPagesPluginAdmin.video")}}}</button>
                            </form>
                        </div><br>
                        <label class="package-label">{{{trans('admin.upload')}}} {{{trans("LandingPagesPluginAdmin.poster")}}}(.jpg)</label><br>
                        <div class="input-group" style="width:100%">
                            <form id = "form_poster_image">
                            <input type="file" accept=".jpg" class="form-control input-border-custom bot-input-custom" id = "input_poster_image">
                            <button type="button" class="btn btn-info btn-addpackage btn-custom upload-poster-image">{{{trans('admin.upload')}}} {{{trans("LandingPagesPluginAdmin.poster")}}}</button>
                            </form>
                        </div>

                        <button type="button" id ="save-video-setting" class="btn btn-info btn-addpackage btn-custom" style="float:initial">{{{trans('admin.save')}}}</button>
                    </div>
                    <label class = "package-label" >{{{trans("LandingPagesPluginAdmin.video_preview")}}}</label>                    
                    <video loop muted autoplay poster="{{{$landing_page_video_poster_url}}}" style="width:100%" id="video-preview">                        
                        <!-- <source src="http://dev2.slicejack.com/fullscreen-video-demo/video/big_buck_bunny.webm" type="video/webm"> -->
                        <source src="{{{$landing_page_video_url}}}" type="video/mp4" >
                        <!-- <source src="http://dev2.slicejack.com/fullscreen-video-demo/video/big_buck_bunny.ogv" type="video/ogg"> -->
                    </video>
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

        $('#form_video').submit(function(e){

            $('.video-upload-progress').show();
            $('.video-upload-progress > div').css('width',"0%");
            $('.video-upload-progress > div > span').html("0%").css('text-align', 'center');
            e.preventDefault();
            var formData = new FormData($(this)[0]);

            $.ajax({
                url: '{{{url('admin/plugins/landing-pages-setting/viedeo-settings/upload-video')}}}',
                type: 'POST',
                data: formData,
                async: true,
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    
                    if(res.status == 'success') {

                        toastr.success("{{{trans('LandingPagesPluginAdmin.video_upload_success')}}}");
                        $("#video_url").val(res.url);
                        $('.video-upload-progress').hide();
                        $('#save-video-setting').trigger('click');
                    } else {
                        toastr.error("{{{trans('LandingPagesPluginAdmin.video_upload_failed')}}}");
                        $('.video-upload-progress').hide();

                    }
                },
                xhr: function() {
                    var xhr = $.ajaxSettings.xhr();
                    xhr.upload.onprogress = function(e) {
                        var percent = Math.floor(e.loaded / e.total *100);
                        
                        $('.video-upload-progress > div').css('width', percent+"%");
                        $('.video-upload-progress > div > span').html(percent+"%").css('text-align', 'center');
                    };
                    return xhr;
                },
            });

        });




        $('.upload-poster-image').on('click', function(){

            var id = $(this).data('id');

            var form = $("#form_poster_image")[0];
            var formData = new FormData(form);
            formData.append('poster_image', $('#input_poster_image')[0].files[0]);

            $.ajax({
            url: '{{{url("/admin/plugins/landing-pages-setting/viedeo-settings/upload-poster-image")}}}',
            type: 'POST',
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.status == 'success') {
                    $("#video_poster_url").val(res.url);
                        toastr.success('{{{trans('LandingPagesPluginAdmin.video_poster_image_success')}}}');
                        $('#save-video-setting').trigger('click');
                } else {
                    toastr.error('{{{trans('LandingPagesPluginAdmin.video_poster_image_error')}}}');
                }
        
            }
          });

        });





        $("#save-video-setting").on('click',function(){

            var landing_page_video_url = $("#video_url").val();
            var landing_page_video_poster_url = $("#video_poster_url").val();

            $.post("{{{url('/admin/plugins/landing-pages-setting/viedeo-settings/save')}}}",
                {_token:"{{{csrf_token()}}}", landing_page_video_url:landing_page_video_url, landing_page_video_poster_url:landing_page_video_poster_url}, 
                function(res){
                    if(res.status == 'success') {
                        $("#video-preview").attr('poster', '');
                        $("#video-preview").attr('poster', landing_page_video_poster_url);

                        $("#video-preview > source").attr('src', '');
                        $("#video-preview > source").attr('src', landing_page_video_url);

                        var myVideo = $("#video-preview");
                        myVideo.src = landing_page_video_url;
                        myVideo.load();

                        toastr.success("{{{trans('LandingPagesPluginAdmin.video_setting_save_success')}}}");
                    }
                    else
                        toastr.error("{{{trans('LandingPagesPluginAdmin.video_setting_save_error')}}}");
                });

        }); 


        $(".video-mode").on('change', function(){
            var landing_page_video_mode = 'false';
            if ($(this).is(':checked')) {
                landing_page_video_mode = 'true';
            }

            $.post("{{{url('/admin/plugins/landing-pages-setting/viedeo-mode/save')}}}",
                {_token:"{{{csrf_token()}}}", landing_page_video_mode:landing_page_video_mode}, 
                function(res){
                    if(res.status == 'success') {
                        toastr.success("{{{trans('LandingPagesPluginAdmin.video_mode_save_success')}}}");
                        $("#video-section").toggle();
                    }
                    else
                        toastr.error("{{{trans('LandingPagesPluginAdmin.video_mode_save_error')}}}");
                });
        });






        $('#edit-btn').on('click', function(event){
            var landing_page_name = $('select[name=landing_page]').find('option:selected').val();
            var edit_language = $('select[name=edit-language]').find('option:selected').val();

            var url = '{{{url('/admin/plugins/landing-pages-setting/edit')}}}';
            url += '?landing-page='+landing_page_name+'&language='+edit_language;

            window.open(url, '{{{trans('LandingPagesPluginAdmin.edit_landing_page')}}}'); 
        });

       

        $('.landing-option').on('change', function(event){

            var selected = $(this).find('option:selected').val();
            
            var url = "{{{url('plugins/LandingPagesPlugin')}}}/"+selected+"/screenshots";

            if(selected == '-1') {
                $('.screenshot').hide();
                $("#edit-btn").hide();
                $(".edit-language").hide();
            } else {
                $('.screenshot').show();
                $("#edit-btn").show();
                $(".edit-language").show();
            }

            $('.registration-image').attr("src", '');
            $('.registration-image').attr("src", url+"/registration.jpg");


            $('.signin-image').attr("src", '');
            $('.signin-image').attr("src", url+"/signin.jpg");

        }).change();


    });
    

    $('#set-landing-pages-btn').on('click', function(){
        var data = $('#set-landing-pages-form').serializeArray();
        $.post("{{{url('/admin/plugins/landing-pages-setting/save')}}}", data, function(response)
        {

            if(response.status == 'success')
                toastr.success(response.message);
            else if (response.status == 'error')
                toastr.error(response.message);

        });
    });
    
</script>
@endsection