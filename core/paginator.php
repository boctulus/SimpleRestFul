<?php

declare(strict_types=1);

class Paginator
{
    public $orders = [];
    public $offset = 0;
    public $limit = null;

    public function getSql()
    {
        $out = '';
        if (!empty($this->orders)){
            $out .= ' ORDER BY ';
            foreach($this->orders as $field => $order){
                $out .= "$field $order, "; 
            }
            $out = substr($out,0,strlen($out)-2);
        }

        if($this->limit>0){
            $out .= " LIMIT $this->offset,$this->limit ";
        }
        return $out;
    }
}