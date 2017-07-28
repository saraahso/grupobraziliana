<?php

importar("Geral.Objeto");
importar("Utilidades.FAQ.Lista.ListaPerguntas");

class PerguntaCategoria extends Objeto {
	
	private	$perguntas;
	
	public	$titulo;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 		= '';
		$this->ordem		= 0;
		
		$this->perguntas 	= new ListaPerguntas;
		$this->perguntas->condicoes('', $this->id, ListaPerguntas::CATEGORIA);
		
	}
	
	public function setPergunta(Pergunta $p){
		
		if($this->id != ''){
			
			$p->setIdCategoria($this->id);
			
			$this->perguntas->inserir($p);
			
		}
		
	}
	
	public function getPerguntas(){
		return $this->perguntas;		
	}
	
}

?>