<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class Usuario extends Objeto {
	
	private	$imagem;
	
	public	$nivel;
	public	$nome;
	public	$login;
	public	$senha;
	public	$texto;
	public	$ensino;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->nivel	= 0;
		$this->nome		= '';
		$this->login	= '';
		$this->senha	= '';
		$this->texto	= '';
		$this->ensino	= '';
		
		$this->imagem	= new Image;
		
	}
	
	public function setImagem(Image $img){
		$this->imagem = $img;	
	}
	
	public function getImagem(){
		return $this->imagem;	
	}
	
}

?>