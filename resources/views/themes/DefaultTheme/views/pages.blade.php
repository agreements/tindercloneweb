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
<link rel="icon" href="{{{asset('uploads/favicon')}}}/{{{$website_favicon}}}" type="image/gif" sizes="16x16">
<!-- Bootstrap -->
      <link href="@theme_asset('css/bootstrap.min.css')" rel="stylesheet">
      
      <link rel="stylesheet" href="@theme_asset('css/style.css')" type="text/css">
      <link href="@theme_asset('css/font-awesome.min.css')" rel="stylesheet">
      <link rel="stylesheet" href="@theme_asset('css/jquery.mCustomScrollbar.css')" type="text/css">
      <link rel="stylesheet" href="@theme_asset('css/scroller.css')" type="text/css" />
     
      <link rel="stylesheet" href="@theme_asset('css/jquery-ui.css')">
      <link rel="stylesheet" href="@theme_asset('css/BootSideMenu.css')">
      <!-- <link href="@theme_asset('css/custom.css')" rel="stylesheet" /> -->
      <link href="@theme_asset('css/sidebar.css')" rel="stylesheet" />
      <link href="@theme_asset('css/settings.css')" rel="stylesheet" />
      <link href="@theme_asset('css/credits.css')" rel="stylesheet" />
      <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
      <link href="@theme_asset('css/people-nearby.css')" rel="stylesheet" />
      <link href="@theme_asset('css/media-queries.css')" rel="stylesheet" />
      <link href="@theme_asset('css/material-custom.css')" rel="stylesheet">
      <link href="@theme_asset('css/toastr.css')" rel="stylesheet">
       <link href="@theme_asset('css/chat.css')" rel="stylesheet" />
       <link href="@theme_asset('css/custom.css')" rel="stylesheet" />
       <link media="all" type="text/css" rel="stylesheet" href="@theme_asset('css/elastislide.css')">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
       
       <link href="@theme_asset('css/lightbox.css')" rel="stylesheet">
       
       <link href="{{{asset('css/bootstrap-datepicker3v1.6.0.css')}}}" rel="stylesheet">
       
<!--
     <link rel="stylesheet" href=" @theme_asset('css/jquery.emojipicker.css')" type="text/css" />

	 <link rel="stylesheet"   href="@theme_asset('css/jquery.emojipicker.a.css')" type="text/css" /> 
-->
	 
	  <link rel="stylesheet"   href="@theme_asset('css/jquerycounter.css')" type="text/css" /> 
	  
	  <link rel="stylesheet"   href="@theme_asset('css/cropper.css')" type="text/css" /> 
	  
	   <link rel="stylesheet" href="https://cdn.jsdelivr.net/embed.js/3.6.2/embed.min.css"/>
	   
	   
	   <link rel="stylesheet" href="@theme_asset('css/fancybox.css')" type="text/css" media="screen" />
	   
	    <link rel="stylesheet" href="@theme_asset('css/flag.css')" type="text/css" media="screen" />
	 

      

      {{{Theme::render('styles')}}}
   <!--    <link rel="stylesheet" href="@plugin_asset('css/chat-style.css')" type="text/css">
    <link rel="stylesheet" href="@plugin_asset('css/chatterStyle.css')" type="text/css" />
    <link rel="stylesheet" href="@plugin_asset('chat-custom.css')" />
     -->

     <script src="@theme_asset('js/jquery-1.11.3.js')"></script>
     <script src="{{{ asset('js/angular.min.js') }}}"></script>
<!--    <script src="@theme_asset('js')/bootstrap.min.js"></script> -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script src="@theme_asset('js/toastr.js')"></script>
   <script src="http://tympanus.net/Development/Elastislide/js/modernizr.custom.17475.js"></script>
   
   
  <script type="text/javascript"  src="@theme_asset('js/jquery.emojipicker.js')"></script>
			
<script type="text/javascript"   src="@theme_asset('js/emojis.js')"></script>

<script src="@theme_asset('js/moment.min.js')"></script>






   
   
   
<script>

