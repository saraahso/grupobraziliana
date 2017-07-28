<?php

importar("Geral.Lista");
importar("LojaVirtual.Produtos.ProdutoMarca");

class ListaProdutoMarcas extends Lista {
	
	const ID 					= 'id';
	const NOME 				= 'nome';
	const ENDERECOURL	= 'enderecourl';
	const DESCRICAO 	= 'descricao';
	const IMAGEM			= 'imagem';
	const DISPONIVEL	= 'disponivel';

	const VALOR_DISPONIVEL_TRUE		= 1;
	const VALOR_DISPONIVEL_FALSE	= 0;
	const VALOR_ROUPA_TRUE				= 1;
	const VALOR_ROUPA_FALSE			  = 0;
	
	public function __construct(){
		
		parent::__construct('produtos_marcas');
		
	}
	
	public function listar($ordem = "ASC", $campo = self::ID){
		
		$info = parent::listar($ordem, $campo);
		
		if(!empty($info)){ 	
		
			parent::resgatarObjetos($info);
		
			$temp = new ProdutoMarca($info[self::ID]);
			$temp->nome 		= $info[self::NOME];
			$temp->descricao 	= $info[self::DESCRICAO];
			$temp->enderecoURL	= $info[self::ENDERECOURL];
			$temp->disponivel	= $info[self::DISPONIVEL] == self::VALOR_DISPONIVEL_TRUE ? true : false;
			
			if(!empty($info[self::IMAGEM])){ 
			
				$temp->setImagem(new Image(new Arquivos(Sistema::$caminhoURL.Sistema::$caminhoDataProdutoMarcas.$info[self::IMAGEM])));
			
			}
			
			$temp->setURL($info[parent::URL]);
			
			return $temp;
		
		}
		
	}
	
	public function inserir(ProdutoMarca &$m){

		parent::inserir($m);

		$disponivel = $m->disponivel ? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$roupa = $m->roupa ? self::VALOR_ROUPA_TRUE : self::VALOR_ROUPA_FALSE;

		if(!empty($m->getImagem()->url)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoMarcas, $id);
		}else
			$imagem = '';
		
		if($m->getId() != '')
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::ID.", ".self::NOME.", ".self::URL.", ".self::IMAGEM.",".self::DESCRICAO.", ".self::ENDERECOURL.", ".self::DISPONIVEL.") VALUES('".$m->getId()."','".$m->nome."','".$m->getURL()->getId()."','".$imagem."',\"".str_replace('"', '\'', $m->descricao)."\",'".$m->enderecoURL."','".$disponivel."')");
		else
			$this->con->executar("INSERT INTO ".Sistema::$BDPrefixo.$this->tabela."(".self::NOME.", ".self::URL.", ".self::IMAGEM.",".self::DESCRICAO.", ".self::ENDERECOURL.", ".self::DISPONIVEL.") VALUES('".$m->nome."','".$m->getURL()->getId()."','".$imagem."',\"".str_replace('"', '\'', $m->descricao)."\",'".$m->enderecoURL."','".$disponivel."')");
		
		$id = $this->con->getId();
		
		$class 	= __CLASS__;
		$l 		= new $class;
		$l->condicoes('', $id, self::ID);
		
		$m = $l->listar();
		
		parent::alterar($m);
		
	}
	
	public function alterar(ProdutoMarca $m){
		
		parent::alterar($m);
		
		$disponivel = $m->disponivel ? self::VALOR_DISPONIVEL_TRUE : self::VALOR_DISPONIVEL_FALSE;
		$roupa = $m->roupa ? self::VALOR_ROUPA_TRUE : self::VALOR_ROUPA_FALSE;

		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::NOME, 			$m->nome, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::URL, 			$m->getURL()->getId(), $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DESCRICAO, 	$m->descricao, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::ENDERECOURL, 	$m->enderecoURL, $where);
		$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::DISPONIVEL, 	$disponivel, $where);
		
		if(!empty($m->getImagem()->url)){
			$m->getImagem()->open();
			$imagem = $m->getImagem()->saveImage(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoMarcas, $m->getId());
			$this->con->alterar(Sistema::$BDPrefixo.$this->tabela, self::IMAGEM, 	$imagem, $where);
		}		
		
	}
	
	public function deletar(ProdutoMarca $m){
		
		parent::deletar($m);
		
		$where = "WHERE ".self::ID." = '".$m->getId()."'";
		
		Arquivos::__DeleteArquivo(Sistema::$caminhoDiretorio.Sistema::$caminhoDataProdutoMarcas.$m->getImagem()->nome.'.'.$m->getImagem()->extensao);
		
		$this->con->deletar(Sistema::$BDPrefixo.$this->tabela, $where);
		
	}
	
}	

?>