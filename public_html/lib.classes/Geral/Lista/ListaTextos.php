<?php

importar("Geral.Lista");
importar("Geral.Texto");

class ListaTextos extends Lista {
	
	const ID			= 'id';
	const URL			= 'url';
	const TITULO		= 'titulo';
	const SUBTITULO		= 'subtitulo';
	const TEXTOPEQUENO	= 'textopequeno';
	const TEXTO			= 'texto';
	const ORDEM			= 'ordem';
	const IMAGEM		= 'imagem';
	
	public function __construct(){
		
		parent::__construct('textos');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Texto($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo		= $info[self::TITULO];
			$temp->subTitulo	= $info[self::SUBTITULO];
			$temp->textoPequeno	= $info[self::TEXTOPEQUENO];
			$temp->texto		= $info[self::TEXTO];
			$temp->ordem		= $info[self::ORDEM];		
			
			if(!empty($info[self::IMAGEM])){
				
				$lI = new ListaImagens;
				$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
				$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
				$lI->condicoes('', $info[self::IMAGEM], ListaImagens::ID);
				
				if($lI->getTotal() > 0)
					$temp->setImagem($lI->listar());
					
			}
			
			$temp->setURL($info[parent::URL]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Texto &$t){
		
		parent::inserir($t);
		
		if($t->getImagem()){
			if($t->getImagem()->getImage()){
				if($t->getImagem()->getImage()->nome != ''){
				
					$lI 	= new ListaImagens;
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
					$img 	= $t->getImagem();
					$lI->inserir($img);
					$t->setImagem($img);
					
				}
			}
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::URL.", ".self::TITULO.", ".self::SUBTITULO.", ".self::TEXTOPEQUENO.", ".self::TEXTO.", ".self::IMAGEM.", ".self::ORDEM.") VALUES('".$t->getURL()->getId()."','".$t->titulo."','".$t->subTitulo."','".$t->textoPequeno."','".$t->texto."','".$t->getImagem()->getId()."','".$t->ordem."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$t = $l->listar();
		
		parent::alterar($t);
		
		if($t->getImagem()){
			if($t->getImagem()->getImage()){
				if($t->getImagem()->getImage()->nome != ''){
					$lI 	= new ListaImagens;
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
					$t->getImagem()->setSessao($this->getTabela(), $t->getId());
					
					$img = $t->getImagem();
					$lI->alterar($img);
					$t->setImagem($img);
				}
			}
		}
		
	}
	
	public function alterar(Texto $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getImagem()->getImage()->nome != ''){
			
			$lI 	= new ListaImagens;
			$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataTextos;
			$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataTextos;
			$img 	= $t->getImagem();
			
			if($img->getId() != '')
				$lI->alterar($img);
			else{
				$t->getImagem()->setSessao($this->getTabela(), $t->getId());
				$lI->inserir($img);
			}
			
			$t->setImagem($img);
			
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 			$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM,		$t->getImagem()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 		$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SUBTITULO,		$t->subTitulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTOPEQUENO,	str_replace("'", "\'", $t->textoPequeno), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO, 		str_replace("'", "\'", $t->texto), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 		$t->ordem, $where);		
		
	}
	
	public function deletar(Texto $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$lI = new ListaImagens;
		
		$lI->deletar($t->getImagem());
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>