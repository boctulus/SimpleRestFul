<html>
	<head> 
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link href="assets/css/toastr.css" rel="stylesheet"/>
		<link href="assets/css/core.css" rel="stylesheet"/>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
		<script src="assets/js/toastr.min.js"></script><!-- flash notifications -->	
		<script src="assets/js/bootbox.min.js"></script><!-- confirmation boxes -->
		<script src="vendor/byjg/jwt-wrapper/js/store.js"></script>
		<script src="assets/js/login.js"></script>
		<script src="assets/js/myjqtable.js"></script>
	</head>

<body>	
<div class="container-fluid">

	<?php include "views/navbar.php" ?>

	<h1 class="red-text text-center" style="font-size:2em">Products</h1>

	<div id="dvTable" class="table-responsive" style="margin-top:4em;">
	</div>

	<a href="#productModalAdd" class="btn btn-info btn-sm" data-toggle="modal" onClick="clearForm()"> 
	  <span class="glyphicon glyphicon-plus"></span> Add 
	</a>


	<!-- Add Form Modal -->
	<div class="modal fade" id="productModalAdd" tabindex="-1" role="dialog" aria-labelledby="productModalAddLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title" id="productModalAddTitle">Add product</h4>
		</div>
		<div class="modal-body">
			<form>
				<div class="form-group">
					<label for="first_name">Name</label>
					<input type="text" class="form-control" id="name"/>
				</div>
				
				<div class="form-group">
					<label for="last_name">Description</label>
					<textarea type="text" class="form-control" id="description"></textarea>
				</div>
				
				<div class="form-group">
					<label for="last_name">Cost</label>
					<input type="text" class="form-control" id="cost"/>
				</div>
				
				<div class="form-group">
					<label for="last_name">Size</label>
					<input type="text" class="form-control" id="size"/>
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


	<!-- Edit Form Modal -->
	<div class="modal fade" id="productModalEdit" tabindex="-1" role="dialog" aria-labelledby="productModalAddLabel" aria-hidden="true">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		  <h4 class="modal-title" id="productModalAddTitle">Edit product</h4>
		</div>
		<div class="modal-body">
			<form>
				<input type="hidden" id="eid"/>
			
				<div class="form-group">
					<label for="first_name">Name</label>
					<input type="text" class="form-control" id="ename"/>
				</div>
				
				<div class="form-group">
					<label for="last_name">Description</label>
					<textarea type="text" class="form-control" id="edescription"></textarea>
				</div>
				
				<div class="form-group">
					<label for="last_name">Cost</label>
					<input type="text" class="form-control" id="ecost"/>
				</div>
				
				<div class="form-group">
					<label for="last_name">Size</label>
					<input type="text" class="form-control" id="esize"/>
				</div>
			</form>
		</div>
		<div class="modal-footer">
		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  <button type="button" class="btn btn-primary" onClick="salvar()">Save changes</button>
		</div>
	  </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<!-- footer                          -->
	<div class="footer navbar-fixed-bottom" style="padding: 0 15px 0 15px; font-size: 1.2em;">
	
	</div>
	
	
</div>		
<script type="text/javascript">

	var $data = [];
	
	$(document).ready(()=>{
		listar();
		
		console.log(new Date()); ///
		
		// renew token
		setInterval(function() {
			if (localStorage.getItem('exp')==null)
				return;
			
			// diff is less than 2 minute
			if ( ((localStorage.getItem('exp')*1000) - (new Date()).getTime()) < 120000 ){
				renew();
			}
			
		}, 60 * 1000); /* 60 sec */
	});
	
	
	function clearForm(){
		$('#name').val("");	
		$('#description').val("");	
		$('#cost').val("");
		$('#size').val("");
	}
	
	/* Edit */
	function salvar(){
		var obj ={};
	
		var id = $('#eid').val();
	
		obj.name = $('#ename').val();	
		obj.description = $('#edescription').val();	
		obj.cost = $('#ecost').val();
		obj.size = $('#esize').val();		
					
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
			type: "PUT",	/* PUT VERB */
			url: 'api/api.php?id='+id,
			data: encoded,
			dataType: 'text json',
			headers: {"Authorization": 'Bearer ' + store.getJWT()},
			success: function(data){
				//console.log(data);
				if (data=="OK"){
					$('#productModalEdit').modal('hide');

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
				
					
					editRow([id,obj.name,obj.description, obj.size,obj.cost]);
					toastr["success"]("Product edited!", "Success");
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
	
	/* just reading previous to save updated data */
	function editar(id)
	{
		$.ajax({
			type: "GET",	/* lectura previa */
			url: 'api/api.php?id='+id.toString(),
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + store.getJWT()},
			success: function(data){
				//console.log(data);
				$('#eid').val(data.id);
				$('#ename').val(data.name);	
				$('#edescription').val(data.description);	
				$('#ecost').val(data.cost);
				$('#esize').val(data.size);	
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
		
		$('#productModalEdit').modal('show');
		
		//console.log(id);
	}

	/* Create */
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
			url: 'api/api.php',
			data: encoded,
			dataType: 'text json',
			headers: {"Authorization": 'Bearer ' + store.getJWT()},
			success: function(data){
				//console.log(data);
				if (data!="Error"){
					$('#productModalAdd').modal('hide');

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
					toastr["success"]("Product added!", "Success");
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
	
	/* Delete */
	function borrar(id){
		//console.log(id);
		
		bootbox.confirm("Are you sure you want to delete?", function(result) {
			if (result)	
				$.ajax({
						type: "DELETE",	/* DELETE VERB */
						url: 'api/api.php?id='+id,
						dataType: 'text json',
						headers: {"Authorization": 'Bearer ' + store.getJWT()},
						success: function(data){
							$('#tr'+id.toString()).remove();
							//console.log(data);
						},
						error: function(data){
							console.log('Error');
							console.log(data);
						}
					});		
		}).find('.modal-content').css({
			'background-color': '#f99',
			'font-weight' : 'bold',
			'color': '#F00',
			'font-size': '2em'
		});
		
	}
	
	/* Read */
	function listar(){
		$.ajax({
			type: "GET",
			url: 'api/api.php',
			dataType: 'text json',
			headers: {"Authorization": 'Bearer ' + store.getJWT()},
			success: function(data){
				
				// unhide				
				$('#dvTable').removeClass('hidden');
				
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
				
				generateTable("products");
			},
			error: function(data){
				if (data.statusText=='Unauthorized' || data.statusText=='error' )
					window.location = 'index.php?c=login';
				
				console.log('Error!!!');
				console.log(data);
			}
		});		
	}
	
</script>

</body>
</html>