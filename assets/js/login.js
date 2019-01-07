	$(document).on('submit', '#sign_up_form', function(){
		console.log('here!');

		// get form data
		let sign_up_form=$(this);
		let form_obj = sign_up_form.serializeObject();

		$.ajax({
			type : "POST",
			url: "api/auth/signin",
			data : JSON.stringify(form_obj),
			dataType: 'json',
			success : function(result) {
				if (typeof data.token != 'undefined'){
					console.log('Token recibido');
					localStorage.setItem('tokenJwt',data.token);
					localStorage.setItem('exp',data.exp);
					localStorage.setItem('username',obj.username);
					window.location = 'index.php';
				}else{		
					$('#siginError').text('Error during signin');
					console.log(data);
				}
			},
			error: function(data){
				console.log('Error',data);
			}
		});
	});
	
	function login(){
		var obj ={};
		
		obj.username = $('#username').val();	
		obj.password = $('#password').val();	
		
		// get form data
		//obj = this.serializeObject();

		$.ajax({
			type: "POST",
			url: 'api/auth/login',
			data: JSON.stringify(obj),
			dataType: 'json',
			success: function(data){
				if (typeof data.token != 'undefined'){
					localStorage.setItem('tokenJwt',data.token);
					localStorage.setItem('exp',data.exp);
					localStorage.setItem('username',obj.username);
					window.location = 'index.php';
				}else{		
					$('#loginError').text('Error en usuario o password');
					console.log(data);
				}
			},
			error: function(data){
				console.log('Error',data);
			}
		});		

		return false;
	}
	
	
	function logout(){
		localStorage.removeItem('tokenJwt');
		window.location.href = '?c=login';
	}

	function renew(){
		console.log('Renewing token at ...'+(new Date()).toString());
	
		$.ajax({
			type: "POST",
			url: 'api/auth/token/renew',
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')}, 
			success: function(data){
				if (typeof data.token != 'undefined'){
					localStorage.setItem('tokenJwt',data.token);
					localStorage.setItem('exp',data.exp);
				}else{
					console.log('Error en la renovación del token');
					window.location = '?c=login';
				}
			},
			error: function(data){
				console.log('Error en la renovación del token!!!!!!!!!!!!');
				console.log(data);
				window.location = '?c=login';
			}
		});		
	}
	
	// function to make form values to json format
	// from codeofaninja.com
	$.fn.serializeObject = function(){
	
		var o = {};
		var a = this.serializeArray();
		$.each(a, function() {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value || '');
			} else {
				o[this.name] = this.value || '';
			}
		});
		return o;
	};