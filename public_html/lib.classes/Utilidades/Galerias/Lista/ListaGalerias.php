<?php

importar("Geral.Lista");
importar("Utilidades.Galerias.Galeria");

class ListaGalerias extends Lista {
	
	const ID						= 'id';
	const TITULO					= 'titulo';
	const DESCRICAO					= 'descricao';
	const LOCAL						= 'local';
	const TIPO						= 'tipo';
	const DATA						= 'data';
	const VIDEO						= 'video';
	
	public function __construct(){
		
		parent::__construct('galerias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Galeria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo 			= $info[self::TITULO];
			$temp->descricao		= $info[self::DESCRICAO];
			$temp->local			= $info[self::LOCAL];
			$temp->tipo				= $info[self::TIPO];
			
			$temp->setData(new DataHora($info[self::DATA]));
			
			$temp->setVideo($info[self::VIDEO]);
			
			$temp->setURL($info[parent::URL]);
		
			return $temp;
		
		}
		
	}
	
	public function inserir(Galeria &$g){
		
		parent::inserir($g);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".parent::URL.", ".self::DESCRICAO.", ".self::LOCAL.", ".self::TIPO.", ".self::DATA.", ".self::VIDEO.") VALUES('".$g->titulo."','".$g->getURL()->getId()."','".$g->descricao."','".$g->local."','".$g->tipo."','".$g->getData()->mostrar("YmdHi")."','".$g->getVideo()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$g = $l->listar();
		
		parent::alterar($g);
		
	}
	
	public function alterar(Galeria $g){
		
		parent::alterar($g);
		
		$where = "WHERE ".self::ID." = '".$g->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 			$g->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 		$g->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO,		$g->descricao, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LOCAL,			$g->local, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPO, 			$g->tipo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATA, 			$g->getData()->mostrar("YmdHi"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VIDEO, 		$g->getVideo(), $where);
		//$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO, 	$p->descricao, $where);
		
	}
	
	public function deletar(Galeria $g){
		
		parent::deletar($g);
		
		$where = "WHERE ".self::ID." = '".$g->getId()."'";
		
		while($img = $g->getImagens()->listar("ASC")){
			$g->getImagens()->deletar($img);
			$g->getImagens()->setParametros(0);
		}
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>