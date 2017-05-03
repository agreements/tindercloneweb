<?php use App\Components\Theme;?>
<header style="max-width: 1262px;width: 100%;margin: 0 auto;" >
    <div class="head_background">
        <i id="c-button--slide-left" class="fa fa-align-justify  c-button"></i>
        <div class="col-md-2  col-xs-4" style="z-index:999999;text-align: right">
            <!-- Sidebar START -->
            <nav id="c-menu--slide-left" class="c-menu c-menu--slide-left navigation">
                <button class="c-menu__close">&larr; Close Menu</button>
                <ul class="c-menu__items">
                    {{{Theme::render('main_menu')}}}   
                    <li><a href="{{{ URL::to('settings') }}}"><i class="material-icons pull-left material-icon-custom-styling">settings</i>{{{ trans("app.preferences")}}}</a></li>
                    <li class=""><a href="#"><i class="material-icons pull-left material-icon-custom-styling">block</i>{{{ trans("app.blockedusers")}}}</a></li>
                </ul>

                @if($peoplenearby_only_superpowers != 'true')
                    <button type="button" data-toggle="modal" data-target="#myModalRiseup"  title="{{{trans('app.boost_btn_text')}}}" class="btn btn-default custom_modal-popup2 boostbtn" style=" top: 547px;left: 81px"><span class="">{{{trans('app.boost')}}}</span></button> 
                @endif

            </nav>
            <div id="c-mask" class="c-mask"></div>
            <div style="height: 50px;top: 10px;position: relative;">
                <a href="{{{url('/home')}}}"><img class="liteoxide-top-custom-styling pulse2"  src="{{{asset('uploads/logo')}}}/{{{$website_logo}}}"></a>
            </div>
        </div>
        <div class="col-md-3 col-xs-8 hidden-sm hidden-sm hidden-xs" >
            <ul class="nav nav-pills left_options">
                <li @if(isset($page) && $page == "encounter") class="active" @endif>
                <a href="{{{url('encounter')}}}">{{{trans_choice('app.encounter',2)}}}</a>
                </li>
                <li @if(isset($page) && $page == "ppl_near_by") class="active" @endif><a href="{{{url('peoplenearby')}}}">{{{trans_choice('app.peoplenearby',1)}}}</a></li>               
            </ul>
        </div>
        {{{Theme::render('central_notifications')}}}
        <div class="col-md-7 col-xs-5 ">
            <div class="col-md-2 col-xs-4 hidden-sm hidden-xs" style="top: 3px;text-align: right;left:34px;">
<!--                 <div id="circle2" ><strong class="score2" data-toggle="tooltip" title="{{{trans('app.popularity_score')}}}"></strong></div> -->
            </div>

            
                <div class="col-md-2 col-xs-4 hidden-sm hidden-xs" >

                    @if($peoplenearby_only_superpowers != 'true')

<!--                        <button type="button" data-toggle="modal" data-target="#myModalRiseup"  title="{{{trans('app.boost_btn_text')}}}" class="btn btn-default custom_modal-popup2"><span class="">{{{trans('app.boost')}}}</span></button> --> 
                       
<!--                        <img data-toggle="modal" data-target="#myModalRiseup"  data-hover="tooltip" title="{{{trans('app.boost_btn_text')}}}" src="@theme_asset('images/startup_rocket.svg')" class="boost_image"/> -->

                    @endif

                </div>
           

            <div class="col-md-3 col-xs-3 message_b hidden-sm">

                @if($credits_module_available == 'true')

<!--
                    <a href="{{{ url('credits') }}}" class="tw3-button tw3-button--yellow tw3-button--subtle tw3-button--small">

<img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="@theme_asset('images/coins.svg')" height="30" width="30"/>
                    <span class="textspecial credits">{{{$credit->balance}}}</span> {{{ trans("app.credits")}}}
                    </a>
-->
                    
<!--
				 <a href="#" data-type="PREMIUM" data-nomodal="yes" data-trigger="topupgradelink" class="jsPaywall tw3-button tw3-button--green tw3-button--premium  tw3-button--small">
				 		<span>Premium</span>
				</a>
-->
                     <a class="button" href="{{{ url('credits') }}}" role="button">
						<span data-toggle="tooltip" title="{{{ trans('app.credits')}}} {{{$credit->balance}}}">{{{ trans("app.credits")}}} {{{$credit->balance}}}</span>
						<div class="icon">
<!-- 							<i class="fa fa-remove"></i> -->
							<img src="@theme_asset('images/coins.svg')" height="30" width="23"/>
						</div>
                     </a>
                @endif

            </div>
            <div class="col-md-4 col-xs-8 user_profile" >
                <ul class="list-inline">
                    <li><a href="{{{url('profile/'.$auth_user->id)}}}"><img class="img-circle" src="{{{ $auth_user->thumbnail_pic_url()}}}" alt="..."></a></li>
                    <li class="user_name_header"><a data-toggle="tooltip" title="{{{$auth_user->name}}}" href="{{{url('profile/'.$auth_user->id)}}}" class="profile_user_name">{{{$auth_user->name}}}</a></li>
                    <li class="dropdown profile_drop_menu">
                        <button class="dropdown-toggle profile_drop_menu" type="button" data-toggle="dropdown"><i class="material-icons">more_vert</i></button>
                        <ul class="dropdown-menu">
                            <li class="dropdown-li-custom-styling"><a href="{{{url('profile/').'/'.$auth_user->id}}}">{{{ trans("app.profile")}}}</a></li>
                            <li class="dropdown-li-custom-styling"><a href="{{{url('settings')}}}">{{{ trans("app.preferences")}}}</a></li>
                            <li class="dropdown-li-custom-styling dropdown-li-custom-styling-signout"><a href="{{{url('/logout')}}}">{{{ trans("app.signout")}}}</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</header>

{{{Theme::render('spot')}}}
<div class="col-md-12 text-center ad-col-head add_box">
    {{{Theme::render('top-ad')}}}
</div>
@section('header-scripts')
@parent
<script src="@theme_asset('js/Menu.js')"></script>
<!-- <link rel="stylesheet" type="text/css" href="@theme_asset('css/jquery-ui.css')" /> -->


<!-- <script type="text/javascript" src="@theme_asset('js/jquery-ui.js')"></script> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script> 

<!-- <script src="@theme_asset('js/multiselect.js')"></script> -->
<script src="https://cdn.jsdelivr.net/jquery.multiselect/1.13/jquery.multiselect.min.js"></script>


<script>
	    var slideLeft = new Menu({
       wrapper: '#html_eesh',
       type: 'slide-left',
       menuOpenerClass: '.c-button',
       maskId: '#c-mask'
     });
     var slideLeftBtn = document.querySelector('#c-button--slide-left');
     
     slideLeftBtn.addEventListener('click', function(e) {
       e.preventDefault;
       slideLeft.open();
     });
</script>	
<script>
	
    $('.boostbtn').on('click',function(){
        
        
        $('#c-menu--slide-left').removeClass('is-active');
        $('#c-mask').removeClass('is-active');
    })
    
    $('.c-menu__items li').on('click',function(){
        
        
        $('#c-menu--slide-left').removeClass('is-active');
        $('#c-mask').removeClass('is-active');
    })
</script>   
@endsection