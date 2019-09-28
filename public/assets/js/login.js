	const base_url = getSiteRoot();
	const login_page = base_url + 'login';  

	//console.log(base_url + 'login');

	function notLoggedGoHome()
	{
		if (typeof login_page === 'undefined' || typeof localStorage === 'undefined'){
			console.log('Error');
			return;
		}
	
		const expired = ((localStorage.getItem('exp')!=null) && ((localStorage.getItem('exp')*1000) - (new Date()).getTime())<0);
		
		if ((localStorage.getItem('access_token') == null) || expired){
			if (localStorage.getItem('refresh_token')){
				renew();	
			}else 
				window.location = login_page;
		}		 
	}

	function signup(){
		//console.log('here');

		var obj ={};

		if ($('#password').val() != $('#password_confirmation').val()){
			$('#signupError').text('Contraseñas no coinciden');
			return;
		}else $('#signupError').text('');

		obj.email = $('#email').val();	
		obj.password = $('#password').val();
		obj.firstname = $('#firstname').val();	
		obj.lastname = $('#lastname').val();

		$.ajax({
			type : "POST",
			url: "/auth/signup",
			data : JSON.stringify(obj),
			dataType: 'json',
			success : function(data) {
				if (typeof data.access_token != 'undefined'){
					console.log('Token recibido');
					localStorage.setItem('access_token',data.access_token);
					localStorage.setItem('refresh_token',data.refresh_token);
					localStorage.setItem('expires_in',data.expires_in);
					localStorage.setItem('exp', parseInt((new Date).getTime() / 1000) + data.expires_in);
					localStorage.setItem('sid',data.sid);
					console.log('Tokens obtenidos',data);
					window.location = base_url; 
				}else{		
					$('#signupError').text('Error desconcido');
					console.log(data);
				}
			},
			error: function(xhr, status, error){
				console.log(JSON.parse(xhr.responseText));
				$('#signupError').text(JSON.parse(xhr.responseText).error);
			}
		});

		return false;
	}
	
	function login(){
		var obj ={};
		
		obj.email = $('#email').val();	
		obj.password = $('#password').val();	
		
		// get form data
		//obj = this.serializeObject();

		$.ajax({
			type: "POST",
			url: '/auth/login',
			data: JSON.stringify(obj),
			dataType: 'json',
			success: function(data){
				if (typeof data.access_token != 'undefined' && typeof data.refresh_token != 'undefined'){
					localStorage.setItem('access_token',data.access_token);
					localStorage.setItem('refresh_token',data.refresh_token);
					localStorage.setItem('expires_in',data.expires_in);
					localStorage.setItem('exp', parseInt((new Date).getTime() / 1000) + data.expires_in);
					localStorage.setItem('sid',data.sid);
					console.log('Tokens obtenidos');
					window.location = base_url;
				}else{	
					console.log('Error (success)',data);	
					$('#loginError').text(data.responseJSON.error);
				}
			},
			error: function(data){
				console.log('Error (error)',data);
				$('#loginError').text('Error en usuario o password!');
			}
		});		

		return false;
	}
	
	
	function logout(){
		localStorage.removeItem('access_token');
		localStorage.removeItem('refresh_token');

		$.ajax({
			type: "POST",
			url: '/auth/logout',
			data : JSON.stringify({sid: localStorage.getItem('sid')}),
			dataType: 'json',
			success: function(data){
				//console.log('OK', data);
			},
			error: function(xhr){
				//console.log('Error en el borrado de la session', xhr);
				window.location = login_page;
			}
		});		

		window.location.href = login_page;
	}

	function renew(){
		console.log('Renewing token at ...'+(new Date()).toString());
	
		$.ajax({
			type: "POST",
			url: '/auth/token_renew',
			data : JSON.stringify({sid: localStorage.getItem('sid')}),
			dataType: 'json',
			headers: {"Authorization": 'Basic ' + localStorage.getItem('refresh_token')}, 
			success: function(data){
				if (typeof data.access_token != 'undefined'){
					localStorage.setItem('access_token',data.access_token);
					localStorage.setItem('expires_in',data.expires_in);
					localStorage.setItem('exp', parseInt((new Date).getTime() / 1000) + data.expires_in);
				}else{
					console.log('Error en la renovación del token');
					////////window.location = login_page;
				}
			},
			error: function(data){
				console.log('Error en la renovación del token!!!!!!!!!!!!');
				console.log(data);
				/////////window.location = login_page;
			}
		});		
	}

	