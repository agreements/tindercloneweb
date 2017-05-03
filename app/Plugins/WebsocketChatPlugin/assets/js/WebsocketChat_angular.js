App.directive('scrollToLast', ['$location', '$anchorScroll', function($location, $anchorScroll){
  
  function linkFn(scope, element, attrs){
	  
	   console.log('anchor scroll fired');
      $location.hash(attrs.scrollToLast);
      $anchorScroll();
      
     
      
      
  }
  
  return {
    restrict: 'AE',
    scope: {
      
    },
    link: linkFn
  };
  
}]);

App.filter('videoembed',function($sce){
	
	 return function(newVal) {
		 
		   		var embedFriendlyUrl = "",
                    urlSections,
                    index;

                var youtubeParams ='?autoplay=0&showinfo=1&controls=0';

		 
		  			if (newVal.indexOf("vimeo") >= 0) { // Displaying a vimeo video
                            if (newVal.indexOf("player.vimeo") >= 0) {
                                embedFriendlyUrl = newVal;
                            } else {
                                embedFriendlyUrl = newVal.replace("http:", "https:");
                                urlSections = embedFriendlyUrl.split(".com/");
                                embedFriendlyUrl = embedFriendlyUrl.replace("vimeo", "player.vimeo");
                                embedFriendlyUrl = embedFriendlyUrl.replace("/" + urlSections[urlSections.length - 1], "/video/" + urlSections[urlSections.length - 1] + "");
                            }
                        } else if (newVal.indexOf("youtu.be") >= 0) {

                            index = newVal.indexOf(".be/");

                            embedFriendlyUrl = newVal.slice(index + 4, newVal.length);
                            embedFriendlyUrl = "https://www.youtube.com/embed/" + embedFriendlyUrl + youtubeParams;
                        } else if (newVal.indexOf("youtube.com") >= 0) { // displaying a youtube video
                            if (newVal.indexOf("embed") >= 0) {
                                embedFriendlyUrl = newVal + youtubeParams;
                            } else {
                                embedFriendlyUrl = newVal.replace("/watch?v=", "/embed/") + youtubeParams;
                                if (embedFriendlyUrl.indexOf('m.youtube.com') != -1) {
                                    embedFriendlyUrl = embedFriendlyUrl.replace("m.youtube.com", "youtube.com");
                                }
                            }
                        }
		 
		 
		 if(embedFriendlyUrl)
		 {
			 var html='<iframe class=videoClass type="text/html" src='+embedFriendlyUrl+' allowfullscreen frameborder="0"></iframe>';
			  //var html='<a href='+embedFriendlyUrl+'></a>';
			  
			 return $sce.trustAsHtml(html);
		 	
		 }	
		 else
		 {
		 	return $sce.trustAsResourceUrl(newVal);	
		 }	
		 
    };
	
});





App.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });
 
                event.preventDefault();
            }
        });
    }
 });   
 
 
 

 
 

/* this is main chat controller */

