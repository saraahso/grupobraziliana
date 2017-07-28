<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Banners.BannerCategoria");

class ListaBannerCategorias extends Lista {
	
	const ID		= 'id';
	const TITULO	= 'titulo';
	const LARGURA	= 'largura';
	const ALTURA	= 'altura';
	
	public function __construct(){
		
		parent::__construct('banners_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new BannerCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
			$temp->setLargura($info[self::LARGURA]);
			$temp->setAltura($info[self::ALTURA]);
		
			return $temp;
		
		}
		
	}
	
	public function inserir(BannerCategoria &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::LARGURA.", ".self::ALTURA.") VALUES('".$t->titulo."','".$t->getLargura()->formatar()."','".$t->getAltura()->formatar()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(BannerCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 	$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LARGURA, 	$t->getLargura()->formatar(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ALTURA, 	$t->getAltura()->formatar(), $where);
		
	}
	
	public function deletar(BannerCategoria $t){
		
		if($t->getBanners()->getTotal() > 0)
			throw new Exception("Está categoria possui banners cadastrados, não foi possível removê-la!");
		else{
		
			parent::deletar($t);
		
			$where = "WHERE ".self::ID." = '".$t->getId()."'";
			
			$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);

		}
		
	}
	
}

?>