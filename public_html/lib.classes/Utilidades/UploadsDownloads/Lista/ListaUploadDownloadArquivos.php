<?php

importar("Geral.Lista");
importar("Utilidades.UploadsDownloads.UploadDownloadArquivo");

class ListaUploadDownloadArquivos extends Lista {
	
	const ID		= 'id';
	const ARQUIVO	= 'arquivo';
	const PRODUTOS	= 'produtos';
	const ORDEM		= 'ordem';
	
	public function __construct(){
		
		parent::__construct('arquivos');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new UploadDownloadArquivo($info[self::ID]);
			
			$temp->produtos	= $info[self::PRODUTOS];
			$temp->ordem	= $info[self::ORDEM];
			
			$temp->setArquivo(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataUploadsDownloads.$info[self::ARQUIVO]));
		
			return $temp;
		
		}
		
	}
	
	public function inserir(UploadDownloadArquivo &$t){
		
		parent::inserir($t);
		
		if($t->getArquivo()->nome != ''){
			$t->getArquivo()->open();
			$arquivo = $t->getArquivo()->nome.".".$t->getArquivo()->extensao;
			$t->getArquivo()->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataUploadsDownloads);
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ARQUIVO.", ".self::PRODUTOS.", ".self::ORDEM.") VALUES('".$arquivo."','".$t->produtos."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(UploadDownloadArquivo $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::PRODUTOS,	$t->produtos, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM,		$t->ordem, $where);
		
	}
	
	public function deletar(UploadDownloadArquivo $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$arquivo = $t->getArquivo()->nome.".".$t->getArquivo()->extensao;
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataUploadsDownloads.$arquivo);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>