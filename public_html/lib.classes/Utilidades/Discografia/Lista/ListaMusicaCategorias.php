<?php

importar("Geral.Lista");
importar("Utilidades.Discografia.MusicaCategoria");

class ListaMusicaCategorias extends Lista {
	
	const ID				= 'id';
	const TITULO			= 'titulo';
	const SUBTITULO			= 'subtitulo';
	const GRAVADORA			= 'gravadora';
	const DATALANCAMENTO	= 'datalancamento';
	const CAPA				= 'capa';
	const ORDEM				= 'ordem';
	
	public function __construct(){
		
		parent::__construct('musicas_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new MusicaCategoria($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo		= $info[self::TITULO];
			$temp->subTitulo	= $info[self::SUBTITULO];
			$temp->gravadora	= $info[self::GRAVADORA];
			$temp->ordem		= $info[self::ORDEM];
			
			$temp->setDataLancamento(new DataHora($info[self::DATALANCAMENTO]));
			
			if(!empty($info[self::CAPA]))
				$temp->setCapa(new Image(new Arquivos(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia.$info[self::CAPA])));
		
			return $temp;
		
		}
		
	}
	
	public function inserir(MusicaCategoria &$t){
		
		parent::inserir($t);
		
		if($t->getCapa()->nome != "")
			$imagem = $t->getCapa()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia);
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::SUBTITULO.", ".self::GRAVADORA.", ".self::DATALANCAMENTO.", ".self::CAPA.", ".self::ORDEM.") VALUES('".$t->titulo."','".$t->subTitulo."','".$t->gravadora."','".$t->getDataLancamento()->mostrar("Ymd")."','".$imagem."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(MusicaCategoria $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getCapa()->nome != "")
			$imagem = $t->getCapa()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataDiscografia);
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 			$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SUBTITULO, 		$t->subTitulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::GRAVADORA, 		$t->gravadora, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DATALANCAMENTO, 	$t->getDataLancamento()->mostrar("Ymd"), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CAPA, 				$imagem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM,				$t->ordem, $where);
		
	}
	
	public function deletar(MusicaCategoria $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>