<?php

importar("Geral.Lista");
importar("Utilidades.Noticias.Noticia");

class ListaNoticias extends Lista {
	
	const ID						= 'id';
	const DATA						= 'data';

	
	public function __construct(){
		
		parent::__construct('noticias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
			
			$temp = new Noticia($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->setData(new DataHora($info[self::DATA]));
			
			$temp->setURL($info[parent::URL]);
			
			$temp->setTexto($info[parent::TEXTO]);
		
			return $temp;
			
		}
		
	}
	
	public function inserir(Noticia &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".parent::URL.", ".parent::TEXTO.", ".self::DATA.") VALUES('".$t->getURL()->getId()."','".$t->getTexto()->getId()."','".$t->getData()->mostrar("YmdHi")."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Noticia $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 			$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO,			$t->getTexto()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA, 			$t->getData()->mostrar("YmdHi"), $where);
		
	}
	
	public function deletar(Noticia $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>