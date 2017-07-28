<?php

importar("Geral.Lista");
importar("LojaVirtual.Categorias.ProdutoCategoria");

class ListaProdutoCategorias extends Lista {
	
	const ID 						= 'id';
	const CATEGORIAPAI				= 'categoriapai';
	const NOME 						= 'nome';
	const ORDEM 					= 'ordem';
	const DISPONIVEL 				= 'disponivel';
	const VISAOUNICA 				= 'visaounica';
	const HOME 						= 'home';
	const COR						= 'cor';
	const DESCRICAOPEQUENA			= 'descricaopequena';
	const DESCRICAO 				= 'descricao';
	const IMAGEM					= 'imagem';
	const SUBREFERENCIA				= 'subreferencia';
	
	const VALOR_DISPONIVEL_TRUE 	= 1;
	const VALOR_DISPONIVEL_FALSE 	= 0;
	const VALOR_VISAOUNICA_TRUE 	= 1;
	const VALOR_VISAOUNICA_FALSE 	= 0;
	const VALOR_HOME_TRUE 			= 1;
	const VALOR_HOME_FALSE 			= 0;
	
	public function __construct(){
		
		parent::__construct('produtos_categorias');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			parent::resgatarObjetos($info);
		
			$temp = new ProdutoCategoria($info[self::ID]);
			$temp->nome 			= $info[self::NOME];
			$temp->ordem 			= $info[self::ORDEM];
			$temp->disponivel 		= $info[self::DISPONIVEL] 	== self::VALOR_DISPONIVEL_TRUE ? true : false;
			$temp->visaoUnica 		= $info[self::VISAOUNICA] 	== self::VALOR_VISAOUNICA_TRUE ? true : false;
			$temp->home 			= $info[self::HOME] 		== self::VALOR_HOME_TRUE ? true : false;
			$temp->descricaoPequena	= $info[self::DESCRICAOPEQUENA];
			$temp->descricao 		= $info[self::DESCRICAO];
			$temp->subreferencia	= $info[self::SUBREFERENCIA];
			$temp->cor				= $info[self::COR];
			
			if(!empty($info[self::IMAGEM])){ 
			
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataProdutoCategorias.$info[self::IMAGEM])));
			
			}
			
			$temp->setURL($info[parent::URL]);
			
			$temp->setIdCategoriaPai($info[self::CATEGORIAPAI]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoCategoria &$pC){

		parent::inserir($pC);
		
		if(!empty($pC->getImagem()->url)){
			$pC->getImagem()->open();
			$imagem = $pC->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCategorias, $id);
		}else
			$imagem = '';
		
		$disponivel = $pC->disponivel 	? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$visaoUnica = $pC->visaoUnica 	? self::VALOR_VISAOUNICA_TRUE : self::VALOR_VISAOUNICA_FALSE;
		$home 		= $pC->home 		? self::VALOR_HOME_TRUE : self::VALOR_HOME_FALSE;
		
		$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::URL.", ".self::IMAGEM.", ".self::ORDEM.", ".self::SUBREFERENCIA.", ".self::DISPONIVEL.", ".self::VISAOUNICA.", ".self::HOME.", ".self::COR.", ".self::DESCRICAOPEQUENA.",".self::DESCRICAO.", ".self::CATEGORIAPAI.") VALUES('".$pC->nome."','".$pC->getURL()->getId()."','".$imagem."','".$pC->ordem."','".$pC->subreferencia."','".$disponivel."','".$visaoUnica."','".$pC->cor."','".$home."',\"".str_replace('"', '\'', $pC->descricaoPequena)."\",\"".str_replace('"', '\'', $pC->descricao)."\",'".$pC->getIdCategoriaPai()."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$pC = $l->listar();
		
		parent::alterar($pC);
		
	}
	
	public function alterar(ProdutoCategoria $pC){
		
		parent::alterar($pC);
		
		$where = "WHERE ".self::ID." = '".$pC->getId()."'";
		
		$disponivel = $pC->disponivel ? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$visaoUnica = $pC->visaoUnica ? self::VALOR_VISAOUNICA_TRUE : self::VALOR_VISAOUNICA_FALSE;
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 				$pC->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 				$pC->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ORDEM, 			$pC->ordem, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DISPONIVEL, 		$pC->disponivel, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::VISAOUNICA, 		$pC->visaoUnica, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::HOME, 				$pC->home, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAOPEQUENA, 	$pC->descricaoPequena, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO, 		$pC->descricao, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::SUBREFERENCIA,		$pC->subreferencia, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::COR,				$pC->cor, $where);
		
		if(!empty($pC->getImagem()->url)){
			$pC->getImagem()->open();
			$imagem = $pC->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCategorias, $pC->getId());
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 	$imagem, $where);
		}		
		
	}
	
	public function deletar(ProdutoCategoria $pC){
		
		parent::deletar($pC);
		
		$where = "WHERE ".self::ID." = '".$pC->getId()."'";
		
		if($pC->getSubCategorias()->getTotal() > 0)
			throw new Exception("Está categoria possui subcategorias, não pode ser removida!");
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoCategorias.$pC->getImagem()->nome.'.'.$pC->getImagem()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>