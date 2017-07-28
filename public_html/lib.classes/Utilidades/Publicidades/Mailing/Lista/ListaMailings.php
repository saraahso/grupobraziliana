<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Mailing.Mailing");
importar("Utilidades.Publicidades.Mailing.Lista.ListaPacoteMailings");

class ListaMailings extends Lista {
	
	const ID					= 'id';
	const PACOTE				= 'pacote';
	const STATUS				= 'status';
	const DATA					= 'data';
	
	public function __construct(){
		
		parent::__construct('mailing');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Mailing($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->setTexto($info[parent::TEXTO]);
			
			$temp->setStatus($info[self::STATUS]);
			
			$temp->setData(new DataHora($info[self::DATA]));
			
			$lPM = new ListaPacoteMailings;
			$lPM->condicoes('', $info[self::PACOTE], ListaPacoteMailings::ID);
			if($lPM->getTotal() > 0)
				$temp->setPacote($lPM->listar());
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Mailing &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::PACOTE.", ".parent::TEXTO.", ".self::STATUS.", ".self::DATA.") VALUES('".$t->getPacote()->getId()."','".$t->getTexto()->getId()."','".$t->getStatus()."','".$t->getData()->mostrar("YmdHi")."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Mailing $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::STATUS,		$t->getStatus(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA,			$t->getData()->mostrar("YmdHi"), $where);
		
	}
	
	public function deletar(Mailing $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		$this->con->deletar(Sistema::$BDPrefixo."mailing_pacotes_envio", "WHERE mailing = '".$t->getId()."'");
		
	}
	
}

?>