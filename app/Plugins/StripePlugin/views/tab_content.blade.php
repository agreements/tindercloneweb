<div role="tabpanel" class="tab-pane  cardcredit credit-card-box" name="stripe">
						   				   
						   				    <form action= "{{{url('/charge')}}}" class="form-horizontal paymentGateway" method="POST" id="stripe">
							   				    
							   				    <div class="select-points container">
													<select class="selectpicker form-control packages"  name = "package">
																												
														
												      </select>
						  					  	 </div>
						    			<div class="row display-tr" >
					                       
					                        <div class="display-td" >                            
					                            <img class="" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="http://i76.imgup.net/accepted_c22e0.png">
					                        </div>
					                    </div>  
		                        {!! csrf_field() !!}
											<div class="form-group" style="">
												
		                                      <div class="col-md-4">
		                                        <input type="hidden" name="url" value="/charge"/>
		                                        <input type="hidden" name="gateway" value="stripe"/>  
		                                        <input type="hidden" class="stripeToken" name="stripeToken" value="">
		                                        
		                                        <input type="hidden" class="feature" name="feature" value="">
		                                        
		                                        <input type="hidden" class="description" name="description" value="">
		                                        
		                                         <input type="hidden" class="metadata" name="metadata" value="">
		                                          <input type="hidden"  class="amount-form" name="amount" value="">
						 <input type="hidden" name="packid" class="packageId" value=""/>
		                                        
		                                        
		                                        
		                                      </div>
		                                    </div>
											<div class="form-group" style="">
		                                      <div class="col-md-4">
<!-- 		                                         <input type="hidden" name="gateway" class="gateid" value=""/>       -->
		                                      </div>
		                                    </div>
									    	<div class="form-group">
									    		<label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',0)}}}.</label>
<!--
										        <div class="col-md-8">
											          <input type="text" class="form-control" id="inputType" size="20" data-stripe="number" value="">
										        </div>
-->
										        
										        <div class="input-group col-md-8 col-xs-11" style="padding-left: 14px;">
                                        <input id="inputType" data-stripe="number" value=""
                                            type="tel"
                                            class="form-control"
                                            name="cardNumber"
                                            placeholder="Valid Card Number"
                                            autocomplete="cc-number"
                                            required autofocus 
                                        />
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
									    	</div>
										    <div class="form-group">
										      <label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',1)}}}</label>
<!--
										      <div class="col-md-2">
										          <input type="text" class="form-control" id="inputType" size="2" data-stripe="exp-month" placeholder="MM" value="" required>
										      </div>
										      <div class="col-md-4">
										          <input type="text" class="form-control" id="inputType" size="2" data-stripe="exp-year" placeholder="YYYY" value="" required>
										      </div>
-->											

											<div class="col-md-4">
										          <input 
                                        type="tel" 
                                        class="form-control" 
                                        name="cardExpiry"
                                        placeholder="MM / YY"
                                        autocomplete="cc-exp"
                                        required 
                                    />
										      </div>
											 										</div>
										    <div class="form-group">
										      <label for="inputType" class="col-md-3 control-label">{{{trans_choice('app.card_details',2)}}}</label>
										      <div class="col-md-4">
<!-- 										          <input type="text" class="form-control" id="inputType" size="20" data-stripe="cvc" value=""> -->
										          
										          <input 
                                        type="tel" data-stripe="cvc" value=""
                                        class="form-control"
                                        name="cardCVC"
                                        placeholder="CVC"
                                        autocomplete="cc-csc"
                                        required
                                    />
										      </div>
										    </div>
										    <button type="submit" class="btn btn-success pay">{{{trans_choice('app.paynow',1)}}}</button>
						    			</form>
					   				   </div>


@section('plugin-scripts')

@parent



<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
	
	formStripe =null;
	
	Stripe.setPublishableKey('{{{$stripe_publishable_key}}}');
    var stripeResponseHandler = function(status, response) {
      var $form = formStripe;
      if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // token contains id, last4, and card type
        var token2 = response.id;
        // Insert the token into the form so it gets submitted to the server
		
		//$form.append($('<input type="hidden" name="stripeToken" />').val(token2));
		 
		 
		 $($form.selector+" input[name=stripeToken]").val(token2);
		 
		  $form.get(0).submit(); 
		  
		  
        
      }
    };

	
		
	
	function stripe($form)
	{
		
		
		
		//form action generate
		formStripe =$form;
		
		
		var expiry = $form.find('[name=cardExpiry]').payment('cardExpiryVal');
	    var ccData = {
	        number: $form.find('[name=cardNumber]').val().replace(/\s/g,''),
	        cvc: $form.find('[name=cardCVC]').val(),
	        exp_month: expiry.month, 
	        exp_year: expiry.year
	    };
		
		
		var token= Stripe.card.createToken(ccData, stripeResponseHandler);
		
		
	}
	
	
	
</script>

<script type="text/javascript" src="@plugin_asset('StripePlugin/js/validation_stripe.js')"></script>




@endsection