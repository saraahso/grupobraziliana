<?php

importar("Geral.Lista");
importar("Utilidades.Publicidades.Slides.Slide");

class ListaSlides extends Lista {
	
	const ID				= 'id';
	const TITULO			= 'titulo';
	const IMAGEM			= 'imagem';
	const FLASH				= 'flash';
	const ENDERECOURL		= 'enderecourl';
	const ATIVO				= 'ativo';
	const TIPO				= 'tipo';
	const SEGUNDOS			= 'segundos';
	const LEGENDA			= 'legenda';
	const CORFUNDO			= 'corfundo';
	const ORDEM				= 'ordem';
	
	const VALOR_ATIVO_TRUE	= 1;
	const VALOR_ATIVO_FALSE	= 0;
	
	public function __construct(){
		
		parent::__construct('slides');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp 			= new Slide($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo		= $info[self::TITULO];
			$temp->legenda		= $info[self::LEGENDA];
			$temp->enderecoURL	= $info[self::ENDERECOURL];
			$temp->ativo		= $info[self::ATIVO] == self::VALOR_ATIVO_TRUE ? true : false;
			$temp->tipo			= $info[self::TIPO];
			$temp->segundos		= $info[self::SEGUNDOS];
			$temp->corfundo		= $info[self::CORFUNDO];
			$temp->ordem		= $info[self::ORDEM];
			
			if(!empty($info[self::IMAGEM]))
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataSlides.$info[self::IMAGEM])));
				
			$temp->setFlash($info[self::FLASH]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Slide &$t){
		
		parent::inserir($t);
		
		if($t->getImagem()->nome != ''){
			$t->getImagem()->open();
			$imagem = $t->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides);
		}
		
		$ativo = $t->ativo ? self::VALOR_ATIVO_TRUE : self::VALOR_ATIVO_FALSE;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::TITULO.", ".self::IMAGEM.", ".self::FLASH.", ".self::SEGUNDOS.", ".self::ENDERECOURL.", ".self::ATIVO.", ".self::TIPO.", ".self::LEGENDA.", ".self::CORFUNDO.", ".self::ORDEM.") VALUES('".$t->titulo."','".$imagem."','".$t->getFlash()."','".$t->segundos."','".$t->enderecoURL."','".$ativo."','".$t->tipo."','".$t->legenda."','".$t->corfundo."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
	}
	
	public function alterar(Slide $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getImagem()->nome != ''){
			$t->getImagem()->open();
			$imagem = $t->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides);
		}
		
		$ativo = $t->ativo ? self::VALOR_ATIVO_TRUE : self::VALOR_ATIVO_FALSE;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 		$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 		$imagem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::FLASH, 		$t->getFlash(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SEGUNDOS, 		$t->segundos, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ENDERECOURL, 	$t->enderecoURL, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ATIVO, 		$ativo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TIPO, 			$t->tipo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LEGENDA, 		$t->legenda, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CORFUNDO, 		$t->corfundo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 		$t->ordem, $where);
		
	}
	
	public function deletar(Slide $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides.$t->getImagem()->nome.".".$t->getImagem()->extensao);
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataSlides.$t->getFlash());
			
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}

?>