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
       
      
      

      {{{Theme::render('styles')}}}
      
           <script src="{{{url('core.js')}}}"></script>	
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
	 superpower_payment_url="{{{url('superpower')}}}";
	 reportPhoto_url="{{{ url('/photo/report') }}}";
	 currency="{{{$currency}}}";
	 reportUser ="{{{ url('/user/report') }}}";
</script>	





</head>

<body ng-controller="AppController" id="AppController">  
	
		<div id="progress" class="waiting">
    <dt></dt>
    <dd></dd>
</div>

{{{Theme::render('chat')}}}

  <div class="wrap">
      @include(Theme::layout('header'))
      <section>
        <div class="container box">
            <div class="row">
                <div class="container" style="max-width: 1200px;">
                  @include(Theme::layout('left-sidebar'))
                  @section('content')
                       
                      <div class="col-md-10 pad ">
                        <div class="row">
                          @parent

                         @show
                        
                        </div>

                        <div class="row" style="margin-top:24px;background-color:inherit;box-shadow:none;">
                           <div class="col-md-12 pad text-center ad-col-bottom" style="display:inline">
                           {{{Theme::render('bottom-ad')}}}
                           </div>
                        </div>
                      </div>
                  
                </div>
            </div>
        </div>
      </section>
  </div>
  
  
  <!-- rise in popularity -->
<div id="myModalRiseup" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 33%">

    <!-- Modal content-->
    <div class="modal-content ">
      <div class="">
        
        <h4 class="report_photo_title">Rise up in People nearby</h4>
        <h5 class="riseup_text">Take first place, get more visitors and messages. Rise up to number one and get new visitors!</h5>
      </div>
      <div class="modal-body">
            <h2 style="color: black">Your popularity: 
              
              @if($auth_user->profile->popularity < 10)
           <li data-toggle="tooltip"  class="popular_boost" title="very low"><i class="fa fa-battery-empty very_very_low"></i></li>
          
           @elseif($auth_user->profile->popularity >= 10 && $auth_user->profile->popularity < 25)
           <li data-toggle="tooltip"  class="popular_boost" title="low"><i class="fa fa-battery-quarter very_low"></i></li>
           
           @elseif($auth_user->profile->popularity >= 25 && $auth_user->profile->popularity < 50)
           <li data-toggle="tooltip" class="popular_boost"  title="average"><i class="fa fa-battery-half low"></i></li>
           @elseif($auth_user->profile->popularity >= 50 && $auth_user->profile->popularity < 75)
           <li data-toggle="tooltip"  class="popular_boost" title="popular"><i class="fa fa-battery-three-quarters medium"></i></li>
           @else
           <li data-toggle="tooltip"  class="popular_boost" title="very popular"><i class="fa fa-battery-full high"></i></li>
           @endif
                
              
            </h2>
            
                  
        <button type="button" class="btn btn-default custom_modal-popup3 riseup_to_numberone"  ng-click="addtoRiseUp()"><div class="loaderUpload"></div><span class="">Rise to number one!</span></button>
       
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
                    <h5 style="color:#238CAF">Your payment is being processed. You will be notified later! </h5>
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

