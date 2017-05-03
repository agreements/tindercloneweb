@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header content-header-custom">
    <h1 class="content-header-head">{{trans_choice('admin.general_settings', 1)}}</h1>
</section>
<!-- Main content -->
<section class="content">
    <div class="col-md-12 section-first-col user-section-first-col">
        <form action = "#" method = "POST" id = "set-title-form">
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_title', 1)}}</p>
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.enter_title', 1)}}</label>
                        <input type="text" value = "@if($title){{{$title}}}@endif" placeholder="{{trans_choice('admin.enter_title', 1)}} {{trans_choice('admin.text', 1)}}" id = "title" name = "title" class="form-control  input-border-custom">
                    </div>
                    <button type="button" id = "set-title" class="btn btn-info btn-addpackage btn-custom btn-set">{{trans_choice('admin.set', 1)}}</button>
                </div>
            </div>
        </form>
            
        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{trans('admin.enable_debug_mode_title')}}</p>
                <div class="form-group">
                    <label class="switch">
                        <input class="switch-input debug-mode-switch" type="checkbox" @if($debug_mode == 'true') checked @endif/>
                        <span class="switch-label"></span> 
                        <span class="switch-handle"></span>
                    </label>
                </div>
            </div>
        </div>
        
        <form method = "POST" id="set-secure-mode-form">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans('admin.enable_secure_mode_title')}}</p>
                    <div class="form-group">
                        <label class="package-label">{{trans('admin.domain_name_title')}}</label>
                        <input type="text" placeholder="{{trans('admin.enter_domain_name_with_protocol_placeholder')}}" name = "domain" class="form-control input-border-custom" value="{{$domain}}">
                    </div>
                    <div class="form-group">
                        <label class="switch">
                            <input class="switch-input" type="checkbox" name="secure_mode" @if($secure_mode == 'true') checked @endif> 
                            <span class="switch-label"></span> 
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                    <button type="button" class="btn btn-info btn-addpackage btn-custom btn-set" id = "set-secure-mode">{{trans_choice('admin.save', 0)}}</button>
                </div>
            </div>
        </form>
       
        <form action = "{{{ url('/admin/generalSettings/saveMaxFileSize') }}}" method = "POST" id = "set-filesize-form">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_max_file_size', 0)}}</p>
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.enter_file_size', 0)}}(MB)</label>
                        <input type="number" value = "@if($max_file_size){{{$max_file_size}}}@endif" placeholder="{{trans_choice('admin.enter_file_size', 1)}}" id = "title" name = "max_file_size" class="form-control  input-border-custom">
                    </div>
                    <button type="submit" id = "set-title" class="btn btn-info btn-addpackage btn-custom btn-set">{{trans_choice('admin.save', 0)}}</button>
                </div>
            </div>
        </form>
        <form action = "{{{ url('/admin/generalSettings/logo') }}}" method = "POST" id = "set-logo-form" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_logo', 0)}}</p>
                    <div class="form-group">
                        <label  class="package-label">{{{trans('admin.current')}}} {{{trans('admin.logo')}}}</label>
                        <img src="{{{asset('uploads/logo')}}}/{{{$logo}}}" id = "logo" /><br><br>
                        <label class="package-label">{{trans_choice('admin.upload_logo', 1)}}</label>
                        <label class="input-label-custom"><input type="file" name="logo" id="fileInput" class="input-custom-style"/></label>
                        <label><input type="submit" id = "set-logo-btn" class="btn btn-info btn-upload-custom" value="{{trans_choice('admin.upload', 1)}}" style="display:none"/></label>
                    </div>
                    <!-- <button type="submit" id = "set-logo-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.upload', 1)}}</button> -->
                    <div id="logo-progress" class="progress" style="display:none">
                        <div id="progress-logo-bar" class="progress-bar progress-bar-striped bar">
                        </div >
                        <div id="logo-percent" class="percent">0%</div >
                    </div>
                </div>
            </div>
        </form>
        <form action = "{{{ url('/admin/generalSettings/outerlogo') }}}" method = "POST" id = "set-outerlogo-form" enctype="multipart/form-data">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_logo', 1)}}</p>
                    <div class="form-group">
                        <label  class="package-label">{{{trans('admin.current')}}} {{{trans('admin.logo')}}}</label>
                        <img src="{{{asset('uploads/logo')}}}/{{{$outerlogo}}}" id = "outerlogo" /><br><br>
                        <label class="package-label">{{trans_choice('admin.upload_logo', 1)}}</label>
                        <label class="input-label-custom"><input type="file" name="outerlogo" id="fileInputOuter" class="input-custom-style"/></label>
                        <label><input type="submit" id = "set-outerlogo-btn" class="btn btn-info btn-upload-custom" value="{{trans_choice('admin.upload', 1)}}" style="display:none"/></label>
                    </div>
                    <!-- <button type="submit" id = "set-logo-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.upload', 1)}}</button> -->
                    <div id="outerlogo-progress" class="progress" style="display:none">
                        <div id="progress-outerlogo-bar" class="progress-bar progress-bar-striped bar">
                        </div >
                        <div id="outerlogo-percent" class="percent">0%</div >
                    </div>
                </div>
            </div>
        </form>
        <form action = "{{{ url('/admin/generalSettings/favicon') }}}" method = "POST"enctype="multipart/form-data" id = "set-favicon-form">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_favicon', 1)}}</p>
                    <div class="form-group">
                        <label  class="package-label">{{{trans('admin.current')}}} {{{trans('admin.favicon')}}}</label>
                       <img src="{{{asset('uploads/favicon')}}}/{{{$favicon}}}" id = "favicon" onerror="this.src=''" /><br><br>
                       
                        <label class="package-label">{{trans_choice('admin.upload_favicon', 1)}}</label>
                        <label class="input-label-custom"><input type="file" name="favicon" id="fileInput1" class="input-custom-style"/></label>
                        <label><input type="submit" id = "set-logo-btn1" class="btn btn-info btn-upload-custom" value="{{trans_choice('admin.upload', 1)}}" style="display:none"/></label>
                    </div>
                    <!-- <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.upload', 1)}}</button> -->
                    <div id="favicon-progress" class="progress" style="display:none;">
                        <div id="progress-favicon-bar" class="progress-bar progress-bar-striped bar">
                        </div >
                        <div id="favicon-percent" class="percent">0%</div >
                    </div>
                </div>
            </div>
        </form>
        <form action = "{{{ url('/admin/generalSettings/backgroundimage') }}}" method = "POST" enctype="multipart/form-data" id = "set-background-form">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <div class='loader'></div>
                    <p class="add-credit-package-text">{{trans_choice('admin.set_background', 1)}}</p>
                    <div class="form-group">
                        <label  class="package-label">{{{trans('admin.current')}}} {{{trans('admin.background_image')}}}</label>
                        <img src="{{{asset('uploads/backgroundimage')}}}/{{{$backgroundimage}}}" style="width:53%;height:35%;" id = "backgroundimage" /><br><br>
                        <label class="package-label">{{trans_choice('admin.upload_background', 1)}}</label>
                        <label class="input-label-custom"><input type="file" name="backgroundimage" id="fileInput2" class="input-custom-style"/></label>
                        <label><button type="submit" id = "set-logo-btn2" class="btn btn-info btn-upload-custom"><i class="fa fa-upload"></i></button>
                        </label>
                        <button id = "delete-background-image" title = "{{{trans('admin.del_bg_tooltip')}}}" type = "button" class = "btn btn-info btn-upload-custom"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <div id="0" class="accordion-body">
            <div class="row">
                <div class="accordion-inner col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{trans_choice('admin.sex',1)}}} {{{trans('admin.menu_settings')}}}</p>
                    <table class="table table-condensed">
                        <thead>
                            <tr>                                
                                <th>{{{trans('admin.default_gender_options')}}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <ul style="list-style: none;padding-left:0px">
                                        @foreach($gender->field_options as $option)
                                        <li>
                                            {{{ $option->name }}}
                                            <form action="{{{url('admin/profilefields/delete_option')}}}"  class="form-horizontal" method="post" style="display: inline-block">
                                                {!! csrf_field() !!}
                                                <input type="hidden" name="option_id" value="{{{ $option->id }}}" />
                                                @if(count($gender->field_options) > 1)<button  type="submit" style="background:#38414A;border: none "><i class="fa fa-trash" style="color: white"></i></button>@endif
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                    <form action="{{{url('/admin/profilefields/add_fieldoption')}}}"  class="form-horizontal" method="post">
                                        {!! csrf_field() !!}
                                        <input type="text" name="optiontitle" style="color:black;" placeholder="{{{trans('admin.add_new_gender')}}}" />
                                        <input type="hidden" name="field" value="{{{ $gender->id }}}" />
                                        <input type="submit" class="btn btn-success" style="padding: 2px 9px 2px 9px;margin-left: 8px;cursor: pointer"  value="+" />
                                    </form>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display:flex">
                                    {{{trans('admin.on_registration')}}}
                                    <label class="switch" style="margin-left:5px">
                                    <input class="switch-input edit-registeration-switch" data-item-id="{{{ $gender->id }}}" data-item-name = "register" type="checkbox" @if($gender->on_registration == 'yes') checked @endif />
                                    <span class="switch-label"></span> 
                                    <span class="switch-handle"></span>
                                    </label>
                                    {{{trans('admin.on_search')}}}
                                    <label class="switch" style="margin-left:5px">
                                    <input class="switch-input edit-search-switch" data-item-id="{{{ $gender->id }}}" data-item-name = "search" type="checkbox" @if($gender->on_search == 'yes') checked @endif/>
                                    <span class="switch-label"></span> 
                                    <span class="switch-handle"></span>
                                    </label>
                                    </div>                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <div class='loader'></div>
                <p class="add-credit-package-text">{{{trans("admin.set_default_prefer_genders")}}}</p>
                <div class="form-group" style="display:flex">
                    <input type = "hidden" id = "default_prefered_genders" value = "{{{join(',', $default_prefered_genders)}}}">
                    @foreach($gender->field_options as $option)
                        <label class="package-label" style="margin-top:2px; margin-right:10px;">{{{trans('custom_profile.'.$option->code)}}}</label>
                        <label class="switch">
                            <input class="switch-input default-prefer-genders" data-item-id="{{{ $gender->id }}}" data-gender = "{{{$option->code}}}" type="checkbox" 
                            @if(in_array($option->code, $default_prefered_genders))
                            checked
                            @endif/>
                            <span class="switch-label"></span> 
                            <span class="switch-handle"></span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>



        <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{trans('admin.upload_photo_setting')}}}</p>
                <div class="form-group" style="display:flex">
                        <label class="package-label" style="margin-top:2px; margin-right:10px;">{{{trans('admin.set_very_first_profile_picture')}}}</label>
                        <label class="switch">
                            <input class="switch-input upload-photo-switch" type="checkbox" @if($make_profile_picture == 'true') checked @endif>
                            <span class="switch-label"></span> 
                            <span class="switch-handle"></span>
                        </label>
                   
                </div>
            </div>
        </div>





        @foreach($gender->field_options as $option)
        <form action = "{{{ url('/admin/generalSettings/setDefaultImage') }}}" method = "POST" enctype="multipart/form-data" id = "set-background-form">
            {!! csrf_field() !!}
            <div class="row">
                <div class="col-md-10 add-creditpackage-col add-interest-div">
                    <p class="add-credit-package-text">{{trans_choice('admin.set_default', 3)}} {{{$option->name}}} {{{trans('admin.image')}}}</p>
                    <div class="form-group">
                        @if($pics[$option->code])
                        <img src="{{{asset('uploads/others')}}}/{{{$pics[$option->code]}}}" style="width:50px;height:50px;" id = "{{{$option->code}}}" />
                        @else
                        <p style="color:white">{{{trans('admin.no_image')}}}</p>
                        @endif
                        <label class="input-label-custom"><input type="file" name="default_{{{$option->code}}}" id="fileInput2" class="input-custom-style"/></label>
                        <button type="submit" class = "btn btn-info btn-upload-custom">{{{trans('admin.save')}}}</button>
                    </div>
                </div>
            </div>
        </form>
        @endforeach
