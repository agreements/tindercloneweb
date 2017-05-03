<div role="tabpanel" class="tab-pane paypalcredit credit-card-box" name="paypal">
						   				   
						   				   <form  class="form-horizontal paymentGateway" action="{{{url('/paypal')}}}" method="POST" id="paypal">
							   				   {!! csrf_field() !!}
							   				   
							   				   <div class="select-points container">
							<select class="selectpicker form-control packages" name = "package">
								
								
								
								
								
								
						      </select>
  					  	 </div>
							   				   
							   				    <input type="hidden" class="feature" name="feature" value="">
							   				    <input type="hidden" class="description" name="description" value="">
							   				    <input type="hidden" class="metadata" name="metadata" value="">
							   				    
							   				     <input type="hidden" id="paypal-amount" class="amount-form" name="amount" value="">
						 <input type="hidden" name="packid" class="packageId" value=""/>
							   				     
									    		<div class="form-group">
										    	
					                        </div>
					                            <div class="form-group">
						                            
						                            
						                           
						                            <button type="submit" class="btn df-button--paypal df-button--large" >{{trans('app.check_out_with')}} <img src="{{{asset('plugins/PaypalPlugin/images')}}}/logo-paypal.png" style="vertical-align: bottom" width="79" height="24"></button>
						                            
						                          
					                              
												</div>
						   				   </form>	
								</div>

@section('plugin-scripts')

@parent


<script>
	function paypal($form)
	{
		
		$form.get(0).submit();
		return;
		
		
	}
	
</script>
@endsection