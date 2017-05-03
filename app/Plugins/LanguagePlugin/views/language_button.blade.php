<link rel="stylesheet" type="text/css" href="@plugin_asset('LanguagePlugin/css/flag.css')">
<div class="dropdown dropdown-custom-styling">
    <div class="btn-group liteoxide-btngroup" style="top: 19px;">
        <div id="options"
            data-input-name="country2"
           	            
		            
			            
		     >
        </div>
    </div>
</div>


<script src="@plugin_asset('LanguagePlugin/js/flag.js')"></script>
<script>
    var temp=[];
    @foreach($langs as $language)
     temp.push('{{{$language}}}');
    @endforeach
     
     
     var url="@plugin_asset('LanguagePlugin/js/Country_Codes_JSON.js')";
     
   $.getJSON(url, function( data ) {
		
		  
		   var json= JSON.stringify(data);
		   
		   		   
		   
		   var data_modified= _.filter(data,function(val){
			   
			   return _.find(temp,function(item){
				   
				   
				   if (val['ISO3166-1-Alpha-2'].toLowerCase()== item)
				   		return true;
				   
			   })
			   
			   
		   })
		   
		   temp= _.object(_.map(data_modified, function(x)
		   {
			   return [x['ISO3166-1-Alpha-2'], x['ISO4217-currency_country_name']]
		})); 
		
		 $('#options').flagStrap({
	      countries:temp,
	      buttonSize: "btn-sm",
	      buttonType: "btn-info",
	      labelMargin: "10px",
	      scrollable: false,
	      scrollableHeight: "350px",
	      selectedCountry:'{{{$selected_language}}}'.toUpperCase()
	    });
		 
		  
	})
	.done(function(data){
		
		console.log(data);
		
		$("#options > ul > li > a").on('click',function(){
      
       var lang = $(this).data('val').toLowerCase();
       
       $(".loader").fadeIn("slow");
    
      var data = {};
      data['_token'] = "{{{csrf_token()}}}";
      data['language'] = lang;
      $.post("{{{url('set/language/cookie')}}}", data, function(response){
    
        if(response.status == 'success') {
            $(".loader").fadeOut("slow");
              toastr.success('{{{trans('app.your_language')}}}{{{trans('app.saved')}}}');
               window.location.reload();
        }
    
      });
      
    });
	
	})
     
    
    
   
    
</script>
<script type="text/javascript">
        
    
    
</script>