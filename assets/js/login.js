	
	function logout(){
		//console.log('Loging out...');
		
		$.ajax({
			type: "GET",
			url: 'index.php?c=login&a=logout',
			dataType: 'text',
			headers: {"Authorization": 'Bearer ' + store.getJWT()}, // token
			success: function(data){
				//console.log(data);
				window.location = 'index.php?c=login';
			},
			error: function(data){
				//console.log('Error');
				//console.log(data);
				window.location = 'index.php?c=login';
			}
		});		
	}
	
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
					localStorage.setItem('exp',data.exp);
					localStorage.setItem('username',obj.username);
					window.location = 'index.php';
				}else{				
					$('#loginError').text('Error en usuario o password');
					console.log(data);
				}
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
	}
	
	function renew(){
		console.log(localStorage.getItem('token'));
		console.log('Renewing token at ...'+(new Date()).toString());
	
		$.ajax({
			type: "GET",
			url: 'index.php?c=login&a=renew',
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + store.getJWT()}, // token
			success: function(data){
				if (typeof data.token != 'undefined'){
					store.setJWT(data.token);
					localStorage.setItem('exp',data.exp);
				}else{
					console.log('Error en la renovación del token');
					window.location = 'index.php?c=login';
				}
			},
			error: function(data){
				console.log('Error en la renovación del token!!!!!!!!!!!!');
				console.log(data);
				//window.location = 'index.php?c=login';
			}
		});		
	}
	
	