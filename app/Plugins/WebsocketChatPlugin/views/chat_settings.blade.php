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
        <h1 class="content-header-head">{{trans('admin.chat_settings_heading')}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-first-col">
            <div class="row">
                
                    <div class="col-md-10 add-creditpackage-col admin-create-div">

                    <form action = ""  method = "POST" id = "chat_settings_form">
                        {!!csrf_field()!!}
                        <p class="add-credit-package-text">{{trans('admin.chat_settings_title')}}</p>
                        <div class="form-group">
                            <label class="package-label">{{trans('admin.choose_chat_option')}}</label>
                            <select class="form-control select-custom" name="chat_settings_option">
                                <option value="everyone" @if($chatSettings['chatSettingsOption'] =='everyone' || $chatSettings['chatSettingsOption'] == '') selected @endif>{{trans('admin.chat_everyone')}}</option>
                                <option value="match_only" @if($chatSettings['chatSettingsOption'] =='match_only') selected @endif>{{trans('admin.chat_match_only')}}</option>
                            </select>
                        </div>
    

                        <span id = "match_only_block" @if($chatSettings['chatSettingsOption'] !='match_only') style="display:none" @endif>

                            <div class="form-group">
                                <label class="package-label">{{trans('admin.chat_initiate_time_title')}}</label>
                                <input type = "text" name="chat_initiate_time_bound" value = "{{$chatSettings['chatInitiateTimeBound']}}" placeholder = "{{trans('admin.chat_initiate_time_placeholder')}}" class = "form-control  input-border-custom">
                            </div>

                        </span>

                        <span id = "everyone_block" @if($chatSettings['chatSettingsOption'] !='everyone' && $chatSettings['chatSettingsOption'] != '') style="display:none" @endif>
                            <div class="form-group">
                                <label class="package-label">{{trans('admin.chat_initiate_limit_title')}}</label>
                                <input type = "text" name="chat_limit" value = "{{$chatSettings['chatLimit']}}" placeholder = "{{trans('admin.chat_initiate_limit_placeholder')}}" class = "form-control  input-border-custom">
                            </div>
                        </span>



                        <button type="button" class="btn btn-info btn-addpackage btn-custom chat_settings_save">{{trans('admin.save')}}</button>

                        </form>

                    </div>
                
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script>
   $(document).ready(function(){


        $(".chat_settings_save").on("click", function(){

            var form_data = $("#chat_settings_form").serializeArray();

            $.post("{{url('plugins/websocketchatplugin/chat/save')}}", form_data, function(res){

                if(res.status == 'success') {
                    toastr.success("{{trans_choice('admin.set_status_message',0)}}");
                } else {
                    toastr.error("{{trans_choice('admin.set_status_message',1)}}");
                }

            });


        });



        $("#chat_settings_form").find(" select[name=chat_settings_option]").on('change', function(){

            var selected = $(this).val();

            if(selected == 'everyone') {

                $("#match_only_block").hide();
                
                $("#everyone_block").slideDown();
                $("#everyone_block").css('display', 'block');

            } else if(selected == 'match_only') {

                $("#match_only_block").slideDown();
                $("#match_only_block").css('display', 'block');
                
                $("#everyone_block").hide();

            }

        });

   });
</script>
@endsection