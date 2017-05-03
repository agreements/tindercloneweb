<style type="text/css">

/*.nav {
    left:50%;
    margin-left:-150px;
    top:50px;
    position:absolute;
}*/
.central-notification {
    position: absolute;
right: 40%;
top: -4px;
z-index: 5001;
}


.central-notification ul{
    overflow-y: scroll;
    max-height: 400px;
}


.central-notification li a:hover
{
	background: none;
}

.central-notification li a:active
{
	background: none;
}


.central-notification li:hover
{
	background: none;
}

.central-notification li:focus
{
	background: none;
}

.central-notification:hover
{
	background: none;
}

.central-notification ul li a img{
  width: 45px;
height: 45px;
border-radius: 50%;
margin-right: 15px;
object-fit:cover;
}

.central-notification .dropdown-menu  {
        z-index: 9999;
}

.central-notification li a {
	
	padding-left: 16px;
padding-right: 18px;
	}
.central-notification .dropdown-menu>li>a {
    color:#428bca;
    padding: 5px 18px 5px 18px;
}
.central-notification .fa {
	font-size: 20px !important;
position: relative;
width: 44px;
right: 0px;
top: 5px;
color: #d8d5d5;

-webkit-transition: all 0.25s cubic-bezier(0.31, -0.105, 0.43, 1.4);
transition: all 0.25s cubic-bezier(0.31, -0.105, 0.43, 1.4);
}

.central-notification .fa:hover {
	
	color: red;
	
	
	}
.central-notification .dropdown ul.dropdown-menu {
    border-radius:4px;
    box-shadow:none;
    margin-top:20px;
    width:auto;
}
.central-notification .dropdown ul.dropdown-menu:before {
    content: "";
    border-bottom: 10px solid #fff;
    border-right: 10px solid transparent;
    border-left: 10px solid transparent;
    position: absolute;
    top: -10px;
    left: 16px;
    z-index: 10;
}
.central-notification .dropdown ul.dropdown-menu:after {
    content: "";
    border-bottom: 12px solid #ccc;
    border-right: 12px solid transparent;
    border-left: 12px solid transparent;
    position: absolute;
    top: -12px;
    left: 14px;
    z-index: 9;
}
.central-notification .badge  {
   background: #e52b2b;
position: absolute;
top: 28px;
left: 35px;
}
.central-notification .nav > li >a {
    padding: 0px;
    text-align: center;
}

ul.central-notification > li{
	text-align: center;
}
.central-notification  #load-more-central-notifications {
    padding: 5px 0px 5px 0px;
text-align: center;
color: black;
background: #f6f1f1;
position: relative;
    bottom: -5px;
    
}

.notification_icon
{
	position: relative;
height: 25px;
top: 2px;

}
</style>

<ul class="central-notification nav navbar-nav hidden-xs">
<li class="dropdown">

  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	  <img src="@theme_asset('images/notification_colored.svg')" class="notification_icon"/>
	  <span class="badge animated bounce" id = "central-notification-counter-badge">@if($notif_count > 99) 99+ @else {{{$notif_count}}} @endif</span></a>
  <ul class="dropdown-menu" id = "central-notification-dropdown-list">
    @if($notification_items_content == '') 
        <li id = "no-notification-item"><a href="#">{{{trans('app.no_notifications')}}}</a></li>
    @else
        {!! $notification_items_content !!}
        <div id = "load-more-central-notifications"><a href="#"> {{{trans('app.see_more')}}} </a></div>
    @endif
  </ul>
</li>
</ul>
<input type="hidden" id = "last-central-notification-timestamp" value = "{{{$last_notification_timestamp}}}">
<input type="hidden" id ="visit_notificaion_text" value = "{{{trans('app.visit_notif_text')}}}">
<script type="text/javascript">
    
    $(document).ready(function(){
	    
	    
	    if({{{$notif_count}}}==0)
	    {
		    $("#central-notification-counter-badge").hide();
	    }


        $(".central-notification .dropdown").on('click', function(){


            $.post('{{{url("notifications/central/mark-seen-all")}}}', function(res){

                if (res.status == 'success') {

                    $("#central-notification-counter-badge").hide();
                }

            });


        });




        $(".central-notification").on('click', '#load-more-central-notifications > a', function(e){

            e.stopPropagation();

            var last_notification_timestamp = $("#last-central-notification-timestamp").val();

            $.get('{{{url('notifications/central')}}}?last_notification_timestamp='+last_notification_timestamp, function(res){



                if (res.status == "success" && res.notifications_count != 0) {

                    $("#last-central-notification-timestamp").val(res.last_notification_timestamp);

                    $("#central-notification-dropdown-list").find(" > li:nth-last-child(2)").after(res.notification_items_content);


                } else {
                    $("#load-more-central-notifications").hide();
                }



            });



        });



    });    


</script>