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
		
	</head>

<body>	

<div class="container">

	<!-- Login -->	
	<div id="loginModal" class="modal fade in show">
		<div class="modal-dialog modal-login">
			<div class="modal-content">
				<div class="modal-header">			
					<h2 class="modal-title">Login</h2>	
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				</div>
				<div class="modal-body">
					<form action="#" onsubmit="return false;">
						<div class="form-group">
							<input type="text" class="form-control" id="username" placeholder="Username" required="required">		
						</div>
						<div class="form-group">
							<input type="password" class="form-control" id="password" placeholder="Password" required="required">	
						</div>        
						<div class="form-group">
							<button type="submit" class="btn btn-primary btn-lg btn-block login-btn" onClick="login()">Login</button>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<!-- a href="#">Forgot Password?</a -->
				</div>
			</div>
		</div>
	</div>

</div>

<script>

	function login(){
		var obj ={};
		
		obj.username = $('#username').val();	
		obj.password = $('#password').val();	
		
		$.ajax({
			type: "GET",
			url: 'index.php?c=login&a=login&username='+obj.username+'&password='+obj.password,
			dataType: 'json',
			success: function(data){
				if (typeof data.token != 'undefined'){
					store.setJWT(data.token);
					window.location = 'index.php';
				}
				
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
	}

</script>

</body>
</html>