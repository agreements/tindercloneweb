
    $(document).ready(function(){
      $.dobPicker({
        daySelector: '#dobday', /* Required */
        monthSelector: '#dobmonth', /* Required */
        yearSelector: '#dobyear', /* Required */
        dayDefault: 'Day', /* Optional */
        monthDefault: 'Month', /* Optional */
        yearDefault: 'Year', /* Optional */
        minimumAge: 8, /* Optional */
        maximumAge: 100 /* Optional */
      });
    });


    $('.dobpart').on('change',function(){
    	
    	
    	$('#dob').val($('#dobday').val()+'/'+$('#dobmonth').val()+'/'+$('#dobyear').val());
    	
    })
    