App.controller('WebsocketChatController',function($scope,$rootScope,$http, socket, $timeout,$location,$anchorScroll,Upload,$log,$timeout,screenSize) {
	
	
	
	//$rootScope.$broadcast('video_injection','video chat injected');


	$rootScope.$on('video_injection', function(event, data) {
            console.log(data);
    });

$scope.youtube='https://www.youtube.com/watch?v=QHulaj5ZxbI';

	// Using dynamic method `on`, which will set the variables initially and then update the variable on window resize
	$scope.desktop = screenSize.on('md, lg', function(match){
	    $scope.desktop = match;
	});
	$scope.mobile = screenSize.on('xs, sm', function(match){
	    $scope.mobile = match;
	});




	 $scope.$watch('files', function () {
        $scope.upload($scope.files);
    });
    $scope.$watch('file', function () {
        if ($scope.file != null) {
            $scope.files = [$scope.file]; 
        }
    });
    $scope.log = '';
    
    var upload = "";

    $scope.upload = function (files) {
        if (files && files.length) {
            for (var i = 0; i < files.length; i++) {
              var file = files[i];
              if (!file.$error) {
                upload = Upload.upload({
                    url: uploadPhoto_chat,
                    data: {
                      
                      image: file  
                    }
                });
                
                upload.then(function (resp) {
                    $timeout(function() {
                        //$scope.log = 'file: ' +
                        //resp.config.data.file.name +
                        //', Response: ' + JSON.stringify(resp.data) +
                        //'\n' + $scope.log;
                        
                        
                        console.log('changing the hash');
                        removeHashToPreventJump();
                        
                        $scope.submitMessageWithMedia(resp.data.image);
                        
                        $('#uploadPhoto').modal('hide');
                    });
                }, null, function (evt) {
                    var progressPercentage = parseInt(100.0 *
                    		evt.loaded / evt.total);
                    $scope.log = progressPercentage ;                });
              }
            }
        }
    };



	// 	cancel upload
	$scope.cancelUpload = function()
	{
		upload.abort();
		
		$scope.log='';
		
	}

	// 	view user profile
	$scope.viewProfile =function(userid)
	{
		window.location.href=profile_url_chat+"/"+userid;
		
	}
	
	
	//block user
	$scope.blockUser = function(user_to_block)
	{
				$.ajax({
						  type: "POST",
						  url: user_block_chat,
						  data: {user_id:user_to_block},
						  success: function(msg){
						        
						        
						        
						        if(msg.status=='error')
						        {
						        	toastr.error(cannotblock);
						        }
						        else
						        {
						        	toastr.success("User blocked");
						        	
						        	socket.emit('user_blocked', {to_user:user_to_block,blocked_user_id:$scope.authUser});

								
									
						        	$scope.getChatUsers();
						        							        	
						        }
						  },
						  error: function(XMLHttpRequest, textStatus, errorThrown) {
						        toastr.info("some error");
						  }
						  
						});

		
	}
	
	socket.on('user_blocked', function(data){
        $scope.getChatUsers();
    });
    
    socket.on('user_unblocked', function(data){
        $scope.getChatUsers();
    });
    
    
    socket.on('notifications', function(data){
        
        
        console.log(JSON.stringify(data));
        
        
        $scope.render_notification(data);
        
    });
    
    
    
   
    


    $scope.render_notification = function(data)
    {
	    	toastr.options.closeButton = true;
	                        
	                         toastr.options.hideDuration = 2000;
	                          toastr.options.timeOut = 0;
	    
	    	if(data.notification.notification_hook_type=='central')
	    	{
		    	if(data.notification.status == "unseen" && data.notification.from_user != $scope.authUser){
		    	
			    	var text = parseInt($("#central-notification-counter-badge").html());
			    	
			    	
			    	if(isNaN(text)){
						text= 0;
					}
					
					
					$("#central-notification-counter-badge").show();
					$("#central-notification-counter-badge").html(text+1);
					
					
					if(data.notification.from_user <= 0)
					{
						$("#central-notification-dropdown-list").prepend('<li><a href="#">'+eval(data.notification.type)+'</a></li>');
					}
					else{
						
						toastr.success('<img height="40" width="40" style="border-radius:50%;margin-right:5px;display:block" src='+base_url+'/uploads/others/thumbnails/'+data.from_user.profile_pic_url+'/>'+data.from_user.name+' '+eval(data.notification.type));
						
						$("#central-notification-dropdown-list").prepend('<li><a href='+base_url+'/profile/'+data.from_user.id+'><img src='+base_url+'/uploads/others/thumbnails/'+data.from_user.profile_pic_url+'/>'+data.from_user.name+' '+eval(data.notification.type)+'</a></li>');
					}
					
					
					
					
					
				}
		    	
	    	}
	    	else if(data.notification.notification_hook_type=='left_menu')
	    	{
		    	var text = parseInt($(".notification-"+data.notification.type).html());
		    	
		    			    	
				if(data.notification.status == "unseen" && data.notification.from_user != $scope.authUser){ 
				
					if(data.notification.type == "visitor"){ 
						//toastr.success(data.from_user.name+' Visited Your Profile');
						toastr.success('<img height="40" width="40" style="border-radius:50%;margin-right:5px" src='+base_url+'/uploads/others/thumbnails/'+data.from_user.profile_pic_url+'/>'+data.from_user.name+' '+visited);
						
					}
					
					
					if(data.notification.type == "payment"){
						
						toastr.success(fortumo_success);
					}
					
					if(data.notification.type == "match"){
						
						toastr.success(match);
					}
					
					if(data.notification.type == "liked"){
						
						toastr.success(liked_you);
					}
					
					
					if(isNaN(text)){
						text= 0;
					}
					
					if(text > 99)
			    	{
				    	$(".notification-"+data.notification.type).html('99+');
			    	}
			    	else
			    	{
				    	$(".notification-"+data.notification.type).html(text+1);
			    	}
					
					
					
				}
				
		
				

				
				
		    	
	    	}
	    	
	    	

    }
    
    $scope.getSocketObj= function()
    {
	    
	    return socket;
    }	
    
    
    $rootScope.addToContactsNew= function(user)
    {
	    
	    
	    
	    data={
					user_id:user
					
		};
	    
	   
	    
	    
	    //check if this user has blocked you or not
	   
			$.ajax({
			  type: "POST",
			  url: user_already_blocked_chat ,
			  data: data,
			  success: function(data){
			        
			        
			        
			        if(data.blocked==false)
			        {
				        
				        formData={user_id:user};
			        	
			        	var res=$http.post(add_contact_chat_url,formData);
    
		    			res.success(function(data, status, headers, config) {
		    				
		    					
			    					
			    					//socket.emit('user_unblocked', {to_user:user,unblocked_user_id:$scope.authUser});
		    						
		    						$('#websocket_chat_modal').modal('show');
		    						
		    						//$scope.total_contacts_count++;
		    						
		    						
		    						
		    						
		    						if(data.status=='success' && data.success_type=='NEW_CONTACT')
					    			{
						    			
						    			$scope.getChatUsers();
						    			
						    			$scope.load_chat_data_first = true;
						    			
						    			$('#fourth_div').hide();
					    			}	
		    						//$scope.getChatUsers();			 		    						
		    					
		    				
		    							
		    					
		    				
		    			});	
		    			
		    			res.then(function(response){
			    			
			    			
			    			if(response.data.status=='error' && response.data.error_type=='ALREADY_CONTACTED')
			    			{
				    			$scope.getChatUsers();
				    			
				    			$scope.load_chat_data_first = true;
			    			}
			    			
		    			})

			        	
			        }
			        else
			        {
			        	toastr.error('First unblock the user');	
			        }	
		       }
			 			  
		     })		     
			     
		    
    }
	
	
	
	//delete the conversation
	$scope.deleteConversation = function(user)
	{
		
	
        $http.post(deleteContact_chat_url, {contact_id:user.contact_id})
        .then(function(response){

            if (response.data.status == 'success') {

                
                $scope.getChatUsers();
                socket.emit('contact_deleted', {to_user:$scope.chatUsers[$scope.selected].user.id,contact_id:user.contact_id });

            }


        }
        ,function(response){});
		
	}
	
	
	
	
	

$scope.profile_unblock= function(to_user)
{
	
	socket.emit('user_unblocked', {to_user:to_user,unblocked_user_id:$scope.authUser});
}

$scope.profile_block= function(to_user)
{
	
	socket.emit('user_blocked', {to_user:to_user,blocked_user_id:$scope.authUser});
} 
	
	
	//delete the individual messege
	$scope.deleteThisMessege = function(msgid)
	{
		//push object again retriving ajax call
        $http.post(delete_msg_chat, {message_id:msgid})
        .then(function(response){

            if (response.data.status == 'success') {

                $('#msg-'+msgid).fadeOut();

            }


        }
        ,function(response){});
		
	}
	
	
	// 	load more conversation
	$scope.loadMoreConversation=function(user)
	{
		
		//fetch the last msg id
		var last_msg_id=user.user.messages[0].id;
		
		
		
		//fetch the selected index from users list
		
		var index = $('.users_list_cnt').find('.active').data('index');
		
		//send the last msg id
		$scope.loadMoreChatData(user,index,last_msg_id);
		
		
		//hide the button
		$('#load_more').hide();
		
		
	}


    /* socket operations */

    $scope.socket_id = 0;
    socket.on('connected', function(data){
        $scope.socket_id = data.socket_id;
        $scope.mapUserWithSocket();
    });


    //if not in chatUsers array take from ajax call

    socket.on("new_message_received", function(data){


		//$scope.getChatUsers();
		
		    var text = parseInt($(".notification-message").html());
			
			
	
			if(isNaN(text)){
				text= 0;
			}
			
			$(".notification-message").html(text+1); 
			
			
			console.log(JSON.stringify(data));
		
        toastr.success(recieved_msg);	
		
        var user = $scope.findUserByID(data.from_user);
        
        
        
        //toastr.success('You got a new message from '+user.user.name);
        
        
        	                
        if(data.to_user==$scope.authUser)
        {
            data.recipient='other';
        }
        else
        {
            data.recipient='self';
        }
	          
	          
	          
	           
          

        
        user.user.messages.push(data);
        
        
        

        if (user.user.last_msg == "" && user.user.id == $scope.chatUsers[$scope.selected].user.id) {
            $('#third_img_div').show();
            $('#first_img_div').hide();
            $("#no-users-div").hide();
            $("#fourth_div").hide();
        }


        if (data.type == 2) {
            user.user.last_msg = data.meta;
            user.user.last_msg_type = 2;
        } else if (data.type == 0) {
            user.user.last_msg = data.text;
            user.user.last_msg_type = 0;
        }

		
        user.user.total_messages_count += 1;
        
        
        var curr_user_id= "user-"+user.user.id;
        
        
        //if user selected and user msg recieved are differed=nt then increment otherwise no
        if(curr_user_id != $(".all_users").parent().find('.active').data('id'))
        {
	        user.user.total_unread_messages_count += 1;
        
			$scope.overall_unread_messages_count += 1;
			
		}	
        var audio = new Audio("@plugin_asset('WebsocketChatPlugin/audio/ping.mp3')");
        audio.play();


        //$("#chat-container").scrollTop($("#chat-container").find("> .img_main_div_padding").height());

    });


    socket.on("new_message_sent", function(data){

        var user = $scope.findUserByID(data.to_user);
        
        
        
	        
	        if(data.to_user==$scope.authUser)
	        {
	            data.recipient='other';
	        }
	        else
	        {
	            data.recipient='self';
	        }
	        user.user.messages.push(data);
	
	
	        if (user.user.last_msg == "" && user.user.id == $scope.chatUsers[$scope.selected].user.id) {
	            $('#third_img_div').show();
	            $('#first_img_div').hide();
	            $("#no-users-div").hide();
	            $("#fourth_div").hide();
	        }
	
	
	        if (data.type == 2) {
	            user.user.last_msg = data.meta;
	            user.user.last_msg_type = 2;
	        } else if (data.type == 0) {
	            user.user.last_msg = data.text;
	            user.user.last_msg_type = 0;
	        }

	   
       	                
        


        // user.user.total_messages_count += 1;
        // user.user.total_unread_messages_count += 1;
        
        //$("#chat-container").scrollTop($("#chat-container").find("> .img_main_div_padding").height());
    });

    /*end of socket operations */




    /* user online offline status */
    socket.on('user_online', function(data){

        var user = $scope.findUserByID(data.user_id);
        if (user) {
            user.online = 1;
        }

    });

    socket.on('user_offline', function(data){

        var user = $scope.findUserByID(data.user_id);
        if (user) {
            user.online = 0;
        }

    });
    /* end user online offline status */




	//event listening to contact delete
	socket.on('contact_deleted', function(data){
		
		

       $scope.getChatUsers();

    });
    
    
    
    
    
    
    
$scope.isTypingUser=0;


    /* typing stop and start notification */

    socket.on('typing', function(data){
        var user = $scope.findUserByID(data.from_user);
        if (user) {
            user.user.isTyping = true;
            console.log(user.user.name + " is typing..");
           
	    	
	    	//$scope.isTypingUser=1;
	    	
	    	$('#typing_'+user.user.id).show();

        }
        
    });

    socket.on('typing_stop', function(data){
        var user = $scope.findUserByID(data.from_user);
        if (user) {
            user.user.isTyping = false;
            console.log(user.user.name + " is stopped typing..");
            
            
            $('#typing_'+user.user.id).hide();
           
        }
    });


    $scope.typing = function(){


        if (!$scope.type) {
            $scope.type = true;
            socket.emit('typing', {from_user: auth_user_id_chat, to_user:$scope.chatUsers[$scope.selected].user.id });
        }
        
        lastTypingTime = (new Date()).getTime();

        setTimeout(function () {

            var typingTimer = (new Date()).getTime();
            var timeDiff    = typingTimer - lastTypingTime;
            
            if (timeDiff >= 2000 && $scope.type) {
                socket.emit('typing_stop', {from_user: auth_user_id_chat, to_user:$scope.chatUsers[$scope.selected].user.id });
                $scope.type = false;
            }

        }, 2000);
    
    }
    /* end typing stop and start notification */







    /* new user contacted */

    socket.on("new_user_contacted", function(data){

        
        var user = $scope.findUserByID(data.user_id);

        /* if user is already in list then remove*/
        if (user) {
	        if(!$scope.total_contacts_count)
	        {
		        $scope.chatUsers=[];
	        }
	        else
	        {
            	var index = $scope.chatUsers.indexOf(user);
            	$scope.chatUsers.splice(index, 1);
            }
        } 

        //push object again retriving ajax call
        $http.post(chat_user, {user_id:data.user_id})
        .then(function(response){

            if (response.data.status == 'success') {

                $scope.chatUsers.push(response.data.chat_user);
                
                $scope.chatUsers = _.uniq($scope.chatUsers, function(a) { return a.contact_id; });
                
                
                
                
			 }


        }
        ,function(response){});

    });


    /* end new user contacted */












    $scope.findUserByID = function(user_id){

        if ($scope.chatUsers.length < 1)
            return null;

        return $scope.chatUsers.find(function(item){
            if(item.user.id == user_id) return true;
        });
    }



    $scope.mapUserWithSocket = function() {
        $http.post(map_user_socket, 
        {socket_id:$scope.socket_id}).then(function(response){
            
            if (response.data.status == 'success') {
                socket.emit('user_socket_mapped', {user_id:auth_user_id_chat})
                console.log('you are connected');
            }

        }, function(){});
    }
    
          

    $scope.chatUsers = [];
    $scope.chat_users_count = 0;
    $scope.total_contacts_count = 0;
    $scope.last_contact_id = 0;
    $scope.authUser = auth_user_id_chat;
    $scope.overall_unread_messages_count = 0;

    /* this method gets chat users */
    $scope.getChatUsers = function(){

        $http.get(
            
           chat_users, 
            {last_contact_id:$scope.last_contact_id}
        
        ).then(function(res){

            if(res.data.status == "success") {

                $scope.total_contacts_count = res.data.total_contacts_count;
                $scope.chatUsers = res.data.chat_users;
                
               
                
                
                $scope.chat_users_count = $scope.chatUsers.length;

                if ($scope.total_contacts_count == 0 || $scope.chat_users_count == 0) {
                    $scope.last_contact_id = 0;
                } else {
                    $scope.last_contact_id = $scope.chatUsers[$scope.chat_users_count-1].contact_id;
                }
				
                if ($scope.total_contacts_count != 0 && $scope.load_chat_data_first == true) {
                    $scope.loadChatDataFirst();
                    $('#fourth_div').hide();
                    
                }
                else
                {
	                
	                $('#fourth_div').show();
	                $('#third_img_div').hide();
		            $('#first_img_div').hide();
		            $("#no-users-div").hide();
                }

                $scope.overall_unread_messages_count = res.data.overall_unread_messages_count;

            }


        }, function(res){});

    }


    /* this method retrives message of a particular user */
    $scope.getMessages = function(user){

        $http.post(
            msgs_chat,
            {user_id:user.user.id, last_message_id:$scope.last_message_id}
        ).then(function(res){

            if (res.data.status == 'success') {
                var temp = [];
                $.merge(temp, res.data.messages);
                
                _.each(temp,function(item,index){
	                
	                if(item.to_user==$scope.authUser)
	                {
		                temp[index].recipient='other';
	                }
	                else
	                {
		                temp[index].recipient='self';
	                }
	                
                })
                
                user.user.messages = $.merge(temp, user.user.messages);

            }

        }, function(res){});

    }



    /* this method is used to send message */
    $scope.submitMessage = function(){
	    
	    console.log('$scope.messageInput',$scope.messageInput);

        var to_send_user = $scope.chatUsers[$scope.selected];

        var msg_obj = {
            from_user : auth_user_id_chat,
            to_user : to_send_user.user.id,
            contact_id : to_send_user.contact_id,
            message_type : 0,
            message_text : $scope.messageInput
        };
        
        
        if(to_send_user.user.can_init_chat)
        {
				socket.emit('new_message', msg_obj);
		}
		else{
			
			
	        
	        if(to_send_user.user.init_chat_error_type === 'CHAT_INIT_HOURS_EXPIRED')
	        {
		         
		        $('#myModalExceedsChatHourExpired').modal('show');
	        }
	        else if(to_send_user.user.init_chat_error_type === 'CHAT_LIMIT_OF_DAY')
	        {
		        $('#myModalExceedsChatLimit').modal('show');
	        }
      
        
		}		
        
        $scope.messageInput='';
        
       
    }
    
    
    
    /* submit messege with media
	    /* this method is used to send message */
    $scope.submitMessageWithMedia = function(image_name){

        var to_send_user = $scope.chatUsers[$scope.selected];

        var msg_obj = {
            from_user : auth_user_id_chat,
            to_user : to_send_user.user.id,
            contact_id : to_send_user.contact_id,
            message_type : 2,
            message_text : image_name
        };

		 if(to_send_user.user.can_init_chat)
        {
				socket.emit('new_message', msg_obj);
		}
		else{
			
			
	        
	        if(to_send_user.user.init_chat_error_type === 'CHAT_INIT_HOURS_EXPIRED')
	        {
		       		        
		        $('#myModalExceedsChatHourExpired').modal('show');
	        }
	        else if(to_send_user.user.init_chat_error_type === 'CHAT_LIMIT_OF_DAY')
	        {
		        $('#myModalExceedsChatLimit').modal('show');
	        }
      
        
		}	
        
       
        
       
    }


    $scope.submitMessageFirstTime = function(){

        var to_send_user = $scope.chatUsers[$scope.selected];

        var msg_obj = {
            from_user : auth_user_id_chat,
            to_user : to_send_user.user.id,
            contact_id : to_send_user.contact_id,
            message_type : 0,
            message_text : $scope.messageInput
        };

		if(to_send_user.user.can_init_chat)
        {
				socket.emit('new_message', msg_obj);
				
				   $scope.loadChatData(to_send_user, $scope.selected);
		}
		else{
			
			
	        
	        if(to_send_user.user.init_chat_error_type === 'CHAT_INIT_HOURS_EXPIRED')
	        {
		        		        

		        $('#myModalExceedsChatHourExpired').modal('show');
	        }
	        else if(to_send_user.user.init_chat_error_type === 'CHAT_LIMIT_OF_DAY')
	        {
		        $('#myModalExceedsChatLimit').modal('show');
	        }
      
        
		}	

     
        
        $scope.messageInput='';

    }
    
    
    
    




    $scope.loadOnline =function() {
	    
	    var counter=0;
        $scope.onlineSelectedUsers = _.filter($scope.chatUsers, function (item,index) { 
	        
	        
	        if(item.online && counter==0){
	        	
	        	
	        	$scope.chatUsers[index].user.already_clicked= 0;
	        	$scope.loadChatData($scope.chatUsers[index],index);
	        	
	        	counter++;
	        	
	        }
	        
	        
	        
	        return  item.online; 
	        
	        
	        });
	        


				var onlineUsers_count = $scope.onlineSelectedUsers.length;
				
				
				if(onlineUsers_count == 0)
				{
					$('#third_img_div').hide();
		            $('#first_img_div').hide();
		            $("#no-users-div").show();
					

				}
				
				
    }


	 $scope.loadOnline_mobile =function() {
        $scope.onlineSelectedUsers = _.filter($scope.chatUsers, function (item) { return  item.online; });
        
        if($scope.total_contacts_count)
         {
        if ($scope.onlineSelectedUsers.length == 0) {
            $('#third_img_div').hide();
            $('#first_img_div').hide();
            $("#no-users-div").show();
            return false;
        }
        
        $scope.loadChatData($scope.onlineSelectedUsers[0],0);
        }
    }


    $scope.loadMatched = function() {
     
        
	             var counter=0;
				  	$scope.matchedUsers = _.filter($scope.chatUsers, function (item,index) { 
			        
			        
			        if(item.matched && counter==0){
			        	
			        	
			        	$scope.chatUsers[index].user.already_clicked= 0;
			        	$scope.loadChatData($scope.chatUsers[index],index);
			        	
			        	counter++;
			        	
			        }
			        
			        
			        
			        return  item.matched; 
		        
		        
		        });
	        


				var matchedUsers_count = $scope.matchedUsers.length;
				
				
				if(matchedUsers_count == 0)
				{
					$('#third_img_div').hide();
		            $('#first_img_div').hide();
		            $("#no-users-div").show();
					

				}
        
    }


    $scope.loadAll =function() {
         if($scope.total_contacts_count)
         {
        if ($scope.chatUsers.length == 0) {
            $('#third_img_div').hide();
            $('#first_img_div').hide();
            $("#no-users-div").show();
            
            return false;
        }
        
        
       
        	$scope.loadChatData($scope.chatUsers[0],0);
        }
    }
    
    $scope.loadAll_mobile =function() {
        if ($scope.chatUsers.length == 0) {
	        
	        
            $('#third_img_div').hide();
            $('#first_img_div').hide();
            $("#no-users-div").show();
            return false;
        }
        
        if($scope.total_contacts_count)
        	$scope.loadChatData($scope.chatUsers[0],0);
    }
    
    

    
    $scope.selected = 0;
    $scope.loadChatData = function(user,index) {  
	    
		   //$scope.selected = index; 
	    
	   	 //index = $scope.chatUsers.indexOf(user);
	   	 
	   	 
	   	 $scope.current_to_user_id=user.user.id;
	   
	     if(!$scope.total_contacts_count)
         {
	         $http.post(add_to_contacts, {user_id:user.user.id})
	        .then(function(response){
	
	            if (response.data.status == 'success') {
	
	              $scope.getChatUsers();  
	
	            }
	
	
	        }
	        ,function(response){});
	         
	     }
	    //hide the button
		$('#load_more').show();

        $scope.userChatDetail = user;
        
        $scope.selected = index;
        

        if (user.user.last_msg) {
		 
            $scope.last_message_id = 0;
            if (user.user.messages.length > 0) {
                $scope.last_message_id = user.user.messages[0].id;
            }

            if(!user.already_clicked) {
	            $('.loaderChatData').fadeIn();
                $scope.getMessages(user);    
                user.already_clicked = 1;
                
                
                //clear unread mesg count
                user.user.total_unread_messages_count= 0;
                
                
                //call function to make unread status for this user once i click on unread mesges
                //pending
                 $http.post(
		            mark_read ,
		            {user_id:user.user.id}
		         ).then(function(res){
		
		            		                
		                console.log(res);
		                
		                 $('.loaderChatData').fadeOut();
		
		         });
		
		        
                
                
            }
            
            
            if(user.already_clicked)
            {  
	            //clear unread mesg count
                user.user.total_unread_messages_count= 0;
                
                //$scope.getMessages(user);
	            
            }
            

            // $scope.last_msg = user.user.last_msg;
            // $scope.last_msg_type = user.user.last_msg_type;
			
            	$('#third_img_div').show();
				$('#first_img_div').hide();
				$("#no-users-div").hide();
				$("#fourth_div").hide();
				
				if($scope.mobile)
				{
					$('.mobile_users').hide();
				}
			 
        }
        else
        {
			
	            $('#third_img_div').hide();
	            $('#first_img_div').show();
	            $("#no-users-div").hide();
	            $("#fourth_div").hide();
	            
	            if($scope.mobile)
				{
					$('.mobile_users').hide();
				}

				
        }
    }
    
    
    
    
    $scope.loadMoreChatData =function(user,index,last_msg_id)
    {
	    $scope.userChatDetail = user;
        $scope.selected = index; 

        if (user.user.last_msg) {

            $scope.last_message_id = 0;
            if (user.user.messages.length > 0) {
                $scope.last_message_id = last_msg_id;
            }

           
                $scope.getMessages(user);    
           
           
          

            // $scope.last_msg = user.user.last_msg;
            // $scope.last_msg_type = user.user.last_msg_type;

            $('#third_img_div').show();
            $('#first_img_div').hide();
            $("#no-users-div").hide();
            
        }
        else
        {

            $('#third_img_div').hide();
            $('#first_img_div').show();
            $("#no-users-div").hide();

        }

	    
    }

    $scope.loadChatDataFirst = function() {
        
        $scope.loadChatData($scope.chatUsers[0], 0);
    }



}); 



/* this is socket factory */
App.factory('socket', function ($rootScope) {
  
  var socket = io(websocket_domain+":"+server_port);

  return {
    on: function (eventName, callback) {
      socket.on(eventName, function () {  
        var args = arguments;
        $rootScope.$apply(function () {
          callback.apply(socket, args);
        });
      });
    },
    emit: function (eventName, data, callback) {
      socket.emit(eventName, data, function () {
        var args = arguments;
        $rootScope.$apply(function () {
          if (callback) {
            callback.apply(socket, args);
          }
        });
      })
    }
  };
});

/* end of socket factory */
