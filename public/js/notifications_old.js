var Notification = {};

Notification.count = 0;

Notification.get_notifications = function(){
	var data = {};

	data._token = App.csrf_token;

	$.get(App.urls.get_notifications, data,function(data){

		
	Notification.count = data.length;
		
		console.log('get notification',Notification.count);
		
				
		_.each(data,function(item){
			
				if(item.type == "payment" && item.status == "unseen"){
					
					toastr.success('Your mobile payment is successfully done.');
					
					
					
					$('#processing-modal').modal('hide');
					
					$('#processed_modal').modal();
					
					
				}

			
		})		
				

		//Notification.poll();

	});


};


Notification.get_notifications();


/*
Notification.poll =  function(){

	var data = {};

	data._token = App.csrf_token;

console.log('poll notification',Notification.count);

	$.get(App.urls.poll_notifications+Notification.count,function(data){
	console.log(data);
	console.log(data.status);
	if(data.status && data.status != 0){ 
		console.log("NEw Notification!!");
		
		
			

		Notification.count =  Notification.count + 1;
		console.log(Notification.count);
		Notification.render(data);


			Notification.poll();

	}

	else{
		Notification.get_notifications();
		
	}
	
	});
	


};
*/

/*

Notification.render = function(n){

	
	console.log(n);
	var text = parseInt($(".notification-"+n.type).html());
			if(n.status == "unseen"){ 
			
			if(n.type == "visitor"){ 
					toastr.success(n.name+' Visited Your Profile');
				}
				
				
				if(n.type == "payment"){
					
					toastr.success('Your mobile payment is successfully done.');
				}
				
			}
			
	
	if(isNaN(text)){
		text= 0;
	}
	
	$(".notification-"+n.type).html(text+1);
	



}
*/
