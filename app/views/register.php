
<!-- Sign up -->	

<div class="row vcenter">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-3">
		<h1 style="font-size: 3em; padding-bottom: 0.5em;">Registro</h1>

		<form action="#" onsubmit="return false;">
			<div class="form-group">

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-user"></i>
					</span>
					<input type="text" class="form-control" name="firstname" id="firstname" placeholder="First name" required />
				</div>

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-user"></i>
					</span>
					<input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last name" required />
				</div>

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-envelope"></i>
					</span>
					<input type="email" class="form-control" id="email" placeholder="E-mail" required="required">
				</div>

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-lock"></i>
					</span>
					<input type="password" class="form-control" id="password" placeholder="Password" required="required">
				</div>

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-lock"></i>
					</span>
					<input type="password" class="form-control" name="passwordconfirmation" id="password_confirmation" placeholder="Password confirmation" required />
				</div>
				
				<div style="color:red; text-align: center;" id="registerError"></div>

			</div>
		</form>		

		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-lg btn-block login-btn" onClick="register()">Registrarse</button>
		</div>

		Ya posee cuenta? <a href="/login">ingresar</a>
	</div>
</div>	
	
