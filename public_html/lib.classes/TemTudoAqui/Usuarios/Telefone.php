<?php

class Telefone{

	private $id;
	
	public	$local;
	public	$ddd;
	public	$telefone;
	public	$ramal;
	
	public function __construct($id = ''){
		$this->id = $id;
	}
	
	public function getId(){
		return $this->id;
	}

}

?>