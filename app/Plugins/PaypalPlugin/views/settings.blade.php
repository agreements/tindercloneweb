@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.paypal_heading', 0 )}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">
      <div class="col-md-12 section-first-col">
         <div class="row">

            <form action = "" method = "POST" id = "set-paypal-form">
               {{csrf_field()}}
               <input type = "hidden" name = "_task" value = "createAdmin">
               <div class="col-md-10 add-creditpackage-col admin-create-div">
                  <p class="add-credit-package-text">{{trans_choice('admin.paypal_title', 0 )}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.paypal_fields', 0 )}}</label>
                     <input type="text"  placeholder="{{trans_choice('admin.paypal_fields_holder', 0 )}}" name = "paypal_username" value = "{{{$paypal_username}}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.paypal_fields', 1 )}}</label>
                     <input type="text" placeholder="{{trans_choice('admin.paypal_fields_holder', 1 )}}" name = "paypal_password" value = "{{{$paypal_password}}}" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.paypal_fields', 2 )}}</label>
                     <input type="text"  placeholder="{{trans_choice('admin.paypal_fields_holder', 2 )}}" class="form-control input-border-custom input-border-custom" name = "paypal_signature" value = "{{{$paypal_signature}}}">
                  </div>
                 
                  <div class="form-group">
                        <label class = "package-label" >{{trans_choice('admin.activate', 0 )}}</label>
                          <select name = "paypal_mode" class="form-control input-border-custom select-custom">
                          
                            @if($paypal_mode == 'true')
                                <option value = "true" selected>{{trans_choice('admin.paypal_mode', 0 )}}</option>
                                <option value = "false">{{trans_choice('admin.paypal_mode', 1 )}}</option>
                            @else
                                <option value = "true">{{trans_choice('admin.paypal_mode', 0 )}}</option>
                                <option value = "false" selected>{{trans_choice('admin.paypal_mode', 1 )}}</option>
                            @endif
                          
                          
                          </select>
                    </div>
                  <button type="submit" id = "set-paypal-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.save', 0 )}}</button>
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
   
$('#set-paypal-btn').click(function(e){
    e.preventDefault();

    var data = $('#set-paypal-form').serializeArray();
    $.post("{{{url('/admin/pluginsettings/paypal')}}}", data, function(response){

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
           data={package_id:id,type:name,gateway:"paypal"};
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
.row {
        background-color: #38414A;
}
.section-first-col{
    min-height: 0px;
}
</style>
@endsection