<?php

importar("Geral.Lista");
importar("TemTudoAqui.Usuarios.Telefone");
importar("Utils.Dados.Strings");

class ListaTelefones extends Lista {
	
	const ID		= 'id';
	const IDSESSAO 	= 'ligacao';
	const LOCAL		= 'local';
	const DDD		= 'ddd';
	const TELEFONE	= 'telefone';
	const RAMAL		= 'ramal';
	
	public function __construct($tabela = 'telefones'){
		
		parent::__construct($tabela);
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
		
			$tel = new Telefone($info[self::ID]);
			$tel->local = $info[self::LOCAL];
			$tel->ddd = $info[self::DDD];
			$tel->telefone = $info[self::TELEFONE];
			$tel->ramal = $info[self::RAMAL];
			
			return $tel;
		
		}
		
	}
	
	public function inserir(Telefone &$tel, $ligacao){
		
		parent::inserir($tel);
			
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::IDSESSAO.", ".self::LOCAL.", ".self::DDD.", ".self::TELEFONE.", ".self::RAMAL.") VALUES('".$ligacao->getId()."','".$tel->local."','".$tel->ddd."','".$tel->telefone."','".$tel->ramal."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$tel = $l->listar();
		
		parent::alterar($tel);
		
	}
	
	public function alterar(Telefone $tel){
		
		parent::alterar($tel);
		
		$where = "WHERE ".self::ID." = '".$tel->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOCAL, 		$tel->local, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DDD, 		$tel->ddd, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TELEFONE, 	$tel->telefone, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::RAMAL, 		$tel->ramal, $where);
		
	}
	
	public function deletar(Telefone $tel){
		
		parent::deletar($tel);
		
		$where = "WHERE ".self::ID." = '".$tel->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>