<?php

importar("Geral.Objeto");
importar("Geral.URL");
importar("Geral.Imagem");

class Texto extends Objeto {
	
	private	$imagem;
	
	public	$titulo;
	public	$subTitulo;
	public	$textoPequeno;
	public	$texto;
	public	$ordem;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->titulo		= '';
		$this->subTitulo	= '';
		$this->texto		= '';
		$this->textoPequeno	= '';
		$this->ordem		= 0;
		
		$this->imagem		= new Imagem;
		$this->url			= new URL;
		
	}
	
	public function setImagem(Imagem $img){
		$this->imagem = $img;	
	}
	
	public function getImagem(){
		return $this->imagem;	
	}
	
	public function setURL(URL $url){
		$this->url = $url;	
	}
	
	public function getURL(){
		return $this->url;	
	}
	
}

?>