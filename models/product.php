<?php

include CORE_PATH. 'model.php';

/*
	Product extends Model to have access to reflection
	Another way could be to use traits 
*/
class Product extends Model 
{
    private $table_name = "products";
 
	/* 
		Entity properties

		Must be the only public properties
	*/	
    public $id;
    public $name;
    public $description;
	public $size;
	public $cost;
	
	/*
		Types are INT, STR and BOOL among others
		see: https://secure.php.net/manual/en/pdo.constants.php 
	*/
	protected $schema = [
		'name' => 'STR',
		'description' => 'STR',
		'size' => 'STR',
		'cost' => 'INT'
	];

    public function __construct($db){
		parent::__construct($db);
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
	
	
	function readById()
	{
		$q  = "SELECT *FROM {$this->table_name} WHERE id=:id";
		$st = $this->conn->prepare($q);
		$st->bindParam(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
		
		$row = $st->fetch(PDO::FETCH_OBJ);
		if (!$row)
			return false;
	 
		$this->name = $row->name;
		$this->description = $row->description;
		$this->size = $row->size;
		$this->cost = $row->cost;
	}
	
	function filter($conditions)
	{
		$vars   = array_keys($conditions);
		$values = array_values($conditions);

		$where = '';
		foreach($vars as $ix => $var){
			$where .= "$var = :$var AND ";
		}
		$where =trim(substr($where, 0, strrpos( $where, 'AND ')));
		
		$q  = "SELECT * FROM {$this->table_name} WHERE $where";
		$st = $this->conn->prepare($q);

		foreach($values as $ix => $val){
			$st->bindValue(":{$vars[$ix]}", $val, constant("PDO::PARAM_{$this->schema[$vars[$ix]]}"));
		}

		$st->execute();
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	function read()
	{
		$q  = "SELECT * FROM {$this->table_name} ORDER BY id";
		$st = $this->conn->prepare($q);
		$st->execute();
	 
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function create()
	{
		$q = "INSERT INTO {$this->table_name} 
				SET
					name=:name, description=:description, size=:size, cost=:cost";
	 
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
					name = :name,
					description = :description,
					size = :size,
					cost = :cost
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