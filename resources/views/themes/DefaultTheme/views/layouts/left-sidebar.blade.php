<?php use App\Components\Theme;?>				
				<div class="col-md-2  pad side_box">
					
					        
        <input type="hidden" id="superpower_invisible" />
        	
        
             <div class=" navigation panel-collapse collapse in" id="navigation" >
                <ul class="special_features">
                     
                   
                   <li class="clearfix" data-toggle="tooltip"title="{{{trans_choice('app.invisible_tiele',1)}}}">
				   		<div>
					   		<img style="float:right" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/invisible.png')" width="24" height="24" class="robot">
					   		
					   		@if($auth_user->isInvisible())
							<input type="checkbox" id="checkbox1" name="checkbox1" checked="checked" class="switch" />
							@else
							 <input type="checkbox" id="checkbox1" name="checkbox1" class="switch" />
							@endif 
							<label for="checkbox1">{{{ trans("app.invisible")}}}</label>
							</div>
				   </li>
				   
				 @if($profile_score_show_mode =='true') 
				   <li>
				    
				   		<span class="user_score">{{trans('app.score')}}</span>
				     <div id="circle2" data-toggle="tooltip" title="{{{trans('app.popularity_score')}}}"><strong class="score_left"></strong></div>
				     
				   </li>
				  @endif 
				   @if($peoplenearby_only_superpowers != 'true')
				   <li>
				    

<!--                        <button type="button" data-toggle="modal" data-target="#myModalRiseup"  title="{{{trans('app.boost_btn_text')}}}" class="btn btn-default custom_modal-popup2"><span class="">{{{trans('app.boost')}}}</span></button> --> 
                       <span class="user_boost">{{{trans('app.boost_popularity')}}}</span>
                       <img data-toggle="modal" data-target="#myModalRiseup"  data-hover="tooltip" title="{{{trans('app.boost_btn_text')}}}" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/rocket.svg')" class="boost_image"/>

                    
				   </li>
				   @endif
				   
				 @if($hide_popularity !='true')  
				   @if($auth_user->profile->popularity < 10)
				   <li data-toggle="tooltip"  class="popular" title="{{{trans_choice('app.popular_values',0)}}}"><i class="fa fa-battery-empty very_very_low"></i><a href="#">{{{trans_choice('app.popularity',1)}}}</a></li>
				   @elseif($auth_user->profile->popularity >= 10 && $auth_user->profile->popularity < 25)
				   <li data-toggle="tooltip"  class="popular" title="{{{trans_choice('app.popular_values',1)}}}"><i class="fa fa-battery-quarter very_low"></i><a href="#">{{{trans_choice('app.popularity',1)}}}</a></li>
				   @elseif($auth_user->profile->popularity >= 25 && $auth_user->profile->popularity < 50)
				   <li data-toggle="tooltip" class="popular"  title="{{{trans_choice('app.popular_values',2)}}}"><i class="fa fa-battery-half low"></i><a href="#">{{{trans_choice('app.popularity',1)}}}</a></li>
				   @elseif($auth_user->profile->popularity >= 50 && $auth_user->profile->popularity < 75)
				   <li data-toggle="tooltip"  class="popular" title="{{{trans_choice('app.popular_values',3)}}}"><i class="fa fa-battery-three-quarters medium"></i><a href="#">{{{trans_choice('app.popularity',1)}}}</a></li>
				   @else
				   <li data-toggle="tooltip"  class="popular" title="{{{trans_choice('app.popular_values',4)}}}"><i class="fa fa-battery-full high"></i><a href="#">{{{trans_choice('app.popularity',1)}}}</a></li>
				   @endif
				 @endif
				 
				 <li class="divider_left_menu"></li>
				   
				    {{{Theme::render('main_menu')}}} 
				    
				    <li class=""><a href="{{{url('blockusers')}}}"><i class="material-icons pull-left material-icon-custom-styling">block</i>{{{ trans("app.blockedusers")}}}</a></li>
                </ul>
            </div>
             
             
              

 

            <div class="your-prof text-center ad-col-left left_add">
                 {{{Theme::render('left-sidebar-ad')}}}
            </div>   
        
      </div>
      
      



