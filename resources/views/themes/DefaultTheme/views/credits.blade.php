<?php use App\Components\Theme; ?>
@extends(Theme::layout('master-2cols'))
	@section('content')
		@parent
				<div class="col-md-12 cont-cover1">
					<div class="row">
						<div class="col-md-9">
							<h3 class="newFontSize">{{{trans_choice('app.credits_heading',0)}}}</h3>
							<hr class="mv20">
							
							<p class="credits_text">{{{trans_choice('app.credits_heading',1)}}}
								<span class="textspecial">{{{trans_choice('app.credits_heading',2)}}}</span>
							</p>
							<p class="credits_text">
								{{{trans_choice('app.credits_heading',3)}}}
							</p>
						</div>
						<div class="col-md-3">
							<!-- Button trigger modal -->
<!-- 							<img class="credits_page" src="data:image/png;base64,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" /> -->
							
							<img class="credits_page" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/coins.svg')" height="30" width="30"/>
							<div class="refill_container">
                                <h4 class="container-fluid popularity-ind1">
                                    <span class="credit_balance">{{{trans_choice('app.your_credits',1)}}}:</span>
                                    <br>
                                   
                                    <strong style="font-size: large;">[[creditBalance]]</strong>
                                </h4>
<!--                                 <button href="#myModal" class="btn btn_refill" data-toggle="modal">{{{trans_choice('app.refill',1)}}}</button> -->
								<button  class="btn btn_refill" ng-click="openPaymentModal('credit',{})">{{{trans_choice('app.refill',1)}}}</button>
				
                            </div>  
                		</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							&nbsp;
							&nbsp;
						</div>
					</div>
					{{{Theme::render('credits-feature')}}}
					
					
				</div>
			</div>
		</div>
	</section>
	<div class="bs-example">
		

 
 
 
	    </div>
	</div> 
	@endsection
	

@section('scripts')
@parent



  
      
   
  </script>


<!-- custom scrollbar plugin --> 




<script>
	function random(name)
{
	var str=name;
	$('.gateid').val(str.trim());
	//alert(name);
}
</script>







	
@endsection

 

  	


	
	
	
	<div class="cd-popup" role="alert">
		@if($credit->balance >= $spotCredits)
			<div class="cd-popup-container">
			<p>{{{trans_choice('app.popup_text',0)}}} {{{$spotCredits}}} {{{trans_choice('app.credits',0)}}}?</p>
			<ul class="cd-buttons">
				<li  ng-click="addtoSpotlight()" style="cursor: pointer"><a class="yes" >{{{trans_choice('app.yes',1)}}}</a></li>
				<li style="cursor: pointer"><a  class="no" href="#0">{{{trans_choice('app.no',1)}}}</a></li>
			</ul>
			<a href="#0" class="cd-popup-close img-replace">{{{trans_choice('app.close',1)}}}</a>
		</div> <!-- cd-popup-container -->
		@else
			<div class="cd-popup-container">
			<p>{{{trans_choice('app.popup_text',1)}}}</p>
			<ul class="cd-buttons">
				<li  style="cursor: pointer"><a href="#myModal" data-toggle="modal" class="yes" >{{{trans_choice('app.refill',1)}}}</a></li>
				<li style="cursor: pointer"><a  class="no" href="#0">{{{trans_choice('app.no',1)}}}</a></li>
			</ul>
			<a href="#0" class="cd-popup-close img-replace">{{{trans_choice('app.close',1)}}}</a>
		</div>
		@endif
	</div>
	
	<div class="cd-popup2" role="alert">
		@if($credit->balance >= $riseup_credits)
		<div class="cd-popup-container">
			<p>{{{trans_choice('app.popup_text',2)}}} {{{$riseup_credits}}} {{{trans_choice('app.credits',1)}}}?</p>
			<ul class="cd-buttons">
				<li ng-click="addtoRiseUp()" style="cursor: pointer"><a class="yes" >{{{trans_choice('app.yes',1)}}}</a></li>
				<li style="cursor: pointer"><a  class="no2" href="#0">{{{trans_choice('app.no',1)}}}</a></li>
			</ul>
			<a href="#0" class="cd-popup-close img-replace">{{{trans_choice('app.close',1)}}}</a>
		</div> <!-- cd-popup-container -->
		
		@else
		<div class="cd-popup-container">
			<p>{{{trans_choice('app.popup_text',1)}}}</p>
			<ul class="cd-buttons">
				<li style="cursor: pointer"><a href="#myModal" data-toggle="modal" class="yes" >{{{trans_choice('app.refill',1)}}}</a></li>
				<li style="cursor: pointer"><a  class="no" href="#0">{{{trans_choice('app.no',1)}}}</a></li>
			</ul>
			<a href="#0" class="cd-popup-close img-replace">{{{trans_choice('app.close',1)}}}</a>
		</div>
	
		@endif
		</div>
	
	
	@yield('credits-scripts')
