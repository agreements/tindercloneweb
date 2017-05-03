@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.google_analytic_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row"> 
            
            <form action = "" method = "POST" id = "set-google-analytic-form">
                {!! csrf_field() !!}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.google_analytic_title', 0 )}}</p>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.google_analytic_field', 0 )}}</label>
                     <input type="text" id = "google_accountNumber" placeholder="{{trans_choice('admin.google_analytic_holder', 0 )}}" value = "{{{($google_accountNumber)}}}" name = "google_accountNumber" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="button" id = "set-google-analytic-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
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
   
   
$('#set-google-analytic-btn').click(function(){
    
    var ac_no = $('#google_accountNumber').val();


    var data = $('#set-google-analytic-form').serializeArray();
    $.post("{{{ url('/admin/googleAnalyticSettings') }}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

    


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