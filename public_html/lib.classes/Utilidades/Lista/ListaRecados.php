<?php

importar("Geral.Lista");
importar("Utilidades.Recado");

class ListaRecados extends Lista {
	
	const ID						= 'id';
	const SESSAO					= 'sessao';
	const IDSESSAO					= 'idsessao';
	const DATA						= 'data';
	const LOCAL						= 'local';
	const NOME						= 'nome';
	const EMAIL						= 'email';
	const LIBERADO					= 'liberado';
	
	const VALOR_LIBERADO_TRUE 		= 1;
	const VALOR_LIBERADO_FALSE 		= 0;
	
	public function __construct(){
		
		parent::__construct('recados');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Recado($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->setData(new DataHora($info[self::DATA]));
			
			$temp->setTexto($info[parent::TEXTO]);
			
			$temp->local 	= $info[self::LOCAL];
			
			$temp->nome 	= $info[self::NOME];
			
			$temp->email 	= $info[self::EMAIL];
			
			$temp->setSessao($info[self::SESSAO], $info[self::IDSESSAO]);
			
			if($info[self::LIBERADO] == self::VALOR_LIBERADO_TRUE)
				$temp->liberar();
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Recado &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::SESSAO.", ".self::IDSESSAO.", ".parent::TEXTO.", ".self::DATA.", ".self::LOCAL.", ".self::NOME.", ".self::EMAIL.", ".self::LIBERADO.") VALUES('".$t->getSessao()."','".$t->getIdSessao()."','".$t->getTexto()->getId()."','".$t->getData()->mostrar("YmdHi")."','".$t->local."','".$t->nome."','".$t->email."','".($t->getSituacao() ? self::VALOR_LIBERADO_TRUE : self::VALOR_LIBERADO_FALSE)."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Recado $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SESSAO,		$t->getSessao(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IDSESSAO,		$t->getIdSessao(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO,			$t->getTexto()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA, 			$t->getData()->mostrar("YmdHi"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOCAL,			$t->local, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME,			$t->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::EMAIL,			$t->email, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LIBERADO,		$t->getSituacao() ? self::VALOR_LIBERADO_TRUE : self::VALOR_LIBERADO_FALSE, $where);
		
	}
	
	public function deletar(Recado $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>