<?php

class SubAtividade{
	
	private		$id;
	
	public		$titulo;
	
	public function __construct($id = ''){
		
		$this->id = $id;
		
	}
	
	public function getId(){
		return $this->id;
	}
	
}

?>