<!DOCTYPE html>
<html>
<head>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.0/jquery.min.js"></script>
</head>
<body>

<input type="button" value= "Listar" onclick="listar()">
<input type="button" value= "Crear" onclick="crear()">
<input type="button" value= "Modificar" onclick="modificar()">
<input type="button" value= "Borrar" onclick="borrar()">

<script>
	function listar(){
		$.ajax({
			type: "POST",
			url: 'api/read_all.php',
			dataType: 'text json',
			success: function(data){
				console.log(data);
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
	}


	function borrar(id){
		var obj ={"id":4};
		
		var encoded = JSON.stringify(obj);
		
		$.ajax({
			type: "POST",
			url: 'api/delete.php',
			data: encoded,
			dataType: 'text json',
			success: function(data){
				console.log(data);
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});			
	}

	function modificar(){
		var obj ={		
						"id":4,
						"name":"ABCD",
						"description":"Crazy product",
						"size":"XL,L,M,S",
						"cost":"6000"
					};
					
		var encoded = JSON.stringify(obj);

		$.ajax({
			type: "POST",
			url: 'api/update.php',
			data: encoded,
			dataType: 'text json',
			success: function(data){
				console.log(data);
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});			
	}

	function crear(){

		var obj ={	"name":"ABCD",
						"description":"Crazy product",
						"size":"XL,L,M,S",
						"cost":"2000"
					};
					
		var encoded = JSON.stringify(obj);

		$.ajax({
			type: "POST",
			url: 'api/create.php',
			data: encoded,
			dataType: 'text json',
			success: function(data){
				console.log(data);
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});
	}
</script>


</body>	