<?php

importar("Geral.Lista");
importar("Geral.Cidade");
importar("Geral.Lista.ListaPaises");
importar("Geral.Lista.ListaEstados");

class ListaCidades extends Lista {
	
	const ID			= 'id';
	const PAIS			= 'pais';
	const ESTADO		= 'estado';
	const NOME			= 'nome';
	const DDD			= 'ddd';
	
	public function __construct(){
		
		parent::__construct('cidade');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Cidade($info[self::ID]);
			
			$temp->nome			= $info[self::NOME];
			$temp->ddd			= $info[self::DDD];
			
			$lP = new ListaPaises;
			$lP->condicoes('', $info[self::PAIS], ListaPaises::ID);
			if($lP->getTotal() > 0)
				$temp->setPais($lP->listar());
				
			$lE = new ListaEstados;
			$lE->condicoes('', $info[self::ESTADO], ListaEstados::ID);
			if($lE->getTotal() > 0)
				$temp->setEstado($lE->listar());
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Cidade &$obj){
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PAIS.", ".self::ESTADO.", ".self::NOME.", ".self::DDD.") VALUES('".$obj->getPais()->getId()."','".$obj->getEstado()->getId()."','".$obj->nome."','".$obj->ddd."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$obj = $l->listar();
		
	}
	
	public function alterar(Cidade $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PAIS, 		$obj->getEstado()->getPais()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ESTADO, 	$obj->getEstado()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 		$obj->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DDD,		$obj->ddd, $where);	
		
	}
	
	public function deletar(Cidade $obj){
		
		$where = "WHERE ".self::ID." = '".$obj->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>