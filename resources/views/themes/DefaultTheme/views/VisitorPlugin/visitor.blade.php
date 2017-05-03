<?php use App\Components\Theme; ?>
@extends(Theme::layout('master'))
@section('content')
@parent
<!-- <div class="col-md-12 col-xs" style="box-shadow: 0px 1px 4px rgba(0,0,0,0.36)"> -->
<div class="col-md-12 col-xs mid_body_container">
    <div class="row">
        <div class="col-md-12 mid_body_head">
            <h4>{{{ trans("app.visitors")}}}</h4>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div class="">
                @if($can_see_visitors)
	                @foreach($visitors as $v)
	                <div ng-click="gotoprofile('{{{ url("/profile/$v->id") }}}')" class="col-md-4 col-xs-12 person_box" style="
	                    background-image: url('{{{$v->others_pic_url()}}}');
	                    background-repeat: no-repeat;
	                    background-size: cover;
	                    background-position: center;
	                    cursor:pointer;
	                    ">
	                 
	                    @if(count($v->photos))
	                    <div class="photo-counter"> <i class="fa fa-camera user_photos_list"></i><span class="small">{{{count($v->photos)}}}</span> </div>
	                    @endif
	                    <ul class="list-inline">
	                        @if($v->onlineStatus())
	                        <li style="float:right;margin-top: 7px;"><i class="fa fa-circle small_circle" style="color:#00BF00;display:inline"></i></li>
	                        @endif
	                        <li class="user_name"><a class="profile_visit" href="{{{ url("/profile/$v->id") }}}" >{{{$v->name}}}</a></li>
	                        <p>{{{$v->city}}}, {{{$v->country}}}</p>
	                    </ul>
	                </div>
	                @endforeach

	                @if($total_visitors_count== 0)
	                <p class="mv30 teardropAnimation dib" style="text-align: center">
	                    <span class="tear"></span>
	                    <img src="@theme_asset('images/crying.png')" width="192" height="192">
	                </p>
	                <div class="mv20 fs16" style="text-align: center">
	                    {{{trans_choice('app.no_visitor',1)}}}
	                </div>
	                <div class="encounters_more">
	                	<a class="explore_more button--large button--blue" href = "{{{ url('/peoplenearby') }}}">
	                	{{{trans_choice('app.get_visitor',1)}}}</a>
	                </div>
	                @endif

                @else
                <div class="" style = "color : black;text-align: center">
                    @if($total_visitors_count!= 0)
	                    <p class="mv30 teardropAnimation dib">
	                        <span class="tear"></span>
	                        <img src="@theme_asset('images/happy.png')" width="192" height="192">
	                    </p>
	                    <div class="col-md-12">
	                        @foreach($visitors as $v)
	                        <img class="vistors-pictures-blurred" src="{{{$v->profile_pic_url()}}}">
	                        @endforeach
	                    </div>
	                    <br/>
	                    <div class="mv20 fs16" style="font-size: 18px;">
	                        {{{trans_choice('app.you_have',1)}}} <span style="font-size: 25px">{{{$total_visitors_count}}}</span> {{{trans_choice('app.your_visitor', $total_visitors_count)}}}.
	                    </div>
	                    <div class="encounters_more"><a class="explore_more button--large button--blue upgrade-now" href = "#"> {{{trans_choice('app.activate_superpower',1)}}}</a></div>
                    @else	
	                    <p class="mv30 teardropAnimation dib">
	                        <span class="tear"></span>
	                        <img src="@theme_asset('images/crying.png')" width="192" height="192">
	                    </p>
	                    <div class="mv20 fs16">
	                        {{{trans_choice('app.no_visitor',1)}}}
	                    </div>
	                    <div class="encounters_more"><a class="explore_more button--large button--blue" href = "{{{ url('/credits') }}}">{{{trans_choice('app.get_visitor',1)}}}</a></div>
                    @endif
                </div>
                @endif
            </div>
        </div>
        <div class="pagination_cnt">{!! $visitors->render() !!}</div>
    </div>
</div>
@endsection
@section('scripts')				
<!-- custom scrollbar plugin --> 
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script> 
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
<script>
    $(document).ready(function(){
    	$('.fa.fa-angle-down.d-arrow-1').click(function(){
    		 $(this).next('.hover-cover').slideToggle();
    	});
    	$('.fa.fa-angle-down.d-arrow').click(function(){
    		$('.sign-out ').slideToggle();
    	});
    });
</script>
@endsection
<style>
    .vistors-picture-styling
    {
    margin-top:10px;
    }
</style>