<?php

class PedidoStatus {
	
	const CANCELADO 			= 0;
	const ABERTO 				= 1;
	const FECHADO				= 4;
	const COBRANCA 				= 2;
	const ENTREGA 				= 3;
	const ESPERA 				= 5;
	const AGUARDANDO_CONTATO	= 6;
	const CHECKOUT				= 7;
	
	private $status = 0;
	
	public function __construct(){
		
		$this->status = self::ABERTO;
		
	}
	
	public function setStatus($vStatus){
		$this->status = $vStatus;	
	}
	
	public function getStatus(){
		return $this->status;	
	}
	
	public function getNomeStatus(){
		return self::__NomeStatus($this->status);	
	}
	
	public static function __NomeStatus($n){
		
		if($n == 0)
			return "Cancelado";
		elseif($n == 1)
			return "Aberto";
		elseif($n == 2)
			return "Aguardando Pagamento";
		elseif($n == 3)
			return "Pagamento Concluído";
		elseif($n == 4)
			return "Produto Enviado";
		elseif($n == 5)
			return "Pagamento em Análise";
		elseif($n == 6)
			return "Aguardando Contato";
		
	}
	
	public function __toString(){
		return (string) self::__NomeStatus($this->status);
	}
	
}

?>