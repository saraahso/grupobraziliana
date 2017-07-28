<?php

importar("Geral.Lista");
importar("TemTudoAqui.Usuarios.Email");
importar("Utils.Dados.Strings");

class ListaEmails extends Lista {
	
	const ID					= 'id';
	const PESSOA 				= 'pessoa';
	const DESCRICAO				= 'descricao';
	const PRINCIPAL				= 'principal';
	const EMAIL					= 'email';
	
	const VALOR_PRINCIPAL_TRUE	= 1;
	const VALOR_PRINCIPAL_FALSE	= 0;
	
	public function __construct($tabela = 'pessoas_emails'){
		
		parent::__construct($tabela);
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){
		
			$email = new Email($info[self::ID]);
			$email->descricao 	= $info[self::DESCRICAO];
			$email->email 		= $info[self::EMAIL];
			$email->principal	= $info[self::PRINCIPAL] == self::VALOR_PRINCIPAL_TRUE ? true : false;
			
			return $email;
		
		}
		
	}
	
	public function inserir(Email &$email, $ligacao){
		
		parent::inserir($email);
			
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PESSOA.", ".self::DESCRICAO.", ".self::EMAIL.", ".self::PRINCIPAL.") VALUES('".$ligacao->getId()."','".$email->descricao."','".$email->email."','".($email->principal ? self::VALOR_PRINCIPAL_TRUE : self::VALOR_PRINCIPAL_FALSE)."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$email = $l->listar();
		
		parent::alterar($email);
		
	}
	
	public function alterar(Email $email){
		
		parent::alterar($email);
		
		$where = "WHERE ".self::ID." = '".$email->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO, 	$email->descricao, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMAIL, 		$email->email, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PRINCIPAL, 	$email->principal ? self::VALOR_PRINCIPAL_TRUE : self::VALOR_PRINCIPAL_FALSE, $where);
		
	}
	
	public function deletar(Email $email){
		
		parent::deletar($email);
		
		$where = "WHERE ".self::ID." = '".$email->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>