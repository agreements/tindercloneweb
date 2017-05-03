@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.stripe_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col user-section-first-col">
         <div class="row"> 
            
            <form action = "{{{url('admin/pluginsettings/stripe')}}}" method = "POST" id = "set-stripe-form">
                {!! csrf_field() !!}
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.stripe_title', 0 )}}</p>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.stripe_field', 0 )}}</label>
                     <input type="text" id = "stripe_api_key" placeholder="{{trans_choice('admin.stripe_holder', 0 )}}" value = "@if(isset($stripe_api_key)){{{$stripe_api_key}}}@endif" name = "stripe_api_key" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label"> {{trans_choice('admin.stripe_field', 1 )}}</label>
                     <input type="text" id = "stripe_publishable_key" placeholder="{{trans_choice('admin.stripe_holder', 1 )}}" value = "@if(isset($publishable_key)){{{$publishable_key}}}@endif" name = "stripe_publishable_key" class="form-control  input-border-custom">
                  </div>
                  
                  <button type="button" id = "set-stripe-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
               </div>
            </form>
            
         </div>
         
         @foreach($payment_packages as $payment)
         <div class="row">
            <div class="col-md-10 add-creditpackage-col add-interest-div">
                <p class="add-credit-package-text">{{{$payment->name}}} {{trans('admin.packages')}}</p>
                
                    @foreach($payment->packages as $pack)
                    	<div class="form-group">
	                    	<label class="package-label">{{{$pack->name}}}</label>
		                    <label class="switch">
		                        <input class="switch-input switch-packages debug-mode-switch" type="checkbox" data-item-id="{{{ $pack->id }}}" data-item-name = "{{{$payment->name}}}" @if($pack->status == 'true') checked @endif/>
		                        <span class="switch-label"></span> 
		                        <span class="switch-handle"></span>
		                    </label>
		                </div>
                    @endforeach	
                
            </div>
        </div>
         @endforeach
         
      </div>
</div>
</section>
</div>









@endsection
@section('scripts')
<script>
   
$('#set-stripe-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-stripe-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/stripe')}}}", data, function(response){

        if(response.status == 'success')
            toastr.success(response.message);
        else if (response.status == 'error')
            toastr.error(response.message);

    });

});

 $(".switch-packages").change(function(){
        
          var name = $(this).data('item-name');
          
          var id= $(this).data('item-id');
          
          if(this.checked){
          	
          	url = "{{{ url('/admin/add_gateway_package') }}}";
           }
          else {
            
             url = "{{{ url('/admin/remove_gateway_package') }}}";
            
          }
           data={package_id:id,type:name,gateway:"stripe"};
            $.ajax({
		          type: "POST",
		          url: url,
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