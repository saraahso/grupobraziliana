<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class ProdutoTamanho extends Objeto {
	
	private $imagem;
	
	public	$nome;
	
	public function __construct($id = ''){
		
		parent::__construct($id);

		$this->nome 			= '';
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