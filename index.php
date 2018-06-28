<html>
	<head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="css/toastr.css" rel="stylesheet"/>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
		<script src="js/toastr.min.js"></script>			
		
		<style>
			.th_blue { background-color: #4a89dc; color:eee; }
		</style>
	</head>

<body>	
<div class="container">
  <h1>Sales</h1>

<div id="dvTable" class="table-responsive" style="margin-top:4em;">
</div>

<a href="#saleModal" class="btn btn-info btn-sm" data-toggle="modal" onClick="$('#saleModalTitle').text('Add sale')"> 
  <span class="glyphicon glyphicon-plus"></span> Add 
</a>

<!-- Modal -->
<div class="modal fade" id="saleModal" tabindex="-1" role="dialog" aria-labelledby="saleModalLabel" aria-hidden="true">
<div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	  <h4 class="modal-title" id="saleModalTitle">Add sale</h4>
	</div>
	<div class="modal-body">
		<form>
			<div class="form-group">
				<label for="first_name">Name</label>
				<input type="text" class="form-control" id="name" name="name">
			</div>
			
			<div class="form-group">
				<label for="last_name">Description</label>
				<textarea type="text" class="form-control" id="description" name="description"></textarea>
			</div>
			
			<div class="form-group">
				<label for="last_name">Cost</label>
				<input type="text" class="form-control" id="cost" name="cost">
			</div>
			
			<div class="form-group">
				<label for="last_name">Size</label>
				<input type="text" class="form-control" id="size" name="size">
			</div>
		</form>
	</div>
	<div class="modal-footer">
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  <button type="button" class="btn btn-primary" onClick="crear()">Save changes</button>
	</div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

	var $data = [];
	
	function editar(id){
		$('#saleModalTitle').text('Edit sale');
		$('#saleModal').modal('show');
		
		console.log(id);
	}

	function crear()
	{
		var obj ={};
		
		obj.name = $('#name').val();	
		obj.description = $('#description').val();	
		obj.cost = $('#cost').val();
		obj.size = $('#size').val();		
					
		//console.log(obj);	
					
		var encoded = JSON.stringify(obj);
		
		toastr.options = {
		  "closeButton": false,
		  "debug": false,
		  "newestOnTop": false,
		  "progressBar": false,
		  "positionClass": "toast-top-right",
		  "preventDuplicates": false,
		  "onclick": null,
		  "showDuration": "300",
		  "hideDuration": "1000",
		  "timeOut": "5000",
		  "extendedTimeOut": "1000",
		  "showEasing": "swing",
		  "hideEasing": "linear",
		  "showMethod": "fadeIn",
		  "hideMethod": "fadeOut"
		}

		$.ajax({
			type: "POST",
			url: 'api/create.php',
			data: encoded,
			dataType: 'text json',
			success: function(data){
				console.log(data);
				if (data!="Error"){
					$('#saleModal').modal('hide');

					toastr.options = {
					  "closeButton": false,
					  "debug": false,
					  "newestOnTop": false,
					  "progressBar": false,
					  "positionClass": "toast-top-right",
					  "preventDuplicates": false,
					  "onclick": null,
					  "showDuration": "300",
					  "hideDuration": "1000",
					  "timeOut": "5000",
					  "extendedTimeOut": "1000",
					  "showEasing": "swing",
					  "hideEasing": "linear",
					  "showMethod": "fadeIn",
					  "hideMethod": "fadeOut"
					};
					
					obj.id = data;
					
					addRow([obj.id,obj.name,obj.description, obj.size,obj.cost]);
					toastr["success"]("Sale added!", "Success");
				}else
					toastr["error"]("An error ocurred!", "Error");				
			},
			error: function(data){
				console.log('Error');
				console.log(data);
				toastr["error"]("An error ocurred!", "Error");
			}
		});
	}
	
	
	function borrar(id){
		$.ajax({
			type: "POST",
			url: 'api/delete.php',
			data: JSON.stringify({"id": id}),
			dataType: 'text json',
			success: function(data){
				$('#tr'+id.toString()).remove();
				console.log(data);
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
	}
	
	
	function listar(){
		$.ajax({
			type: "POST",
			url: 'api/read_all.php',
			dataType: 'text json',
			success: function(data){
				for (i=0;i<data.length;i++){
					var row = [];
					for(var key in data[i]) {
						var value = data[i][key];
						row.push(value);
					}
					$data.push(row);
				}
	
				
				// headers
				$data.unshift(["Id","Name","Description","Size","Cost"]);
				
				// row classes
				//$data.rowClasses = ['info','','warning'];
				
				generateTable("sales");
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
	}
	
	
	function addRow(reg){
		var table = document.getElementById("sales"); // hardcoded!
		row = table.insertRow(-1);
		
		id = reg[0];
		
		//row.className = $data.rowClasses[i-1];
		row.id = 'tr'+ id.toString();
		
		for (var j = 0; j < reg.length; j++) {
			var cell = row.insertCell(-1);
			cell.innerHTML = reg[j];
		}
		
		btnEdit = row.insertCell(-1);
		btnEdit.innerHTML = '<button type="button" class="btn btn-md btn-success" onClick="editar('+id+')">edit</button>';
		
		btnDelete = row.insertCell(-1);
		btnDelete.innerHTML = '<button type="button" class="btn btn-default btn-md btn-danger" onClick="borrar('+id+')">del</button>';
		
	}
	
	
	function generateTable(id) {
	 
		//Create a HTML Table element.
		var table = document.createElement("TABLE");
		table.border = "0";
		table.className = "table";
		table.id = id;
	 
		//Get the count of columns.
		var columnCount = $data[0].length;
	 
		//Add the header row.
		var row = table.insertRow(-1);
		for (var i = 0; i < columnCount; i++) {
			var headerCell = document.createElement("TH");
			headerCell.className = "th_blue";
			headerCell.innerHTML = $data[0][i];
			row.appendChild(headerCell);
		}
		
		//Additional headers for buttons
		for (var i = 0; i < 2; i++) {
			var headerCell = document.createElement("TH");
			headerCell.className = "th_blue";
			row.appendChild(headerCell);
		}
		
		//Add the data rows.
		for (var i = 1; i < $data.length; i++) {
			row = table.insertRow(-1);
			
			//row.className = $data.rowClasses[i-1];
			row.id = 'tr'+ ($data[i][0]).toString();
			
			for (var j = 0; j < columnCount; j++) {
				var cell = row.insertCell(-1);
				cell.innerHTML = $data[i][j];
			}
			
			btnEdit = row.insertCell(-1);
			btnEdit.innerHTML = '<button type="button" class="btn btn-md btn-success" onClick="editar('+id+')">edit</button>';
			
			btnDelete = row.insertCell(-1);
			btnDelete.innerHTML = '<button type="button" class="btn btn-default btn-md btn-danger" onClick="borrar('+id+')">del</button>';
		}
	 
		var dvTable = document.getElementById("dvTable");
		dvTable.innerHTML = "";
		dvTable.appendChild(table);
	}
	
	$(document).ready(()=>{listar()});


</script>
</body>
</html>