</section>
</div>
@endsection
@section('scripts')
<script>

    $("#set-secure-mode").on('click', function(){

        var formData = $("#set-secure-mode-form").serializeArray();
        
        $.post("{{{ url('admin/generalSettings/enable-disable-secure-mode') }}}",
        formData, function(res){

                if(res.status == "success") {
                    toastr.success(res.success_text);
                }
                else if(res.status == "error")
                {
	                toastr.error(res.error_text);
                }

            });

    });



    $(".upload-photo-switch").on('change', function(){
        if($(this).is(":checked")) {
            make_profile_picture = 'true';
        } else {
            make_profile_picture = 'false';
        }

        url = "{{{url('/admin/generalSettings/upload-photo-setting-save')}}}";
        $.post(url, {_token: "{{{csrf_token()}}}", make_profile_picture:make_profile_picture}, function(res){
            if (res.status == 'success') {
                toastr.success('{{{trans_choice('admin.set_status_message',0)}}}');
            } else {
                toastr.error('{{{trans_choice('admin.set_status_message',1)}}}');
            }
        });
    });

    $(".debug-mode-switch").on("change", function(){

        var debug_mode = $(this).is(':checked') ? 'true' : 'false';
        $.post("{{{url('admin/generalSettings/enable-disable-debug-mode')}}}", 
            {_token: "{{{csrf_token()}}}", debug_mode:debug_mode}, function(){

            });

    });


    $(".default-prefer-genders").on('click', function(){

        var genders = $("#default_prefered_genders").val();
        var genders_array = genders.split(',');
        var gender = $(this).data('gender');

        if($(this).is(":checked")) {
            genders_array.push(gender);
        } else {
            genders_array = genders_array.filter(function(e){
                return e !== gender;
            });
        }
           
        if(genders_array[0] == "")
            $("#default_prefered_genders").val(gender);   
        else
            $("#default_prefered_genders").val(genders_array.join(','));


        $.post('{{{url('admin/generalsettings/save-prefer-genders')}}}',
            {_token: "{{{csrf_token()}}}", perfer_genders: $("#default_prefered_genders").val()},
            function(res){
                if(res.status == 'success') {
                    toastr.success(res.message);
                } else if(res.status == 'error') {
                    toastr.error(res.message);
                }
            });

    });




    $(".edit-registeration-switch").change(function(){
        
          var field = $(this).data('item-name');
          
          
          var id= $(this).data('item-id');
          
          var checked= '';
        
          if(this.checked){
          
                checked = 'yes';
              
          }
          else {
            
                //$('#'+field).val("no");
                checked = 'no';
            
          }
          
          
            data={id:id,register:checked};
                            
                    
                        
                        $.ajax({
                                      type: "POST",
                                      url: "{{{ url('/admin/profilefields/edit_field') }}}",
                                      data: data,
                                      success: function(msg){
                                            
                                           toastr.success('Saved');                                     
                                            
                                      },
                                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            toastr.error("{{{trans_choice('app.error',1)}}}");
                                      }
                                      
                       });
    
        
        });
        
        
        
         $(".edit-search-switch").change(function(){
        
          var field = $(this).data('item-name');
          
            var id= $(this).data('item-id');
          
          var checked= '';
        
          if(this.checked){
          
                checked = 'yes';
              
          }
          else {
            
                //$('#'+field).val("no");
                checked = 'no';
            
          }
          
          
            data={id:id,search:checked};
                            
                    
                        
                        $.ajax({
                                      type: "POST",
                                      url: "{{{ url('/admin/profilefields/edit_field') }}}",
                                      data: data,
                                      success: function(msg){
                                            
                                           toastr.success('Saved');                                     
                                            
                                      },
                                      error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            toastr.error("{{{trans_choice('app.error',1)}}}");
                                      }
                                      
                       });
    
        
        });
    
     
