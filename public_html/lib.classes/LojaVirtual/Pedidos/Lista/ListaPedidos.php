<?php

importar("Geral.Lista");
importar("LojaVirtual.Pedidos.Pedido");
importar("LojaVirtual.Pedidos.Lista.ListaPedidoEnderecoEntregas");
importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");

class ListaPedidos extends Lista {
	
	const ID			= 'id';
	const IDSESSAO		= 'sessao';
	const QUANTIDADE	= 'quantidade';
	const OBSERVACOES	= 'observacoes';
	const TIPOPAGAMENTO	= 'tipopagamento';
	const VALOR			= 'valor';
	const DESCONTO		= 'desconto';
	const DATA			= 'data';
	const STATUS		= 'status';
	const ESTOQUE		= 'estoque';
	const VENDEDOR		= 'vendedor';
	
	public function __construct(){
		
		parent::__construct('pedidos');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 				= new Pedido($info[self::ID]);
			
			$temp->setIdSessao($info[self::IDSESSAO]);
			
			$temp->quantidade 	= $info[self::QUANTIDADE];
			$temp->observacoes 	= $info[self::OBSERVACOES];
			$temp->estoque 		= $info[self::ESTOQUE];
			
			$temp->setData(new DataHora($info[self::DATA]));
			$temp->setStatus($info[self::STATUS]);
			$temp->estoque = $info[self::ESTOQUE];
			$temp->setDesconto($info[self::DESCONTO]);
			$temp->setTipoPagamento($info[self::TIPOPAGAMENTO]);
			$temp->setValor($info[self::VALOR]);
			$temp->setVendedor($info[self::VENDEDOR]);
			
			$lPEE = new ListaPedidoEnderecoEntregas;
			$lPEE->condicoes('', $temp->getId(), ListaPedidoEnderecoEntregas::IDSESSAO);
			if($lPEE->getTotal() > 0){				
				$temp->setEndereco($lPEE->listar());	
			}
			
			$lP = new ListaPessoas;
			$lP->condicoes('', $info[self::IDSESSAO], ListaPessoas::ID);
			if($lP->getTotal() > 0){				
				$temp->setCliente($lP->listar());	
			}
					
			return $temp;
		
		}
		
	}
	
	public function inserir(Pedido &$p){
		
		parent::inserir($p);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::IDSESSAO.", ".self::QUANTIDADE.", ".self::OBSERVACOES.", ".self::TIPOPAGAMENTO.", ".self::VALOR.", ".self::DESCONTO.", ".self::DATA.", ".self::STATUS.") VALUES('".$p->getCliente()->getId()."','".$p->quantidade."','".$p->observacoes."','".$p->getTipoPagamento()."','".$p->calcular()->getValor()->formatar()."','".$p->getDesconto()->num."','".$p->getData()->mostrar('YmdHi')."','".$p->getStatus()->getStatus()."')");
		
		$id 	= $this->con->getId();
		
		$class 	= __CLASS__;
		$lP 	= new $class;
		$temp 	= $lP->condicoes('', $id, self::ID)->listar();
		
		$p = $temp;
		
		
		$lPEE 	= new ListaPedidoEnderecoEntregas;
		$lPEE->inserir($p->getEndereco(), $p);
		
		parent::alterar($p);
		
	}
	
	public function alterar(Pedido $p){

		$where = "WHERE ".self::ID." = '".$p->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::QUANTIDADE, 	$p->quantidade, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::OBSERVACOES, 	$p->observacoes, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPOPAGAMENTO, $p->getTipoPagamento(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA, 			$p->getData()->mostrar("YmdHi"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::STATUS, 		$p->getStatus()->getStatus(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, 		$p->getValor()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCONTO, 		$p->getDesconto()->num, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ESTOQUE, 		$p->estoque, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VENDEDOR, 		$p->getVendedor(), $where);
		
		$lPEE 	= new ListaPedidoEnderecoEntregas;
		$lPEE->condicoes('', $p->getId(), ListaPedidoEnderecoEntregas::IDSESSAO);
		
		if($lPEE->getTotal() > 0)
			$lPEE->alterar($p->getEndereco(), $p);
		else
			$lPEE->inserir($p->getEndereco(), $p);
								 
		parent::alterar($p);
		
	}
	
	public function deletar(Pedido $p){
		
		parent::deletar($p);
		
		if($p->getEndereco()->getId() != ''){
			$lPEE 	= new ListaPedidoEnderecoEntregas;
			$lPEE->deletar($p->getEndereco());
		}
		
		$where = "WHERE ".self::ID." = '".$p->getId()."'";
			
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>