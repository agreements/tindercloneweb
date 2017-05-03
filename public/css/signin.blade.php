<!doctype html>
<html lang="en">
<head>
<title>Minglematic Sign Up</title>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/signin.css">
<link rel="stylesheet" href="WOW-1.1.2/css/libs/animate.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-8 col_header">

        <div class="col-md-3 col_header_image">
           <img class="hearts" src="/images/landingPage/hearts.png">
         </div>
         <div class="col-md-3 col_header_one">
              <div class="row">
              <div class="col-md-3 language_select">
                 
                  <div class="btn-group">
                     <button type="button" class="form-control btn btn-default dropdown-toggle" data-toggle="dropdown">
                        English <span class="caret"></span>
                     </button>
                   <ul class="dropdown-menu" role="menu">
                     <li><a href="#">Telugu</a></li>
                     <li><a href="#">Hindi</a></li>
                     <li><a href="#">Kannada</a></li>
                  </ul>
                  </div>
                 </div>

               <div class="col-md-3 mobile_web">
                 <button class=" mobile_web_btn disabled btn" type=
                            "button">Mobile-Web</button>
               </div>

             </div>	  
             </div>
							
		 		
          <div class="col-md-4 col_alreadymember">
              <div class="row">

               <div class="col-md-2 col_alreadymember1">
                <button type="button" class="btn btn-default btn_alreadymember1">Not a member</button>
               </div>
               <div class="col-md-2 alreadymember_logo">
                <a href="{{{url('/register')}}}"><button type="button" class="btn btn-success btn-circle btn-lg btn_alreadymember2"><p id="sign-in">Sign Up</p></button></a>
               </div>

              </div>
          </div>
</div>

     
     <div class="col-md-8 col_centerbody">
       <div class="row">
                   <div class="col-sm-6 col_bannertext">
						<div class="banner-text">
							<h1>Welcome to <span class="header_main">Let's Mingle</span></h1>
							<h2>Enjoy your new meeting and date.
								With members over the world,</h2>
							<p>you can enjoy our various services, 
								such as chatting, new encounters, 
								sharing interests, encounter &amp; matching games,
								global popularity contest and searching 
								photo certified members, etc. And 
								at the same time, you can win the prizes
								awarded by let's mingle. </p>
						</div>
					</div>
     


     <div class="col-sm-5 col-sm-offset-1 col_signup">
						<div class="sign-up">
					@if($errors->any())
  							{{{$errors->first()}}}
  						@endif
              <h1>Already a member? Sign In</h1>

  					<form role="form" method = "POST" action = "{{{ URL::to('login') }}}" id="edit-profile" class="form-horizontal">
  						 {!! csrf_field() !!} 
              <div class="row">
                <div class="col-xs-4">
                  <label>Email</label>
                </div>
                <div class="col-xs-8">
                  <input type="email" class="form-control" id="email" name="username">
                </div>
              </div>
              <div class="row">
                <div class="col-xs-4">
                  <label>Password</label>
                </div>
                <div class="col-xs-8">
                  <input type="password" class="form-control" id="password" name="password">
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <button type="submit" class="sign-up-btn ">Sign in</button>
                </div>
              </div>
            </form> 
            </div>




</div>


   
      <div class="col-md-8 col_centerbottom wow bounceInUp">
       <div class="row">
         <button type="button" class="btn btn-default download_button">
         <img class="apple_img" src="/images/landingPage/Apple.png"/>
         <div class="col-sm-2 col_apple">
         <p class="apple_para">Download from </p>
         <p class="apple_para">App Store</p>
         </div>
         </button>
         <button type="button" class="btn btn-default download_button">
         	<img class="apple_img" src="/images/landingPage/google_play.png"/>
         <div class="col-sm-2 col_apple">
         <p class="apple_para">Download from </p>
         <p class="apple_para">Google Play</p>
         </div>
        

         </button>
         <button type="button" class="btn btn-default download_button">
         	<img class="apple_img" src="/images/landingPage/windows_store.png"/>
         <div class="col-sm-2 col_apple">
         <p class="apple_para">Download from </p>
         <p class="apple_para">Windows Store</p>
         </div>
         </button>
         <button type="button" class="btn btn-default download_button">
         	<img class="apple_img" src="/images/landingPage/blackberry.png"/>
         <div class="col-sm-2 col_apple">
         <p class="apple_para">Download from </p>
         <p class="apple_para">Blackberry Store</p>
         </div>
         </button>
         
       </div>
      </div>
   </div>


<div class="col-md-8 col_third">
  <div class="row">
    <div class="col-md-4 col_third_first">
      <img src="/images/landingPage/Selection_001.png"/>
    </div>
    <div class="col-md-4 col_third_second wow slideInRight">
      <div class="match-encounter">
		    <h1>MATCH&amp; ENCOUNTER GAME</h1>
			<p>Find your exactly matched
             partner through our Match &amp; 
             Encounter game, 
             and get started new meetings.</p>
             <button type="button" class="btn btn-success">Goto Encounter</button>
			
		  </div>
    </div>
  </div>
</div>
	

<div class="col-md-8 col_fourth">
 <div class="row">
  <div class="col-md-6 col_fourth_one">
    <p class="connect_para">CONNECT</p>
    <p class="connect_para1">ANYWHERE ANYTIME</p>
    <p class="connect_para2">Let's Mingle can be connected with all kinds of 
       equipments as computer, tablet, mobile and 
       cellular phones. You can connect
       anytime and anywhere to LEZUM and
       meet people over the world.</p>
  </div>
  <div class="col-md-4 col_fourth_one1 wow slideInLeft">
    
  </div>
 </div>
</div>


<div class="col-md-8 col_fifth">
 <div class="row">
  <div class="col-sm-4 col_fifth_one">
  <img src="/images/landingPage/people-map.png"/>
  </div>
  <div class="col-sm-4 col_fifth_two">
  
  <h1>Members Nearby</h1>
  <p>LEZUM never open your exact location for 
				your privacy.<br>
				But you will be able to search members nearby
				your location, in your city and in your country.
				</p>
  <p><span>See people who is nearby my area PAJU</span></p>
  <button type="button" class="btn btn-success">Near By Members</button>
  </div>
 </div>
</div>

<div class="col-md-8 col_6">
        <div class="row">
           <div class="col-sm-4 col_sixth_one">
            <h1>Sharing Interests</h1>
                 <p>Sharing your interests can be exposed more and
                   given more opportunity to chat.
				</p>
                 <p><span>Meet people who has same interests. </span></p>
                 <button type="button" class="btn btn-success">See Same interest numbers</button>
           </div>
            <div class="col-sm-4 col_sixth_two">
              <img class="sharing_interests" src="/images/landingPage/sharing_interests.jpg"/>
            </div>
             </div>
               </div>

   

    <div class="col-md-8 col_bottom">
     <p class="copyright">Copyright 2015</p>
     <p class="copyright">This is the sole property of Let's Mingle</p>
    </div> 
















</div>
</div>
<script src="WOW-1.1.2/dist/wow.min.js"></script>
              <script>
              new WOW().init();
              </script>
</body>
</html>