<div id="myModalPayment" class="modal fade" data-backdrop="static"> 
	    <div class="modal-dialog">
 			<div class="modal-content">
	 			<div class="loader_payment"></div>
				<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				        <h4 class="modal-title  superpower-invisible-header" ng-bind="myModalPaymentHeading"></h4>
			    </div>
	             <div class="modal-body">
		             <!-- <img style="float:left" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/invisible.png')" width="40" height="40" class="robot_popup"> -->
				        <p ng-bind="myModalPaymentSubHeading"></p>
				        
   					 	
  					  	 
   						 <div class="payment_getway " id="payment_getway">
							  <!-- Nav tabs -->
							  <ul class="nav nav-tabs" role="tablist">
							    {{{Theme::render('payment-tab')}}}
							  </ul>
						</div>
						
						<!--hidden fields for any payment gateway-->
						
						  <!-- Tab panes -->
					  	    <div class="tab-content" id="tab-content">
			   				  {{{Theme::render('payment-tab_content')}}}
			   				  <span class="no-content-payment">{{trans('app.click_payment_option')}}</span>
		 	 				</div>
		 	 				
		 	 				    <div class="activate_superpower" style="display: none">
						  	    
						  	   <button href="#/" class="btn btn_refill" data-toggle="modal">{{{trans('app.upgrade_premium')}}}</button>		
						  	</div>
	            	</div> <!-- modal body ends -->
	           </div>
 			
 			<div class="dating-modal--payments__features text--left credits_features">
	 			<h5>{{trans('app.use_your_credits_for')}}</h5>
	 			
	 			<ul class='paywallFeatures '>
				      <li > <img  src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="20" height="20" data-src="@theme_asset('images/rocket.svg')" /><span>{{trans('app.boost_your_profile')}}</span></li>
				      <li ><img data-src="@theme_asset('images/spotlights.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="20" width="20"/><span>{{trans('app.step_into_spotlight')}}</span></li>
				      
				       <li > <img data-src="@theme_asset('images/up-arrow.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="20" width="20"/><span>{{trans('app.rise_up_in_people_nearby')}}</span></li>
				      
				</ul>
				
				
 			</div>	
 			
 			
 			
 			<div class="dating-modal--payments__features text--left superpower_features">
	 			<h5>{{trans('app.use_your_superpower_for')}}</h5>
 				<ul class='paywallFeatures '>
				      <li > <img  src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" width="20" height="20" data-src="@theme_asset('images/rocket.svg')" /><span>{{trans('app.boost_your_profile')}}</span></li>
				      <li ><img data-src="@theme_asset('images/spotlights.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="20" width="20"/><span>{{trans('app.step_into_spotlight')}}</span></li>
				      
				       <li > <img data-src="@theme_asset('images/up-arrow.svg')" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" height="20" width="20"/><span>{{trans('app.rise_up_in_people_nearby')}}</span></li>
				        <li ><img  src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/invisible.png')" width="20" height="20" /><span>{{trans('app.browse_invisibly')}}</span></li>
				</ul>
 			</div>
 			
 			
	        </div>
 </div>
	    <input type = "hidden" value ="{{{trans_choice('app.invisible_modal_title',0)}}}" id = "invisible-modal-header">
	    
	    @section('leftsidebar-scripts')
		@parent
		
		<script>
			console.log('leftsidebar scripts');
	$('input[name="checkbox1"]').click(function(e) {
	  	if ($('input[name="checkbox1"]').length == $("input[name='checkbox1']:checked").length) {
	       
	       
	       $(".loader").fadeIn("slow");
	       
	       //check if super powers are active
	       $.post("{{{ url('/isSuperPowerActivated/') }}}"+'/'+'{{{$auth_user->id}}}', function(data){


				$(".loader").fadeOut("slow");
				
				if(!data)
				{
					//open activate super power pop up
					//$('.superpower-invisible-header').text($("#invisible-modal-header").val());
					
					//$('#myModalInvisibleMode').modal('show');
					
					angular.element('#AppController').scope().openPaymentModal('superpower',{'invisible':'1'});
					
					$('.close').on('click', function () {
						$(this).data('clicked', true);
					    $('input[name="checkbox1"]').attr("checked",false);
					})
					
					
					
					if(!$('.close').data('clicked')) 
					{
							//$('#superpower_invisible').val('1');
							
							
							$('input[name="checkbox1"]').attr("checked",true);
					}
				}
				else
				{
					
					
					$.post("{{{ url('/activate_invisible_mode') }}}", function(data){
						
						$(".invisible_mode_popup").fadeIn("slow");
						
						
						
						
						//show you are invisible pop up
						$('input[name="checkbox1"]').attr("checked",true);
						
						
						$(".invisible_mode_popup").fadeOut("slow");
						
						toastr.info("You are now invisible");
					});	
					
					
				}
				
				

			});
	       
	   } else {
		    $(".loader").fadeIn("slow");
		   
		   $.post("{{{ url('/deactivate_invisible_mode') }}}", function(data){
						
						$(".loader").fadeOut("slow");
						//show you are invisible pop up
						$('input[name="checkbox1"]').attr("checked",false);
						
						toastr.info("You are now visible");
						
					});	
		   
	     
	   }
	});

</script>

<script>
	paymentPackages="{{{url('/payment/packages')}}}";
</script>


		
		@endsection