{{--{{{url('facebook/photos')}}}--}}
<a href="#" id = "facebook-photo-import-button"><i class="fa fa-facebook icon_fb importfromfb"></i></a>
<script>

var fb_access_token = '';
var fb_user_id = '';
var fb_user = '';
$.ajaxSetup({ cache: true });
	$.getScript('https://connect.facebook.net/en_US/all.js', function(){
		FB.init({
		appId: '{{{$fb_app_id}}}',
		version: 'v2.5' // or v2.0, v2.1, v2.2, v2.3
	});     
});

$("#facebook-photo-import-button").on('click', function(){

	fb_import_photos = [];
	var img_container = $(".body-down-div").find('.img-container');
	img_container.each(function(index){
		$(this).remove();
	});

	FacebookLogin();

	//$("#photoModal").addClass('invisible');
	$("#facebook-photo-upload-modal").modal('show');
});

function FacebookLogin() {
	FB.login(function(response) {
		if (response.status = "connected") {	
			if(response.authResponse) {
				fb_access_token = response.authResponse.accessToken;
				fb_user_id = response.authResponse.userID;
				console.log(response);
				getFacebookUserByUserId();
			}
		} else {
	 		console.log('Authorization failed.');
		}
	},{scope:'email, user_photos', return_scopes: true});
}


function getFacebookUserByUserId() {
	
	FB.api("/"+fb_user_id, function (response){
        if(response && !response.error) {
        	console.log(response);
        	getFbPhotos();
        }
	});
}


function getFbPhotos() {
	FB.api("/me/albums", function (response) {console.log(response);
		if(response && !response.error) {
			var albums = response.data;

			if(albums.length == 0) {
      			toastr.error('{{{trans('app.no_fb_photos_found')}}}');
	      		$(".fb-photo-loader").hide();
		  		$('#fb-photos-counter').text(fb_photo_counter);
		  		return false;
     		}


			for(var i =0; i < albums.length;i++){
				if(albums[i].name == 'Profile Pictures') {
					getFbProfilePhotos(albums[i].id);
				}
			}
		} 
	});
}


var fb_photo_counter = 0;
next_link = '';
function getFbProfilePhotos(album_id) {
	$(".fb-photo-loader").show();
	FB.api("/"+album_id+"/photos",
		{fields:"id, source"},
	    function (response) {
	      if (response && !response.error) {
	        var photos = response.data;
	        
	        if(photos.length == 0) {
      			toastr.error('{{{trans('app.no_fb_photos_found')}}}');
	      		$(".fb-photo-loader").hide();
		  		$('#fb-photos-counter').text(fb_photo_counter);
		  		return false;
     		}

	        for(var i = 0; i<photos.length; i++) {
	        	fb_photo_counter++;
	        	var html = '<div class="img-container" data-photo-id="'+photos[i].id+'" data-checked="false"><img src="'+photos[i].source+'"><i class="fa fa-check-circle fb-photo-check"></i><div class="select-curtain"></div></div>';
	        	$("#facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div")
	        	.prepend(html);
	        }

	        if(!response.paging.next) {
					next_link = '';
					$(".fb-photo-loader").hide();
					$('#fb-photos-counter').text(fb_photo_counter);
					return false;
			}
			next_link  = response.paging.next;
	        getFbProfilePhotosNext();
	      } 
	    }
	);
}

function getFbProfilePhotosNext() {
	if(next_link != '') {
		$.get(next_link, function(response) {

			if (response && !response.error) {
	        	var photos = response.data;

		        for(var i = 0; i < photos.length; i++) {
		        	fb_photo_counter++;
		        	var html = '<div class="img-container" data-photo-id="'+photos[i].id+'" data-checked="false"><img src="'+photos[i].source+'"><i class="fa fa-check-circle fb-photo-check"></i><div class="select-curtain"></div></div>';
		        	$("#facebook-photo-upload-modal > div > .modal-content > .modal-body > .body-down-div")
		        	.prepend(html);
		        }
		        if(!response.paging.next) {
					next_link = '';
					$(".fb-photo-loader").hide();
					$('#fb-photos-counter').text(fb_photo_counter);
					return false;
				}
				next_link  = response.paging.next;
		        getFbProfilePhotosNext();
	      	}

		});
	}
}


</script>