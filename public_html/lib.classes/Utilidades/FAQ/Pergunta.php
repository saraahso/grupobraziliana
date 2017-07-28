<?php

importar("Geral.Texto");

class Pergunta extends Texto {
	
	private	$idCategoria;
	
	public function setIdCategoria($idCategoria){
		$this->idCategoria = $idCategoria;	
	}
	
	public function getIdCategoria(){
		return $this->idCategoria;	
	}
	
}

?>