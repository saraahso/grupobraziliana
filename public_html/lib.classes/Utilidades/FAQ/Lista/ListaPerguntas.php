<?php

importar("Geral.Lista");
importar("Utilidades.FAQ.Pergunta");

class ListaPerguntas extends Lista {
	
	const ID			= 'id';
	const CATEGORIA		= 'idcategoria';
	const TITULO		= 'titulo';
	const TEXTO			= 'texto';
	const ORDEM			= 'ordem';
	const IMAGEM		= 'imagem';
	
	public function __construct(){
		
		parent::__construct('perguntas');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 
		
			$temp = new Pergunta($info[self::ID]);
			
			parent::resgatarObjetos($info);
			
			$temp->titulo	= $info[self::TITULO];
			$temp->texto	= $info[self::TEXTO];
			$temp->ordem	= $info[self::ORDEM];		
			
			if(!empty($info[self::IMAGEM])){
				
				$lI = new ListaImagens;
				$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataFAQ;
				$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataFAQ;
				$lI->condicoes('', $info[self::IMAGEM], ListaImagens::ID);
				
				if($lI->getTotal() > 0)
					$temp->setImagem($lI->listar());
					
			}
			
			$temp->setURL($info[parent::URL]);
			$temp->setIdCategoria($info[self::CATEGORIA]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(Pergunta &$t){
		
		parent::inserir($t);
		
		if($t->getImagem()){
			if($t->getImagem()->getImage()){
				if($t->getImagem()->getImage()->nome != ''){
				
					$lI 	= new ListaImagens;
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataFAQ;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataFAQ;
					$img 	= $t->getImagem();
					$lI->inserir($img);
					$t->setImagem($img);
					
				}
			}
		}
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::CATEGORIA.", ".self::URL.", ".self::TITULO.", ".self::TEXTO.", ".self::IMAGEM.", ".self::ORDEM.") VALUES('".$t->getIdCategoria()."','".$t->getURL()->getId()."','".$t->titulo."','".$t->texto."','".$t->getImagem()->getId()."','".$t->ordem."')");
		
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
					$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataFAQ;
					$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataFAQ;
					$t->getImagem()->setSessao($this->getTabela(), $t->getId());
					
					$img = $t->getImagem();
					$lI->alterar($img);
					$t->setImagem($img);
				}
			}
		}
		
	}
	
	public function alterar(Pergunta $t){
		
		parent::alterar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		if($t->getImagem()->getImage()->nome != ''){
			
			$lI 	= new ListaImagens;
			$lI->caminhoEscrita = Sistema::$caminhoDiretorio.Sistema::$caminhoDataFAQ;
			$lI->caminhoLeitura = Sistema::$caminhoURL.Sistema::$caminhoDataFAQ;
			$img 	= $t->getImagem();
			$lI->inserir($img);
			$t->setImagem($img);
			
		}
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::CATEGORIA,	$t->getIdCategoria(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 		$t->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM,	$t->getImagem()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TITULO, 	$t->titulo, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::TEXTO, 	$t->texto, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 	$t->ordem, $where);		
		
	}
	
	public function deletar(Pergunta $t){
		
		parent::deletar($t);
		
		$where = "WHERE ".self::ID." = '".$t->getId()."'";
		
		$lI = new ListaImagens;
		
		$lI->deletar($t->getImagem());
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>