var App = angular.module('App', ['ngFileUpload','emoji','ngSanitize','angularMoment','matchMedia','ngAnimate'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

App.csrf_token = "{{{ csrf_token() }}}";
App.urls = [];

App.urls.get_notifications = "{{{url('/get_notifications')}}}";
App.urls.poll_notifications = "{{{url('/poll_notifications')}}}/";


App.controller("AppController",["$scope", "$rootScope","$http", function($scope, $rootScope,$http){


$scope.creditBalance="{{{$credit->balance}}}";


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


}]);

</script>

 <!-- Include all compiled plugins (below), or include individual files as needed -->
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
     
     @yield('header-scripts')
   
   <script>
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
</script>

@yield('leftsidebar-scripts')
  
 
 	  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>

 
 
  
  
  

<script>
  function random(name)
{
  var str=name;
  $('.gateid').val(str.trim());
  //alert(name);
}
</script>  
 <script>
 (function(){
 
  $(".controller").click(function(){
    id = $(this).attr("id");
    
    $(".controller-container").find(".is_current").removeClass("is_current");
    $(this).addClass("is_current");
    $(".card").attr('class', 'card card--' + id);
    $("html").attr('class', 'bg--' + id);
    
  });
  
})();


 </script>  
 
 

 <script type="text/javascript">
      
      $( '.main' ).click(function(){
        
        $(this).addClass('spotlight_index');
        
      });
      
      $('#spot').on('hidden.bs.modal', function () {
          $('.main').removeClass('spotlight_index');
      });
      
</script>
 
  <script>
    jQuery(document).ready(function($){
  //open popup
  $('.cd-popup-trigger').on('click', function(event){
    //event.preventDefault();
    $('.cd-popup').addClass('is-visible');
  });
  
  $('.cd-popup-trigger2').on('click', function(event){
    //event.preventDefault();
    $('.cd-popup2').addClass('is-visible');
  });
  
  $('.cd-popup-trigger3').on('click', function(event){
    //event.preventDefault();
    $('.cd-popup2').addClass('is-visible');
  });
  
  //close popup
  $('.cd-popup').on('click', function(event){
    if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup') ) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
  
  //close popup
  $('.cd-popup2').on('click', function(event){
    if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup2') ) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
  
  //close popup
  $('.cd-popup3').on('click', function(event){
    if( $(event.target).is('.cd-popup-close') || $(event.target).is('.cd-popup3') ) {
      event.preventDefault();
      $(this).removeClass('is-visible');
    }
  });
  
  
  //close popup on no
  $('.no').on('click',function(){
    
    $('.cd-popup').removeClass('is-visible');
    
    
  });
  
  
  //close popup on no
  $('.no2').on('click',function(){
    
    $('.cd-popup2').removeClass('is-visible');
    
    
  });
  
  //close popup on no
  $('.no3').on('click',function(){
    
    $('.cd-popup3').removeClass('is-visible');
    
    
  });
  //close popup when clicking the esc keyboard button
  $(document).keyup(function(event){
      if(event.which=='27'){
        $('.cd-popup').removeClass('is-visible');
      }
    });
});
    </script>
    
   



  <script>
    
    function add_to_spotlight()
    {
      
      var Request= {
        
        id:'{{{$auth_user->id}}}'
      };
      
       $('.loaderUpload').fadeIn();
      
      $.post("{{{ url('/user/spotlight/add') }}}",Request, function(data){

         $('.loaderUpload').fadeOut();
        toastr.info('Added to spotLight');
        window.location.reload();

      });

      
    }
  </script>
  


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
          $(this).find('strong').html(parseInt(100 * val) + '<i>%</i>');
      });   
        
      
        
    });
   </script> 
    <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        tooltipClass: "tooltip_new",
}); 
});
</script>


      
<script>
  
  
  $('.spotlighthover').mouseover(function(){
      $(this).addClass('imghover');
    
    //alert(this);
  })
  
  $('.spotlighthover').mouseout(function(){
      $(this).removeClass('imghover');
    
    //alert(this);
  })
  
 </script> 
 
 <script>




var refill_package_id;


$(document).ready(function(){

$( ".packages" ).change(function () { 

				var parent = $(this).closest('.credit-card-box');
				
				
				parent.find(".amount").html(currency+$(this).find(':selected').data('amount'));
				parent.find(".amount-form").val($(this).find(':selected').data('amount'));
				
				
				refill_package_id = $(this).find(':selected').data('package-id');
				
				parent.find("input[name=description]").val($(this).find(':selected').data('description'));
				
				
				parent.find('.packageId').val(refill_package_id);
				
				
				if(!parseFloat($(this).find(':selected').data('amount')))
				{
					$('.tab-content').fadeOut();
					$('.activate_superpower').fadeIn();
				}
				else{
					$('.activate_superpower').fadeOut();
					$('.tab-content').fadeIn();
				}

});






});



</script> 

<script>
  function random(name)
{
  var str=name;
  $('.gateid').val(str.trim());
  
}

$('div.payment_getway ul li').on('click',function(){
	
					var name=$(this).attr('name');
					
					$('.no-content-payment').hide();
	
					var listItems_content = $("#tab-content div.tab-pane");
					listItems_content.each(function(idx, li) {
					    var item = $(li);
					    
					    if(item.attr('name') === name)
					    	{
						    	item.show();
					    	}
					    	else{
						    	item.hide();
					    	}
					    
					  })
					    

})



</script>  





<script>
  
$('#myModalPayment form').submit(function(e){
  
  
  e.preventDefault();
  
  var $form= $(e.currentTarget);
  
  
  	if(isNaN(parseFloat($form.closest('.credit-card-box').find(':selected').data('amount'))))
	{
		toastr.error('Please select some package');
		return false;
	}
  
  var paymentName = $form.attr('id');
  

  
  //call JS for  specific payment
  
  eval(paymentName)($form);
  
  //$form.get(0).submit(); 
  
})
</script>




 <script>
  $(document).ready(function()
  {
  $("#sidebar-fixed-btn").click(function()
  {
    $("#menu-toggle").click();
  });
  });
</script>

<script>
	$( document ).ready(function() {
        $({property: 0}).animate({property: 105}, {
            duration: 600,
            step: function() {
                var _percent = Math.round(this.property);
                $('#progress').css('width',  _percent+"%");
                if(_percent == 105) {
                    $("#progress").addClass("done");
                }
            },
            complete: function() {
                //alert('complete');
                $("#progress").fadeOut('slow');
            }
        });
    });
</script>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
  @section('scripts')
@show


@yield('plugin-scripts')	

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




</body>


</html>
