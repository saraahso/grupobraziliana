<?php

importar("Geral.Objeto");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcao");
importar("LojaVirtual.Produtos.Opcoes.ProdutoOpcaoValor");

class ProdutoOpcaoGerado extends Objeto {
		
	protected	$opcao;
	protected	$valor;
	
	public function __construct($id = ''){
		
		parent::__construct($id);
		
		$this->opcao	= new ProdutoOpcao;
		$this->valor	= new ProdutoOpcaoValor;
		
	}
	
	public function setOpcao(ProdutoOpcao $obj){
		$this->opcao = $obj;
	}
	
	public function getOpcao(){
		return $this->opcao;
	}
	
	public function setValor(ProdutoOpcaoValor $obj){
		$this->valor = $obj;
	}
	
	public function getValor(){
		return $this->valor;
	}
	
}

?>