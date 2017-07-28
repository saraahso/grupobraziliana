<?php

importar("Geral.Lista");
importar("Geral.Lista.ListaCidades");
importar("TemTudoAqui.Usuarios.Lista.ListaEnderecos");
importar("LojaVirtual.Pedidos.PedidoEnderecoEntrega");
importar("Utils.Dados.Strings");

class ListaPedidoEnderecoEntregas extends Lista {
	
	const ID			= 'id';
	const IDSESSAO		= 'ligacao';
	const LOGRADOURO	= 'logradouro';
	const NUMERO		= 'numero';
	const COMPLEMENTO	= 'complemento';
	const BAIRRO		= 'bairro';
	const CIDADE		= 'cidade';
	const ESTADO		= 'estado';
	const PAIS			= 'pais';
	const CEP			= 'cep';
	const TIPO			= 'tipo';
	const VALOR			= 'valor';
	const PRAZO			= 'prazo';
	
	public function __construct(){
		
		parent::__construct('pedido_enderecos');
		$this->enableClearCache = false;
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		//echo count($info);
		if(!empty($info)){
		
			$end = new PedidoEnderecoEntrega($info[self::ID]);
			$end->logradouro 	= $info[self::LOGRADOURO];
			$end->numero 		= $info[self::NUMERO];
			$end->complemento 	= $info[self::COMPLEMENTO];
			$end->bairro 		= $info[self::BAIRRO];
			$end->tipo 			= $info[self::TIPO];
			$end->prazo			= $info[self::PRAZO];
			
			$end->setValor($info[self::VALOR]);
			
			$end->setCep($info[self::CEP]);
			
			$lC = new ListaCidades;
			$lC->condicoes('', $info[self::CIDADE], ListaCidades::ID);
			if($lC->getTotal() > 0){
				$c = $lC->listar();
				$end->setCidade($c);
				$end->setEstado($c->getEstado());
				$end->setPais($c->getPais());
			}
			
			return $end;
			
		}
		
	}
	
	public function inserir(PedidoEnderecoEntrega &$end, Pedido $ligacao){
		
		//parent::inserir($end);
		
		$e = $end->getEstado();
		if($e->getId() <= 0){
			
			$lE = new ListaEstados;
			$lE->inserir($e);
			$end->setEstado($e);
			
		}
				
		$c = $end->getCidade();
		if($c->getId() <= 0){
			
			$lC = new ListaCidades;
			$lC->inserir($c);
			$end->setCidade($c);
			
		}else{
			
			$lC = new ListaCidades;
			$lC->condicoes('', $c->getId(), ListaCidades::ID);
			if($lC->getTotal() <= 0){
				if($c->getPais()->getId() <= 0)
					$c->setPais($end->getPais());
				if($c->getEstado()->getId() <= 0)
					$c->setEstado($end->getEstado());
				$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$lC->getTabela()."(".ListaCidades::ID.", ".ListaCidades::PAIS.", ".ListaCidades::ESTADO.", ".ListaCidades::NOME.", ".ListaCidades::DDD.") VALUES('".$c->getId()."','".$c->getPais()->getId()."','".$c->getEstado()->getId()."','".$c->nome."','".$c->ddd."')");
				$id = $this->con->getId();
				$lC->condicoes('', $id, ListaCidades::ID);
				if($lC->getTotal() > 0)
					$end->setCidade($lC->listar());
			}
			
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::IDSESSAO.", ".self::LOGRADOURO.", ".self::NUMERO.", ".self::COMPLEMENTO.", ".self::BAIRRO.", ".self::CIDADE.", ".self::ESTADO.", ".self::PAIS.", ".self::CEP.", ".self::TIPO.", ".self::VALOR.", ".self::PRAZO.") VALUES('".$ligacao->getId()."','".$end->logradouro."','".$end->numero."','".$end->complemento."','".$end->bairro."','".$end->getCidade()->getId()."','".$end->getEstado()->getId()."','".$end->getPais()->getId()."','".$end->getCep()."','".$end->tipo."','".$end->getValor()->formatar()."','".$end->prazo."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$end = $l->listar();
		
		//parent::alterar($end);
		
	}
	
	public function alterar(PedidoEnderecoEntrega $end, Pedido $ligacao){
		
		//parent::alterar($end);
		$where = "WHERE ".self::IDSESSAO." = '".$ligacao->getId()."'";
		
		$e = $end->getEstado();
		if($e->getId() <= 0){
			
			$lE = new ListaEstados;
			$lE->inserir($e);
			$end->setEstado($e);
			
		}
				
		$c = $end->getCidade();
		if($c->getId() <= 0){
			
			$lC = new ListaCidades;
			$lC->inserir($c);
			$end->setCidade($c);
			
		}else{
			
			$lC = new ListaCidades;
			$lC->condicoes('', $c->getId(), ListaCidades::ID);
			if($lC->getTotal() <= 0){
				if($c->getPais()->getId() <= 0)
					$c->setPais($end->getPais());
				if($c->getEstado()->getId() <= 0)
					$c->setEstado($end->getEstado());
				$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$lC->getTabela()."(".ListaCidades::ID.", ".ListaCidades::PAIS.", ".ListaCidades::ESTADO.", ".ListaCidades::NOME.", ".ListaCidades::DDD.") VALUES('".$c->getId()."','".$c->getPais()->getId()."','".$c->getEstado()->getId()."','".$c->nome."','".$c->ddd."')");
				$id = $this->con->getId();
				$end->setCidade(new Cidade($id));
			}
			
		}		
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOGRADOURO, 	$end->logradouro, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NUMERO, 		$end->numero, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COMPLEMENTO, 	$end->complemento, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::BAIRRO, 		$end->bairro, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CIDADE, 		$end->getCidade()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ESTADO, 		$end->getEstado()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PAIS, 			$end->getPais()->getId(), $where);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CEP, 			$end->getCep(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPO, 			$end->tipo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PRAZO,			$end->prazo, $where);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VALOR, 		$end->getValor()->formatar(), $where);
		
	}
	
	public function deletar(PedidoEnderecoEntrega $end){
		
		//parent::deletar($end);
		
		$where = "WHERE ".self::ID." = '".$end->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>