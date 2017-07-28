<?php

importar("Geral.Lista");
importar("Utilidades.Evento");

class ListaEventos extends Lista {
	
	const ID						= 'id';
	const DATA						= 'data';
	const LOCAL						= 'local';

	
	public function __construct(){
		
		parent::__construct('eventos');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Evento($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->setData(new DataHora($info[self::DATA]));
			
			$temp->setURL($info[parent::URL]);
			
			$temp->setTexto($info[parent::TEXTO]);
			
			$temp->local = $info[self::LOCAL];
		
			return $temp;
		
		}
		
	}
	
	public function inserir(Evento &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".parent::URL.", ".parent::TEXTO.", ".self::DATA.", ".self::LOCAL.") VALUES('".$t->getURL()->getId()."','".$t->getTexto()->getId()."','".$t->getData()->mostrar("Ymd")."','".$t->local."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Evento $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 			$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO,			$t->getTexto()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA, 			$t->getData()->mostrar("Ymd"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOCAL,			$t->local, $where);
		
	}
	
	public function deletar(Evento $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>