<?php use App\Components\Theme;?>
<!DOCTYPE html>
<html lang="en" ng-app="App" id="html_eesh" class="o-wrapper">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{{ csrf_token() }}}">





{{{Theme::render('metaTags')}}}
<title>{{{$website_title}}}</title>
	  <link rel="icon" href="{{{asset('uploads/favicon')}}}/{{{$website_favicon}}}" type="image/gif" sizes="16x16">
	
	  <!-- Bootstrap -->
<!--       <link href="@theme_asset('css/bootstrap.min.css')" rel="stylesheet"> -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css">

      
<!--       <link rel="stylesheet" href="@theme_asset('css/style.css')" type="text/css"> -->
     
     
<!--       <link href="@theme_asset('css/font-awesome.min.css')" rel="stylesheet"> -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" type="text/css">



      <link rel="stylesheet" href="@theme_asset('css/jquery.mCustomScrollbar.css')" type="text/css">
<!--       <link rel="stylesheet" href="@theme_asset('css/scroller.css')" type="text/css" /> -->
     
<!--       <link rel="stylesheet" href="@theme_asset('css/jquery-ui.css')"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.css" type="text/css">




<!--       <link rel="stylesheet" href="@theme_asset('css/BootSideMenu.css')" type="text/css"> -->
      <!-- <link href="@theme_asset('css/custom.css')" rel="stylesheet" /> -->
<!--       <link href="@theme_asset('css/sidebar.css')" rel="stylesheet"type="text/css" /> -->
     
<!--       <link href="@theme_asset('css/settings.css')" rel="stylesheet" /> -->
      
<!--       <link href="@theme_asset('css/robotofont.css')" rel='stylesheet' type='text/css'> -->


<!--       <link href="@theme_asset('css/people-nearby.css')" rel="stylesheet" /> -->
<!--       <link href="@theme_asset('css/media-queries.css')" rel="stylesheet" type="text/css"/> -->
      <link href="@theme_asset('css/material-custom.css')" rel="stylesheet" type="text/css">
<!--       <link href="@theme_asset('css/toastr.css')" rel="stylesheet"> -->
      <link href="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.css" rel="stylesheet" type="text/css">
       @yield('plugin-css')
       
       <link href="@theme_asset('css/custom.css')" rel="stylesheet" type="text/css"/>
       
       <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" type="text/css">
       
       <link href="@theme_asset('css/lightbox.css')" type="text/css" rel="stylesheet">
       
<!--        <link href="@theme_asset('css/bootstrap-datepicker3.css')" type="text/css" rel="stylesheet"> -->
       

	 
<!-- 	  <link rel="stylesheet"   href="@theme_asset('css/jquerycounter.css')" type="text/css" />  -->
	  
	  
	  
	   <!-- <link rel="stylesheet" href="@theme_asset('css/embed.css')"/> -->
	   
	   
<!-- 	   <link rel="stylesheet" href="@theme_asset('css/fancybox.css')" type="text/css" media="screen" /> -->
	   
	    
	 
 <style>



[ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak, .ng-hide {
    display: none !important;
}

		  </style>
      

      {{{Theme::render('styles')}}}


      <script src="{{{url('core.js')}}}"></script>	
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
	 superpower_payment_url="{{{url('superpower')}}}";
	 reportPhoto_url="{{{ url('/photo/report') }}}";
	 currency="{{{$currency}}}";
	 reportUser ="{{{ url('/user/report') }}}";
	 filter_url="{{{ url('/home/searchfilter') }}}";
	 api_error="{{{trans_choice('app.error',1)}}}";
	 failed_save="{{{trans('app.fail_save')}}}";
	success_save="{{{trans('app.save_success')}}}";
	peoplenearby_url="{{{url('peoplenearby')}}}"; 
	encounter_url="{{{url('encounter')}}}"; 
	
	visited="{{{trans('notification.visited_profile')}}}";
	match="{{{trans('notification.match')}}}";
	liked_you ="{{{trans('notification.liked_you')}}}";
	fortumo_success= "{{{trans('notification.fortumo_success')}}}";
</script>	


</head>

<body ng-controller="AppController"  id="AppController">  
	
	
	<div id="progress" class="waiting">
    <dt></dt>
    <dd></dd>
</div>

<!-- 	<div class="loader"></div> -->
	
	<div class="invisible_mode_popup"></div>
	

{{{Theme::render('chat')}}}

  <div class="wrap" >
      @include(Theme::layout('header'))
      <section>
        <div class="container box ng-cloak" >
            <div class="row col-sm">
                <div class="container" style="max-width: 1233px;">
                  @include(Theme::layout('left-sidebar'))
                  @section('content')
                          <!--content view  add-boxshadow-to-vistors-view-->
                      <div class="col-md-7 pad cont-cover1">
                        <div class="row">
                          @parent

                         @show
                        
                        </div>

                        <div class="row" style="margin-top:24px;background-color:inherit;box-shadow:none;">
                           <div class="col-md-12 pad hidden-xs text-center ad-col-bottom" style="display:inline">
                           {{{Theme::render('bottom-ad')}}}
                           </div>
                        </div>
                      </div>
                  @include(Theme::layout('right-sidebar'))
                </div>
            </div>
        </div>
      </section>
  </div>
  
  <!-- Report photo Modal -->
<div id="myModalReportPhoto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content">
      <div class="">
        
        <h4 class="report_photo_title">{{{trans('app.whats_wrong_photo')}}}</h4>
      </div>
      <div class="modal-body user_block_modal_body">
	      		<div class="radiocnt">
					<input type="radio" name="radio" id="radio1" class="radio" value="It's inappropriate"/>
					<label for="radio1"><span></span>{{{trans('app.inappropriate')}}}</label>
				</div>
				
				<div class="radiocnt">
					<input type="radio" name="radio" id="radio2" class="radio" value="It is a celebrity"/>
					<label for="radio2"><span></span>{{{trans('app.celebrity')}}}</label>
				</div>
				
				<div class="radiocnt">	
					<input type="radio" name="radio" id="radio3" class="radio" value="Other"/>
					<label for="radio3"><span></span>{{{trans('app.others')}}}</label>
				</div>
				
				<br/>
        <textarea name="text" class="user_reason" id="user_reason" placeholder="Your comments (Required)"></textarea>
        </div>
      <div class="" style="text-align: center">
       
        <input type="hidden" class="reason"/>
        <button type="button" class="btn btn-default report_photo_modal" data-dismiss="modal">{{{trans('app.report')}}}</button>
      </div>
    </div>

  </div>
</div>


<!-- rise in popularity -->
<div id="myModalRiseup" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content ">
      <div class="">
        
        <h4 class="report_photo_title">{{{trans('app.master_riseup_title')}}}</h4>
        <h5 class="riseup_text">{{{trans('app.master_riseup_text')}}}</h5>
      </div>
      <div class="modal-body">
	      		<h2 style="color: black">{{{trans('app.your_popularity')}}}: 
		      		
		      		@if($auth_user->profile->popularity < 10)
				   <li data-toggle="tooltip"  class="popular_boost" title="{{{trans_choice('app.popularity_choice',0)}}}"><i class="fa fa-battery-empty very_very_low"></i></li>
				  
				   @elseif($auth_user->profile->popularity >= 10 && $auth_user->profile->popularity < 25)
				   <li data-toggle="tooltip"  class="popular_boost" title="{{{trans_choice('app.popularity_choice',1)}}}"><i class="fa fa-battery-quarter very_low"></i></li>
				   
				   @elseif($auth_user->profile->popularity >= 25 && $auth_user->profile->popularity < 50)
				   <li data-toggle="tooltip" class="popular_boost"  title="{{{trans_choice('app.popularity_choice',2)}}}"><i class="fa fa-battery-half low"></i></li>
				   @elseif($auth_user->profile->popularity >= 50 && $auth_user->profile->popularity < 75)
				   <li data-toggle="tooltip"  class="popular_boost" title="{{{trans_choice('app.popularity_choice',3)}}}"><i class="fa fa-battery-three-quarters medium"></i></li>
				   @else
				   <li data-toggle="tooltip"  class="popular_boost" title="{{{trans_choice('app.popularity_choice',4)}}}"><i class="fa fa-battery-full high"></i></li>
				   @endif
		      		  
		      		
		        </h2>
	      		
	      				
				<button type="button" class="btn btn-default custom_modal-popup3 riseup_to_numberone"  ng-click="addtoRiseUp()"><div class="loaderUpload"></div><span class="">{{{trans('app.rise_number_one')}}}</span></button>
       
      </div>
    </div>

  </div>
</div>



<!-- encounters exceeds modal -->
<div id="myModalExceedsEncounters" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content encounterexceeds_modal">
      <div class="">
        
        <h4 class="report_photo_title">{{{trans('app.exceed_daylimit')}}}</h4>
      </div>
      <div class="modal-body user_block_modal_body">
	     <div class="clock_cnt"> 
		     {{{trans('app.wait_till')}}}..
	     	<span id="clock"></span>
	     </div>
		  <!-- <p id="note" ></p> -->
		 <div style="
    font-size: 16px;
    color: red;
    /* text-align: center; */
    margin-left: 33%;
">-{{trans('app.or')}}-</div>
	      		
        </div>
      <div class="" style="text-align: center">
       
        
        <button type="button" class="btn btn-default encounter_exceeds" data-dismiss="modal">{{{trans('app.upgrade_premium')}}} {{{trans('app.to_continue_instantly')}}}</button>
      </div>
    </div>

  </div>
</div>






 <!-- User block Modal -->
<div id="myModalReportUser" class="modal fade" role="dialog">
  <div class="modal-dialog" >

    <!-- Modal content-->
    <div class="modal-content user_block_modal_content">
      <div class="">
        
        <h4 class="report_photo_title">{{{trans('app.whats_problem')}}}</h4>
      </div>
      <div class="modal-body user_block_modal_body">
	      		<div class="radiocnt radiocnt2">
					<input type="radio" name="radio" id="radio01" class="radio" value="I just don't like her/him"/>
					<label for="radio01"><span></span>{{{trans_choice('app.report_profile_choice',0)}}}</label>
				</div>
				
				<div class="radiocnt radiocnt2">
					<input type="radio" name="radio" id="radio02" class="radio" value="Rude or abusive"/>
					<label for="radio02"><span></span>{{{trans_choice('app.report_profile_choice',1)}}}</label>
				</div>
				
				<div class="radiocnt radiocnt2">	
					<input type="radio" name="radio" id="radio03" class="radio" value="Has inappropriate content"/>
					<label for="radio03"><span></span>{{{trans_choice('app.report_profile_choice',2)}}}</label>
				</div>
				
				<div class="radiocnt radiocnt2">	
					<input type="radio" name="radio" id="radio04" class="radio" value="Using fake photos"/>
					<label for="radio04"><span></span>{{{trans_choice('app.report_profile_choice',3)}}}</label>
				</div>
				
				<div class="radiocnt radiocnt2">	
					<input type="radio" name="radio" id="radio05" class="radio" value="Running a scam"/>
					<label for="radio05"><span></span>{{{trans_choice('app.report_profile_choice',4)}}}</label>
				</div>
				
				<div class="radiocnt radiocnt2">	
					<input type="radio" name="radio" id="radio06" class="radio" value="Other"/>
					<label for="radio06"><span></span>{{{trans('app.others')}}}</label>
				</div>
				
				
				
				<br/>
        <textarea name="text" class="user_reason_abuse" id="user_reason_abuse" placeholder="Your comments (Required)"></textarea>
        </div>
      <div class="" style="text-align: center">
       
        <input type="hidden" class="reason"/>
        <input type="hidden" name="userid" value=""/>
        <button type="button" class="btn btn-default report_user_modal" data-dismiss="modal">{{{trans('app.report')}}}</button>
      </div>
    </div>

  </div>
</div>


<div class="modal fade" id="processing-modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <img src="@theme_asset('images/loading.gif')" class="icon" />
                    <h5 style="color: #238CAF;">Processing... <button type="button" class="close" style="float: none;" data-dismiss="modal" aria-hidden="true">×</button></h5>
                    <h5 style="color:#238CAF">Your payment is being processed. You will be notified later!</h5>
                </div>
            </div>
        </div>
    </div>
</div>	

<div class="modal fade" id="processed_modal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-check" style="    font-size: 100px;
    color: #5CB85C;"></i>
                   
                    <h5 style="color:#238CAF">Your payment is processed.!</h5>
                    <button type="button" class="btn btn-success paymentDone" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>
</div>

<input type = "hidden" value ="{{{trans_choice('app.invisible_modal_title',0)}}}" id = "invisible-modal-header">

 <!-- Include all compiled plugins (below), or include individual files as needed -->
 
 
 
 





<!--      <script src="{{{ asset('js/angular.min.js') }}}"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.8/angular.min.js"></script>

<!-- <script src="@theme_asset('js/bootstrap.min.js')"></script>  -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

 
 
<!--   <script src="@theme_asset('js/toastr.js')"></script> -->
<script src="https://cdn.jsdelivr.net/toastr/2.1.3/toastr.min.js"></script>

<!--    <script src="@theme_asset('js/modernizr.js')"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js"></script>
   
   


<!-- <script src="@theme_asset('js/moment.min.js')"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/angular.js/1.4.0/angular-animate.min.js"></script>
   
   
   
<script>

var App = angular.module('App', ['ngFileUpload','emoji','ngSanitize','angularMoment','matchMedia','ngAnimate'],  function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

App.csrf_token = "{{{ csrf_token() }}}";
App.urls = [];

App.urls.get_notifications = "{{{url('/get_notifications')}}}";
App.urls.poll_notifications = "{{{url('/poll_notifications')}}}/";


App.controller('VideoChatController',function($scope,$rootScope,$http, socket, $timeout,$location,$anchorScroll) {

	$timeout(function() {
	    $rootScope.$broadcast('video_injection','video chat injected');
	
	});
	 		

 })	

App.controller("AppController",["$scope", "$rootScope","$http", function($scope, $rootScope,$http){







$scope.openPaymentModal= function(type,metadata)
{
	
	
	$('#myModalPayment').modal('show');
	
	$scope.fetchPaymentModal(type,metadata);
	
	
	if(type=='credit')
	{
		$('.credits_features').show();
		$('.superpower_features').hide();
	}
	else if(type=='superpower')
	{
		$('.credits_features').hide();
		$('.superpower_features').show();
	}
	
	
}



$scope.fetchPaymentModal= function(type,metadata)
{
	$scope.packages=[];
	
	$('.loader_payment').fadeIn();
	
	 $http.get(paymentPackages+'?type='+type)
        .then(function(response){

            if (response.status == 200) {
	            
	            $('.loader_payment').fadeOut();

                $scope.myModalPaymentHeading = response.data.heading;
                
                $scope.myModalPaymentSubHeading = response.data.subheading;
                
                
                _.each(response.data.gateways, function(item){
	                
	                
	               $scope.packages.push(item.packages);
	                
	                
                })
                
                
                
                $scope.gateways= response.data.gateways;
                
                	var listItems = $("#payment_getway ul li");
					listItems.each(function(idx, li) {
					    var item = $(li);
					    
					    
					    
					    check = _.some( $scope.gateways, function( el ) {
						    return el.name === item.attr('name');
						} );
						
						if(!check)
						{
							
							item.hide();
							
						}
						else
						{
							item.show();
						}
					
					   
					});
					
					
					var listItems_content = $("#tab-content div.tab-pane");
					listItems_content.each(function(idx, li) {
					    var item = $(li);
					    
					    
					    
					    check_content = _.some( $scope.gateways, function( el ) {
						    return el.name === item.attr('name');
						} );
						
						if(!check_content)
						{
							
							item.hide();
							
						}
						
						
						
						_.each($scope.gateways,function(el){
							
							if(el.name === item.attr('name'))
							{
								
							
								item.find('select.packages').empty();
								
								_.each(el.packages, function (e) {
						            item.find('select.packages').append($("<option></option>").attr("value", e.name).attr("data-amount",e.amount).attr("data-package-id",e.id).attr("data-description",e.description).text(e.name));
						        });
						        
						        
						        item.find('select.packages').prepend("<option value='' data-amount='novalue' selected='selected'>{{trans('app.select_package')}}</option>");
						        
						        

						        
								
							}
							
							
						})
					
					   
					});
					
					
					
					
					$("input[name=feature]").val(type);
					
					$("input[name=metadata]").val(JSON.stringify(metadata) );
					
					
					

            }


        }
        ,function(response){});
	
}


$scope.next_users = [];

$scope.userid='{{{$auth_user->id}}}';

$scope.username='{{{$auth_user->name}}}';
	

if (window.location.href.indexOf("/profile") > -1) {
	
	
	$scope.userid = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    
    $scope.otheruserid= window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
}





$scope.addtoRiseUp= function()
{
	$('.loaderUpload').fadeIn();
	var res=$http.post("{{{ url('/riseup/pay') }}}" ,{id:$scope.userid});

	res.success(function(data, status, headers, config) {
		
			
		$('.loaderUpload').fadeOut();
		
			$('#myModalRiseup').modal('hide');
	});

	res.then(function(data){
		
		
			toastr.info('You have raised your profile. Get seen more by people now!');	  
	})
	
}


			
var res=$http.post("{{{ url('/social_verification') }}}/"+ $scope.userid,{});

res.then(function(data) {
	
		$scope.social_login_verify= data;
	
});
			



$rootScope.$on('encounter_list_updated', function(event, message){


  $scope.next_users = [];
   $scope.next_users_chat = message;
  for (var i = message.encounters_list.length - 1; i > 0; i--) {
    $scope.next_users.push(message.encounters_list[i].user.profile_pic_url);
  };
  $scope.next_users= $scope.next_users.reverse();
  console.log(message.encounters_list);
  
});



$rootScope.$on('next_user', function(event, message){

 $scope.next_users = [];
 
 
  for (var i = message.length - 1; i > 0; i--) {
    $scope.next_users.push(message[i].user.profile_pic_url);
  };
  $scope.next_users= $scope.next_users.reverse();
 
  
});


$scope.likeyou= function()
{
				var Request= {};
				
				$(".loader").fadeIn();
				
				
				
				$('.right-cross-alpha').fadeOut();
				
				
				
				var user_id= $('.blockUser').data('user-id');
				
				
				
				
					
					$.post("{{{ url('/liked') }}}/"+ user_id+"/1",Request, function(data){
		
						
						
						
						if(data.status=='error')
						{
							$(".loader").fadeOut();
							toastr.info('You cant like this person');
						}
						else
						{
							$(".loader").fadeOut();
							toastr.info('You liked this person');
							$('.onlylike').fadeIn();
						//window.location.reload();
						}
						
						if($("#otheruser").data("user-isliked")== 1 ){
	
							$('#matchModalProfile').modal("show");
	
						}
				
		
					});
}

$scope.dislikeyou= function()
{
				var Request= {};
				$(".loader").fadeIn();
				
				$('.right-cross-alpha').fadeOut();
				
				
				
				var user_id= $('.blockUser').data('user-id');
					
					$.post("{{{ url('/liked') }}}/"+ user_id+"/0",Request, function(data){
		
		
					if(data.status=='error')
					{
						$(".loader").fadeOut();
						toastr.info('You cant dislike');
					}
					else
					{
						$(".loader").fadeOut();
						toastr.info('You pressed dislike');
						$('.onlydislike').fadeIn();
						//window.location.reload();
						
					}
						
		
					});
}




$scope.gotoprofile= function(url)
{
	window.location.href=url;
}


}]);

</script>
 
 
 
 
 
 
 
 
 

<!--    <script type="text/javascript" src="@theme_asset('js/jquery.easing.1.3.js')"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<!--    <script type="text/javascript" src="@theme_asset('js/jquery.mousewheel.js')"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.4/jquery.mousewheel.min.js"></script>


   <script type="text/javascript" src="@theme_asset('js/jquery.contentcarousel.js')"></script>
   
      <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script>

<!--    <script src="{{{ url('js/notifications.js') }}}"></script> -->

   <script type="text/javascript">
      $('#ca-container').contentcarousel();
   </script>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--    <script src="@theme_asset('js/jquery.mCustomScrollbar.concat.min.js')"></script> -->
<!--    <script src="@theme_asset('js/slider.js')"></script> -->
<!--    <script src="@theme_asset('js/thumbnail-slider.js')"></script> -->
   <script src="@theme_asset('js/BootSideMenu.js')"></script>
   
 

   
<!--    <script src="@theme_asset('js/underscore.js')" type="text/javascript"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.5.3/jquery.timeago.min.js"></script>
 
  



       
<!--         <script src="@theme_asset('js/circle-progress.js')"></script> -->
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script>
        
        <script>
	         $(document).ready(function(){
		  var val = parseFloat('{{{$user_score->score}}}')/10;
		  	$('#circle2').circleProgress({
		        value: val,
		        size: 30,
		        fill: {
		            gradient: ["#526AD7 ", "#F82856 "]
		        }
		    }).on('circle-animation-progress', function(event, progress) {
			    $(this).find('strong').html(parseInt(100 * val));
			});		
		    
		  
		    
});

	       </script> 
     @yield('header-scripts')
        
          
  


<script>
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'Cache-Control': 'max-age=1000'
        
    }
})
</script>	

