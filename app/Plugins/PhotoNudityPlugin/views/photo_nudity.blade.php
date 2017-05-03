<div id="photoNudeModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="">
        <!-- Modal content-->
        <div class="modal-content " style="padding:5%;">
            <div class="">
                <h5 class="" style="font-size: 18px!important;color: #3F4752;line-height: 1.5em">{{{trans('admin.nude_photo_modal_header')}}}:
                </h5 >
            </div>
            <div class="modal-body">
                <div class="loaderUpload"></div>
                <div class="photo-rules">
                    <div class="ol">
                        <ol class="x-large">
                            <li>{{{trans_choice('admin.nude_photo_modal_list',0)}}}</li>
                            <li>{{{trans_choice('admin.nude_photo_modal_list',1)}}}</li>
                        </ol>
                    </div>
                </div>
                <button type="button" class="btn btn-default custom_modal-popup4 delete_nude_photo" style="margin-right: 20px">
                    <div class="loaderUpload"></div>
                    <span class="">{{{trans_choice('app.delete',0)}}}</span>
                </button>
                <button type="button" class="btn btn-default custom_modal-popup3 cancel_delete" data-dismiss="modal" ><span class="">{{{trans_choice('app.cancel',1)}}}</span></button>
            </div>
        </div>
    </div>
</div>
@section('plugin-scripts')
@parent
<script>
    $(document).ready(function() {
    			$('#myCarouselMyPhotos > .carousel-inner  > .item > .fancybox  >  img').each(function(){
    			
    				var imgel= $(this);
    				
    				var img_url = imgel.attr('id');
    				
    				if(!imgel.data('photochecked'))
					{
						
						
						
    					$.ajax({
    					  type: "GET",
    					  jsonp: "callback",
    					  beforeSend: function (request)
    			            {
    			                request.setRequestHeader("X-Mashape-Key", 'fEoAApL9WJmshin0xYkbBcPND2d2p1Jg9lMjsntTSpx2GAOFpu');
    			                 request.setRequestHeader("Accept", 'text/plain');
    			                  request.setRequestHeader("Content-Type", 'application/json');
    			                
     			               
    			            },
    			           dataType: 'json',
    					  url: "https://mdr8.p.mashape.com/api/?url="+img_url,
    					  data: {},
    					  success: function(msg){
	    					  
	    					   //report this photo as nude to admin
							    $.ajax({
								  type: "POST",
								  url: "{{{ url('/mark-photo-checked') }}}",
								  data: {photo_name:img_url.substring( img_url.lastIndexOf('/')+1, img_url.length )},
								  success: function(msg){    									        
								  },
								  error: function(XMLHttpRequest, textStatus, errorThrown) {
								  }
								  
								});   
	    					  
	    					  
	    					      					        console.log(msg);
    					        
    					        if(msg.predictions!=undefined && (msg.predictions.teen > {{{$nudity_percentage}}} || msg.predictions.adult > {{{$nudity_percentage}}}))
    					        {
    					           imgel.addClass('image_nude');
    					           imgel.prop("onclick", false);
    					           
    					           	
    					           	var url = img_url;
    					            var photo_name = url.substring( url.lastIndexOf('/')+1, url.length );
    					           
    					           
    					           
									 //report this photo as nude to admin
								    $.ajax({
									  type: "POST",
									  url: "{{{ url('/report-nude-photo') }}}",
									  data: {photo_name:img_url.substring( img_url.lastIndexOf('/')+1, img_url.length )},
									  success: function(msg){  
										  
										  									        
									  },
									  then: function(){
										
										  
									  },
									  error: function(XMLHttpRequest, textStatus, errorThrown) {
									  }
									  
									});

    					           
    					           
    					           
    					           
    						           imgel.click(function(event) {
    								    event.stopPropagation();
    								    
    								    event.preventDefault();
    								    
    								    


    								    
    								    $('.photo_tobedeleted_url').val($(this).attr('src').substring($(this).attr('src').lastIndexOf('/')+1));
    								    
    								    
    								    $('#photoNudeModal').modal('show');
    								});
    					         }  
    					  },
    					  then:function(){
	    					  
	    					 	    					  
    					  },
    					  error: function(XMLHttpRequest, textStatus, errorThrown) {
    					        //toastr.error("{{{trans_choice('app.error',1)}}}");
    					  }
    					});	
		
    				}
					    			
    			
    			})
    });
    
</script>
@endsection