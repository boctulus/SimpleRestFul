	
	
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