@yield('leftsidebar-scripts')

<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
})



</script>

 


@yield('rightsidebar-scripts')


	

<!-- 	<script src="@theme_asset('js/lightbox2.js')"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>


  	
	
 
<!--   <script src="@theme_asset('js/jquery_counter.js')"></script> -->
  <script src="https://cdn.jsdelivr.net/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>

	 <script type="text/javascript" src="@theme_asset('js/fancybox.js')"></script>
	 
	 
	  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>
	


   <script src="@theme_asset('js/custom.js')" type="text/javascript"></script>	
   
<!--    <script src="@theme_asset('js/custom_defered.js')" type="text/javascript"></script>	 -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
  @section('scripts')
  
  
@show



@yield('plugin-scripts')



<script type="text/javascript">
	function downloadJSAtOnload() {
		var element = document.createElement("script");
		element.src = "@theme_asset('js/custom_defered.js')";
		document.body.appendChild(element);
		
		var element2 = document.createElement("script");
		element2.src = "{{{ url('js/notifications.js') }}}";
		document.body.appendChild(element2);
		
		
	}
	if (window.addEventListener)
		window.addEventListener("load", downloadJSAtOnload, false);
	else if (window.attachEvent)
		window.attachEvent("onload", downloadJSAtOnload);
	else window.onload = downloadJSAtOnload;


