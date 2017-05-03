@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice("admin.limit",1)}} {{trans("admin.setting")}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
        
            
            <form action = "" method = "POST" id = "set-photo-restriction-form">
               
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{{trans('admin.min_photo_heading')}}}</p>
                  
                 {{ csrf_field() }}
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{{trans('admin.enable_disbale_feature')}}}
                     <label class="switch block-switch">
                              <input type="checkbox"  name = "photo_restriction_mode" id = "photo_restriction_mode" class="switch-input installed-plugin-switch" @if($photo_restriction_mode == "true") checked @endif/>
                              <span class="switch-label" ></span> 
                              <span class="switch-handle"></span> 
                    </label></label>
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{{trans_choice('admin.photo_restriction_field',0)}}}</label>
                     <input type="number" id = "minimum_photo_count" placeholder="{{{trans_choice('admin.photo_restriction_field_holder', 1)}}}" value = "{{{$minimum_photo_count}}}" name = "minimum_photo_count" class="form-control  input-border-custom">
                  </div>

                  <button type="button" id = "set-photo-restriction-btn" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save', 1)}}}</button>
               </div>
            </form>
            
            
            <form action = "{{{ url('/admin/generalSettings/visitors') }}}" method = "POST" enctype="multipart/form-data" id = "set-background-form">
      {!! csrf_field() !!}
       
            <div class="col-md-10 add-creditpackage-col admin-create-div">
               <p class="add-credit-package-text">{{{trans('admin.who_can_see_profile_visitors')}}}</p>
               <div class="form-group">
               
                  <label class="package-label package-label-custom-general">{{{trans_choice('admin.general_visit_setting',1)}}}</label>
                  <div class="form-group">
                  <select class="form-control select-custom" name="visitor_setting">
                    <option value="everyone" @if($visitor_setting == 'everyone') selected @endif name="everyone">{{{trans_choice('admin.general_visit_setting',2)}}}</option>
                    <option value="superpowers" @if($visitor_setting == 'superpowers') selected @endif name="superpowers">{{{trans_choice('admin.general_visit_setting',3)}}}</option>
                  </select>
                  </div>

               </div>
               <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save',1)}}} {{{trans_choice('admin.general_visit_setting',4)}}}</button>
            </div>
         
       </form>
       <form action = "{{{ url('/admin/generalSettings/encounter_limit_users') }}}" method = "POST" enctype="multipart/form-data" id = "set-background-form">
      {!! csrf_field() !!}
        
            <div class="col-md-10 add-creditpackage-col admin-create-div">
               <p class="add-credit-package-text">{{{trans('admin.encounter_limit_heading')}}}</p>
                
               <div class="form-group">
                  
                  <input type="text" name="limit_encounter" value="{{{$limit_setting[0]}}}" class="form-control  input-border-custom"> </input> <br />
                </div>
 
                 <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save',1)}}} {{{trans_choice('admin.limit',2)}}}</button>
               </div>
                
           
       </form>


	   <!-- <form action = "{{{ url('/admin/generalSettings/chat_limit_users') }}}" method = "POST" enctype="multipart/form-data" id = "set-background-form">
      {!! csrf_field() !!}
        
            <div class="col-md-10 add-creditpackage-col admin-create-div">
               <p class="add-credit-package-text">{{{trans('admin.chat_limit_heading')}}}</p>
                
               
                <div class="form-group">  
                    <input type="text" name="limit_chat" value="{{{$limit_setting[1]}}}" class="form-control  input-border-custom"> </input>
                </div>  
                 <button type="submit" class="btn btn-info btn-addpackage btn-custom">{{{trans_choice('admin.save',1)}}} {{{trans_choice('admin.limit',2)}}}</button>
               </div>
                
           
       </form> -->
            
         
      </div>
</div>
</section>
</div>









@endsection
@section('scripts')
<link href="{{{asset('Admin')}}}/css/toastr.css" rel="stylesheet"/>
<script src="{{{asset('Admin')}}}/js/toastr.js"></script>
<script>
   
$('#set-photo-restriction-btn').click(function(e){

    e.preventDefault();

    var photo_restriction_mode = $('#photo_restriction_mode').is(':checked');
    var minimum_photo_count = $('#minimum_photo_count').val();


    if(minimum_photo_count < 1) {
      toastr.error('{{{trans_choice('admin.photo_restrictino_status_msg',0,['min' => '1'])}}}');
      return false;
    }

    var data = {};
            data.photo_restriction_mode = (photo_restriction_mode)? 'true' : 'false';;
            data.minimum_photo_count = minimum_photo_count;
            
            data._token = "{{{csrf_token()}}}";

            $.post("{{{ url('/admin/settings/photo/restriction/set') }}}", data, function(response){

                if(response.status == 'success'){
                    toastr.success(response.message);
                } else if(response.status == 'error'){
                    toastr.error(response.message);
                }
            });
            
    


});

   
</script>
<style type="text/css">
   
.admin-create-div{
   width : 100%;
   
}

.block-switch{
/*    margin-left: 108%; */
/*     margin-top: -21px; */
}

.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}

</style>
@endsection