</script>
<script>
    if('{{{$backgroundimage}}}'=='')
    {
    
            $("#backgroundimage").hide();
            $("#delete-background-image").hide();
    }
    
    
    
    
    
    $("#delete-background-image").on('click', function(){
    
      $.post("{{{url('admin/generalsettings/delete-background-image')}}}",{_token:'{{{csrf_token()}}}'}, function(res){
        if(res.status == "success") {
          toastr.success(res.message);
          $("#backgroundimage").hide();
          $("#delete-background-image").hide();
    
    
        } else {
          toastr.error(res.message);
        }
      });
    
    });
    
    
    
    
    
    
    $("#set-background-form").submit(function(e){
     
    
      var bar = $('#progress-bg-bar');
     var percent = $('#bg-percent');
     var status = $('#status');
     var progress=$("#bg-progress");
      //disable the default form submission
      e.preventDefault();
     
      //grab all form data  
      var formData = new FormData($(this)[0]);
     $('.loader').fadeIn();
      $.ajax({
        url: '{{{ url('/admin/generalSettings/backgroundimage') }}}',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
        },
        success: function (response) {
          if(response.status == 'success') {
    
            toastr.success(response.message);
            $('.loader').fadeOut();
            $("#backgroundimage").show();
            $("#delete-background-image").show();
            $("#backgroundimage").attr("src",'');
            $("#backgroundimage").attr("src",response.url);
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
              setTimeout(function(){ progress.hide();$("#set-background-form")[0].reset(); }, 1500);
          } else if(response.status == 'error') {
            $('.loader').fadeOut();
            toastr.error(response.message);
            percent.html(response.message);
            percent.addClass("error-red");
          }
    
        }
      });
    });
    
    
    $("#set-favicon-form").submit(function(e){
     
    
      var bar = $('#progress-favicon-bar');
     var percent = $('#favicon-percent');
     var status = $('#status');
     var progress=$("#favicon-progress");
      //disable the default form submission
      e.preventDefault();
     
      //grab all form data  
      var formData = new FormData($(this)[0]);
     
      $.ajax({
        url: '{{{ url('/admin/generalSettings/favicon') }}}',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
        //console.log(percentVal, position, total);
        },
        success: function (response) {
          if(response.status == 'success') {
    
            toastr.success(response.message);
            $("#favicon").attr("src",'');
            $("#favicon").attr("src",response.url);
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
            progress.show();
             setTimeout(function(){ progress.hide();$("#set-favicon-form")[0].reset();$('#set-logo-btn1').hide() }, 1500);
          } else if(response.status == 'error') {
            toastr.error(response.message);
          }
    
        }
      });
    }); 
    
    
    
     
    
    
    $("#set-logo-form").submit(function(e){
    
     var bar = $('#progress-logo-bar');
     var percent = $('#logo-percent');
     var status = $('#status');
     var logo=$("#logo-progress");
      //disable the default form submission
      e.preventDefault();
     
      //grab all form data  
      var formData = new FormData($(this)[0]);
     
      $.ajax({
        url: '{{{ url('/admin/generalSettings/logo') }}}',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
        //console.log(percentVal, position, total);
        
        },
        success: function (response) {
          if(response.status == 'success') {
    
            toastr.success(response.message);
            $("#logo").attr("src",'');
            $("#logo").attr("src",response.url);
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
             setTimeout(function(){ logo.hide();$("#set-logo-form")[0].reset();$('#set-logo-btn').hide() }, 1500);
          } else if(response.status == 'error') {
            toastr.error(response.message);
          }
    
        }
      });
     
    });
    
    
    $("#set-outerlogo-form").submit(function(e){
    
     var bar = $('#progress-outerlogo-bar');
     var percent = $('#outerlogo-percent');
     var status = $('#status');
     var logo=$("#outerlogo-progress");
      //disable the default form submission
      e.preventDefault();
     
      //grab all form data  
      var formData = new FormData($(this)[0]);
     
      $.ajax({
        url: '{{{ url('/admin/generalSettings/outerlogo') }}}',
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            status.empty();
            var percentVal = '0%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
        },
        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
			console.log(percentVal, position, total);
        },
        success: function (response) {
          if(response.status == 'success') {
    
            toastr.success(response.message);
            $("#outerlogo").attr("src",'');
            $("#outerlogo").attr("src",response.url);
            var percentVal = '100%';
            bar.width(percentVal)
            percent.html(percentVal);
            logo.show();
             setTimeout(function(){ logo.hide();$("#set-outerlogo-form")[0].reset();$('#set-outerlogo-btn').hide() }, 1500);
          } else if(response.status == 'error') {
            toastr.error(response.message);
          }
    
        }
      });
     
    });
    
    
    
    
    $('#set-title').click(function(){
    
      var data = {};
      data.title = $('#title').val();
    
      data._token="{{{csrf_token()}}}";
    
      $.post("{{{ url('/admin/generalSettings/title') }}}", data, function(response){
    
        if(response.status == 'error') {
    
          toastr.error(response.message);
    
        } else if(response.status == 'success'){
    
          toastr.success(response.message);
        }
    
      });
    
    });
    
    
    
</script>
<style type="text/css">
    .add-interest-div {
    width : 100%;
    }
    .loader {
    position: absolute;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url({{{asset("admin_assets/images/image_loader.gif")}}}) 50% 50% no-repeat rgb(249,249,249);
    display: none;
    opacity: 0.7;
    }
</style>
@endsection