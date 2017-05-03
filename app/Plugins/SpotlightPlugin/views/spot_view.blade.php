<div class="modal fade" id="spot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form role="form"  enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{{trans_choice('app.add_me_here', 1)}}}</h4>
                </div>
                <div class="modal-body">
                    <div class="inline brick-wrap">
                        <div class="brick brick--xxxlg brick--stroke js-photo-wrap">
                            <div class="brick-img"><img src="{{{$auth_user->profile_pic_url()}}}" width="150" height="150" alt=""></div>
                            <div class="brick brick--sm brick--stroke brick--bc"></div>
                        </div>
                    </div>
                    @if($credit->balance < $spotCredits)
                    <span class="min_credits"> {{{trans('app.spot_credit_min')}}} {{{$spotCredits}}} {{{trans('app.credits')}}}</span>
                    @else
                    {!! csrf_field() !!}
                    <div class="form-group" style = "color : black">
                        <label for="name" style="color: #AFADAD">{{{trans('app.spot_pay_with')}}} <span class="textspecial credits">{{{$spotCredits}}}</span> {{{trans('app.credits')}}}</label>
                    </div>
                    @endif
                </div>
                <div class="modal-footer" style="position: relative">
                    <!--                     <button type="button" class="btn btn-default custom_modal_cancel" data-dismiss="modal">Close</button> -->
                    @if($credit->balance < $spotCredits)
                    <a href="{{{ url('credits') }}}"><button type="button" class="btn btn-default custom_modal-popup">{{{trans('app.add_credits')}}}</button>	</a>
                    @else
                    <button type="button" class="btn btn-default custom_modal-popup" onclick="add_to_spotlight()">{{{trans_choice('app.spot_add', 0)}}}</button>			            
                    @endif
                    <div class="loaderUpload"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container demo-3">
    <div class="main">
        <!-- Elastislide Carousel -->
        <ul id="carousel" class="elastislide-list">

        	@if($spotlight_only_superpowers != 'true')

        		<li data-toggle="tooltip" title="{{{trans_choice('app.add_me_here', 0)}}}" ><a href="#" style="position: relative"><img   class="thumb"   src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="{{{$auth_user->thumbnail_pic_url()}}}" data-description="Add Photo"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="{{{asset('images/solid_red_background_209961.jpg')}}}" class="transparentcover" /><i class="material-icons ico ico--white ico--plus-lg" data-toggle="modal"  data-target="#spot">add</i></a></li>


        	@endif

            @foreach($spots as $spot)
            <li><a href='{{{url("/profile/".$spot["id"])}}}' data-toggle="tooltip" title="{{{$spot['name']}}}"><img  class="thumb spotlighthover" src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="{{{asset('uploads/others/thumbnails/'.$spot['profile_pic_url'])}}}" alt="{{{$spot['name']}}}" /></a></li>
            @endforeach
        </ul>
        <!-- End Elastislide Carousel -->
    </div>
</div>
@section('plugin-scripts')
@parent
<script src="{{{asset('js/jquerypp.custom.js')}}}"></script>
<script src="{{{asset('js/jquery.elastislide.js')}}}"></script>
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
    console.log('testing-spotlight');
    
    	function add_to_spotlight()
    	{
    		
    		var Request= {
    			
    			id:'{{{$auth_user->id}}}'
    		};
    		
    		 $('.loaderUpload').fadeIn();
    		
    		$.post("{{{url('/user/spotlight/add')}}}",Request, function(data){
    
    			 $('.loaderUpload').fadeOut();
    			toastr.info('{{{trans_choice('app.spot_add', 1)}}}');
    			window.location.reload();
    
    		});
    
    		
    	}
    
    
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
<!--  <link media="all" type="text/css" rel="stylesheet" href="@theme_asset('css/elastislide.css')"> -->
@endsection
@section('credits-scripts')
<script>
    console.log('from spotlight to credit ');
</script>	
@endsection