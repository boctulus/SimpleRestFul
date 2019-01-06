<?php
declare(strict_types=1);

class Paginator
{
    public $orders = [];
    public $offset = 0;
    public $limit = null;
    public $properties = [];
    private $query = null;
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
                    throw new InvalidArgumentException("order should be ASC or DESC!");   

                if(!in_array($field,$this->properties))
                    throw new InvalidArgumentException("property '$field' not found!");   

                $query .= "$field $ord, "; 
            }
            $query = substr($query,0,strlen($query)-2);
        }

        if($this->limit >0){
            $query .= " LIMIT :offset, :limit";
            $this->binding[] = [':offset', $this->offset, PDO::PARAM_INT];
            $this->binding[] = [':limit', $this->limit, PDO::PARAM_INT];
        }

        $this->query = $query;
    }

    function getQuery():string {
        return $this->query;
    }

    function getBinding():array {
        return $this->binding;
    }
}