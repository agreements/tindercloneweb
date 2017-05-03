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
        <h1 class="content-header-head">{{trans('admin.websocket_chat_server_settings_title')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                <form action = "" method = "POST" id = "settings-save-form">
                    {{csrf_field()}}
                   
                    <div class="col-md-10 add-creditpackage-col admin-create-div">
                        <p class="add-credit-package-text">{{trans('admin.server_settings')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_php_path')}}</label>
                            <input type="text"  placeholder="" name = "wesocket_php_path" value = "{{{$server_php_path}}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_server_port')}}</label>
                            <input type="text"  name = "websocket_chat_server_port" value = "{{{$server_port}}}" class="form-control  input-border-custom">
                        </div>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_server_domain')}}</label>
                            <input type="text"  placeholder="" name = "websocket_domain" value = "{{{$server_domain}}}" class="form-control  input-border-custom">
                        </div>

                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enable_secure_mode')}}</label>
                            <label class="switch">
                                <input class="switch-input debug-mode-switch" type="checkbox" id ="secure-mode-switch" @if($secure_mode == 'true') checked @endif name ="websocket_secure_mode">
                                <span class="switch-label"></span> 
                                <span class="switch-handle"></span>
                            </label>
                        </div>
                        <span id ="secure-div" @if($secure_mode != 'true') style="display:none" @endif>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_ssl_certificate')}}(.crt)</label>
                            <textarea class="form-control  input-border-custom" name="websocket_crt"style="height:100px;">{{$websocket_crt}}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_private_key')}}(.key)</label>
                            <textarea class="form-control  input-border-custom" name ="websocket_key" style="height:100px;">{{$websocket_crt}}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="package-label">{{trans('admin.enter_intermediae_key')}}(.ca)</label>
                            <textarea class="form-control  input-border-custom" name="websocket_ca" style="height:100px;">{{$websocket_ca}}</textarea>
                        </div>
                        </span>

                        <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{trans('admin.save')}}</button>
                    </div>
                </form>
            </div>

            <div class="row" style="margin-top:10px">
                <div class="col-md-10 add-creditpackage-col admin-create-div">
                    @if($server_running == 'true')
                    @else
                    @endif
                    <button type="button" id = "start-server-btn" class="btn btn-info btn-addpackage btn-custom" style="@if($server_running == 'true')display:none @endif">{{trans('admin.start_server_btn')}}</button>
                    <button type="button" id = "stop-server-btn" class="btn btn-info btn-addpackage btn-custom" style="background-color:#D9534F;@if($server_running == 'false')display:none @endif">{{trans('admin.stop_server_btn')}}</button>

                    <div class="form-group">
                        <label class="package-label">{{trans('admin.server_status')}}</label>
                        <textarea placeholder=""  id="output" class="form-control input-border-custom">{{{$server_status}}}</textarea>
                    </div>
                </div>
            </div>

        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function(){


        $("#secure-mode-switch").on('change', function(){

            if($(this).is(':checked')) {

                $("#secure-div").slideDown();
                $("#secure-div").css('display', 'block');

            } else {

                $("#secure-div").css('display', 'block');
                $("#secure-div").slideUp();

            }


        });


        $("#settings-save-form").on('submit', function(e){

            e.preventDefault();


            if($("input[name=wesocket_php_path]").val() == "") {
                toastr.warning("{{trans('admin.wesocket_php_path_required')}}");
                return false;
            }

            if($("input[name=websocket_chat_server_port]").val() == '') {
                toastr.warning("{{trans('admin.websocket_chat_server_port_required')}}");
                return false;
            }

            var domain = $("input[name=websocket_domain]").val();
            if(domain == '') {
                 toastr.warning("{{trans('admin.domain_required')}}");
                return false;
            }

            var domain_split = domain.split('://');
            if(domain_split[0] !== 'http' && domain_split[0] !== 'https') {
                toastr.warning("{{trans('admin.domain_schema_required')}}");
                return false;
            }




            if($("#secure-mode-switch").is(':checked')) {

                if($("textarea[name=websocket_crt]").val() == '') {
                    toastr.warning("{{trans('admin.websocket_crt_required')}}");
                    return false;
                }
                if($("textarea[name=websocket_key]").val() == '') {
                    toastr.warning("{{trans('admin.websocket_key_required')}}");
                    return false;
                }
            }



            var formData = $("#settings-save-form").serializeArray();

            $.post("{{url('plugins/websocketchatplugin/server-settings/save-server-settings')}}", formData, function(res){


                if (res.status == 'success') {
                    toastr.success("{{trans_choice('admin.set_status_message',0)}}");
                } else {
                    toastr.error("{{trans_choice('admin.set_status_message',1)}}");
                }

            });



        });


        $("#stop-server-btn").on('click', function(){
            $.post("{{{url('plugins/websocketchatplugin/stop-server')}}}",
                {_token:"{{{csrf_token()}}}"},
                function(res){
                    if (res.status == 'success') {
                        toastr.success("{{trans('admin.server_stopped_success')}}");
                        $("#output").text("Server stopped.");
                        $("#start-server-btn").show();
                        $("#stop-server-btn").hide();
                    } else {
                        toastr.error("{{trans('admin.server_stopped_fail')}}");
                    }
                }
            );
        });




        $("#start-server-btn").on('click', function(){
            $.post("{{{url('plugins/websocketchatplugin/start-server')}}}",
                {_token:"{{{csrf_token()}}}"},
                function(res){
                    if (res.status == 'success') {
                        toastr.success("{{trans('admin.server_started_success')}}");
                        $("#start-server-btn").hide();
                        $("#stop-server-btn").show();
                    } else {
                        toastr.error("{{trans('admin.server_started_fail')}}");
                    }
                    $("#output").text(res.output);
                }
            );
        });




        // $("#save-server-btn").on('click', function(){

        //     var server_port = $("#server_port").val();
        //     var server_php_path = $("#server_php_path").val();

        //     $.post("{{{url('plugins/websocketchatplugin/server-settings/save-server-settings')}}}",
        //         {_token:"{{{csrf_token()}}}", server_port:server_port, server_php_path:server_php_path},
                
        //         function(res){

        //             if (res.status == 'success') {
        //                 toastr.success("Chat server settings saved successfully.");
        //             } else {
        //                 toastr.error("Failed to save chat server setttings.");
        //             }

        //         });

        // });

   });
</script>
@endsection