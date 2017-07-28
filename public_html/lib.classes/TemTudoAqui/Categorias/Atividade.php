<?php

importar("TemTudoAqui.Categorias.Lista.ListaSubAtividades");

class Atividade{
	
	private		$id;
	private 	$subAtividade;
	
	public		$titulo;
	
	public function __construct($id = ''){
		
		$this->id = $id;
		$this->subAtividade = new ListaSubAtividades;
		$this->subAtividade->condicoes('', $this->id, 'atividade');
		
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setSubAtividade(SubAtividade $vSubAtividade){
		$this->subAtividade->inserir($vSubAtividade, $this);
	}
	
	public function getSubAtividade(){
		return $this->subAtividade;
	}
	
}

?>