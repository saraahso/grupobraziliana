<?php

importar("Geral.Lista");
importar("Utilidades.UploadsDownloads.UploadDownloadCategoria");

class ListaUploadDownloadCategorias extends Lista {
	
	const ID		= 'id';
	const TITULO	= 'titulo';
	const ORDEM		= 'ordem';
	
	public function __construct(){
		
		parent::__construct('arquivos_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new UploadDownloadCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
			$temp->ordem	= $info[self::ORDEM];
		
			return $temp;
		
		}
		
	}
	
	public function inserir(UploadDownloadCategoria &$t){
		
		parent::inserir($t);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::ORDEM.") VALUES('".$t->titulo."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(UploadDownloadCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 	$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM,		$t->ordem, $where);
		
	}
	
	public function deletar(UploadDownloadCategoria $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>