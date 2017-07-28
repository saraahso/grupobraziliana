<?php

importar("Geral.Lista");
importar("Utilidades.Discografia.Musica");

class ListaMusicas extends Lista {
	
	const ID		= 'id';
	const TITULO	= 'titulo';
	const MUSICA	= 'musica';
	const ORDEM		= 'ordem';
	
	public function __construct(){
		
		parent::__construct('musicas');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Musica($info[self::ID]);
			$temp->titulo	= $info[self::TITULO];
			$temp->ordem	= $info[self::ORDEM];
			
			$temp->setMusica(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataDiscografia.$info[self::MUSICA]));
		
			return $temp;
		
		}
		
	}
	
	public function inserir(Musica &$t){
		
		parent::inserir($t);
		
		if($t->getMusica()->nome != ''){
			$arquivo = $t->getMusica()->nome.".".$t->getMusica()->extensao;
			$t->getMusica()->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia);
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::MUSICA.", ".self::ORDEM.") VALUES('".$t->titulo."','".$arquivo."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Musica $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getMusica()->nome != "")
			$arquivo = $t->getMusica()->saveArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO,	$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::MUSICA,	$arquivo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM,		$t->ordem, $where);
		
		
		
	}
	
	public function deletar(Musica $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$arquivo = $t->getMusica()->nome.".".$t->getMusica()->extensao;
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia.$arquivo);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>