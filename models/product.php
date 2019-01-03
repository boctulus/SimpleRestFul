<?php

include CORE_PATH. 'model.php';

class Product extends Model {
 
    private $conn;
    private $table_name = "products";
 
    // entity properties
    public $id;
    public $name;
    public $description;
	public $size;
    public $cost;
 
    public function __construct($db){
        $this->conn = $db;
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

	/*
		CRUD operations
	*/
	
	function exists()
	{
		$q  = "SELECT *FROM {$this->table_name} WHERE id=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_ASSOC);

		if (!$row)
			return false;
		else
			return true;
	}
	
	
	function read()
	{
		$q  = "SELECT *FROM {$this->table_name} WHERE id=:id";
		$st = $this->conn->prepare( $q );
		$st->bindParam(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		$this->name = $row->item_name;
		$this->description = $row->item_description;
		$this->size = $row->item_size;
		$this->cost = $row->item_cost;
	}
	
	
	function readAll()
	{
		$q  = "SELECT *FROM {$this->table_name} ORDER BY id";
		$st = $this->conn->prepare($q);
		$result = $st->execute();
	 
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function create()
	{
		$q = "INSERT INTO {$this->table_name} 
				SET
					item_name=:name, item_description=:description, item_size=:size, item_cost=:cost";
	 
		$st = $this->conn->prepare($q);
	 
		$st->bindParam(":name", $this->name, PDO::PARAM_STR);
		$st->bindParam(":description", $this->description, PDO::PARAM_STR);
		$st->bindParam(":size", $this->size, PDO::PARAM_STR);
		$st->bindParam(":cost", $this->cost, PDO::PARAM_INT);
	 
		//var_dump($st->errorInfo());
		
		$result = $st->execute();
		$this->id = $this->conn->lastInsertId();
	 
		return ($result);
	}
	
	
	function update()
	{
		$q = "UPDATE {$this->table_name} SET
					item_name = :name,
					item_description = :description,
					item_size = :size,
					item_cost = :cost
				WHERE
					id = :id";
	 
		$st = $this->conn->prepare($q);
	 
		$st->bindParam(':id', $this->id);
		$st->bindParam(':name', $this->name);
		$st->bindParam(':description', $this->description);
		$st->bindParam(':size', $this->size);
		$st->bindParam(':cost', $this->cost);
	 
		$st->execute();
		
		return $st->rowCount();
	}
	
	function delete()
	{
		$q = "DELETE FROM {$this->table_name} WHERE id = ?";
		$st = $this->conn->prepare($q);
		$st->bindParam(1, $this->id);
	 
		$st->execute();
		return $st->rowCount();
	}
	
	
}