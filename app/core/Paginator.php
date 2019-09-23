<?php
declare(strict_types=1);

namespace simplerest\core;

class Paginator
{
    public $orders = [];
    public $offset = 0;
    public $limit = null;
    public $properties = [];
    private $query = '';
    private $binding = [];

    /** 
     * @param array $properties of the entity to be paginated
     * @param array $order 
     * @param int $offset
     * @param int $limit
    */
    public function __construct(array $properties=null, array $order = null, int $offset = 0, int $limit = null){
        $this->order = $order;
        $this->offset = $offset;
        $this->limit = $limit;

        if ($order!=null && $limit!=null)
            $this->compile();
    }

    public function compile():void
    {
        $query = '';
        if (!empty($this->orders)){
            $query .= ' ORDER BY ';
            foreach($this->orders as $field => $order){
                if ($order == 'ASC' || $order == 'DESC')
                    $ord = $order;
                else
                    throw new \InvalidArgumentException("order should be ASC or DESC!");   

                if(!in_array($field,$this->properties))
                    throw new \InvalidArgumentException("property '$field' not found!");   

                $query .= "$field $ord, "; 
            }
            $query = substr($query,0,strlen($query)-2);
        }

        if($this->limit >0){
            $query .= " LIMIT :offset, :limit";
            $this->binding[] = [':offset', $this->offset, \PDO::PARAM_INT];
            $this->binding[] = [':limit', $this->limit, \PDO::PARAM_INT];
        }

        $this->query = $query;
    }

    /**
     * Set the value of orders
     *
     * @return  self
     */ 
    public function setOrders($orders): Paginator
    {
        $this->orders = $orders;
        return $this;
    }

    /**
     * Set the value of offset
     *
     * @return  self
     */ 
    public function setOffset($offset): Paginator
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Set the value of limit
     *
     * @return  self
     */ 
    public function setLimit($limit): Paginator
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set the value of properties
     *
     * @return  self
     */ 
    public function setProperties($properties): Paginator
    {
        $this->properties = $properties;
        return $this;
    }
 

    /**
     * Get the value of query
     */ 
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Get the value of binding
     */ 
    public function getBinding(): array
    {
        return $this->binding;
    }
}