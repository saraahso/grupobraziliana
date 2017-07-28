<?php

abstract class Objeto {
	
	protected	$id;
	protected	$session;
	
	public function __construct($id = ''){
		
		$this->id 		= $id; 
		$this->session	= '';
		
	}
	
	public function getId(){
		return $this->id ? $this->id : 0;	
	}
	
	public function __call($m, $a){
		return false;	
	}
	
	public function setSession($s){
		$this->session = $s;	
	}
	
	public function getSession($s){
		return $this->session;	
	}
	
}

?>