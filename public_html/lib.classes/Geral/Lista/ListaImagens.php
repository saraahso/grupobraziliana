<?php

importar("Geral.Lista");
importar("Geral.Imagem");
importar("Geral.URL");

class ListaImagens extends Lista {
	
	const ID					= 'id';
	const SESSAO				= 'sessao';
	const IDSESSAO				= 'idsessao';
	const IMAGEM				= 'imagem';
	const LEGENDA				= 'legenda';
	const DESTAQUE				= 'destaque';
	
	const VALOR_DESTAQUE_TRUE 	= 1;
	const VALOR_DESTAQUE_FALSE 	= 0;
	
	public $caminhoEscrita 		= '';
	public $caminhoLeitura 		= '';
	
	public function __construct(){
		
		parent::__construct('imagens');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
			
			$temp = new Imagem($info[self::ID]);
			$temp->legenda 		= $info[self::LEGENDA];
			$temp->destaque		= $info[self::DESTAQUE] == self::VALOR_DESTAQUE_TRUE ? true : false;
			
			$temp->setSessao($info[self::SESSAO], $info[self::IDSESSAO]);			
			
			if(!empty($info[self::IMAGEM])){
				$arquivo 	= new Arquivos($this->caminhoLeitura.$info[self::IMAGEM]);
				$img		= new Image($arquivo);
				$temp->setImage($img);
			}
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Imagem &$img){
		
		if(!empty($img->getImage()->nome)){
			$img->getImage()->open();
			$imagem = $img->getImage()->saveImage($this->caminhoEscrita);
		}
		
		$principal = $img->destaque ? self::VALOR_DESTAQUE_TRUE : self::VALOR_DESTAQUE_FALSE;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::SESSAO.", ".self::IDSESSAO.", ".self::LEGENDA.", ".self::IMAGEM.", ".self::DESTAQUE.") VALUES('".$img->getSessao()."','".$img->getIdSessao()."','".$img->legenda."','".$imagem."','".$principal."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->caminhoEscrita = $this->caminhoEscrita;
		$l->caminhoLeitura = $this->caminhoLeitura;
		$l->condicoes('', $id, self::ID);
		
		$img = $l->listar();
		
	}
	
	public function alterar(Imagem $img){
		
		$where = "WHERE ".self::ID." = '".$img->getId()."'";
		
		$principal = $img->destaque ? self::VALOR_DESTAQUE_TRUE : self::VALOR_DESTAQUE_FALSE;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESTAQUE, 	$principal, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::LEGENDA, 	$img->legenda, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SESSAO, 	$img->getSessao(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IDSESSAO, 	$img->getIdSessao(), $where);
		
		if($img->getImage()->nome != ''){
			$img->getImage()->open();
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, $img->getImage()->saveImage($this->caminhoEscrita), $where);
		}
		
	}
	
	public function deletar(Imagem $img){
		
		$where = "WHERE ".self::ID." = '".$img->getId()."'";
		
		Arquivos::__DeleteArquivo($this->caminhoEscrita.$img->getImage()->nome.'.'.$img->getImage()->extensao);
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>