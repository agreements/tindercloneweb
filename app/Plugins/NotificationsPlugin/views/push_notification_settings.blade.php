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
      <h1 class="content-header-head">{{trans('notification.push_notification_header')}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "push_notification_setting_form">
               {{csrf_field()}}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans('notification.push_notification_title')}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans('notification.firebase_push_notification_project_number')}}</label>
                     <input type="text" name = "project_number" value = "{{$project_number}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('notification.firebase_push_notification_web_api_key')}}</label>
                     <input type="text" name = "web_api_key" value = "{{$web_api_key}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('notification.firebase_push_notification_android_api_key')}}</label>
                     <input type="text"  name = "android_api_key" value = "{{$android_api_key}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('notification.firebase_push_notification_ios_api_key')}}</label>
                     <input type="text"  name = "ios_api_key" value = "{{$ios_api_key}}" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="submit"  class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0)}}</button>
                  
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
   
$('#push_notification_setting_form').submit(function(e){
    e.preventDefault();


    var formData = $(this).serializeArray();
    console.log(formData);
    
    var url = "{{url('plugins/settings/push-notification')}}";   

    $.post(url, formData, function(res){

        if (res.status == "success") {
            toastr.success('{{{trans_choice('admin.set_status_message',0)}}}');
        } else {
            toastr.success('{{{trans_choice('admin.set_status_message',1)}}}');
        }


    });

});

   
</script>

@endsection