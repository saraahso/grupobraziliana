<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class Imagem extends Objeto {
	
	private $image;
	private $sessao;
	private	$idSessao;
	
	public 	$legenda;
	public 	$destaque;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->sessao 		= '';
		$this->idSessao 	= '';
		$this->legenda 		= '';
		$this->destaque 	= false;
		
		$this->image 		= new NewImage(1, 1);
		
	}
	
	public function setSessao($sessao, $id){
		
		$this->sessao 	= $sessao;
		$this->idSessao = $id;
		
	}
	
	public function getSessao(){
		return $this->sessao;
	}
	
	public function getIdSessao(){
		return $this->idSessao;
	}
	
	public function setImage(Image $vImage){
		$this->image = $vImage;
	}
	
	public function getImage(){
		return $this->image;	
	}
	
}

?>