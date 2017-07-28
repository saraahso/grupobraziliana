<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Mailing.PacoteMailing");

class ListaPacoteMailings extends Lista {
	
	const ID						= 'id';
	const TITULO					= 'titulo';
	
	public function __construct(){
		
		parent::__construct('mailing_pacotes');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new PacoteMailing($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
			
			return $temp;
		
		}
		
	}
	
	public function inserir(PacoteMailing &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.") VALUES('".$t->titulo."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(PacoteMailing $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO,		$t->titulo, $where);
		
	}
	
	public function deletar(PacoteMailing $t){
		
		//parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		$this->con->deletar(Sistema::$BDPrefixo."mailing_pacotes_emails", "WHERE pacote = '".$t->getId()."'");
		
	}
	
}

?>