var App = angular.module('App', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

App.csrf_token = "{{{ csrf_token() }}}";
App.urls = [];

App.urls.get_notifications = "{{{url('/get_notifications')}}}";
App.urls.poll_notifications = "{{{url('/poll_notifications')}}}/";


App.controller("AppController",["$scope", "$rootScope","$http", function($scope, $rootScope,$http){



$scope.next_users = [];

$scope.userid='{{{$auth_user->id}}}';

$scope.username='{{{$auth_user->name}}}';
	

if (window.location.href.indexOf("/profile") > -1) {
	
	
	$scope.userid = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    
    $scope.otheruserid= window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
}


//add to chat contacts 
$scope.addToContacts= function(id)
{
	data={};
	
	data.id=id;
	
	$rootScope.$broadcast('chatToPerson', data);
	
}





$scope.openchat= function(id){
	
	data={};
	
	data.id=id;
	
	$rootScope.$broadcast('chatToMatchedPerson', data);
	
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
			

@foreach($encounter_list as $encounter)
        
         //$scope.next_users.push("{{{ $encounter->profile_pic_url }}}");
          
        @endforeach

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

<!--
{{{Theme::render('scripts')}}}
{{{Theme::render('scriptTags')}}}
-->


<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body ng-controller="AppController">  

	<div class="loader"></div>
	
	<div class="invisible_mode_popup"></div>
	

{{{Theme::render('chat')}}}

  <div class="wrap">
      @include(Theme::layout('header'))
      <section>
        <div class="container box">
            <div class="row col-sm">
                <div class="container" style="max-width: 1233px;">
                  
                  @section('content')
                          <!--content view  add-boxshadow-to-vistors-view-->
                      <div class="col-md-7 pad cont-cover1">
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
  
  <!-- Report photo Modal -->
<div id="myModalReportPhoto" class="modal fade" role="dialog">
  <div class="modal-dialog" style="width: 33%">

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
  <div class="modal-dialog" style="width: 33%">

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
">-OR-</div>
	      		
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
        <button type="button" class="btn btn-default report_user_modal" data-dismiss="modal">{{{trans('app.report')}}}</button>
      </div>
    </div>

  </div>
</div>


	
<!-- 	<textarea rows='5' class='emojis-wysiwyg'>Hello :neckbeard:</textarea>		 -->
			

 <!-- Include all compiled plugins (below), or include individual files as needed -->

   <script type="text/javascript" src="@theme_asset('js/jquery.easing.1.3.js')"></script>
   <script type="text/javascript" src="@theme_asset('js/jquery.mousewheel.js')"></script>
   <script type="text/javascript" src="@theme_asset('js/jquery.contentcarousel.js')"></script>

   <script src="{{{ url('js/notifications.js') }}}"></script>

   <script type="text/javascript">
      $('#ca-container').contentcarousel();
   </script>
   <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--    <script src="@theme_asset('js/jquery.mCustomScrollbar.concat.min.js')"></script> -->
   <script src="@theme_asset('js/slider.js')"></script>
<!--    <script src="@theme_asset('js/thumbnail-slider.js')"></script> -->
   <script src="@theme_asset('js/BootSideMenu.js')"></script>
   
   <script src="http://tympanus.net/Development/Elastislide/js/jquerypp.custom.js"></script>
   <script src="http://tympanus.net/Development/Elastislide/js/jquery.elastislide.js"></script>
   <script src="http://timeago.yarp.com/jquery.timeago.js" type="text/javascript"></script>
   
   <script src="@theme_asset('js/underscore.js')" type="text/javascript"></script>
  

   <script src="@theme_asset('js/custom.js')" type="text/javascript"></script>
   <script src="@theme_asset('js/highlight.js')"></script>
       <script src="@theme_asset('js/embed.js')"></script>
       
        <script src="@theme_asset('js/circle-progress.js')"></script>
       

 
   
   
<!--
   <script>
      (function($){
        $(window).load(function(){
          
          $("#content-1").mCustomScrollbar({
            axis:"x",
            advanced:{
              autoExpandHorizontalScroll:true
            }
          });
          
        
      
          
          $("a[rel='add-content']").click(function(e){
            e.preventDefault();
            var markup="<li id='img-"+i+"-container'><img src='images/"+imgs[i]+"' /></li>";
            $(".content .mCSB_container ul").append(markup);
            if(i<imgs.length-1){
              i++
            }else{
              i=0;
            }
          });
          
          $("a[rel='remove-content']").click(function(e){
            e.preventDefault();
            if($(".content .mCSB_container ul li").length<4){return;} 
            i--
            if(i<0){i=imgs.length-1;}
            $("#img-"+i+"-container").remove();
          });
          
        });
      })(jQuery);
   </script>
-->
   <script>
      $(document).ready(function(){
        $('.fa.fa-angle-down.d-arrow-1').click(function(){
           $(this).next('.hover-cover').slideToggle();
        });
        $('.fa.fa-angle-down.d-arrow').click(function(){
          $('.sign-out ').slideToggle();
        });
      
      
      
        $(document).ready(function() {
              $('#edit').click(function () {
                  
                    var dad = $(this).parent().parent();  
                    dad.find('.personal').toggle();
                    dad.find('.personal-info').toggle().focus();
      
                    $( ".personal-info" ).wrap( '<form action = "{{{ url('/profile/submitpersonalinfo') }}}" method = "POST"></form>' );
              });
      
      
              $('#edit-aboutme').click(function () {
                  
                    var dad = $(this).parent().parent();  
                    dad.find('.aboutme').toggle();
                    dad.find('.personal-aboutme').toggle().focus();
      
                    $( ".personal-aboutme" ).wrap( '<form action = "{{{ url('/profile/submitaboutme') }}}" method = "POST"></form>' );
              });
        });
                
      });
   </script>
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
      
               //age
          var age= $('#age')[0].defaultValue;
      
          var value= age.split('-');
      
          $( "#age" ).val(value[ 0 ] + " - " + value[ 1 ]);
      
          $( "#slider-age" ).slider({
            range: true,
            min: 0,
            max: 80,
            values: [ value[0], value[1] ]
          });
      
          
      
        //km
          var km= $('#km')[0].defaultValue;
            $( "#km" ).val(km);
          
             $( "#slider-km" ).slider({
           range: "min",
           value: km,
           min: 0,
           max: 100
            });
             //people-nearby
            /* var age12= $('#age12')[0].defaultValue;*/
            /*$( "#age12" ).val(age12);*/
          
             $( "#slider-age1" ).slider({
           range: "min",
           value: km,
           min: 0,
           max: 100
            });
             //people-nearby
      
      
            
      
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


<script>
	$('input[name="checkbox1"]').click(function(e) {
	  	if ($('input[name="checkbox1"]').length == $("input[name='checkbox1']:checked").length) {
	       
	       
	       $(".loader").fadeIn("slow");
	       
	       //check if super powers are active
	       $.post("{{{ url('/isSuperPowerActivated/') }}}"+'/'+'{{{$auth_user->id}}}', function(data){


				$(".loader").fadeOut("slow");
				
				if(!data)
				{
					//open activate super power pop up
					
					$('.superpower-invisible-header').text('Browse profiles without being seen.');
					
					$('#myModalInvisibleMode').modal('show');
					
					$('.close').on('click', function () {
						$(this).data('clicked', true);
					    $('input[name="checkbox1"]').attr("checked",false);
					})
					
					
					
					if(!$('.close').data('clicked')) 
					{
							$('#superpower_invisible').val('1');
							
							
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



// Ripple function
(function(){
	"use strict";

	var colour = "#FF1744";
	var opacity = 0.1;
	var ripple_within_elements = ['input', 'button', 'a'];
	var ripple_without_diameter = 0;

	var overlays = {
		items: [],
		get: function(){
			var $element;
			for(var i = 0; i < overlays.items.length; i++){
				$element = overlays.items[i];
				if($element.transition_phase === false) {
					$element.transition_phase = 0;
					return $element;
				}
			}
			$element = document.createElement("div");
			$element.style.position = "absolute";
			$element.style.opacity = opacity;
			//$element.style.outline = "10px solid red";
			$element.style.pointerEvents = "none";
			$element.style.background = "-webkit-radial-gradient(" + colour + " 64%, rgba(0,0,0,0) 65%) no-repeat";
			$element.style.background = "radial-gradient(" + colour + " 64%, rgba(0,0,0,0) 65%) no-repeat";
			$element.style.transform = "translateZ(0)";
			$element.transition_phase = 0;
			$element.rid = overlays.items.length;
			$element.next_transition = overlays.next_transition_generator($element);
			document.body.appendChild($element);
			overlays.items.push($element);
			return $element;
		},
		next_transition_generator: function($element){
			return function(){
				$element.transition_phase++;
				switch($element.transition_phase){
					case 1:
						$element.style[transition] = "all 500ms cubic-bezier(0.165, 0.840, 0.440, 1.000)";
						$element.style.backgroundSize = $element.ripple_backgroundSize;
						$element.style.backgroundPosition = $element.ripple_backgroundPosition;
						setTimeout($element.next_transition, 0.2 * 1000); //now I know transitionend is better but it fires multiple times when multiple properties are animated, so this is simpler code and (imo) worth tiny delays
						break;
					case 2:
						$element.style[transition] = "opacity 0.15s ease-in-out";
						$element.style.opacity = 0;
						setTimeout($element.next_transition, 0.15 * 1000);
						break;
					case 3:
						overlays.recycle($element);
						break;
				}
			};
		},
		recycle: function($element){
			$element.style.display = "none";
			$element.style[transition] = "none";
			if($element.timer) clearTimeout($element.timer);
			$element.transition_phase = false;
		}
	};

	var transition = function(){
		var i,
			el = document.createElement('div'),
			transitions = {
				'WebkitTransition':'webkitTransition',
				'transition':'transition',
				'OTransition':'otransition',
				'MozTransition':'transition'
			};
		for (i in transitions) {
			if (transitions.hasOwnProperty(i) && el.style[i] !== undefined) {
				return transitions[i];
			}
		}
	}();

	var click = function(event){
		var $element = overlays.get(),
			touch,
			x,
			y;

		touch = event.touches ? event.touches[0] : event;

		$element.style[transition] = "none";
		$element.style.backgroundSize = "3px 3px";
		$element.style.opacity = opacity;
		if(ripple_within_elements.indexOf(touch.target.nodeName.toLowerCase()) > -1) {
			x = touch.offsetX;
			y = touch.offsetY;
			
			var dimensions = touch.target.getBoundingClientRect();
			if(!x || !y){
				x = (touch.clientX || touch.x) - dimensions.left;
				y = (touch.clientY || touch.y) - dimensions.top;
			}
			$element.style.backgroundPosition = x + "px " + y + "px";
			$element.style.width = dimensions.width + "px";
			$element.style.height = dimensions.height + "px";
			$element.style.left = (dimensions.left) + "px";
			$element.style.top = (dimensions.top + document.body.scrollTop + document.documentElement.scrollTop) + "px";
			var computed_style = window.getComputedStyle(event.target);
			for (var key in computed_style) {
				if (key.toString().indexOf("adius") > -1) {
					if(computed_style[key]) {
						$element.style[key] = computed_style[key];
					}
				} else if(parseInt(key, 10).toString() === key && computed_style[key].indexOf("adius") > -1){
					$element.style[computed_style[key]] = computed_style[computed_style[key]];
				}
			}
			$element.style.backgroundPosition = x + "px " + y + "px";
			$element.ripple_backgroundPosition = (x - dimensions.width)  + "px " + (y - dimensions.width) + "px";
			$element.ripple_backgroundSize = (dimensions.width * 2) + "px " + (dimensions.width * 2) + "px";
		} else { //click was outside of ripple element
			x = touch.clientX || touch.x || touch.pageX;
			y = touch.clientY || touch.y || touch.pageY;
			
			$element.style.borderRadius = "0px";
			$element.style.left = (x - ripple_without_diameter / 2) + "px";
			$element.style.top = (document.body.scrollTop + document.documentElement.scrollTop + y - ripple_without_diameter / 2) + "px";
			$element.ripple_backgroundSize = ripple_without_diameter + "px " + ripple_without_diameter + "px";
			$element.style.width = ripple_without_diameter + "px";
			$element.style.height = ripple_without_diameter + "px";
			$element.style.backgroundPosition = "center center";
			$element.ripple_backgroundPosition = "center center";
			$element.ripple_backgroundSize = ripple_without_diameter + "px " + ripple_without_diameter + "px";
		}
		$element.ripple_x = x;
		$element.ripple_y = y;
		$element.style.display = "block";
		setTimeout($element.next_transition, 20);
	};

	if('ontouchstart' in window || 'onmsgesturechange' in window){
		document.addEventListener("touchstart", click, false);
	} else {
		document.addEventListener("click", click, false);
	}
}());
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


<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script type="text/javascript">
    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('{{{$stripe_publishable_key}}}');
    var stripeResponseHandler = function(status, response) {
      var $form = $('#payment-form_superpower');
      if (response.error) {
        // Show the errors on the form
        $form.find('.payment-errors').text(response.error.message);
        $form.find('button').prop('disabled', false);
      } else {
        // token contains id, last4, and card type
        var token2 = response.id;
        // Insert the token into the form so it gets submitted to the server

      
        $form.append($('<input type="hidden" name="stripeToken" />').val(token2));
        // and re-submit
        $form.get(0).submit();
      }
    };

      $('#payment-form_superpower').submit(function(e) {

      	e.preventDefault();
        var $form = $(this);
        // Disable the submit button to prevent repeated clicks
       
        $form.find('button').prop('disabled', true);
        Stripe.card.createToken($form, stripeResponseHandler);
        // Prevent the form from submitting with the default action
        return false;
      });
   
  </script>
  
  
<script>

var refill_package_id;

$("#paypal-pay").click(function(){




console.log("{{{ url('/paypal/pay_superpower/') }}}/"+refill_package_id);
window.location.href= "{{{ url('/paypal/pay_superpower/') }}}/"+refill_package_id+"/"+$('#superpower_invisible').val();

})

$( "#superpower_package" ).change(function () {

				
				$(".amount").html("{{{$currency}}}"+$(this).find(':selected').data('amount'));
				$(".amount-form").val($(this).find(':selected').data('amount'));
				
				
				refill_package_id = $(this).find(':selected').data('package-id');
				
				
				$('.packageId').val(refill_package_id);

});




$(".amount").html("{{{$currency}}}"+$( "#superpower_package" ).find(':selected').data('amount'));
$(".amount-form").val($( "#superpower_package" ).find(':selected').data('amount'));

refill_package_id = $( "#superpower_package" ).find(':selected').data('package-id');


$('.packageId').val(refill_package_id);


</script>	

<script>
	function random(name)
{
	var str=name;
	$('.gateid').val(str.trim());
	//alert(name);
}
</script>  

<script>
	
	$(function(){
		
		$(document).on('click', '.upgrade-now', function(){
	
	
		
					$('.superpower-invisible-header').text('Activate Super Power');
					 
					//open activate super power pop up
					$('#myModalInvisibleMode').modal('show');
					
					
					
					$('#superpower_invisible').val('0');
		
		
	})
	
	});
	
	</script>
	

	<script src="@theme_asset('js/lightbox2.js')"></script>
	
	<script>
		$('.user_photos_list').on('click',function(){
			
			if($(this).parent().next().children()[0])
				$($(this)[0].parentElement.nextElementSibling.firstElementChild).trigger('click');
				
				
				
			
		})
		
		
		$('.expandPhotos').on('click',function(){
			
			
			
			$($('div.user_photos_lightbox')[0].firstElementChild).trigger('click');
			
		})
	</script>
	
	
	<script>
		
		$('.report_photo').on("click",function(){
			
			$('#myModalReportPhoto').modal('show');
			
// 			$('.reason').val($(this).parent().css('background-image').substring($(this).parent().css('background-image').lastIndexOf('/')+1).split('"')[0]);
			
			if($(this.previousElementSibling).children("div.active")[0])
			$('.reason').val($(this.previousElementSibling).children("div.active")[0].firstElementChild.currentSrc.substring($(this.previousElementSibling).children("div.active")[0].firstElementChild.currentSrc.lastIndexOf('/')+1).split('"')[0]);
			
			
			
			
		});
		
		
		$('.report_photo_lightbox').on("click",function(){
			
			
			$('.lb-close').trigger('click');
			
			$('#myModalReportPhoto').modal('show');
			
// 			$('.reason').val($(this).parent().css('background-image').substring($(this).parent().css('background-image').lastIndexOf('/')+1).split('"')[0]);
			
			if($(this.nextElementSibling)[0])
			$('.reason').val($(this.nextElementSibling)[0].currentSrc.substring($(this.nextElementSibling)[0].currentSrc.lastIndexOf('/')+1).split('"')[0]);
			
			
			
			
		});
		
		
		
		
		
		
		$('.report_photo_modal').on("click",function(){
			
									
			data={
					photo_name:$('.reason').val(),
					reason:$('.user_reason').val()
				};
			
			$(".loader").fadeIn("slow");
			
			
			$.ajax({
			  type: "POST",
			  url: "{{{ url('/photo/report') }}}",
			  data: data,
			  success: function(msg){
			        $(".loader").fadeOut("slow");
			        
			        
			        if(msg.status=='error')
			        {
			        	toastr.info("Can't report this photo");
			        }
			        else
			        {
			        	toastr.info("Reported");
			        	
			        	
			        }
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			        toastr.info("some error");
			  }
			});
			
		})
		
		
		$("div.radiocnt input:radio").change(function () {
		    //alert($(this).val());
		    
		    if($(this).val()=="Other")
		    {
			    $('.user_reason').val('');
			    $('.user_reason').show();
			    
		    }
		    else
		    {
			    $('.user_reason').hide();
			    
			    $('.user_reason').val($(this).val());
			    
		    }
		        
		});
		
		
		</script>
	
	
	
	<script>
		
		$("div.radiocnt2 input:radio").change(function () {
		    //alert($(this).val());
		    
		    if($(this).val()=="Other")
		    {
			    $('.user_reason_abuse').val('');
			    $('.user_reason_abuse').show();
			    
		    }
		    else
		    {
			    $('.user_reason_abuse').hide();
			    
			    $('.user_reason_abuse').val($(this).val());
			    
		    }
		        
		});



		$('.report_user_modal').on("click",function(){
			
									
			data={
					userid:window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
					reason:$('.user_reason_abuse').val()
				};
			
			
			
			
			$.ajax({
			  type: "POST",
			  url: "{{{ url('/user/report') }}}",
			  data: data,
			  success: function(msg){
			        
			        
			        
			        if(msg.status=='error')
			        {
			        	toastr.info("Can't report this user");
			        }
			        else
			        {
			        	toastr.info("Reported");
			        	
			        	
			        }
			  },
			  error: function(XMLHttpRequest, textStatus, errorThrown) {
			        toastr.info("some error");
			  }
			});
			
		})
	</script>	
	
	
	<script>
		
		$('.blockUser').on("click",function(){
			
			data={
					user_id:$(this).data('user-id')
					
				};
				
			
			//if user already blocked then unblock user
			$.ajax({
			  type: "POST",
			  url: "{{{ url('/user/blocked_by_auth_user') }}}",
			  data: data,
			  success: function(data){
			        
			        
			        
			        if(data.blocked==false)
			        {
			        	
			        	$.ajax({
						  type: "POST",
						  url: "{{{ url('/user/block') }}}",
						  data: {user_id:window.location.href.substring(window.location.href.lastIndexOf('/') + 1).replace(/\D/g,'')},
						  success: function(msg){
						        
						        
						        
						        if(msg.status=='error')
						        {
						        	toastr.info("Can't block this user");
						        }
						        else
						        {
						        	toastr.info("User blocked");
						        	
						        	$('.blockUser').text('Unblock');
						        	
						        							        	
						        }
						  },
						  error: function(XMLHttpRequest, textStatus, errorThrown) {
						        toastr.info("some error");
						  }
						  
						});

			        	
			        }
			        else
			        {
			        	
			        	$.ajax({
							  type: "POST",
							  url: "{{{ url('/user/unblock') }}}",
							  data: {user_id:window.location.href.substring(window.location.href.lastIndexOf('/') + 1).replace(/\D/g,'')},
							  success: function(msg){
							        
							        
							        
							        if(msg.status=='error')
							        {
							        	toastr.info("Can't block this user");
							        }
							        else
							        {
							        	toastr.info("User Unblocked");
							        	
							        	$('.blockUser').text('Block');
							        								        	
							        }
								},
								 error: function(XMLHttpRequest, textStatus, errorThrown) {
									        toastr.info("some error");
									  }
								});
				
							        	
							        				        	
							        	
							   }
							  },
							  error: function(XMLHttpRequest, textStatus, errorThrown) {
							        toastr.info("some error");
							  }
							});
			
				
			
						
			
			
			
		})
		
		
		
		
	</script>	
	
  <script src="@theme_asset('js/jMyCarousel.js')"></script>
  
  <script type="text/javascript">
    $(function() {

        $(".jMyCarousel").jMyCarousel({
            visible: '100%',
            eltByElt: true,
            evtStart: 'mousedown',
            evtStop: 'mouseup',
            circular:false
        });

    });
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
		
		function add_to_spotlight()
		{
			
			var Request= {
				
				id:'{{{$auth_user->id}}}'
			};
			
			 $('.loaderUpload').fadeIn();
			
			$.post("{{{url('/user/spotlight/add')}}}",Request, function(data){

				 $('.loaderUpload').fadeOut();
				toastr.info('Added to spotLight');
				window.location.reload();

			});

			
		}
	</script>
	
	
	
	<script>
		var ChatController = App.controller("ChatController", ["$scope", "$rootScope","$http","$compile", function($scope, $rootScope,$http,$compile){
		
		var unreadCount=0;
		$scope.totalUnreadCount = 0;
		$scope.totalContactsCount =0;
		
		$scope.chatUsers=[];
		
		$scope.chatUsersArry=[];
		
		$scope.user_id='{{{$auth_user->id}}}';
		
		
		
		var WindowManager = {};

		WindowManager.max_limit = 3;
		
		
		WindowManager.windows = [];
		WindowManager.sessions = [];
		$scope.w ={};
		
		
		$scope.activeUsers=[];
		
		WindowManager.add = function(w,id,contact_id){   
			 
			var window = [];
			var chat_data = {};
			chat_data.user_id = id;
			chat_data.contact_id = contact_id;
			chat_data.count = -1;
			chat_data.isActive = 1;
			
			window["data"] = chat_data;
			window["element"] = w;
			 
    
		    WindowManager.windows.push(window);
		    
		    
		    $scope.activeUsers.push(id);
		    
		    //$.cookie = WindowManager.windows.length+"place" + "=" + user_id + "; " + expires + ";domain=;path=/";
		    
		    return window;
		}
		
		
		
		//close the chat
		
		$scope.chatClose= function(id)
		{
			
			
			$placements = [250,570,890];
			
			
			$i =0 ;
			
			$('#dialog'+id).fadeOut();
			$('#dialog'+id).remove();
			
			_.each(WindowManager.windows, function(item){
				
				
				if(item["element"][0].id==('dialog'+id))
				{
					$index = WindowManager.windows.indexOf(item);
					$scope.w[id].isActive = 0;
					
					$scope.activeUsers= _.reject($scope.activeUsers, function(d){ return d == id; });
					
					return;
					
				}
				
				
			});
			WindowManager.windows.splice($index, 1);
			
			_.each(WindowManager.windows, function(item){ 
			
				$(item["element"][0]).css("right",$placements[$i]);	
				$i++;
				
			});	
			$scope.session={};
			
			
		}
				
		
		//polling for notifications count		
		$scope.get_notifications_poll= function()
		{
			
			
				//var res=$http.get("{{{ url('chat/notifications') }}}/?count="+$scope.totalUnreadCount);
	
			return	$http.get("{{{url('chat/notifications')}}}/?count="+$scope.totalUnreadCount).success(function(data, status, headers, config) {
					
						console.log(data);
						
						
						if($scope.totalUnreadCount < data.count || $scope.totalUnreadCount > data.count){
						
								$scope.totalUnreadCount = data.count;
								
								
								if(data.status)
								{
									$scope.newCounts= data.details;
									
									
									_.each($scope.chatUsers,function(item){
										
										
										if($scope.newCounts[item.id])
										{
											
											$scope.w[0].data.user_id
											
											
											
											
											
											if(!_.contain($scope.activeUsers,item.id ))
											
											item.unread= $scope.newCounts[item.id];
											
											 $('#chatAudio')[0].play();
										
										}
									});
										
								
								}
						}		
					$scope.polling_notifications();
				});


			
			
		}		
		$scope.polling_notifications= function()
		{
			
			
 			$scope.get_notifications_poll();
 			
 			
			
		}
		
		
		
		
		
		//new contacts polling
		$scope.get_contacts_poll= function()
		{
			
			
				//var res=$http.get("{{{ url('chat/contactscount') }}}/?count="+$scope.totalContactsCount);
	
				return $http.get("{{{url('chat/contactsstatus/?contacts_count=')}}}"+$scope.totalContactsCount+'&notification_count='+$scope.totalUnreadCount).success(function(data, status, headers, config) {
					
						console.log(data);
						
						$scope.totalUnreadCount = data.notification_count;
						
						if($scope.totalContactsCount < data.contacts_count)
						{
							$scope.totalContactsCount= data.contacts_count;
							
							$scope.getContacts();
						}
						else{
							var i = 0;
						_.each($scope.chatUsers,function(item){
												
												
												item.is_online = data.contacts[i].is_online;
												item.last_msg = data.contacts[i].last_msg;
												item.unread= data.contacts[i].unread;
												i++;									
									})	;
						}
								
								
						//$scope.polling_contacts();
						$scope.get_contacts_poll();
					
				});


			
			
		}		
		$scope.polling_contacts= function()
		{
			
			

 			//poll_contacts = 
			
		}
		
		
		
		
		$scope.sendMessage= function(id,contact_id)
		{
			
			$scope.msg = {};
			$scope.msg.to_user = id;
			$scope.msg.contact_id = contact_id;
			$scope.msg.message = $('#posttext'+id).val(); 
			
			//$scope.msg.type=1;
			
			
				
			
			if($('#posttext'+id).val() )			
			{
				//if($('emoji-wysiwyg-editor'))
					//$('.emoji-wysiwyg-editor').hide();					
				
				var discussion= $("#chatbody"+id);
				
				
				
				
				
				if($scope.w[id].count==0)
				{
					discussion.empty();
					
					
					
				}
				
			
			var ele="";
			ele += "                <div class=\"row\">";
				ele += "					<span class='bubble'>"+$scope.msg.message +"</span>";
				ele += "                <\/div>";		
				ele += "";	
			
			 
			
			
			
			
			discussion.append($(ele).fadeIn('slow'));
			
			
		        
			$scope.scrollDown(id);
			
			
			$('#posttext'+id).val('');
			
			
			$scope.msg.type=0;
			
			$('.loader_chat').fadeIn();	
						
			$scope.msg._token = "{{{ csrf_token() }}}";
			$.post("{{{url('chat/message')}}}",$scope.msg,function(data){
		
					console.log(data);
					//Chat.get_message();
					
					$('.loader_chat').fadeOut();	
					
					//$scope.get_message(id);
			})

				
				
			}
			
			
			
			if($scope.attachmentName)
			{
				
				
					var fd = new FormData();
					
					var file_data = $('input[name="file"]')[0].files; 
				    for(var i = 0;i<file_data.length;i++){
				        fd.append("file"+id, file_data[i]);
				    }
					
					fd.append('to_user',id);
					fd.append('contact_id',contact_id);
					fd.append('message','');
					fd.append('type',2);				
					
					
					$(".sendphotos").val('');
					$scope.attachmentName='';
					
 					$('.loader_chat').fadeIn();					
					
					$.ajax({
								  type: "POST",
								  url: "{{{url('chat/message')}}}",
								  contentType: false,								  
								  processData: false,
								  data: fd,
								  success: function(msg){
									  
									  console.log(msg);
								        $('.loader_chat').fadeOut();	
								        
								  },
								  error: function(XMLHttpRequest, textStatus, errorThrown) {
								        toastr.info("some error");
								  }
								  
				   });
				
				
			}		
			
			
			
		}
		
		
		
		
		
		
		$scope.get_message= function(id)
		{
			

			
		
			$.get("{{{url('/chat/messages/')}}}/?user_id="+id+"&contact_id="+$scope.w[id].contact_id+"&count="+$scope.w[id].count,function(data){
		
				if(data.status) {
					$("#chatbody"+id).empty();
					
					$('.loader_chat').fadeOut();
		
					console.log("Current Messages -> ", data.chat.length)
					
					$scope.w[id].count = data.chat.length;
		
		
					for(var i=0; i < data.chat.length; i++){
						
						$scope.add_message(data.chat[i], id);
		
					}
					
					
					//apply embed js
					ejs.setOptions({
// 									  inlineEmbed:'all',
									  googleAuthKey : 'AIzaSyCqFouT8h5DKAbxlrTZmjXEmNBjC69f0ts',
// 									  highlightCode:true,
// 									  videoHeight:100,
// 									  videoWidth:100,
									  emoji:true
// 									  locationEmbed:true
									});
									
					ejs.applyEmbedJS('.bubbletext');
					
					//if chat is empty show some magical words
					
					if(data.chat.length==0)
					{
						
						$("#chatbody"+id).html('<img src="@theme_asset('images/chat_no_messges.jpg')" style="position:absolute;top:54px;left:101px;" height="130px" width="100px"/> <div style="position: absolute;bottom: 53px;font-family: sans-serif;color: black;text-align: center;font-weight: 600;">Now why not break the ice and send a message?</div>');
						
					}
					
					
				}
				else{
					$('.loader_chat').fadeOut();
					
				}
		
					
				
				$scope.polling(id);
		
			})
			
		}
		
		
		
		
		$scope.polling= function(id)
		{
			
			if(!$scope.w[id].isActive){
				return;
 			}

 			$scope.get_message(id);
			
		} 
		
		
		
		

		
		
		
		$scope.add_message= function(msg,id)
		{
			var discussion = $("#chatbody"+id);
			
			
			
			var ele="";
			if(msg.sender == 'self')
			{
				
				ele += "                <div class=\"row\">";
				
				if(msg.type==2)
				{
					ele += "					<span class='bubble'><a class='fancybox' ref='user1' href='"+msg.photo_url+"'><img src="+msg.photo_url+" class='attachments'/></a></span>";
				}
				if(msg.type==0)
				{
					ele += "					<span class='bubble bubbletext'>"+msg.text+"</span>";
					
				}
				
				
				
				ele += "                <\/div>";		
				ele += "";
			} 	
			else
			{
								
				ele += "                <div class=\"row\">";
				ele += '                  <img src='+msg.user_image+'>';	
				
				
				if(msg.type==0)
				{
					ele += "					<span  class='bubbleOther bubbletext'>"+msg.text+"</span>";
				}	
				if(msg.type==2)
				{
					ele += "					<span  class='bubbleOther'><a class='fancybox' ref='user2' href='"+msg.photo_url+"'><img src="+msg.photo_url+" class='attachments'/></a></span>";
				}		
				
				
				
				
				
				ele += "                <\/div>";		
				ele += "";	
				
			}		
						
			
						var compiledElement= $compile(ele)($scope);
						discussion.prepend(compiledElement);
									
								/* 			code for video embedding */
									
									
/*
								if(msg.type==0)
								{		
									ejs.setOptions({
// 									  inlineEmbed:'all',
									  googleAuthKey : 'AIzaSyCqFouT8h5DKAbxlrTZmjXEmNBjC69f0ts',
// 									  highlightCode:true,
// 									  videoHeight:100,
// 									  videoWidth:100,
									  emoji:true
// 									  locationEmbed:true
									});
									
									ejs.applyEmbedJS('.bubbletext');
								}
*/
		
/*
			$("#fancybox"+id).fancybox();
			$("#fancybox2"+id).fancybox();
*/
		        
			$scope.scrollDown(id);
			
		}
		
		
		$scope.scrollDown= function(id)
		{
					           
	            
	             $("#chatbody"+id).scrollTop( $("#chatbody"+id)[0].scrollHeight);
	            
		}
		
		
		
		
			
			$rootScope.$on('chatToPerson', function(event, message){
				
				data={contact_id:message.id};
				
				$scope.id=data.contact_id;
			
				$('.loader').fadeIn();
				var res=$http.post("{{{url('chat/addcontact')}}}",data);
	
				res.success(function(data, status, headers, config) {
					
						console.log(data);
						
							$('.loader').fadeOut();
						
						if(data.success=='limit')
						{
							$('#myModalExceedsEncounters').modal('show');
						}
						else{
								if(data.success=='new_contact')
								{
									$scope.new_contact=true;
								}
								else
								{
									$scope.new_contact=false;
								}
								
								
								//$scope.getContacts();
								//$scope.get_contacts_poll();
								$('.chat_list_container').addClass('maximize');
								
								//$scope.polling_notifications();	
							
						}
					
								
						
					
				});
		
			})
			
			
			$rootScope.$on('chatToMatchedPerson', function(event, message){
				
						data={contact_id:message.id};
				
						$scope.id=data.contact_id;
			
						//$scope.getContacts();
						
						
						//$scope.get_contacts_poll();
						
						$('.chat_list_container').addClass('maximize');	
						//$scope.polling_notifications();
			})
			
			
			
		
		$scope.getContacts= function()
		{
			
				var res=$http.get("{{{url('chat/contacts')}}}");
	
						res.success(function(data, status, headers, config) {
							
								console.log(data);
								
								
								
									
								$scope.chatUsers=data;
								
									
								$scope.totalContactsCount= data.length;
								
								
								
								//$scope.get_contacts_poll();
							
								
							
									
									if($scope.id)
									{
									
										$scope.userToChat = _.filter($scope.chatUsers,function(val){
											
											return (val.id.toString()==$scope.id.toString())
											
										})
										
										
										$scope.id='';
									}
									
									
									unreadCount=0;
									_.each($scope.chatUsers,function(item){
												
												
												unreadCount=unreadCount+item.unread;
												$scope.totalUnreadCount=unreadCount;									
									})
									
									
									if($scope.userToChat)
										$scope.openChatDialog($scope.userToChat[0]);
									
									//$scope.get_notifications_poll();
								
								
							
						});
			
		}
		
		
		$scope.openChatDialog = function(chatUsers)
		{
			var element='';
			//$('#chat1').fadeIn(); 
			
			if($('#dialog'+chatUsers.id).length)
			{
				$('#dialog'+chatUsers.id).fadeIn();
				
			}
				
			else
			{
			
				//form the html for chat dialog
				
				var chat_dialog="";
				chat_dialog += '  <div class=\"chat_container chat_dialog\" id=dialog'+chatUsers.id+'>'; 
				chat_dialog += "         <div class=\"chat_box\">";
				chat_dialog += "             <div class=\"chat_head\">";
				chat_dialog += '               <p>'+chatUsers.name+'<\/p>';
				chat_dialog += "               <ul class=\"list-inline chat_head_icon\">";
				
				chat_dialog += "                 <li><i class=\"material-icons chat_close\" ng-click='chatClose("+chatUsers.id+")'>clear<\/i><\/li>";
				chat_dialog += "               <\/ul>";
				chat_dialog += "             <\/div>";
				chat_dialog += "             <div class=\"chat_body\" id=chatbody"+chatUsers.id+">";
				chat_dialog += "					<div class='loader_chat'></div>";
				chat_dialog += "                <div class=\"row\">";				
				chat_dialog += "                <\/div>";
				chat_dialog += "                <div class=\"row\">";				
				chat_dialog += "                <\/div>";
				chat_dialog += "                ";
				chat_dialog += "             <\/div>";
				chat_dialog += "             <div class=\"chat_footer\">";
				
				
				chat_dialog += "               <textarea   class='postText' rows=\"10\" cols=\"27\"   id='posttext"+chatUsers.id+"' ng-keyup='$event.keyCode == 13 ? sendMessage("+chatUsers.id+","+chatUsers.contact_id+") : null' ng-focus='typingTrue()' ng-blur='typingFalse()' placeholder=\"Type a message...\"><\/textarea>";
				chat_dialog += "               <ul class=\"list-inline chat_footer_icon\">";
				chat_dialog += "                 <li ><i class=\"material-icons emoticons"+chatUsers.id+"\" >insert_emoticon<\/i><\/li>";
				chat_dialog += "                 <li  ><i  class=\"material-icons uploadchatphoto\">camera_alt<\/i><form enctype='multipart/form-data' class='sendfileform'><input type=\"file\" class=\"sendphotos\" name=\"file\"  accept=\"image\/*\" title=\"Add Photos\"></form><\/li>";
	
				chat_dialog += "               <\/ul>";
				chat_dialog += "             <\/div>";
				chat_dialog += "         <\/div> ";
				chat_dialog += "      <\/div>  ";
				
				
				
				

				
			
				element= $compile(chat_dialog)($scope);
				
				
				
				
				
				var rightValue= $scope.generatePlacing();
				
				element.css("right", rightValue);				
				
				$('.chat_prepend').append(element);
				
				
				
				var $wysiwyg = $('#posttext'+chatUsers.id).emojiarea({wysiwyg: true});
				
				$('#posttext'+chatUsers.id).emojiarea({button: '.emoticons'+chatUsers.id});
				
				var $wysiwyg_value = $('#posttext'+chatUsers.id);
				
				$wysiwyg_value.show();
				
				//$('emoji-wysiwyg-editor').hide();
				
				$('.emoji-button').hide();
		
				
				$('.uploadchatphoto').on('click',function(){
				
					$(this).parent().find('input[type=file].sendphotos').trigger('click');
					
					
					
				});
				
				
				$('.emoji-wysiwyg-editor').keypress(function(){
					
					//trigger send emoticon event
					$scope.sendMessage(chatUsers.id,chatUsers.contact_id);
					
					$(this).empty();
					
				});
				
				$("input[type=file].sendphotos").on('change',function(){
					//send photo attahment to server
				
					
					$scope.attachmentName= this.value.substring(this.value.lastIndexOf('\\') + 1);
					
					$scope.sendMessage(chatUsers.id,chatUsers.contact_id);
					
    			});
				
				temp =  WindowManager.add(element,chatUsers.id,chatUsers.contact_id);
				
				
				var user_id=chatUsers.id;
				
				$scope.w[user_id] = temp["data"];
				
				//$scope.w.push({""+ chatUsers.id +"": temp["data"] });
				
				
				$scope.get_message(chatUsers.id);
			
				//make the unread count as zero
				$scope.totalUnreadCount=0;
				
				$('li#'+chatUsers.id+' span.noti_msg').text(0).delay(250).fadeOut(500);
				
			}
			
			
			
			
			
			
			return;
			
		}
		
		


		$scope.generatePlacing= function(){
		 
/*
		     if(WindowManager.windows.length >= 3){
		      $(WindowManager.windows[0]).hide();
		      return 250;
		   }
*/
		    
		    
		    
		   if(WindowManager.windows.length == 0){
		       return 250;
		   }
		   
		     if(WindowManager.windows.length == 1){
		       return 570;
		   }
		   
		   
		     if(WindowManager.windows.length == 2){
		       return 890;
		   }
		   
		   if(WindowManager.windows.length == 3) {
			   
			   WindowManager.closeWindow(WindowManager.windows[2]);
			   return 890;
		   }
		   		   
		    
		}

		WindowManager.closeWindow = function($window) {
			
			user_id = $window["element"][0].id;
			$('#'+user_id).fadeOut();
			$('#'+user_id).remove();
			
			var w = WindowManager.windows.pop();
			$scope.activeUsers= _.reject($scope.activeUsers, function(d){ return d == user_id; });
			
			$scope.w[w["data"].user_id].isActive = 0;
			
		}



$scope.ChatInit = function() {
			
			$scope.get_contacts_poll();
			
					
		}
		
		$scope.ChatInit();


		//$scope.polling_notifications();
		
		
		
		
		
	}]);


	</script>	
	
	
	<script type="text/javascript">
				$(function(){ 
				
				//Appending HTML5 Audio Tag in HTML Body
				$("<audio id='chatAudio'><source src='@theme_asset('music/notify.ogg')' type='audio/ogg'><source src='@theme_asset('music/notify.mp3')' type='audio/mpeg'><source src='@theme_asset('music/notify.wav')' type='audio/wav'></audio>')").appendTo('body');
				
				
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
 

 

 
 
  <script src="@theme_asset('js/jquery_counter.js')"></script>
			<script> 
				$('#clock').countdown(stringifyTomorrow(), function(event) {
				  var totalHours = event.offset.totalDays * 24 + event.offset.hours;
				   $(this).html(event.strftime(totalHours + ' hr %M min %S sec'));
				});
				
				
				
				function stringifyTomorrow() {
				   var today = moment();
				   var tomorrow = today.add('days', 1);
				   return moment(tomorrow).format("YYYY/MM/DD");
				}
				
				



		</script>
	 
	 <script>
		 
		 $('.encounter_exceeds').on('click',function(){
			 
			 $('#myModalExceedsEncounters').modal('hide');
			 
			 
			 $('.superpower-invisible-header').text('Activate Super Power');
					 
					//open activate super power pop up
			$('#myModalInvisibleMode').modal('show');

			 
		 })
		 
		</script> 
		
		
				

		<script>
	  $(document).ready(function(){
		  var val = parseFloat('{{{$user_score->score}}}')/10;
		  	$('#circle2').circleProgress({
		        value: val,
		        size: 45,
		        fill: {
		            gradient: ["#526AD7 ", "#F82856 "]
		        }
		    }).on('circle-animation-progress', function(event, progress) {
			    $(this).find('strong').html(parseInt(100 * val) + '<i>%</i>');
			});		
		    
		  
		    
    });
	 </script> 
	 <script type="text/javascript" src="@theme_asset('js/fancybox.js')"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox();
		});
	</script>	
		
		
		<script>
			
			</script>
	
  @section('scripts')
@show

</body>


</html>
