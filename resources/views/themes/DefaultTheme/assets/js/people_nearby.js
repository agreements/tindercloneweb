

  

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
        
        var max_range;
        
        if(non_superpower_range_enabled)
        {
	        if(superpower_user)
	        {
		        max_range = parseInt(distance);
	        }
	        else{
		        
		        max_range = parseInt(distance_range_max);
		        
	        }
        }
        else
        {
	        max_range = parseInt(distance);
	        
        }
        
        
        
        
        
          $( "#km" ).val(km);
        
           $( "#slider-km" ).slider({
         range: "min",
         value: km,
         min: parseInt(distance_range_min),
         max: max_range
          });
           //people-nearby
          /* var age12= $('#age12')[0].defaultValue;*/
          /*$( "#age12" ).val(age12);*/
        
           $( "#slider-age1" ).slider({
         range: "min",
         value: km,
         min: parseInt(distance_range_min),
         max: max_range
          });
           //people-nearby
    
    
          
    
    });       
    
        
    


    selectedFields = [];
    
    selectedOptions= [];
    $(document).ready(function(){
     
     
    
     
 
    
     
    _.each(selectedFields,function(item,index){
    
    $('#customoption'+index).val(item.code);
    
                 
    
    if(item.type=='range')
    {
     
    var optiontobeselected = _.filter(selectedOptions,function(val){
         
         return (val.fieldcode == item.code)
         
         
     })
            
    }
    
    
    $('#customoption'+index).parent().find('.crossfilter').show();
    
    
    
    })  
     
     
    })
    
    
    $('.customoptionslink').on('click',function(){
     
     $('.customoptions').toggle();
     
     $('#customoption1').attr('disabled','disabled');
     
     $('#customoption2').attr('disabled','disabled');
     
     
    })
    
    
    //$('.ui-multiselect').hide();
    
    
    field='';
    
    customfields={};
    
    
    $('#customoption0').on('change', function() {
     
     
     
     
    
     
     $('#customoption1').removeAttr('disabled');
     
     //$('#customoption2').removeAttr('disabled');
     
     if($(this).val()=='0')
     {
      
      $(this).parent().find('.crossfilter').hide();
      $('#customoption1').parent().find('.crossfilter').hide();
      $('#customoption2').parent().find('.crossfilter').hide();
      
      $('#customoption1').attr('disabled','disabled');
      
      $('#customoption1').empty();
     
      $('#customoption2').attr('disabled','disabled');
      
      $('#customoption2').empty();
      
      $(this).parent().find('.ui-multiselect').hide();
        
        
        $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
        
        
         $('#customoption1').parent().find('.ui-multiselect').hide();
        
        
        $('#customoption1').parent().find('.dropdowncustomrangemin').hide();
        $('#customoption1').parent().find('.dropdowncustomrangemax').hide();
        
        
         $('#customoption2').parent().find('.ui-multiselect').hide();
        
        
                         $('#customoption2').parent().find('.dropdowncustomrangemin').hide();
                         $('#customoption2').parent().find('.dropdowncustomrangemax').hide();
     }
     else
     {
      
        $(this).parent().find('.crossfilter').show();
        
        var field = $(this).find(":selected").data('value');
      
      
      var options = $(this).find(":selected").data('options');
      
      var optionshtml = '<option value='+0+'>--</option>' ;
      
      if(options.length)
    for (var i = 0; i < options.length; i++) {
      optionshtml += '<option  name='+options[i].name+' value='+JSON.stringify(options[i].id)+'>'+options[i].name+' '+field.unit+'</option>';
    } 
    
    
    
                 
    
    
    //remove the selected field from next dropdown
    
    
    var oldSeerchfields = [];
    $('#customoption0 option').each(function() {
       oldSeerchfields.push({ "id" : this.value, "text" : this.textContent ,"field":$(this).data('value'),"options":$(this).data('options'),"name":$(this).attr('name')});
    });
    
    
    
    
    oldSeerchfields= _.filter(oldSeerchfields, function (obj) {
       return obj.id!=field.code
    });
    
            
    var searchFieldshtml = '';
    for (var i = 0; i < oldSeerchfields.length; i++) {
    searchFieldshtml += '<option value='+JSON.stringify(oldSeerchfields[i].id)+'>'+oldSeerchfields[i].text+' </option>';
    } 
    
    
    $('#customoption1').empty().html(searchFieldshtml);
    
    $('#customoption1').parent().find('.ui-multiselect').hide();
        
        
        $('#customoption1').parent().find('.dropdowncustomrangemin').hide();
        $('#customoption1').parent().find('.dropdowncustomrangemax').hide();
    
    $('#customoption2').empty();
    $('#customoption2').parent().find('.ui-multiselect').hide();
        
        
        $('#customoption2').parent().find('.dropdowncustomrangemin').hide();
        $('#customoption2').parent().find('.dropdowncustomrangemax').hide();
    $('#customoption2').attr('disabled','disabled');
    
    
    $('#customoption1 option').each(function() {
    
    for (var i = 0; i < oldSeerchfields.length; i++) {
        
            $($('#customoption1 option')[i]).attr('data-value',JSON.stringify(oldSeerchfields[i].field)); 
            $($('#customoption1 option')[i]).attr('data-options',JSON.stringify(oldSeerchfields[i].options));
            $($('#customoption1 option')[i]).attr('name',JSON.stringify(oldSeerchfields[i].name)); 
        } 
        
        
    });
      
      
      if(field.on_search_type=='dropdown'){
       
                    
       $(this).parent().find('.dropdowncustom').html(optionshtml);
        $(this).parent().find('.ui-multiselect').show();
        
        
        $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
        
        var $callback = $("#callback");
        
        var arr1=[];
        $($(this).parent().find('.dropdowncustom')).multiselect({
        show: "bounce",
        hide: "explode",
        click: function(event, ui){
          $callback.text(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
          
          if(ui.text!='--')
          arr1.push(ui.text.toLowerCase());
          
          customfields[field.code]=arr1.join(',').replace(/\s/g, "");
          
       }
    });
    
    //$($(this).parent().find('.dropdowncustom')).multiselect("checkAll");
    
      }
      else if(field.on_search_type=='range')
      {
       
       $(this).parent().find('.dropdowncustomrangemin select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemin').show();
       
       
       $(this).parent().find('.dropdowncustomrangemax select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemax').show();
       
       $(this).parent().find('.ui-multiselect').hide();
      } 
      
     }
     
      
    });
    
    
    
    
    
    
    
    $('#customoption1').on('change', function() {
    
    $('#customoption2').removeAttr('disabled');
    
    if($(this).val()=='0')
     {
      
      $(this).parent().find('.crossfilter').hide();
      
      $('#customoption2').parent().find('.crossfilter').hide();
      
      $('#customoption2').empty();
      $('#customoption2').attr('disabled','disabled');
      $('#customoption2').parent().find('.ui-multiselect').hide();
        
        
        $('#customoption2').parent().find('.dropdowncustomrangemin').hide();
        $('#customoption2').parent().find('.dropdowncustomrangemax').hide();
    
      $(this).parent().find('.ui-multiselect').hide();
        
        
        $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
     }
     else
     {
      
       $(this).parent().find('.crossfilter').show();
       
       var field = $(this).find(":selected").data('value');
      
      
      
      
      var options = $(this).find(":selected").data('options');
      
       var optionshtml = '<option value='+0+'>--</option>' ;
      
      
    for (var i = 0; i < options.length; i++) {
    optionshtml += '<option name='+options[i].name+' value='+JSON.stringify(options[i].id)+'>'+options[i].name+' '+field.unit+'</option>';
    } 
    
    
    
    var oldSeerchfields = [];
    $('#customoption1 option').each(function() {
       oldSeerchfields.push({ "id" : this.value, "text" : this.textContent ,"field":$(this).data('value'),"options":$(this).data('options'),"name":$(this).attr('name')});
    });
    
    
    
    
    oldSeerchfields= _.filter(oldSeerchfields, function (obj) {
       return obj.id!=field.code
    });
    
            
    var searchFieldshtml = '';
    for (var i = 0; i < oldSeerchfields.length; i++) {
    searchFieldshtml += '<option value='+JSON.stringify(oldSeerchfields[i].id)+'>'+oldSeerchfields[i].text+' </option>';
    } 
    
    
    $('#customoption2').empty().html(searchFieldshtml);
    
    $('#customoption2').parent().find('.ui-multiselect').hide();
        
        
        $('#customoption2').parent().find('.dropdowncustomrangemin').hide();
        $('#customoption2').parent().find('.dropdowncustomrangemax').hide();
    
    
    $('#customoption2 option').each(function() {
    
    for (var i = 0; i < oldSeerchfields.length; i++) {
        
            $($('#customoption2 option')[i]).attr('data-value',JSON.stringify(oldSeerchfields[i].field)); 
            $($('#customoption2 option')[i]).attr('data-options',JSON.stringify(oldSeerchfields[i].options));
            $($('#customoption2 option')[i]).attr('name',JSON.stringify(oldSeerchfields[i].name)); 
        } 
        
        
    });
    
    
      
      
      if(field.on_search_type=='dropdown'){
       
       $(this).parent().find('.dropdowncustom').html(optionshtml);
        $(this).parent().find('.ui-multiselect').show();
        
        
        $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
        
        var arr1=[];
        
        var $callback = $("#callback");
        $($(this).parent().find('.dropdowncustom')).multiselect({
        show: "bounce",
        hide: "explode",
        click: function(event, ui){
          $callback.text(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
          
          if(ui.text!='--')
          arr1.push(ui.text.toLowerCase());
          
          customfields[field.code]=arr1.join(',').replace(/\s/g, "");
          
       }
    
    });
    
    //$($(this).parent().find('.dropdowncustom')).multiselect("checkAll");
      }
      else if(field.on_search_type=='range')
      {
       
       $(this).parent().find('.dropdowncustomrangemin select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemin').show();
       
       
       $(this).parent().find('.dropdowncustomrangemax select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemax').show();
       
       $(this).parent().find('.ui-multiselect').hide();
       
       customfields[field.code]=arr1.join(',');
      } 
     }
      
    });
    
    
    
    
    
    $('#customoption2').on('change', function() {
    
    if($(this).val()=='0')
     {
      
      $(this).parent().find('.crossfilter').hide();
      $(this).parent().find('.ui-multiselect').hide();
        
        
        $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
     }
     else
     {
      
       $(this).parent().find('.crossfilter').show();
       
        var field = $(this).find(":selected").data('value');
      
      
      var options = $(this).find(":selected").data('options');
      
       var optionshtml = '<option value='+0+'>--</option>' ;
      
      
    for (var i = 0; i < options.length; i++) {
    optionshtml += '<option name='+options[i].name+' value='+JSON.stringify(options[i].id)+'>'+options[i].name+' '+field.unit+'</option>';
    } 
    
      
      
      if(field.on_search_type=='dropdown'){
       
        $(this).parent().find('.dropdowncustom').html(optionshtml);
        $(this).parent().find('.ui-multiselect').show();
       
       $(this).parent().find('.dropdowncustomrangemin').hide();
        $(this).parent().find('.dropdowncustomrangemax').hide();
       
       var arr1=[];
       
       var $callback = $("#callback");
       $($(this).parent().find('.dropdowncustom')).multiselect({
        show: "bounce",
        hide: "explode",
        click: function(event, ui){
          $callback.text(ui.value + ' ' + (ui.checked ? 'checked' : 'unchecked') );
          
           if(ui.text!='--')
          arr1.push(ui.text.toLowerCase());
          
          customfields[field.code]=arr1.join(',').replace(/\s/g, "");
          
       }
    
    });
    
    //$($(this).parent().find('.dropdowncustom')).multiselect("checkAll");    
       
      
      }
      else if(field.on_search_type=='range')
      {
       
       $(this).parent().find('.dropdowncustomrangemin select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemin').show();
       
       
       $(this).parent().find('.dropdowncustomrangemax select').html(optionshtml);
       $(this).parent().find('.dropdowncustomrangemax').show();
       
       $(this).parent().find('.ui-multiselect').hide();
      } 
     }
     
    });


    var arr2=[];
    $('.rangemin').on('change',function(){
     
     var min_range_value=$(this).find('option:selected').attr('name');
     
     
    var max_range_value=  $(this).parent().parent().find('.rangemax').find('option:selected').attr('name');
    
    
    
    fieldCode= $(this).parent().parent().parent().find('.customoption option:selected').val();
    
    
    
    
    
    
    if(min_range_value > max_range_value) 
    {
     //set max value same as min value
     
     $(this).parent().parent().find('.rangemax').val($(this).val());
     
     arr2=[];
     if(min_range_value!='--' || $(this).parent().parent().find('.rangemax option:selected').attr('name')!='--')
     arr2.push(min_range_value+'-'+$(this).parent().parent().find('.rangemax option:selected').attr('name'));
     
     customfields[fieldCode]=arr2.join(',');
    }else{
     arr2=[];
     if(min_range_value!='--' || max_range_value!='--')
     arr2.push(min_range_value+'-'+max_range_value);
     
     customfields[fieldCode]=arr2.join(',');
    }
     
     
    })
    
    
    
    $('.rangemax').on('change',function(){
     
     var max_range_value=$(this).find('option:selected').attr('name');        
     
    var min_range_value=  $(this).parent().parent().find('.rangemin').find('option:selected').attr('name');
    
    
    fieldCode= $(this).parent().parent().parent().find('.customoption option:selected').val();
    
    
    if(min_range_value > max_range_value) 
    {
     //set max value same as min value
     
     $(this).parent().parent().find('.rangemin').val($(this).val());
     
     arr2=[];
     if($(this).parent().parent().find('.rangemin option:selected').attr('name')!='--' || max_range_value!='--')
     arr2.push($(this).parent().parent().find('.rangemin option:selected').attr('name')+'-'+max_range_value);
     
     customfields[fieldCode]=arr2.join(',');
    
     
    }
    else{
     arr2=[];
     if(min_range_value!='--' || max_range_value!='--')
     arr2.push(min_range_value+'-'+max_range_value);
     
     customfields[fieldCode]=arr2.join(',');
    }
     
     
     
     
    })
  

    $('.crossfilter').on('click',function(){
        
        
        $(this).parent().find('.customoption').val('0');
        $(this).parent().find('.customoption').trigger('change');
        
        
        $('.tw3-filter__form__content__flex').hide();
                    
        
        
    })


    $('.savefiltersettings').on('click',function(){
               
                
                //gender
                var gender=[];
                $('.customchkbox').each(function() {
                    
                    
                    if( $(this).is(":checked"))
                                gender.push($(this).attr('name'));
                    
                });
                
                
                customfields['prefer_gender']=gender.join(',');
                
                //age
                customfields['prefer_age']=$('#age').val().replace(/\s/g, "");
                
                
                //distance
                
                customfields['prefer_distance']=$('#km').val();
                
                
        
                            
                
                $.ajax({
                  type: "POST",
                  url: filter_url,
                  data: customfields,
                  success: function(msg){
                       
                        
                        
                        if(msg.status=='error')
                        {
                            toastr.error(failed_save);
                        }
                        else
                        {
                            toastr.success(success_save);
                            
                            $('#filterModal').modal('hide');
                            
                            window.location.href = peoplenearby_url;
                                                    
                            
                        }
                  },
                  error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.info(api_error);
                  }
                });
            })
 