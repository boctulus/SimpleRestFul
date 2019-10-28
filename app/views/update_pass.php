
<!-- Sign up -->	

<div class="row vcenter">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-3">
		<h1 style="font-size: 3em; padding-bottom: 0.5em;">Cambio de password</h1>

		<form action="#" onsubmit="return false;">
			<div class="form-group">

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

				<div style="color:red; text-align: center;" id="passChangeError"></div>

			</div>
		</form>		

		<div class="form-group">
			<button type="submit" class="btn btn-primary btn-lg btn-block login-btn" onClick="update_pass()">Actualizar</button>
		</div>
	</div>
</div>	
	
