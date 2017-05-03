var ChatController = App.controller("ChatController", ["$scope", "$rootScope","$http", function($scope, $rootScope,$http){
		
		
		$scope.user_id='{{{$auth_user->id}}}';
		
		//fetch the list of users for chat(only for demo)(later it will be list of matched users)
		var res=$http.get("{{{ url('/get_all_users') }}}");

		res.success(function(data, status, headers, config) {
			
				$scope.users_chatList= data;
			
		});	
		
		//close the chat
		
		$scope.chatClose= function(id)
		{
			
			$('#'+id).fadeOut();
			
		}
		
		
		
		$scope.openChatDialog = function(id,user_name,profile_pic)
		{
			
			//$('#chat1').fadeIn(); 
			
			
			//form the html for chat dialog
			
		var chat_dialog="";
			chat_dialog += '  <div class=\"chat_container chat_dialog\" id='+id+'>'; 
			chat_dialog += "         <div class=\"chat_box\">";
			chat_dialog += "             <div class=\"chat_head\">";
			chat_dialog += '               <p>'+user_name+'<span>2<\/span><\/p>';
			chat_dialog += "               <ul class=\"list-inline chat_head_icon\">";
			chat_dialog += "                 <li><i class=\"material-icons\">videocam<\/i><\/li>";
			chat_dialog += "                 <li><i class=\"material-icons\">settings<\/i><\/li>";
			chat_dialog += "                 <li><i class=\"material-icons chat_close\" ng-click='chatClose("+id+")'>clear<\/i><\/li>";
			chat_dialog += "               <\/ul>";
			chat_dialog += "             <\/div>";
			chat_dialog += "             <div class=\"chat_body\">";
			chat_dialog += "                <div class=\"row\">";
			chat_dialog += '                  <img src='+profile_pic+'>';
			chat_dialog += "                  <div>";
			chat_dialog += "                    <h4><i class=\"fa fa-caret-left\"><\/i><span>djsdfsdfsdfs<\/span><\/h4>  ";
			chat_dialog += "                  <\/div>";
			chat_dialog += "                <\/div>";
			chat_dialog += "                <div class=\"row\">";
			chat_dialog += "                    <h5><span>djsdfsdfsdfs<\/span><i class=\"fa fa-caret-right\"><\/i><\/h5>  ";
			chat_dialog += "                <\/div>";
			chat_dialog += "                ";
			chat_dialog += "             <\/div>";
			chat_dialog += "             <div class=\"chat_footer\">";
			chat_dialog += "               <textarea rows=\"10\" cols=\"27\" placeholder=\"Type a message\"><\/textarea>";
			chat_dialog += "               <ul class=\"list-inline chat_footer_icon\">";
			chat_dialog += "                 <li><i class=\"material-icons\">insert_emoticon<\/i><\/li>";
			chat_dialog += "                 <li><i class=\"material-icons\">thumb_up<\/i><\/li>";
			chat_dialog += "               <\/ul>";
			chat_dialog += "             <\/div>";
			chat_dialog += "         <\/div> ";
			chat_dialog += "      <\/div>  ";
			
			
			
			
			$('.chat_prepend').prepend(chat_dialog);
			
		}
		
		
		//on click of a user, fetch the chat history form db
		var Chat = {};

Chat.current_user = $scope.user_id;


Chat.poll = "";
Chat.session = "";

Chat.send_message = function(text){
	$("#postText").val("");
	var msg = {};
	msg.to_user = Chat.to_user;
	msg.postText = text;
	msg._token = "{{{ csrf_token() }}}";
	$.post("{{{url('postChat')}}}",msg,function(data){

			console.log(data);
			//Chat.get_message();
	})


}


Chat.polling = function(){
/*
	if(Chat.session && Chat.session.to_user != Chat.to_user){ 
 	
 	if(Chat.poll){
 		Chat.poll.abort();
 	}

 		

 	}
*/
 	
 	if(Chat.poll){
 		Chat.poll.abort();
 	}

 	Chat.poll = Chat.get_message_poll();


}



Chat.get_message = function(){

	var data = {};

	data._token = "{{{ csrf_token() }}}";
	Chat.to_user = "{{-- $lastContact->user2 --}}";


	$.get("{{{url('chat/selectedUser/')}}}/"+Chat.to_user,data,function(data){

			$(".discussion").empty()

			console.log("Current Messages -> ", data.msg.length)
			
			Chat.session = {};
			Chat.session.to_user = Chat.to_user;
			Chat.session.count = data.msg.length;


			for(var i=0; i < data.msg.length; i++){
				
				Chat.add_message(data.msg[i].text);

			}

			Chat.polling();

	})


}

Chat.get_message_poll = function(){

	var data = {};

	data._token = "{{{ csrf_token() }}}";


	
	return $.get("{{{url('/getMessagePoll/')}}}/"+Chat.to_user+"/"+Chat.session.count,data,function(data){

			$(".discussion").empty()

			if(data.status == 0)
			{

			}
			else { 
			if(data.msg){ 
			Chat.session = {};
			Chat.session.to_user = Chat.to_user;
			console.log(data.msg.length);
			Chat.session.count = data.msg.length;
			
			for(var i=0; i < data.msg.length; i++){
				
				Chat.add_message(data.msg[i].text);

			}

			}

			}


			Chat.polling();

	})


}


Chat.scrollDown = function(){

	$(".module").animate({
				        scrollTop:  $("#postText").offset().top + $(".module").height() + $(".module")[0].scrollHeight
				   });
}


Chat.add_message = function(msg){

	var discussion = $(".discussion");
	var ele = $("<li>").addClass("chat-list-custom-styling");
	ele.html(msg);
	discussion.append(ele);

	// setInterval(function(){ 
        
	Chat.scrollDown();

	//}, 0); */
}



Chat.init = function(){


$(".to-user").click(function(){

Chat.to_user = $(this).data("user-id");

Chat.get_message();




});


$("#trig").click(function(e){

e.preventDefault();


Chat.add_message($("#postText").val());

Chat.send_message($("#postText").val());

});

if(Chat.to_user){ 
Chat.get_message();
}


}

$(function(){ 
	console.log("initializefd");
Chat.init();
});

		
		
		
		
		
	}]);
