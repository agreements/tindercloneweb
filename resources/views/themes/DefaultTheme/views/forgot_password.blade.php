<html>
	<head>
		<title>Login</title>

		<link rel="stylesheet" href="{{{asset('css/bootstrap3.3.2.min.css')}}}">
  		<script src="{{{asset('js/jquery.min1.11.3.js')}}}"></script>
  		<script src="{{{asset('js/bootstrap3.3.5.min.js')}}}"></script>
		</style>
	</head>
	<body>
		<!-- jumbotron header -->	
		<div class="container-fluid text-center">
  			<div class="jumbotron">
    			<h1>Panel</h1>
			  	<p>LightOxide Panel</p> 
			</div>
		</div>
		<!-- End of jumbotron header -->	
		<div class = "container">
			<div class="row" style="margin-bottom:20px;">
				<div class = "col-sm-4">
				</div>
  				<div class="col-sm-4">
  					<form role="form" method = "POST" action = "{{{ URL::to('forgotPassword/submit') }}}">
  						 {!! csrf_field() !!}
			  			<div class="form-group">
			   				<label for="email"> Registered Email address:</label>
			    			<input type="email" class="form-control" id="email" name = "username">
			  			</div>

			  			<button type="submit" class="btn btn-default" style = "float : right;">Get New password</button>
					</form>
  				</div>

  			</div>

  		</div>
		
	</body>
</html>
