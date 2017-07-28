<?php

importar("Geral.Objeto");
importar("Utils.Imagens.Image");

class ProdutoOpcaoValor extends Objeto {
		
	private	$image;
		
	public	$valor;
	public	$cor;
	
	public function __construct($id = ''){
		
		parent::__construct($id);

		$this->valor = '';
		$this->cor = '';
		$this->image = new NewImage(1, 1);
		
	}
	
	public function setImagem(Image $obj){
		$this->image = $obj;
	}
	
	public function getImagem(){
		return $this->image;	
	}
	
}

?>