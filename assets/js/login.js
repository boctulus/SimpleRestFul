	
		
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
	}
	
	
	function logout(){
		localStorage.removeItem('tokenJwt');
		window.location.href = '?c=login';
	}

	function renew(){
		console.log('Renewing token at ...'+(new Date()).toString());
	
		$.ajax({
			type: "GET",
			url: 'index.php?c=login&a=renew',
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
	
