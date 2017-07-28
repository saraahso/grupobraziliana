<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class ProdutoCor extends Objeto {
	
	private $imagem;
	
	public	$nome;
	public	$hexadecimal;
	
	public function __construct($id = ''){
		
		parent::__construct($id);

		$this->nome 			= '';
		$this->hexadecimal		= '';
		$this->imagem 			= new Image;
		
	}
	
	public function setImagem(Image $vImagem){
		$this->imagem = $vImagem;
	}
	
	public function getImagem(){
		return $this->imagem;
	}
	
}

?>