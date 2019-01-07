<html>
	<head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="assets/css/toastr.css" rel="stylesheet"/>
		<link href="assets/css/core.css" rel="stylesheet"/>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
		<script src="assets/js/toastr.min.js"></script><!-- flash notifications -->	
		<script src="assets/js/bootbox.min.js"></script><!-- confirmation boxes -->
		<script src="vendor/byjg/jwt-wrapper/js/store.js"></script>
		<script src="assets/js/login.js"></script>
		
	</head>

<body>	

<div class="container">

	<!-- Sign in -->	
	<div id="loginModal" class="modal fade in show">
		<div class="modal-dialog modal-login">
			<div class="modal-content">
				<div class="modal-header">			
					<h2 class="modal-title">Signin</h2>	
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form id='sign_up_form'>
						<div class="form-group">
							<label for="firstname">Username</label>
							<input type="text" class="form-control" name="username" id="username" placeholder="desired username" required />
						</div>

						<div class="form-group">
							<label for="firstname">Firstname</label>
							<input type="text" class="form-control" name="firstname" id="firstname" required />
						</div>
		
						<div class="form-group">
							<label for="lastname">Lastname</label>
							<input type="text" class="form-control" name="lastname" id="lastname" required />
						</div>
		
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control" name="email" id="email" required />
						</div>
		
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" name="password" id="password" required />
						</div>

						<div class="form-group">
							<label for="password">Password confirmation</label>
							<input type="password" class="form-control" name="passwordconfirmation" id="password_confirmation" required />
						</div>
						<div class="form-group">
							<button type="submit" class="btn btn-success btn-lg btn-block login-btn">Signin</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<span style="color:red" id="singinError"></span>
				</div>
			</div>
		</div>
	</div>

</div>


</body>
</html>