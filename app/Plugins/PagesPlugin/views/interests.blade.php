<?php use App\Components\Theme; ?>
@extends(Theme::layout('master-nocols'))
        @section('content')
          @parent
  <div class="centered_div">
     <div class="col-md-12 col-xs-12 top_chat_div">
         
         <div class="col-md-10 chat_text_div">
            <h3 class="font_bold" style="color:black">{{{$interest->interest}}}</h3>
            <a href="{{{url('/')}}}"><button type="button" class="btn btn-primary chat_top_btn">Chat with them Now!</button></a>
        </div>
     </div>
     <div class="col-md-12 col-xs-12 interest_img_div">
        <div class="row">
           <div class="col-md-12 col-xs-12 select_tag_div">
               <ul id="select-ul" class="list-inline">
                 <li class="active"><a href="#">Members</a><span class="count_li">{{{$users_interest->count}}}</span></li>
               </ul>
           </div>
           <div class="col-md-12 col-xs-12 interest_main_div">
              <h4 class="img_div_text">Get Connected,Chat and Date-Find Online Dating Profiles who like {{{$interest->interest}}} Today!</h4>
              @foreach($users_interest as $user)
              <a href="{{{url('profile/'.$user->user->id)}}}"><div class="img-div" style="background:url({{{$user->user->profile_pic_url()}}});">
                 <ul class="list-inline img_ul">
                   <li class="display_block user_name_li">@if($user->user->onlineStatus())<span class="online-icon"><i class="fa fa-circle"></i></span>@endif{{{$user->user->name}}},{{{$user->user->age()}}}</li>
                   <li class="display_block user_city_li">@if($user->user->city != ''){{{$user->user->city}}},@endif @if($user->user->country != ''){{{$user->user->country}}}@endif</li>
                 </ul>
              </div></a>
              @endforeach
              {!! $users_interest->render() !!}
              
           </div>
        </div>   
     </div>
     
  </div>
          @endsection
@section('scripts')
 <script>
  $(document).ready(function() {
    var showChar = 220;
    var ellipsestext = "...";
    var moretext = "more";
    var lesstext = "less";
    $('.more').each(function() {
        var content = $(this).html();
 
        if(content.length > showChar) {
 
            var c = content.substr(0, showChar);
            var h = content.substr(showChar-1, content.length - showChar);
 
            var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><span class="morecontent"><span>' + h + '</span>&nbsp;&nbsp;<a href="" class="morelink">' + moretext + '</a></span>';
 
            $(this).html(html);
        }
 
    });
 
    $(".morelink").click(function(){
        if($(this).hasClass("less")) {
            $(this).removeClass("less");
            $(this).html(moretext);
        } else {
            $(this).addClass("less");
            $(this).html(lesstext);
        }
        $(this).parent().prev().toggle();
        $(this).prev().toggle();
        return false;
    });
});
 </script> 
 <script>
   $(document).ready(function(){ 
    $("#select-ul li").on("click", function() {
      $("#select-ul li").removeClass("active");
      $(this).addClass("active");
    });
   });
</script>
@endsection

