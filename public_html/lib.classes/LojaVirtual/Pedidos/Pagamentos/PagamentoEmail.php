<?php

importar("LojaVirtual.Pedidos.PedidoPagamento");

class PagamentoEmail extends PedidoPagamento {
	
	public function addItem(PedidoItem $obj){}
	
	public function checkout(){
		
		return Sistema::$caminhoURL."retornoemail.php";
		
	}
	
	public static function GetTipo(){
		return 'Pedido Enviado por E-mail';
	}
	
	public function toString(){
		return self::GetTipo();
	}
	
	public function __toString(){
		return self::GetTipo();
	}
	
}

?>