<?php

importar("LojaVirtual.Pedidos.PedidoPagamento");

class PagamentoDeposito extends PedidoPagamento {
	
	public function addItem(PedidoItem $obj){}
	
	public function checkout(){
		
		return Sistema::$caminhoURL."retornodeposito.php";
		
	}
	
	public static function GetTipo(){
		return 'Depósito Bancário';
	}
	
	public function toString(){
		return self::GetTipo();
	}
	
	public function __toString(){
		return self::GetTipo();
	}
	
}

?>