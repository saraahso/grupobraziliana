<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class ProdutoMarca extends Objeto {
	
	private $imagem;
	private $url;
	
	public	$nome;
	public	$descricao;
	public	$enderecoURL;
	public	$disponivel;

	public function __construct($id = ''){
		
		parent::__construct($id);

		$this->nome 			= '';
		$this->descricao 		= '';
		$this->enderecoURL 		= '';
		$this->imagem 			= new Image;
		$this->url				= new URL;
		$this->disponivel		= false;

	}
	
	public function setImagem(Image $vImagem){
		$this->imagem = $vImagem;
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