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
  

// profile-edit





	