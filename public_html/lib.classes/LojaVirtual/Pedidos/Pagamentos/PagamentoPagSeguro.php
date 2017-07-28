<?php

importar("LojaVirtual.Pedidos.PedidoPagamento");
importar("LojaVirtual.PagSeguroLibrary.PagSeguroLibrary");

class PagamentoPagSeguro extends PedidoPagamento {
	
	const	PARCELAMINIMA = 5.00;
	
	private	$pay;
	
	public function __construct(){
		parent::__construct();
		
		$this->parcemaMinima = self::PARCELAMINIMA;
		
		$this->pay = new PagSeguroPaymentRequest();
		$this->pay->setCurrency("BRL");
		
	}
	
	public function addItem(PedidoItem $obj){
		
		$this->itens->append($obj);
		
	}
	
	public function checkout(){
		
		$con = BDConexao::__Abrir();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."pagamentos");
		$rsP = $con->getRegistro();
		$con->executar("SELECT * FROM ".Sistema::$BDPrefixo."frete");
		$rsF = $con->getRegistro();
		
		$this->pay->setReference($this->id);
		
		foreach($this->itens as $k => $obj){
			
			if(!$rsP['fretepagseguro'] && $rsF['ativocorreio'] && !$rsF['fretegratis'] && $obj->frete != Produto::SEM_FRETE && $obj->frete != Produto::FRETE_GRATIS)
				$this->pay->addItem($obj->getId(), (string) $obj, $obj->quantidade, $obj->valor->formatar());
			elseif($rsP['fretepagseguro'] && !$rsF['fretegratis'] && $obj->frete != Produto::SEM_FRETE && $obj->frete != Produto::FRETE_GRATIS)
				$this->pay->addItem($obj->getId(), (string) $obj, $obj->quantidade, $obj->valor->formatar(), $obj->peso->formatar()*1000, $obj->getValorFrete()->formatar());
			else
				$this->pay->addItem($obj->getId(), (string) $obj, $obj->quantidade, $obj->valor->formatar());
			
				
		}
		
		$this->pay->setShippingType(3);
		
		$this->pay->setShippingAddress($this->endereco->getCep(),  $this->endereco->logradouro,  $this->endereco->numero, $this->endereco->complemento, $this->endereco->bairro, $this->endereco->cidade, $this->endereco->estado, $this->endereco->pais);
		
		if(!$rsF['fretegratis'] && !$rsP['fretepagseguro'])
			$this->pay->setShippingCost($this->endereco->getValor()->formatar());
		
		$tel = $this->cliente->getTelefone()->listar();
		$this->pay->setSender($this->cliente->nome." ".$this->cliente->sobreNome, $this->cliente->emailPrimario, $tel->ddd, $tel->telefone);
		
		$this->pay->setExtraAmount(-($this->getDesconto()->formatar()));
		
		try {
			
			$this->pay->setRedirectUrl($this->urlRetorno);
			$credentials = new PagSeguroAccountCredentials($rsP['emailpagseguro'], $rsP['tokenpagseguro']);
			
			// Register this payment request in PagSeguro, to obtain the payment URL for redirect your customer.
			$url = $this->pay->register($credentials);
			
			return $url;
			
		} catch (PagSeguroServiceException $e) {
			throw new Exception($e->getMessage());
		}
		
	}
	
	public static function GetTipo(){
		return 'PagSeguro';
	}
	
	public function toString(){
		return self::GetTipo();
	}
	
	public function __toString(){
		return self::GetTipo();
	}
	
}

?>