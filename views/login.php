
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
					<span style="color:red" id="loginError"></span>
				</div>
			</div>
		</div>
	</div>

