	const base_url = getSiteRoot();
	const login_page = base_url + 'login';  

	console.log(base_url + 'login');

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
					window.location = base_url; // ok?
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
					window.location = base_url;
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
		window.location.href = login_page;
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
					window.location = login_page ;
				}
			},
			error: function(data){
				console.log('Error en la renovación del token!!!!!!!!!!!!');
				console.log(data);
				window.location = login_page;
			}
		});		
	}

	