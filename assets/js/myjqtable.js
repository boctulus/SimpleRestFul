

	function generateTable(id) {
	 
		// Create a HTML Table element.
		var table = document.createElement("TABLE");
		table.border = "0";
		table.className = "table";
		
		if (typeof id != 'undefined')
			table.id = id;
	 
		// Get the count of columns.
		var columnCount = $data[0].length;
	 
		// Add the header row.
		var row = table.insertRow(-1);
		for (var i = 0; i < columnCount; i++) {
			var headerCell = document.createElement("TH");
			headerCell.className = "th_blue";
			headerCell.innerHTML = $data[0][i];
			row.appendChild(headerCell);
		}
		
		// Additional headers for buttons
		var headerCell = document.createElement("TH");
		headerCell.className = "th_blue";
		row.appendChild(headerCell);
		
		
		// Add the data rows.
		for (var i = 1; i < $data.length; i++) {
			row = table.insertRow(-1);
			
			//row.className = $data.rowClasses[i-1];
			row.id = 'tr'+ ($data[i][0]).toString();
			
			for (var j = 0; j < columnCount; j++) {
				var cell = row.insertCell(-1);
				cell.innerHTML = $data[i][j];
			}
			
			btnEdit = row.insertCell(-1);
			btnEdit.innerHTML = '<div style="display:inline;"> <button type="button" class="btn btn-md btn-success" onClick="editar('+$data[i][0]+')"><span class="glyphicon glyphicon-pencil"></span></button> &nbsp; <button type="button" class="btn btn-default btn-md btn-danger" onClick="borrar('+$data[i][0]+')"><span class="glyphicon glyphicon-trash"></span></button> </div>';
		}
	 
		var dvTable = document.getElementById("dvTable");
		dvTable.innerHTML = "";
		dvTable.appendChild(table);
	}
	
	function addRow(reg){
		var table = document.getElementById("products"); // hardcoded!
		row = table.insertRow(-1);
		
		id = reg[0];
		
		//row.className = $data.rowClasses[i-1];
		row.id = 'tr'+ id.toString();
		
		for (var j = 0; j < reg.length; j++) {
			var cell = row.insertCell(-1);
			cell.innerHTML = reg[j];
		}
		
		btnEdit = row.insertCell(-1);
		btnEdit.innerHTML = '<div style="display:inline;"> <button type="button" class="btn btn-md btn-success" onClick="editar('+id+')"><span class="glyphicon glyphicon-pencil"></span></button> &nbsp; <button type="button" class="btn btn-default btn-md btn-danger" onClick="borrar('+id+')"><span class="glyphicon glyphicon-trash"></span></button> </div>';
		
	}
	
	
	function editRow(reg){
		var id = reg[0];
		
		for (var j = 0; j < reg.length; j++) {
			$('#tr'+id+' td:nth-child('+(j+1).toString()+')').text(reg[j]);
		}
	}