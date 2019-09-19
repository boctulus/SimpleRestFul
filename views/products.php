
	<script>
		notLoggedGoHome();
	</script>

	<h1 class="red-text text-center" style="font-size:2em">Products</h1>

	<div id="tb_products" class="table-responsive" style="margin-top:4em;">
	</div>

	<a href="#productModalAdd" class="btn btn-info btn-sm" data-toggle="modal" onClick="clearForm();"> 
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
		  <button type="button" class="btn btn-primary" onClick="do_edit()">Save changes</button>
		</div>
	  </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->


	<!-- footer                          -->
	<div class="footer navbar-fixed-bottom" style="padding: 0 15px 0 15px; font-size: 1.2em;">
	
	</div>
	
	
</div>		
<script type="text/javascript">
	const endpoint = '/api/products';
	const minutes_for_token_renew = 2 // 2

	let $data = [];
	let table = new JqTable('tb_products');
	
	$(document).ready(()=>{
		listar();
		
		// renew token
		setInterval(function() {
			if (localStorage.getItem('exp')==null)
				return;
			else
				notLoggedGoHome();
			
			// diff is less than 2 minute
			if ( ((localStorage.getItem('exp')*1000) - (new Date()).getTime()) < 60000 * minutes_for_token_renew){
				renew();
			}
			
		}, 60 * 1000); /* 60 sec */
	});
	
	
	function clearForm(){
		notLoggedGoHome();
		
		$('#name').val("");	
		$('#description').val("");	
		$('#cost').val("");
		$('#size').val("");
	}
	
	/* Edit */
	function do_edit(){
		notLoggedGoHome();
		
		let obj ={};
		let id   = $('#eid').val();
		obj.name = $('#ename').val();	
		obj.description = $('#edescription').val();	
		obj.cost = $('#ecost').val();
		obj.size = $('#esize').val();		
					
		//console.log(obj);	
					
		let encoded = JSON.stringify(obj);
		
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
			url: endpoint + '/' + id.toString(),
			data: encoded,
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')},
			success: function(data){
				console.log(data);
				if (!data.error){
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
					
					table.editRow([id,obj.name,obj.description, obj.size,obj.cost]);
					toastr["success"]("Product edited!", "Success");
				}else
					toastr["error"]("An error ocurred!", "Error");				
			},
			error: function(data){
				console.log('!Error~', data);
				toastr["error"]("An error ocurred!", "Error");
			}
		});
	}
	
	/* just previous reading to save updated data */
	function editar(id)
	{
		notLoggedGoHome();
		
		$.ajax({
			type: "GET",	// lectura previa //
			url: endpoint+'/'+id.toString(),
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')},
			success: function(data){
				// console.log('data for id= '+id.toString(),data);
				$('#eid').val(data.id);
				$('#ename').val(data.name);	
				$('#edescription').val(data.description);	
				$('#ecost').val(data.cost);
				$('#esize').val(data.size);	
				
				$('#productModalEdit').modal('show');
			},
			error: function(data){
				console.log('Error');
				console.log(data);
			}
		});		
		
		//console.log(id);
	}

	/* Create */
	function crear()
	{
		notLoggedGoHome();
		
		let obj ={};
		obj.name = $('#name').val();	
		obj.description = $('#description').val();	
		obj.cost = $('#cost').val();
		obj.size = $('#size').val();		
				
		let encoded = JSON.stringify(obj);
		
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
			url: endpoint,
			data: encoded,
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')},
			success: function(data){
				//console.log(data);
				if (!data.error){
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
					
					obj.id = data.id;
					
					table.addRow([obj.id,obj.name,obj.description, obj.size,obj.cost]);
					toastr["success"]("Product added!", "Success");
				}else{
					toastr["error"]("An error ocurred!", "Error");	
					console.log(data.error);
				}		
			},
			error: function(data){
				console.log('Error', data);
				toastr["error"]("An error ocurred!", "Error");
			}
		});
	}
	
	/* Delete */
	function borrar(id){
		notLoggedGoHome();
		
		bootbox.confirm("Are you sure you want to delete?", function(result) {
			if (result)	
				$.ajax({
						type: "DELETE",	/* DELETE VERB */
						url: endpoint + '/' + id.toString(),
						dataType: 'json',
						headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')},
						success: function(data){
							$('#tr'+id.toString()).remove();
							if(data.error)
								console.log('Error',data.error);
						},
						error: function(data){
							console.log('Error', data);
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
		notLoggedGoHome();
		
		$.ajax({
			type: "GET",
			url: endpoint,
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('tokenJwt')},
			success: function(data){
				
				// unhide				
				$('#dvTable').removeClass('hidden');
				
				// array de objetos --> array de arrays
				for (i=0;i<data.length;i++){
					let row = [];
					for(let key in data[i]) {
						let value = data[i][key];
						row.push(value);
					}
					$data.push(row);
				}
		
				
				// headers
				$data.unshift(["Id","Name","Description","Size","Cost"]);
				
				// row classes
				//$data.rowClasses = ['info','','warning'];
				
				table.render($data);
			},
			error: function(data){
				if (data.statusText=='Unauthorized' ){
					window.location = login_page;
				}
				console.log('Error in GET all', data);
			}
		});		
	}
	
</script>
