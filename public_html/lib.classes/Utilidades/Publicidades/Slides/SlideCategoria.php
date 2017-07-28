<?php

importar("Geral.Objeto");
importar("Utilidades.Publicidades.Slides.Lista.ListaSlides");

class SlideCategoria extends Objeto {
	
	private	$slides;
	
	public	$titulo;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo 		= '';
		
		$this->slides	= new ListaSlides;
		$this->slides->condicoes('', '', '', '', "SELECT * FROM ".Sistema::$BDPrefixo."relacionamento_slides_categorias rsc INNER JOIN ".Sistema::$BDPrefixo."slides s ON s.id = rsc.slide WHERE rsc.categoria = '".$this->id."'");
		
	}
	
	public function addSlide(Slide $s){
		
		if($s->getId() != '' && $this->getId() != ''){
		
			$con = BDConexao::__Abrir();
			$con->executar("INSERT INTO ".Sistema::$BDPrefixo."relacionamento_slides_categorias(categoria, slide) VALUES('".$this->getId()."','".$s->getId()."')");		
		
		}
		
	}
	
	public function getSlides(){
		
		return $this->slides;
		
	}
	
}

?>