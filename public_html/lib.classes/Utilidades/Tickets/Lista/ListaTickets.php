<?php

importar("Geral.Lista");
importar("Utils.Dados.DataHora");
importar("Utilidades.Tickets.Ticket");
importar("TemTudoAqui.Usuarios.Lista.ListaPessoas");

class ListaTickets extends Lista {
	
	const ID					= 'id';
	const CLIENTE				= 'cliente';
	const TITULO				= 'titulo';
	const NIVEL					= 'nivel';
	const STATUS				= 'status';
	const SATISFACAO			= 'satisfacao';
	const DATAHORA_CRIACAO		= 'datahora_criacao';
	const DATAHORA_ALTERACAO	= 'datahora_alteracao';
	
	public function __construct(){
		
		parent::__construct('tickets');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Ticket($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$lP = new ListaPessoas;
			$lP->condicoes('', $info[self::CLIENTE], ListaPessoas::ID);
			if($lP->getTotal() > 0) $temp->setCliente($lP->listar());
			
			$temp->setNivel($info[self::NIVEL]);
			
			$temp->setStatus($info[self::STATUS]);
			
			$temp->setSatisfacao($info[self::SATISFACAO]);
			
			$temp->titulo = $info[self::TITULO];
			
			$temp->setDataAlteracao(new DataHora($info[self::DATAHORA_ALTERACAO]));
		
			return $temp;
		
		}
		
	}
	
	public function inserir(Ticket &$t){
		
		parent::inserir($t);
		
		$dT = new DataHora;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::CLIENTE.", ".self::TITULO.", ".self::NIVEL.", ".self::STATUS.", ".self::SATISFACAO.", ".self::DATAHORA_CRIACAO.", ".self::DATAHORA_ALTERACAO.") VALUES('".$t->getCliente()->getId()."','".$t->titulo."','".$t->getNivel()."','".$t->getStatus()."','".$t->getSatisfacao()."','".$dT->mostrar("YmdHi")."','".$dT->mostrar("YmdHi")."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Ticket $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$dT = new DataHora;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NIVEL,					$t->getNivel(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::STATUS, 				$t->getStatus(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SATISFACAO,			$t->getSatisfacao(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATAHORA_ALTERACAO,	$dT->mostrar("YmdHi"), $where);
		
	}
	
	public function deletar(Ticket $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		while($tP = $t->getPostagens()->listar()){
			
			$t->getPostagens()->deletar($tP);
			$t->getPostagens()->setParametros(0);
			
		}
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>