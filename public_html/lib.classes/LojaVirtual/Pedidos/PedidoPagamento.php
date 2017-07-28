<?php

importar("LojaVirtual.Pedidos.PedidoItem");
importar("TemTudoAqui.Usuarios.Pessoa");
importar("TemTudoAqui.Usuarios.Endereco");
importar("Utils.Dados.Numero");

abstract class PedidoPagamento {
	
	protected	$itens;
	protected	$id;
	protected	$endereco;
	protected	$cliente;
	protected	$desconto;
	
	public		$urlRetorno;
	public		$parcelaMinima;
	
	public function __construct(){
		$this->itens = new ArrayObject;
		$this->desconto = new Numero;
	}
	
	public function setReferencia($id){
		$this->id = $id;
	}
	
	public function getReferencia(){
		return $this->id;
	}
	
	public function setEndereco(PedidoEnderecoEntrega $obj){
		$this->endereco = $obj;
	}
	
	public function getEndereco(){
		return $this->endereco;
	}
	
	public function setCliente(Pessoa $obj){
		$this->cliente = $obj;
	}
	
	public function getCliente(){
		return $this->cliente;
	}
	
	abstract public function addItem(PedidoItem $obj);
	
	public function getItens(){
		return $this->itens;
	}
	
	public function setDesconto($num){
		$this->desconto->num = $num;
	}
	
	public function getDesconto(){
		return $this->desconto;
	}
	
	abstract public function checkout();
	
	abstract public function toString();
	
	abstract public function __toString();
	
}

?>