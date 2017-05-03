// edit_profilelpage 
$(document).ready(function(){
$(".chat_head>p").on("click",function(){
  var $current=$(this).parent().parent().parent();
    $current.toggleClass("minimize");
    if($current.hasClass("minimize"))
      {
        $current.find(".chat_footer").css("display","none");
      }
      else{
        $current.find(".chat_footer").css("display","block");
      }
});
$(".chat_list_head>h5").on("click",function(){
  var $current=$(this).parent().parent().parent();
    $current.toggleClass("maximize");
    if($current.hasClass("maximize"))
      {
        $current.find(".chat_list_footer").css("display","block");
        $(this).find(".expand").css("display","none");
        $(this).find(".mini").css("display","block");
      }
      else{
        $current.find(".chat_list_footer").css("display","none");
        $(this).find(".expand").css("display","block");
        $(this).find(".mini").css("display","none");
      }
});
$(".chat_list_body>ul").on("click",function(){
  var id = $(this).attr("id");
  // $(this).attr("id").addClass("active");

if($("#chat1").hasClass("active"))
{
  if($("#chat2").hasClass("active"))
  {
    $("#chat3").addClass("active");
    $("#chat3").css("display","block");
  }
  else{
    $("#chat2").addClass("active");
    $("#chat2").css("display","block");
  }
  
}
else{
  $("#chat1").addClass("active");
  $("#chat1").css("display","block");
}
// $("#chat2").css("display","block");
});

$(".chat_close").on("click",function(){
  var clickBoxId = $(this).closest(".chat_container").attr("id");
    $("#"+clickBoxId).css("display","none");
    $("#"+clickBoxId).removeClass("active");
});
});


$(document).ready(function(){
	$('.fa.fa-angle-down.d-arrow-1').click(function(){
		 $(this).next('.hover-cover').slideToggle();
	});
	$('.fa.fa-angle-down.d-arrow').click(function(){
		$('.sign-out ').slideToggle();
	});
});
$(document).ready(function(){
	$('.buttons-1 button').click(function(){
		 $('.bg-wh').css("display","none");
		 $('.bg-wh_update').css("display","block");
	});
	$('.bg-wh_update_footer button.btn-default').click(function(){
		 $('.bg-wh_update').css("display","none");
		  $('.bg-wh').css("display","block");
	});
});
// indexnw


  $(function() {
    $( "#slider-age" ).slider({
      range: true,
      min: 0,
      max: 80,
      values: [ 18, 80 ],
      slide: function( event, ui ) {
        $( "#age" ).val(  ui.values[ 0 ] + " - " + ui.values[ 1 ] );
      }
    });
    $( "#age" ).val( $( "#slider-age" ).slider( "values", 0 ) +
      " - " + $( "#slider-age" ).slider( "values", 1 ) );

    $( "#slider-km" ).slider({
      range: "min",
      value: 100,
      min: 0,
      max: 100,
      slide: function( event, ui ) {
        $( "#km" ).val( ui.value );
      }
    });
    $( "#km" ).val( $( "#slider-km" ).slider( "value" ) );
  });

$(document).ready( function ()
{
	$('#lunch').change(function()
	{
		var option = $(this).find('option:selected').val();
		$('#showlunch').val(option);
	});
});
  

// master page









$('.expandPhotos').on('click',function(){
			
			
			
			$($('div.user_photos_lightbox')[0].firstElementChild).trigger('click');
			
		})
		
		
		$('.user_photos_list').on('click',function(){
			
			if($(this).parent().next().children()[0])
				$($(this)[0].parentElement.nextElementSibling.firstElementChild).trigger('click');
				
				
				
			
		})
		
		
		














	
	

				





		 
		 
		 
		 
	
    
    

		
		

	
	
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



//triggered when modal is about to be shown
$('#myModalReportUser').on('show.bs.modal', function(e) {

    //get data-id attribute of the clicked element
    var userid = $(e.relatedTarget).data('user-id');

    //populate the textbox
    $(e.currentTarget).find('input[name="userid"]').val(userid);
});

$('.report_user_modal').on("click",function(e){
			
									
			data={
					//userid:window.location.href.substring(window.location.href.lastIndexOf('/') + 1),
					userid:$('input[name="userid"]').val(),
					reason:$('.user_reason_abuse').val()
				};
			
			
			
			
			$.ajax({
			  type: "POST",
			  url: reportUser,
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