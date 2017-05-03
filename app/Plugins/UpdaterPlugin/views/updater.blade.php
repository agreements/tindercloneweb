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

    .progress{
        display: none;
    }

    .list-group-item.active {
        /*background-color: #2d343c;*/
        /*border-color: #2d343c;*/
    }

    .package-label
    {
        margin-bottom: 20px;
        color: red;
        font-size: 14px;
        opacity: 1;
    }
    .overwrite_lang_file
    {
        color: white;
font-size: 13px;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header content-header-custom">
        <h1 class="content-header-head">{{trans_choice('admin.update',1)}} {{trans_choice('admin.manage',1)}}</h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="col-md-12 section-updates-col">
            <h4 class="user-statistics">{{{$website_title}}} {{trans_choice('admin.update',3)}}  : {{trans('admin.current_version')}} ({{$current_version}})</h4>

            @if(isset($errors))
                @if(count($errors) > 0)
                    @foreach($errors as $err)
                    <div class="alert alert-danger">
                        {{{$err}}}
                    </div>
                    @endforeach
                @endif

                @if(count($errors) > 0)
                <form action = "{{{url('admin/updater')}}}" method = "GET">
                    <div class="form-group">
                        <button type="submit" id = "create_btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.check_options',1)}}</button>
                    </div>
                </form>
                @else
                <form action = "" method = "POST" id="check-update-form">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label class="package-label">{{trans_choice('admin.update',0)}} {{trans_choice('admin.url',1)}}</label>
                        <input type="text" id = "url" name = "url" placeholder="Enter Update Url" class="form-control  input-border-custom" value = "http://licensing.datingframework.com/check-update">
                    </div>
                    <button type="submit" id="check-update-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.check_options',0)}}</button>
                    <div id="update-response" style="color: red;"></div>
                </form>
                @endif
            @endif



            <div id = "update-div" style="display:none">
           
                <p class="package-label">{{trans_choice('admin.update',1)}} {{trans_choice('admin.avail',0)}}.</p>
                <div class = "progress progress-striped progress-custom">
                    <div class = "progress-bar" role = "progressbar" aria-valuenow = "50" 
                        aria-valuemin = "0" aria-valuemax = "100" style = "width : 0%">
                        0%
                    </div>
                </div>

                <p class="overwrite_lang_file">{{trans('admin.overwrite_language_files')}}</p>
                <label class="switch">
                    <input class="switch-input" type="checkbox" id="overwrite-lang-files">
                    <span class="switch-label"></span> 
                    <span class="switch-handle"></span> 
                </label>

                <button type="button" class="btn btn-success btn btn-info btn-addpackage btn-custom pull-right" id = "install">{{trans_choice('admin.install',0)}} 
                {{trans_choice('admin.update',1)}}</button><br><br><br>
                <button type="button" class="btn btn-success btn btn-info btn-addpackage btn-custom pull-right" onClick="window.location.reload()" style="display:none" id = "reload">{{trans('admin.reload')}}</button><br><br><br>
                <!-- <button type="button" class="btn btn-success btn btn-info btn-addpackage btn-custom pull-right" id = "backup" style="margin-right:8px;">Backup</button>
                <p class="package-label update-description-package">{{{trans_choice('admin.update_desc',1)}}}</p><br> -->
                <span id="update-descriptions">
                <span>
                
             </div>   
               
        </div>
</div>
</section>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
       

        function changeInstallButtonText(btnText) 
        {
            $("#install").text(btnText);
        }

        var updateFunc = function(index = 0)
        {
            $.ajax({ type: "POST",   
               url: "{{{url('admin/updater/doupdate')}}}",   
               async: true,
               data : {_token:"{{csrf_token()}}", filename:file_lists[index], overwrite_lang_files:overwrite_lang_files},
               success : function(res) {
                 
                 
                if(res.status == "success") {

                    var percent = 100 * ((index+1) /file_lists.length);

                    $('.progress-bar').css('width', percent+"%");  
                    $('.progress-bar').text(parseInt(percent)+"%");
                    

                    if(index <  file_lists.length-1) {
                        updateFunc(++index);
                    } else {
                        toastr.success('{{trans('admin.update_done')}}');
                         $("#reload").hide();
                         $("#install").attr('disabled', true);

                         changeInstallButtonText('Update done');

                         $('.progress').hide();
                    }
                    
                } else if(res.status =="error"){
                    toastr.error(res.error_text);
                    $("#reload").show().attr("class", "btn btn-danger pull-right");;
                    $("#install").hide();
                }
                    
                
               },
               error : function(){
                    $("#reload").show().attr("class", "btn btn-danger pull-right");;
                    $("#install").hide();
                    alert("some error occoured");
               }

           });
        }


        $("#install").on("click", function(){
            $("#reload").hide();
            changeInstallButtonText("{{trans('admin.installing')}}");
            $('.progress').show();
            updateFunc(0);

        });


        




        function changeUpdateButtonText()
        {
            $("#check-update-btn").text('{{trans('admin.checking')}}');
        }

        function changeUpdateButtonBack()
        {
            $("#check-update-btn").text('{{trans_choice('admin.check_options',0)}}');
        }



        function buildDescription(product_lists) 
        {
            var list_html = "";

            for(product in product_lists) {

                if(product_lists[product][3] == true) {

                    list_html += "<div class=\"list-group\">" + 
                                "<a href=\"#\" class=\"list-group-item active\">"+product_lists[product][0]+" "+ product_lists[product][1] + "  ({{trans('admin.new_product_install')}})</a>";
                } else {
                    list_html += "<div class=\"list-group\">" + 
                                "<a href=\"#\" class=\"list-group-item active\">"+product_lists[product][0]+" "+ product_lists[product][1] +"</a>";
                }

                


                var update_list = product_lists[product][2]

                for(update in update_list) {
                    list_html += "<a href=\"#\" class=\"list-group-item\">           "+update_list[update].version+" : "+ update_list[update].description +"</a>";
                }

                list_html += "</div>";
            }

            return list_html;
        }


        var file_lists = null;


        $("#check-update-form").on("submit", function(e){

            changeUpdateButtonText();


            e.preventDefault();

            var formData = $(this).serializeArray();
            

            $.post("{{{url('admin/updater/checkupdate')}}}", formData, function(response){

                if(response.status == 'ERROR') {

                    $("#update-response").text(response.message);

                } else if(response.status =="SUCCESS") {


                    $("#check-update-form").slideUp();
                    $("#update-div").slideDown();


                    var desc_html = buildDescription(response.product_lists);
                    
                    $("#update-descriptions").append(desc_html);


                    file_lists = response.file_lists;
                    console.log(file_lists);
                }

                changeUpdateButtonBack();

            });


        });












        var overwrite_lang_files = 'false';

        $("#overwrite-lang-files").on("click", function(){
            overwrite_lang_files = $(this).is(":checked") == true ? 'true' : 'false';
        });



        /*
        $("#backup").on("click", function(){

            toastr.error("Wait untill response is showing.")

            $.ajax({ type: "GET",   
               url: "{{{url('/admin/updater/dobackup')}}}",   
               async: true,
               success : function(res) {
                 
                 if(res.status == "success") {
                    alert(res.output);
                } else {
                    alert("some error occoured");
                }
               },
               error : function(){
                    alert("some error occoured");
               }
           });

        });
        */



        /*
        $('#install').click(function(){
           //setings progress bar zero
           $('.progress-bar').css('width', '0%');  
           $('.progress-bar').text("0%");
           $.ajax({ type: "GET",   
               url: "{{{url('/admin/updater/dodownload')}}}",   
               async: true,
               success : function(res) {
                 
                 if(res.status == 'success')
                 {
                     $('.progress-bar').css('width', '50%');  
                     $('.progress-bar').text("50%");
                     //if download successfull then process extract zip updates
                     doExtract();
                 }
                 else
                 {
                    toastr.error("Updates download failed")
                     $('.progress-bar').css('width', '0%');  
                     $('.progress-bar').text("0%");
                     $('#install').text("Retry");
                     $('#install').attr("class", "btn btn-danger pull-right");
                 }
                 
               },
           });
        }); 





    function doExtract () {


        $.ajax({ type: "GET",   
             url: "{{{url('admin/updater/doupdate')}}}?&task=EXTRACT",   
             async: true,
             success : function(res) {
               
               if(res.status == 'success')
               {
                   $('.progress-bar').css('width', '60%');  
                   $('.progress-bar').text("60%");
                    doUpdate();
               }
               else
               {
                    toastr.error("extract updates failed")
                   $('.progress-bar').css('width', '0%');  
                   $('.progress-bar').text("0%");
                   $('#install').text("Retry");
                   $('#install').attr("class", "btn btn-danger pull-right");
               }
               
             },
         });



    }




    function doUpdate() {

        $.ajax({ type: "GET",   
           url: "{{{url('admin/updater/doupdate')}}}?task=UPDATE&overwrite_lang_files="+overwrite_lang_files,   
           async: true,
           success : function(res) {
             
             if(res.status == 'success')
             {
                 $('.progress-bar').css('width', '80%');  
                 $('.progress-bar').text("80%");
                 finishUpdate();
                 
             }
             else if (res.status == "error" && res.issue != '') {
                toastr.error(res.issue);
                 $('#install').text("Retry");
                 $('#install').attr("class", "btn btn-danger pull-right");
             }
             else
             {
                toastr.error("Update failed");
                 $('#install').text("Retry");
                 $('#install').attr("class", "btn btn-danger pull-right");
             }
             
           },
       });



    }


    function finishUpdate() {


        $.ajax({ type: "GET",   
             url: "{{{url('admin/updater/doupdate')}}}?task=FINISH",   
             async: true,
             success : function(res) {
               
               if(res.status == 'success')
               {
                   $('.progress-bar').css('width', '100%');  
                   $('.progress-bar').text("100%");
                   $('#install').text('Update Done');
                   $('#install').attr("class", "btn btn-warning pull-right disabled");
               }
               else
               {
                   $('#install').text("Retry");
                   $('#install').attr("class", "btn btn-danger pull-right");
               }
               
             },
         });


    }
    
    */


    });
</script>

@endsection