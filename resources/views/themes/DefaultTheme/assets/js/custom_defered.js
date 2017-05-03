


$(document).ready(function() {
			$(".fancybox").fancybox();
});


 $('.encounter_exceeds').on('click',function(){
			 
			 $('#myModalExceedsEncounters').modal('hide');
			 
			 
			 $('.superpower-invisible-header').text($("#activte-supoer-power-header").val());
					 
					//open activate super power pop up
			$('#myModalInvisibleMode').modal('show');

			 
})



$('#clock').countdown(stringifyTomorrow(), function(event) {
				  var totalHours = event.offset.totalDays * 24 + event.offset.hours;
				   $(this).html(event.strftime(totalHours + ' hr %M min %S sec'));
});
				
				
				
function stringifyTomorrow() {
   var today = moment();
   var tomorrow = today.add('days', 1);
   return moment(tomorrow).format("YYYY/MM/DD");
}


$(document).ready(function()
	{
	$("#sidebar-fixed-btn").click(function()
	{
	  $("#menu-toggle").click();
	});
});
	
	
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
			  url: reportPhoto_url,
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



		




		
		
		
		(function(){
 
  $(".controller").click(function(){
    id = $(this).attr("id");
    
    $(".controller-container").find(".is_current").removeClass("is_current");
    $(this).addClass("is_current");
    $(".card").attr('class', 'card card--' + id);
    $("html").attr('class', 'bg--' + id);
    
  });
  
})();



$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        tooltipClass: "tooltip_new",
	}); 
	
	$('[data-hover="tooltip"]').tooltip({
        tooltipClass: "tooltip_new",
	}); 
});


$(document).ready(function() {
  $("time.timeago").timeago();
});



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


function random(name)
{
	var str=name;
	$('.gateid').val(str.trim());
	//alert(name);
}
				
		