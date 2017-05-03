
<html>
	<head>
		<title>Login</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

		
  		<link href='https://fonts.googleapis.com/css?family=Roboto:400,300,500,700,100' rel='stylesheet' type='text/css'>
		<style>
		  @import url(https://fonts.googleapis.com/css?family=Roboto:300);

.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 480px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #43A047;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message a {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #76b852; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #76b852, #8DC26F);
  background: -moz-linear-gradient(right, #76b852, #8DC26F);
  background: -o-linear-gradient(right, #76b852, #8DC26F);
  background: linear-gradient(to left, #76b852, #8DC26F);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;   
  
  
  		width:100%; height:100%; 
      background:#fff url("{{ asset('admin_assets/images/adminlogin.png') }}") center center no-repeat;
      background-attachment: fixed;
      background-size:cover;   
}

.login-admin-jumbotron
{
	text-align: center;
color: white;
}
		</style>
	</head>
<!--
	<body>
			
		<div class="container-fluid text-center">
		  <div class="row">
  			<div class="jumbotron login-admin-jumbotron" style="color: #FFF;background: url({{ asset('admin_assets/images/adminlogin.png') }});background-size: cover;background-repeat: no-repeat;background-position: 0px;height: 400px;">
    			<h1 >Master Control Panel</h1>
			  	<p class="liteoxide-panel-text">{{{$website_title}}}</p> 
			</div>
		   </div>	
		</div>
	
		<div class = "container">
			<div class="row" style="margin-bottom:20px;">
				<div class = "col-sm-4">
				</div>
  				<div class="col-sm-4">
  					<form role="form" method = "POST" action = "{{{ URL::to('admin/login') }}}">
  						 {!! csrf_field() !!}
			  			<div class="form-group">
			   				<label class="admin-email-pass" for="email">Email address:</label>
			    			<input type="text" placeholder="Enter email"class="form-control admin-formcontrol-login" id="email" name = "username">
			  			</div>
			  			
			  			<div class="form-group">
			    			<label class="admin-email-pass"  for="pwd">Password:</label>
			    			<input type="password" placeholder="Enter password" class="form-control admin-formcontrol-login" id="pwd" name = "password">
			  			</div>
			  			
			  			

			  			<button type="submit" class="btn btn-default btn-admin-login" style = "float : right;">Login</button>
					</form>
  				</div>

  			</div>

  			<div class="row">
  					<div class="col-sm-4"></div>
  					<div class="col-sm-4">
  						@if($errors->any())
  							<br><div class="alert alert-danger">{{{$errors->first()}}}</div>
  						@endif
  					</div>
  					<div class="col-sm-4"></div>
  			</div>

  		</div>
		
	</body>
-->
	
 <body >

	    <div class="login-page">
		    
		    <div class="row">
  			<div class="login-admin-jumbotron">
    			<h1 >Master Control Panel</h1>
			  	<p class="liteoxide-panel-text">{{{$website_title}}}</p> 
			</div>
		   </div>	
		</div>
		  <div class="form">
		 
		    <form class="login-form" method = "POST" action = "{{{ URL::to('admin/login') }}}">
			     {!! csrf_field() !!}
		      <input type="text" placeholder="username" id="email" name = "username"/>
		      <input type="password" placeholder="password" id="pwd" name = "password"/>
		      <button type="submit">login</button>
		     
		    </form>
		    
  <div class="row">
  					<div class="col-sm-2"></div>
  					<div class="col-sm-8">
  						@if($errors->any())
  							<br><div class="alert alert-danger">{{{$errors->first()}}}</div>
  						@endif
  					</div>
  					<div class="col-sm-2"></div>
  			</div>
		  </div>
		  
		
		</div>
	   		 <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
	
	        <script>
		        $('.message a').click(function(){
				   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
				});
	        </script>

  </body>

	
</html>