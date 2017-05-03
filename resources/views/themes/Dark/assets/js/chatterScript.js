function Chatter(){
	this.getMessage = function(callback, lastTime){
		var t = this;
		var latest = null;
		
		$.ajax({
			'url': 'chatterEngine.php',
			'type': 'post',
			'dataType': 'json',
			'data': {
				'mode': 'get',
				'lastTime': lastTime
			},
			'timeout': 30000,
			'cache': false,
			'success': function(result){
				if(result.result){
					callback(result.message);
					latest = result.latest;
				}	
			},
			'error': function(e){
				console.log(e);
			},
			'complete': function(){
				t.getMessage(callback, latest);
			}
		});
	};

	this.getUsers = function(callback) {
		// body...
		$.ajax({
			'url': 'chatterEngine.php',
			'type': 'post',
			'dataType': 'json',
			'data': {
				'mode': 'getuser'
			},
			'success': function(result){
				callback(result);
			},
			'error': function(e){
				console.log(e);
			}
		});
	}
	
	this.postMessage = function(user, text, callback){
		$.ajax({
			'url': 'chatterEngine.php',
			'type': 'post',
			'dataType': 'json',
			'data': {
				'mode': 'post',
				'user': user,
				'text': text
			},
			'success': function(result){
				callback(result);
			},
			'error': function(e){
				console.log(e);
			}
		});
	};
};

var c = new Chatter();

$(document).ready(function(){
	$('#formPostChat').submit(function(e){
		e.preventDefault();
		
		var user = $('#postUsername').text();
		var text = $('#postText');
		var err = $('#postError');
		
		c.postMessage(user, text.val().trim(), function(result){
			if(result){
				text.val('');
				text.focus();
			}
			err.html(result.output);
			$('#chatAudio')[0].play();
		});
	
		return false;
	});
	
	c.getMessage(function(message){
		var chat = $('.discussion').empty();
		
		
		
		for(var i = 0; i < message.length; i++){
			

				if(message[i].user==$('#postUsername').text())
				{      
				
				    $("<li class='self'>").html('<div class="avatar"><img src="images/img2.png" class="user_chat_thumb"/> </div><div class="messages1">'+message[i].text+'<time datetime="2009-11-13T20:14">37 mins</time> </div>').appendTo(".discussion");
				}
				else if($('#openchatuser').text()!=message[i].user)
				{

					//go throught the list of users and increment the number
						c.getUsers(function(result){

							console.log(result.responseText);
						});	

				}
				else
				{
					$("<li class='other'>").html('<div class="avatar"><img src="images/img2.png" class="user_chat_thumb"/> </div><div class="messages1">'+message[i].text+'<time datetime="2009-11-13T20:14">37 mins</time> </div>').appendTo(".discussion");
				}
			
		}
		
		
		$(".module").animate({"scrollTop": $('.module')[0].scrollHeight}, "slow");
		
		
	});
});