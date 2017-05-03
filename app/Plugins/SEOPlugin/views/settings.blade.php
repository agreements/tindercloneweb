@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.seo',1)}} {{trans_choice('admin.setting',2)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">
            
            <form action = "" method = "POST" id = "set-seo-form">
               
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.set',1)}} {{trans_choice('admin.seo',1)}} {{trans_choice('admin.setting',2)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.meta',1)}} {{trans_choice('admin.desc',1)}}</label>
                     <input type="text" id = "meta_description" placeholder="{{trans_choice('admin.meta_desc_holder',1)}}" value = "@if(isset($meta_description)) {{{$meta_description}}} @endif" name = "meta_description" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.meta',1)}} {{trans_choice('admin.keyword',2)}}</label>
                     <input type="text" value = "@if(isset($meta_keywords)) {{{$meta_keywords}}} @endif" id= "meta_keywords" placeholder="{{trans_choice('admin.meta_keys_holder',1)}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.seo_block',1)}}
                     <label class="switch block-switch">
                              <input type="checkbox" @if(isset($meta_block)) @if($meta_block =='yes') checked @endif @endif  name = "meta_block" id = "meta_block" class="switch-input installed-plugin-switch"/>
                              <span class="switch-label" ></span> 
                              <span class="switch-handle"></span> 
                    </label></label>
                  </div>
                  <button type="button" id = "set-seo-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save',2)}}</button>
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
   
$('#set-seo-btn').click(function(e){

    e.preventDefault();

    var meta_description = $('#meta_description').val();
    var meta_keywords = $('#meta_keywords').val();
    var meta_block = $('#meta_block').is(':checked');


    if(meta_description == '') {
        toastr.warning('{{{trans_choice('admin.meta_desc_require',1)}}}');
    } else {

        if(meta_keywords == '') {
            toastr.warning('{{{trans_choice('admin.meta_keyword_require',1)}}}');            
        } else {

            var data = {};
            data.meta_description = meta_description;
            data.meta_keywords = meta_keywords;
            data.meta_block = (meta_block) ? 'yes' : 'no';
            data._token = "{{{csrf_token()}}}";

            $.post("{{{ url('/admin/seoSettings') }}}", data, function(response){

                if(response.status == 'success'){
                    toastr.success(response.message);
                } else if(response.status == 'error'){
                    toastr.error(response.message);
                }
            });

        }


    }

            
    


});

   
</script>
<style type="text/css">
   
.admin-create-div{
   width : 100%;
 }

.block-switch{
   margin-left: 108%;
    margin-top: -21px;
}

.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}

</style>
@endsection