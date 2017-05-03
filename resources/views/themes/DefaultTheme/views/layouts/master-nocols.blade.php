<?php use App\Components\Theme;?>
<!DOCTYPE html>
<html lang="en" ng-app="App">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{{ csrf_token() }}}">





{{{Theme::render('metaTags')}}}
<title>{{{$website_title}}}</title>
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
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
	 superpower_payment_url="{{{url('superpower')}}}";
	 reportPhoto_url="{{{ url('/photo/report') }}}";
	 currency="{{{$currency}}}";
	 reportUser ="{{{ url('/user/report') }}}";
</script>	

<style>
	.cont-c1{
    color: rgb(119, 112, 112);
   
}

.cont-cover1 a{
        color: #cc0727 !important;
   
}

.cont-c1 li{
           list-style-type: decimal;
    padding: 1%;
   
}
</style>	

</head>

<body ng-controller="AppController">  

<!-- 	<div class="loader"></div> -->

	<div id="progress" class="waiting">
    <dt></dt>
    <dd></dd>
</div>
	
	<div class="invisible_mode_popup"></div>
	

  <div class="wrap">
  	@if($auth_user)
      @include(Theme::layout('header'))
     @else
     	@include(Theme::layout('header-nocols'))
     @endif

      <section>
        <div class="container box" style="height:100vh">
            <div class="row col-sm">
                <div class="container" style="max-width: 1233px;">
                  
                  @section('content')
                          
                      <div class="col-md-12 pad cont-c1">
                        <div class="row">
                          @parent

                         @show
                        
                        </div>

                        <div class="row" style="margin-top:24px;background-color:inherit;box-shadow:none;">
                           <div class="col-md-12 pad text-center ad-col-bottom" style="display:inline">
                           
                           </div>
                        </div>
                      </div>
                  
                </div>
            </div>
        </div>
      </section>
  </div>
  
  
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




@if($auth_user)




var App = angular.module('App', ['ngFileUpload','emoji','ngSanitize','angularMoment','matchMedia','ngAnimate'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

App.csrf_token = "{{{ csrf_token() }}}";
App.urls = [];

App.urls.get_notifications = "{{{url('/get_notifications')}}}";
App.urls.poll_notifications = "{{{url('/poll_notifications')}}}/";


App.controller("AppController",["$scope", "$rootScope","$http", function($scope, $rootScope,$http){




$scope.next_users = [];



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

res.success(function(data, status, headers, config) {
	
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

@endif

</script>


			

 <!-- Include all compiled plugins (below), or include individual files as needed -->

<!--    <script type="text/javascript" src="@theme_asset('js/jquery.easing.1.3.js')"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<!--    <script type="text/javascript" src="@theme_asset('js/jquery.mousewheel.js')"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.4/jquery.mousewheel.min.js"></script>


   <script type="text/javascript" src="@theme_asset('js/jquery.contentcarousel.js')"></script>

   <script type="text/javascript">
      $('#ca-container').contentcarousel();
   </script>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--    <script src="@theme_asset('js/jquery.mCustomScrollbar.concat.min.js')"></script> -->
   <script src="@theme_asset('js/slider.js')"></script>
<!--    <script src="@theme_asset('js/thumbnail-slider.js')"></script> -->
   <script src="@theme_asset('js/BootSideMenu.js')"></script>
   
<!--
   <script src="{{{asset('js/jquerypp.custom.js')}}}"></script>
   <script src="{{{asset('js/jquery.elastislide.js')}}}"></script>
-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timeago/1.5.3/jquery.timeago.min.js"></script>
   
       <script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.8.3/underscore-min.js" type="text/javascript"></script>
  

   <script src="@theme_asset('js/custom.js')" type="text/javascript"></script>

       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.1.3/circle-progress.min.js"></script>
   
   <script>
      $(document).ready(function(){
        $('#location').click(function(){
           $('.bg-wh h3:first').css("display","none");
           $('.bg-wh p:first').css("display","none");
           $('.location').css("display","block");
        });
        $('#here_to').click(function(){
           $('#here_to_box').css("display","none");
           $('.here_to').css("display","block");
        });
      });
   </script>
   
   <script>
   $(document).ready(function(){
        $("#showsettings").click(function()
        {
           $("#hidden").show();
           $("#close-currentlocation").show();
           $("#close-currentlocation1").hide();
        });
       $("#closemark").click(function()
       {
        $("#hidden").hide();
         $("#close-currentlocation").hide();
         $("#close-currentlocation1").show();
       });
     });
   </script>

  
 <script>
 $(document).ready(function(){
 $(".toggler").click(function()
 {

   //$(".col-nothing").toggle();
 });
   

});
 </script>  
 <script>
 $(document).ready(function()
 {
  /*
  $("#side-menu-button").click(function()
  {
    $(".toggler").click();
  });*/
 });
 </script>  
 
 <script type="text/javascript">
			
			$( '#carousel' ).elastislide();
			
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
	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})
</script>	


<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
})



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
 
 <script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        tooltipClass: "tooltip_new",
}); 
});
</script>

<script>
	jQuery(document).ready(function() {
  jQuery("time.timeago").timeago();
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
            }
        });
    });
</script>	
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
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular-sanitize.js"></script>


@yield('plugin-scripts')
</body>


</html>
