<?php

class SQL {
	
	private $tabelas;
	private $campos;
	private $join;
	private $where;
	private $order;
	private $limit;
	private $group;
	
	public function __construct(){
		
		$this->tabelas 	= '';
		$this->campos 	= '*';
		$this->join 	= '';
		$this->where 	= '';
		$this->order 	= '';
		$this->limit 	= '';
		$this->group 	= '';
		
	}
	
	public function setTabela($tabelas){
		
		$this->tabelas = $tabelas;
		return $this;
		
	}
	
	public function setCampo($campos = '*'){
		
		$this->campos = $campos;
		return $this;
		
	}
	
	public function setJoin($join){
		
		$this->join = $join;
		return $this;
		
	}
	
	public function setWhere($where){
		
		$this->where = $where;
		return $this;
		
	}
	
	public function getWhere(){
		
		return $this->where;
		
	}
	
	public function setOrder($order){
		
		$this->order = $order;
		return $this;
		
	}
	
	public function setLimit($limit){
		
		$this->limit = $limit;
		return $this;
		
	}
	
	public function setGroup($group){
		
		$this->group = $group;
		return $this;
		
	}
	
	public function getSELECT($limit = ''){
		
		$sql = 	"SELECT ".$this->campos." FROM ".$this->tabelas." ".$this->join;
		
		if(!empty($this->where))
			$sql .=	" WHERE ".$this->where;
			
		if(!empty($this->group))
			$sql .= " GROUP BY ".$this->group;
		
		if(!empty($this->order))
			$sql .= " ORDER BY ".$this->order;
		
		if(!empty($limit))
			$sql .= " LIMIT ".$limit;
		elseif(!empty($this->limit))
			$sql .= " LIMIT ".$this->limit;
		
		return $sql;
		
	}
	
}

?>