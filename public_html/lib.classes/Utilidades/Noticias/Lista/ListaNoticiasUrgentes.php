<?php

importar("Geral.Lista");
importar("Utilidades.Noticias.NoticiaUrgente");

class ListaNoticiasUrgentes extends Lista {
	
	const ID						= 'id';
	const NOTICIA					= 'noticia';
	const ORDEM						= 'ordem';
	const DATA						= 'data';
	
	public function __construct(){
		
		parent::__construct('noticias_urgentes');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
			
			$temp = new NoticiaUrgente($info[self::ID]);
			
			$temp->noticia = $info[self::NOTICIA];
			$temp->ordem = $info[self::ORDEM];
			$temp->setData(new DataHora($info[self::DATA]));
		
			return $temp;
			
		}
		
	}
	
	public function inserir(NoticiaUrgente &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOTICIA.", ".self::ORDEM.", ".self::DATA.") VALUES('".$t->noticia."','".$t->ordem."','".$t->getData()->mostrar('YmdHi')."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(NoticiaUrgente $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOTICIA,	$t->noticia, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM,		$t->ordem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA,		$t->getData()->mostrar("YmdHi"), $where);
		
	}
	
	public function deletar(NoticiaUrgente $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>