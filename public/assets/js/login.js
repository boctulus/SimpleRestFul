	const base_url = getSiteRoot();
	const login_page = base_url + 'login';  

	//console.log(base_url + 'login');

	function notLoggedGoHome(){
		if (typeof login_page === 'undefined' || typeof localStorage === 'undefined'){
			console.log('Error');
			return;
		}

		const expired = ((localStorage.getItem('exp')!=null) && ((localStorage.getItem('exp')*1000) - (new Date()).getTime())<0);
		
		if ((localStorage.getItem('tokenJwt') == null) || expired)
			window.location = login_page; 
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
				if (typeof data.token != 'undefined'){
					console.log('Token recibido');
					localStorage.setItem('tokenJwt',data.token);
					localStorage.setItem('exp',data.exp);
					localStorage.setItem('email',obj.email);
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
				if (typeof data.token != 'undefined'){
					localStorage.setItem('tokenJwt',data.token);
					localStorage.setItem('exp',data.exp);
					localStorage.setItem('email',obj.email);
					window.location = base_url;
				}else{		
					$('#loginError').text('Error en usuario o password');
					console.log(data);
				}
			},
			error: function(data){
				console.log('Error',data);
				$('#loginError').text('Error en usuario o password');
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
			url: '/auth/token/renew',
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

	