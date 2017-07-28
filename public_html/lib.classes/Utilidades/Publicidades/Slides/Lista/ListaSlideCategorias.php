<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Slides.SlideCategoria");

class ListaSlideCategorias extends Lista {
	
	const ID		= 'id';
	const TITULO	= 'titulo';
	
	public function __construct(){
		
		parent::__construct('slides_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new SlideCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
		
			return $temp;
		
		}
		
	}
	
	public function inserir(SlideCategoria &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.") VALUES('".$t->titulo."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(SlideCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 	$t->titulo, $where);
		
	}
	
	public function deletar(SlideCategoria $t){
		
		if($t->getBanners()->getTotal() > 0)
			throw new Exception("Está categoria possui slides cadastrados, não foi possível removê-la!");
		else{
		
			parent::deletar($t);
		
			$where = "WHERE ".self::ID." = '".$t->getId()."'";
			
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);

		}
		
	}
	
}

?>