</script>

<script>
function init() {
	var imgDefer = document.getElementsByTagName('img');
	for (var i=0; i<imgDefer.length; i++) {
		if(imgDefer[i].getAttribute('data-src')) {
			imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
		} 
	} 

}
window.onload = init;
</script>



@if($auto_browser_geolocation=='true' && session::get('auto_browser_geolocation_save_required'))
<script type="text/javascript">
	
    $(document).ready(function() {
	    
	    getLocation();
	    
	    
	    //make loction disabled
	    if($('#city').length)
	    	$('#city').attr('disabled','disbaled');
	    
	    function getLocation() {
		    if (navigator.geolocation) {
		        navigator.geolocation.getCurrentPosition(showPosition,showError);
		    } else { 
		        toastr.info("Geolocation is not supported by this browser.");
		    }
		}
		
		 
		 
		 function showPosition(position) {
		   // toastr.info("Latitude: " + position.coords.latitude +"<br>Longitude: " + position.coords.longitude);
		    
		    codeLatLng(position.coords.latitude, position.coords.longitude);
		 }
		 
		 
		 function showError(error) {
		    switch(error.code) {
		        case error.PERMISSION_DENIED:
		            toastr.error("User denied the request for Geolocation.");
		            break;
		        case error.POSITION_UNAVAILABLE:
		            toastr.error("Location information is unavailable.").
		            break;
		        case error.TIMEOUT:
		            toastr.error("The request to get user location timed out.").
		            break;
		        case error.UNKNOWN_ERROR:
		            toastr.error("An unknown error occurred.").
		            break;
		    }
		}
		
		
		
		
		
		 function codeLatLng(lat, lng) {
			 
			 //assign values to hidden fields
			 $('#lat').val(lat);
			 $('#lng').val(lng);
			 
			  geocoder = new google.maps.Geocoder();

			var latlng = new google.maps.LatLng(lat, lng);
			geocoder.geocode({latLng: latlng}, function(results, status) {
			    if (status == google.maps.GeocoderStatus.OK) {
			      if (results[1]) {
			        var arrAddress = results;
			        
			        $.each(arrAddress, function(i, address_component) {
			          if (address_component.types[0] == "locality") {
			            console.log("City: " + address_component.address_components[0].long_name);
			            itemLocality = address_component.address_components[0].long_name;
			            
			            
			            
			            $('#cityhidden').val(itemLocality);
			            
			            
			            
			          }
			          
			          if (address_component.types[0] == "country") {
			            
			            country = address_component.address_components[0].long_name;
			            
			            
			            $('#country').val(country);
			           
			          }
			          
			        });
			        
			        if($('#city').length)
			        {
			            	$('#city').val(itemLocality+' '+country);
			            	
			         }    
			         
			         
			         
			         var Request = {
						city   :itemLocality,
						country:country,
						lat    :lat,
						long   :lng	
			    	};
			    	
			    
			
			    	$.post("{{{ url('user/profile/location/update') }}}" , Request, function(data){
			    		   	});	
			        
			        
			      } 
			    } 			});
		}
		
	    
    });
</script>

@endif


</body>


</html>
