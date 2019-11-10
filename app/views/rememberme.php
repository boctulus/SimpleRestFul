<!-- Login -->

<style type="text/css">
	.login-form {
		width: 340px;
    	margin: 30px auto;
	}
    .login-form form {
    	margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .login-form .hint-text {
		color: #777;
		padding-bottom: 15px;
		text-align: center;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .login-btn {        
        font-size: 15px;
        font-weight: bold;
    }
    .or-seperator {
        margin: 20px 0 10px;
        text-align: center;
        border-top: 1px solid #ccc;
    }
    .or-seperator i {
        padding: 0 10px;
        background: #f7f7f7;
        position: relative;
        top: -11px;
        z-index: 1;
    }
    .social-btn .btn {
        margin: 10px 0;
        font-size: 15px;
        text-align: left; 
        line-height: 24px;       
    }
	.social-btn .btn i {
		float: left;
		margin: 4px 15px  0 5px;
        min-width: 15px;
	}
	.input-group-addon .fa{
		font-size: 18px;
	}
</style>


<div class="row vcenter">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-push-3">
		<h1 style="font-size: 3em; padding-bottom: 0.5em;">Login</h1>

		<form action="#" onsubmit="return false;">

			<div class="form-group" >		
					
				<div style="text-align:right; margin-bottom:1em;">
					Tiene cuenta? <a href="/login">Ingresar</a>
				</div>

				<div class="input-group" style="margin-bottom:1em;">
					<span class="input-group-addon">
					<i class="glyphicon glyphicon-envelope"></i>
					</span>
					<input type="email" class="form-control" id="email" placeholder="email" required="required">
				</div>
				
				<div style="color:red; text-align: center;" id="remembermeError"></div>
			
			</div>

			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg btn-block login-btn" onClick="rememberme()">Recuérdame</button>
			</div>

			No registrado? <a href="/login/register">regístrese</a>
		</form>		
		
	</div>
</div>
