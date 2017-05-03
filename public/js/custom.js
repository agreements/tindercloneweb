// edit_profilelpage 
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
  

// profile-edit
$(document).ready(function(){
	$('.cancel').click(function(){
		 $('.hidden_form').css("display","none");
		 $('.name-c').css("display","block");
		 $('.name-c-1').css("display","block");
		 $('#location_box').css("display","block");
		 $('#here_to_box').css("display","block");
		 $('#per_info').css("display","block");
		 $('#interests').css("display","block");
		 $('#about').css("display","block");
	});
	$('#update_name').click(function(){
		 $('.name-c').css("display","none");
		 $('.name-c-1').css("display","none");
		 $('#updt_name_by_id').css("display","block");
	});
	$('#update_location').click(function(){
		 $('#location_box').css("display","none");
		 $('#updt_laction_by_id').css("display","block");
	});
	$('#update_here_to').click(function(){
		 $('#here_to_box').css("display","none");
		 $('#updt_here_to_by_id').css("display","block");
	});
	$('#update_per_info').click(function(){
		 $('#per_info').css("display","none");
		 $('#updt_per_info_by_id').css("display","block");
	});
	$('#update_about_me').click(function(){
		 $('#about').css("display","none");
		 $('#updt_about_me_by_id').css("display","block");
	});
	$('#update_interests').click(function(){
		 $('#interests').css("display","none");
		 $('#updt_interest_by_id').css("display","block");
	});
	// image_hover
	$('.expand').click(function(){
	 $('#img_popup_div').addClass("img_popup");
	 $('#img_popup_div').show();
	});
	$('.img_popup_div_remove').click(function(){
		 $('#img_popup_div').hide();
	});
	// $('.ca-icon').click(function(){
	// 	url = $(this).css('background-image').replace(/^url\(['"]?/,'').replace(/['"]?\)$/,'');
 //  		//alert(url);
	// })



	// $('#lunch').change(function()
	// {
	// 	var option = $(this).find('option:selected').val();
	// 	alert(option);
	// 	$('#showlunch').val(option);
	// });


	});






	