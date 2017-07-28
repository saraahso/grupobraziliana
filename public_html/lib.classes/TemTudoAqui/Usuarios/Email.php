<?php

class Email {

	private $id;
	
	public	$descricao;
	public	$email;
	public	$principal;
	
	public function __construct($id = ''){
		
		$this->id 			= $id;
		
		$this->email		= '';
		$this->descricao	= '';
		$this->principal	= false;
		
	}
	
	public function getId(){
		return $this->id;
	}

}

?>