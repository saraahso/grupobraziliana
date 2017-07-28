<?php

class ProdutoDetalhe {
	
	private		$id;
	
	public		$detalhe;
	public 		$pedido;
	
	public function __construct($id = ''){
		
		$this->id 		= $id;
		$this->detalhe 	= '';
		$this->pedido 	= false;
		
	}
	
	public function getId(){
		
		return $this->id;
		
	}
	
}

?>