<?php

importar("Geral.Lista");
importar("TemTudoAqui.Usuarios.Endereco");
importar("Utils.Dados.Strings");
importar("Geral.Lista.ListaEstados");
importar("Geral.Lista.ListaCidades");

class ListaEnderecos extends Lista {
	
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
	
	public function __construct($tabela = 'enderecos'){
		
		parent::__construct($tabela);
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
		
			$end = new Endereco($info[self::ID]);
			$end->logradouro 	= $info[self::LOGRADOURO];
			$end->numero 		= $info[self::NUMERO];
			$end->complemento 	= $info[self::COMPLEMENTO];
			$end->bairro 		= $info[self::BAIRRO];
			
			$lC = new ListaCidades;
			$lC->condicoes('', $info[self::CIDADE], ListaCidades::ID);
			if($lC->getTotal() > 0){
				$c = $lC->listar();
				$end->setCidade($c);
				$end->setEstado($c->getEstado());
				$end->setPais($c->getPais());
			}
			
			$end->setCep($info[self::CEP]);
			
			return $end;
			
		}
		
	}
	
	public function inserir(Endereco &$end, $ligacao){
		
		parent::inserir($end);
		
		$e = $end->getEstado();
		if($e->getId() <= 0){
			
			$lE = new ListaEstados;
			$lE->inserir($e);
			$end->setEstado($e);
			
		}
				
		$end->getCidade()->setEstado($e);
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
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::IDSESSAO.", ".self::LOGRADOURO.", ".self::NUMERO.", ".self::COMPLEMENTO.", ".self::BAIRRO.", ".self::CIDADE.", ".self::ESTADO.", ".self::PAIS.", ".self::CEP.") VALUES('".$ligacao->getId()."','".$end->logradouro."','".$end->numero."','".$end->complemento."','".$end->bairro."','".$end->getCidade()->getId()."','".$end->getEstado()->getId()."','".$end->getPais()->getId()."','".$end->getCep()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$end = $l->listar();
		
		parent::alterar($end);
		
	}
	
	public function alterar(Endereco $end){
		
		parent::alterar($end);
		
		$where = "WHERE ".self::ID." = '".$end->getId()."'";
		
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
		
	}
	
	public function deletar(Endereco $end){
		
		parent::deletar($end);
		
		$where = "WHERE ".self::ID." = '".$end->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>