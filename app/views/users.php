
<script>
	checkpoint();
</script>

<h1 class="red-text text-center" style="font-size:2em">Users</h1>

<div id="tb_users" class="table-responsive" style="margin-top:4em;">
</div>


<script type="text/javascript">
	const endpoint = 'api/users';
	const minutes_for_refresh = 2 // 2

	let $data = [];
	let table = new JqTable('tb_users');
	
	$(document).ready(()=>{
		listar();
		
		// renew token
		setInterval(function() {
			if (localStorage.getItem('exp')==null)
				return;
			else
				checkpoint();
			
			// diff is less than 2 minute
			if ( ((localStorage.getItem('exp')*1000) - (new Date()).getTime()) < 60000 * minutes_for_refresh){
				renew();
			}
			
		}, 60 * 1000); /* 60 sec */
	});
	
	
	/* Read */
	function listar(){
		checkpoint();
		
		$.ajax({
			type: "GET",
			url: endpoint,
			dataType: 'json',
			headers: {"Authorization": 'Bearer ' + localStorage.getItem('access_token')},
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
				$data.unshift(["Id","Email","Nombre","Apellido"]);
				
				// row classes
				//$data.rowClasses = ['info','','warning'];
				
				table.render($data);
				table.hide_first_col();
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
